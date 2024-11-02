<div class="main" id="chatSection">
    <div class="tab-content" id="nav-tabContent">
        <div class="babble tab-pane fade active show" id="list-chat" role="tabpanel">
            <div class="chat" id="chat1">
                <!-- Top Section: Contact Details and Options -->
                <div class="top">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="inside">
                                <a href="#"><img class="avatar-md" src="<?= htmlspecialchars($contact['ProfilePictureURL']) ?>" data-toggle="tooltip" title="<?= htmlspecialchars($contact['UserName']) ?>" alt="avatar"></a>
                                <div class="status"><i class="material-icons <?= $contact['IsOnline'] ? 'online' : 'offline' ?>">fiber_manual_record</i></div>
                                <div class="data">
                                    <h5><a href="#"><?= htmlspecialchars($contact['UserName'] ?? "") ?></a></h5>
                                    <span><?= $contact['IsOnline'] ?? "" ? 'Active now' : 'Last seen recently' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Section: Messages -->
                <div class="content" id="content">
                    <div class="container">
                        <div class="col-md-12">
                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $message): ?>
                                    <div class="message <?= $message['SenderID'] == $loggedInUserID ? 'me' : '' ?>">
                                        <div class="text-main">
                                            <div class="text-group <?= $message['SenderID'] == $loggedInUserID ? 'me' : '' ?>">
                                                <div class="text"><?= htmlspecialchars($message['MessageContent']) ?></div>
                                            </div>
                                            <span><?= htmlspecialchars($message['DateTime']) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No messages yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Message Input Section -->
                <div class="bottom">
                    <form class="position-relative w-100" action="sendMessage.php" method="POST">
                        <textarea class="form-control" name="message" placeholder="Type your message..." rows="1" required></textarea>
                        <input type="hidden" name="contactID" value="<?= htmlspecialchars($contactID) ?>"> <!-- Include contact ID -->
                        <button type="submit" class="btn send"><i class="material-icons">send</i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
