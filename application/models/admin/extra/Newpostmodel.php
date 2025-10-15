<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newpostmodel extends CI_Model
{


  public function fetch_data()
  {
    return $this->db->get('extra')->result_array();
  }
  public function fetch_form()
  {
    return $this->db->get('extra_form')->result_array();
  }
  function update_pro($name, $about,$welcome, $id, $imageurl){
      
    $data = array(
                   'web_name' =>$name,
                   'about' => $about,
                   
                   
                   'id' => $id,
                   'image' => $imageurl,
                   'welcome' => $welcome,
                   
               );
               
   $this->db->set($data);
   $this->db->where('id',$id);
    $this->db->update('extra');
}
  
  public function deleteposts($data)
  {
      $explodData = explode(',',$data);
      $this->db->where_in('id',$explodData);
      $getDeleteStatus = $this->db->delete('extra_form');
      if($getDeleteStatus == 1)
      {
          return array('message'=>'yes');
    }else{
      return array('message'=>'no');
    }
    }
}
