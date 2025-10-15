<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newpostmodel extends CI_Model
{

  function create_cate($datas)
  {
    $this->db->insert('gallary_cate', $datas);
    return true;
  }

  public function fetch_cate()
  {
    return $this->db->get('gallary_cate')->result_array();
  }

  
  public function delete_cate($data)
  {
      $explodData = explode(',',$data);
      $this->db->where_in('id',$explodData);
      $getDeleteStatus = $this->db->delete('gallary_cate');
      if($getDeleteStatus == 1)
      {
          return array('message'=>'yes');
    }else{
      return array('message'=>'no');
    }
    }


    function upload_gallary($datas)
  {
    $this->db->insert('gallary', $datas);
    return true;
  }




  public function fetch_gallary()
  {
    return $this->db->get('gallary')->result_array();
  }

  public function delete_gallary($data)
    {
        $explodData = explode(',',$data);
        $this->db->where_in('id',$explodData);
        $getDeleteStatus = $this->db->delete('gallary');
        if($getDeleteStatus == 1)
        {
            return array('message'=>'yes');
      }else{
        return array('message'=>'no');
      }
      }
}
