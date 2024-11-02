<?php
session_start();
require_once __DIR__ . "/../../controllers/UserController.php";
require_once __DIR__ . "/../../controllers/MessageController.php";

// Ensure the user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get parameters
$contactID = isset($_POST['contactID']) ? intval($_POST['contactID']) : null; // Ensure this is included

// Fetch messages and contact information
$contact = UserController::getUserData($contactID);
$messages = MessageController::showChat(MessageController::getLastThreeSessions($_SESSION['UserID'], $contactID)); 
$loggedInUserID = $_SESSION['UserID']; // Define it if not already set
if ($contact && $messages) {
    ?>
    <div class="chat" id="chat1">
        <div class="top">
            <div class="container">
                <div class="col-md-12">
                    <div class="inside">
                        <a href="#">
                            <img class="avatar-md" src="<?= htmlspecialchars($contact['ProfilePictureURL']) ?>" data-toggle="tooltip" title="<?= htmlspecialchars($contact['UserName']) ?>" alt="avatar">
                        </a>
                        <div class="status">
                            <i class="material-icons <?= $contact['IsOnline'] ? 'online' : 'offline' ?>">fiber_manual_record</i>
                        </div>
                        <div class="data">
                            <h5><a href="#"><?= htmlspecialchars($contact['UserName']) ?></a></h5>
                            <span><?= $contact['IsOnline'] ? 'Active now' : 'Last seen recently' ?></span>
                        </div>
                        <div class="dropdown">
                            <button class="btn" data-toggle="dropdown">
                                <i class="material-icons md-30">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item"><i class="material-icons">clear</i> Clear History</button>
                                <button class="dropdown-item"><i class="material-icons">block</i> Block Contact</button>
                                <button class="dropdown-item"><i class="material-icons">delete</i> Delete Contact</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content" id="content">
            <div class="container">
                <div class="col-md-12">
                    <?php if (!empty($messages)): ?>
                        <div class="date"><hr><span>Yesterday</span><hr></div>
                        <?php foreach ($messages as $message): ?>
                            <div class="message <?= $message['SenderID'] == $loggedInUserID ? 'me' : '' ?>">
                                <?php if ($message['SenderID'] != $loggedInUserID): ?>
                                    <img class="avatar-md" src="<?= htmlspecialchars($message['avatar']) ?>" data-toggle="tooltip" title="<?= htmlspecialchars($message['sender_name']) ?>" alt="avatar">
                                <?php endif; ?>
                                <div class="text-main">
                                    <div class="text-group <?= $message['SenderID'] == $loggedInUserID ? 'me' : '' ?>">
                                        <div class="text <?= $message['SenderID'] == $loggedInUserID ? 'me' : '' ?>">
                                            <p class="message-content">
                                                <?= htmlspecialchars($message['MessageContent']) ?>
                                            </p>
                                            <span class="message-datetime"><?= date("d-m-Y, h:i A", strtotime($message['DateTime'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages yet.</p>
                    <?php endif; ?>

                    <div class="date"><hr><span>Today</span><hr></div>
                    <!-- Typing indicator -->
                    <div class="message typing">
                        <img class="avatar-md" src="<?= htmlspecialchars($message['avatar']) ?>" data-toggle="tooltip" title="Typing..." alt="avatar">
                        <div class="text-main">
                            <div class="text-group">
                                <div class="text typing">
                                    <div class="wave">
                                        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="col-md-12">
                <div class="bottom">
                <form class="position-relative w-100" action="/chatApp/app/views/sendMessage.php" method="POST">
                <textarea class="form-control" name="message" placeholder="Start typing for reply..." rows="1" required></textarea>
                        <input type="hidden" name="contactID" value="<?= htmlspecialchars($contactID) ?>">
                        <button type="submit" class="btn send">
                            <i class="material-icons">send</i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "<p>No chat data available.</p>";
}
?>

<style>
/* Chat bubble styling */
.message-content {
    white-space: normal;
    word-wrap: break-word;
    max-width: 250px;
    margin: 0;
}

.text-main {
    display: flex;
    flex-direction: column;
}

.text-group {
    margin-bottom: 5px;
}

.message-datetime {
    font-size: 0.75em;
    color: #aaa;
    display: block;
    margin-top: 3px;
    text-align: right;
}

.message.me .message-datetime {
    text-align: left;
    margin-left: 5px;
}


</style>
