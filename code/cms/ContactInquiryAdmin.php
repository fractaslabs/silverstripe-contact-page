<?php

class ContactInquiryAdmin extends ModelAdmin {

	private static $managed_models = array(
		'ContactInquiry'
	);
	
	private static $url_segment = 'contactinquiry';
	private static $menu_title = 'Contact Inq.';

	public $showImportForm = false;
	
	public function init(){
		parent::init();
	}


}
