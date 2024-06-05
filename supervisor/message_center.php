<?php
session_start();
require '../conn/db_connection.php';

// Check if the user is logged in and is a supervisor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supervisor') {
    header("Location: ../login.php");
    exit();
}

// Fetch interns assigned to the logged-in supervisor
$interns_query = $conn->prepare("
    SELECT u.user_id, u.username 
    FROM users u 
    JOIN assignments a ON u.user_id = a.intern_id 
    WHERE a.supervisor_id = ?
");
$interns_query->bind_param("i", $_SESSION['user_id']);
$interns_query->execute();
$interns_result = $interns_query->get_result();
$interns = [];
while ($row = $interns_result->fetch_assoc()) {
    $interns[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Message Center</title>
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
        <a href="supervisor_dashboard.php" style="color: #fff; text-decoration:none;">
            <div class="logo">Supervisor Dashboard</div>
        </a>
        <div class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <div class="burger" id="burger">&#9776;</div>
    </header>
    <nav class="left-navbar" id="left-navbar">
        <a href="supervisor_dashboard.php">Dashboard</a>
        <a href="message_center.php">Message Center</a>
        <a href="../profile.php">Profile Management</a>
        <a href="review_attendance.php">Approve Attendance</a>
        <a href="submitted_logbooks.php">Interns Logoboks</a>
        <a href="../logout.php">Logout</a>
    </nav>
    <main>
        <div class="container">
            <div class="user-list">
                <h3>Interns</h3>
                <ul>
                    <?php foreach ($interns as $intern) : ?>
                        <li><a href="#" onclick="selectUser(<?php echo $intern['user_id']; ?>)"><?php echo htmlspecialchars($intern['username']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="message-box">
                <h3>Messages</h3>
                <div id="messages"></div>
                <form id="messageForm">
                    <input type="hidden" id="receiver_id" name="receiver_id" />
                    <textarea id="message" name="message" placeholder="Type your message..." required></textarea>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </main>
    <script>
        let selectedUserId = null;
        const userId = <?php echo $_SESSION['user_id']; ?>;

        function selectUser(userId) {
            selectedUserId = userId;
            document.getElementById('receiver_id').value = userId;
            fetchMessages();
        }

        async function fetchMessages() {
            if (!selectedUserId) return;
            const response = await fetch('fetch_messages.php?other_user_id=' + selectedUserId);
            const messages = await response.json();
            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML = '';
            messages.forEach(msg => {
                const messageElement = document.createElement('div');
                messageElement.classList.add('message');
                if (msg.sender_id == userId) {
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
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        document.getElementById('messageForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch('send_message.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            console.log(result);
            fetchMessages();
            this.reset();
        });

        // Fetch messages periodically
        setInterval(fetchMessages, 5000);
    </script>
    <script src="../script/supervisor_dashboard.js"></script>
</body>

</html>