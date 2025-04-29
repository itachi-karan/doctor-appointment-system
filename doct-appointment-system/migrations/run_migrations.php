<?php
// Auto-run migrations via PHP for accidents table
require_once __DIR__ . '/../config.php';

global $db;
try {
    // Disable FK checks
    $db->exec('SET FOREIGN_KEY_CHECKS=0');
    // Run SQL from file
    $sql = file_get_contents(__DIR__ . '/create_accidents_table.sql');
    $db->exec($sql);
    // Re-enable FK checks
    $db->exec('SET FOREIGN_KEY_CHECKS=1');
    echo "Migration completed: 'accidents' table is now created.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
