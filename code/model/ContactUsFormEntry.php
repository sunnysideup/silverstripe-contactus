<?php

class ContactUsFormEntry extends DataObject
{

    private static $db = array(
        'Email' => 'Varchar',
        'FirstName' => 'Varchar',
        'Surname' => 'Varchar',
        'Phone' => 'Varchar',
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

    public static function create_enquiry($data, $page)
    {
        $obj = ContactUsFormEntry::create(
            array(
                'Email'=> Convert::raw2sql($data['Email']),
                'FirstName' => Convert::raw2sql($data['FirstName']),
                'Surname' => Convert::raw2sql($data['Surname']),
                'Phone' => Convert::raw2sql($data['Phone']),
                'Enquiry' => Convert::raw2sql($data['Enquiry']),
                //data is a backup for any additional fields in the form...
                "PageID" => $page->ID
            )
        );
        unset($data['url']);
        unset($data['action_docontactusform']);
        unset($data['SecurityID']);
        unset($data['Enquiry']);
        unset($data['Phone']);
        unset($data['FirstName']);
        unset($data['Surname']);
        unset($data['Email']);
        $obj->Data = serialize($data);
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
        'Email' => 'Email',
        'Created' => 'Created',
        'SentToAdmin.Nice' => 'Sent to Admin',
        'SentToCustomer.Nice' => 'Sent to Customer',
        'Enquiry' => 'Enquiry',
        'Responded.Nice' => 'Replied',
        'Page.Title' => 'Page'
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
