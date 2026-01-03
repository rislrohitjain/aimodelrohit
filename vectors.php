<?php

// Function to fetch embeddings from local Ollama/Gemma
function get_embedding($text) {
    $url = "http://127.0.0.1:11434/api/embed";
    $data = ["model" => "nomic-embed-text", "input" => $text];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    return $result['embeddings'][0] ?? null;
}

// 1. Database Connection
$mysqli = new mysqli('localhost', 'admin', 'Admin@123', 'test');

// 2. Data Set (State, District, City, Lat, Long, City Description)
// Each city includes its top 10 places within the description

	// $topPlacesIndia = [
		$topPlacesIndia = [
    // --- NORTH INDIA ---
    ['Uttar Pradesh', 'Agra', 'Agra', 'Taj Mahal', 27.1751, 78.0421, 'A white marble mausoleum and UNESCO World Heritage site built by Shah Jahan.'],
    ['Uttar Pradesh', 'Agra', 'Agra', 'Agra Fort', 27.1795, 78.0211, 'A massive 16th-century red sandstone fortress located near the Taj Mahal.'],
    ['Uttar Pradesh', 'Varanasi', 'Varanasi', 'Kashi Vishwanath Temple', 25.3110, 83.0104, 'One of the most famous Hindu temples dedicated to Lord Shiva, located on the Ganges.'],
    ['Uttar Pradesh', 'Varanasi', 'Varanasi', 'Tulsi Manas Mandir', 25.2890, 82.9990, 'White marble temple where the Ramcharitmanas was composed by Tulsidas.'],
    ['Uttar Pradesh', 'Varanasi', 'Sarnath', 'Dhamekh Stupa', 25.3811, 83.0247, 'The location where Lord Buddha gave his first sermon to his five disciples.'],
    ['Delhi', 'New Delhi', 'New Delhi', 'Qutub Minar', 28.5245, 77.1855, 'A 73-meter tall tapering tower built in 1193 by Qutab-ud-din Aibak.'],
    ['Delhi', 'New Delhi', 'New Delhi', 'India Gate', 28.6129, 77.2295, 'A war memorial archway commemorating Indian soldiers of the First World War.'],
    ['Delhi', 'New Delhi', 'New Delhi', 'Humayuns Tomb', 28.5933, 77.2507, 'The first garden-tomb on the Indian subcontinent, precursor to the Taj Mahal.'],
    ['Delhi', 'New Delhi', 'New Delhi', 'Lotus Temple', 28.5535, 77.2588, 'Baháʼí House of Worship notable for its flowerlike shape.'],
    ['Delhi', 'Central Delhi', 'Old Delhi', 'Jama Masjid', 28.6507, 77.2334, 'One of India\'s largest mosques, built by Mughal Emperor Shah Jahan.'],
    ['Delhi', 'Central Delhi', 'New Delhi', 'Akshardham Temple', 28.6127, 77.2773, 'Massive spiritual-cultural campus showcasing traditional Hindu culture.'],
    ['Rajasthan', 'Jaipur', 'Jaipur', 'Amer Fort', 26.9855, 75.8513, 'A majestic fort situated on a hill, known for its artistic Hindu style elements.'],
    ['Rajasthan', 'Jaipur', 'Jaipur', 'Hawa Mahal', 26.9239, 75.8267, 'The Palace of Winds, a five-story pink sandstone structure with 953 windows.'],
    ['Rajasthan', 'Udaipur', 'Udaipur', 'City Palace', 24.5764, 73.6835, 'A complex of palaces on the banks of Lake Pichola.'],
    ['Rajasthan', 'Jodhpur', 'Jodhpur', 'Mehrangarh Fort', 26.2978, 73.0185, 'One of the largest forts in India, 400 feet above the city skyline.'],
    ['Rajasthan', 'Jaisalmer', 'Jaisalmer', 'Jaisalmer Fort', 26.9124, 70.9126, 'A living fort made of yellow sandstone in the heart of the Thar Desert.'],
    ['Rajasthan', 'Ajmer', 'Ajmer', 'Ajmer Sharif Dargah', 26.4561, 74.6282, 'Sufi shrine of the revered saint Moinuddin Chishti.'],
    ['Rajasthan', 'Sirohi', 'Mount Abu', 'Dilwara Temples', 24.6067, 74.7214, 'Group of world-famous Jain temples known for marble carvings.'],
    ['Punjab', 'Amritsar', 'Amritsar', 'Golden Temple', 31.6200, 74.8765, 'The holiest Gurdwara of Sikhism, famous for its gold-covered exterior.'],
    ['Punjab', 'Amritsar', 'Amritsar', 'Wagah Border', 31.6048, 74.5739, 'International border known for its beating retreat ceremony.'],
    ['Uttarakhand', 'Chamoli', 'Govindghat', 'Hemkund Sahib', 30.6994, 79.6055, 'High-altitude Sikh pilgrimage site surrounded by seven snow-capped peaks.'],
    ['Uttarakhand', 'Rudraprayag', 'Kedarnath', 'Kedarnath Temple', 30.7352, 79.0669, 'Remote Himalayan temple dedicated to Lord Shiva.'],
    ['Himachal Pradesh', 'Kangra', 'Dharamshala', 'Tsuglagkhang Complex', 32.2356, 76.3245, 'The official residence of the Dalai Lama.'],
    ['Ladakh', 'Leh', 'Leh', 'Pangong Tso', 33.7595, 78.6674, 'An endorheic lake in the Himalayas at an elevation of 4,350m.'],
    ['Jammu & Kashmir', 'Srinagar', 'Srinagar', 'Hazratbal Shrine', 34.1209, 74.8433, 'White marble shrine believed to house a relic of the Prophet Muhammad.'],

    // --- WEST INDIA ---
    ['Maharashtra', 'Mumbai', 'Mumbai', 'Gateway of India', 18.9220, 72.8347, 'Arch-monument built to commemorate the landing of King George V.'],
    ['Maharashtra', 'Mumbai', 'Mumbai', 'Haji Ali Dargah', 18.9827, 72.8089, 'Mosque and tomb of a Sufi saint located on an islet.'],
    ['Maharashtra', 'Mumbai', 'Mumbai', 'Siddhivinayak Temple', 19.0177, 72.8305, 'Prominent temple dedicated to Lord Ganesha.'],
    ['Maharashtra', 'Aurangabad', 'Ellora', 'Ellora Caves', 20.0258, 75.1780, 'UNESCO site featuring Hindu, Buddhist and Jain cave temples.'],
    ['Maharashtra', 'Aurangabad', 'Ajanta', 'Ajanta Caves', 20.5519, 75.7033, 'Ancient Buddhist rock-cut monuments containing paintings.'],
    ['Gujarat', 'Narmada', 'Kevadia', 'Statue of Unity', 21.8380, 73.7191, 'The world\'s tallest statue depicting Sardar Vallabhbhai Patel.'],
    ['Gujarat', 'Gir Somnath', 'Veraval', 'Somnath Temple', 20.8880, 70.4012, 'The first among the twelve Jyotirlinga shrines of Shiva.'],
    ['Gujarat', 'Valsad', 'Udvada', 'Iranshah Atash Behram', 20.4770, 72.9168, 'The most sacred Parsi fire temple in India.'],
    ['Gujarat', 'Bhavnagar', 'Palitana', 'Shatrunjaya Hill Temples', 21.4981, 71.8415, 'Holy hill with over 800 marble temples for Jains.'],
    ['Goa', 'North Goa', 'Old Goa', 'Basilica of Bom Jesus', 15.5009, 73.9115, 'Contains the mortal remains of St. Francis Xavier.'],

    // --- SOUTH INDIA ---
    ['Karnataka', 'Mysuru', 'Mysuru', 'Mysore Palace', 12.3052, 76.6552, 'Historical palace of the Wadiyar dynasty.'],
    ['Karnataka', 'Vijayanagara', 'Hampi', 'Virupaksha Temple', 15.3358, 76.4582, '7th-century temple, part of the Hampi UNESCO site.'],
    ['Karnataka', 'Hassan', 'Shravanabelagola', 'Gommateshwara Statue', 12.8573, 76.4869, '57-foot monolithic statue of Bahubali.'],
    ['Tamil Nadu', 'Madurai', 'Madurai', 'Meenakshi Amman Temple', 9.9195, 78.1193, 'Historic Hindu temple on the southern bank of the Vaigai River.'],
    ['Tamil Nadu', 'Thanjavur', 'Thanjavur', 'Brihadisvara Temple', 10.7828, 79.1318, 'A brilliant example of Chola architecture and UNESCO site.'],
    ['Tamil Nadu', 'Nagapattinam', 'Velankanni', 'Basilica of Our Lady of Good Health', 10.6806, 79.8436, 'Major Catholic pilgrimage destination, "Lourdes of the East".'],
    ['Kerala', 'Thiruvananthapuram', 'Thiruvananthapuram', 'Padmanabhaswamy Temple', 8.4830, 76.9436, 'Richest temple in the world, featuring gold-plated exterior.'],
    ['Kerala', 'Alappuzha', 'Alleppey', 'Alleppey Backwaters', 9.4981, 76.3329, 'Famous for houseboat cruises through a network of canals.'],
    ['Andhra Pradesh', 'Tirupati', 'Tirumala', 'Venkateswara Temple', 13.6833, 79.3500, 'Landmark Vaishnavite temple on the Tirumala hills.'],
    ['Telangana', 'Hyderabad', 'Hyderabad', 'Charminar', 17.3616, 78.4747, 'Monument and mosque built in 1591, iconic symbol of Hyderabad.'],
    ['Telangana', 'Hyderabad', 'Hyderabad', 'Golconda Fort', 17.3833, 78.4011, 'Fortified citadel and early capital of the Qutb Shahi dynasty.'],

    // --- EAST & NORTHEAST INDIA ---
    ['West Bengal', 'Kolkata', 'Kolkata', 'Victoria Memorial', 22.5448, 88.3426, 'Large marble building dedicated to Queen Victoria.'],
    ['West Bengal', 'Kolkata', 'Kolkata', 'Dakshineswar Kali Temple', 22.6550, 88.3575, 'Temple associated with mystic Ramakrishna Paramahamsa.'],
    ['Odisha', 'Puri', 'Konark', 'Sun Temple', 19.8876, 86.0945, '13th-century chariot-shaped temple built from Khondalite rocks.'],
    ['Bihar', 'Nalanda', 'Pawapuri', 'Jal Mandir', 25.0883, 85.5181, 'White marble temple in a lotus pond where Lord Mahavira attained Nirvana.'],
    ['Bihar', 'Gaya', 'Bodh Gaya', 'Mahabodhi Temple', 24.6959, 84.9914, 'The holiest site where Buddha attained enlightenment.'],
    ['Assam', 'Golaghat', 'Kaziranga', 'Kaziranga National Park', 26.5775, 93.1703, 'Home to two-thirds of the world\'s one-horned rhinoceroses.'],
    ['Arunachal Pradesh', 'Tawang', 'Tawang', 'Tawang Monastery', 27.5878, 91.8596, 'The largest monastery in India and second largest in the world.'],
    ['Meghalaya', 'East Khasi Hills', 'Cherrapunji', 'Double Decker Living Root Bridge', 25.2586, 91.6685, 'Bridge grown from the roots of rubber trees.'],
    ['Manipur', 'Bishnupur', 'Moirang', 'Loktak Lake', 24.5500, 93.8167, 'Largest freshwater lake in NE India, famous for floating islands (Phumdis).'],

    // --- CENTRAL INDIA ---
    ['Madhya Pradesh', 'Chhatarpur', 'Khajuraho', 'Khajuraho Temples', 24.8318, 79.9199, 'Famous for Nagara-style architecture and erotic sculptures.'],
    ['Madhya Pradesh', 'Raisen', 'Sanchi', 'Sanchi Stupa', 23.4807, 77.7363, 'One of the oldest stone structures in India, commissioned by Ashoka.'],
    ['Madhya Pradesh', 'Datia', 'Sonagiri', 'Sonagiri Temples', 25.7111, 78.4716, 'Features 77 white Jain temples on a hill.'],
    ['Chhattisgarh', 'Bastar', 'Jagdalpur', 'Chitrakote Falls', 19.2014, 81.7614, 'The widest waterfall in India, the "Niagara of India".'],

    // --- ISLANDS & TERRITORIES ---
    ['Andaman & Nicobar', 'South Andaman', 'Port Blair', 'Cellular Jail', 11.6740, 92.7485, 'Former colonial prison used to exile political prisoners.'],
    ['Lakshadweep', 'Agatti', 'Agatti', 'Agatti Island', 10.8500, 72.1833, 'Known for its stunning coral reefs and lagoons.'],
    ['Chandigarh', 'Chandigarh', 'Chandigarh', 'Rock Garden', 30.7525, 76.8101, 'Sculpture garden built entirely from industrial waste.'],
    ['Puducherry', 'Puducherry', 'Auroville', 'Matrimandir', 12.0070, 79.8105, 'Golden sphere symbolizing the soul of the city of Auroville.']
];	


