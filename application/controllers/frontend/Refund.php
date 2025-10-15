<?php
class Refund extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('admin/Plansmodel');
    }
    public function index()
    {
        //$data['course'] = $this->Plansmodel->fetch_plans();
        // $data['plans'] = $this->Plansmodel->fetchinventory_api();
        $this->load->view('frontend/template/header');
        $this->load->view('frontend/template/navbar');
        $this->load->view('frontend/refund');
        $this->load->view('frontend/template/footer');
    }
}
