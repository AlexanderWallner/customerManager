<?php

/*
 *  Module: webNpro Invoice Numbering v1.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More license clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 *
 *  Changelog:
 *
 *  v1.0.4   -   09/15/2014  -   Add connection error checking for the license api
 *  v1.0.3   -   09/07/2014  -   Add envato license validation to the plugin
 *                               Add the new webNpro upgrade function to the plugin
 *                               Add the "Read documentation" function to the plugin
 *                               Make the Custom Invoice Numbering Format field wider
 *                               Add the help texts to the new UCM translation system
 *  v1.0.2	-	02/21/2014	-	FIX for php 5.3 in the customer_invoice_number_function | Thanks, Jan! :-)
 *  								New shortcode added: {YEAR2}
 *  v1.0.1 	- 	02/16/2014	- 	Small code optimizations to make sure we always get unique numbers,
 *  								From now we use the last invoice_id instead of invoice_incrementing_next,
 *  								BUG FIX: numbering step
 *  v1.0 	- 	02/03/2014	- 	Custom invoice number format (Settings/Invoice numbering)
 *
 *  Shortcodes:
 *
 *  {PREFIX} = $invoice_prefix
 *  {CUSTOMERID} = $customer_id
 *  {JOBID} = $job_id
 *  {YEAR} = year (4 digits)
 *  {YEAR2} = year (2 digits)
 *  {MONTH} = month (2 digits)
 *  {DAY} = day (2 digits)
 *  {NUM} / {NUM1} = 0..9 - auto incremental
 *  {NUM2} = 00..99 - auto incremental
 *  {NUM3} = 000..999 - auto incremental
 *  {NUM4} = 0000..9999 - auto incremental
 *  {NUM5} = 00000..99999 - auto incremental
 *  {HEX} / {HEX1} = 0..F - auto incremental
 *  {HEX2} = 00..FF - auto incremental
 *  {HEX3} = 000..FFF - auto incremental
 *  {HEX4} = 0000..FFFF - auto incremental
 *  {HEX5} = 00000..FFFFF - auto incremental
 *  {RANDNUM} / {RANDNUM1} = 0..9 - random
 *  {RANDNUM2} = 00..99 - random
 *  {RANDNUM3} = 000..999 - random
 *  {RANDNUM4} = 0000..9999 - random
 *  {RANDNUM5} = 00000..99999 - random
 *  {RANDHEX} = 0..F - random
 *  {RANDHEX2} = 00..FF - random
 *  {RANDHEX3} = 000..FFF - random
 *  {RANDHEX4} = 0000..FFFF - random
 *  {RANDHEX5} = 00000..FFFFF - random
 */

/**
 * This function is called in plugin_invoice/invoice.php (function new_invoice_number)
 *
 * @return string The plugin generated invoice number
 */
