<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function db_connect()
{
    // Specify the path to your SQLite database file
    $database_file = "/var/www/web/voter/db/votes.db";

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

function insert_candidate()
{
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

    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize inputs to avoid SQL injection
        $id = filter_input(INPUT_POST, 'candidate_id', FILTER_SANITIZE_NUMBER_INT);
        $nourut = $_POST['nourut'];
        $name = filter_input(INPUT_POST, 'nama_can', FILTER_SANITIZE_STRING);
        $visiMisi = filter_input(INPUT_POST, 'visi_misi', FILTER_SANITIZE_STRING);
        $sambutan = filter_input(INPUT_POST, 'sambutan', FILTER_SANITIZE_STRING);

        // Handle file upload if there is no error
        if ($_FILES["picture"]["error"] === UPLOAD_ERR_OK) {
            // File upload handling
            $targetDir = "pict/"; // Specify the target directory
            $fileName = $nourut . '_' . preg_replace("/[^a-zA-Z0-9_-]+/", "", $name) . '.jpg'; // Sanitize file name
            $targetFilePath = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFilePath)) {
                // Prepare SQL query for either INSERT or UPDATE
                if (empty($id)) {
                    // INSERT operation
                    $stmt = $db->prepare('INSERT INTO CANDIDATES (no_urut, candidate_name, picture_url, visi_misi, sambutan) VALUES (:no_urut, :nama_can, :urlp, :visiMisi, :sambutan)');
                } else {
                    // UPDATE operation
                    $stmt = $db->prepare('UPDATE CANDIDATES SET no_urut = :no_urut, candidate_name = :nama_can, picture_url = :urlp, visi_misi = :visiMisi, sambutan = :sambutan WHERE candidate_id = :id');
                    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
                }

                // Bind values
                $stmt->bindValue(':no_urut', $nourut, SQLITE3_TEXT);
                $stmt->bindValue(':nama_can', $name, SQLITE3_TEXT);
                $stmt->bindValue(':urlp', $targetFilePath, SQLITE3_TEXT);
                $stmt->bindValue(':visiMisi', $visiMisi, SQLITE3_TEXT);
                $stmt->bindValue(':sambutan', $sambutan, SQLITE3_TEXT);

                // Execute the query
                $result = $stmt->execute();
                if ($result) {
                    echo "Operation successful.";
                    // Redirect after successful operation
                    header("Location: admin.php");
                    exit();
                } else {
                    echo "Operation failed: " . $db->lastErrorMsg();
                }
            } else {
                // File upload failed
                echo "File upload failed.";
            }
        } else {
            echo "File upload error: " . $_FILES["picture"]["error"];
        }

        // Close database connection
        $db->close();
    }
}

function insert_nasabah()
{
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

    // Check if form data is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize inputs to avoid SQL injection
        $id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        $nasabahId = $_POST['nasabah_id'];
        $namaNasabah = filter_input(INPUT_POST, 'nama_nasabah', FILTER_SANITIZE_STRING);
        $asalBo = filter_input(INPUT_POST, 'asal_bo', FILTER_SANITIZE_STRING);
        $noHp = $_POST['no_hp'];

        // Prepare SQL query for either INSERT or UPDATE
        if (empty($id)) {
            // INSERT operation
            $stmt = $db->prepare('INSERT INTO users (nasabah_id, nama_nasabah, asal_BO, no_hp) VALUES (:nasabah_id, :nama_nasabah, :asal_bo, :no_hp)');
        } else {
            // UPDATE operation
            $stmt = $db->prepare('UPDATE users SET nasabah_id = :nasabah_id, nama_nasabah= :nama_nasabah, asal_bo = :asal_bo, no_hp = :no_hp WHERE user_id = :id');
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        }

        // Bind values
        $stmt->bindValue(':nasabah_id', $nasabahId, SQLITE3_TEXT);
        $stmt->bindValue(':nama_nasabah', $namaNasabah, SQLITE3_TEXT);
        $stmt->bindValue(':asal_bo', $asalBo, SQLITE3_TEXT);
        $stmt->bindValue(':no_hp', $noHp, SQLITE3_TEXT);

        // Execute the query
        $result = $stmt->execute();
        if ($result) {
            echo "Operation successful.";
            // Redirect after successful operation
            header("Location: admin.php");
            exit();
        } else {
            echo "Operation failed: " . $db->lastErrorMsg();
        }

        // Close database connection
        $db->close();
    }
}

// Conditionally execute a specific function
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which action to perform, based on a hidden input field or URL parameter
    if (isset($_POST['action']) && $_POST['action'] == 'candidate') {
        insert_candidate();  // Call insert function
    } elseif (isset($_POST['action']) && $_POST['action'] == 'nasabah') {
        insert_nasabah();  // Call delete function
    }
}
