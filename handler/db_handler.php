<?php

require_once  '../db.php';

$action = $_POST['action'] ?? null;

//handel db dashboard actions
if($action === 'update'){

    $tableName = $_POST['table_name'] ?? '';
    $column    = $_POST['column_name'] ?? '';
    $id        = $_POST['id'] ?? '';
    $new_data  = $_POST['new_data'] ?? '';

    update($tableName, $column, $id, $new_data);
}elseif ($action === 'new_table') {
	
	//pull data 
	$tableName = $_POST['table_name'] ?? '';
	$columns = $_POST['columns']?? "";

	newTable($tableName, $columns);

}elseif($action === 'new_column'){

	//pull data 
	$tableName = $_POST['table_name'] ?? '';
	$column = $_POST['column_name']?? "";

	newColumn($tableName, $column);

}elseif($action ==='insert'){

	//pull data 
	$tableName = $_POST['table_name'];
	$dataToInsert = [];

	foreach (explode("\n", $_POST['insert_data']) as $line) {
    	[$key, $value] = explode("=", trim($line), 2);
    	$dataToInsert[$key] = $value;
	}

	insert($tableName, $dataToInsert);
}

?>

<html>
	done
	<style>
		html {
			background-color: #000000;
			color: #ffff;
			display: flex;
			top: %50;
			justify-content: center;
			align-items: center;
		}
	</style>
</html>