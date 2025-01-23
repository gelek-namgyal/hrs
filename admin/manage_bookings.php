<?php
session_start();
include '../config.php';

// Fetch all bookings with user details
$bookingsQuery = $conn->query("
    SELECT b.*, u.full_name, u.email, u.phone_number
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.id
    ORDER BY b.booking_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - HRS Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            background-color: #E7F6F2;
            min-height: 100vh;
            color: #2C3333;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2C3333, #395B64);
            color: #E7F6F2;
            padding: 0;
            position: fixed;
            height: 100vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 2px solid #A5C9CA;
            text-align: center;
        }

        .sidebar-header h2 {
            color: #E7F6F2;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1rem 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #E7F6F2;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.3rem 1rem;
            border-radius: 10px;
            font-weight: 500;
        }

        .sidebar a i {
            width: 24px;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .sidebar a:hover {
            background-color: #A5C9CA;
            transform: translateX(5px);
        }

        .sidebar a.active {
            background-color: #A5C9CA;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content-wrapper {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }

        .navbar {
            background-color: #ffffff;
            padding: 1.2rem 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .navbar h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2C3333;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #E7F6F2;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            width: 300px;
        }

        .search-box input {
            border: none;
            background: none;
            padding: 0.5rem;
            width: 100%;
            font-size: 1rem;
            color: #2C3333;
        }

        .search-box input:focus {
            outline: none;
        }

        .table-container {
            background: #ffffff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background-color: #E7F6F2;
            color: #2C3333;
            font-weight: 600;
            padding: 1.2rem 1rem;
            text-align: left;
            border-bottom: 2px solid #395B64;
        }

        td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        tr:hover td {
            background-color: #E7F6F2;
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            gap: 0.5rem;
        }

        .status::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-pending::before {
            background-color: #856404;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-confirmed::before {
            background-color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-cancelled::before {
            background-color: #721c24;
        }

        .action-btn {
            padding: 0.5rem;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin: 0 0.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .confirm-btn {
            background-color: #2C3333;
            color: #E7F6F2;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: #ffffff;
        }

        .view-btn {
            background-color: #395B64;
            color: #E7F6F2;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                padding: 1rem;
            }

            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .search-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-hotel"></i> HRS Admin</h2>
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="manage_bookings.php" class="active">
                <i class="fas fa-calendar-check"></i>
                <span>Manage Bookings</span>
            </a>
            
            <a href="user_management.php">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>

            <a href="../database/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="navbar">
            <h1><i class="fas fa-calendar-check"></i> Manage Bookings</h1>
            <div class="search-box">
                <input type="text" id="searchBookings" placeholder="Search bookings...">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Details</th>
                        <th>Room Details</th>
                        <th>Stay Details</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($booking = $bookingsQuery->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $booking['booking_id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($booking['full_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($booking['email']); ?></small><br>
                            <small><?php echo htmlspecialchars($booking['phone_number']); ?></small>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['room_type']); ?><br>
                            <small>Room ID: <?php echo $booking['room_id']; ?></small>
                        </td>
                        <td>
                            Check-in: <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?><br>
                            Duration: <?php echo $booking['days']; ?> days<br>
                            Guests: <?php echo $booking['persons']; ?> persons
                        </td>
                        <td>Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
                        <td>
                            <button class="action-btn view-btn" title="View Details" onclick="viewBooking(<?php echo $booking['booking_id']; ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn confirm-btn" title="Confirm Booking" onclick="confirmBooking(<?php echo $booking['booking_id']; ?>)">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="action-btn cancel-btn" title="Cancel Booking" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchBookings').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        function viewBooking(bookingId) {
            // Add view booking logic
            alert('View booking: ' + bookingId);
        }

        function confirmBooking(bookingId) {
            if(confirm('Are you sure you want to confirm this booking?')) {
                // Add confirmation logic
            }
        }

        function cancelBooking(bookingId) {
            if(confirm('Are you sure you want to cancel this booking?')) {
                // Add cancellation logic
            }
        }
    </script>
</body>
</html>
