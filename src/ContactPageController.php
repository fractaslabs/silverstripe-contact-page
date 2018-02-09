<?php

namespace Fractas\ContactPage;

use PageController;
use SilverStripe\View\Requirements;

class ContactPageController extends PageController
{
    private static $allowed_actions = [
        'success',
        'error',
    ];

    public function init()
    {
        parent::init();

        Requirements::css('fractas/contactpage: css/contact.css');
    }

    public function ErrorMessage()
    {
        return _t(__CLASS__.'.ERRORMESSAGE', 'Something went wrong with form submission. Please call us or try again later.');
    }
}
