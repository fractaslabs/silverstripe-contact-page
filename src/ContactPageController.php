<?php

namespace Fractas\ContactPage;

use PageController;

class ContactPageController extends PageController
{
    private static $allowed_actions = array(
        'success',
        'error',
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
