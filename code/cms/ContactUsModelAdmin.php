<?php

class ContactUsModelAdmin extends ModelAdmin {

    private static $managed_models = array('ContactUsFormEntry');

    private static $url_segment = 'enquiries';

    private static $menu_title = 'Customer Enquiries';

}
