<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_users
 {
 	
	//this is the expiration for a non-remember session
	var $session_expire	= 600000000;

	function __construct()
	{
	  $CI = & get_instance();
      // $this->is_permission();
       
	 	
	}
    
function is_logged_in(){ 
	 $CI = & get_instance();
$CI->load->helper('url'); 
 		//$redirect allows us to choose where a customer will get redirected to after they login
		//$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you  
   if(!$CI->session->userdata('vendorAuth'))
     {           
    redirect(base_url(), 'refresh');
        }
	}
    
    
/*    
    
function is_permission(){ 
    
  
	 $CI = & get_instance();
    $CI->load->helper('url'); 
    $rt =  $CI->uri->segment('2'); 
      if($CI->session->userdata('vendorRole')==1)
    {
 		//$redirect allows us to choose where a customer will get redirected to after they login
		//$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you  
    $gh = strtolower($CI->router->fetch_class());
    
    if($gh=='staff')
    {
       $gh="staff_members"; 
    }
 
     else if($gh=='supplier')
    {
      $gh="supplier_management";   
    }
    
      else if($gh=='template')
    {
      $gh="templates";   
    }
    


       else if($gh=='giftvouchers')
    {
      $gh="gift_voucher";   
    }
     else if($gh=='pos')
    {
      $gh="POS";   
    }
     else if($gh=='customer')
    {
      $gh="customer_management";   
    }
    
       else if($rt=='taxsetting')
    {
      $gh="tax";   
    }
     else if($rt=='storesetting')
    {
      $gh="store_setting";   
    }
     
    
    $data = $CI->db->select("*")->from("houdinv_staff_management_sub")
    ->join("houdinv_staff_management","houdinv_staff_management.staff_id=houdinv_staff_management_sub.staff_id","right outer")
    ->where("houdinv_staff_management.houdinv_staff_auth_token",$CI->session->userdata('vendorAuth'))
    ->get()->row();
   if(($data->$gh) !=1)
   {
   redirect(base_url()."Dashboard", 'refresh');
    }
   // return $rt;
   }
	}
   
   */
    
   public function profileget(){
        $CI = & get_instance();
   return $getSlider = $CI->db->select('*')->from('houdinv_staff_management')->where('houdinv_staff_auth_url_token',$CI->session->userdata('vendorAuth'))->get()->result();

         
    }
    
    }