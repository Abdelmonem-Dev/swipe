<?php
// Fetch all contacts
$UsersContactsData = ContactController::getAllContacts();
?>

<div class="tab-pane fade" id="members">
    <div class="search">
        <form class="form-inline position-relative">
            <input type="search" class="form-control" id="people" placeholder="Search for people...">
            <button type="button" class="btn btn-link loop"><i class="material-icons">search</i></button>
        </form>
        <button class="btn create" data-toggle="modal" data-target="#exampleModalCenter"><i class="material-icons">person_add</i></button>
    </div>
    
    <!-- Filter Buttons -->
    <div class="list-group sort">
        <button class="btn filterMembersBtn active show" data-toggle="list" data-filter="all">All</button>
        <button class="btn filterMembersBtn" data-toggle="list" data-filter="online">Online</button>
        <button class="btn filterMembersBtn" data-toggle="list" data-filter="offline">Offline</button>
    </div>
    
    <!-- Contacts List -->
    <div class="contacts">
        <h1>Contacts</h1>
        <div class="list-group" id="contacts" role="tablist">
            <?php foreach ($UsersContactsData as $Contact): 
                            $contactID = ($Contact['User1ID'] == $_SESSION['UserID']) ? $Contact['User2ID'] : $Contact['User1ID'];
                            $contact = UserController::getUserData($contactID);
                            $isOnline = $contact['IsOnline'] ? 'online' : 'offline';
            ?>
                <a href="#" class="filterMembers all <?= $isOnline ?> contact" data-toggle="list">
                    <img class="avatar-md" src="<?= $contact['ProfilePictureURL'] ?>" data-toggle="tooltip" data-placement="top" title="<?= $contact['UserName'] ?>" alt="avatar">
                    <div class="status">
                        <i class="material-icons <?= $isOnline ?>">fiber_manual_record</i>
                    </div>
                    <div class="data">
                        <h5><?= $contact['FirstName'] . " " . $contact['LastName']?></h5>
                        <p><?= $contact['UserName'] ?></p>
                    </div>
                    <div class="person-add">
                        <i class="material-icons">person</i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>


