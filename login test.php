<?php
session_start();
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/database.php";

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['image'] = $user['image']; 
        $_SESSION['id'] = $user['id']; 
        $_SESSION['authority'] = $user['authority'];
        header("Location: index.php");
        exit;
    } else {
        $is_invalid = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/EXXOELECTRIC_FT.css">
    <style>
       /* Center the container */
        .container {
            width: 40%;
            margin: auto;
            background-color: #EAEAEA;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        .container h1 {
            color: #395C6B;
        }
        .container label {
            color: #395C6B;
            font-family: "Lucida Console", "Lucida", monospace;
        }
        .container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #BCDBDC;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div align="center">
        <img src="images/LongLogo_Blk.png" alt="ExoElectric Logo" width="50%" height="auto">
    </div>
    <div class="container">
        <form method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Log in</button>
        </form>
        <?php if ($is_invalid): ?>
            <em>Invalid login credentials. Please try again.</em>
        <?php endif; ?>
    </div>
</body>
</html>
