<?php
require_once 'config.php';

try {
    // Try to execute a simple query
    $stmt = $pdo->query("SELECT 1");
    echo "Database connection successful!";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?> 