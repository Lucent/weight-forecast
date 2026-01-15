<?php
include "server/calorie_data.php";
?>
<!doctype html>
<html>
<head>
<title>fitCast - Predict your weight and BMR using calorie counting</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
table				{ border-collapse: collapse; }
:root				{ color-scheme: light dark; }
body				{ font-family: sans-serif; color: CanvasText; background-color: Canvas; }

tbody				{ font-size: large; }
tbody th			{ font-weight: normal; text-align: right; }
tbody input			{ font-size: inherit; border: none; border-bottom: thin solid #999; padding: 0.1em 0.2em; width: 4em; line-height: 1.8; }
tbody th, tbody input,
tbody td			{ text-align: right; }

.Sun > *			{ padding-top: 1em; }
.Today				{ border-bottom: thick solid red; }
tbody tr			{ color: gray; }
.Today ~ tr			{ color: revert; }
#LineChart			{ width: 10em; }

.Black				{ color: black; }
.White				{ color: white; }
</style>
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/regression@2.0.1/dist/regression.min.js"></script>
<script type="module" src="js/load.js">
</script>
</head>
<body>
<h1>fitCast</h1>
<h2>Enter your daily calorie intake and weight to calculate your BMR and forecast future weight loss or gain.</h2>
<?php
if (isset($_SESSION["valid"]) && $_SESSION["valid"] === 1) {
	echo "<p>Logged in as {$_SESSION['username']}. <a href='logout.php'>Log out</a></p>";
} else {
	echo "<p><a href='login.php'>Log in or register</a> to save your data.</p>";
}
?>
<h3>Your predicted BMR from the entered intake and weights is <output></output></h3>

<canvas id="LineChart"></canvas>

<?php
if (isset($_SESSION["valid"]) && $_SESSION["valid"] === 1)
	draw_table_chart(-2, 100);
?>
<nav>
 <a href="faq.html">Questions</a>
 <a href="//foodpicker.fitcast.com/">Food picker</a>
</nav>

</body>
</html>
