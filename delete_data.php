<?php

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

function get_data()
{
    // Connect to the database
    $connection = db_connect();

    // Check for errors in the connection
    if (isset($connection['error'])) {
        // Return error response
        return $connection;
    }

    // Get the database object from the connection
    $db = $connection['db'];

    // Extract the candidate ID from the POST data
    $table = $_POST["table"];
    $field = $_POST["field"];
    $id = $_POST["id"];

    // Prepare a statement to delete the candidate
    // $stmt = $db->prepare("DELETE FROM CANDIDATES WHERE candidate_id = :id");
    $stmt = $db->prepare("DELETE FROM " . $db->escapeString($table) . " WHERE " . $db->escapeString($field) . " = :id");
    $stmt->bindValue(':id', $id);

    // Execute the prepared statement
    $result = $stmt->execute();

    // Check if the delete operation was successful
    if ($result) {
        // Close the prepared statement
        $stmt->close();

        // Close the database connection
        $db->close();

        // Return success response
        return array('success' => 'Data deleted successfully');
    } else {
        // If the delete operation fails, handle the error here
        $error = "Delete operation failed: " . $db->lastErrorMsg();

        // Close the prepared statement
        $stmt->close();

        // Close the database connection
        $db->close();

        // Return error response
        return array('error' => $error);
    }
}


// Call the function to get candidate data
$response = get_data();

// Encode the response as JSON and output it
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
