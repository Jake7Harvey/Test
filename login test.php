<?php
session_start();
// Initialize a variable to check if the login is invalid
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Connect to the database
    $mysqli = require __DIR__ . "/database.php";
    
    // Sanitize email input to prevent SQL injection
    $email = $mysqli->real_escape_string($_POST["email"]);
    
    // Query the database for the user with the given email
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    
    // If user exists and the password matches
    if ($user && $_POST["password"] == $user["password"]) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['image'] = $user['image']; 
        $_SESSION['id'] = $user['id']; 
        $_SESSION['authority'] = $user['authority'];
        header("Location: index.php?email=" . urlencode($user['email']) . "&name=" . urlencode($user['name']) . "&authority=" . urlencode($user['authority']));
        exit;
    } else {
        // Set invalid login flag if credentials don't match
        $is_invalid = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="EXXOELECTRIC_FT.css">
<head > 
<div align="center">
    <img src="LongLogo_Blk.png" alt="ExoElectric Logo" width="40%" height="auto"> 
    
    <title>Login</title>
    <meta charset="UTF-8">
</head>

<body align="center">
    <h1>Login</h1>
    
    <?php if ($is_invalid): ?>
        <em>Invalid login credentials. Please try again.</em>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        
        <button type="submit">Log in</button>
    </form>
    
</body>
</html>