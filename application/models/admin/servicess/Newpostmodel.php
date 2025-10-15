<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newpostmodel extends CI_Model
{

  function create_cate($datas)
  {
    $this->db->insert('service_cate', $datas);
    return true;
  }

  public function fetch_cate()
  {
    return $this->db->get('service_cate')->result_array();
  }

  
  public function delete_cate($data)
  {
      $explodData = explode(',',$data);
      $this->db->where_in('id',$explodData);
      $getDeleteStatus = $this->db->delete('service_cate');
      if($getDeleteStatus == 1)
      {
          return array('message'=>'yes');
    }else{
      return array('message'=>'no');
    }
    }


    function newpost($datas)
  {
    $this->db->insert('service', $datas);
    return true;
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
    $this->db->update('service');
}

  public function fetch_gallary()
  {
    return $this->db->get('service')->result_array();
  }

  public function delete_gallary($data)
    {
        $explodData = explode(',',$data);
        $this->db->where_in('id',$explodData);
        $getDeleteStatus = $this->db->delete('service');
        if($getDeleteStatus == 1)
        {
            return array('message'=>'yes');
      }else{
        return array('message'=>'no');
      }
      }
}
