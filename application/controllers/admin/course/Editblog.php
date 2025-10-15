<?php
class Editblog extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('admin/login');
        }
        
        $this->load->model('admin/course/Newpostmodel');
    }

    public function index()
    {



        $data['fetch_content'] = $this->Newpostmodel->fetch_data();
        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/course/editblog',$data);
        $this->load->view('admin/template/footer');
    }

    public function update()
    {
        $this->load->model('admin/course/Newpostmodel');

        $this->input->post('formSubmit');


        
        

              $name = $this->input->post('name');
             $delivery = $this->input->post('delivery');
              $lenght = $this->input->post('lenght');
              $code = $this->input->post('code');
              $id = $this->input->post('id');
               $about = $this->input->post('about');

             
            
            if ($this->Newpostmodel->update_pro($name, $delivery,$lenght,$code,$id,$about)) {



                $this->session->set_flashdata('success', 'Technical error');
                redirect(base_url() . 'admin/course/allpost');
            } else {
                $this->session->set_flashdata('success', 'Updated Successfully');
                redirect(base_url() . 'admin/course/allpost');
            }
        
    }
}
