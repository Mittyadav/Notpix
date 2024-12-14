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

// Gradient text function
function gradientText($text, $colors) {
    $output = "";
    $colorIndex = 0;
    foreach (str_split($text) as $char) {
        $color = $colors[$colorIndex++ % count($colors)];
        $output .= "\033[" . $color . "m" . $char . "\033[0m";
    }
    return $output;
}

// Color codes
$green = "32";
$red = "31";
$yellow = "33";
$blue = "34";
$cyan = "36";
$magenta = "35";
$gradientColors = ["31", "33", "32", "36", "34", "35"];

// Function to print banner
function printBanner() {
    global $gradientColors;
    $banner = "

╔════════════════════════════════════════════════════╗
║                                                    ║
║  ██████╗  █████╗ ██████╗ ██╗  ██╗                  ║
║  ██╔══██╗██╔══██╗██╔══██╗██║ ██╔╝                  ║
║  ██║  ██║███████║██████╔╝█████╔╝                   ║
║  ██║  ██║██╔══██║██╔══██╗██╔═██╗                   ║
║  ██████╔╝██║  ██║██║  ██║██║  ██╗                  ║
║  ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝                  ║
║                                                    ║
║                                                    ║
╚════════════════════════════════════════════════════╝

          - NOT PIXEL AD WATCH -
              
              - VERSION 2.0 -

MADE BY : @iamak_roy (SCRIPTHUB00)
Telegram: @scripthub00
channel: https://t.me/scripthub0

-------------------------------------------------
";

    echo gradientText($banner, $gradientColors);
}

// Function to create animated loading effect
function loadingAnimation($message, $time = 3) {
    $symbols = ['|', '/', '-', '\\'];
    $count = 0;

    echo "\033[s"; // Save cursor position
    for ($i = 0; $i < $time * 4; $i++) {
        echo "\033[u"; // Restore cursor position
        echo printColored("$message " . $symbols[$count++ % 4], "36");
        usleep(250000); // 0.25 seconds
    }
    echo "\033[u\033[K"; // Clear line after animation
}

// Check for users.json file
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    echo printColored("Error: No users found! Please save a Telegram ID by running the command: php adduser.php\nFollow the on-screen instructions to add users.\n", $red);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (!$users) {
    echo printColored("Error: Could not parse users.json!\n", $red);
    exit;
}

$userPoints = array_fill_keys(array_keys($users), 0);

// Rest of your code...
// Add `loadingAnimation` and `gradientText` to other sections as needed
?>
