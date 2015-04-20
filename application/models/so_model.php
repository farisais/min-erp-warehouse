<?php
class So_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_so_all()
	{
		$this->db->select('so.*, customer.name');
		$this->db->from('so');
        $this->db->join('customer', 'customer.id_customer=so.customer', 'LEFT');
                
		return $this->db->get()->result_array();
	}
        
        public function get_so_idr_all()
	{
		$this->db->select('so.*, project_list.*, internal_delivery.*');
		$this->db->from('so');
                $this->db->join('project_list', 'project_list.so=so.id_so', 'INNER');
                $this->db->join('internal_delivery', 'internal_delivery.project_list=project_list.id_project_list', 'INNER');
                
		return $this->db->get()->result_array();
	}
    
        public function get_so_open()
    {
        $this->db->select('so.*, customer.name AS customer_name');
	$this->db->from('so');
        $this->db->join('customer', 'so.customer=customer.id_customer', 'INNER');
        
        $this->db->where('so.status =', 'open');
        
        return $this->db->get()->result_array();
    }
    
    public function get_so_deliver()
    {
        $this->db->select('so.*, customer.name AS customer_name');
	$this->db->from('so');
        $this->db->join('customer', 'so.customer=customer.id_customer', 'INNER');
        
        $this->db->where('so.status =', 'deliver');
        
        return $this->db->get()->result_array();
    }
        
        public function add_so($data)
    {
        $this->db->trans_start();
        
        $data_input = array(
            'date' => $data['date'],
            'note' => $data['note'],
            'po_cust' => ($data['po_cust'] == '' ? null : $data['po_cust']),
            'so_number' => $this->generate_so_number(),
            'customer' => $data['customer'],
            'sub_total' => $data['sub_total'],
            'total_price' => $data['total_price'],
            'tax' => $data['tax'],
            'status' => 'draft',
            'user_create' => $this->session->userdata('app_userid'),
            'date_create' => date('Y-m-d H:i:s'),
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
        );
        
        $this->db->insert('so', $data_input);
        $id_so = $this->db->insert_id();
        
        $this->add_so_product($id_so, $data['product_detail']);
            
        //=====================
        
        
        $this->db->trans_complete();
    }
    
    public function add_so_product($id_so, $data_product)
    {
        foreach($data_product as $p)
        {
            $data_input = array(
                'so' => $id_so,
                'product' => $p['id_product'],
                'qty' => $p['qty'],
                'uom' => $p['unit'],
                'unit_price' => $p['unit_price'],
                'total_price' => ($p['unit_price'] * $p['qty'])
            );
            $this->db->insert('so_finish_product', $data_input);
        }
    }
        
    
     public function edit_so($data)
    {
        $this->db->trans_start();
        
        $data_input = array(
            'date' => $data['date'],
            'note' => $data['note'],
            'po_cust' => ($data['po_cust'] == '' ? null : $data_post['po_cust']),
            'so_number' => $data['so_number'],
            'customer' => $data['customer'],
            'sub_total' => $data['sub_total'],
            'total_price' => $data['total_price'],
            'tax' => $data['tax'],
            'user_create' => $this->session->userdata('app_userid'),
            'date_create' => date('Y-m-d H:i:s'),
            'discount_type' => $data_post['discount_type'],
            'discount_value' => $data_post['discount_value'],
        );
        $this->add_so_product($id_so, $data['product_detail']);
        $this->db->where('id_so', $data['id_so']);
        $this->db->update('so', $data_input);
        
        $this->db->trans_complete();
    }
    
     public function delete_so($id)
    {
        $this->delete_product_so($id);
        $this->db->trans_start();
        $this->db->where('id_so', $id);
        
        $this->db->delete('so');
        
        
        $this->db->trans_complete();
    }
    
    
    public function delete_product_so($id)
    {
        $this->db->trans_start();
        $this->db->where('so', $id);
        $this->db->delete('so_finish_product');
    }
    
    
    public function change_so_status($id, $status)
    {
        $this->db->trans_start();
        
        $this->db->where('id_so', $id);
        $this->db->update('so', array('status' => $status));
        
        $this->db->trans_complete();
    }
    
    public function generate_so_number()
    {
        $this->db->select('*');
        $this->db->from('so');
        $this->db->where('YEAR(date)', date('Y'));
        
        $result = $this->db->get()->result_array();
        $countResult = count($result) + 1;
        $zeroCount = '';
        
        for($i=0; $i<4 - strlen($countResult);$i++)
        {
            $zeroCount .= '0';
        }
        
        return ("SO" . date('y') . $zeroCount . $countResult);
    }
    
    public function validate_so($id)
    {
        $this->db->where('id_so', $id);
        $this->db->update('so', array('status' => 'open'));
        
        return array('id_so' => $id, 'status' => 'open');
    }
    
    public function get_so_by_id($id)
    {
        $this->db->select('so.*, customer.name as customer_name');
        $this->db->from('so');
        $this->db->join('customer', 'customer.id_customer=so.customer', 'INNER');
        $this->db->where('so.id_so', $id);
        return $this->db->get()->result_array();
    }
    
    public function get_so_product_by_id($id)
    {
        $this->db->select('so_finish_product.*, product_category.product_category AS category_name , unit_measure.name as unit_name, product.product_code, product.product_name, product.unit');
        $this->db->from('so_finish_product');
        $this->db->join('product', 'so_finish_product.product=product.id_product', 'INNER');
        $this->db->join('unit_measure', 'unit_measure.id_unit_measure=so_finish_product.uom', 'INNER');
        $this->db->join('product_category', 'product_category.id_product_category=product.product_category', 'LEFT');
        $this->db->where('so', $id);
        
        return $this->db->get()->result_array();
    }
    
    //==========================================================================================
    public function get_so_product_open_dn($id_so)
    {

        $query = 'select dp.* from dn_product as dp inner join dn on dn.id_dn=dp.dn inner join so on so.id_so=dn.so where so.id_so=' . $id_so;
        $check_po = $this->db->query($query);
        
        $check_po = $check_po->result_array();
        
        $this->db->select('so_finish_product.*, product.*, unit_measure.name AS unit_name, product_category.product_category AS category_name, m.name');
        $this->db->from('so_finish_product');
        $this->db->join('product', 'so_finish_product.product=product.id_product', 'INNER');
        $this->db->join('unit_measure', 'unit_measure.id_unit_measure=product.unit', 'LEFT');
        $this->db->join('merk as m', 'm.id_merk=product.merk', 'LEFT');
        $this->db->join('product_category', 'product_category.id_product_category=product.product_category', 'LEFT');
        //$this->db->where('dp.product != so_finish_product.product');
        
        if(count($check_po) > 0)
        {
            $this->db->join('dn', 'dn.so=so_finish_product.so', 'INNER');
            $this->db->join('dn_product as dp', 'dp.dn=dn.id_dn', 'INNER');

            $this->db->where('dp.product != so_finish_product.product');
        }
        
        
        $this->db->where('so_finish_product.so', $id_so);
        
        $result = $this->db->get()->result_array();
        
        for($i=0;$i<count($result);$i++)
        {
            $result[$i]['unit_price'] = 0;
        }
        return $result;
        
    }
    
    public function get_pl_product_from_so($id)
    {
        $ci =& get_instance();
        $ci->load->model('material_valuation_model');
        
        $query = 'select plp.*, pl.so, pr.*, m2.name as merk_name, um2.name as unit_name, pc.product_category as category_name ,pl.status,
                    sum(if(pr.unit=plp.uom,plp.qty,plp.qty * (
                    	select if(uc.unit_measure_from = plp.uom, uc.multiplier, uc.multiplier_reverse) from unit_convertion as uc where (uc.unit_measure_from = plp.uom and uc.unit_measure_to = pr.unit) or (uc.unit_measure_to = plp.uom and uc.unit_measure_from = pr.unit))
                    )) as total_qty
                    from project_list_product as plp 
                    inner join project_list as pl on pl.id_project_list = plp.project_list 
                    inner join product as pr on pr.id_product = plp.product
                    inner join product_category as pc on pc.id_product_category=pr.product_category
                    inner join unit_measure as um2 on um2.id_unit_measure = plp.uom
                    inner join merk as m2 on m2.id_merk = pr.merk
                    where pl.status = \'submit\' and pl.so = ' . $id . ' group by plp.product';
        $result_query = $this->db->query($query);
        $pl_product = $result_query->result_array();
        
        for($i=0;$i<count($pl_product);$i++)
        {
            $pl_product[$i]['unit_cogs'] = 0;
            $pl_product[$i]['total_cogs'] = 0;
            if($pl_product[$i]['is_material_valuation'] == 1)
            {
                $product_valuation = $ci->material_valuation_model->get_material_valuation_by_prod($pl_product[$i]['id_product']);
                if(count($product_valuation) > 0)
                {
                    $pl_product[$i]['unit_cogs'] = $product_valuation[0]['valuation'];
                    $pl_product[$i]['total_cogs'] = $pl_product[$i]['total_qty'] * $product_valuation[0]['valuation'];
                }
            }
            else
            {
                $pl_product[$i]['unit_cogs'] = $pl_product[$i]['cost_price'];
                $pl_product[$i]['total_cogs'] = $pl_product[$i]['total_qty'] * $pl_product[$i]['cost_price'];
            }
            
        }
        
        return $pl_product;
        
    }
   
    
}