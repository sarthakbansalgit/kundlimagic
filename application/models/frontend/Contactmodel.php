<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contactmodel extends CI_Model
{
    private $collection_contact = 'contact';
    private $collection_subscribers = 'subscribers';
    private $collection_kundli = 'genrate_kundli';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('mongodb_simple');
    }
    
    function insert_data($name, $email, $number, $sub, $msg)
    {
        $data = array(
            '_id' => $this->mongodb_simple->generate_id(),
            'name' => $name,
            'email' => $email,
            'number' => $number,
            'subject' => $sub,
            'msg' => $msg,
            'created_at' => $this->mongodb_simple->get_timestamp()
        );
        return $this->mongodb_simple->insert($this->collection_contact, $data);
    }

    function insert_subscriber($email)
    {
        $data = array(
            '_id' => $this->mongodb_simple->generate_id(),
            'email' => $email,
            'created_at' => $this->mongodb_simple->get_timestamp()
        );
        return $this->mongodb_simple->insert($this->collection_subscribers, $data);
    }

    function generate_kundli($data)
    {
        $data['_id'] = $this->mongodb_simple->generate_id();
        $data['created_at'] = isset($data['created_at']) ? $data['created_at'] : $this->mongodb_simple->get_timestamp();
        return $this->mongodb_simple->insert($this->collection_kundli, $data);
    }

    public function fetch_kundli_form_details() {
        return $this->mongodb_simple->find($this->collection_kundli);
    }
    
    public function get_kundli_by_order_id($orderId) {
        return $this->mongodb_simple->find_one($this->collection_kundli, ['merchantOrderId' => $orderId]);
    }
    
    public function update_kundli_by_order_id($orderId, $data) {
        return $this->mongodb_simple->update_one($this->collection_kundli, ['merchantOrderId' => $orderId], $data);
    }
}
