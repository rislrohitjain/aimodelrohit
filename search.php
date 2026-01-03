<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json'); // Set header for JSON output

// echo get_wikipedia_summary("Ajmeri Gate");
// die; 
/**
 * 1. Generate Embedding via Ollama
 */
 function get_wikipedia_summary($query) {
    $url = "https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&exintro=1&explaintext=1&titles=" . urlencode($query) . "&redirects=1";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Important: Wikipedia blocks requests without a descriptive User-Agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'MyProject/1.0 (contact@example.com)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        // Handle cURL errors (e.g., timeout, DNS issues)
        return "cURL Error: " . curl_error($ch);
    }
    
    curl_close($ch);

    $data = json_decode($response, true);
    // echo "<pre>";print_r($data);die;
    if (!isset($data['query']['pages'])) {
        return "No results found.";
    }

    $pages = $data['query']['pages'];
    $page = reset($pages); // Get the first result

    // Check if the page actually exists (missing property check)
    if (isset($page['missing'])) {
        // return "Page not found for: " . htmlspecialchars($query);
        return  htmlspecialchars($query);
    }

    return $page['extract'] ?? "No summary available.";
}
 
 // 1. Get query embedding and NORMALIZE it immediately
function get_query_embedding($text) {
    $url = "http://127.0.0.1:11434/api/embed";
    $data = ["model" => "nomic-embed-text", "input" => $text];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    $vector = $result['embeddings'][0] ?? null;
    
    if (!$vector) return null;

    // Normalize query vector so we can use Dot Product
    $sum = 0;
    foreach ($vector as $v) $sum += $v ** 2;
    $mag = sqrt($sum);
    return array_map(fn($v) => $v / $mag, $vector);
} 
/**
 * 2. Calculate Cosine Similarity
 */
function calculate_cosine_similarity($vecA, $vecB) {
    $dotProduct = 0; $normA = 0; $normB = 0;
    $count = count($vecA);
    for ($i = 0; $i < $count; $i++) {
        $dotProduct += $vecA[$i] * $vecB[$i];
        $normA += $vecA[$i] ** 2;
        $normB += $vecB[$i] ** 2;
    }
    $denominator = sqrt($normA) * sqrt($normB);
    return ($denominator == 0) ? 0 : ($dotProduct / $denominator);
}

