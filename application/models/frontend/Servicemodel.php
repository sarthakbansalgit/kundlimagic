<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicemodel extends CI_Model {

    public function fetch() {
    return $this->db->get('service')->result_array();
    }
    public function fetch_cate() {
        return $this->db->get('service_cate')->result_array();
        }
  
    public function blog_detail($slug = '') {
        return $this->db->where('link',$slug)->get('service')->row();
       
      }

}