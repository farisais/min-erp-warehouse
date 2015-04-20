<?php
class Bank_model extends CI_Model
{
 public function get_bank_all()
    {
        $this->db->select('*');
		$this->db->from('bank');
		return $this->db->get()->result_array();
    }
    
    public function add_bank($data_post)
    {
        $this->db->trans_start();
        $data = array(
            'bank_name' => $data_post['bank_name'],
            'bank_account' => $data_post['bank_account'],
            'bank_user' => $data_post['bank_user'],   
        );
        
        $this->db->insert('bank', $data);
        $this->db->trans_complete();
    }
    
    public function edit_bank($data_post)
    {
        $this->db->trans_start();
        $data = array(
            'bank_name' => $data_post['bank_name'],
            'bank_account' => $data_post['bank_account'],
            'bank_user' => $data_post['bank_user'],
        );
        
        $this->db->where('id_bank', $data_post['id_bank']);
        $this->db->update('bank', $data);
        $this->db->trans_complete();
    }
    
    public function delete_bank($id)
    {
        $this->db->trans_start();
        $this->db->where('id_bank', $id);
        $this->db->delete('bank');
        $this->db->trans_complete();
    }
    
    public function get_bank_by_id($id)
    {
        $this->db->select('*');
		$this->db->from('bank');
        $this->db->where('id_bank', $id);
		return $this->db->get()->result_array();
    }
}
