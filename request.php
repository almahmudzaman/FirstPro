<?php
session_start();

$selectedCities = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    <title>City AQI Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            padding: 15px;
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

        br {
            display: none;
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
            border: 3px solid while;
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
    </style>
</head>
<body>
    <h1>Select Exactly 10 Cities</h1>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Dhaka</label><input type="checkbox" name="cities[]" value="Dhaka" /> <br>
        <label>Delhi</label><input type="checkbox" name="cities[]" value="Delhi" /><br>
        <label>Beijing</label><input type="checkbox" name="cities[]" value="Beijing" /><br>
        <label>Mumbai</label><input type="checkbox" name="cities[]" value="Mumbai" /> <br>
        <label>Tokyo</label><input type="checkbox" name="cities[]" value="Tokyo" /> <br>
        <label>Seoul</label><input type="checkbox" name="cities[]" value="Seoul" /> <br>
        <label>Jakarta</label><input type="checkbox" name="cities[]" value="Jakarta" /> <br>
        <label>Bangkok</label><input type="checkbox" name="cities[]" value="Bangkok" /> <br>
        <label>Karachi</label><input type="checkbox" name="cities[]" value="Karachi" /> <br>
        <label>Shanghai</label><input type="checkbox" name="cities[]" value="Shanghai" /> <br>
        <label>Manila</label><input type="checkbox" name="cities[]" value="Manila" /> <br>
        <label>Kuala Lumpur</label><input type="checkbox" name="cities[]" value="Kuala Lumpur" /> <br>
        <label>Hanoi</label><input type="checkbox" name="cities[]" value="Hanoi" /> <br>
        <label>Ho Chi Minh City</label><input type="checkbox" name="cities[]" value="Ho Chi Minh City" /> <br>
        <label>Lahore</label><input type="checkbox" name="cities[]" value="Lahore" /> <br>
        <label>Tehran</label><input type="checkbox" name="cities[]" value="Tehran" /> <br>
        <label>Bangalore</label><input type="checkbox" name="cities[]" value="Bangalore" /> <br>
        <label>Shenzhen</label><input type="checkbox" name="cities[]" value="Shenzhen" /> <br>
        <label>Osaka</label><input type="checkbox" name="cities[]" value="Osaka" /> 

        <div class="button-container">
            <button type="submit">Submit</button>
        </div>
    </form>
</body>
</html>
