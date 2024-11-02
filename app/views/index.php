<?php
require_once __DIR__ . "/../models/Country.php";
require_once __DIR__ . "/../controllers/UserController.php";
require_once __DIR__ . "/../controllers/ContactController.php";
require_once __DIR__ . "/../controllers/MessageController.php";

if (!isset($_SESSION['UserData'])) {
    header("Location: auth/sign-in.php");
    exit;
}

$userData = $_SESSION['UserData'];
$loggedInUserID = $userData['UserID'];

$allCountry = new Country();
$countries = $allCountry->getAllCountries(); // Fetch all countries
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Swipe – The Simplest Chat Platform</title>
    <meta name="description" content="#">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="dist/css/lib/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="dist/css/swipe.min.css" type="text/css" rel="stylesheet">
    <link href="dist/img/favicon.png" type="image/png" rel="icon">
</head>

<body>
    <main>
        <div class="layout">
            <!-- Navigation -->
            <div class="navigation">
                <div class="container">
                    <div class="inside">
                        <div class="nav nav-tab menu">
                            <button class="btn">
                                <img class="avatar-xl" src="dist/img/avatars/avatar-male-1.jpg" alt="avatar">
                            </button>
                            <a href="#members" data-toggle="tab"><i class="material-icons">account_circle</i></a>
                            <a href="#discussions" data-toggle="tab" class="active"><i
                                    class="material-icons">chat_bubble_outline</i></a>
                            <a href="#settings" data-toggle="tab"><i class="material-icons">settings</i></a>
                            <button class="btn power" onclick="logout();"><i
                                    class="material-icons">power_settings_new</i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="container">
                    <div class="col-md-12">
                        <div class="tab-content">
                            <?php 
                                include_once __DIR__ . "/chatPages/Contacts.php";
                                include_once __DIR__ . "/chatPages/Discussions.php";
                                include_once __DIR__ . "/chatPages/Settings.php";
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Friends -->
            <?php include_once  __DIR__ . "/chatPages/AddFriends.php"; ?>

            <!-- Start New Chat -->
            <?php include_once  __DIR__ . "/chatPages/Chat.php"; ?>
        </div>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="dist/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="dist/js/swipe.min.js"></script>

    <script>
   function scrollToBottom(el) {
    el.animate({ scrollTop: el[0].scrollHeight }, "fast");
}


    function logout() {
        window.location.href = 'auth/sign-in.php';
    }

    $(document).on('click', '.list-chat-item', function(e) {
    e.preventDefault();
    
    const userID = $(this).data('userid'); // Adjust to get the correct user ID
    const messagesSectionID = $(this).data('sessionid'); // Adjust as needed
    const contactID = $(this).data('contactid'); // Get the contact ID from the clicked item

    // Ensure contactID is defined
    if (!contactID) {
        console.error("Contact ID is not defined.");
        return; // Stop if contactID is not defined
    }

    $.ajax({
        url: "/chatApp/app/views/chatPages/fetchChat.php", // Adjust this path as needed
        method: "POST",
        data: {
            MessagesSectionID: messagesSectionID,
            ContactID: contactID
        },
        success: function(data) {
            // Handle success
            $('#chatContent').html(data); // Assuming there's an element to display the chat
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
        }
    });
});

$(document).on('submit', '#sendMessageForm', function(e) {
    e.preventDefault();

    const contactID = $('input[name="contactID"]').val();
    const messageContent = $('textarea[name="message"]').val().trim();

    if (messageContent) {
        $.ajax({
            url: "sendMessage.php",
            type: "POST",
            dataType: "json",
            data: {
                contactID: contactID,
                message: messageContent
            },
            success: function(response) {
                if (response.success) {
                    // Append the new message to the chat window
                    $('#content .container .col-md-12').append(`
                        <div class="message me">
                            <div class="text-main">
                                <div class="text-group me">
                                    <div class="text me">
                                        <p class="message-content">${response.message}</p>
                                        <span class="message-datetime">${response.timestamp}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                    // Clear the message input
                    $('textarea[name="message"]').val('');

                    // Scroll to bottom to show the latest message
                    scrollToBottom($('#content'));
                } else if (response.error) {
                    console.error("Error sending message: " + response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    } else {
        alert("Message content cannot be empty.");
    }
});

    </script>
</body>

</html>