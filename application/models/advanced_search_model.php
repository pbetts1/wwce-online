<?php
class Advanced_search_model extends CI_Model {

//The model for the advanced search page 
//Controller: advanced.php
//Views:basicSearch_view.php

	public function get_search()
	{
			//Get the form varibles and put them in an associative array
				$query_array = array(
				
				'option'=> $this->input->post('option'),
				'basic'=> $this->input->post('basic'),
				'effective' => $this->input->post('effective'),
				'target' => $this->input->post('target'),
				'strategy' => $this->input->post('strategy'),
				'outcomes' => $this->input->post('outcomes')
				);
		
		//Check if this is an and or search
				if (strlen($query_array['option']) AND $query_array['option']=="or"){
					
		//start query			
			    $this->db->select('*');
				$this->db->from('synopsis_header');

               //if this the Intervention Effectiveness field is selected add it to the query				
				if (strlen($query_array['effective'])){
				$this->db->like('Overall_designation',$query_array['effective'],'both');
				}
			
			   //if this the Grade Level Intervention field is selected add it to the query		
				if (strlen($query_array['target'])){
				$this->db->or_like('Target_population',$query_array['target'],'both');
				}
				
			 //if this the Basic field is has text add it to the query		
				if (strlen($query_array['basic'])){
				$match = $query_array['basic'];
				$this->db->or_like('Delivery_Agents',$match, 'both');
				$this->db->or_like('Initial_Mission',$match, 'both');
                $this->db->or_like('Program_Synopsis',$match, 'both');
				}
				
				//"and" search	
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
				$match = $query_array['basic'];	
				$this->db->like('Delivery_Agents',$match, 'both');
				$this->db->like('Initial_Mission',$match, 'both');
                $this->db->like('Program_Synopsis',$match, 'both');
				}
					
					
				}
				
	            //get query results
				$query = $this->db->get();
				
				//return query results
				return $query->result();   
				
	   
		
	}
}


