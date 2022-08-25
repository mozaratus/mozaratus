<?php

// ALTER TABLE lesip AUTO_INCREMENT = 1

session_start();

header('Access-Control-Allow-Origin: *');


$dsn = 'mysql:dbname=freelancetoulous;host=freelancetoulous.sql-pro.online.net';
$user = 'freelancetoulous';
$password = '_Arctique67';


// $dsn = 'mysql:dbname=telepathons;host=localhost';
// $user = 'root';
// $password = '';

$pdo = new PDO($dsn, $user, $password);

$ff = fopen("poste.txt", "w");

fwrite($ff, "POST ? : \n");
foreach ($_POST as $key => $value) {
    fwrite($ff, "Post : " . $key . " -> " . $value . "\n");
}

if (isset($_SESSION['idUser'])) {
    fwrite($ff, "Session ID : " . $_SESSION['idUser'] . "\n");
}

$tab = array(
    array("qui" => $_POST["action"],
        "error" => "0",
        "msg" => "Pas de récupération dans POST"),
);

// --------------------------------------------------------------------------
//          Inscription : INSERT USER // IP
// --------------------------------------------------------------------------
if (isset($_POST['action']) && $_POST["action"] === 'miniForm') {

    $date = date('Y-m-d H:i:s');

    // doublons ?
    $sql = "SELECT id, nom, `email` FROM `user` WHERE email='" . $_POST["email"] . "';";
    $dblChk = $pdo->prepare($sql);
    $dblChk->execute();

    fwrite($ff, 'nb Lines Dbl : ' . $dblChk->rowCount() . "\n");

    if ($dblChk->rowCount()) {
        // doublons !
        // Utilisateur existe déjà en base.
        $nom = "";
        $rowset = $dblChk->fetchAll(PDO::FETCH_NUM);
        if ($rowset) {
            foreach ($rowset as $row) {
                $nom = $row[1];
                $_SESSION['idUser'] = $row[0];
            }
        }

        $tab = array(
            array("qui" => $_POST["action"],
                "error" => 1,
                "msg" => "Doublon de " . $_POST["email"]),
        );
        $_SESSION['idUser'] = "";
    } else {
        //--------------------------------
        // INSERT USER
        //--------------------------------

        $pdo->beginTransaction();

        $sql = 'INSERT INTO user
            (`nom`, `email`, `pw`, date)
            VALUES (?, ?, ?, ?)';

        $sth = $pdo->prepare($sql);
        $sth->execute(array(
            $_POST["nom"],
            $_POST["email"],
            md5($_POST["pw"]),
            $_POST['date'],
        ));

        $_SESSION['idUser'] = $pdo->lastInsertId();

        $pdo->commit();

        fwrite($ff, "Session ID  insert: " . $_SESSION['idUser'] . "\n");

        $tab = array(
            array("qui" => $_POST["action"],
                "error" => 0,
                "msg" => "Insciption de " . $_POST["nom"],
                "nom" => $_POST["nom"]),
        );

        // --------------------------------------------------   IP
        //
        //

        try {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } catch (\Exception $e) {
            $ip = "/";
            fwrite($ff, $e . getMessage());
        }

        $pdo->beginTransaction();
        $sql = 'INSERT INTO `lesip`(`idUSer`, `ip`, `ip2`)
            VALUES (?, ?, ?)';
        $sth = $pdo->prepare($sql);
        $sth->execute(array(
            $_SESSION['idUser'],
            $ip,
            $_SERVER['REMOTE_ADDR'],
        ));

        $pdo->commit();

    }

    // --------------------------------------------------------------------------
    //          LOGIN
    // --------------------------------------------------------------------------
}
// --------------------------------------------------------------------------
//          Login
// --------------------------------------------------------------------------
elseif (isset($_POST['action']) && $_POST["action"] === 'login') {

    fwrite($ff, "Login \n");

    $sql = "SELECT `id`,`nom`, `email`, `pw` FROM `user` WHERE pw='" . md5($_POST["pw"]) . "' AND email='" . $_POST["email"] . "';";

    fwrite($ff, "SQL = " . $sql . " \n");

    $stmt = $pdo->prepare($sql);

    fwrite($ff, "Pw  : " . $_POST["pw"] . "\n" . md5($_POST["pw"]) . "\n");

    // $stmt->execute([password_hash($_POST["pw"], PASSWORD_DEFAULT), $_POST["email"]]);
    $stmt->execute(); //['$2y$10$Cs5c7OHxeRpax1g5wJISE.7IzU7xypq2SBkANxblkPqWgI7bV9aLa',"titi@toto.fr"]);

    $tab = [];
    if ($stmt->rowCount()) {
        fwrite($ff, 'nb Lines : ' . $stmt->rowCount() . "\n");

        $tab0 = [];
        do {
            $rowset = $stmt->fetchAll(PDO::FETCH_NUM);
            if ($rowset) {
                foreach ($rowset as $row) {

                    fwrite($ff, "id : " . $row[0] . "\n");
                    fwrite($ff, "nom : " . $row[1] . "\n");
                    fwrite($ff, "email : " . $row[2] . "\n");

                    $tab0['id'] = $row[0];
                    $tab0['nom'] = $row[1];
                    $tab0['email'] = $row[2];
                }
            }
        } while ($stmt->nextRowset());

        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "msg" => "Login Ok",
                "nom" => $tab0['nom'],
            ),
        );

    } else {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "1",
                "msg" => "Erreur d'e-mail ou de mot de passe."),
        );

    }

    // --------------------------------------------------------------------------
    //          Exo Couleur
    // --------------------------------------------------------------------------
}
// --------------------------------------------------------------------------
//          SET COLOR // sendColor
// --------------------------------------------------------------------------
//      Générique pour les page exos
// --------------------------------------------------------------------------
elseif (isset($_POST['action']) && $_POST["action"] === 'sendDataPropal') {

    $tab = [];

    // DOUBLONS ??
    $date = date('Y-m-d');

    // SELECT `id`, `date`, `userId`, `couleur`, `rcouleur`, `forme`, `rforme`, `nombre`, `rnombre` FROM `resultat`

    $sql = "SELECT `id` FROM `resultat` WHERE `date`='" . $date . "' AND userId='" . $_SESSION["idUser"] . "';";

    fwrite($ff, "SQL sendDataPropal = " . $sql . " \n");

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $pdo->beginTransaction();
    // user existe dans la table résultat
    //
    //----------------------------------------------- UPDATE data Resultat
    //
    if ($stmt->rowCount()) {

        $id = -1;
        $rowset = $stmt->fetchAll(PDO::FETCH_NUM);
        if ($rowset) {
            foreach ($rowset as $row) {
                $id = $row[0];
            }
        }

        if ($_POST['champs'] !== "nombre") {
            $sql = "UPDATE `resultat` SET `" . $_POST['champs'] . "`=\"" . $_POST['data'] . "\" WHERE id=" . $id . ";";
        } else {
            $sql = "UPDATE `resultat` SET `" . $_POST['champs'] . "`=" . $_POST['data'] . " WHERE id=" . $id . ";";
        }

        fwrite($ff, "UPDATE RESULTATS\nSQL = " . $sql . " \n");

        $stmt = $pdo->prepare($sql);

        $tab = [];

        if (!$stmt->execute()) {
            $tab = array(
                array("qui" => $_POST["action"],
                    "error" => "1",
                    "msg" => "ERROR updateResultat : " . $stmt->errorInfo()[2]),
            );
        } else {
            $tab = array(
                array("qui" => $_POST["action"],
                    "error" => "0",
                    "msg" => "OK update table resultat"),
            );
        }

    } else {
        // --------------------------------------------  INSERT
        $sql = 'INSERT INTO resultat
                (`date`, `userId`, `' . $_POST['champs'] . '`)
                VALUES (?, ?, ?)';

        $sth = $pdo->prepare($sql);
        $sth->execute(array(
            $date,
            $_SESSION['idUser'],
            $_POST['data'],
        ));

        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "msg" => "INSERT Resultat " . $_POST['champs'] . " : " . $_POST["data"],
            ),
        );
    }

    /* Valider les modifications */
    $pdo->commit();

}
// --------------------------------------------------------------------------
//          Get USER
// --------------------------------------------------------------------------

