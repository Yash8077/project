<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: user_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '_dbconnect.php';

    // Retrieve values from session or form
    $name = $_SESSION["name"];
    $email = $_SESSION['email'];
    $userId = $_SESSION['user_id'];
    $restaurantId = $_SESSION['rest_id'];
    $phoneNO = $_POST['phone'] ?? ''; 
    $_SESSION['Billing_phone'] = $phoneNO;

    $address1 = $_POST["address1"] ?? '';
    $address2 = $_POST["address2"] ?? '';
    $address3 = $_POST["address3"] ?? '';
    $address = $address1 . " " . $address2 . " " . $address3;
    $_SESSION['Billing_address'] = $address;

    $orderItems = $_SESSION['Order'] ?? [];
    $amount = $_SESSION['amount'] ?? 0;
    $tranStatus = "COMPLETED";
    $orderStatus = "Pending";
    $rating = 0; // Default rating

    $orderId = $_SESSION['orderid']; // Assume you have this stored already
    $dateTime = date("Y-m-d H:i:s"); // Current date and time

    // Prepare order items as a string
    $orderString = implode(", ", $orderItems);

    // Insert into the `orders` table
    $sql = "INSERT INTO `orders` (`r_id`, `order_id`, `dt`, `name`, `user_id`, `order`, `amount`, `address`, `phone`, `payment`, `order_status`, `rating`) 
            VALUES ('$restaurantId', '$orderId', '$dateTime', '$name', '$userId', '$orderString', '$amount', '$address', '$phoneNO', '$tranStatus', '$orderStatus', '$rating')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .tick {
            font-size: 80px;
            color: #4CAF50;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
            margin: 10px 0;
        }
        .details {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="gtranslate_wrapper"></div>
  <script>window.gtranslateSettings = { "default_language": "en", "detect_browser_language": true, "wrapper_selector": ".gtranslate_wrapper" }</script>
  <script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>

    <div class="container">
        <div class="tick">✔</div>
        <h1>Order Placed Successfully</h1>
        <p>Thank you, <strong>$name</strong>, for your order.</p>
        <div class="details">
            <p><strong>Amount Paid:</strong> $$amount</p>
            <p><strong>Order ID:</strong> $orderId</p>
            <p><strong>Status:</strong> $orderStatus</p>
        </div>
        <p style="margin-top: 20px;">We appreciate your business!</p>
    </div>
</body>
</html>
HTML;
    } else {
        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .error {
            font-size: 80px;
            color: #FF5722;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error">✘</div>
        <h1>Order Failed</h1>
        <p>There was an error processing your order. Please try again.</p>
    </div>
</body>
</html>
HTML;
    }
}
?>
