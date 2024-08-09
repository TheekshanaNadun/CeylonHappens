<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Publishing Form</title>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navv">
        <a href="#" id="logo"><img src="assets/logo 2.png" class="logo2"></a>
        <ul>
            <li><a href="http://localhost/CeylonHappens/index.php">Home</a></li>
            <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
            <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
            <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
            <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
        </ul>
    </nav>
    <div class="container">
        <div class="header">
            <h1>Ceylon Happens</h1>
            <p>Please fill out the form below to publish your event. Ensure all details are accurate and complete.</p>
        </div>
        <div class="form-container">
            <div class="form-image" >
                <img src="Picture1.png" alt="Event Image" style="aspect-ratio: auto;">
            </div>
            <div class="form-content">
                <h2>Event Publishing Form</h2>
                <form id="event-form" action="process_event.php" method="POST" enctype="multipart/form-data">
                    <!-- Event Title -->
                    <div class="form-group">
                        <label for="event-title">Event Title</label>
                        <input type="text" id="event-title" name="event-title" placeholder="Event Title" required>
                    </div>

                    <!-- Event Description -->
                    <div class="form-group">
                        <label for="event-description">Event Description</label>
                        <textarea id="event-description" name="event-description" rows="4" placeholder="Detailed Description" required></textarea>
                    </div>

                    <!-- Event Category -->
                    <div class="form-group">
                        <label for="event-category">Event Category</label>
                        <select id="event-category" name="event-category" required>
                            <option value="">Select Category</option>
                            <option value="1">Arts & Entertainment</option>
                            <option value="2">Business & Networking</option>
                            <option value="3">Workshop</option>
                            <option value="4">Fashion & Beauty</option>
                            <option value="5">Music & Concerts</option>
                            <option value="6">Sports & Fitness</option>
                            <option value="7">Health & Wellness</option>
                        </select>
                    </div>

                    <!-- Event Date and Time -->
                    <div class="form-group">
                        <label for="event-date">Event Date</label>
                        <input type="date" id="event-date" name="event-date" required>
                    </div>
                    <div class="form-group">
                        <label for="event-time">Event Time</label>
                        <input type="time" id="event-time" name="event-time" required>
                    </div>

                    <!-- Event Location -->
                    <div class="form-group">
                        <label for="event-location">Event Location</label>
                        <input type="search" id="event-location" name="event-location" placeholder="Search Location" required>
                    </div>

                    <!-- Organizer Details -->
                    <div class="form-group">
                        <label for="organizer-name">Organizer Name</label>
                        <input type="text" id="organizer-name" name="organizer-name" placeholder="Organizer Name" required>
                    </div>
                    <div class="form-group">
                        <label for="organizer-email">Organizer Email</label>
                        <input type="email" id="organizer-email" name="organizer-email" placeholder="Organizer Email" required>
                    </div>
                    <div class="form-group">
                        <label for="organizer-phone">Organizer Phone</label>
                        <input type="tel" id="organizer-phone" name="organizer-phone" placeholder="Organizer Phone" required>
                    </div>

                    <!-- Ticket Price -->
                    <div class="form-group">
                        <label for="ticket-price">Ticket Price</label>
                        <input type="number" id="ticket-price" name="ticket-price" placeholder="Ticket Price" required>
                    </div>

                    <!-- Number of Tickets -->
                    <div class="form-group">
                        <label for="number-tickets">Number of Tickets</label>
                        <input type="number" id="number-tickets" name="number-tickets" placeholder="Number of Tickets" required>
                    </div>

                    <!-- Event Image -->
                    <div class="form-group">
                        <label for="event_image">Event Image</label>
                        <input type="file" id="event_image" name="event_image" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit">Publish Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Waves Container-->
    <div class="footer">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
            <defs>
                <path
                  id="gentle-wave"
                  d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"
                />
            </defs>
            <g class="parallax">
                <use
                  xlink:href="#gentle-wave"
                  x="48"
                  y="0"
                  fill="rgb(111, 197, 182)"
                />
                <use
                  xlink:href="#gentle-wave"
                  x="48"
                  y="3"
                  fill="rgb(61, 176, 157)"
                />
                <use
                  xlink:href="#gentle-wave"
                  x="48"
                  y="5"
                  fill="rgba(255,255,255,0.3)"
                />
                <use xlink:href="#gentle-wave" x="48" y="7" fill="rgb(27, 161, 139)" />
            </g>
        </svg>
    </div>
    <!--Waves end-->

    <!--Footer starts-->
    <div class="contentss flex">
        <img src="assets/logo 2.png" style=" max-width: 140px; max-height: fit-content; margin-top: 15px; margin-left: 1px;">
        <div class="footerNav">
            <ul>
                <li><a href="http://localhost/CeylonHappens/index.php">Home</a></li>
                <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
                <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
                <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
                <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
            </ul>
        </div>
        <div class="socialIcons">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-instagram"></i></a>
            <a href=""><i class="fa-brands fa-twitter"></i></a>
            <a href=""><i class="fa-brands fa-google-plus"></i></a>
            <a href=""><i class="fa-brands fa-youtube"></i></a>
        </div>
        <p style="padding-bottom:20px; padding-top:15px; color:white;">Copyright &copy;2024 Designed by Theekshana Nadun</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelector('#event-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);

            try {
                const response = await fetch('process_event.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Try Again'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    confirmButtonText: 'Try Again'
                });
            }
        });
    </script>
</body>

</html>
