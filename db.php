	<?php

$baseDIR = __DIR__;


//helper functions
require_once __DIR__ .'/functions.php';


//functions to manage db


//create table
function newTable($tableName, $columns = ""){
	global $baseDIR;


	//check if table already exists
	if (is_dir("$baseDIR/data/$tableName")) {
		die("VastDB Error: The table already exists");
	}
	

	//create table folder
	if(!mkdir("$baseDIR/data/$tableName")){
		die('VastDB Error: There was an error occured while creating table check if there is any unallowed chars in the folder name');
	} 

	


	//create columns check if user enterd first
	if($columns !== null && $columns !== ""){
		$columns = explode(",", $columns);
		$createdColumns = [];

		//create each columns folder and add it to meta data
		foreach ($columns as $column) {
			$column = trim($column);
			if (mkdir("$baseDIR/data/$tableName/$column")) {
				//column created succesfully
				$createdColumns[] = $column;
				//add a data json in it
				file_put_contents("$baseDIR/data/$tableName/$column/data.vastdb", "{}");
				
			}else{

				//delete left over files
				//check if any columns created
				if(empty($createdColumns)){
					//no columns created yet delete the table
					rmdir("$baseDIR/data/$tableName");
				}else{
					//delete each created column
					foreach ($createdColumns as $createdColumn) {
						rmdir("$baseDIR/data/$tableName/$createdColumn");
					}

					//as final delete table folder
					rmdir("$baseDIR/data/$tableName");

				}

				//and die show error
				die("VastDB Error: There was an error occured while creating columns check if there is any unallowed chars in the folder name");


			}	
		}



	}

	//create meta data for the table
	//prepare meta template
	$metaTemplate = [
		"columns"=> $createdColumns ?? [],
		"created_at"=> date("Y-m-d")
	];

	//save meta
	file_put_contents("$baseDIR/data/$tableName/meta.vast", json_encode($metaTemplate, JSON_PRETTY_PRINT));

	//create next indexfile
	file_put_contents("$baseDIR/data/$tableName/next_index.vast", "0");

	//add to tables list meta
	file_put_contents("$baseDIR/data/tables.vast", "$tableName" . PHP_EOL, FILE_APPEND);

}


function deleteTable($tableName){
	global $baseDIR;

	if (!tableExists($tableName)) {
		die("VastDB Error: The table does not exist");
	}

	//hardocore delete it
	exec('rmdir /s /q ' . escapeshellarg("$baseDIR/data/$tableName")); 

	//get it out of tables data
		//get all tables
		$tables = getTables();
		//clear data
		file_put_contents("$baseDIR/data/tables.vast", "");
		 //save each table
		foreach ($tables as $tableToSaveBack) {
			if ($tableToSaveBack !== $tableName) {
			file_put_contents("$baseDIR/data/tables.vast", "$tableToSaveBack" . PHP_EOL, FILE_APPEND);
			}
		}



}

function newColumn($tableName, $columnName){
	global $baseDIR;

	//clean column name
	$columnName = trim($columnName);

	//check if table exists
	if (!is_dir("$baseDIR/data/$tableName")) {
		die("VastDB Error: The table does not exist");
	}

	//check if column already exists
	if (is_dir("$baseDIR/data/$tableName/$columnName")) {
		die("VastDB Error: The column already exists");
	}

	//create column folder
	if(!mkdir("$baseDIR/data/$tableName/$columnName")){
		die("VastDB Error: There was an error occured while creating column");
	}

	//create data json in column
	//generate empty json rows to fill
	$next_index = getNextIndex($tableName);
	$newColumnData = [];
	for ($i=0; $i < $next_index; $i++) { 
		$newColumnData[] = "";
	}
	//create vastdb file put data in
	if(file_put_contents("$baseDIR/data/$tableName/$columnName/data.vastdb", json_encode($newColumnData, JSON_PRETTY_PRINT)) === false){
		rmdir("$baseDIR/data/$tableName/$columnName");
		die("VastDB Error: Could not create column data file");
	}

	//read meta
	$meta = readJson("$baseDIR/data/$tableName/meta.vast");

	//add column to meta
	$meta["columns"][] = $columnName;

	//save meta
	writeJson("$baseDIR/data/$tableName/meta.vast" , $meta);
}


function deleteColumn($tableName, $columnName){
	global $baseDIR;


	//read meta
	$meta = readJson($baseDIR . "/data/$tableName/meta.vast");
	//remove column from meta
	//find key in the array
	$key = array_search($columnName, $meta["columns"]);

	if ($key !== false) {
		//remove from columns
		unset($meta["columns"][$key]);
		//reset indexes
		$meta["columns"] = array_values($meta["columns"]);
	}

	// FIXED: the updated meta data was missing from this function call
	writeJson($baseDIR . "/data/$tableName/meta.vast", $meta);

	exec('rmdir /s /q ' . escapeshellarg("$baseDIR/data/$tableName/$columnName"));     

}


