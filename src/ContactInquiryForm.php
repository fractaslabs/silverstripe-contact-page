<?php

namespace Fractas\ContactPage;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;
use SilverStripe\Control\Director;

class ContactInquiryForm extends Form
{
    protected $currController = false;

    public function __construct(Controller $controller, $name)
    {
        $this->currController = $controller;

        $fields = new FieldList(
            array(
                TextField::create('FirstName')
                    ->setTitle(_t('ContactPage.FIRSTNAME', 'First Name'))
                    ->setAttribute('placeholder', _t('ContactPage.FIRSTNAMEPLACEHOLDER', 'Your First Name'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                TextField::create('LastName')
                    ->setTitle(_t('ContactPage.LASTNAME', 'Last Name'))
                    ->setAttribute('placeholder', _t('ContactPage.LASTNAMEPLACEHOLDER', 'Your Last Name'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                EmailField::create('Email')
                    ->setTitle(_t('ContactPage.EMAIL', 'Email'))
                    ->setAttribute('placeholder', _t('ContactPage.EMAILPLACEHOLDER', 'Your Email address'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                TextField::create('Phone')
                    ->setTitle(_t('ContactPage.PHONE', 'Phone'))
                    ->setAttribute('placeholder', _t('ContactPage.PHONEPLACEHOLDER', 'Your Phone number'))
                    ->setAttribute('autocomplete', 'off')
                    ->setMaxLength(55),

                TextareaField::create('Description')
                    ->setTitle(_t('ContactPage.DESCRIPTION', 'Message'))
                    ->setAttribute('placeholder', _t('ContactPage.DESCRIPTIONPLACEHOLDER', 'Your Message for us'))
                    ->setRows(6),

                TextField::create('Url')
                    ->setTitle('')
                    ->setMaxLength(55)
                    ->setAttribute('style', 'display:none;')
                    ->setAttribute('placeholder', _t('ContactPage.URLPLACEHOLDER', 'Url'))
                    ->setAttribute('autocomplete', 'off'),

                TextareaField::create('Comment')
                    ->setTitle('')
                    ->setAttribute('style', 'display:none;')
                    ->setAttribute('placeholder', _t('ContactPage.COMMENTPLACEHOLDER', 'Comment'))
                    ->setRows(6),

                HiddenField::create('Ref')->setValue($this->currController->Title),
                HiddenField::create('Locale')->setValue($this->currController->Locale),
            )
        );

        $actions = new FieldList(
            FormAction::create('dosave', _t('ContactPage.APPLY', 'Send'))
        );

        $validator = new RequiredFields('FirstName', 'LastName', 'Email', 'Description');

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

        /*
         * Most probably spam - terminate silently
         */
        if ('' != $SQLData['Comment'] || '' != $SQLData['Url']) {
            Director::redirect(Director::baseURL().$this->URLSegment.'/success');

            return;
        }

        $item = new ContactInquiry();
        $form->saveInto($item);
        $item->write();

        $mailFrom = $this->currController->MailFrom ? $this->currController->MailFrom : $SQLData['Email'];
        $mailTo = $this->currController->MailTo ? $this->currController->MailTo : Email::getAdminEmail();
        $mailSubject = $this->currController->MailSubject ? ($this->currController->MailSubject.' - '.$SQLData['Ref']) : _t('ContactPage.SUBJECT', '[web] New contact inquiry - ').' '.$data['Ref'];

        /**
         * Send Email notification to site administrator or
         * to email specified in MailTo field.
         */
        $email = new Email($mailFrom, $mailTo, $mailSubject);
        $email->setReplyTo($SQLData['Email']);
        $email->setHTMLTemplate('ContactInquiry');
        $email->setData($SQLData);
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
