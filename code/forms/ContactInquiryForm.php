<?php

class ContactInquiryForm extends BootstrapForm
{

    protected $currController = false;

    public function __construct(Controller $controller, $name)
    {
        $this->currController = $controller;

        $fields = new FieldList(
            array(
                TextField::create("FirstName")
                    ->setTitle(_t("ContactPage.FIRSTNAME", "First Name"))
                    ->addPlaceholder(_t("ContactPage.FIRSTNAMEPLACEHOLDER", "Your First Name"))
                    ->setMaxLength(55)
                    ->setAttribute("autocomplete", "off"),

                TextField::create("LastName")
                    ->setTitle(_t("ContactPage.LASTNAME", "Last Name"))
                    ->addPlaceholder(_t("ContactPage.LASTNAMEPLACEHOLDER", "Your Last Name"))
                    ->setMaxLength(55)
                    ->setAttribute("autocomplete", "off"),

                EmailField::create("Email")
                    ->setTitle(_t("ContactPage.EMAIL", "Email"))
                    ->addPlaceholder(_t("ContactPage.EMAILPLACEHOLDER", "Your Email address"))
                    ->setMaxLength(55)
                    ->setAttribute("autocomplete", "off"),

                TextField::create("Phone")
                    ->setTitle(_t("ContactPage.PHONE", "Phone"))
                    ->addPlaceholder(_t("ContactPage.PHONEPLACEHOLDER", "Your Phone number"))
                    ->setMaxLength(55)
                    ->setAttribute("autocomplete", "off"),

                TextareaField::create("Description")
                    ->setTitle(_t("ContactPage.DESCRIPTION", "Message"))
                    ->addPlaceholder(_t("ContactPage.DESCRIPTIONPLACEHOLDER", "Your Message for us"))
                    ->setRows(6),

                TextField::create("Url")
                    ->setTitle("")
                    ->addPlaceholder(_t("ContactPage.URL", "Url"))
                    ->setMaxLength(55)
                    ->setAttribute("autocomplete", "off"),

                TextareaField::create("Comment")
                    ->setTitle("")
                    ->addPlaceholder(_t("ContactPage.COMMENT", "Comment"))
                    ->setRows(6),

                HiddenField::create("Ref")->setValue($controller->Title),
                HiddenField::create("Locale")->setValue($controller->Locale)
            )
        );

        $actions = new FieldList(
            FormAction::create('dosave', _t("ContactPage.APPLY", "Send"))->setStyle("success")
        );

        $validator = ZenValidator::create();
        $validator->addRequiredFields(array('FirstName', 'LastName', 'Email', 'Description'));
        $validator->setConstraint('Email', Constraint_type::create('email'));
        $validator->setConstraint('Description', Constraint_length::create('min', 10));

        parent::__construct($controller, $name, $fields, $actions, $validator);

        if ($this->extend('updateFields', $fields) !== null) {
            $this->setFields($fields);
        }
        if ($this->extend('updateActions', $actions) !== null) {
            $this->setActions($actions);
        }
        // if($this->extend('updateValidator', $requiredFields) !== null) {$this->setValidator($requiredFields);}

        $oldData = Session::get("FormInfo.{$this->FormName()}.data");
        if ($oldData && (is_array($oldData) || is_object($oldData))) {
            $this->loadDataFrom($oldData);
        }
        $this->extend('updateContactInquiryForm', $this);
    }

    /**
     * Form action handler for ContactInquiryForm.
     *
     * @param array $data The form request data submitted
     * @param Form $form The {@link Form} this was submitted on
     */
    public function dosave(array $data, Form $form, SS_HTTPRequest $request)
    {
        $SQLData = Convert::raw2sql($data);
        $attrs = $form->getAttributes();

        if ($SQLData['Comment'] != '' || $SQLData['Url'] != '') {
            // most probably spam - terminate silently
            Director::redirect(Director::baseURL(). $this->URLSegment . "/success");
            return;
        }

        $item = new ContactInquiry();
        $form->saveInto($item);
        // $form->sessionMessage(_t("ContactPage.FORMMESSAGEGOOD", "Your inquiry has been submitted. Thanks!"), 'good');
        $item->write();

        $mailFrom =  $this->currController->MailFrom ? $this->currController->MailFrom : $SQLData['Email'];
        $mailTo = $this->currController->MailTo ? $this->currController->MailTo : Email::getAdminEmail();
        $mailSubject = $this->currController->MailSubject ? ($this->currController->MailSubject  .' - '. $SQLData['Ref']) : _t('ContactPage.SUBJECT', '[web] New contact inquiry - ') .' '. $data['Ref'];

        $email = new Email($mailFrom, $mailTo, $mailSubject);
        $email->replyTo($SQLData['Email']);
        $email->setTemplate("ContactInquiry");
        $email->populateTemplate($SQLData);
        $email->send();

        // $this->controller->redirectBack();
        if ($email->send()) {
            $this->controller->redirect($this->controller->Link() . "success");
        } else {
            $this->controller->redirect($this->controller->Link() . "error");
        }
        return false;
    }

    /**
     * saves the form into session
     * @param Array $data - data from form.
     */
    public function saveDataToSession()
    {
        $data = $this->getData();
        Session::set("FormInfo.{$this->FormName()}.data", $data);
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
