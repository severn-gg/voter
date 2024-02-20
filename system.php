<?php

function db_connect()
{
    // Specify the path to your SQLite database file
    $database_file = "/var/www/dev/voter/db/votes.db";

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

function get_candidate()
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

    // Perform a query to fetch all rows from the 'CANDIDATES' table
    $result = $db->query("SELECT * FROM CANDIDATES");

    // Check if the query executed successfully
    if ($result) {
        // Fetch all rows from the result set
        $candidates = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $candidates[] = $row;
        }

        // Close the database connection
        $db->close();

        // Return success response with data
        return array('data' => $candidates);
    } else {
        // If the query fails, handle the error here
        $error = "Query failed: " . $db->lastErrorMsg();

        // Close the database connection
        $db->close();

        // Return error response
        return array('error' => $error);
    }
}

// Call the function to get candidate data
$response = get_candidate();

// Encode the response as JSON and output it
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
