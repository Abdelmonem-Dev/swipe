<?php 
include_once  __DIR__ ."/../models/Contact.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class ContactController
{

    public static function getAllContacts()
    {
        return Contact::getAllContacts($_SESSION['UserID']);
    }
    public static function getContactsCount()
    {
        return Contact::getContactsCount($_SESSION['UserID']);
    }
    public static function sendContactRequest($senderID, $recipientID) {
        return Contact::sendContactRequest($senderID, $recipientID);
    }
}

