<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newpostmodel extends CI_Model
{

  function newpost($datas)
  {
    $this->db->insert('course', $datas);
    return true;
  }

  public function fetch_data()
  {
    return $this->db->get('course')->result_array();
  }

  function update_pro($name, $delivery,$lenght,$code,$id,$about){
      
    $data = array(
                   'name' =>$name,
                   'about' => $about,
                   'lenght' => $lenght,
                   'code' => $code,
                   'id' => $id,
                   'delivery' => $delivery,
                   
               );
               
   $this->db->set($data);
   $this->db->where('id',$id);
    $this->db->update('course');
}
  
  public function deleteposts($data)
  {
      $explodData = explode(',',$data);
      $this->db->where_in('id',$explodData);
      $getDeleteStatus = $this->db->delete('course');
      if($getDeleteStatus == 1)
      {
          return array('message'=>'yes');
    }else{
      return array('message'=>'no');
    }
    }
}
