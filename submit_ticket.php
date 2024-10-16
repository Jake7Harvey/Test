<!DOCTYPE html>
<?php

$host = 'localhost'; 
$db = 'userdata';   
$user = 'root'; 
$pass = ''; 

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get input from the previous page
$email = $_POST['email'] ?? '';
$issue = $_POST['issue'] ?? '';
$comments = $_POST['comments'] ?? '';
$image = $_FILES['image'] ?? null;

if (!empty($email)) {
    try {
        // Prepare a statement to pull data from user
        $stmt = $pdo->prepare("SELECT name, address, phone_number, meter_number FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);

        // Fetch the user data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Handle the image upload with validation
            $imageData = null;
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $fileType = mime_content_type($image['tmp_name']);
                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                
                if (in_array($fileType, $allowedTypes)) {
                    $imageData = file_get_contents($image['tmp_name']);
                } else {
                    echo "Invalid file type. Only PNG and JPG files are allowed.";
                    exit;
                }
            } else {
                echo "Image upload error.";
                exit;
            }

            // Prep. statement to insert data into tickets table
            $insertStmt = $pdo->prepare("INSERT INTO tickets (name, address, email, phone_number, meter_number, issue, comments, image) VALUES (:name, :address, :email, :phone_number, :meter_number, :issue, :comments, :image)");

            // Bind parameters and execute the insert statement
            $insertStmt->execute([
                'name' => $userData['name'],
                'address' => $userData['address'],
                'email' => $email, 
                'phone_number' => $userData['phone_number'],
                'meter_number' => $userData['meter_number'],
                'issue' => $issue,
                'comments' => $comments,
                'image' => $imageData
            ]);
            header("Location: report-success.php");
        } else {
            echo "No user found with that email.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>