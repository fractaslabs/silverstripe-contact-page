<?php

namespace Fractas\ContactPage;

use SilverStripe\Admin\ModelAdmin;

class ContactInquiryAdmin extends ModelAdmin
{
    public $showImportForm = false;

    private static $managed_models = [
        ContactInquiry::class,
    ];

    private static $url_segment = 'contactinquiry';

    private static $menu_title = 'Contact Inq.';

    private static $menu_icon = 'font-icon-book';

    public function init()
    {
        parent::init();
    }
}
