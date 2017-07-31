<?php

class ContactUsSiteConfigExtension extends DataExtension
{

    private static $db = [
        "ContactUsFormEmail" => "Varchar(100)",
        "ContactUsFormEnquiryLabel" => "Varchar(50)",
        "ContactUsFormSendLabel" => "Varchar(50)",
        "ContactUsFormThankYouMessage" => "Varchar(50)"
    ];

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.ContactUs',
            array(
                TextField::create('ContactUsEmail', 'Email'),
                TextField::create('ContactUsEnquiryLabel', 'Enquiry Label'),
                TextField::create('ContactUsSendLabel', 'Send Label'),
                HTMLEditorField::create('ContactUsThankYouMessage', 'Thank you message')
            )
        );

        return $fields;
    }



}
