<?php
require_once '../functions.php';
require_once '../db.php';
if ($_SERVER['REQUEST_METHOD'] === "POST") {

	if (isset($_POST['delete_confirm'])) {

		//get user password hash
		$dbInfo = readJson("../data/info.vast");



		if (password_verify($_POST['passwordToConfirm'], $dbInfo['Admin']['password'])) {

				if($_POST['action'] == "table"){
					deleteTable($_POST['table_name']);
				 	$succes = true;
				}else if($_POST['action'] == "column"){
					deleteColumn($_POST['table_name'], $_POST['column_name']);
					$succes = true;
				}else if($_POST['action'] == "row"){
					deleteID($_POST['table_name'], $_POST['row_id']);
					$succes = true;
				}else {
					echo("Error - Not enough tags!");
				}

		}else{
			die("Password couldnt be verified!");
		}
	}

}else{
	die("No valid req found");
}

if ($_POST['table_name'] == "") {
	die("No table selected");
}
?>

<html>
		<?php if(isset($succes)): ?>
			<center><p><?php 
				if($_POST['action'] == "table"){
					echo($_POST['table_name']);
				}else if($_POST['action'] == "column"){
					echo($_POST['column_name']);
				}else if($_POST['action'] == "row"){
					echo($_POST['row_id']);
				}?> Deleted succesfully</p></center>
		<?php else: ?>
			<form method="POST">
				<input type="password" name="passwordToConfirm" placeholder="Type in admin password">
				<input type="hidden" name="action" value="<?php echo htmlspecialchars($_POST['action'] ?? ''); ?>">
    			<input type="hidden" name="table_name" value="<?php echo htmlspecialchars($_POST['table_name'] ?? ''); ?>">
    			<input type="hidden" name="column_name" value="<?php echo htmlspecialchars($_POST['column_name'] ?? ''); ?>">
				<input type="hidden" name="row_id" value="<?php echo htmlspecialchars($_POST['row_id'] ?? ''); ?>">
				<button name="delete_confirm" type="submit">Delete</button>
			</form>
		<?php endif; ?>
</html>

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

