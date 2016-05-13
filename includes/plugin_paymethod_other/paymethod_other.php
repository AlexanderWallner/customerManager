<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */



class module_paymethod_other extends module_base{

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	function init(){
        $this->version = 2.11;
        //2.1 - new option that can be used for cheque/check payments
        //2.11 - 2014-11-27 - updated defaults

		$this->module_name = "paymethod_other";
		$this->module_position = 8899;

        if(class_exists('module_template',false)){
            module_template::init_template('paymethod_other','Hello,
Please make payment as per the below details:

{OTHER_DETAILS}

If you have any questions please feel free to contact us.

Please <a href="{LINK}" target="_blank">click here</a> to return to your previous page.

Thank you
','Displayed when this payment method is selected from invoice page.');

            module_template::init_template('paymethod_other_details','Payable To: <strong>Name Here</strong>
Post To: <strong>123 Your Address, LA</strong>
Amount: <strong>{AMOUNT}</strong>','Details displayed on the invoice payments area.');
        }

	}

    public function pre_menu(){

        if(module_config::can_i('view','Settings')){
            $this->links[] = array(
                "name"=>"Other",
                "p"=>"other_settings",
                'holder_module' => 'config', // which parent module this link will sit under.
                'holder_module_page' => 'config_payment',  // which page this link will be automatically added to.
                'menu_include_parent' => 1,
            );
        }
    }


    public function handle_hook($hook){
        switch($hook){
            case 'get_payment_methods':
                return $this;
                break;
        }
    }
    
    public function is_method($method){
        return $method=='offline';
    }

    public static function is_enabled(){
        return module_config::c('payment_method_other_enabled',0);
    }

    public function is_allowed_for_invoice($invoice_id){
        return module_config::c('__inv_other_'.$invoice_id,1);
    }
    public function set_allowed_for_invoice($invoice_id,$allowed=1){
        module_config::save_config('__inv_other_'.$invoice_id,$allowed);
    }
    public static function get_payment_method_name(){
        return module_config::s('payment_method_other_label','Other');
    }

    public function get_invoice_payment_description($invoice_id,$method=''){
        $template = module_template::get_template_by_key('paymethod_other_details');
        $invoice_data = module_invoice::get_invoice($invoice_id);
        $template->assign_values($invoice_data + array(
                                     'amount' => dollar($invoice_data['total_amount_due'],true,$invoice_data['currency_id']),
                                 ));
        return $template->render('html');
    }

    public static function start_payment($invoice_id,$payment_amount,$invoice_payment_id){
        if($invoice_id && $payment_amount && $invoice_payment_id){
            // we are starting a payment via other!
            // setup a pending payment and redirect to other.
            $invoice_data = module_invoice::get_invoice($invoice_id);
            $description = _l('Payment for invoice %s',$invoice_data['name']);
            self::other_redirect($description,$payment_amount,module_security::get_loggedin_id(),$invoice_payment_id,$invoice_id);
            return true;
        }
        return false;
    }

    public static function other_redirect($description,$amount,$user_id,$payment_id,$invoice_id){

        $invoice_data = module_invoice::get_invoice($invoice_id);

        $other_details = module_template::get_template_by_key('paymethod_other_details');
        $other_details->assign_values($invoice_data + array(
                                     'amount' => dollar($amount,true,$invoice_data['currency_id']),
                                 ));
        $other_details_html = $other_details->render('html');

        // display a template with the other details in it.
        $template = module_template::get_template_by_key('paymethod_other');
        $template->assign_values(array(
                                     'other_details' => $other_details_html,
                                     'link' => module_invoice::link_open($invoice_id),
                                 ));
        echo $template->render('pretty_html');
        exit;
    }

}