function custom_invoice_number() {
    // This function is called in plugin_invoice/invoice.php (function new_invoice_number)
    if (module_config::c('invoice_number_custom_format', 0) && module_config::c('use_custom_invoice_numbering_format', 0)) {
        // If we have a custom invoice numbering format and the checkbox is checked create the new number else the UCM will use the default methods
        $custom_invoice_number = '';
        $invoice_prefix = '';
        $custom_invoice_number_format = module_config::c('invoice_number_custom_format', 1);

        // $customer_id
        $customer_id = (isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : 0);
        // $job_id
        $job_id = (isset($_REQUEST['job_id']) ? $_REQUEST['job_id'] : 0);
        // $currency_id
        $currency_id = module_config::c('default_currency_id', 1);

        if ($customer_id > 0) {
            // nothing to do
        } else if ($job_id > 0) {
            // only a job, no customer. set the customer id.
            $job_data = module_job::get_job($job_id, false);
            $customer_id = $job_data['customer_id'];
            $currency_id = $job_data['currency_id'];
        }

        $customer_id > 0 ? $customer_id : "0";
        $job_id > 0 ? $job_id : "0";

        // $invoice_prefix
        if ($customer_id > 0) {
            $customer_data = module_customer::get_customer($customer_id);
            if ($customer_data && isset($customer_data['default_invoice_prefix'])) {
                $invoice_prefix = $customer_data['default_invoice_prefix'];
            }
        }

        // Get the next invoice number | It's the last invoice_id + 1
        // If we use this we always get the 'next' number independent from the other part of the format
        // Next line commented out, it's working only above php 5.4 | The next two lines are the workaround :-)
        // $number = max(module_invoice::get_invoices())['invoice_id']+1;
        $last_invoice = max(module_invoice::get_invoices());
        $number = $last_invoice['invoice_id'] + 1;

        // We have to be sure this is a unique invoice number in the system
        do {
            unset($shortcodes);
            unset($shortcodes_value);
            $shortcodes[] = "{PREFIX}";
            $shortcodes_value[] = $invoice_prefix;
            $shortcodes[] = "{CUSTOMERID}";
            $shortcodes_value[] = $customer_id;
            $shortcodes[] = "{JOBID}";
            $shortcodes_value[] = $job_id;
            $shortcodes[] = "{YEAR}";
            $shortcodes_value[] = date("Y");
            $shortcodes[] = "{YEAR2}";
            $shortcodes_value[] = date("y"); // Thanks, Jan :-)
            $shortcodes[] = "{MONTH}";
            $shortcodes_value[] = date("m");
            $shortcodes[] = "{DAY}";
            $shortcodes_value[] = date("d");
            $shortcodes[] = "{RANDNUM}";
            $shortcodes_value[] = mt_rand(0, 9);
            $shortcodes[] = "{RANDNUM1}";
            $shortcodes_value[] = mt_rand(0, 9);
            $shortcodes[] = "{RANDNUM2}";
            $shortcodes_value[] = str_pad(mt_rand(0, 99), 2, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDNUM3}";
            $shortcodes_value[] = str_pad(mt_rand(0, 999), 3, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDNUM4}";
            $shortcodes_value[] = str_pad(mt_rand(0, 9999), 4, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDNUM5}";
            $shortcodes_value[] = str_pad(mt_rand(0, 99999), 5, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDHEX}";
            $shortcodes_value[] = strtoupper(dechex(mt_rand(0, hexdec("f"))));
            $shortcodes[] = "{RANDHEX1}";
            $shortcodes_value[] = strtoupper(dechex(mt_rand(0, hexdec("f"))));
            $shortcodes[] = "{RANDHEX2}";
            $shortcodes_value[] = str_pad(strtoupper(dechex(mt_rand(0, hexdec("ff")))), 2, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDHEX3}";
            $shortcodes_value[] = str_pad(strtoupper(dechex(mt_rand(0, hexdec("fff")))), 3, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDHEX4}";
            $shortcodes_value[] = str_pad(strtoupper(dechex(mt_rand(0, hexdec("ffff")))), 4, "0", STR_PAD_LEFT);
            $shortcodes[] = "{RANDHEX5}";
            $shortcodes_value[] = str_pad(strtoupper(dechex(mt_rand(0, hexdec("fffff")))), 5, "0", STR_PAD_LEFT);
            $shortcodes[] = "{NUM}";
            $shortcodes_value[] = $number;
            $shortcodes[] = "{NUM1}";
            $shortcodes_value[] = $number;
            $shortcodes[] = "{NUM2}";
            $shortcodes_value[] = str_pad($number, 2, "0", STR_PAD_LEFT);
            $shortcodes[] = "{NUM3}";
            $shortcodes_value[] = str_pad($number, 3, "0", STR_PAD_LEFT);
            $shortcodes[] = "{NUM4}";
            $shortcodes_value[] = str_pad($number, 4, "0", STR_PAD_LEFT);
            $shortcodes[] = "{NUM5}";
            $shortcodes_value[] = str_pad($number, 5, "0", STR_PAD_LEFT);
            $shortcodes[] = "{HEX}";
            $shortcodes_value[] = strtoupper(dechex($number));
            $shortcodes[] = "{HEX1}";
            $shortcodes_value[] = strtoupper(dechex($number));
            $shortcodes[] = "{HEX2}";
            $shortcodes_value[] = str_pad(strtoupper(dechex($number)), 2, "0", STR_PAD_LEFT);
            $shortcodes[] = "{HEX3}";
            $shortcodes_value[] = str_pad(strtoupper(dechex($number)), 3, "0", STR_PAD_LEFT);
            $shortcodes[] = "{HEX4}";
            $shortcodes_value[] = str_pad(strtoupper(dechex($number)), 4, "0", STR_PAD_LEFT);
            $shortcodes[] = "{HEX5}";
            $shortcodes_value[] = str_pad(strtoupper(dechex($number)), 5, "0", STR_PAD_LEFT);

            $custom_invoice_number = str_replace($shortcodes, $shortcodes_value, $custom_invoice_number_format);

            $invoices = module_invoice::get_invoices(array('name' => $custom_invoice_number));
            if (!count($invoices)) {
                // Okay, this is the full invoice number, nothing to do;
            } else {
                // Increase the number;
                $number++;
            }
        } while (count($invoices) > 0); // until it's not a unique invoice number we go back
    }

    // Oh, yeah!
    return $custom_invoice_number;
    /* END function custom_invoice_number() */
}

// *** webNpro | START | Custom invoice number format ***

/**
 * webNpro Invoice Numbering module class
 */
class module_webnpro_invoice_numbering extends module_base {

    public $links;
    public $customer_types;
    public $customer_id;

    /**
     * Standard UCM function for permissions checking
     *
     * @param string $actions
     * @param string $name
     * @param string $category
     * @param string $module
     * @return boolean
     */
    public static function can_i($actions, $name = false, $category = false, $module = false) {
        if (!$module)
            $module = __CLASS__;
        return parent::can_i($actions, $name, $category, $module);
        /* END public static function can_i($actions, $name = false, $category = false, $module = false) */
    }

    /**
     * Give back the class name
     *
     * @return string The class name
     */
    public static function get_class() {
        return __CLASS__;
        /* END public static function get_class() */
    }

    /**
     * Standard UCM function with the base module datas
     */
    public function init() {
        $this->links = array();
        $this->module_name = "webnpro_invoice_numbering";
        $this->module_position = 20;
        $this->version = "1.0.4";
        /* END public function init() */
    }

    /**
     * Standard UCM function to generate the menu items
     *
     * @global $load_modules
     */
    public function pre_menu() {
        global $load_modules;
        // Menu => Settings / Invoice Numbering
        if ($this->can_i('edit', 'Invoice Settings', 'Config')) {
            $this->links[] = array(
                "name" => "Invoice Numbering",
                "p" => "settings",
                'holder_module' => 'config',
                'holder_module_page' => 'config_admin',
                'menu_include_parent' => 0,
            );
        }
        /* END public function pre_menu() */
    }

    /* END class module_webnpro_invoice_numbering */
}
