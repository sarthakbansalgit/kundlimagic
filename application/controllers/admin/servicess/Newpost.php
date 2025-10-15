<?php
class Newpost extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('login');
        }
        
        $this->load->model('admin/servicess/Newpostmodel');
    }

    public function index()
    {
        $data['fetch_cate'] = $this->Newpostmodel->fetch_cate();
        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/servicess/newpost',$data);
        $this->load->view('admin/template/footer');
    }
    public function post()
    {
        $this->load->model('admin/servicess/newpostmodel');
        $this->input->post('formSubmit');
        if (!empty($_FILES['images']['name'])) {

            $File_name ='';

            $config['upload_path'] = APPPATH . '../upload/servicess/sub';
            $config['file_name'] = $File_name;
            $config['overwrite'] = TRUE;
            $config["allowed_types"] = 'png|jpg|jpeg';
            $config["max_size"] = '6144';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('images')) {

                $this->data['error'] = $this->upload->display_errors();
                $this->session->set_flashdata('error', $this->upload->display_errors());

                redirect(base_url().'admin/servicess/newpost');
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
            'cate' => $this->input->post('cate'),
            
            'link' =>   $link,
            'cover' => $imageurl,
            
        );
        if ($this->newpostmodel->newpost($datas)) {
            $this->session->set_flashdata('success', 'Service Created');
            redirect(base_url() . 'admin/servicess/newpost');
        } else {
            $this->session->set_flashdata('error', 'Error In Updating Please Try Again');
            redirect(base_url() . 'admin/servicess/newpost');
        }
    }



    
}
