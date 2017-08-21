<?php

class ContactUsFormEntry extends DataObject
{

    private static $db = array(
        'AdminEmail' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
        'FirstName' => 'Varchar(255)',
        'Surname' => 'Varchar(255)',
        'Phone' => 'Varchar(255)',
        'Enquiry' => 'Text',
        'Data' => 'Text',
        'Responded' => 'Boolean',
        'SentToAdmin' => 'Boolean',
        'SentToCustomer' => 'Boolean'
    );

    private static $has_one = array(
        'Page' => 'SiteTree'
    );

    private static $casting = array(
        'NiceData' => 'HTMLText'
    );

    public static function create_enquiry($sqlSafeData, $page)
    {
        $obj = ContactUsFormEntry::create(
            array(
                'Email'=> ($sqlSafeData['Email']),
                'FirstName' => ($sqlSafeData['FirstName']),
                'Surname' => ($sqlSafeData['Surname']),
                'Phone' => ($sqlSafeData['Phone']),
                'Enquiry' => ($sqlSafeData['Enquiry']),
                //data is a backup for any additional fields in the form...
                "PageID" => $page->ID
            )
        );
        unset($sqlSafeData['url']);
        unset($sqlSafeData['action_docontactusform']);
        unset($sqlSafeData['SecurityID']);
        unset($sqlSafeData['Enquiry']);
        unset($sqlSafeData['Phone']);
        unset($sqlSafeData['FirstName']);
        unset($sqlSafeData['Surname']);
        unset($sqlSafeData['Email']);
        $obj->Data = serialize($sqlSafeData);
        $obj->write();

        return $obj;
    }

    private static $singular_name = "Customer Enquiry";
        function i18n_singular_name() { return self::$singular_name;}

    private static $plural_name = "Customer Enquiries";
        function i18n_plural_name() { return self::$plural_name;}

    private static $indexes = array(
        "Email" => true
    );

    private static $default_sort = array(
        'Created' => 'Desc'
    );

    private static $summary_fields = array(
        'Email' => 'From',
        'AdminEmail' => 'To',
        'Created' => 'Created',
        'SentToAdmin.Nice' => 'Sent to Admin',
        'SentToCustomer.Nice' => 'Sent to Customer',
        'Enquiry' => 'Enquiry',
        'Responded.Nice' => 'Replied',
        'Page.Title' => 'Page'
    );

    private static $field_labes = array(
        'Email' => 'From',
        'AdminEmail' => 'To'
    );

    public function canCreate($member = null) {
        return false;
    }

    public function canDelete($member = null) {
        return false;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->replaceField(
                'Data',
                LiteralField::create('NiceData', $this->getNiceData())
         );
        $fixedFields = array(
            'SentToCustomer',
            'SentToAdmin',
            'FirstName',
            'Email',
            'Surname',
            'Phone',
            'Enquiry'
        );

        $labels = $this->FieldLabels();
        foreach($fixedFields as $fixedField) {
            $fields->replaceField(
                $fixedField,
                ReadonlyField::create($fixedField, $labels[$fixedField])
            );
        }
        $fields->removeByName('PageID');

        return $fields;
   }

    /**
     * returns list of fields as they are exported
     * @return array
     * Field => Label
     */
  //  public function getExportFields();

    function getNiceData()
    {
        $array = unserialize($this->Data);
        $html = '<ul>';
        if(is_array($array)){
            foreach($array as $field => $value){
                $html .= '<li><strong>'.$field.':</strong> '.$value;
            }
        }
        $html .= '</ul>';

        return $html;
    }

}
