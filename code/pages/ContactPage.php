<?php

/**
 * Defines the ContactPage page type
 */

class ContactPage extends Page {

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
	
	private static $defaults = array (
		'MailFrom' => 'you@example.com',
		'MailTo' => 'you@example.com',
		'MailSubject' => 'New contact form inquiry',
		'SuccessTitle' => 'Thank you for submitting the contact form!',
		'SuccessText' => 'We will contact you back asap! Cheers :)'
	);

	function getCMSFields() {
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


class ContactPage_Controller extends Page_Controller {
	
	public static $allowed_actions = array (
		'ContactForm',
		'sendContact',
		'success',
		'error'
	);

	function init(){
		parent::init();
	}
	
	function ErrorMessage(){
		return _t('ContactPage.ERRORMESSAGE', 'Something went wrong with form submission. Please call us or try again later.');	
	}

	function ContactForm() {
		$fields = new FieldList(			
			TextField::create("FirstName")
				->setTitle(_t("ContactPage.FIRSTNAME", "First Name"))
				->setMaxLength(55)
				->setAttribute("autocomplete", "off"),
			
			TextField::create("LastName")
				->setTitle(_t("ContactPage.LASTNAME", "Last Name"))
				->setMaxLength(55)
				->setAttribute("autocomplete", "off"),
			
			EmailField::create("Email")
				->setTitle(_t("ContactPage.EMAIL", "Email"))
				->setMaxLength(55)
				->setAttribute("autocomplete", "off"),

			TextField::create("Phone")
				->setTitle(_t("ContactPage.PHONE", "Phone"))
				->setMaxLength(55)
				->setAttribute("autocomplete", "off"),
			
			TextareaField::create("Description")
				->setTitle(_t("ContactPage.DESCRIPTION", "Description"))
				->setRows(6),

			TextField::create("Url")
				->setTitle(_t("ContactPage.URL", "Url"))
				->setMaxLength(55)
				->setAttribute("autocomplete", "off"),
			
			TextareaField::create("Comment")
				->setTitle(_t("ContactPage.COMMENT", "Comment"))
				->setRows(6),

			HiddenField::create("PageTitle", "PageTitle", $this->Title),
			HiddenField::create("Locale", "Locale", $this->dataRecord->Locale)
		);
		
		$actions = new FieldList(
			FormAction::create('sendContact','<i class="icon-ok icon-white"></i> ' . _t("ContactPage.APPLY","Send"))->setStyle("success")
		);
		$actions->useButtonTag = true;

		$validator = new RequiredFields(array('FirstName', 'LastName', 'Email', 'Description'));

		return BootstrapForm::create($this, 'ContactForm', $fields, $actions, $validator)
					->addExtraClass("validation-light")
					->setAttribute("style", "position: relative")
					->setLayout("vertical");
	}

	function sendContact($data, $form) {
		if( $data['Comment'] != '' || $data['Url'] != '' ){
			// most probably spam - terminate silently
			Director::redirect(Director::baseURL(). $this->URLSegment . "/success");
			return;
		}

		$item = new ContactInquiry();
		$form->saveInto($item);
        $form->sessionMessage(_t("ContactPage.FORMMESSAGEGOOD", "Your inquiry has beed submitted. Thanks!"), 'good');
		$item->write();

		$mailFrom =  $this->MailFrom ? $this->MailFrom : $data['Email'];
		$mailTo = $this->EmailTo ? $this->EmailTo : Email::getAdminEmail();			
		$mailSubject = $this->MailSubject ? ($this->MailSubject  .' - '. $data['PageTitle']) : _t('ContactPage.SUBJECT', '[CONTACT] New contact from web - ') .' '. $data['PageTitle'];
		
		$email = new Email($mailFrom, $mailTo, $mailSubject);
		$email->replyTo($data['Email']);		
		$email->setTemplate("ContactEmail");
		$email->populateTemplate($data);        

		if($email->send()){
			$this->redirect(Director::baseURL(). $this->URLSegment . "/success");
		} else {
			$this->redirect(Director::baseURL(). $this->URLSegment . "/error");
		}
		// return $this->redirectBack();
	}

	
}

?>