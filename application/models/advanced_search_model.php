<?php
class Advanced_search_model extends CI_Model {

	public function get_search()
	{
			
				$query_array = array(
				
				'option'=> $this->input->post('option'),
				'basic'=> $this->input->post('basic'),
				'effective' => $this->input->post('effective'),
				'target' => $this->input->post('target'),
				'strategy' => $this->input->post('strategy'),
				'outcomes' => $this->input->post('outcomes')
				);
				
			
				
				
				
				if (strlen($query_array['option']) AND $query_array['option']=="or"){
					
					
			    $this->db->select('*');
				$this->db->from('synopsis_header');
				
				if (strlen($query_array['effective'])){
				$this->db->like('Overall_designation',$query_array['effective'],'both');
				}
				
				if (strlen($query_array['target'])){
				$this->db->or_like('Target_population',$query_array['target'],'both');
				}
				
				if (strlen($query_array['basic'])){
				$this->db->or_like('Delivery_Agents',$match, 'both');
				$this->db->or_like('Initial_Mission',$match, 'both');
                $this->db->or_like('Program_Synopsis',$match, 'both');
				}
				
				} elseif (strlen($query_array['option']) AND $query_array['option']=="and") {
					
					
			    $this->db->select('*');
				$this->db->from('synopsis_header');
				
				if (strlen($query_array['effective'])){
				$this->db->like('Overall_designation',$query_array['effective'],'both');
				}
				
				if (strlen($query_array['target'])){
				$this->db->like('Target_population',$query_array['target'],'both');
				}
				
				if (strlen($query_array['basic'])){
				$this->db->like('Delivery_Agents',$match, 'both');
				$this->db->like('Initial_Mission',$match, 'both');
                $this->db->like('Program_Synopsis',$match, 'both');
				}
					
					
				}
				
	
				$query = $this->db->get();
				
				return $query->result();   
				
	   
		
	}
}


