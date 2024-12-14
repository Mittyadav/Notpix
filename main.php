<?php

// Function to clear the screen
function clearScreen() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}

// Function to generate a random user agent
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

// Function to print colored and styled text
function printStyled($text, $style) {
    return "\033[" . $style . "m" . $text . "\033[0m";
}

// Define ANSI color codes for futuristic styling
$styles = [
    'green' => '32',
    'red' => '31',
    'yellow' => '33',
    'blue' => '34',
    'cyan' => '36',
    'magenta' => '35',
    'bold' => '1',
    'underline' => '4',
    'blink' => '5'
];

// Function to print the updated futuristic banner
function printBanner() {
    global $styles;

    $banner = "

    ╔═════════════════════╗
    ║                     ║
    ║  █████╗ ███████╗██╗ ║
    ║ ██╔══██╗██╔════╝██║ ║
    ║ ███████║███████╗██║ ║
    ║ ██╔══██║╚════██║██║ ║
    ║ ██║  ██║███████║██║ ║
    ║ ╚═╝  ╚═╝╚══════╝╚═╝ ║
    ║                     ║
    ╚═════════════════════╝

         - Futuristic Engine -
    ";

    echo printStyled($banner, $styles['cyan'] . ";" . $styles['bold']);
    echo printStyled("     - Version 2.0 -\n", $styles['magenta']);
    echo printStyled("     Developed by: @scripthub00\n", $styles['blue']);
    echo printStyled("     Telegram: t.me/scripthub0\n", $styles['yellow']);
    echo printStyled("\n-------------------------------------------------\n\n", $styles['green']);
}

// Check for users.json file
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    echo printStyled("Error: No users found! Run `php adduser.php`.\n", $styles['red'] . ";" . $styles['blink']);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (!$users) {
    echo printStyled("Error: Could not parse users.json!\n", $styles['red']);
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
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [$response, $httpCode, $headers];
}

// Infinite loop for the engine
$totalPoints = 0;
$firstRun = true;

while (true) {
    clearScreen();
    printBanner();

    foreach ($users as $userId => $userData) {
        echo printStyled("---> $userId +{$userPoints[$userId]} PX\n", $styles['green']);
    }
    echo printStyled("\nTotal PX Earned: $totalPoints\n\n", $styles['yellow']);

    for ($i = 20; $i > 0; $i--) {
        echo printStyled("Cooldown: $i seconds left...\r", $styles['cyan']);
        sleep(1);
    }
}
?>
