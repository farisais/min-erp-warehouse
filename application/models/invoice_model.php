<?php
class Invoice_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_invoice_all()
	{
		$this->db->select('invoice.*, so.so_number, bank.bank_account');
		$this->db->from('invoice');
                $this->db->join('so', 'so.id_so=invoice.so', 'LEFT');
                $this->db->join('bank', 'bank.id_bank=invoice.rekening', 'LEFT');
                
		return $this->db->get()->result_array();
	}
    
    public function save_invoice($data)
    {
        $this->db->trans_start();
        $ci =& get_instance();
        $ci->load->model('so_model');
        
        $so = $ci->so_model->get_so_by_id($data['id_so']);
        
        $data_input = array(
            "so" => $data['id_so'],
            "invoice_date" => $data['date'],
            "sub_total" => $so[0]['sub_total'],
            "tax" => $so[0]['tax'],
            "total_price" => $so[0]['total_price'],
            "total_payment" => $data['total_payment'],
            "invoice_receipt_number" => $this->generate_invoice_number(),
            "invoice_method" => $data['invoice_method'],
            "rekening" => ($data['rekening'] == '' ? null : $data['rekening']),
            "status" => 'open',
        );
        
        $this->db->insert('invoice', $data_input);
        $return_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        return $return_id;
    }
    
    public function generate_invoice_number()
    {
        $this->db->select('*');
        $this->db->from('invoice');
        $this->db->where('YEAR(invoice_date)', date('Y'));
        
        $result = $this->db->get()->result_array();
        $countResult = count($result) + 1;
        $zeroCount = '';
        
        for($i=0; $i<4 - strlen($countResult);$i++)
        {
            $zeroCount .= '0';
        }
        
        return ("INV" . date('y') . $zeroCount . $countResult);
    }
    
    public function delete_invoice($id)
    {
        $this->db->trans_start();
        $this->db->where('id_invoice', $id);
        $this->db->delete('invoice');
        $this->db->trans_complete();
    }
    
    public function get_invoice_by_id($id)
    {
        $this->db->select('invoice.*, so.so_number, bank.bank_account, customer.name as customer_name, customer.adress as address');
        $this->db->from('invoice');
        $this->db->join('so', 'invoice.so=so.id_so', 'LEFT');
        $this->db->join('customer', 'customer.id_customer=so.customer', 'LEFT');
        $this->db->join('bank', 'invoice.rekening=bank.id_bank', 'LEFT');
        $this->db->where('invoice.id_invoice', $id);
                
	return $this->db->get()->result_array();
    }
    
    public function edit_invoice($data)
    {
        $this->db->trans_start();
        $ci =& get_instance();
        $ci->load->model('so_model');
                
        $so = $ci->so_model->get_so_by_id($data['id_so']);
        
        $data_input = array(
            "so" => $data['id_so'],
            "invoice_date" => $data['date'],
            "sub_total" => $so[0]['sub_total'],
            "tax" => $so[0]['tax'],
            "total_price" => $so[0]['total_price'],
            "total_payment" => $data['total_payment'],
            "invoice_method" => $data['invoice_method'],
            "rekening" => ($data['rekening'] == '' || $data['rekening'] == null ? '' : $data['rekening']),
            "status" => 'open',
            );
        $this->db->where('id_invoice', $data['id_invoice']);
        $this->db->update('invoice', $data_input);
        $this->db->trans_complete();
    }
    
    public function get_invoice_product_by_id($id)
    {
        $ci =& get_instance();
        $ci->load->model('so_model');
        
        $this->db->select('invoice_product.*, invoice_product.qty AS qty_send, invoice_product.uom as unit, unit_measure.name as unit_name, product.*');
        $this->db->from('invoice_product');
        $this->db->join('product', 'product.id_product=invoice_product.product', 'INNER');
        $this->db->join('unit_measure', 'unit_measure.id_unit_measure=invoice_product.uom', 'INNER');
        $this->db->where('invoice_receipt', $id);
        
        $result = $this->db->get()->result_array();
        
        for($i=0;$i<count($result);$i++)
        {
            $so_product = $ci->so_model->get_so_product_by_id($this->get_id_so_from_invoice($id)[0]['so']);
            
            for($j=0;$j<count($so_product);$j++)
            {
                if($so_product[$j]['product'] == $result[$i]['product'])
                {
                    $result[$i]['qty_ordered'] = $so_product[$j]['qty'];
                }
            }
        }
        return $result;
    }
    
    public function get_id_so_from_invoice($id_invoice)
    {
        $this->db->select('so');
        $this->db->from('invoice');
        $this->db->where('id_invoice', $id_invoice);
        
        return $this->db->get()->result_array();
    }
    
    public function change_invoice_status($id, $status)
    {
        $this->db->where('id_invoice', $id);
        $this->db->update('invoice', array("status" => $status));
        
        return null;
    }
    
    public function get_invoice_history($id_so, $exclude_id = null)
    {
        $inv = null;
        if($exclude_id !=null)
        {
            $inv = $this->get_invoice_by_id($exclude_id);
        }
        
        $this->db->select('invoice.*, so.so_number');
	$this->db->from('invoice');
        $this->db->join('so', 'so.id_so=invoice.so', 'LEFT');
        
        $this->db->where('invoice.so', $id_so);
        $this->db->where('invoice.status', 'close');
        if($exclude_id !=null)
        {
            $this->db->where('invoice.id_invoice !=', $exclude_id);
            $this->db->where('DATE(invoice.invoice_date) <= DATE(\''. $inv[0]['invoice_date'] . '\')', null, false );
            $this->db->where('invoice.id_invoice <', $inv[0]['id_invoice']);
        }
                
		return $this->db->get()->result_array();
    }
    
    public function get_invoice_left($id_so, $exclude_id = null)
    {
        $inv = null;
        if($exclude_id !=null)
        {
            $inv = $this->get_invoice_by_id($exclude_id);
        }
        
        $this->db->select('invoice.*, so.so_number');
        $this->db->from('invoice');
        $this->db->join('so', 'so.id_so=invoice.so', 'LEFT');
        $this->db->where('invoice.so', $id_so);
        $this->db->where('invoice.status', 'close');
        if($exclude_id!=null)
        {
            $this->db->where('invoice.id_invoice !=', $exclude_id);
            $this->db->where('DATE(invoice.invoice_date) <= DATE(\''. $inv[0]['invoice_date'] . '\')', null, false );
            $this->db->where('invoice.id_invoice <', $inv[0]['id_invoice']);
        }
                
		$inv = $this->db->get()->result_array();
        $total = 0;
        foreach($inv as $p)
        {
            $total += $p['total_payment'];
        }
        
        $ci =& get_instance();
        $ci->load->model('so_model');
        
        $so = $ci->so_model->get_so_by_id($id_so);
        
        return $so[0]['total_price'] - $total;
    }
    
    public function validate_invoice($id_invoice)
    {   
        $inv = $this->get_invoice_by_id($id_invoice);
        $this->db->trans_start();
         
        $this->db->where('id_invoice', $id_invoice);
        $this->db->update('invoice', array('status' => 'close'));
         
        
        
        if($this->get_invoice_left($inv[0]['so']) == 0)
        {   
            //close SO
            $ci =& get_instance();
            $ci->load->model('so_model');
        
            $so = $ci->so_model->get_so_by_id($inv[0]['so']);
            if($so[0]['status'] == 'deliver')
            {
                $ci->so_model->change_so_status($inv[0]['so'], 'close');
            }
           
        }
        
        $this->db->trans_complete();
    }
    
    public function cancel_invoice($id)
    {
        $this->db->trans_start();
        $this->db->where('id_invoice', $id);
        $this->db->update('invoice', array("status" => "cancel"));
        $this->db->trans_complete();
        
        $inv = $this->get_invoice_by_id($id);
        $ci =& get_instance();
        $ci->load->model('so_model');

        $so = $ci->so_model->get_so_by_id($inv[0]['so']);
        if($so[0]['status'] == 'close')
        {
            $ci->so_model->change_so_status($inv[0]['so'], 'deliver');
        }
        
    }
}