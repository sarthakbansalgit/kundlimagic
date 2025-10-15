<?php
class Newpost extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('login');
        }
        
        $this->load->model('admin/course/Newpostmodel');
    }

    public function index()
    {

        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/course/newpost');
        $this->load->view('admin/template/footer');
    }


    public function post()
    {
        $this->load->model('admin/newpostmodel');
        $this->input->post('formSubmit');

        
        $link = $this->input->post('name');
        $link = str_replace(' ', '-', $link);

        $datas = array(
            'name' => $this->input->post('name'),
            'about' => $this->input->post('about'),
            'cate' => $this->input->post('cate'),
            'delivery' => $this->input->post('delivery'),
            'lenght' => $this->input->post('lenght'),
            'code' => $this->input->post('code'),
            
           
            'link' => $link,
          
            
        );
        if ($this->newpostmodel->newpost($datas)) {
            $this->session->set_flashdata('success', 'Course Created');
            redirect(base_url() . 'admin/course/newpost');
        } else {
            $this->session->set_flashdata('error', 'Error In Updating Please Try Again');
            redirect(base_url() . 'admin/course/newpost');
        }
    }



    
}
