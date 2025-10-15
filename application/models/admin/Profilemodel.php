<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profilemodel extends CI_Model{
    function __construct() {
    }


    public function fetchprofile(){
        
        return $getSlider = $this->db->select('*')->from('houdinv_staff_management')->get()->result();
       
    
    
    }
    
    public function insert_slider($vlues){
        
       $getInsertData= $this->db->insert('houdinv_staff_management',$vlues);   
            if($getInsertData == 1)
            {
                 return true;
            }
            else
            {
                return false;
            } 
        
        
    }
    
     public function updte_profile($vl){      
        
         
         $getInsertData= $this->db->update('houdinv_staff_management',$vl);   
            if($getInsertData == 1)
            {
                 return true;
            }
            else
            {
                return false;
            } 
        
      } 
      
      
       public function update_pass_staff($vl){      
        
         
         $getInsertData= $this->db->update('houdinv_staff_management',$vl);   
            if($getInsertData == 1)
            {
                 return true;
            }
            else
            {
                return false;
            } 
        
      } 
      
      
    
     public function updte_profile_img($vl){      
        
        
         $getInsertData= $this->db->update('houdinv_staff_management',$vl);   
            if($getInsertData == 1)
            {
                 return true;
            }
            else
            {
                return false;
            } 
        
      }
    
    
      public function updateUserPass($data)
      {
          
           
              $getUserId = 1;
              $getUserPass = $data['passwordData'];
              $letters1='abcdefghijklmnopqrstuvwxyz'; 
              $string1=''; 
              for($x=0; $x<3; ++$x)
              {  
                  $string1.=$letters1[rand(0,25)].rand(0,9); 
              }
              $saltdata = password_hash($string1,PASSWORD_DEFAULT);
              $pass = crypt($getUserPass,$saltdata);
              $setDate = strtotime(date('Y-m-d h:i:s'));
              $setUpdatedArray = array('staff_password'=>$pass,'staff_password_salt'=>$saltdata,'update_date'=>$setDate);
             // $this->db->where('staff_id',$getUserId);
              $getUserUpdateStatus  =$this->db->update('houdinv_staff_management',$setUpdatedArray);
              if($getUserUpdateStatus == 1)
              {
                return array('message'=>'yes');   
              }
              else
              {
                  return array('message'=>'no');
              }
          
      }
    
    
    }
    ?>