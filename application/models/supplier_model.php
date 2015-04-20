<?php
class Supplier_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_supplier_all()
	{
		$this->db->select('supplier.*');
		$this->db->from('supplier');
                
                
		return $this->db->get()->result_array();
	}
    
    public function add_supplier($data)
    {
        $this->db->trans_start();
        
        $data_input = array(
            "supplier_code" => $data['supplier_code'],
            "name" => $data['name'],
            "address" => $data['address'],
            "city" => $data['city'],
            "npwp" => $data['npwp'],
            "contact" => $data['contact'],
            "tlp" => $data['tlp'],
            "handphone" => $data['handphone'],
            "fax" => $data['fax'],
            "email" => $data['email'],
            "rekening" => $data['rekening']
        );
        
        $this->db->insert('supplier', $data_input);
        
        $this->db->trans_complete();
    }
    
    public function edit_supplier($data)
    {
        $this->db->trans_start();
        
        $data_input = array(
            "supplier_code" => $data['supplier_code'],
            "name" => $data['name'],
            "address" => $data['address'],
            "city" => $data['city'],
            "npwp" => $data['npwp'],
            "contact" => $data['contact'],
            "tlp" => $data['tlp'],
            "handphone" => $data['handphone'],
            "fax" => $data['fax'],
            "email" => $data['email'],
            "rekening" => $data['rekening']
        );
        
        $this->db->where('id_supplier', $data['id_supplier']);
        $this->db->update('supplier', $data_input);
        
        $this->db->trans_complete();
    }
    
    public function delete_supplier($id)
    {
        $this->db->trans_start();
        $this->db->where('id_supplier', $id);
        
        $this->db->delete('supplier');
        
        $this->db->trans_complete();
    }
    
    public function get_supplier_by_id($id)
    {
        $this->db->select('supplier.*');
		$this->db->from('supplier');
        $this->db->where('id_supplier', $id);
                
		return $this->db->get()->result_array();
    }
}