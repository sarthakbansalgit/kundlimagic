<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newpostmodel extends CI_Model
{

  function newpost($datas)
  {
    $this->db->insert('news', $datas);
    return true;
  }

  public function fetch_data()
  {
    return $this->db->get('news')->result_array();
  }

  function update_pro($name, $about, $id, $imageurl){
      
    $data = array(
                   'name' =>$name,
                   'about' => $about,
                   
                   
                   'id' => $id,
                   'cover' => $imageurl,
                   
               );
               
   $this->db->set($data);
   $this->db->where('id',$id);
    $this->db->update('news');
}
  
  public function deleteposts($data)
  {
      $explodData = explode(',',$data);
      $this->db->where_in('id',$explodData);
      $getDeleteStatus = $this->db->delete('news');
      if($getDeleteStatus == 1)
      {
          return array('message'=>'yes');
    }else{
      return array('message'=>'no');
    }
    }
}
