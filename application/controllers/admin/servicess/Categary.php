<?php
class Categary extends CI_Controller
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

        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/servicess/categary');
        $this->load->view('admin/template/footer');
    }


    public function create()
    {
        $this->load->model('admin/servicess/Newpostmodel');
        $this->input->post('formSubmit');
        


        if (!empty($_FILES['images']['name'])) {

          $File_name ='';

          $config['upload_path'] = APPPATH . '../upload/servicess/main';
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




        $datas = array(
            'name' => $this->input->post('name'),
            'about' => $this->input->post('about'),
            'image' => $imageurl
        );
        if ($this->Newpostmodel->create_cate($datas)) {
            $this->session->set_flashdata('success', 'Category Created');
            redirect(base_url() . 'admin/servicess/categary');
        } else {
            $this->session->set_flashdata('error', 'Error In Updating Please Try Again');
            redirect(base_url() . 'admin/servicess/categary');
        }
    }


    public function addinventory_api(){

        $getPurchaseData = $this->Newpostmodel->fetch_cate();

        foreach ($getPurchaseData as $key => $value) { 
       
            $arrya_json[] = array($value['id'],$value['name'],$value['about'],'<img src="'.base_url().'upload/servicess/main/'.$value['image'].'">',
           '<a class="delete_sliders" data-id="'.$value['id'].'"  style="color: red;cursor: pointer;" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>' );
            }
             echo json_encode(array('data'=>$arrya_json));
        }
  
public function deletepost(){ 

                if($this->input->post('deletesliderId'))
            {
              $this->form_validation->set_rules('deletesliderId','text','required');
              if($this->form_validation->run() == true)
              {
                $getDeleteStatus = $this->Newpostmodel->delete_cate($this->input->post('deletesliderId'));
                if($getDeleteStatus['message'] == 'yes')
                {
                  $this->session->set_flashdata('success','categary  deleted successfully');
                  redirect(base_url()."admin/servicess/categary");
                }
                else
                {
                  $this->session->set_flashdata('error','Something went wrong. Please try again');
                redirect(base_url()."admin/servicess/categary");
                  
                }
              }
              else
              {
                $this->session->set_flashdata('error','Something went wrong. Please try again');
                redirect(base_url()."admin/servicess/categary");

              }
            }
          }



    
}
