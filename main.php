<?php

// Function to clear screen based on OS
function clearScreen() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}

// Function to generate random user agent
function generateUserAgent() {
    $os = ['Windows', 'Linux', 'iOS', 'Android'];
    $versions = ['8', '9', '10', '11', '12', '13', '14'];
    $devices = ['Samsung', 'Motorola', 'Xiaomi', 'Huawei', 'OnePlus'];

    $selectedOs = $os[array_rand($os)];

    if ($selectedOs === 'Android') {
        $version = $versions[array_rand($versions)];
        $device = $devices[array_rand($devices)];
        $userAgent = "Mozilla/5.0 (Linux; Android $version; $device) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Mobile Safari/537.36";
    } else {
        $userAgent = "Mozilla/5.0 ($selectedOs NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36";
    }

    return $userAgent . rand(1000000, 9999999);
}

// Function to print colored text
function printColored($text, $color) {
    return "\033[" . $color . "m" . $text . "\033[0m";
}

// Color codes
$green = "32";
$red = "31";
$yellow = "33";
$blue = "34";

// Function to print banner
function printBanner() {
    global $yellow;
    $banner = "

 -================= ≫ ──── ≪•◦ ❈ ◦•≫ ──── ≪=================-
 │                                                          │
 │  ██████╗  █████╗ ██████╗ ██╗  ██╗                        │
 │  ██╔══██╗██╔══██╗██╔══██╗██║ ██╔╝                        │
 │  ██║  ██║███████║██████╔╝█████╔╝                         │
 │  ██║  ██║██╔══██║██╔══██╗██╔═██╗                         │
 │  ██████╔╝██║  ██║██║  ██║██║  ██╗                        │
 │  ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝                        │
 │                                                          │
 ╰─━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━─╯

- Version: 2.0
- Created by: @iamak_roy (SCRIPTHUB00)
- Telegram: @scripthub00
- Channel: https://t.me/scripthub0

--------------------------------------------------

";
    echo printColored($banner, $yellow);
}

// Check for users.json file
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    echo printColored("Error: No users found! Add a Telegram ID with: php adduser.php\n", $red);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (!$users) {
    echo printColored("Error: Could not parse users.json!\n", $red);
    exit;
}

$userPoints = array_fill_keys(array_keys($users), 0);

// Function to generate random chat instance
function generateChatInstance() {
    return strval(rand(10000000000000, 99999999999999));
}

// Function to make API request
function makeApiRequest($userId, $tgId) {
    $url = "https://api.adsgram.ai/adv?blockId=4853&tg_id=$tgId&tg_platform=android&platform=Linux%20aarch64&language=en&chat_type=sender&chat_instance=" . generateChatInstance() . "&top_domain=app.notpx.app";
    
    $userAgent = generateUserAgent();
    $baseUrl = "https://app.notpx.app/";

    $headers = [
        'Host: api.adsgram.ai',
        'Connection: keep-alive',
        'Cache-Control: max-age=0',
        'sec-ch-ua-platform: "Android"',
        "User-Agent: $userAgent",
        'sec-ch-ua: "Android WebView";v="131", "Chromium";v="131", "Not_A Brand";v="24"',
        'sec-ch-ua-mobile: ?1',
        'Accept: */*',
        'Origin: https://app.notpx.app',
        'X-Requested-With: org.telegram.messenger',
        'Sec-Fetch-Site: cross-site',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Dest: empty',
        "Referer: $baseUrl",
        'Accept-Encoding: gzip, deflate, br, zstd',
        'Accept-Language: en,en-US;q=0.9'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Set timeout
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$response, $httpCode, $headers];
}

// Function to extract reward value
function extractReward($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['banner']['trackings'])) {
        foreach ($data['banner']['trackings'] as $tracking) {
            if ($tracking['name'] === 'reward') {
                return $tracking['value'];
            }
        }
    }
    return null;
}

$totalPoints = 0;

while (true) {
    clearScreen();
    printBanner();

    foreach ($users as $userId => $userData) {
        $tgId = $userData['tg_id'];

        echo printColored("[ INFO ] Starting engine for $userId...\n", $yellow);
        list($response, $httpCode, $reqHeaders) = makeApiRequest($userId, $tgId);

        if ($httpCode === 200) {
            $reward = extractReward($response);
            if ($reward) {
                $totalPoints += 16;
                $userPoints[$userId] += 16;
                echo printColored("[ SUCCESS ] $userId earned 16 PX\n", $green);
            } else {
                echo printColored("[ ERROR ] Ad limit reached for $userId.\n", $red);
                continue;
            }
        } elseif ($httpCode === 403) {
            echo printColored("[ ERROR ] IP banned. Use VPN.\n", $red);
            exit;
        } else {
            echo printColored("[ ERROR ] HTTP Error: $httpCode for $userId\n", $red);
        }
    }

    echo printColored("Cooldown for 60 seconds...\n", $yellow);
    sleep(60); // Cooldown between cycles
}
?>
