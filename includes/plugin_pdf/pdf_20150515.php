<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 91.221.59.205
  */ 


class module_pdf extends module_base{
	
	var $links;
	public $pdf_library;
	 
    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	function init(){
        $this->version=2.13;
        // 2.1 - added arialuni ttf file
        // 2.11 - more options for pdf generation
        // 2.12 - better unicode configuration
        // 2.121 - 2013-05-27 - space after slash fix
        // 2.122 - 2013-05-27 - jpeg image quality improvement
        // 2.123 - 2014-05-21 - faster/better pdf generation, READ BLOG FOR DETAILS!
        // 2.124 - 2014-05-23 - faster/better pdf generation, READ BLOG FOR DETAILS!
        // 2.125 - 2014-07-01 - cron job pdf fix
        // 2.126 - 2014-07-01 - better mpdf unicode support
        // 2.127 - 2014-08-18 - mpdf update
        // 2.128 - 2014-09-29 - pdf generation file path fix
        // 2.129 - 2015-01-27 - mpdf bug fix
        // 2.13 - 2015-01-31 - mpdf update
		$this->links = array();
		$this->module_name = "pdf";
		$this->module_position = 8882;
	}

	public function pre_menu(){
		// the link within Admin > Settings > Emails.
        if($this->can_i('edit','PDF Settings','Config')){
            $this->links[] = array(
                "name"=>"PDF",
                "p"=>"pdf_settings",
                'holder_module' => 'config', // which parent module this link will sit under.
                'holder_module_page' => 'config_admin',  // which page this link will be automatically added to.
                'menu_include_parent' => 0,
            );
        }
	}

}


function convert_html2pdf($html,$pdf,$header='',$footer=''){

    // start conversion
    //require_once('includes/html2ps/dave.php');

	$library = module_config::c('pdf_library');
	if(!$library && file_exists(dirname(__FILE__).'/mpdf/mpdf.php')){
		$library = 'mpdf';
	}
    switch($library)
    {
    	case 'mpdf':
			if(file_exists(dirname(__FILE__).'/mpdf/mpdf.php')) {
				//require_once ( dirname( __FILE__ ) . '/mpdf/mpdf.php' );
				require_once ( module_theme::include_ucm('includes/plugin_pdf/mpdf/mpdf.php') );
				$html_contents = file_get_contents( $html );
				$mpdf        = new mPDF( '', module_config::c('pdf_media_size','A4'), 0, '', module_config::c('pdf_media_left','10'), module_config::c('pdf_media_right','10'), module_config::c('pdf_media_top','10'), module_config::c('pdf_media_bottom','10'), 8, 8 );

                $mpdf->debug = true;
                $mpdf->setAutoTopMargin = true;
                $mpdf->setAutoBottomMargin = true;
                $mpdf->SetHTMLHeader($header);
                $mpdf->SetHTMLFooter($footer);
                $mpdf->WriteHTML( $html_contents );
				$mpdf->Output( $pdf, 'F' );

				break;
			}
    	default:
    		ini_set('error_reporting',E_ERROR);
    		ini_set("display_errors",true);
    		require_once('html2ps/config.inc.php');
    		require_once(HTML2PS_DIR.'pipeline.factory.class.php');
    		
    		set_time_limit(120);
    		parse_config_file(HTML2PS_DIR.'html2ps.config');
    		
    		
    		global $g_font_resolver_pdf;
    		//    print_r($g_font_resolver_pdf->ttf_mappings); exit;
    		$g_font_resolver_pdf->ttf_mappings['Arial Unicode MS'] = module_config::c('pdf_unicode_font','arialuni.ttf');
    		/**
    		 * Handles the saving generated PDF to user-defined output file on server
    		 */
    		if(!class_exists('MyDestinationFile',false)){
    			class MyDestinationFile extends Destination {
    				/**
    				 * @var String result file name / path
    				 * @access private
    				 */
    				var $_dest_filename;
    		
    				function MyDestinationFile($dest_filename) {
    					$this->_dest_filename = $dest_filename;
    				}
    		
    				function process($tmp_filename, $content_type) {
    					copy($tmp_filename, $this->_dest_filename);
    				}
    			}
    		
    			class MyFetcherLocalFile extends Fetcher {
    				var $_content;
    		
    				function MyFetcherLocalFile($file) {
    					$this->_content = file_get_contents($file);
    				}
    		
    				function get_data($dummy1) {
    					return new FetchedDataURL($this->_content, array(), "");
    				}
    		
    				function get_base_url() {
    					return "http://".$_SERVER['HTTP_HOST'].'/';
    				}
    			}
    		
    		
    			/**
    			 * Runs the HTML->PDF conversion with default settings
    			 *
    			 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
    			 * use absolute links (like http://my.host/image.gif).
    			 *
    			 * @param $path_to_html String path to source html file.
    			 * @param $path_to_pdf  String path to file to save generated PDF to.
    			 */
    			function convert_to_pdf($path_to_html, $path_to_pdf) {
    				$pipeline = PipelineFactory::create_default_pipeline("", "");
    				// Override HTML source
    				$pipeline->fetchers[] = new MyFetcherLocalFile($path_to_html);
    		
    				$filter = new PreTreeFilterHeaderFooter("HEADER", "FOOTER");
    				$pipeline->pre_tree_filters[] = $filter;
    		
    				// Override destination to local file
    				$pipeline->destination = new MyDestinationFile($path_to_pdf);
    		
    				$baseurl = "";
    				$media = Media::predefined(module_config::c('pdf_media_size','A4'));
    				$media->set_landscape(false);
    				$media->set_margins(array('left'   => module_config::c('pdf_media_left','0'),
    						'right'  => module_config::c('pdf_media_right','0'),
    						'top'    => module_config::c('pdf_media_top','0'),
    						'bottom' => module_config::c('pdf_media_bottom','0')));
    				$media->set_pixels(module_config::c('pdf_media_pixels','1010'));
    		
    				global $g_config;
    				$g_config = array(
    						'compress'     => true,
    						'cssmedia'     => 'screen',
    						'scalepoints'  => '1',
    						'renderimages' => true,
    						'renderlinks'  => true,
    						'renderfields' => true,
    						'renderforms'  => false,
    						'mode'         => 'html',
    						'encoding'     => 'UTF-8',
    						'debugbox'     => false,
    						'pdfversion'    => '1.4',
    						'draw_page_border' => false,
    						'media' => module_config::c('pdf_media_size','A4'),
    				);
    				$pipeline->configure($g_config);
    				//$pipeline->add_feature('toc', array('location' => 'before'));
    				$pipeline->process($baseurl, $media);
    			}
    		}
    		convert_to_pdf($html, $pdf);
    	break;
    }

    return str_replace(_UCM_FOLDER,'',$pdf);
}
