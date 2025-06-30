<?php
ob_start(); 
session_start();

// Prevent browser caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

// CSS Styling
echo '<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        font-size: 19px;
        background: white;
    }
    .content {
        text-align: left;
        border: 1px solid #ddd;
        max-width: 330px;
        padding: 20px;
        border-radius: 10px;
        background: rgb(188, 155, 223);
        box-shadow: 0 2px 5px rgba(54, 9, 232, 0.1);
        color: black;
    }
    .error {
        background-color: #e74c3c;
        color: white !important;
    }
    .success {
        background-color: #2ecc71;
        color: white !important;
    }
    .process_button {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 16px;
        background: rgb(115, 47, 187);
        color: white;
        font-family: Amasis MT Pro Black;
        font-size: 17px;
        font-weight: bolder;
        text-decoration: none;
        border-radius: 8px;
        border: 2px solid white;
        cursor: pointer;
    }
    .process_button:hover {
        background: rgb(18, 17, 19);
    }
    .button-box {
        display: flex;
        justify-content: space-between;
    }
</style>';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'confirm') {
        handleDatabaseOperations();
    } else {
        $_SESSION['form_data'] = $_POST;
        showConfirmationPage();
    }
} else {
    echo "<div class='content error'>Invalid request method.</div>";
    header("Refresh: 3; url=index.html");
    exit();
}

// Function to insert into database
function handleDatabaseOperations() {
    $data = $_SESSION['form_data'] ?? [];

    $name     = $data['fullname'] ?? '';
    $gender   = $data['gender'] ?? '';
    $email    = $data['email'] ?? '';
    $password = $data['pass'] ?? '';
    $birth    = $data['birth'] ?? '';
    $country  = $data['country'] ?? '';
    $opinion  = $data['opinion'] ?? '';
    $bgc      = $data['bgc'] ?? '#ffffff';

    if (!$name || !$gender || !$email || !$password || !$birth || !$country) {
        echo "<div class='content error'>Missing required fields.</div>";
        header("Refresh: 2; url=index.html");
        exit();
    }

    $con = mysqli_connect('localhost', 'root', '', 'aqi', 3307);
    if (!$con) {
        echo "<div class='content error'>Connection failed: " . mysqli_connect_error() . "</div>";
        header("Refresh: 2; url=process.php"); 
        exit();
    }

    // Escape inputs
    $name     = mysqli_real_escape_string($con, $name);
    $gender   = mysqli_real_escape_string($con, $gender);
    $email    = mysqli_real_escape_string($con, $email);
    $password = mysqli_real_escape_string($con, $password);
    $birth    = mysqli_real_escape_string($con, $birth);
    $country  = mysqli_real_escape_string($con, $country);
    $opinion  = mysqli_real_escape_string($con, $opinion);

    // Check for unique email and password
    $errors = [];
    $emailCheck = mysqli_query($con, "SELECT * FROM user WHERE Email = '$email'");
    if (mysqli_num_rows($emailCheck) > 0) {
        $errors[] = "Email already exists!";
    }

    $passwordCheck = mysqli_query($con, "SELECT * FROM user WHERE Password = '$password'");
    if (mysqli_num_rows($passwordCheck) > 0) {
        $errors[] = "Password already exists!";
    }

    if (!empty($errors)) {
        echo "<div class='content error'><h3>Registration Error</h3>";
        foreach ($errors as $error) {
            echo "<p>• $error</p>";
        }
        echo "</div>";
        header("Refresh: 2; url=process.php");
        exit();
    }

    // Insert into user table
    $sql = "INSERT INTO user (Name, Gender, Email, Password, `Date of Birth`, Country, Opinion)
            VALUES ('$name', '$gender', '$email', '$password', '$birth', '$country', '$opinion')";

    if (mysqli_query($con, $sql)) {
        // Set cookies
        setcookie("user_color_" . $email, $bgc, time() + (86400 * 30), "/");
        setcookie("user_email", $email, time() + (86400 * 30), "/");

        showSuccessMessage();
        unset($_SESSION['form_data']);
    } else {
        echo "<div class='content error'>Error: " . mysqli_error($con) . "</div>";
        header("Refresh: 2; url=process.php"); 
    }

    mysqli_close($con);
}

// Function to show confirmation page
function showConfirmationPage() {
    $data = $_SESSION['form_data'] ?? $_POST;

    $name     = htmlspecialchars($data['fullname'] ?? '');
    $gender   = htmlspecialchars($data['gender'] ?? '');
    $email    = htmlspecialchars($data['email'] ?? '');
    $password = htmlspecialchars($data['pass'] ?? '');
    $birth    = htmlspecialchars($data['birth'] ?? '');
    $country  = htmlspecialchars($data['country'] ?? '');
    $opinion  = htmlspecialchars($data['opinion'] ?? '');
    $bgcolor  = htmlspecialchars($data['bgc'] ?? '');


    $countryCodes = [
        "BD" => "Bangladesh",
        "IN" => "India",
        "US" => "United States",
        "UK" => "United Kingdom",
        "CA" => "Canada",
        "AU" => "Australia"
    ];
    $countryName = $countryCodes[$country] ?? 'Unknown';

    echo '<div class="content">';
    echo "<h2>User Details</h2>";
    echo "<b>Name:</b> $name<br>";
    echo "<b>Gender:</b> $gender<br>";
    echo "<b>Email:</b> $email<br>";
    echo "<b>Password:</b> $password<br>";
    echo "<b>Date of Birth:</b> $birth<br>";
    echo "<b>Country:</b> $countryName<br>";
    echo "<b>Opinion:</b> $opinion<br>";
    echo "<b>Background Color:</b> $bgcolor<br><br>";
    echo "Do you want to confirm this?";
    
    echo '<div class="button-box">';

    // Cancel button
    echo '<form method="post">';
    echo '<input type="hidden" name="cancel" value="1">';
    echo '<button type="submit" class="process_button">Cancel</button>';
    echo '</form>';

    // Confirm button
    echo '<form method="post">';
    echo '<input type="hidden" name="action" value="confirm">';
    echo '<button type="submit" class="process_button">Confirm</button>';
    echo '</form>';

    echo '</div></div>';

}

// Function to show success message
function showSuccessMessage() {
    echo '<div class="content success">';
    echo '<h2>Success!</h2>';
    echo '<p>Data successfully inserted into the database!</p>';
    echo '</div>';
    header("Refresh: 2; url=index.html");
}
?>
