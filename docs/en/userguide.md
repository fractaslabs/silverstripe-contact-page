# SilverStripe Contact Page Module user guide

## Overview
Simple & configurable SilverStripe module to notify admin on contact form submission.
Module saves user submissions in database and also sends an email notification to sites admin with users inquiry.


### Email notifications
When users on site successfully submits an form module automatically send an email to website administrator or person which is email added in Contact Page configuration.
See image that shows Contact page "Mail" tab for configuration:
![Contact page mail settings](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/docs/en/images/contact-page-mail-settings.png)


### Success messages
For showing users they are successfully submitted its contact inquiry you can specify Success Title and Message, like so:
![Contact page mail settings](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/docs/en/images/contact-page-mail-success-message-fields.png)


### CMS model admin
Using Contact inq. model admin interface CMS users can view all contact submissions and optionally add admin comments and status (New, Opened, Answered, Spam, Archived - with "New" being the default status) to inquiry thus turning it into "simple CRM".  

See image that shows one contact form submission and its fields:
![Contact inquiry interface](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/docs/en/images/contact-inq-interface.png)

Also it is possible to export data to CSV:
![Contact inquiry CSV export](https://github.com/fractaslabs/silverstripe-contact-page/blob/master/docs/en/images/contact-inq-interface-csv.png)
