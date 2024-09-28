<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function db_connect()
{
    // Specify the path to your SQLite database file
    $database_file = "/var/www/home/voter/db/votes.db";

    // Attempt to connect to the SQLite database
    $db = new SQLite3($database_file);

    // Check if the connection was successful
    if (!$db) {
        // If connection fails, handle the error here
        return array('error' => "Connection failed: " . $db->lastErrorMsg());
    } else {
        // Connection successful
        return array('dbmsg' => "Connected to the database", 'db' => $db);
    }
}

// Connect to the database
$connection = db_connect();

// Check for errors in the connection
if (isset($connection['error'])) {
    // Return error response
    echo $connection['error'];
    return;
}

// Get the database object from the connection
$db = $connection['db'];

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; // Get the user ID
    $candidates = $_POST['candidates']; // Get the selected candidates array
    $selectionTime = date('Y-m-d H:i:s'); // Set current timestamp for selection_time

    // Prepare the SQL statement for inserting data
    $stmt = $db->prepare('INSERT INTO user_selections (user_id, candidate_id, selection_time) VALUES (:user_id, :candidate_id, :selection_time)');

    // Insert each selected candidate into the database
    foreach ($candidates as $candidateId) {
        // Bind values and execute the insert query
        $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
        $stmt->bindValue(':candidate_id', $candidateId, SQLITE3_INTEGER);
        $stmt->bindValue(':selection_time', $selectionTime, SQLITE3_TEXT); // Use TEXT for timestamp
        $stmt->execute();
    }

    // Send a success response
    echo json_encode(['status' => 'success', 'message' => 'Selections saved!']);
}
