<?php
class Advanced_search_model extends CI_Model {

	public function get_search()
	{
			
				$match = $this->input->post('search');
				
				$this->db->select('*');
				$this->db->from('synopsis_header');
				$this->db->like('Overall_designation',$match);
				$this->db->or_like('Delivery_Agents',$match);
				$this->db->or_like('Initial_Mission',$match);
				$this->db->or_like('Target_population',$match);
                $this->db->or_like('Program_Synopsis',$match);
	
				$query = $this->db->get();
				
				return $query->result();   
	   
		
	}
}


