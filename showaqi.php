<?php
session_start();


// Get background color from cookie
$bgColor = '#ffffff'; // default
$userEmail = $_SESSION['uname'] ?? '';
if ($userEmail && isset($_COOKIE["user_color_".$userEmail])) {
    $bgColor = $_COOKIE["user_color_".$userEmail];
}

// Restrict access if session data is missing or invalid
if((!isset($_SESSION["uname"]))&&(!isset($_SESSION['selectedCities']) || count($_SESSION['selectedCities']) !== 10)){
    session_destroy();
    header("Location: request.php");
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = ""; 
$dbname = "AQI";
$port = 3307; 

$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$selectedCities = ($_SESSION['selectedCities']); 
$placeholders = implode(',', array_fill(0, count($selectedCities), '?'));

$sql = "SELECT city, country, aqi FROM info WHERE city IN ($placeholders)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(str_repeat('s', count($selectedCities)), ...$selectedCities);
if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();
$fetchedData = [];
while ($row = $result->fetch_assoc()) {
    $fetchedData[$row['city']] = $row;
}

// Generate AQI table rows in original selection order
$dataRows = "";
foreach ($selectedCities as $city) {
    if (isset($fetchedData[$city])) {
        $row = $fetchedData[$city];
        $aqiValue = (int)$row['aqi'];
        $aqiClass = ($aqiValue <= 50) ? "good" :
                   (($aqiValue <= 85) ? "moderate" :
                   (($aqiValue <= 110) ? "unhealthy-sensitive" :
                   (($aqiValue <= 135) ? "unhealthy" : "very-unhealthy")));

        $dataRows .= "<tr>
            <td>" . htmlspecialchars($row['city']) . "</td>
            <td>" . htmlspecialchars($row['country']) . "</td>
            <td class='aqi $aqiClass'>" . htmlspecialchars($row['aqi']) . "</td>
        </tr>";
    } else {
        $dataRows .= "<tr class='missing-data'>
            <td colspan='3'>Data unavailable for: " . htmlspecialchars($city) . "</td>
        </tr>";
    }
}

$stmt->close();
$conn->close();

//unset($_SESSION['selectedCities']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="icon" type="image/x-icon" href="air-quality-index.png">

    <meta charset="UTF-8" />
    <title>Simple AQI</title>
    <style>
        body {
        background-color: <?= htmlspecialchars($bgColor) ?>;
        margin: 0;
        font-family: Tahoma, Geneva, Verdana, sans-serif;
        }

        .top-bar {
        display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgb(255, 254, 254);
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            padding-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-size: 16px;
            font-weight: bold;
        }

        .top-bar a {
        color: white;
        text-decoration: none;
        font-weight: bold;
        }
        
        .signout-link a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
        .signout-link a:hover {
            text-decoration: underline;
        }

        .aqi-table-container {
            background-color: rgb(245, 183, 218);
            width: 100%;
            max-width: 500px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            font-family: Tahoma, Geneva, Verdana, sans-serif;
            margin: 30px auto;
        }

        .aqi-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #6a1b9a;
        }

        #aqi-table {
            width: 100%;
            border-collapse: collapse;
        }

        #aqi-table thead {
            background-color: #ba68c8;
            color: white;
        }

        #aqi-table th, #aqi-table td {
            padding: 14px 18px;
            text-align: left;
            font-size: 18px;
        }

        #aqi-table tbody tr:nth-child(even) {
            background-color: #fce4ec;
        }

        #aqi-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        #aqi-table tbody tr:hover {
            background-color: #f8bbd0;
            transition: background-color 0.3s ease;
        }

        .aqi.good {
            color: #2e7d32;
            font-weight: bold;
        }

        .aqi.moderate {
            color:rgb(238, 168, 29);
            font-weight: bold;
        }

        .aqi.unhealthy-sensitive {
            color:rgb(234, 26, 22);
            font-weight: bold;
        }

        .aqi.unhealthy {
            color:rgb(130, 20, 20);
            font-weight: bold;
        }

        .aqi.very-unhealthy {
            color: #880e4f;
            font-weight: bold;
        }

        tr.missing-data {
            background-color: #ffebee !important;
            color: #c62828;
            font-style: italic;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION["uname"])): ?>
    <div class="top-bar">
        <div><?= htmlspecialchars($userEmail ?: 'Guest') ?></div>
        <div class="signout-link"><a href="signout.php">Sign Out</a></div>
    </div>
<?php endif; ?>

    <div class="aqi-table-container">
        <div class="aqi-title">
            Air Quality Index (AQI)
        </div>
        <table id="aqi-table">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Country</th>
                    <th>AQI</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $dataRows; ?>
            </tbody>
        </table>
    </div>

</body>
</html>