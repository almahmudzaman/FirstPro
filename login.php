<?php
ob_start(); // Start output buffering
session_start();

if (isset($_SESSION["uname"])) {
    header("Location: request.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uname = $_POST["username"] ?? '';
    $pass = $_POST["password"] ?? '';

    $conn = mysqli_connect('localhost', 'root', '', 'aqi','3307');

    if (!$conn) {
        echo "<div style='color: white; background-color: red; padding: 10px;'>Database connection failed.</div>";
        header("refresh: 2; url=index.html");
        exit();
    }

    $uname = mysqli_real_escape_string($conn, $uname);
    $pass = mysqli_real_escape_string($conn, $pass);

    $sql = "SELECT * FROM user WHERE email = '$uname' AND password = '$pass' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count === 1) {
        $_SESSION["uname"] = $uname;


        echo "<div style='color: white; background-color: green; padding: 10px;'>Login successful. Redirecting...</div>";
        header("refresh: 2; url=request.php");
        exit();

    } else {
        echo "<div style='color: white; background-color: red; padding: 10px;'>Invalid email or password. Redirecting...</div>";
        header("refresh: 2; url=index.html");
        exit();
    }
} else {
    echo "<div style='color: white; background-color: orange; padding: 10px;'>Please fill in the login form.</div>";
    header("refresh: 2; url=index.html");
    exit();
}

ob_end_flush(); // Flush output buffer
?>
