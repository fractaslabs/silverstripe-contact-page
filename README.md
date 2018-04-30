# SilverStripe Contact Page Module
[![Latest Stable Version](https://poser.pugx.org/fractas/contactpage/v/stable)](https://packagist.org/packages/fractas/contactpage)
[![Latest Unstable Version](https://poser.pugx.org/fractas/contactpage/v/unstable)](https://packagist.org/packages/fractas/contactpage)
[![Total Downloads](https://poser.pugx.org/fractas/contactpage/downloads)](https://packagist.org/packages/fractas/contactpage)
[![License](https://poser.pugx.org/fractas/contactpage/license)](https://packagist.org/packages/fractas/contactpage)

## Overview

Simple & configurable SilverStripe module to notify CMS Admin on Contact form submission

## Maintainer Contacts

- Milan Jelicanin [at] Fractas.com
- Petar Simic [at] Fractas.com

## Requirements

- SilverStripe 4 CMS & Framework

## Version info
The master branch of this module is currently aiming for SilverStripe 4.x compatibility
- [SilverStripe 3.0+ compatible version](https://github.com/fractaslabs/silverstripe-contact-page/tree/3.0)

## Installation Instructions

- Download a release of this module and place this directory in the root of your SilverStripe installation and rename the folder 'contact-page' OR better way install it via Composer:

  ```
  composer require "fractas/contactpage" "^2.0"
  ```

- Visit yoursite.com/dev/build?flush=1 to rebuild the database.
- Visit yoursite.com/admin/pages/ and create Contact Page.
- On newly created Contact Page set up Mail From (or leave blank) and Mail To adresses and enter Mail Subject for sending email notification on mail specified on Mail To field.
- On tab "On Submision" enter validation Success Title and Success Text, optionaly add Image or Content.
- Optionaly you can add an checkbox for "Terms of service page" and link to it

## User guide

For more informations about usage with screenshoots user guide is [available here](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/docs/en/userguide.md)

## Bugtracker

Bugs are tracked on [github.com](https://github.com/fractaslabs/silverstripe-contact-page/issues)

## Licence

See [Licence](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/LICENSE)
