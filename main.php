<?php

// Function to clear the screen
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
        return "Mozilla/5.0 (Linux; Android $version; $device) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Mobile Safari/537.36";
    } else {
        return "Mozilla/5.0 ($selectedOs NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36";
    }
}

// Print colored text
function printColored($text, $color) {
    return "\033[" . $color . "m" . $text . "\033[0m";
}

// Color codes
$green = "32";
$red = "31";
$yellow = "33";
$blue = "34";
$magenta = "35";
$cyan = "36";

// Function to print a banner
function printBanner() {
    global $magenta;
    $banner = "
 ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 ┃                                                       ┃
 ┃   ███╗   ██╗ ██████╗ ████████╗ ██████╗ ██╗  ██╗       ┃
 ┃   ████╗  ██║██╔═══██╗╚══██╔══╝██╔═══██╗██║ ██╔╝       ┃
 ┃   ██╔██╗ ██║██║   ██║   ██║   ██║   ██║█████╔╝        ┃
 ┃   ██║╚██╗██║██║   ██║   ██║   ██║   ██║██╔═██╗        ┃
 ┃   ██║ ╚████║╚██████╔╝   ██║   ╚██████╔╝██║  ██╗       ┃
 ┃   ╚═╝  ╚═══╝ ╚═════╝    ╚═╝    ╚═════╝ ╚═╝  ╚═╝       ┃
 ┃                                                       ┃
 ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    NOT PIXEL AD WATCH - VERSION 2.0
     🚀 Cool Edition by ScriptHub00 🚀

 - MADE BY  : @iamak_roy
 - TELEGRAM : @scripthub00
 - CHANNEL  : https://t.me/scripthub0

 ⚠️ Note: If you encounter the issue "URL not found",
    kindly ignore it. PX Points will be added
    to your account within 20 seconds.
 ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

";
    echo printColored($banner, $magenta);
}

// Check for `users.json` file
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    echo printColored("Error: No users found! Please save a Telegram ID by running `php adduser.php`.\nFollow the on-screen instructions to add users.\n", $red);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (!$users) {
    echo printColored("Error: Could not parse users.json!\n", $red);
    exit;
}

// Initialize user points
$userPoints = array_fill_keys(array_keys($users), 0);

// Function to generate a random chat instance
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
        'sec-ch-ua-mobile: ?1',
        'Accept: */*',
        'Origin: https://app.notpx.app',
        'X-Requested-With: org.telegram.messenger',
        "Referer: $baseUrl",
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: en,en-US;q=0.9'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$response, $httpCode];
}

// Extract reward value
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

// Main loop
$totalPoints = 0;

while (true) {
    clearScreen();
    printBanner();

    foreach ($users as $userId => $userData) {
        echo printColored("[ INFO ] Starting Not Pixel Engine for User ID: $userId\n", $cyan);

        list($response, $httpCode) = makeApiRequest($userId, $userData['tg_id']);

        if ($httpCode === 200) {
            $reward = extractReward($response);
            if ($reward) {
                $userPoints[$userId] += $reward;
                $totalPoints += $reward;
                echo printColored("[ SUCCESS ] User ID: $userId Earned $reward PX\n", $green);
            } else {
                echo printColored("[ ERROR ] Limit reached for User ID: $userId\n", $yellow);
            }
        } else {
            echo printColored("[ ERROR ] HTTP Code $httpCode for User ID: $userId\n", $red);
        }
    }

    echo printColored("\nTotal PX Earned: $totalPoints\n", $green);

    sleep(20); // Cooldown
}