// Main Execution
if (isset($_POST['query'])) {
    $userQuery = $_POST['query'];

    // Database Connection
    $host = 'localhost';
    $db   = 'test';
    $user = 'admin';
    $pass = "Admin@123";
    $mysqli = new mysqli($host, $user, $pass, $db);

    if ($mysqli->connect_error) {
        echo json_encode(["error" => "Connection failed"]);
        exit;
    }

    $queryVector = get_query_embedding($userQuery);

    if (!$queryVector) {
        echo json_encode(["error" => "Ollama not responding"]);
        exit;
    }

    $sql = "SELECT state_name, district,latitude,longitude, city, place_name, description, embedding FROM state_palce_embeddings";
    $result = $mysqli->query($sql);
	
    $matches = [];
	$resultCounter = 0;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (empty($row['embedding'])) continue;
            $storedVector = json_decode($row['embedding'], true);
            if (!is_array($storedVector)) continue;

            $score = calculate_cosine_similarity($queryVector, $storedVector);
            
            // Only keep relevant results
            if ($score >= 0.60) { // .60 .20
				$resultCounter++;
                $matches[] = [
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'place_name' => $row['place_name'],
                    'location'   => $row['city'] . ", " . $row['district'] . ", " . $row['state_name'],
                    'desc'       => $row['description'],
                    'score'      => round($score * 100, 2)
                ];
            }else{
				// echo "no found";die;
			}
        }
    }
	
	// echo $resultCounter;die;
	$newDesc = null;
	if(false && $resultCounter <= 0){
		// STEP 2: Fallback to Web Search if No Good Matches Found
		if (empty($matches)) {
			// 1. Get Description from DuckDuckGo
			$ddgUrl = "https://api.duckduckgo.com/?q=" . urlencode($userQuery) . "&format=json&no_html=1&skip_disambig=1";
			
			$ch = curl_init($ddgUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
			$webResponse = curl_exec($ch);
			curl_close($ch);

			$webData = json_decode($webResponse, true);
			$newDesc = $webData['AbstractText'] ?? null;

			if ($newDesc) {
				$place_name = $userQuery;
				// 2. Fetch Geo Details (Lat, Lon, State, District) from Nominatim
				$geoUrl = "https://nominatim.openstreetmap.org/search?q=" . urlencode($userQuery) . "&format=json&addressdetails=1&limit=1";
				
				$chGeo = curl_init($geoUrl);
				curl_setopt($chGeo, CURLOPT_RETURNTRANSFER, true);
				// Nominatim REQUIRES a custom User-Agent identifying your app
				curl_setopt($chGeo, CURLOPT_USERAGENT, 'YourAppName/1.0 (contact@yourdomain.com)');
				$geoResponse = curl_exec($chGeo);
				curl_close($chGeo);

				$geoData = json_decode($geoResponse, true);
				
				$lat = null; $lon = null; $state = null; $district = null; $city = null;

				if (!empty($geoData)) {
					$location = $geoData[0];
					$lat = $location['lat'];
					$lon = $location['lon'];
					$address = $location['address'];
					
					// Map address components
					$state    = $address['state'] ?? null;
					$district = $address['county'] ?? $address['state_district'] ?? null;
					$city     = $address['city'] ?? $address['town'] ?? $address['village'] ?? $userQuery;
				}

				// 3. Generate embedding
				$newEmbedding = get_query_embedding($newDesc);
				$source = 'web_fallback';
				$score = 100;
				// 4. Save to DB (assuming you add lat/long/state/district columns to your table)
				// Adjust your table schema: ALTER TABLE state_palce_embeddings ADD COLUMN lat DECIMAL(10,8), ADD COLUMN lon DECIMAL(11,8)...
				$sql = "INSERT INTO state_palce_embeddings (place_name,source,city, state_name, district, latitude, longitude, description, score,embedding) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stmt = $mysqli->prepare($sql);
				$embedJson = json_encode($newEmbedding);
				$stmt->bind_param("ssssssssss", $place_name,$source, $city, $state, $district, $lat, $lon, $newDesc, $score,$embedJson);
				$stmt->execute();

				$matches[] = [
					'source'     => $source,
					'place_name' => $place_name,
					'state_name'      => $state,
					'district'   => $district,
					'latitude'        => $lat,
					'longitude'        => $lon,
					'desc'       => $newDesc,
					'score'      => $score
				];
				$resultCounter++;
				header('Content-Type: application/json');
				// print_r($matches);
				// die();
			}
		} 
		// usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);
		// echo json_encode(array_slice($matches, 0, 10));
		// die; 
	}
	
	
	
	
	// echo $resultCounter;die;
	if($resultCounter <= 0){
		// STEP 3: Fallback to Web Search if No Good Matches Found
		if (empty($matches)) {
			// 1. Get Description from DuckDuckGo
			// 2. NEW: If DuckDuckGo is empty, try Wikipedia
			$source = 'wikipedia_fallback';
			if (!$newDesc) {
				$newDesc = get_wikipedia_summary($userQuery);
				// echo "<pre>";print_r($newDesc);die;
				$source = 'wikipedia_fallback';
			}
			
			// print_r($matches);die;
			
			// echo $source;die;

			if ($newDesc) {
				$place_name = $userQuery;
				// 2. Fetch Geo Details (Lat, Lon, State, District) from Nominatim
				$geoUrl = "https://nominatim.openstreetmap.org/search?q=" . urlencode($userQuery) . "&format=json&addressdetails=1&limit=1";
				
				$chGeo = curl_init($geoUrl);
				curl_setopt($chGeo, CURLOPT_RETURNTRANSFER, true);
				// Nominatim REQUIRES a custom User-Agent identifying your app
				curl_setopt($chGeo, CURLOPT_USERAGENT, 'YourAppName/1.0 (contact@yourdomain.com)');
				$geoResponse = curl_exec($chGeo);
				curl_close($chGeo);

				$geoData = json_decode($geoResponse, true);
				
				$lat = null; $lon = null; $state = null; $district = null; $city = null;

				if (!empty($geoData)) {
					$location = $geoData[0];
					$lat = $location['lat'];
					$lon = $location['lon'];
					$address = $location['address'];
					
					// Map address components
					$state    = $address['state'] ?? null;
					$district = $address['county'] ?? $address['state_district'] ?? null;
					$city     = $address['city'] ?? $address['town'] ?? $address['village'] ?? $userQuery;
				}

				// 3. Generate embedding
				$newEmbedding = get_query_embedding($newDesc);
				// $source = 'web_fallback';
				$score = 100;
				// 4. Save to DB (assuming you add lat/long/state/district columns to your table)
				// Adjust your table schema: ALTER TABLE state_palce_embeddings ADD COLUMN lat DECIMAL(10,8), ADD COLUMN lon DECIMAL(11,8)...
				$sql = "INSERT INTO state_palce_embeddings (place_name,source,city, state_name, district, latitude, longitude, description, score,embedding) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stmt = $mysqli->prepare($sql);
				$embedJson = json_encode($newEmbedding);
				$stmt->bind_param("ssssssssss", $place_name,$source, $city, $state, $district, $lat, $lon, $newDesc, $score,$embedJson);
				$stmt->execute();

				$matches[] = [
					'source'     => $source,
					'place_name' => $place_name,
					'state_name'      => $state,
					'district'   => $district,
					'latitude'        => $lat,
					'longitude'        => $lon,
					'desc'       => $newDesc,
					'score'      => $score
				];

				header('Content-Type: application/json');
				// print_r($matches);
				// die();
			}
		}

		usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);
		echo json_encode(array_slice($matches, 0, 10));
		die; 
	}

    // Sort by similarity
    usort($matches, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });
	// print_r($matches);die;
    // Return the top 10 results as JSON
    echo json_encode(array_slice($matches, 0, 10));

} else {
    echo json_encode(["error" => "No query provided"]);
}