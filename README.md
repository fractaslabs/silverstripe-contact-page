# SilverStripe Contact Page Module

## Overview
Simple & configurable SilverStripe plugin to notify admin on contact form submission


## Maintainer Contacts
*  Milan Jelicanin [at] Fractas.com


## Requirements
* SilverStripe 3 CMS & Framework
* [Silverstripe Zenvalidator](https://github.com/sheadawson/silverstripe-zenvalidator)
* [SilverStripe Bootstrap Forms](https://github.com/unclecheese/silverstripe-bootstrap-forms/)


## Installation Instructions

 * Download a release of this module and place this directory in the root of your SilverStripe installation and rename the folder 'contact-page' OR better way install it via Composer:
 ```
 composer require "fractas/silverstripe-contact-page" "^1.0"
 ``` 
 * Visit yoursite.com/dev/build?flush=1 to rebuild the database.
 * Visit yoursite.com/admin/pages/ and create Contact Page.
 * On newly created Contact Page set up Mail From (or leave blank) and Mail To adresses and enter Mail Subject for sending email notification on mail specified on Mail To field.
 * On tab "On Submision" enter validation Success Title and Success Text, optionaly add Image or Content.


## User guide
User guide in [available here](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/docs/en/userguide.md)


## Bugtracker
Bugs are tracked on [github.com](https://github.com/fractaslabs/silverstripe-contact-page/issues)


## Licence
 * See [Licence](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/LICENSE)
