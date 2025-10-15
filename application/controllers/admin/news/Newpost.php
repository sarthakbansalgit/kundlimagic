<?php
class Newpost extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('login');
        }
        
        $this->load->model('admin/news/Newpostmodel');
    }

    public function index()
    {

        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/news/newpost');
        $this->load->view('admin/template/footer');
    }


    public function post()
    {
        $this->load->model('admin/news/newpostmodel');
        $this->input->post('formSubmit');
        if (!empty($_FILES['images']['name'])) {

            $File_name = 'course-' . strtotime(date('YmdHis'));

            $config['upload_path'] = APPPATH . '../upload/news';
            $config['file_name'] = $File_name;
            $config['overwrite'] = TRUE;
            $config["allowed_types"] = 'png|jpg|jpeg';
            $config["max_size"] = '6144';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('images')) {

                $this->data['error'] = $this->upload->display_errors();
                $this->session->set_flashdata('error', $this->upload->display_errors());

                redirect(base_url().'admin/news/newpost');
            } else {
                $dataimage_return = $this->upload->data();
                $imageurl = $dataimage_return['file_name'];
            }
        }

        
        $link = $this->input->post('name');
        $link = str_replace(' ', '-', $link);

       

        $datas = array(
            'name' => $this->input->post('name'),
            'about' => $this->input->post('about'),
            
            'link' => $link,
            'cover' => $imageurl,
            
        );
        if ($this->newpostmodel->newpost($datas)) {
            $this->session->set_flashdata('success', 'Course Created');
            redirect(base_url() . 'admin/news/newpost');
        } else {
            $this->session->set_flashdata('error', 'Error In Updating Please Try Again');
            redirect(base_url() . 'admin/news/newpost');
        }
    }



    
}
