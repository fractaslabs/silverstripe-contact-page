<?php

class ContactInquiry Extends DataObject {

	private static $db = array(
		"FirstName" => "Varchar(255)",
		"LastName" => "Varchar(255)",
		"Email" => "Varchar(255)",
		"Phone" => "Varchar(255)",
		"Locale" => "Varchar(255)",
		"Description" => "Text",
		"AdminComment" => "Text",
		'Status' => "Enum('New, Opened, Answered, Spam, Archived', 'New')",
		"Sort" => "Int"
	);

	private static $has_one = array(
	);

	private static $casting = array(
		"Title" => "Varchar(255)"
	);
	
	private static $defaults = array(
		'Status' => 'New'
	);

	private static $singular_name = 'Contact Inquiry';
	private static $plural_name = 'Contact Inquiries';
	private static $default_sort = 'Sort, ID Desc';

	public static $searchable_fields = array(
		"FirstName",
		"LastName",
		"Email",
		"Phone",
		"Status"
	);

	public static $summary_fields = array(
		"FullName",
		"Email",
		"Status",
		"Locale",
		"Created"
	);

	public static $field_labels = array(
		"FullName" => "Full Name",
		"Sort" => "Sort Index",
	);

	public function getFullName() {
		return $this->FirstName .' '. $this->LastName;
	}

	public function FullName() {
		return $this->getFullName();
	}
	public function getTitle() {
		return $this->getFullName() .' / '. $this->Status .' / '. $this->Created;
	}

	public function Title() {
		return $this->getTitle();
	}

	public static function get_contactinquiry_status_options() {
		return singleton('ContactInquiry')->dbObject('Status')->enumValues(false);
	}

	public static function get_stafpages() {
		return StaffPage::get()->map();
	}

    public function canView($member = null) {
        if ($member == null && !$member = Member::currentUser()) return false;
        if(($member->inGroup('administrators', true) || $member->inGroup('content-authors', true))) return true;
        if($member->inGroup($this->Group()->ID, true)) return true;

        return false;
    }

    public function canEdit($member = false) {
        if ($member == null && !$member = Member::currentUser()) return false;
        if(($member->inGroup('administrators', true) || $member->inGroup('content-authors', true))) return true;
        if($member->inGroup($this->Group()->ID, true)) return true;

        return false;
    }

    public function canDelete($member = null) {
        return false;
    }
    
	public function getCMSFields(){
		$fields = new FieldList(new TabSet('Root', new Tab('Main')));
		$fields->removeByName("Sort");

		$dropFieldStatus = new DropdownField('Status', 'Status', self::get_contactinquiry_status_options());

		$tabName = singleton("ContactInquiry")->singular_name();
		$fields->addFieldsToTab('Root.Main', array(
			new HeaderField("$tabName details"),
			$dropFieldStatus,			
			new ReadOnlyField('FirstName', 'First Name'),
			new ReadOnlyField('LastName', 'Last Name'),
			new ReadOnlyField('Email', 'Email'),
			new ReadOnlyField('Phone', 'Phone'),
			new ReadOnlyField('Locale', 'Locale'),
			new ReadOnlyField('Created', 'Created'),
			new ReadOnlyField('Description', 'Description'),
			new TextareaField('AdminComment', 'Admin Comment')
		));

		return $fields;
	}

	function onBeforeWrite() {
		parent::onBeforeWrite();
		
	}

}
