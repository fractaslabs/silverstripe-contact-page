<?php

namespace Fractas\ContactPage;

use Page;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use  SilverStripe\Forms\TreeDropdownField;

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

    private static $use_terms_page = false;
    private static $use_privacy_page = false;

    private static $db = [
        'MailFrom' => 'Varchar(255)',
        'MailTo' => 'Varchar(255)',
        'MailSubject' => 'Varchar(255)',
        'SuccessTitle' => 'Varchar(255)',
        'SuccessText' => 'Text',
        'SideContent' => 'HTMLText',
    ];

    private static $has_one = [
        'Image' => Image::class,
        'TermsPage' => SiteTree::class,
        'PrivacyPage' => SiteTree::class,
    ];

    private static $table_name = 'ContactPage';

    private static $owns = ['Image'];

    private static $default_sort = 'ID DESC';

    private static $defaults = [
        'MailFrom' => 'you@example.com',
        'MailTo' => 'you@example.com',
        'MailSubject' => 'New contact form inquiry',
        'SuccessTitle' => 'Thank you for submitting the contact form!',
        'SuccessText' => 'We will contact you back asap! Cheers :)',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Mail', TextField::create('MailFrom', 'Mail From'));
        $fields->addFieldToTab('Root.Mail', TextField::create('MailTo', 'Mail To'));
        $fields->addFieldToTab('Root.Mail', TextField::create('MailSubject', 'Mail Subject'));

        $fields->addFieldToTab('Root.SideContent', HtmlEditorField::create('SideContent', 'Side Content'));

        if ($this->config()->get('use_terms_page')) {
            $fields->addFieldToTab(
                'Root.TermsPages',
                $treedropdownfield = TreeDropdownField::create('TermsPageID', 'Choose a Terms and Condisions Page', SiteTree::class)
            );
        }

        if ($this->config()->get('use_privacy_page')) {
            $fields->addFieldToTab(
                'Root.TermsPages',
                $treedropdownfield = TreeDropdownField::create('PrivacyPageID', 'Choose a Security and Privacy Page', SiteTree::class)
            );
        }

        $fields->addFieldToTab('Root.OnSubmission', TextField::create('SuccessTitle', 'Success Title'));
        $fields->addFieldToTab('Root.OnSubmission', TextareaField::create('SuccessText', 'Success Text'));

        $fields->addFieldToTab('Root.Images', UploadField::create('Image', 'Image'));

        return $fields;
    }
}
