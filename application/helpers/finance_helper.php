<?php

 function get_active_year()
{
	$CI =& get_instance();

	$query=$CI->db->query("select from_date,to_date from financial_details where active=1");
	return $query->result();
}

 function get_vat_percentage()
    {
        $CI =& get_instance();
        $query=$CI->db->query('select tax_value from tax_data_entity where tax_code=2 order by sno desc limit 1;');
        return $query->row('tax_value');
    }

 	
 function get_name_record($emptype, $user_id) {
		$CI =& get_instance();
		
		 if($emptype == 'VIS') {
			$query=$CI->db->query("select occu_name as name from bmwregistration where occupier_id = '$user_id'");
		}
		
		else if($emptype == 'SUPP') {
			$query=$CI->db->query("select supp_name as name from bmwregistration; where supp_id = '$user_id'");
		}
		
		return $query->row('name');
	}	
	

?>