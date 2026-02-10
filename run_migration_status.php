<?php
// Define BASE_URL if not defined (though config usually does)
// require_once 'app/config/config.php'; // Might need this if Database uses constants
// require_once 'app/config/database.php'; // Might define DB constants
// require_once 'app/core/Database.php';

// Let's try to mimic index.php minimal bootstrap
require_once 'app/config/config.php';
require_once 'app/config/database.php';
require_once 'app/core/Database.php';

try {
    $db = Database::getInstance();
    $sql = "ALTER TABLE posts MODIFY COLUMN status ENUM('draft', 'published', 'pending') DEFAULT 'draft'";
    $db->execute($sql);
    echo "Successfully updated posts status enum column.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
