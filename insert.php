<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Specify the path to your SQLite database file
$database_file = "/var/www/dev/voter/db/votes.db";

// Attempt to connect to the SQLite database
$db = new SQLite3($database_file);

// Check if the connection was successful
if (!$db) {
    // If connection fails, handle the error here
    die("Connection failed: " . $db->lastErrorMsg());
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract the form data
    $id = $_POST['candidate_id'];
    $nourut = $_POST["nourut"];
    $name = $_POST["nama_can"];

    // File upload handling
    $targetDir = "pict/"; // Specify the target directory where the file will be uploaded
    $fileName = $nourut . '_' . $name . '.jpg';
    $targetFilePath = $targetDir . $fileName; // Set the file path

    // if (file_exists($targetFilePath)) {
    //     // If the file exists, delete it
    //     unlink($targetFilePath);
    // }

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFilePath)) {
        // Prepare a statement for the insert operation
        if ($id == "") {
            // If $id is empty, it's an INSERT operation
            $stmt = $db->prepare('INSERT INTO CANDIDATES (no_urut, candidate_name, picture_url) VALUES (:no_urut, :nama_can, :urlp)');
        } else {
            // If $id is not empty, it's an UPDATE operation
            $stmt = $db->prepare('UPDATE CANDIDATES SET no_urut = :no_urut, candidate_name = :nama_can, picture_url = :urlp WHERE candidate_id = :id');
            $stmt->bindValue(':id', $id);
        }

        // Bind parameters to the prepared statement
        $stmt->bindValue(':no_urut', $nourut);
        $stmt->bindValue(':nama_can', $name);
        $stmt->bindValue(':urlp', $targetFilePath);

        // Execute the prepared statement
        $result = $stmt->execute();
        echo $db->lastErrorMsg();
        if ($result) {
            echo "Insert operation successful." . $db->lastErrorMsg();
            header("Location: index.php");
            exit();
        } else {
            echo "Insert operation failed: " . $db->lastErrorMsg();
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // File upload failed
        echo "File upload failed.";
    }
}

// Remember to close the database connection when you're done
$db->close();
