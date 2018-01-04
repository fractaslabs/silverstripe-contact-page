<?php

namespace Fractas\ContactPage;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Page;

/**
 * Defines the ContactPage page type.
 */
class ContactPage extends Page
{
    private static $add_action = 'a Contact Page';
    private static $allowed_children = 'none';
    private static $singular_name = 'Contact Page';
    private static $plural_name = 'Contact Pages';
    private static $description = 'Page with contact Form and contact details';

    private static $db = array(
        'MailFrom' => 'Varchar(255)',
        'MailTo' => 'Varchar(255)',
        'MailSubject' => 'Varchar(255)',
        'SuccessTitle' => 'Varchar(255)',
        'SuccessText' => 'Text',
        'SideContent' => 'HTMLText',
    );

    private static $has_one = array(
        'Image' => Image::class,
    );
    
    private static $owns = ['Image'];

    private static $default_sort = 'ID DESC';

    private static $defaults = array(
        'MailFrom' => 'you@example.com',
        'MailTo' => 'you@example.com',
        'MailSubject' => 'New contact form inquiry',
        'SuccessTitle' => 'Thank you for submitting the contact form!',
        'SuccessText' => 'We will contact you back asap! Cheers :)',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Mail', new TextField('MailFrom', 'Mail From'));
        $fields->addFieldToTab('Root.Mail', new TextField('MailTo', 'Mail To'));
        $fields->addFieldToTab('Root.Mail', new TextField('MailSubject', 'Mail Subject'));

        $fields->addFieldToTab('Root.OnSubmission', new TextField('SuccessTitle', 'Success Title'));
        $fields->addFieldToTab('Root.OnSubmission', new TextareaField('SuccessText', 'Success Text'));

        $fields->addFieldToTab('Root.Images', new UploadField('Image', 'Image'));
        $fields->addFieldToTab('Root.SideContent', new HtmlEditorField('SideContent', 'Side Content'));

        return $fields;
    }
}
