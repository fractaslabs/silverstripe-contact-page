<?php

namespace Fractas\ContactPage;

use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\ReadOnlyField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;

class ContactInquiry extends DataObject implements PermissionProvider
{
    private static $db = [
        'FirstName' => 'Varchar(255)',
        'LastName' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
        'Phone' => 'Varchar(255)',
        'Locale' => 'Varchar(255)',
        'Ref' => 'Varchar(255)',
        'Description' => 'Text',
        'AdminComment' => 'Text',
        'Status' => "Enum('New, Opened, Answered, Spam, Archived', 'New')",
        'Sort' => 'Int',
    ];

    private static $has_one = [];

    private static $casting = [
        'Title' => 'Varchar(255)',
    ];

    private static $defaults = [
        'Status' => 'New',
    ];

    private static $singular_name = 'Contact Inquiry';
    private static $plural_name = 'Contact Inquiries';
    private static $default_sort = 'Sort, ID Desc';

    private static $searchable_fields = [
        'FirstName',
        'LastName',
        'Email',
        'Phone',
        'Status',
    ];

    private static $summary_fields = [
        'FullName',
        'Email',
        'Status',
        'Locale',
        'Created',
    ];

    private static $field_labels = [
        'FullName' => 'Full Name',
        'Sort' => 'Sort Index',
    ];

    public function getFullName()
    {
        return $this->FirstName.' '.$this->LastName;
    }

    public function FullName()
    {
        return $this->getFullName();
    }

    public function getTitle()
    {
        return $this->getFullName().' / '.$this->Status.' / '.$this->Created;
    }

    public function Title()
    {
        return $this->getTitle();
    }

    public static function get_contactinquiry_status_options()
    {
        return singleton(self::class)->dbObject('Status')->enumValues(false);
    }

    public function getCMSFields()
    {
        $fields = new FieldList(new TabSet('Root', new Tab('Main')));
        $fields->removeByName('Sort');

        $dropFieldStatus = new DropdownField('Status', 'Status', self::get_contactinquiry_status_options());

        $tabName = singleton(self::class)->singular_name();
        $fields->addFieldsToTab('Root.Main', [
            new HeaderField('HeaderDetails', "${tabName} details"),
            $dropFieldStatus,
            new ReadOnlyField('FirstName', 'First Name'),
            new ReadOnlyField('LastName', 'Last Name'),
            new ReadOnlyField('Email', 'Email'),
            new ReadOnlyField('Phone', 'Phone'),
            new ReadOnlyField('Ref', 'Referal'),
            new ReadOnlyField('Locale', 'Locale'),
            new ReadOnlyField('Created', 'Created'),
            new ReadOnlyField('Description', 'Description'),
            new TextareaField('AdminComment', 'Admin Comment'),
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
    }

    public function canView($member = null, $context = [])
    {
        return true;
    }

    public function canEdit($member = null, $context = [])
    {
        return Permission::check('CONTACTINQUIRY_EDIT');
    }

    public function canDelete($member = null, $context = [])
    {
        return Permission::check('CONTACTINQUIRY_DELETE');
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CONTACTINQUIRY_CREATE');
    }

    public function providePermissions()
    {
        return array(
            'CONTACTINQUIRY_EDIT' => array(
                'name' => _t(
                    __CLASS__ . '.EditPermissionLabel',
                    'Edit a Contact Inquiry'
                ),
                'category' => _t(
                    __CLASS__ . '.Category',
                    'Contact Inquiries'
                ),
            ),
            'CONTACTINQUIRY_DELETE' => array(
                'name' => _t(
                    __CLASS__ . '.DeletePermissionLabel',
                    'Delete a Contact Inquiry'
                ),
                'category' => _t(
                    __CLASS__ . '.Category',
                    'Contact Inquiries'
                ),
            ),
            'CONTACTINQUIRY_CREATE' => array(
                'name' => _t(
                    __CLASS__ . '.CreatePermissionLabel',
                    'Create a Contact Inquiry'
                ),
                'category' => _t(
                    __CLASS__ . '.Category',
                    'Contact Inquiries'
                ),
            )
        );
    }
}
