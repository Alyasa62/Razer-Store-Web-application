<?php

// --- DATABASE CONNECTION DETAILS ---
// It's good practice to keep these at the top.
$host = "localhost";
$dbname = "website_commerce";
$username_db = "root";
$password_db = ""; // Default XAMPP has no password for root

// --- FORM PROCESSING ---
// Only process the form if it was submitted via POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize and retrieve form data
    $name = htmlspecialchars($_POST["name"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    // Basic validation: Ensure no fields are empty
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        die("Error: All fields are required. Please go back and fill them out.");
    }

    try {
        // 2. Establish a secure database connection using PDO
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        // Set PDO to throw exceptions on error, which is a robust way to handle issues.
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 3. CHECK FOR DUPLICATES: Prevent registering an email or username that already exists.
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email); // <-- FIX: Changed "aname" to the correct "$email" variable.
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // A user with this email or username already exists.
            echo "<h2>Registration Failed</h2>";
            echo "<p>An account with that username or email already exists. Please use a different one.</p>";
            echo "<p><a href='register.html'>Go back to registration</a></p>";
        } else {
            // 4. HASH THE PASSWORD: Never store plain-text passwords.
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // 5. INSERT THE NEW USER: Use a prepared statement to prevent SQL injection.
            $stmt = $db->prepare("INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)");
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);
            
            $stmt->execute();

            // 6. SHOW SUCCESS MESSAGE AND REDIRECT
            // Using a meta refresh is a reliable way to redirect after showing a message.
            echo "<h2>Registration Successful!</h2>";
            echo "<p>Thank you for registering, " . $name . "!</p>";
            echo "<p>You will be redirected to the login page in 3 seconds.</p>";
            echo '<meta http-equiv="refresh" content="3;url=login.html" />';
        }

    } catch(PDOException $e) {
        // This will catch any database connection or query errors.
        // For a live site, you might want to log this error instead of showing it to the user.
        die("Database Error: " . $e->getMessage());
    } finally {
        // Always close the connection
        $db = null;
    }
} else {
    // If someone tries to access this page directly without submitting the form
    header("Location: register.html");
    exit();
}
?>
