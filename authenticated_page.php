<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Display the authenticated content
echo "<h2>Welcome, {$_SESSION['username']}!</h2>";
echo "<p>Only logged-in users can access this.</p>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authenticated Page</title>
    <style>
        /* confetti styles */
        @keyframes confetti {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100vh) rotate(720deg);
            }
        }

        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f0f;
            animation: confetti 3s infinite;
            transform-origin: top left;
            opacity: 0.8;
            border-radius: 50%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to create confetti
            function createConfetti() {
                // Create a confetti element
                var confetti = $("<div>").addClass("confetti");

                // Set random position on the screen
                var xPos = Math.random() * $(window).width();
                var yPos = $(window).height() + 50;

                confetti.css({
                    left: xPos,
                    top: yPos
                });

                // Append the confetti element to the container
                $(".confetti-container").append(confetti);

                // Remove the confetti element after animation completes
                setTimeout(function() {
                    confetti.remove();
                }, 3000);
            }

            // Function to check if authenticated content is visible
            function checkContentVisibility() {
                var contentElement = $('.authenticated-content');

                // Check if the content element is visible in the viewport
                var visible = contentElement.is(":visible");

                if (visible) {
                    // Trigger confetti creation at intervals
                    setInterval(function() {
                        createConfetti();
                    }, 200);

                    // Stop checking for visibility
                    clearInterval(visibilityInterval);
                }
            }

            // Load authenticated content via AJAX
            $.ajax({
                url: 'load_authenticated_content.php',
                type: 'GET',
                success: function(response) {
                    $('.authenticated-content').html(response);

                    // Check content visibility after a delay
                    setTimeout(function() {
                        checkContentVisibility();
                    }, 100);
                },
                error: function() {
                    alert('Error loading authenticated content.');
                }
            });
        });
    </script>
</head>
<body>
    <div class="confetti-container"></div>
    <div class="authenticated-content" style="display: none;">
        <h2>Loading...</h2>
    </div>
</body>
</html>
