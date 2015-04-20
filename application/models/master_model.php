<?php
class Master_model extends CI_Model
{
    public function __construct()
	{
		parent::__construct();
	}
    
    public function get_division_all()
    {
        $this->db->select('*');
        $this->db->from('division');
        
        return $this->db->get()->result_array();
    }
}
?>