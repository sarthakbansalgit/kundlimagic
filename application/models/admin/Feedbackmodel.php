<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class  Feedbackmodel extends CI_Model
{
  function __construct()
  {
  }
  public function fetchinventory_api()
  {
    return $this->db->get('feedback')->result_array();
  }



  public function deletecontactdata($data)
  {
    $explodData = explode(',', $data);
    $this->db->where_in('id', $explodData);
    $getDeleteStatus = $this->db->delete('feedback');
    if ($getDeleteStatus == 1) {
      return array('message' => 'yes');
    } else {
      return array('message' => 'no');
    }
  }
}
