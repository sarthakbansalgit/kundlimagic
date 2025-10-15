<?php
class Editblog extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('admin/login');
        }
        
        $this->load->model('admin/news/Newpostmodel');
    }

    public function index()
    {



        $data['fetch_content'] = $this->Newpostmodel->fetch_data();
        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/news/editblog',$data);
        $this->load->view('admin/template/footer');
    }

    public function update()
    {
        $this->load->model('admin/news/Newpostmodel');

        $this->input->post('formSubmit');


        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('about', 'Email', 'required');
       
        $this->form_validation->set_rules('id', 'Id', 'required');
       
        if ($this->form_validation->run()) {

            if (!empty($_FILES['images']['name'])) {
                $File_name = '';
                $config['upload_path'] = APPPATH . '../upload/news';
                $config['file_name'] = $File_name;
                $config['overwrite'] = TRUE;
                $config["allowed_types"] = 'jpeg|jpg|png';
                $config["max_size"] = 2048;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('images')) {
                    $this->data['error'] = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('admin/news/newpost');
                } else {
                    $dataimage_return = $this->upload->data();
                    $imageurl =  $dataimage_return['file_name'];
                }
            } else {
                $data = $this->Newpostmodel->fetch_data();

                foreach ($data as $value) {
                    if ($value['id'] == $this->input->post('id')) {
                        $imageurl = $value['cover'];
                    }
                }
            }

            $name = $this->input->post('name');
        
            $about = $this->input->post('about');
            
            $id = $this->input->post('id');
             
           
            if ($this->Newpostmodel->update_pro($name, $about, $id, $imageurl)) {



                $this->session->set_flashdata('success', 'Technical error');
                redirect(base_url() . 'admin/news/allpost');
            } else {
                $this->session->set_flashdata('success', 'Updated Successfully');
                redirect(base_url() . 'admin/news/allpost');
            }
        } else {
            $this->session->set_flashdata('error', 'Please Fill all Fields');
            redirect(base_url() . 'admin/news/allpos');
        }
    }
}
