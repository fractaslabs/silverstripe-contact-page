<?php

namespace Fractas\ContactPage;

use Fractas\ContactPage\ContactInquiry;
use SilverStripe\Admin\ModelAdmin;

class ContactInquiryAdmin extends ModelAdmin
{
    private static $managed_models = array(
        ContactInquiry::class,
    );

    private static $url_segment = 'contactinquiry';
    private static $menu_title = 'Contact Inq.';

    public $showImportForm = false;

    public function init()
    {
        parent::init();
    }
}
