<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'intern') {
    header("Location: ../login.php");
    exit();
}

// Fetch the supervisor for the logged-in intern
$supervisor_query = $conn->prepare("
    SELECT u.user_id, u.username, u.first_name, last_name 
    FROM users u 
    JOIN assignments a ON u.user_id = a.supervisor_id 
    WHERE a.intern_id = ?
");
$supervisor_query->bind_param("i", $_SESSION['user_id']);
$supervisor_query->execute();
$supervisor_result = $supervisor_query->get_result();
$supervisor = $supervisor_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern Message Center</title>
    <link rel="stylesheet" href="../css/intern_dashboard_styles.css">
    <link rel="stylesheet" href="../css/message_center.css">
    <style>
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 10px;
            max-width: 60%;
            position: relative;
            font-size: 0.9em;
            display: flex;
            min-width: 70px;
        }

        .sent {
            background-color: #d4edda;
            color: #155724;
            text-align: right;
            float: right;
            clear: both;
        }

        .received {
            background-color: #f8d7da;
            color: #721c24;
            text-align: left;
            float: left;
            clear: both;
        }

        .timestamp {
            font-size: 10px;
            color: #999;
            position: absolute;
            bottom: -2.2em;
            right: 10px;
        }

        .received .timestamp {
            left: 10px;
            right: auto;
        }

        #messages {
            border: 1px solid #ccc;
            padding: 10px;
            height: 300px;
            overflow-y: scroll;
        }

        .message-box {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <header>
        <a href="intern_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Intern Dashboard</div>
        </a>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <div class="burger" id="burger">&#9776;</div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <a href="submit_update.php">Submit Update</a>
        <a href="message_center_intern.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="log_attendance.php">Log Attendance</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <main>
        <div class="container">
            <div class="message-box">
                <h3>Messages with Supervisor( <?php echo htmlspecialchars($supervisor['first_name']); ?> <?php echo htmlspecialchars($supervisor['last_name']); ?>)</h3>
                <div id="messages"></div>
                <form id="messageForm">
                    <input type="hidden" id="receiver_id" name="receiver_id" value="<?php echo $supervisor['user_id']; ?>" />
                    <textarea id="message" name="message" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </main>
    <script>
        let selectedUserId = <?php echo $supervisor['user_id']; ?>;

        function fetchMessages() {
            if (!selectedUserId) return;
            fetch('fetch_messages.php?other_user_id=' + selectedUserId)
                .then(response => response.json())
                .then(messages => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = '';
                    messages.forEach(msg => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message');
                        if (msg.sender_id == <?php echo $_SESSION['user_id']; ?>) {
                            messageElement.classList.add('sent');
                        } else {
                            messageElement.classList.add('received');
                        }
                        messageElement.innerText = msg.message;
                        const timestampElement = document.createElement('div');
                        timestampElement.classList.add('timestamp');
                        timestampElement.innerText = new Date(msg.timestamp).toLocaleString();
                        messageElement.appendChild(timestampElement);
                        messagesDiv.appendChild(messageElement);
                    });
                });
        }

        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result);
                    fetchMessages();
                });
            this.reset();
        });

        // Fetch messages every 5 seconds
        setInterval(fetchMessages, 5000);

        // Initial fetch
        fetchMessages();
    </script>
    <script src="../script/intern_dashboard.js"></script>
</body>

</html>