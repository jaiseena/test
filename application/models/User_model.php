<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function register($user_data) {
        $this->db->insert('items', $user_data);
    }
    public function get_items() {
        $query = $this->db->get('items'); // Assuming 'items' is the name of your items table
        return $query->result(); // Return the query result as an array of objects
    }
    public function delete_item($item_id) {
        $this->db->where('id', $item_id);
        $this->db->delete('items');
    }
    public function get_item($item_id) {
        $query = $this->db->get_where('items', array('id' => $item_id));
        return $query->row();
    }
    public function update_item($item_id, $update_data) {
        $this->db->where('id', $item_id);
        $this->db->update('items', $update_data);
    }
    
    
}
