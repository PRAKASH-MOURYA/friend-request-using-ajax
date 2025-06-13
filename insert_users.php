<?php
require_once 'config.php';

$users = [
    ['Arshdeep', 'password123', 'arshdeep@example.com'],
    ['Priya', 'password123', 'priya@example.com'],
    ['Rajesh', 'password123', 'rajesh@example.com'],
    ['Ananya', 'password123', 'ananya@example.com'],
    ['Vikram', 'password123', 'vikram@example.com'],
    ['Meera', 'password123', 'meera@example.com'],
    ['Amit', 'password123', 'amit@example.com'],
    ['Neha', 'password123', 'neha@example.com'],
    ['Rahul', 'password123', 'rahul@example.com'],
    ['Sneha', 'password123', 'sneha@example.com']
];

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    
    foreach ($users as $user) {
        $hashed_password = password_hash($user[1], PASSWORD_DEFAULT);
        $stmt->execute([$user[0], $hashed_password, $user[2]]);
        echo "Added user: {$user[0]}\n";
    }
    
    echo "All users added successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 