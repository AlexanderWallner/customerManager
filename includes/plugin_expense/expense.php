<?php 
/** 

  */



class module_expense extends module_base{


    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	
	public function init(){
		$this->links = array();
		$this->module_name = "expense";
		$this->module_position = 18;

        $this->version = 1.0;
      

        //module_config::register_css('invoice','invoice.css');
        //module_config::register_js('invoice','invoice.js');

       // hook_add('expense_recurring_list','module_invoice::get_expense_recurring_items');



	
	}

	
    public function pre_menu()	{
	    if($this->can_i('view','expense')){
			if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] && $_REQUEST['customer_id']!='new'){
				$expenses = self::get_expenses(array('customer_id'=>$_REQUEST['customer_id']));
				$name = _l('Expenses');
				if(count($expenses)){
					$name .= " <span class='menu_label'>".count($expenses)."</span> ";
				}
				$this->links[] = array(
					"name"=>$name,
					"p"=>"expense_admin",
					'args'=>array('expense_id'=>false),
					'holder_module' => 'customer', // which parent module this link will sit under.
					'holder_module_page' => 'customer_admin_open',  // which page this link will be automatically added to.
					'menu_include_parent' => 0,
					'icon_name' => 'check',
				);
			}
			
			
			
			$this->links[] = array(
					"name"=>"Expenses",
					"p"=>"expense_admin",
					'args'=>array(),
					'icon_name' => 'dollar',
				);
		}
      
    }

	


	
	public function process(){
		switch($_REQUEST['_process']){
            case 'save_expense':
				if(isset($_REQUEST['butt_del'])){
					if( isset($_REQUEST['expense_id']) ) {
						$expense_id = self::delete_expense($_REQUEST['expense_id']);
					} 
					redirect_browser(self::link_generate(false));
				}
				if( $_REQUEST['expense_id'] == 'new') {
					$expense_id = self::create_expense($_REQUEST);
				} 
				else {
					$expense = self::get_expense($_REQUEST['expense_id']);
					$expense_id = self::edit_expense($_REQUEST);
				}
			
				if(isset($_REQUEST['butt_save_return'])){
					redirect_browser(self::link_generate(false));
				}
	            redirect_browser(self::link_generate($expense_id));
				break;
				
/*				
			case 'ajax_expense_job_list':
				$result = '';				
			
				if( isset($_REQUEST['job_id']) && (int)$_REQUEST['job_id'] > 0 ) {
					$result = ajax_expense_job_list((int)$_REQUEST['job_id']);
				}			

				echo $result;
				exit;				
*/
		}
		
    }
	

	
	public static function create_expense($expense_data){

        $fileName = self::generate_file_name($expense_data, $expense_data['name']);
        $insert_data = array();
		$insert_data['transaction_date'] = mysql_real_escape_string($expense_data['transaction_date']);
		$insert_data['name'] = mysql_real_escape_string($fileName);
		$insert_data['description'] = mysql_real_escape_string($expense_data['description']);
		$insert_data['amount'] = round( number_out($expense_data['amount']) , 2 );
		$insert_data['currency_id'] = (int)$expense_data['currency_id'];
		$insert_data['company_id'] = (int)$expense_data['company_id'];	
		$insert_data['customer_id'] = (int)$expense_data['customer_id'];	
		$insert_data['job_id'] = (int)$expense_data['job_id'];	
		$insert_data['task_id'] = (int)$expense_data['task_id'];	
		$insert_data['staff_id'] = (int)$expense_data['staff_id'];
        $insert_data['status'] = mysql_real_escape_string($expense_data['status']);
        $insert_data['clinic'] = mysql_real_escape_string($expense_data['clinic']);
        $insert_data['arzt'] = mysql_real_escape_string($expense_data['arzt']);
        $insert_data['art_der_leistung'] = mysql_real_escape_string($expense_data['art_der_leistung']);
        $insert_data['invoice_name'] = mysql_real_escape_string($expense_data['invoice_name']);
        $insert_data['date_invoice'] = mysql_real_escape_string($expense_data['date_invoice']);

        return update_insert('expense_id','new','expense',$insert_data);
    }
	
	public static function edit_expense($expense_data){
        $fileName = self::generate_file_name($expense_data, $expense_data['name']);
        $update_data = array();
		$update_data['transaction_date'] = mysql_real_escape_string($expense_data['transaction_date']);
		$update_data['name'] = mysql_real_escape_string($fileName);
		$update_data['description'] = mysql_real_escape_string($expense_data['description']);
		$update_data['amount'] =  round( number_in($expense_data['amount']) , 2 );
		$update_data['currency_id'] = (int)$expense_data['currency_id'];
		$update_data['company_id'] = (int)$expense_data['company_id'];	
		$update_data['customer_id'] = (int)$expense_data['customer_id'];	
		$update_data['job_id'] = (int)$expense_data['job_id'];	
		$update_data['task_id'] = (int)$expense_data['task_id'];	
		$update_data['staff_id'] = (int)$expense_data['staff_id'];
        $update_data['status'] = mysql_real_escape_string($expense_data['status']);
        $update_data['clinic'] = mysql_real_escape_string($expense_data['clinic']);
        $update_data['arzt'] = mysql_real_escape_string($expense_data['arzt']);
        $update_data['art_der_leistung'] = mysql_real_escape_string($expense_data['art_der_leistung']);
        $update_data['invoice_name'] = mysql_real_escape_string($expense_data['invoice_name']);
        $update_data['date_invoice'] = mysql_real_escape_string($expense_data['date_invoice']);
        return update_insert('expense_id',(int)$expense_data['expense_id'],'expense',$update_data);
    }
	
	public static function delete_expense($expense_id){		
		$del_expense_id = (int)$expense_id;		
        $sql = "DELETE FROM "._DB_PREFIX."expense WHERE expense_id = '".$del_expense_id."' LIMIT 1";
		return query($sql);	
    }
	
	
	public static function get_expense($expense_id){

        $expense_id = (int)$expense_id;
		
		
        if($expense_id > 0){
            return get_single("expense","expense_id",$expense_id);
        }
	    if($expense_id <= 0){

            $expense = array(
                'expense_id' => 0,
                'transaction_date' => print_date(time()),
                'name' => '',
                'description' => '',
                'amount' => 0,
                'currency_id' => module_config::c('default_currency_id',1),
                'customer_id'=>0,
                'job_id'=>0,
             );
           
      
            return $expense;
		}
		return $expense_id;
	
	}

	public static function get_expenses($options = array()){

        $expenses = array();
		
		$sql = "SELECT * FROM `"._DB_PREFIX."expense` ";

		if( isset($options['job_id']) && (int)$options['job_id'] > 0 ) {			
			$sql .= " WHERE `job_id` ='".(int)$options['job_id']."' ";
			
			if( isset($options['customer_id']) && (int)$options['customer_id'] > 0 ) {			
				$sql .= " AND `customer_id` ='".(int)$options['customer_id']."' ";
			}
		} 
		elseif( isset($options['customer_id']) && (int)$options['customer_id'] > 0 ) {			
			$sql .= " WHERE `customer_id` ='".(int)$options['customer_id']."' ";
		}
		
		
		$expenses = qa($sql);

		return $expenses;
	
	}

    public static function link_open($expense_id,$full=false,$data=array()){
        return self::link_generate($expense_id,array('full'=>$full,'data'=>$data));
    }

    public function generate_file_name($fileConfig, $fileName)
    {
        if ($fileConfig['generate_name']) {
            $name = array();
            if ($fileConfig['status']) {
                //$name[] = rawurlencode($fileConfig['status']);
                $name[] = $fileConfig['status'];
            }
            if ($fileConfig['clinic']) {
                $name[] = $fileConfig['clinic'];
            }
            if ($fileConfig['arzt']) {
                $name[] = $fileConfig['arzt'];
            }
            if ($fileConfig['art_der_leistung']) {
                $name[] = $fileConfig['art_der_leistung'];
            }
            if ($fileConfig['invoice_name']) {
                $name[] = $fileConfig['invoice_name'];
            }
            if ($fileConfig['date_invoice']) {
                $name[] = 'RD' . $fileConfig['date_invoice'];
            }
            if ($fileConfig['amount']) {
                $name[] = $fileConfig['amount'];
            }

            //$name[] = date('YmdHis');
            //$name[] = $fileConfig['file_id'] ? $fileConfig['file_id'] : $counter;

            $name = implode('_', $name);
            return $name;
        }
        else {
            return $fileName;
        }
    }



    public static function get_valid_expense_ids(){
        return self::expenses_new(array(),array('columns'=>'u.expense_id'));
    }
    public static function get_expenses_new($search=array(),$return_options=array()){
        // limit based on customer id
        /*if(!isset($_REQUEST['customer_id']) || !(int)$_REQUEST['customer_id']){
            return array();
        }*/
        /*$cache_key = 'get_quotes_'.md5(serialize(array($search,$return_options)));
        if($cached_item = module_cache::get('quote',$cache_key)){
            return $cached_item;
        }
        $cache_timeout = module_config::c('cache_objects',60);*/

        // build up a custom search sql query based on the provided search fields
        $sql = "SELECT ";
        if(isset($return_options['columns'])){
            $sql .= $return_options['columns'];
        }else{
            $sql .= "u.*,u.expense_id AS id ";
            $sql .= ", u.name AS name ";

        }
        $from = " FROM `"._DB_PREFIX."expense` u ";

        $where = " WHERE 1 ";
        if(is_array($return_options) && isset($return_options['custom_where'])){
            // put in return options so harder to push through from user end.
            $where .= $return_options['custom_where'];
        }
        if(isset($search['generic']) && $search['generic']){
            $str = mysql_real_escape_string($search['generic']);
            $where .= " AND ( ";
            $where .= " u.name LIKE '%$str%' "; //OR ";
            //$where .= " u.url LIKE '%$str%'  ";
            $where .= ' ) ';
        }
        foreach(array('customer_id','website_id','status','type','date_create') as $key){
            if(isset($search[$key]) && $search[$key] !== ''&& $search[$key] !== false){
                $str = mysql_real_escape_string($search[$key]);
                if($str[0]=='!'){
                    // hack for != sql searching.
                    $str = ltrim($str,'!');
                    $where .= " AND u.`$key` != '$str'";
                }else{
                    $where .= " AND u.`$key` = '$str'";
                }
            }
        }


        $group_order = ' GROUP BY u.expense_id ORDER BY u.name';



        $sql = $sql . $from . $where . $group_order;
//        echo $sql;print_r(debug_backtrace());exit;
        $result = qa($sql);
        //module_security::filter_data_set("quote",$result);
        //module_cache::put('quote',$cache_key,$result,$cache_timeout);
        return $result;
//		return get_multiple("quote",$search,"quote_id","fuzzy","name");

    }




	
	public static function link_generate($expense_id=false,$options=array(),$link_options=array()){

        $key = 'expense_id';
        if($expense_id === false && $link_options){
            foreach($link_options as $link_option){
                if(isset($link_option['data']) && isset($link_option['data'][$key])){
                    ${$key} = $link_option['data'][$key];
                    break;
                }
            }
            if(!${$key} && isset($_REQUEST[$key])){
                ${$key} = $_REQUEST[$key];
            }
        }
        $bubble_to_module = false;
        if(!isset($options['type'])) $options['type']='expense';
        if(!isset($options['page'])){
            if($expense_id && !isset($link_options['stop_bubble'])){
                $options['page'] = 'expense_edit';
            }else{
                $options['page'] = 'expense_admin';
            }
        }

        if(!isset($options['arguments'])){
            $options['arguments'] = array();
        }
        $options['arguments']['expense_id'] = $expense_id;
        $options['module'] = 'expense';
        if(isset($options['data'])){
            $data = $options['data'];
        }else{
            $data = self::get_expense($expense_id,false);
        }
        $options['data'] = $data;
        // what text should we display in this link?
        $options['text'] = (!isset($data['name'])||!trim($data['name'])) ? 'N/A' : $data['name'];
        if(($options['page']=='recurring' || $options['page']=='expense_edit') && !isset($link_options['stop_bubble'])){
            $link_options['stop_bubble']=true;
            $bubble_to_module = array(
                'module' => 'expense',
                'argument' => 'expense_id',
            );
        }
		
		if( isset($_REQUEST['customer_id']) && $_REQUEST['customer_id']>0 ){
            $bubble_to_module = array(
                'module' => 'customer',
                'argument' => 'customer_id',
            );
        }
		
        array_unshift($link_options,$options);

		
		
        if($bubble_to_module){
            global $plugins;
            return $plugins[$bubble_to_module['module']]->link_generate(false,array(),$link_options);
        }else{
            // return the link as-is, no more bubbling or anything.
            // pass this off to the global link_generate() function
            return link_generate($link_options);

        }
    }
	
	
	



}