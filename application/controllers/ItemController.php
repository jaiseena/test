<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url'); 
    }

    public function index() {
  
    $this->load->library('form_validation');

   
    if (method_exists($this->user_model, 'get_items')) {
       
        $data['items'] = $this->user_model->get_items();
    } else {
        
        $data['items'] = [];
    }

   
    $this->load->view('add_item.html', $data);
}


public function process_registration() {
    $this->load->library('form_validation');

    $this->form_validation->set_rules('itemname', 'Item Name', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('add_item.html');
    } else {
        $itemname = $this->input->post('itemname');
        $description = $this->input->post('description');

        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            // Show alert message using JavaScript
            echo '<script>alert("Only GIF, JPG, JPEG, and PNG file types are allowed.");</script>';
            // Reload the registration view
            echo '<script>window.location.href="'.base_url('ItemController').'";</script>';
        } else {
            $image_data = $this->upload->data();
            $image_path = 'upload/' . $image_data['file_name'];

            $user_data = array(
                'item_name' => $itemname,
                'description' => $description,
                'image' => $image_path
            );
            $this->user_model->register($user_data);
echo '<script>alert("Item Successfully Added");</script>';
echo '<script>window.location.href="'.base_url('ItemController').'";</script>';

        }
    }
}



    public function delete_item($item_id) {
       
        $this->user_model->delete_item($item_id); 
        echo '<script>alert("Delete Successfully");</script>';
        echo '<script>window.location.href="'.base_url('ItemController').'";</script>';
    }

    public function edit_item($item_id) {
     
        $this->load->library('form_validation');
    
       
        $data['item'] = $this->user_model->get_item($item_id);
    
        
        $this->load->view('edit_item.html', $data);
    }
    public function update_item($item_id) {
        
        $this->load->library('form_validation');
    
       
        $this->form_validation->set_rules('itemname', 'Item Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
    
        
        if ($this->form_validation->run() == FALSE) {
           
            $this->load->view('edit_item.html');
        } else {
           
            $itemname = $this->input->post('itemname');
            $description = $this->input->post('description');
    
           
            if (!empty($_FILES['userfile']['name'])) {
                
                $config['upload_path'] = './upload/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; 
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
    
               
                if ($this->upload->do_upload('userfile')) {
                    
                    $image_data = $this->upload->data();
                    $image_path = 'upload/' . $image_data['file_name'];
    
                    
                    $update_data = array(
                        'item_name' => $itemname,
                        'description' => $description,
                        'image' => $image_path
                    );
                } else {
                   
                    $error = array('error' => $this->upload->display_errors());
                    $this->load->view('edit_item.html', $error);
                    return; 
                }
            } else {
                
                $update_data = array(
                    'item_name' => $itemname,
                    'description' => $description
                );
            }
    
           
            $this->user_model->update_item($item_id, $update_data);
    
           
            echo '<script>alert("Updated Successfully");</script>';
        echo '<script>window.location.href="'.base_url('ItemController').'";</script>';
        }
    }
    
    
    
}
?>
