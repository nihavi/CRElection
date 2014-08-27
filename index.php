<?php
	//CR Election portal
	require_once("config.php");
	function get_candidates() {
		$query = mysqli_prepare($DB, "SELECT id, name FROM `candidates`");
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $id, $name);
		mysqli_stmt_store_result($query);
		$array = array();
		while(mysqli_stmt_fetch($query)){
			$array[$id] = $name;
		}
		return $array;
	}
	if ( allowed() ) {
		$htmlOutput = "<form action='sumbit.php' method='post'>";
		$array = get_candidates();
		foreach ( $array as $id => $name ){
			$htmlOutput .= ("<input type='radio' name='candidate_id' value='".$id."' required >".$name."<br>" );
		}
		$htmlOutput .= "<input type='submit' value='Vote'></form>";
	}
	else {

	}
}

