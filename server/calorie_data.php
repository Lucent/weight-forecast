<?php
include "session.php";

function intake_get($clip_start, $clip_end) {
	if (isset($_SESSION["valid"]) && $_SESSION["valid"] === 1) {
		$conn = database_connect();
		$id = $_SESSION["id"];

		$query = "SELECT date,intake,weight FROM calories WHERE id='{$id}' ORDER BY date;";
		$result = mysqli_query($conn, $query);

		$intake = [];
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$db_date = \DateTime::createFromFormat('Y-m-d', $row["date"]);
			if ($db_date < relative_to_absolute_date($clip_start) && $db_date > relative_to_absolute_date($clip_end))
				$intake[] = ["date" => $row["date"], "intake" => $row["intake"], "weight" => $row["weight"]];
		}

		mysqli_close($conn);

		return $intake;
	}
}

function relative_to_absolute_date($offset) {
	$date = new DateTime();
	if ($offset < 0) {
		$y = abs($offset);
		$date->add(new DateInterval("P{$y}D"));
	} else
		$date->sub(new DateInterval("P{$offset}D"));
	return $date;
}

function draw_table_chart($start = -7, $end = 21) {
	$intake_array = intake_get($start, $end);

	echo "<script>let intake_array = " . json_encode($intake_array) . ";</script>";
	echo "<form method='post' action='server/intake_set.php'>\n";
	echo "<table id='Table'>\n";
	echo "<thead><tr><th>Date</th><th>Intake</th><th>Cumulative</th><th>Weight</th><th>Predicted</th></tr></thead>\n";
	echo "<tbody>\n";
//	echo "<tr><td><td><td><td><td rowspan=200 id='LineChart'></td></tr>";
	for ($x = $start; $x < $end; $x++) {
		$date = relative_to_absolute_date($x);

		$date_string = $date->format("Y-m-d");
		$month_day = $date->format("M d");
		$weekday = $date->format("D");
		$today = $x == -1 ? "Today" : "";
		$intake_today = array_search($date_string, array_column($intake_array, "date"));
		if ($intake_today !== false)
			$intake = $intake_array[$intake_today]["intake"];
		else
			$intake = "";

		$weight_today = array_search($date_string, array_column($intake_array, "date"));
		if ($weight_today !== false)
			$weight = $intake_array[$weight_today]["weight"];
		else
			$weight = "";
		echo " <tr class='{$weekday} {$today}' id='{$date_string}'><th>{$weekday} {$month_day}</th><td><input type='number' name='Intake-{$date_string}' min='0' max='10000' value='{$intake}'></td><td><output></output></td><td><input type='number' name='Weight-{$date_string}' min='50' max='1000' value='{$weight}' step='0.1'></td><td><output></output></td></tr>\n";
	}
	echo "</tbody>\n";
	echo "</table>\n";
	echo "<input type='submit'>\n";
	echo "</form>\n";
}

?>
