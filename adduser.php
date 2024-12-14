<?php
// Clear screen function
function clearScreen() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}

// Colored text functions
function printGreen($message) {
    echo "\033[1;32m$message\033[0m\n";
}

function printRed($message) {
    echo "\033[1;31m$message\033[0m\n";
}

function printYellow($message) {
    echo "\033[1;33m$message\033[0m\n";
}

function printSkyblue($message) {
    echo "\033[1;36m$message\033[0m\n";
}

// Extract ID from referral link
function extractReferralId($link) {
    if (preg_match('/startapp=f(\d+)/', $link, $matches)) {
        return $matches[1];
    }
    return false;
}

// Initialize script
clearScreen();
printYellow("Welcome to the Not Pixel Referral Link Saver!");
printGreen("1. Open Not Pixel.");
printGreen("2. Copy your Not Pixel referral link.");
printGreen("3. Multiple accounts are supported.");

// File to store user data
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// Main loop to process referral links
while (true) {
    printYellow("Please paste your Not Pixel referral link:");
    $referralLink = trim(fgets(STDIN));
    
    $userId = extractReferralId($referralLink);
    
    if (!$userId) {
        printRed("Error: Invalid Not Pixel referral link! Please try again.");
        continue;
    }
    
    if (isset($users[$userId])) {
        printSkyblue("Error: ID already saved!");
        $userData = $users[$userId];
        printSkyblue("User ID: {$userId}\nSaved At: {$userData['saved_at']}");
        continue;
    }
    
    $users[$userId] = [
        'tg_id' => $userId,
        'saved_at' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    printGreen("Success: ID saved!");
    
    printYellow("Do you want to save more referral links? (y/n):");
    $continue = strtolower(trim(fgets(STDIN)));
    
    if ($continue !== 'y') {
        break;
    }
}

// Display all saved IDs
printSkyblue("\nSaved IDs:");
foreach ($users as $userId => $data) {
    printGreen("User ID: {$userId}, Saved At: {$data['saved_at']}");
}

printYellow("\nThank you for using the Not Pixel Referral Link Saver!");
?>