elseif (isset($_POST['action']) && $_POST["action"] === 'getUser') {
    $sql = "SELECT `id`,`nom`, `email`, `pw` FROM `user`;";

    fwrite($ff, "Get USER\nSQL = " . $sql . " \n");

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $tab = [];
    if ($stmt->rowCount()) {

        do {
            $rowset = $stmt->fetchAll(PDO::FETCH_NUM);
            if ($rowset) {
                foreach ($rowset as $row) {

                    // fwrite($ff,"id : ".$row[0]. "\n");
                    // fwrite($ff,"nom : ".$row[1]. "\n");
                    // fwrite($ff,"email : ".$row[2]. "\n");

                    $tab0['id'] = $row[0];
                    $tab0['nom'] = $row[1];
                    $tab0['email'] = $row[2];

                    $tab[] = $tab0;
                }
            }
        } while ($stmt->nextRowset());

        // $tab = array(
        //     array("qui" => $_POST["action"],
        //         "error" => "0",
        //         "msg" => "Récup user"),
        // );

    } else {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "1",
                "msg" => "Problème récup"),
        );
    }
}

// --------------------------------------------------------------------------
//          Get RESULTATS
// --------------------------------------------------------------------------
elseif (isset($_POST['action']) && $_POST["action"] === 'getResultats') {

    $sql = "SELECT `id`, `date`, `userId`, `couleur`, `rcouleur`, `forme`, `rforme`, `nombre`, `rnombre`  FROM `resultat` WHERE userId=" . $_POST['idUser'] . ";";

    fwrite($ff, "Get RESULTATS\nSQL = " . $sql . " \n");

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $tab = [];
    if ($stmt->rowCount()) {

        do {
            $rowset = $stmt->fetchAll(PDO::FETCH_NUM);
            if ($rowset) {
                foreach ($rowset as $row) {

                    $tab0['id'] = $row[0];
                    $tab0['date'] = $row[1];
                    $tab0['userId'] = $row[2];
                    $tab0['couleur'] = $row[3];
                    $tab0['rcouleur'] = !!$row[4];
                    $tab0['forme'] = $row[5];
                    $tab0['rforme'] = !!$row[6];
                    $tab0['nombre'] = $row[7];
                    $tab0['rnombre'] = !!$row[8];

                    $tab[] = $tab0;
                }
            }
        } while ($stmt->nextRowset());

    } else {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "msg" => "Problème récup Résultats"),
        );
    }
}

