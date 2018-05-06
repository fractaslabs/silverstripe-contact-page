<?php

namespace Fractas\ContactPage;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Email\Email;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;

class ContactInquiryForm extends Form
{
    protected $currController = false;

    public function __construct(Controller $controller, $name)
    {
        $this->currController = $controller;
        $termsPage = $this->currController->TermsPage();
        $privacyPage = $this->currController->PrivacyPage();
        $termsFields = LiteralField::create('', '');

        if ($termsPage->exists() && ContactPage::config()->get('use_terms_page')) {
            $termsFields = CompositeField::create([
                                    CheckboxField::create('ConfirmTerms')
                                        ->setTitle(_t(__CLASS__.'.CONFIRMTERMSLABEL', 'Check here to indicate that you have read and agree to the terms'))
                                        ->setDescription(_t(
                                            __CLASS__.'.CONFIRMTERMSDESCRIPTION',
                                            'By clicking Send, you agree to our <a href="{TermsPageLink}" target="_blank" title="{TermsPageTitle}">{TermsPageTitle}</a> and that you have read our <a href="{PrivacyPageLink}" target="_blank" title="{PrivacyPageTitle}">{PrivacyPageTitle}</a>',
                                            [
                                                'TermsPageLink' => $termsPage->Link(),
                                                'TermsPageTitle' => $termsPage->getTitle(),
                                                'PrivacyPageLink' => $privacyPage->Link(),
                                                'PrivacyPageTitle' => $privacyPage->getTitle(),
                                            ]
                                        )),
                                ])->addExtraClass('terms-check');
        }

        $fields = FieldList::create(
            [
                TextField::create('FirstName')
                    ->setTitle(_t(__CLASS__.'.FIRSTNAME', 'First Name'))
                    ->setAttribute('placeholder', _t(__CLASS__.'.FIRSTNAMEPLACEHOLDER', 'Your First Name'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                TextField::create('LastName')
                    ->setTitle(_t(__CLASS__.'.LASTNAME', 'Last Name'))
                    ->setAttribute('placeholder', _t(__CLASS__.'.LASTNAMEPLACEHOLDER', 'Your Last Name'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                EmailField::create('Email')
                    ->setTitle(_t(__CLASS__.'.EMAIL', 'Email'))
                    ->setAttribute('placeholder', _t(__CLASS__.'.EMAILPLACEHOLDER', 'Your Email address'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                TextField::create('Phone')
                    ->setTitle(_t(__CLASS__.'.PHONE', 'Phone'))
                    ->setAttribute('placeholder', _t(__CLASS__.'.PHONEPLACEHOLDER', 'Your Phone number'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                TextareaField::create('Description')
                    ->setTitle(_t(__CLASS__.'.DESCRIPTION', 'Message'))
                    ->setAttribute('placeholder', _t(__CLASS__.'.DESCRIPTIONPLACEHOLDER', 'Your Message for us'))
                    ->setRows(6),
                $termsFields,
                CompositeField::create([
                    TextField::create('Url')
                        ->setTitle('')
                        ->setMaxLength(55)
                        ->setAttribute('style', 'display:none;')
                        ->setAttribute('placeholder', _t(__CLASS__.'.URLPLACEHOLDER', 'Url'))
                        ->setAttribute('autocomplete', 'off'),

                    TextareaField::create('Comment')
                        ->setTitle('')
                        ->setAttribute('style', 'display:none;')
                        ->setAttribute('placeholder', _t(__CLASS__.'.COMMENTPLACEHOLDER', 'Comment'))
                        ->setRows(6),
                ])->addExtraClass('hidden'),
                HiddenField::create('Ref')->setValue($this->currController->Title),
                HiddenField::create('Locale')->setValue($this->currController->Locale),
            ]
        );

        $actions = FieldList::create(
            FormAction::create('dosave', _t(__CLASS__.'.APPLY', 'Send'))->setUseButtonTag(true)
        );

        $validator = RequiredFields::create('FirstName', 'LastName', 'Email', 'Description');
        if ($termsPage->exists()) {
            $validator = RequiredFields::create('FirstName', 'LastName', 'Email', 'Description', 'ConfirmTerms');
        }

        parent::__construct($controller, $name, $fields, $actions, $validator);

        if (null !== $this->extend('updateFields', $fields)) {
            $this->setFields($fields);
        }
        if (null !== $this->extend('updateActions', $actions)) {
            $this->setActions($actions);
        }

        $session = $this->currController->getRequest()->getSession();
        $oldData = $session->get("FormInfo.{$this->FormName()}.data");
        if ($oldData && (is_array($oldData) || is_object($oldData))) {
            $this->loadDataFrom($oldData);
        }
        $this->extend('updateContactInquiryForm', $this);
    }

    /**
     * Form action handler for ContactInquiryForm.
     *
     * @param array $data The form request data submitted
     * @param Form  $form The {@link Form} this was submitted on
     */
    public function dosave(array $data, Form $form, HTTPRequest $request)
    {
        $SQLData = Convert::raw2sql($data);
        $attrs = $form->getAttributes();

        // Most probably spam - terminate silently
        if ('' !== $SQLData['Comment'] || '' !== $SQLData['Url']) {
            $this->currController->redirect($this->currController->Link().'success');

            return;
        }

        $item = ContactInquiry::create();
        $form->saveInto($item);
        $item->write();

        $mailFrom = $this->currController->MailFrom ? $this->currController->MailFrom : $SQLData['Email'];
        $mailTo = $this->currController->MailTo ? $this->currController->MailTo : Email::getAdminEmail();
        $mailSubject = $this->currController->MailSubject ?
            ($this->currController->MailSubject.' - '.$SQLData['Ref']) :
            _t(__CLASS__.'.SUBJECT', '[web] New contact inquiry - ').' '.$data['Ref'];

        /**
         * Send Email notification to site administrator or
         * to email specified in MailTo field.
         */
        $email = Email::create($mailFrom, $mailTo, $mailSubject);
        $email->setReplyTo($SQLData['Email']);
        $email->setHTMLTemplate('ContactInquiryEmail');
        $email->setData($SQLData);
        $email->send();

        /*
         * Handle validation messages
         * Error message is presented on ContactPage.ss layout as
         * a variable $ErrorMessage
         */
        if ($email->send()) {
            $this->currController->redirect($this->currController->Link().'success');
        } else {
            $this->currController->redirect($this->currController->Link().'error');
        }

        return false;
    }

    /**
     * saves the form into session.
     *
     * @param array $data - data from form
     */
    public function saveDataToSession()
    {
        $data = $this->getData();
        $session = $this->currController->getRequest()->getSession();
        $session->set("FormInfo.{$this->FormName()}.data", $data);
    }
}

class ContactInquiryForm_Validator extends RequiredFields
{
    public function php($data)
    {
        $this->form->saveDataToSession();

        return parent::validate($data);
    }
}
