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
		background: #070b12;
		color: #dbe4f0;
		font-family: Inter, ui-sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
		overflow-x: auto;
		-webkit-font-smoothing: antialiased;
	}

	.dashboard {
		width: 100%;
		min-height: 100vh;
		padding: 24px;
		overflow-x: auto;
		background:
			radial-gradient(circle at 50% -10%, rgba(37, 99, 235, 0.08), transparent 30%),
			#070b12;
	}

	.forms {
		display: grid;
		grid-template-columns: repeat(4, minmax(220px, 1fr));
		gap: 14px;
		margin-bottom: 24px;
	}

	.card {
		background: linear-gradient(180deg, #111827 0%, #0e1521 100%);
		border: 1px solid #243146;
		border-radius: 9px;
		padding: 16px;
		min-width: 0;
		box-shadow: 0 10px 28px rgba(0, 0, 0, 0.16), inset 0 1px 0 rgba(255, 255, 255, 0.018);
		transition: border-color 120ms ease, transform 120ms ease, box-shadow 120ms ease;
	}

	.card:hover {
		border-color: #31415a;
		box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22), inset 0 1px 0 rgba(255, 255, 255, 0.025);
	}

	.card h3 {
		margin-top: 0;
		margin-bottom: 14px;
		font-size: 13px;
		font-weight: 650;
		letter-spacing: 0.01em;
		color: #f1f5f9;
	}

	input,
	textarea,
	select {
		width: 100%;
		padding: 9px 10px;
		margin-bottom: 9px;
		background: #080e18;
		color: #e8eef7;
		border: 1px solid #29384d;
		border-radius: 6px;
		outline: none;
		font-size: 12px;
		font-family: inherit;
		transition: border-color 120ms ease, box-shadow 120ms ease, background 120ms ease;
	}

	input,
	select {
		height: 36px;
	}

	input::placeholder,
	textarea::placeholder {
		color: #536177;
	}

	select {
		cursor: pointer;
	}

	textarea {
		min-height: 90px;
		resize: vertical;
		line-height: 1.45;
		font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
	}

	input:hover,
	textarea:hover,
	select:hover {
		border-color: #394a63;
	}

	input:focus,
	textarea:focus,
	select:focus {
		background: #0a111d;
		border-color: #3b82f6;
		box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.10);
	}

	button {
		width: 100%;
		min-height: 36px;
		padding: 8px 12px;
		background: #2563eb;
		color: #ffffff;
		border: 1px solid #3572ef;
		border-radius: 6px;
		cursor: pointer;
		font-size: 12px;
		font-weight: 650;
		font-family: inherit;
		box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
		transition: background 120ms ease, border-color 120ms ease, transform 120ms ease;
	}

	button:hover {
		background: #2f6df0;
		border-color: #4b82f3;
	}

	button:active {
		transform: translateY(1px);
	}

	.danger-button {
		width: auto;
		padding: 9px 14px;
		background: #b4232d;
		color: white;
		border: 1px solid #d13a44;
		border-radius: 6px;
		font-weight: 650;
		cursor: pointer;
		white-space: nowrap;
		box-shadow: none;
	}

	.danger-button:hover {
		background: #c12b35;
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
		padding: 4px 0 14px;
	}

	#tables h2 {
		margin: 18px 0 10px;
		font-size: 14px;
		font-weight: 650;
		letter-spacing: 0.01em;
		color: #f1f5f9;
	}

	table {
		width: max-content;
		min-width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		margin-bottom: 28px;
		background: #0d1420;
		border: 1px solid #243146;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 10px 28px rgba(0, 0, 0, 0.14);
	}

	th,
	td {
		padding: 9px 12px;
		border: 0;
		border-right: 1px solid #202c3d;
		border-bottom: 1px solid #202c3d;
		text-align: left;
		white-space: nowrap;
		vertical-align: top;
		font-size: 12px;
	}

	th:last-child,
	td:last-child {
		border-right: 0;
	}

	tr:last-child td {
		border-bottom: 0;
	}

	th {
		background: #101927;
		color: #9eacc0;
		position: sticky;
		top: 0;
		z-index: 2;
		font-size: 11px;
		font-weight: 650;
		letter-spacing: 0.035em;
		text-transform: uppercase;
	}

	td {
		color: #cbd5e1;
		background: #0d1420;
		font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
	}

	tr:hover td {
		background: #101927;
	}

	.dangerous {
		background-color: #a61f29;
		border-color: #c63741;
		box-shadow: none;
	}

	.dangerous:hover {
    	background-color: #bd2934;
		border-color: #d7444e;
	}

	.buttons {
    	display: flex;
    	flex-wrap: wrap;
		justify-content: flex-start;
    	gap: 7px;
		margin: 4px 0 10px;
		padding: 12px;
		background: #0d1420;
		border: 1px solid #243146;
		border-radius: 8px;
	}

	.buttons button {
		width: fit-content;
		min-height: 32px;
		flex: 0 0 auto;
		padding: 7px 12px;
		cursor: pointer;
		background: #131e2e;
		border: 1px solid #2b3b52;
		color: #b9c6d8;
		box-shadow: none;
		font-size: 11px;
	}

	.buttons button:hover {
		padding: 7px 12px;
		cursor: pointer;
		background: #18263a;
		border-color: #3a506f;
		color: #f1f5f9;
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
			gap: 10px;
		}

		.card {
			padding: 14px;
		}

		.buttons {
			padding: 10px;
		}
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
			<h3>Replace Column</h3>

			<form method="POST" action="handler/db_handler.php" target="_blank">
				<input type="hidden" name="action" value="replace_column">

				<select name="table_name" required>
					<?php foreach ($tables as $table) { ?>
						<option value="<?php echo htmlspecialchars($table); ?>">
							<?php echo htmlspecialchars($table); ?>
						</option>
					<?php } ?>
				</select>

				<input type="text" name="column_name" placeholder="column name" required>
				<input type="text" name="new_data" placeholder="value for every row">

				<button type="submit">Replace Column</button>
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