<?php

class ContactUsPageDataExtension extends Extension
{

    private static $has_many = [
        'ContactUsFormEntries' => 'ContactUsFormEntry'
    ];

    private static $field_labels = [
        'ContactUsFormEntries' => 'Contact Us Form Entries'
    ];


    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $fieldLabels = $this->owner->FieldLabels();
        $label = $fieldLabels['ContactUsFormEntries'];
        $fields->addFieldToTab(
            'Root.Enquiries',
            GridField::create(
                'ContactUsFormEntries',
                $label,
                $this->owner->ContactUsFormEntries(),
                GridFieldConfig_RelationEditor::create()
            )
        );

        return $fields;
    }
}
