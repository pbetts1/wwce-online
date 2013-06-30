<?php
class Get_study_model extends CI_Model {

	public function get_study()
	{
			
				$ProgramID = $this->uri->segment(3);
				
				$this->db->select('*');
				$this->db->from('synopsis_header');
				$this->db->where('ProgramID',$ProgramID);
					
				$query = $this->db->get();
				
				return $query->result();   
	  
	}
}

