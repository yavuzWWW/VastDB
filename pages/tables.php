<?php

require __DIR__ . '/../db.php';


$tables = getTables();

$selectedTable = $_POST['table_name'] ?? null;

if ($selectedTable && in_array($selectedTable, $tables)) {
	$tables = [$selectedTable];
}

foreach ($tables as $table) {

		echo "<h2>$table</h2>";

		$columns = getColumns($table);

		echo "<table>";
		echo "<tr>";
		echo "<th>ID</th>";

		foreach ($columns as $column) {
			echo "<th>$column</th>";
		}

		echo "</tr>";

		$columnData = [];
		$allIDs = [];

		foreach ($columns as $column) {
			$columnData[$column] = readJson("$baseDIR/data/$table/$column/data.vastdb");

			foreach ($columnData[$column] as $id => $value) {
				$allIDs[] = $id;
			}
		}

		$allIDs = array_unique($allIDs);
		sort($allIDs);

		foreach ($allIDs as $id) {
			echo "<tr>";
			echo "<td>$id</td>";

			foreach ($columns as $column) {
				$value = $columnData[$column][$id] ?? "";

				echo "<td>$value</td>";
			}

			echo "</tr>";
		}

		echo "</table>";
	}

	?>