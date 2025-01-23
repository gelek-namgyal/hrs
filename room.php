<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRS - Room Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        header {
            background-color: #2C3333;
            padding: 1.5rem 10%;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            gap: 3rem;
            list-style: none;
        }

        .nav-links a {
            color: #E7F6F2;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #A5C9CA;
        }

        .user-icon a {
            font-size: 1.5rem;
            color: #E7F6F2;
            transition: color 0.3s ease;
        }

        .user-icon a:hover {
            color: #A5C9CA;
        }

        .room-details-section {
            padding: 8rem 5% 4rem;
            background-color: #E7F6F2;
        }

        .room-type-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-top: 2rem;
        }

        .tab-btn {
            padding: 1rem 2rem;
            border: none;
            background-color: #ffffff;
            color: #2C3333;
            font-size: 1.1rem;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid #A5C9CA;
        }

        .tab-btn:hover {
            background-color: #A5C9CA;
            color: #ffffff;
        }

        .tab-btn.active {
            background-color: #2C3333;
            color: #ffffff;
            border-color: #2C3333;
        }

        .room-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .room-images {
            padding: 2rem;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .thumbnail {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .thumbnail:hover {
            opacity: 0.8;
        }

        .room-info {
            padding: 2rem;
        }

        .room-info h1 {
            color: #2C3333;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .room-info p {
            color: #395B64;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .price {
            font-size: 2rem;
            color: #2C3333;
            margin: 2rem 0;
        }

        .price span {
            font-size: 1rem;
            color: #395B64;
        }

        .amenities {
            margin: 2rem 0;
        }

        .amenities h3 {
            color: #2C3333;
            margin-bottom: 1rem;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #395B64;
        }

        .booking-form {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(165, 201, 202, 0.3);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #2C3333;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #A5C9CA;
            border-radius: 4px;
            font-size: 1rem;
        }

        .book-button {
            background-color: #2C3333;
            color: #E7F6F2;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .book-button:hover {
            background-color: #395B64;
        }

        .room-tab {
            display: none;
        }

        .room-tab.active {
            display: grid;
        }

        .footer-section {
            background-color: #2C3333;
            color: #E7F6F2;
            padding: 5rem 5% 2rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
        }

        .footer-column h4 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 0.8rem;
        }

        .footer-column ul li a {
            color: #A5C9CA;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-column ul li a:hover {
            color: #E7F6F2;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="nav-links">
                <a href="index.html">Home</a>
                <a href="index.html#rooms">Rooms</a>
                <a href="index.html#services">Services</a>
            </div>
            <div class="user-icon">
                <a href="profile.php"><i class="fas fa-user"></i></a>
            </div>
        </nav>
    </header>

    <section class="room-details-section">
        <div class="room-type-tabs">
            <button class="tab-btn active" data-room="normal">Normal Room</button>
            <button class="tab-btn" data-room="deluxe">Deluxe Room</button>
            <button class="tab-btn" data-room="suite">Suite Room</button>
        </div>

        <!-- Normal Room -->
        <div class="room-container room-tab active" id="normal-room">
            <div class="room-images">
                <img src="img/hotel-normal.jpg" alt="Normal Room Main Image" class="main-image">
                <div class="image-gallery">
                    <img src="img/hotel-normal.jpg" alt="Normal Room View 1" class="thumbnail">
                    <img src="img/hotel room1.jpg" alt="Normal Room View 2" class="thumbnail">
                    <img src="img/economy room.jpg" alt="Normal Room View 3" class="thumbnail">
                    <img src="img/service.jpg" alt="Normal Room View 4" class="thumbnail">
                </div>
            </div>
            <div class="room-info">
                <h1>Normal Room</h1>
                <p>Comfortable and cozy room perfect for solo travelers or couples. Features essential amenities for a pleasant stay.</p>
                <div class="price">
                    Rs.1500 <span>/night</span>
                </div>
                <div class="amenities">
                    <h3>Room Amenities</h3>
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <i class="fas fa-wifi"></i>
                            <span>Free WiFi</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-tv"></i>
                            <span>TV</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-snowflake"></i>
                            <span>Air Conditioning</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-bed"></i>
                            <span>Queen Size Bed</span>
                        </div>
                    </div>
                </div>
                <form class="booking-form" action="process_booking.php" method="POST">
                    <input type="hidden" name="room_type" value="normal">
                    <div class="form-group">
                        <label for="check-in-normal">Check-in Date</label>
                        <input type="date" id="check-in-normal" name="check_in" required>
                    </div>
                    <div class="form-group">
                        <label for="check-out-normal">Check-out Date</label>
                        <input type="date" id="check-out-normal" name="check_out" required>
                    </div>
                    <div class="form-group">
                        <label for="guests-normal">Number of Guests</label>
                        <input type="number" id="guests-normal" name="guests" min="1" max="2" required>
                    </div>
                    <button type="submit" class="book-button">Book Now</button>
                </form>
            </div>
        </div>

        <!-- Deluxe Room -->
        <div class="room-container room-tab" id="deluxe-room">
            <div class="room-images">
                <img src="img/hotel-deluxe.jpg" alt="Deluxe Room Main Image" class="main-image">
                <div class="image-gallery">
                    <img src="img/hotel-deluxe.jpg" alt="Deluxe Room View 1" class="thumbnail">
                    <img src="img/hotel room1.jpg" alt="Deluxe Room View 2" class="thumbnail">
                    <img src="img/hotel-normal.jpg" alt="Deluxe Room View 3" class="thumbnail">
                    <img src="img/service.jpg" alt="Deluxe Room View 4" class="thumbnail">
                </div>
            </div>
            <div class="room-info">
                <h1>Deluxe Room</h1>
                <p>Experience luxury and comfort in our spacious Deluxe Room. Perfect for both business and leisure travelers, featuring modern amenities and stunning views.</p>
                <div class="price">
                    Rs.3000 <span>/night</span>
                </div>
                <div class="amenities">
                    <h3>Room Amenities</h3>
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <i class="fas fa-wifi"></i>
                            <span>High-Speed WiFi</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-tv"></i>
                            <span>Smart TV</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-snowflake"></i>
                            <span>Air Conditioning</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-coffee"></i>
                            <span>Coffee Maker</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-utensils"></i>
                            <span>Mini Bar</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-concierge-bell"></i>
                            <span>Room Service</span>
                        </div>
                    </div>
                </div>
                <form class="booking-form" action="process_booking.php" method="POST">
                    <input type="hidden" name="room_type" value="deluxe">
                    <div class="form-group">
                        <label for="check-in-deluxe">Check-in Date</label>
                        <input type="date" id="check-in-deluxe" name="check_in" required>
                    </div>
                    <div class="form-group">
                        <label for="check-out-deluxe">Check-out Date</label>
                        <input type="date" id="check-out-deluxe" name="check_out" required>
                    </div>
                    <div class="form-group">
                        <label for="guests-deluxe">Number of Guests</label>
                        <input type="number" id="guests-deluxe" name="guests" min="1" max="3" required>
                    </div>
                    <button type="submit" class="book-button">Book Now</button>
                </form>
            </div>
        </div>

        <!-- Suite Room -->
        <div class="room-container room-tab" id="suite-room">
            <div class="room-images">
                <img src="img/hotel-suite.jpg" alt="Suite Room Main Image" class="main-image">
                <div class="image-gallery">
                    <img src="img/hotel-suite.jpg" alt="Suite Room View 1" class="thumbnail">
                    <img src="img/hotel room1.jpg" alt="Suite Room View 2" class="thumbnail">
                    <img src="img/hotel-deluxe.jpg" alt="Suite Room View 3" class="thumbnail">
                    <img src="img/service.jpg" alt="Suite Room View 4" class="thumbnail">
                </div>
            </div>
            <div class="room-info">
                <h1>Suite Room</h1>
                <p>Our most luxurious accommodation featuring a separate living area, premium amenities, and breathtaking views. Perfect for families or those seeking the ultimate comfort.</p>
                <div class="price">
                    Rs.5000 <span>/night</span>
                </div>
                <div class="amenities">
                    <h3>Room Amenities</h3>
                    <div class="amenities-grid">
                        <div class="amenity-item">
                            <i class="fas fa-wifi"></i>
                            <span>Premium WiFi</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-tv"></i>
                            <span>65" Smart TV</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-snowflake"></i>
                            <span>Climate Control</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-coffee"></i>
                            <span>Premium Coffee Maker</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-utensils"></i>
                            <span>Mini Kitchen</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-concierge-bell"></i>
                            <span>24/7 Room Service</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-couch"></i>
                            <span>Living Area</span>
                        </div>
                        <div class="amenity-item">
                            <i class="fas fa-bath"></i>
                            <span>Luxury Bathroom</span>
                        </div>
                    </div>
                </div>
                <form class="booking-form" action="process_booking.php" method="POST">
                    <input type="hidden" name="room_type" value="suite">
                    <div class="form-group">
                        <label for="check-in-suite">Check-in Date</label>
                        <input type="date" id="check-in-suite" name="check_in" required>
                    </div>
                    <div class="form-group">
                        <label for="check-out-suite">Check-out Date</label>
                        <input type="date" id="check-out-suite" name="check_out" required>
                    </div>
                    <div class="form-group">
                        <label for="guests-suite">Number of Guests</label>
                        <input type="number" id="guests-suite" name="guests" min="1" max="4" required>
                    </div>
                    <button type="submit" class="book-button">Book Now</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Rooms</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">About Us</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Contact Us</h4>
                <ul>
                    <li><a href="#">Email</a></li>
                    <li><a href="#">Phone</a></li>
                    <li><a href="#">Address</a></li>
                    <li><a href="#">Support</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Follow Us</h4>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">LinkedIn</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image gallery functionality
            const mainImages = document.querySelectorAll('.main-image');
            const thumbnails = document.querySelectorAll('.thumbnail');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const mainImage = this.closest('.room-images').querySelector('.main-image');
                    mainImage.src = this.src;
                });
            });

            // Room type tabs functionality
            const tabButtons = document.querySelectorAll('.tab-btn');
            const roomTabs = document.querySelectorAll('.room-tab');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const roomType = button.getAttribute('data-room');
                    
                    // Update active button
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    // Update active room tab
                    roomTabs.forEach(tab => tab.classList.remove('active'));
                    document.getElementById(`${roomType}-room`).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
