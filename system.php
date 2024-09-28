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

// Conditionally execute a specific function
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which action to perform, based on a hidden input field or URL parameter
    if (isset($_POST['action']) && $_POST['action'] == 'loginNas') {
        cek_nas();  // Call insert function
    }
} else {
    get_candidate();
}

function cek_nas()
{
    $connection = db_connect();

    // Check for errors in the connection
    if (isset($connection['error'])) {
        // Return error response
        return $connection;
    }

    // Get the database object from the connection
    $db = $connection['db'];

    // Extract the candidate ID from the POST data
    $nasabahId = $_POST["nasabah_id"];
    $noHp = $_POST["no_hp"];

    // Perform a query to fetch the user based on nasabah_id
    $stmt = $db->prepare("SELECT * FROM USERS WHERE nasabah_id = :nasabah_id AND no_hp = :no_hp");
    $stmt->bindValue(':nasabah_id', $nasabahId);
    $stmt->bindValue(':no_hp', $noHp);

    // Execute the prepared statement
    $result = $stmt->execute();

    // Check if the query executed successfully
    if ($result) {
        // Fetch user data
        $user = $result->fetchArray(SQLITE3_ASSOC);

        // Check if user exists
        if ($user) {
            // Check if the user has already selected candidates
            $getUserId = $db->prepare('SELECT user_id FROM user_selections WHERE user_id = :user_id');
            $getUserId->bindValue(':user_id', $user['user_id'], SQLITE3_INTEGER);
            $selectionResult = $getUserId->execute();

            // Check if the user has made selections
            if ($selectionResult->fetchArray(SQLITE3_ASSOC)) {
                // If the user has made selections, return an error
                $error = "Anggota sudah memilih.";

                // Close the database connection
                $db->close();

                // Return error response
                return array('error' => $error);
            }

            // If user has not made selections, return user data
            $db->close();
            return array('data' => $user);
        } else {
            // If the user does not exist, return an error
            $db->close();
            return array('error' => "User not found.");
        }
    } else {
        // If the query fails, handle the error
        $error = "Query failed: " . $db->lastErrorMsg();

        // Close the database connection
        $db->close();

        // Return error response
        return array('error' => $error);
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

function get_candidate_votes()
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
    $result = $db->query("SELECT * FROM candidate_votes");

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

function get_nasabah()
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
    $result = $db->query("SELECT * FROM users");

    // Check if the query executed successfully
    if ($result) {
        // Fetch all rows from the result set
        $nasabah = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $nasabah[] = $row;
        }

        // Close the database connection
        $db->close();

        // Return success response with data
        return array('data' => $nasabah);
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
$response = array(
    'candidate' => get_candidate(),
    'candidate_votes' => get_candidate_votes(),
    'nasabah' => get_nasabah(),
    'nas' => cek_nas(),
);

// Encode the response as JSON and output it
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
