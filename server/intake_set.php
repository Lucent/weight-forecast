<?php include "session.php";

if (isset($_SESSION["valid"]) && $_SESSION["valid"] === 1) {
	header("Location: /");
	$conn = database_connect();
	$id = $_SESSION["id"];

	$by_date = [];
	foreach ($_POST as $key => $value) {
		$field_date = explode("-", $key, 2);
		$field = strtolower($field_date[0]);
		$date = mysqli_real_escape_string($conn, $field_date[1]);
		$by_date[$date][$field] = $value === "" ? "NULL" : mysqli_real_escape_string($conn, $value);
	}

	$submission = [];
	foreach ($by_date as $date => $assoc)
		$submission[] = "($id, STR_TO_DATE('$date', '%Y-%m-%d'), {$assoc['intake']}, {$assoc['weight']})";
	$sql_replace = implode(", ", $submission);

	$query = "REPLACE INTO calories (id, date, intake, weight) VALUES {$sql_replace};";

	mysqli_query($conn, $query);
	echo $conn->error;

	mysqli_close($conn);
}
?>
