<?php
class Customer_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_customer_all()
	{
		$this->db->select('customer.*');
		$this->db->from('customer');
                
                
		return $this->db->get()->result_array();
	}
    
    public function add_customer($data)
    {
        $this->db->trans_start();
        
        $data_input = array(
            "customer_code" => $data['customer_code'],
            "name" => $data['name'],
            "adress" => $data['adress'],
            "city" => $data['city'],
            "contact" => $data['contact'],
            "tlp" => $data['tlp'],
            "fax" => $data['fax'],
            "email" => $data['email'],
            "handphone" => $data['handphone']
        );
        
        $this->db->insert('customer', $data_input);
        
        $this->db->trans_complete();
    }
    
    public function edit_customer($data)
    {
        $this->db->trans_start();
        
        $data_input = array(
            "customer_code" => $data['customer_code'],
            "name" => $data['name'],
            "adress" => $data['adress'],
            "city" => $data['city'],
            "contact" => $data['contact'],
            "tlp" => $data['tlp'],
            "fax" => $data['fax'],
            "email" => $data['email'],
            "handphone" => $data['handphone']
        );
        
        $this->db->where('id_customer', $data['id_customer']);
        $this->db->update('customer', $data_input);
        
        $this->db->trans_complete();
    }
    
    public function delete_customer($id)
    {
        $this->db->trans_start();
        $this->db->where('id_customer', $id);
        
        $this->db->delete('customer');
        
        $this->db->trans_complete();
    }
    
    public function get_customer_by_id($id)
    {
        $this->db->select('customer.*');
		$this->db->from('customer');
        $this->db->where('id_customer', $id);
                
		return $this->db->get()->result_array();
    }
}