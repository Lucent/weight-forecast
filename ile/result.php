<?php
header('Content-Encoding: none');
$search_string = urlencode($_GET["search"]);

set_time_limit(0);
ob_implicit_flush(1);
ob_end_flush();

// Search for foods matching the search string
$search_url = "https://api.nal.usda.gov/fdc/v1/foods/search?api_key={$api_key}&query={$search_string}";
$search_response = makeRequest($search_url);
$search_data = json_decode($search_response, true);

// Loop through the search results and get the nutrient data for each food
echo "TOTAL=", count($search_data["foods"]), "\n";
foreach ($search_data['foods'] as $food) {
	$food_id = $food['fdcId'];
	$food_url = "https://api.nal.usda.gov/fdc/v1/food/{$food_id}?api_key={$api_key}";
	$food_response = makeRequest($food_url);
	$food_data = json_decode($food_response, true);

	// Calculate the isoleucine per 100 calories
	$isoleucine_per_100_calories = calculateIsoleucinePer100Calories($food_data);

	// Print the results
	if ($isoleucine_per_100_calories)
		echo $food_data['description'] . "|" . $isoleucine_per_100_calories . "\n";
	else
		echo "NO_DATA_FOR_FOOD\n";
}

// Function to make a cURL request
function makeRequest($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

// Function to calculate the isoleucine per 100 calories
function calculateIsoleucinePer100Calories($food_data) {
	$ENERGY_CODE = 1008;
	$ISOLEUCINE_CODE = 1212;

	foreach ($food_data['foodNutrients'] as $nutrient) {
		if ($nutrient['nutrient']['id'] == $ISOLEUCINE_CODE)
			$isoleucine = $nutrient['amount'];
		elseif ($nutrient['nutrient']['id'] == $ENERGY_CODE)
			$calories = $nutrient['amount'];
	}

	if (!isset($calories) || !isset($isoleucine))
		return false;

	return round(($isoleucine / $calories) * 2000, 2);
}
?>
