<?php

/**
 * 1. Generate Embedding via Ollama (nomic-embed-text)
 */
function get_query_embedding($text) {
    $url = "http://127.0.0.1:11434/api/embed";
    $data = ["model" => "nomic-embed-text", "input" => $text];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    return $result['embeddings'][0] ?? null;
}

/**
 * 2. Calculate Cosine Similarity
 */
function calculate_cosine_similarity($vecA, $vecB) {
    $dotProduct = 0;
    $normA = 0;
    $normB = 0;
    
    $count = count($vecA);
    for ($i = 0; $i < $count; $i++) {
        $dotProduct += $vecA[$i] * $vecB[$i];
        $normA += $vecA[$i] ** 2;
        $normB += $vecB[$i] ** 2;
    }
    
    $denominator = sqrt($normA) * sqrt($normB);
    return ($denominator == 0) ? 0 : ($dotProduct / $denominator);
}

// --- CONFIGURATION ---
$userQuery = "Top Jain temples with intricate marble carvings"; 

// --- DATABASE CONNECTION ---
$host = 'localhost';
$db   = 'test';
$user = 'admin';
$pass = "Admin@123";
$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Generate vector for the user query
$queryVector = get_query_embedding($userQuery);

if (!$queryVector) {
    die("Error: Could not generate vector for search. Check if Ollama is running.");
}

/**
 * 3. FETCH AND COMPARE
 * Updated to use table: state_palce_embeddings
 * Updated columns: state_name, district, city, place_name, description, embedding
 */
$sql = "SELECT state_name, district, city, place_name, description, embedding FROM state_palce_embeddings";
$result = $mysqli->query($sql);

$matches = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (empty($row['embedding'])) continue;

        $storedVector = json_decode($row['embedding'], true);
        if (!is_array($storedVector)) continue;

        $score = calculate_cosine_similarity($queryVector, $storedVector);
        
        $matches[] = [
            'state'      => $row['state_name'],
            'district'   => $row['district'],
            'city'       => $row['city'],
            'place_name' => $row['place_name'],
            'desc'       => $row['description'],
            'score'      => $score
        ];
    }
}

// Sort results by similarity score DESC
usort($matches, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

// --- DISPLAY OUTPUT ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jain Temple Search</title>
    <style>
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; line-height: 1.6; padding: 40px; background-color: #f0f2f5; color: #1c1e21; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h2 { color: #0056b3; margin-top: 0; }
        .query-box { background: #e7f3ff; padding: 15px; border-left: 5px solid #0056b3; border-radius: 4px; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px; border-bottom: 1px solid #e5e5e5; text-align: left; }
        th { background-color: #f8f9fa; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; color: #65676b; }
        tr:last-child td { border-bottom: none; }
        .place-name { font-size: 1.15rem; color: #1c1e21; margin: 0 0 4px 0; }
        .location-info { font-size: 0.9rem; color: #65676b; display: block; }
        .description { font-size: 0.95rem; color: #4b4f56; max-width: 400px; }
        .score-pill { display: inline-block; padding: 6px 12px; background: #34a853; color: white; border-radius: 20px; font-weight: bold; font-size: 0.85rem; }
        .low-score { background: #fbbc05; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Place Similarity Search</h2>
        <div class="query-box">
            Searching for: <strong>"<?php echo htmlspecialchars($userQuery); ?>"</strong>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Temple & Location</th>
                    <th>About</th>
                    <th style="text-align: right;">Similarity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                $found = false;
                foreach ($matches as $match) {
                    if ($match['score'] < 0.25) continue; 
                    if ($rank > 20) break;
                    $found = true;
                    
                    $scoreClass = ($match['score'] < 0.6) ? 'low-score' : '';
                ?>
                <tr>
                    <td><?php echo $rank; ?></td>
                    <td>
                        <div class="place-name"><strong><?php echo htmlspecialchars($match['place_name']); ?></strong></div>
                        <span class="location-info">
                            <?php echo htmlspecialchars($match['city'] . ", " . $match['district'] . ", " . $match['state']); ?>
                        </span>
                    </td>
                    <td><div class="description"><?php echo htmlspecialchars($match['desc']); ?></div></td>
                    <td style="text-align: right;">
                        <span class="score-pill <?php echo $scoreClass; ?>">
                            <?php echo round($match['score'] * 100, 2); ?>%
                        </span>
                    </td>
                </tr>
                <?php
                    $rank++;
                }
                if (!$found) {
                    echo "<tr><td colspan='4' style='text-align:center;'>No matching religious places found. Try a different query.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>