<?php
function PokazKontakt()
{
    echo '
        <form method="post" action="">

            <label for="email">Email:</label>
            <input type="email" name="email" required /><br />

            <label for="subject">Subject:</label>
            <input type="text" name="subject" required /><br />

            <label for="message">Message:</label>
            <textarea name="message" rows="4" required></textarea><br />

            <input type="submit" name="submit_contact" value="Send Message" />
        </form>
    ';
}

function PrzypomnijHaslo()
{
    if (isset($_POST['submit_password_reminder'])) {
    }

    // Add the rest of your code for password reminder form, if any
}

function WyslijMailKontakt($odbiorca)
{
    if(empty($_POST['subject']) || empty($_POST['message'] || empty($_POST['email']))){
        echo '[nie wypelniles pola]';
        PokazKontakt();
    }
    else{
        $mail['subject'] = $_POST['subject'];
        $mail['body'] = $_POST['message'];
        $mail['sender'] = $_POST['email'];
        $mail['recipient'] = $odbiorca;
        $header = "From: Formularz kontaktowy <". $mail['sender'].">\n";
        $header .= "MIME-Version: 1.0\ncontent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding:";
        $header .= "x-Sender: <". $mail['sender'].">\n";
        $header .= "X-Mailer: PRapwww mail 1.2\n";
        $header .= "x-Priority: 3\n";
        $header .= "Return-Path: <". $mail['sender'].">\n";
        mail($mail["reciptient"], $mail['subject'], $mail["body"], $header);
        echo '[wiadomosc_wyslana]';
    }
}

function generateRandomPassword($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    $charLength = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[mt_rand(0, $charLength)];
    }

    return $password;
}
PokazKontakt();