// --------------------------------------------------------------------------
//        UPDATE RESULTATS
// --------------------------------------------------------------------------
elseif (isset($_POST['action']) && $_POST["action"] === 'updateBDDResultat') {

    $sql = "UPDATE  `resultat` SET `rcouleur`=" . $_POST['rc'] . ", `rforme`=" . $_POST['rf'] . ", `rnombre`=" . $_POST['rn'] . " WHERE id=" . $_POST['idResultat'] . ";";

    fwrite($ff, "UPDATE RESULTATS\nSQL = " . $sql . " \n");

    $stmt = $pdo->prepare($sql);

    $tab = [];

    if (!$stmt->execute()) {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "1",
                "msg" => "ERROR updateBDDResultat : " . $stmt->errorInfo()[2]),
        );
    } else {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "msg" => "OK updateBDDResultat Résultats"),
        );
    }
}

// --------------------------------------------------------------------------
//        New PassWord (demande)
// --------------------------------------------------------------------------
elseif (isset($_POST['action']) && $_POST["action"] === 'newPw') {

    // https://analyse-innovation-solution.fr/publication/fr/php/comment-envoyer-un-mail-en-php
    // https://www.tutsmake.com/send-reset-password-link-email-php/

    $emailId = $_POST['email'];
    $token = md5($emailId) . rand(10, 9999);

    $sql = "SELECT `id`,`nom`, `email` FROM `user` WHERE email='" . $emailId . "';";

    fwrite($ff, "newPw\nSQL = " . $sql . " \n");

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $tab = [];
    if ($stmt->rowCount()) {

        $expFormat = mktime(
            date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y")
        );
        $expDate = date("Y-m-d H:i:s", $expFormat);

        // !!!
        $sql = "UPDATE users set reset_link_token='" . $token . "', exp_date='" . $expDate . "' WHERE email='" . $emailId . "'";

        fwrite($ff, "UPDATE Demande PW\nSQL = " . $sql . " \n");

        $stmt = $pdo->prepare($sql);
        
        $link = "<a href='tlpt.freelancetoutlouse/php/reset-password.php?key=" . $emailId . "&token=" . $token . "'>Click To Reset password</a>";
        
        //Et envoi du mail...
        // $a = mail($destinataire, $objet, $message, $headers);
        // $a = mail("mozaratus@gmail.com", "coucou", $message, $headers);

   //     if ($a) {
            $tab = array(
                array("qui" => $_POST["action"],
                    "error" => "0",
                    "link" => $link,
                    "msg" => "Regardez dans votre boîte mail (vérifiez dans les spams), pour finaliser l'inscription."),
            );

        // } else {
        //     $tab = array(
        //         array("qui" => $_POST["action"],
        //             "error" => "1",
        //             "msg" => "Problème envoie Mail."),
        //     );
        // }

    } else {
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "1",
                "msg" => "Pbm Mail : Invalid Email Address "),
        );
    }

}

fclose($ff);

echo json_encode($tab);