function insert($tableName, $data){
	global $baseDIR;

	//checks if table exists
	if (!tableExists($tableName)) {
		die("VastDB Error: The table does not exist");
	}

	$columnsToFill = [];

	//first check every column exists
	foreach ($data as $columnName => $value) {
		if (!columnExists($tableName, $columnName)) {
		    die("VastDB Error: The column does not exist");
		}

		$columnsToFill[] = $columnName;
	}
		


	//check if every value is entered
	$tableColumns = getColumns($tableName);

	sort($columnsToFill);
	sort($tableColumns);


	if ($columnsToFill !== $tableColumns) {
		$notFilledColumns = array_diff($tableColumns, $columnsToFill);

		foreach ($notFilledColumns as $notFilledColumn) {
			// FIXED: only add one empty value for this new row.
			//the old code replaced all existing values in the column.
			$columnData = readJson("$baseDIR/data/$tableName/$notFilledColumn/data.vastdb");
			$columnData[] = "";
			writeJson("$baseDIR/data/$tableName/$notFilledColumn/data.vastdb", $columnData);
		}
	}


	foreach ($data as $columnName => $value) {
		//read column db data
		$columnData = readJson("$baseDIR/data/$tableName/$columnName/data.vastdb");


		//add data
		$columnData[] = $value;

		//write data
		writeJson("$baseDIR/data/$tableName/$columnName/data.vastdb", $columnData);


	}

	//increse next index
	$next_index = (int) file_get_contents("$baseDIR/data/$tableName/next_index.vast");

	$next_index += 1;

	//save next index file
	file_put_contents("$baseDIR/data/$tableName/next_index.vast", $next_index);

}


function deleteID($tableName, $idRow){
	global $baseDIR;

	$columns = getColumns($tableName);

	foreach ($columns as $column) {
		// get column data
		$columnData = readJson($baseDIR."/data/$tableName/$column/data.vastdb");
		//get the id info out of it 
		unset($columnData[$idRow]);
		//write it back
		writeJson($baseDIR."/data/$tableName/$column/data.vastdb", $columnData);
	}
}

function pull($tableName, $idToSearch){
	global $baseDIR;

	$columns = getColumns($tableName);
	$dataToReturn = [];

	foreach ($columns as $column) {
		// get column data
		$columnData = readJson($baseDIR."/data/$tableName/$column/data.vastdb");

		//add to data to return
		// FIXED: older rows can miss a column, use an empty value instead of showing a warning
		if (array_key_exists($idToSearch, $columnData)) {
			$dataToReturn[$column] = $columnData[$idToSearch];
		}else{
			$dataToReturn[$column] = "";
		}
	}


	return $dataToReturn;
}

function search($tableName, $columnName, $stringToSearch, $returntype = "rowData", $searchType = "strict"){
	global $baseDIR;


	$data = readJson($baseDIR."/data/$tableName/$columnName/data.vastdb");


	//search data one by one
	foreach ($data as $id => $value) {
		

		if ($searchType == "strict") {
			if ($value === $stringToSearch) {
				//found it, check what to return
				if ($returntype == "rowData") {
					return pull($tableName, $id);
				}elseif($returntype == "id"){
					return $id;
				}else{
					die("VastDB Error: Non existend return type used");
				}
				
			}
		}else{
			if ($value == $stringToSearch) {
				//found it, check what to return
				if ($returntype == "rowData") {
					return pull($tableName, $id);
				}elseif($returntype == "id"){
					return $id;
				}else{
					die("VastDB Error: Non existend return type used");
				}
				
			}

		}


	}


	//not found
	return false;
}

function update($tableName, $column, $id, $newData = ""){
	global $baseDIR;

	$data = readJson("$baseDIR/data/$tableName/$column/data.vastdb");

	//change update the data
	$data[$id] = $newData;

	//save to db
	writeJson("$baseDIR/data/$tableName/$column/data.vastdb", $data);
}

function pullColumn($tableName, $column){
	global $baseDIR;
		return readJson("$baseDIR/data/$tableName/$column/data.vastdb");

}

function getLast($tableName, $column){
	global $baseDIR;
		// FIXED: the parameter was named $colum while this function used $column
		return array_reverse(readJson("$baseDIR/data/$tableName/$column/data.vastdb"))[0];
}

function searchAll(
	$tableName,
	$columnName,
	$stringToSearch,
	$returntype = "rowData",
	$searchType = "strict"
){
	global $baseDIR;

	$data = readJson(
		$baseDIR . "/data/$tableName/$columnName/data.vastdb"
	);

	$results = [];

	foreach ($data as $id => $value) {

		$matches = false;

		if ($searchType === "strict") {
			$matches = ($value === $stringToSearch);
		}else{
			$matches = ($value == $stringToSearch);
		}

		if (!$matches) {
			continue;
		}

		if ($returntype === "rowData") {
			// Keep the VastDB row ID as the array key
			$results[$id] = pull($tableName, $id);
		}elseif ($returntype === "id") {
			$results[] = $id;
		}else{
			die("VastDB Error: Non existent return type used");
		}
	}

	return $results;
}
?>

