<?php
session_start();
$selectedCities = [];
$error = "";

if (!isset($_SESSION["uname"]) || empty($_SESSION["uname"]) || $_SESSION["uname"] === "guest") {
    $loginMessage = true;
} else {
    $loginMessage = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$loginMessage) {
    if (isset($_POST['cities'])) {
        $selectedCities = $_POST['cities'];
        $count = count($selectedCities);
        if ($count < 10) {
            $error = "❌ Please select exactly 10 cities. You have selected only $count.";
        } elseif ($count > 10) {
            $error = "❌ Please select exactly 10 cities. You have selected $count.";
        } else {
            $_SESSION['selectedCities'] = $selectedCities;
            header("Location: showaqi.php");
            exit();
        }
    } else {
        $error = "❌ Please select exactly 10 cities. None were selected.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
        <link rel="icon" type="image/x-icon" href="air-quality-index.png">

    <title>Simple AQI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            padding: 15px;
        }
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgb(255, 254, 254);
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-size: 16px;
            font-weight: bold;
        }
        .user-info {
            color: #2c3e50;
        }
        .signout-link a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
        .signout-link a:hover {
            text-decoration: underline;
        }
        .login-msg {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            margin: 10px auto 20px;
            width: fit-content;
            box-shadow: 0 0 5px #aaa;
        }
        h1 {
            text-align: center;
        }
        form {
            background-color: #e7f6e6;
            padding: 30px;
            border-radius: 8px;
            max-width: 300px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
        }
        label {
            display: inline-block;
            width: 200px;
            padding-left: 30px;
            margin-bottom: 8px;
            font-size: 18px;
            font-weight: bold;
        }
        input[type="checkbox"] {
            width: 14px;
            height: 14px;
            transform: scale(1.5);
            margin-right: 10px;
            cursor: pointer;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .button-container button {
            padding: 8px 16px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            background-color: #202323;
            color: #f1ecec;
            border: 3px solid white;
            border-radius: 5px;
        }
        .button-container button:hover {
            background-color: rgb(15, 176, 133);
            transform: scale(1.05);
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        #counter {
            display: none;
            text-align: center;
            font-weight: bold;
            color: #333;
            margin: 10px auto;
            font-size: 18px;
        }
    </style>
</head>
<body>

<?php if (!$loginMessage && isset($_SESSION["uname"])): ?>
    <div class="header-bar">
        <div class="user-info">Welcome, <b><?php echo $_SESSION["uname"]; ?></b></div>
        <div class="signout-link"><a href="signout.php">Sign Out</a></div>
    </div>
<?php endif; ?>

<?php if ($loginMessage): ?>
    <div class="login-msg" id="loginMsg">⚠ Please login first.</div>
    <script>
        setTimeout(() => {
            window.location.href = "index.html";
        }, 1000);
    </script>
<?php else: ?>

    <h1>Select Exactly 10 Cities</h1>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <div id="counter">You have selected <span id="selectedCount">0</span> cities.</div>

    <form method="post" action="">
        
        <?php
        $cities = ["Dhaka", "Delhi", "Beijing", "Mumbai", "Tokyo", "Seoul", "Jakarta", "Bangkok", "Karachi", "Shanghai", "Manila", "Kuala Lumpur", "Hanoi", "Ho Chi Minh City", "Lahore", "Tehran", "Bangalore", "Shenzhen", "Osaka"];
        foreach ($cities as $city) {
            echo "<label>$city</label><input type=\"checkbox\" name=\"cities[]\" value=\"$city\" onchange=\"updateCounter()\"><br>";
        }
        ?>
        <div class="button-container">
            <button type="submit">Submit</button>
        </div>
    </form>

    <script>
        function updateCounter() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            const count = checkboxes.length;
            const counterDiv = document.getElementById("counter");
            const countSpan = document.getElementById("selectedCount");

            if (count > 0) {
                counterDiv.style.display = "block";
                countSpan.textContent = count;
            } else {
                counterDiv.style.display = "none";
            }
        }
    </script>

<?php endif; ?>

</body>
</html>
