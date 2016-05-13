<?php 
/** 

  */



class module_profit extends module_base{


    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	
	public function init(){
		$this->links = array();
		$this->module_name = "profit";
		$this->module_position = 18;

        $this->version = 1.0;
      
	
	}

	
    public function pre_menu()	{
		
		if($this->can_i('view','profit')){
			if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] && $_REQUEST['customer_id']!='new'){
				if(isset($_REQUEST['job_id']) && $_REQUEST['job_id'] && $_REQUEST['job_id']!='new'){
					$profit = self::get_profit($_REQUEST['job_id']);
					$name = _l('Profit');
					if( $profit ){
						$name .= " <span class='";
						$name .= ($profit['total'] > 0 ? "success_text'>+" : "error_text'>-" );
						$name .= $profit['total']."€</span> ";
					}
					$this->links[] = array(
						"name"=>$name,
						"p"=>"profit_list",
						'args'=>array('profit_id'=>false),
						'holder_module' => 'customer', // which parent module this link will sit under.
						'holder_module_page' => 'customer_admin_open',  // which page this link will be automatically added to.
						'menu_include_parent' => 0,
						'icon_name' => 'check',
					);
				}
			}
			
			$this->links[] = array(
					"name"=>"Profit",
					"p"=>"profit_list",
					'args'=>array(),
					'icon_name' => 'dollar',
				);
			
		}
		
     
    }

	


	
	public function process(){
		switch($_REQUEST['_process']){
            case 'save_profit':
			break;
		}
		
    }
	

	public static function get_profit($job_id){
		$profit = array();
		$profit['invoice'] = 0;
		$profit['expense'] = 0;
		
		$job_id = (int)$job_id;
		
		$job_invoices = module_invoice::get_invoices( array('job_id'=>$job_id) );
		$fee_invoices = module_job::get_fee_invoices($job_id);
		$job_invoices = array_merge($job_invoices, $fee_invoices);
		foreach($job_invoices as $invoice){
			$this_invoice = module_invoice::get_invoice($invoice['invoice_id']);
			$profit['invoice'] += $this_invoice['total_amount'];		
		}
		
		$job_expenses = module_expense::get_expenses( array('job_id'=>$job_id) );
		foreach($job_expenses as $job_expense){
			$profit['expense'] += $job_expense['amount'];		
		}
		
		$profit['total'] = $profit['invoice'] - $profit['expense'];
		
		return $profit;
	
	}



public static function get_expenses($job_id){
		$profit = array();
		$profit['invoice'] = 0;
		$profit['expense'] = 0;
		
		$job_id = (int)$job_id;
		
		$job_expenses = module_expense::get_expenses( array('job_id'=>$job_id) );
		foreach($job_expenses as $job_expense){
			$profit['expense'] += $job_expense['amount'];		
		}
		
		$profit['total'] = $profit['expense'];
		
		return $profit;
	
	}


	
	public static function link_generate($profit_id=false,$options=array(),$link_options=array()){

        $key = 'profit_id';
        if($profit_id === false && $link_options){
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
        if(!isset($options['type'])) $options['type']='profit';
        if(!isset($options['page'])){
            if($profit_id && !isset($link_options['stop_bubble'])){
                $options['page'] = 'profit_edit';
            }else{
                $options['page'] = 'profit_admin';
            }
        }

        if(!isset($options['arguments'])){
            $options['arguments'] = array();
        }
        $options['arguments']['profit_id'] = $profit_id;
        $options['module'] = 'profit';
        if(isset($options['data'])){
            $data = $options['data'];
        }else{
            $data = self::get_profit($profit_id,false);
        }
        $options['data'] = $data;
        // what text should we display in this link?
        $options['text'] = (!isset($data['name'])||!trim($data['name'])) ? 'N/A' : $data['name'];
        if(($options['page']=='recurring' || $options['page']=='profit_edit') && !isset($link_options['stop_bubble'])){
            $link_options['stop_bubble']=true;
            $bubble_to_module = array(
                'module' => 'profit',
                'argument' => 'profit_id',
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