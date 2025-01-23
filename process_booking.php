<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $persons = $_POST['guests'];
    
    // Get room price based on type
    $room_prices = [
        'normal' => 1500,
        'deluxe' => 3000,
        'suite' => 5000
    ];
    
    $price_per_night = $room_prices[$room_type];
    
    // Calculate total days
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $interval = $check_in_date->diff($check_out_date);
    $days = $interval->days;
    $total_price = $days * $price_per_night;

    try {
        // Format room type name
        $room_type_name = ucfirst($room_type) . ' Room';

        // Insert booking
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, room_type, days, persons, booking_date, total_price) VALUES (?, 1, ?, ?, ?, CURDATE(), ?)");
        $stmt->bind_param("isids", $user_id, $room_type_name, $days, $persons, $total_price);
        
        if (!$stmt->execute()) {
            throw new Exception("Error creating booking: " . $stmt->error);
        }

        $booking_id = $conn->insert_id;

        // Set success data
        $_SESSION['booking_data'] = [
            'booking_id' => $booking_id,
            'room_type' => $room_type_name,
            'check_in' => $check_in,
            'check_out' => $check_out,
            'days' => $days,
            'persons' => $persons,
            'total_price' => $total_price
        ];
        
        header("Location: booking_confirmation.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: room.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Booking - HRS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #E7F6F2;
            padding: 2rem;
        }

        .processing-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .icon {
            font-size: 3rem;
            color: #2C3333;
            margin-bottom: 1rem;
        }

        h1 {
            color: #2C3333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        p {
            color: #395B64;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .spinner {
            border: 4px solid rgba(165, 201, 202, 0.3);
            border-radius: 50%;
            border-top: 4px solid #2C3333;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 1rem auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-message {
            color: #dc3545;
            margin-top: 1rem;
            padding: 1rem;
            background-color: #fff5f5;
            border-radius: 4px;
        }

        .back-link {
            color: #395B64;
            text-decoration: none;
            margin-top: 1rem;
            display: inline-block;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2C3333;
        }
    </style>
</head>
<body>
    <div class="processing-container">
        <?php if (isset($_SESSION['error_message'])): ?>
            <i class="fas fa-exclamation-circle icon"></i>
            <h1>Booking Error</h1>
            <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
            <a href="room.php" class="back-link">← Back to Rooms</a>
            <?php unset($_SESSION['error_message']); ?>
        <?php else: ?>
            <div class="spinner"></div>
            <h1>Processing Your Booking</h1>
            <p>Please wait while we process your reservation...</p>
        <?php endif; ?>
    </div>
</body>
</html>
