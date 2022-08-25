<?php

// die();
// echo phpinfo();


header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set("SMTP","smtp.gmail.com");
// ini_set("sendmail_from","<email-address>@gmail.com>");

// ini_set("SMTP","ssl://smtp.gmail.com");
ini_set("smtp_port","25");

ini_set('sendmail_path', 'D:\\wamp64\\sendmail\\sendmail.exe -t -i -f mozaratus@gmail.com');


// $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
// "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//---------------------------------
$ff = fopen("post_ft.txt", "w");

// fwrite($ff, "URL : \n" . $actual_link);

fwrite($ff, "\n_POST  : \n");
foreach ($_POST as $key => $value) {
    fwrite($ff, "Post : " . $key . " -> " . $value . "\n");
}

//--------------------------------------------
$tab = [];

if (isset($_POST['action']) && $_POST["action"] === 'sendMail') {

    $headers = 'From: mozaratus@gmail.com' . "\r\n" .
    'Reply-To: mozaratus@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion(). "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    // $headers .= "CC: susan@example.com\r\n";

    $message = "Bonjour, <br>" . $_POST['link'];
    $message .= '<p><strong>This is strong text</strong> while this is not.</p>';

    $to = "mozaratus@gmail.com"; //$_POST['email'];

    $subject = 'TLPT : Création nouveau mot de passe';

    if (mail($to,$subject, $message, $headers)) {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "msg" => "Regardez dans votre boîte mail (vérifiez dans les spams), pour finaliser l'inscription."),
        );

    } else {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "1",
                "msg" => "Problème envoie Mail."),
        );
    }
}
fclose($ff);

echo json_encode($tab);
