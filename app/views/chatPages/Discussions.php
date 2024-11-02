<div id="discussions" class="tab-pane fade active show">
    <div class="search">
        <form class="form-inline position-relative">
            <input type="search" class="form-control" id="conversations" placeholder="Search for conversations...">
            <button type="button" class="btn btn-link loop"><i class="material-icons">search</i></button>
        </form>
    </div>
    <div class="list-group sort">
        <button class="btn filterDiscussionsBtn active show" data-toggle="list" data-filter="all">All</button>
        <button class="btn filterDiscussionsBtn" data-toggle="list" data-filter="read">Read</button>
        <button class="btn filterDiscussionsBtn" data-toggle="list" data-filter="unread">Unread</button>
    </div>
    <div class="discussions">
        <h1>Discussions</h1>
        <div class="list-group" id="chats" role="tablist">
            <?php if (!empty($UsersContactsData)): ?>
            <?php foreach ($UsersContactsData as $Contact):
                    $contactID = ($Contact['User1ID'] == $_SESSION['UserID']) ? $Contact['User2ID'] : $Contact['User1ID'];
                    $contact = UserController::getUserData($contactID);
                    $discussion = MessageController::displayDiscussions($contact['UserID']);
                    $isOnline = $contact['IsOnline'] ? 'online' : 'offline';
                ?>
            <a href="#" class="filterMembers all <?= ($discussion['new_messages'] ?? 0 > 0) ? 'unread' : 'read' ?> single <?= $isOnline ?>" 
               onclick="loadChat(<?= htmlspecialchars($contact['UserID']) ?>)" data-toggle="list" role="tab">

                <img class="avatar-md" src="<?= htmlspecialchars($contact['ProfilePictureURL']) ?>" data-toggle="tooltip" title="<?= htmlspecialchars($contact['UserName']) ?>" alt="avatar">
                <div class="status">
                    <i class="material-icons <?= $isOnline ?>">fiber_manual_record</i>
                </div>

                <div class="data">
                <span class="timestamp"><?= isset($discussion['last_message_date']) ? date('D, M j', strtotime($discussion['last_message_date'])) : '' ?></span>

                    <h5><?= htmlspecialchars($contact['UserName']) ?></h5>
                    <p class="text-muted">
                        
                    <p><?= htmlspecialchars(mb_strimwidth($discussion['last_message_content'] ?? '', 0, 27, '...')) ?></p>
                    </div>
                <?php if (!empty($discussion['new_messages'])): ?>
                <span class="new bg-yellow"><?= htmlspecialchars($discussion['new_messages']) ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function loadChat(contactID) {
    $.ajax({
        url: '/chatApp/app/views/chatPages/fetchChat.php', // Adjust this path
        method: 'POST',
        data: {
            contactID: contactID
        },
        success: function(response) {
            $('#chatSection').html(response); // Populate chat section
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error loading chat: " + textStatus, errorThrown);
            alert("Failed to load chat. Please try again.");
        }
    });
}

</script>
