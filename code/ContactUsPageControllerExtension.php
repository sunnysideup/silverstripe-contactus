<?php

class ContactUsPageControllerExtension extends Extension
{


    protected $contactUsProcessingForm = false;

    function ContactUsProcessingForm() {
        return $this->contactUsProcessingForm;
    }

    function ContactUsForm()
    {
        $m = Member::currentUser();
        if(!$m) {
            $m = new Member();
        }
        $fields = new FieldList(
            TextField::create('FirstName',_t('ContactUsPageControllerExtension.FIRST_NAME', 'first name'), $m->FirstName),
            TextField::create('Surname', _t('ContactUsPageControllerExtension.SURNAME', 'Surname'), $m->Surname),
            EmailField::create('Email',_t('ContactUsPageControllerExtension.EMAIL', 'Email'), $m->Email),
            TextField::create('Phone', _t('ContactUsPageControllerExtension.PHONE', 'Phone')),
            TextareaField::create('Enquiry', SiteConfig::current_site_config()->ContactUsFormEnquiryLabel)
        );
        $actions = FieldList::create(
            FormAction::create('docontactusform', SiteConfig::current_site_config()->ContactUsFormSendLabel)
        );
        $form =  new Form(
            $this->owner,
            'ContactUsForm',
            $fields,
            $actions,
            RequiredFields::create(
                array("Email", "Enquiry")
            )
        );
        // Update the form to add the protecter field to it

        return $form;

    }

    function docontactusform ($data, $form)
    {
        $obj = PageEnquiry::create_enquiry($data, $this->owner->dataRecord);
        $subject = _t('ContactUsPageControllerExtension.THANK_YOU_SUBJECT', 'Thank you for your enquiry').' - '.Director::absoluteBaseURL();
        $body = "<strong>$subject</strong><br /><br />";
        foreach($data as $key => $value) {
            if($key == "url") {
                $value = Director::absoluteURL(str_replace("ContactUsForm", "", $value));
            }
            if($key == "SecurityID" || $key == "Send" || $key == "Captcha") {
                //do nothing
            }else {
                $body .=  "<br /><br />".$key.': '.strip_tags($value).' --- ';
            }
        }
        $adminEmail = SiteConfig::current_site_config()->ContactUsEmail;

        $email = Email::create(
            $from = $data["Email"],
            $to = $adminEmail,
            $subject,
            $body
        );
        $obj->SentToAdmin = $email->send();

        $email = Email::create(
            $from = $adminEmail,
            $to = $data["Email"],
            $subject,
            $body
        );
        $obj->SentToCustomer = $email->send();
        $this->contactUsProcessingForm = true;
        $obj->write();

        return array();
    }
}
