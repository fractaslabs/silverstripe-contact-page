<?php

/**
 * Defines the ContactPage page type
 */

class ContactPage extends Page
{

    private static $add_action = 'a Contact Page';
    private static $allowed_children = "none";

    private static $db = array(
        'MailFrom' => 'Varchar(255)',
        'MailTo' => 'Varchar(255)',
        'MailSubject' => 'Varchar(255)',
        'SuccessTitle' => 'Varchar(255)',
        'SuccessText' => 'Text',
        'SideContent' => 'HTMLText'
    );

    private static $has_one = array(
        'Image' => 'Image'
    );

    private static $defaults = array(
        'MailFrom' => 'you@example.com',
        'MailTo' => 'you@example.com',
        'MailSubject' => 'New contact form inquiry',
        'SuccessTitle' => 'Thank you for submitting the contact form!',
        'SuccessText' => 'We will contact you back asap! Cheers :)'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab("Root.Mail", new TextField('MailFrom', 'Mail From'));
        $fields->addFieldToTab("Root.Mail", new TextField('MailTo', 'Mail To'));
        $fields->addFieldToTab("Root.Mail", new TextField('MailSubject', 'Mail Subject'));

        $fields->addFieldToTab("Root.OnSubmission", new TextField('SuccessTitle', 'Success Title'));
        $fields->addFieldToTab("Root.OnSubmission", new TextareaField('SuccessText', 'Success Text'));

        $fields->addFieldToTab("Root.Images", new UploadField("Image", "Image"));
        $fields->addFieldToTab("Root.SideContent", new HtmlEditorField('SideContent', 'Side Content'));

        return $fields;
    }
}


class ContactPage_Controller extends Page_Controller
{

    public static $allowed_actions = array(
        'success',
        'error'
    );

    public function init()
    {
        parent::init();
    }

    public function ErrorMessage()
    {
        return _t('ContactPage.ERRORMESSAGE', 'Something went wrong with form submission. Please call us or try again later.');
    }
}
