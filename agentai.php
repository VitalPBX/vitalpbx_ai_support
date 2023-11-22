<?php

// Start or resume a session.
session_start();

// Check if there is a user_id stored in the session.
if (isset($_SESSION["user_id"])) {
    
    // Include the database connection file and get the mysqli object.
    $mysqli = require __DIR__ . "/database.php";
    
    // Prepare an SQL query to select the user by their ID stored in the session.
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
            
    // Execute the query and get the result.
    $result = $mysqli->query($sql);
    
    // Fetch the user data from the result.
    $user = $result->fetch_assoc();
}

// Checks if the $user variable is not set or empty
if (!isset($user) || empty($user)) {
    // Redirect the user to the login page
    header('Location: login.php');
    exit; // It is important to call exit() after header()
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>VitalPBX Wiki-AI</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <!-- Main chat interface container -->
    <div class="chat-container">

        <div class="chat-header">
            <div class="header-left">
                <a href="index.php">Home</a> <!-- Enlace a la página principal -->
            </div>
            <div class="header-title">VitalPBX Agent AI</div>
            <div class="header-right">
               <a href="logout.php">LogOut</a> <!-- Log out -->
            </div>
        </div>

        <!-- Container where all chat messages will appear -->
        <div class="chat-box clearfix" id="chatContent">
            <!-- Messages will appear here -->
        </div>

        <!-- User input area -->
        <input type="text" id="messageInput" class="form-control" placeholder="Type your message...">

        <!-- Chat footer displaying the title 'Chatbot' -->
        <div class="chat-footer">
            VitalPBX Agent AI (Powered by ChatGPT) can make mistakes. Consider checking important information.
        </div>

    </div>

    <script>

        // This code provides an interactive chat interface where users can send messages to a chatbot. 
        // The frontend communicates with a backend through a WebSocket connection. 
        // When the user sends a message, a temporary reply is displayed until the actual response from the backend is received. 
        // The chat messages from the backend are prefixed with "ASSISTANCE" and the current date and time.

        // Constants
        const RECONNECT_DELAY = 5000; // 5 seconds delay for reconnection attempts

        // Variable to hold the WebSocket connection
        let ws;
    
        // Function to establish a WebSocket connection
        function connect() {
            ws = new WebSocket(`wss://${location.hostname}:3002`);

            ws.onopen = function() {
                console.log("WebSocket connection opened");
            };

            ws.onclose = function(event) {
                // Check if the connection was closed cleanly
                if(event.wasClean) {
                    console.log("Closed cleanly, code=", event.code, ", reason=", event.reason);
                } else {
                    console.log("Connection died");
                }
                setTimeout(connect, RECONNECT_DELAY);  // Reconnect after a delay
            };

            ws.onerror = function(error) {
                console.error("WebSocket Error:", error);
            };

            // Function to handle incoming messages
            ws.onmessage = handleMessage;
        }

        // Helper function to get the current date and time
        function getCurrentDateTime() {
            const now = new Date();
            return now.toLocaleString();  // Example format: "10/20/2023, 10:12:05 AM"
        }

        // Function to process and display incoming messages
        function handleMessage(event) {
            $('.temp-reply').remove(); // Remove temporary replies
            const data = JSON.parse(event.data); // Parse incoming data
 
            // Prefix the message with "ASSISTANCE" and the current date and time
            // const prefix = `<b>ASSISTANCE</b> - ${getCurrentDateTime()}<br>`;
            const prefix = `<b>Assistant</b><br>`;

            formattedAnswer = prefix + data.answer; 

            var answerElement = $('<div class="bot-message"></div>');
            $('#chatContent').append(answerElement);

            // If the data status is OK, display the formatted answer, otherwise show an error
            if (data.status == 'OK') {
                typeWriter(formattedAnswer, answerElement[0]);
            } else {
                typeWriter("Error retrieving answer.", answerElement[0]);
            }

            autoScroll();  // Ensure the chatbox view is scrolled to the latest message
        }

        // Function to automatically scroll the chatbox to the latest message
        function autoScroll() {
            var chatBox = $(".chat-box");
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }

        // Function to display text in a "type writer" animation
        function typeWriter(text, element, delay = 10) {
            let runningText = '';
            let index = 0;

            function typeChar() {
                if (index < text.length) {
                    let char = text.charAt(index);
                    runningText += char;
                    element.innerHTML = '';
                    element.insertAdjacentHTML('beforeend', runningText);
                    index++;
                    setTimeout(typeChar, delay);
                    autoScroll();
                }
            }
            typeChar();
        }

        $(document).ready(function() {
            // Establish the WebSocket connection upon page load
            connect();

            // Event listener for the send button
            $('#sendBtn').click(sendMessage);

            // Event listener to send the message when the 'Enter' key is pressed
            $('#messageInput').keydown(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            // Function to handle sending messages
            function sendMessage() {
                var message = $('#messageInput').val();
                if (!message) return;

                // Display the user's message in the chatbox
                const prefix = `<b>You</b><br>`;
                formattedQuestion = prefix + message; 
                $('#chatContent').append('<div class="user-message">' + formattedQuestion + '</div>');
                $('#messageInput').val(''); // Clear the input box

                // Display a temporary reply while waiting for a response
                var tempReply = '<div class="bot-message temp-reply">Please wait...</div>';
                $('#chatContent').append(tempReply);

                autoScroll();  // Ensure the chatbox view is scrolled to the latest message

                // Send the user's message over the WebSocket connection
                ws.send(JSON.stringify({ message: message }));
            }

        });
    </script>
</body>
</html>
