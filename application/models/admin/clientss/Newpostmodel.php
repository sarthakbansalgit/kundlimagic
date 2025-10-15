<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newpostmodel extends CI_Model
{

  function newpost($datas)
  {
    $this->db->insert('client', $datas);
    return true;
  }

  public function fetch_data()
  {
    return $this->db->get('client')->result_array();
  }

  
  public function deleteposts($data)
  {
      $explodData = explode(',',$data);
      $this->db->where_in('id',$explodData);
      $getDeleteStatus = $this->db->delete('client');
      if($getDeleteStatus == 1)
      {
          return array('message'=>'yes');
    }else{
      return array('message'=>'no');
    }
    }
}
