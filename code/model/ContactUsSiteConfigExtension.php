<?php

class ContactUsSiteConfigExtension extends DataExtension
{

    private static $db = [
        'ContactUsFormEmail' => 'Varchar(100)',
        'ContactUsFormEnquiryLabel' => 'Varchar(50)',
        'ContactUsFormSendLabel' => 'Varchar(50)',
        'ContactUsFormThankYouMessage' => 'HTMLText'
    ];

    private static $defaults = [
        'ContactUsFormEnquiryLabel' => 'Message',
        'ContactUsFormSendLabel' => 'Send',
        'ContactUsFormThankYouMessage' => '<p class="message good">Thank you for your enquiry.</p>'
    ];

    private static $field_labels = [
        'ContactUsFormEmail' => 'Email to use',
        'ContactUsFormEnquiryLabel' => 'Label for Message Box',
        'ContactUsFormSendLabel' => 'Send Button Label',
        'ContactUsFormThankYouMessage' => 'Message to show as thank you.'
    ];

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $formLabels = $this->owner->FieldLabels();
        $fields->addFieldsToTab(
            'Root.ContactUs',
            array(
                TextField::create('ContactUsFormEmail', $formLabels['ContactUsFormEmail']),
                TextField::create('ContactUsFormEnquiryLabel', $formLabels['ContactUsFormEnquiryLabel']),
                TextField::create('ContactUsFormSendLabel', $formLabels['ContactUsFormSendLabel']),
                HTMLEditorField::create('ContactUsFormThankYouMessage', $formLabels['ContactUsFormThankYouMessage'])
            )
        );

        return $fields;
    }



}
