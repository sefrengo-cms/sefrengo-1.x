<?php
class SF_TEST_ConstructorParms extends SF_API_Object{
		
	function SF_TEST_ConstructorParms($p1, $p2, $p3 ){
		echo '<pre>';
		echo "p1 is string: $p1 \n";
		echo "p2 is array: \n"; print_r($p2);
		echo "p3 is string: $p3 \n";
		echo '</pre>';
	}
}

?>