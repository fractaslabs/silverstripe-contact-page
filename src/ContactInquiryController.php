<?php

namespace Fractas\ContactPage;

use SilverStripe\Core\Extension;

class ContactInquiryController extends Extension
{
    private static $allowed_actions = [
        'ContactInquiryForm',
    ];

    public function ContactInquiryForm()
    {
        return ContactInquiryForm::create($this->owner, 'ContactInquiryForm');
    }
}