/*


// REAL-TIME DATA AS OF JAN 2026
$districts = [
    'Jaipur', 'Jodhpur', 'Udaipur', 'Bikaner', 'Ajmer', 'Kota', 'Bharatpur', 
    'Alwar', 'Sikar', 'Bhilwara', 'Nagaur', 'Pali', 'Barmer', 'Jaisalmer',
    'Churu', 'Hanumangarh', 'Sri Ganganagar', 'Dausa', 'Jhunjhunu', 'Tonk',
    'Beawar', 'Balotra', 'Deeg', 'Didwana-Kuchaman', 'Khairthal-Tijara',
    'Kotputli-Behror', 'Phalodi', 'Salumbar', 'Sanchore', 'Shahpura',
    'Anupgarh', 'Dudu', 'Gangapur City', 'Kekri', 'Neem Ka Thana', 'Khairthal' 
    // ... add all 50 districts
];

$org_masters = [
    ['Revenue Department', 'District Collectorate Office', 'Land revenue and district admin.'],
    ['Medical & Health', 'Chief Medical & Health Office (CMHO)', 'Public health and hospital management.'],
    ['School Education', 'District Education Office (DEO)', 'Elementary and secondary school oversight.'],
    ['Rajasthan Police', 'Superintendent of Police (SP) Office', 'Law, order, and district intelligence.'],
    ['PHED', 'Executive Engineer Office (Water Supply)', 'Drinking water infrastructure.'],
    ['PWD', 'Executive Engineer Office (Roads)', 'State highway and building maintenance.'],
    ['Social Justice', 'District Social Welfare Office', 'Pensions and scholarship distribution.'],
    ['Panchayati Raj', 'Zila Parishad Office', 'Rural development and MGNREGA.'],
    ['Agriculture', 'Deputy Director Agriculture Office', 'Farmer schemes and seed distribution.'],
    ['Transport', 'District Transport Office (DTO)', 'DL and vehicle registration services.'],
    ['Forest', 'Divisional Forest Office (DFO)', 'Wildlife and forest conservation.'],
    ['Co-Operative', 'Deputy Registrar Co-operative Societies', 'Credit societies and farmer unions.'],
    ['Food & Supplies', 'District Supply Office (DSO)', 'Ration card and PDS management.'],
    ['Women & Child', 'WCD District Office', 'Anganwadi and women safety programs.'],
    ['Information Tech', 'DoIT&C District Office', 'e-Mitra and Jan Aadhaar technical support.'],
    ['Industries', 'District Industries Centre (DIC)', 'MSME and industrial promotion.'],
    ['Treasury', 'District Treasury Office', 'Government payment and pension processing.'],
    ['Animal Husbandry', 'Joint Director Animal Husbandry', 'Veterinary services and livestock care.'],
    ['Horticulture', 'Assistant Director Horticulture', 'Fruit and vegetable farming support.'],
    ['Election', 'District Election Office', 'Voter list and election management.']
];

$rajasthan_services = [];

// This loop generates 50 districts * 20 departments = 1,000 Real Entries
foreach ($districts as $dist) {
    foreach ($org_masters as $org) {
        $rajasthan_services[] = [
            'Rajasthan', 
            $dist, 
            $dist, 
            "{$org[1]} - {$dist}", 
            0.00, // Latitude (Can be fetched via GeoAPI)
            0.00, // Longitude (Can be fetched via GeoAPI)
            $org[2]
        ];
    }
}
 
 print_r(json_encode($rajasthan_services));
 die;
 
*/
	// ];

foreach ($topPlacesIndia as $loc) {
    // Matches the 7-element array: State, District, City, Place, Lat, Long, Desc
    list($state, $district, $city, $place_name, $lat, $long, $description) = $loc;
    
    // Combine Place Name and Description for a stronger search vector
    $full_text = "Place: $place_name. Location: $city, $district, $state. Description: $description";
    
    // Generate the vector embedding using your existing function
    $vectorArray = get_embedding($full_text);
    
    if ($vectorArray) {
        $vectorJson = json_encode($vectorArray);

        // Updated to target table: state_palce_embeddings
        // Updated columns: place_name exists, top_places removed
        $stmt = $mysqli->prepare("INSERT INTO state_palce_embeddings 
            (state_name, district, city, place_name, latitude, longitude, description, embedding) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssddss", 
            $state, $district, $city, $place_name, $lat, $long, $description, $vectorJson
        );
        
        if ($stmt->execute()) {
            echo "Successfully indexed: $place_name in $city, $state\n";
        } else {
            echo "Error indexing $place_name: " . $stmt->error . "\n";
        }
        $stmt->close();
    }
}



$mysqli->close();
?>