<?php

require_once  '../db.php';
$tables = getTables();
$action = $_POST['action'] ?? null;

$tableName = $_POST['table_name'] ?? '';

if ($tableName) {
    echo "Selected table: " . htmlspecialchars($tableName);
}

?>

<style>
	* {
		box-sizing: border-box;
	}

	html,
	body {
		margin: 0;
		min-height: 100%;
		background: #0b0f19;
		color: white;
		font-family: Arial, Helvetica, sans-serif;
		overflow-x: auto;
	}

	.dashboard {
		width: 100%;
		min-height: 100vh;
		padding: 20px;
		overflow-x: auto;
	}

	.forms {
		display: grid;
		grid-template-columns: repeat(4, minmax(220px, 1fr));
		gap: 15px;
		margin-bottom: 20px;
	}

	.card {
		background: #111827;
		border: 1px solid #263244;
		border-radius: 10px;
		padding: 15px;
		min-width: 0;
	}

	.card h3 {
		margin-top: 0;
		margin-bottom: 14px;
		font-size: 17px;
	}

	input,
	textarea,
	select {
		width: 100%;
		padding: 9px;
		margin-bottom: 10px;
		background: #0b1220;
		color: white;
		border: 1px solid #334155;
		border-radius: 6px;
		outline: none;
		font-size: 14px;
	}

	textarea {
		min-height: 92px;
		resize: vertical;
	}

	input:focus,
	textarea:focus,
	select:focus {
		border-color: #2563eb;
		box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
	}

	button {
		width: 100%;
		padding: 10px;
		background: #2563eb;
		color: white;
		border: none;
		border-radius: 6px;
		cursor: pointer;
		font-weight: bold;
	}

	button:hover {
		background: #1d4ed8;
	}

	.danger-button {
		width: auto;
		padding: 9px 14px;
		background: #dc2626;
		color: white;
		border: none;
		border-radius: 8px;
		font-weight: bold;
		cursor: pointer;
		white-space: nowrap;
	}

	.danger-button:hover {
		background: #b91c1c;
	}

	.tables-area {
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	#tables {
		display: block;
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
		padding-bottom: 12px;
	}

	table {
		width: max-content;
		min-width: 100%;
		border-collapse: collapse;
		margin-bottom: 30px;
		background: #111827;
	}

	th,
	td {
		padding: 10px;
		border: 1px solid #263244;
		text-align: left;
		white-space: nowrap;
		vertical-align: top;
	}

	th {
		background: #0b1220;
		color: #e5e7eb;
		position: sticky;
		top: 0;
		z-index: 2;
	}

	td {
		color: #d1d5db;
	}

	.dangerous {
		background-color: #dc2626;
	}

	.dangerous:hover {
    	background-color: #600f0f; /* Darker red on hover */
	}

	@media (max-width: 1200px) {
		.forms {
			grid-template-columns: repeat(2, minmax(220px, 1fr));
		}
	}

	@media (max-width: 650px) {
		.dashboard {
			padding: 14px;
		}

		.forms {
			grid-template-columns: 1fr;
		}
	}

	.buttons {
    	display: flex;
    	flex-wrap: wrap;
		justify-content: center;
    	gap: 10px;      
	}

	.buttons button {
		width: fit-content;
		flex: 0 0 auto;
		padding: 10px 20px;
		cursor: pointer;
		background-color: #334155;
	}

	.buttons button:hover {
		padding: 10px 20px;
		cursor: pointer;
		background-color: #213b5f;
	}
</style>

<div id="dashboard" class="dashboard">

	<div class="forms">

		<div class="card">
			<h3>New Table</h3>

			<form method="POST" action="handler/db_handler.php" target="_blank">
				<input type="hidden" name="action" value="new_table">

				<input type="text" name="table_name" placeholder="table name" required>
				<input type="text" name="columns" placeholder="username,email,password" required>

				<button type="submit">Create Table</button>
			</form>
		</div>

		<div class="card">
			<h3>New Column</h3>

			<form method="POST" action="handler/db_handler.php" target="_blank">
				<input type="hidden" name="action" value="new_column">

				<select name="table_name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>

				<input type="text" name="column_name" placeholder="column name" required>

				<button type="submit">Add Column</button>
			</form>
			
		</div>

		<div class="card">
			<h3>Insert Row</h3>

			<form method="POST" action="handler/db_handler.php" target="_blank">
				<input type="hidden" name="action" value="insert">

				<select name="table_name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>

				<textarea name="insert_data" placeholder="username=yavuz&#10;email=test@mail.com&#10;password=123" required></textarea>

				<button type="submit">Insert</button>
			</form>
		</div>

		<div class="card">
			<h3>Update Value</h3>

			<form method="POST" action="handler/db_handler.php" target="_blank">
				<input type="hidden" name="action" value="update">

				<select name="table_name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>
				<input type="text" name="column_name" placeholder="column name" required>
				<input type="number" name="id" placeholder="id" required>
				<input type="text" name="new_data" placeholder="new value" required>

				<button type="submit">Update</button>
			</form>
		</div>

		<div class="card">
			<h3>Delete Table</h3>
			
			<form method="POST" action="handler/delete_handler.php" target="_blank">
				<input type="hidden" name="action" value="table">

				<select name="table_name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>

				<button type="submit" class="dangerous">Delete Table</button>
			</form>
		</div>

		<div class="card">
			<h3>Delete Column</h3>
			
			<form method="POST" action="handler/delete_handler.php" target="_blank">
				<input type="hidden" name="action" value="column">

				<select name="table_name" placeholder="table name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>

				<input type="text" name="column_name" placeholder="column name" required>

				<button type="submit" class="dangerous">Delete Column</button>
			</form>

		</div>

		<div class="card">
			<h3>Delete Row</h3>
			
			<form method="POST" action="handler/delete_handler.php" target="_blank">
				<input type="hidden" name="action" value="row">

				<select name="table_name" placeholder="table name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>

				<input type="text" name="row_id" placeholder="Row ID" required>

				<button type="submit" class="dangerous">Delete Column</button>
			</form>

		</div>

	</div>


	<input type="hidden" id="selected_table" name="table_name" value="">

	<div class="buttons">
		<?php foreach ($tables as $table) { 
			$tableName = htmlspecialchars($table); 
		?>
			<button
				type="button"
				onclick="document.getElementById('selected_table').value='<?php echo $tableName; ?>'; htmx.trigger('#tables', 'refresh');"
			>
				<?php echo $tableName; ?>
			</button>
		<?php } ?>
	</div>

	<div 
		id="tables" 
		hx-post="pages/tables.php" 
		hx-trigger="load, every 1s, refresh" 
		hx-include="#selected_table"
	></div>

</div>

<script src="https://unpkg.com/htmx.org@2.0.4"></script>