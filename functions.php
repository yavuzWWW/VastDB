<?php
//helper functions for db
$baseDIR = __DIR__;

function readJson($filepath){
	return json_decode(file_get_contents($filepath), true);
}

function writeJson($filepath, $dataToWrite){
	//encode data to json format
	$jsonContentToSave = json_encode($dataToWrite, JSON_PRETTY_PRINT);

	//check if encoded
	if (!$jsonContentToSave) {
		die("VastDB Error: An error occured while encoding the json content to save");
	}

	//write to file
	file_put_contents($filepath, $jsonContentToSave, LOCK_EX);
}

function getTasks(){
	global $baseDIR;

	//get queue and return
	return readJson($baseDIR."/worker/queue.json");

}

function writeTask($tasks){
	global $baseDIR;

	//write tasks
	writeJson("$baseDIR/worker/queue.json", $tasks);
}

function getNextIndex($tableName){
	global $baseDIR;
	//return index 
	return file_get_contents("$baseDIR/data/$tableName/next_index.vast");
}


function tableExists($tableName){
	global $baseDIR;

	//check if thet table is a dir and return
	return is_dir("$baseDIR/data/$tableName");

}

function columnExists($tableName, $columnName){
	global $baseDIR;

	//check if thet table is a dir and return
	return is_dir("$baseDIR/data/$tableName/$columnName");
}

function getColumns($tableName){
	global $baseDIR;


	$metaData = readJson($baseDIR."/data/$tableName/meta.vast");
	return $metaData['columns']; 


}

function getTables(){
	global $baseDIR;

	return $lines = file("$baseDIR/data/tables.vast", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}




?>