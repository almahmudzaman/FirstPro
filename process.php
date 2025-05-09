<?php
echo '<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        font-size: 19px;
        background:rgb(219, 207, 232);
    }
    .content {
        text-align: left;
        border: 1px solid #ddd;
        max-width:330px;
        padding: 20px;
        border-radius: 10px;
        background: rgb(188, 155, 223);
        box-shadow: 0 2px 5px rgba(54, 9, 232, 0.1);
    }
    .back-button {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 16px;
        background:rgb(115, 47, 187);
        color: white;
        font-family: Amasis MT Pro Black;
        font-size: 17px;
        font-weight: bolder;
        text-decoration: none;
        border-radius: 8px;
        border: 2px solid white;
        cursor: pointer;
    }
    .back-button:hover {
        background:rgb(18, 17, 19);
    }
    .button-box {
        display: flex;
        justify-content: space-between;
    }
</style>';

echo '<div class="content">';
echo "<h2>User Details</h2>";

// Map country codes to full country names
$countryCodes = [
    "BD" => "Bangladesh",
    "IN" => "India",
    "US" => "United States",
    "UK" => "United Kingdom",
    "CA" => "Canada",
    "AU" => "Australia"
    // Add more as needed
];

// Get country code and map to full name
$countryCode = $_POST['country'] ?? '';
$countryName = $countryCodes[$countryCode] ?? 'Unknown';

// Output user data
echo "Name: " . htmlspecialchars($_POST['fullname']) ;
echo "<br>Gender: " . htmlspecialchars($_POST['gender']) ;
echo "<br>Email: " . htmlspecialchars($_POST['email']) ;
echo "<br>Password: " . htmlspecialchars($_POST['pass']) ;
echo "<br>Date of Birth: " . htmlspecialchars($_POST['birth']) ;
echo "<br>Country: " . $countryName;
echo "<br>Opinion: " . htmlspecialchars($_POST['opinion']);

echo "<br><br>Do you want to confirm this?";
echo '<br><br><div class="button-box">';
echo '<a href="javascript:history.back()" class="back-button">Go Back</a>';
echo '<a href="javascript:void(0);" class="back-button">Confirm</a>';
echo '</div>';
echo '</div>';
?>
