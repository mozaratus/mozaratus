<?php

session_start();

header('Access-Control-Allow-Origin: *');

$dsn = 'mysql:dbname=freelancetoulous;host=freelancetoulous.sql-pro.online.net';
$user = 'freelancetoulous';
$password = '_Arctique67';

$pdo = new PDO($dsn, $user, $password);

$ff = fopen("duJour.txt", "w");

fwrite($ff, "POST ? : \n");
foreach ($_POST as $key => $value) {
    fwrite($ff, "Post : " . $key . " -> " . $value . "\n");
}

$tab = array(
    array("qui" => "Personne ?",
        "error" => "0",
        "msg" => "Du jour "),
);
if (isset($_POST['action']) && $_POST["action"] === 'duJourInit') {

    $date = date('Y-m-d');

    // Ok pour aujourd'hui ?
    $sql = "SELECT id, `couleur`, `forme`, `nombre` FROM `dujour` WHERE date='" . $date . "';";
    $dblChk = $pdo->prepare($sql);
    $dblChk->execute();

    fwrite($ff, 'nb Lines du jour : ' . $dblChk->rowCount() . "\n");

    if ($dblChk->rowCount()) {
        $rowset = $dblChk->fetchAll(PDO::FETCH_NUM);
        if ($rowset) {
            foreach ($rowset as $row) {
                $couleur = $row[1];
                $forme = $row[2];
                $nombre = $row[3];
            }
        }
        fwrite($ff, "couleur " . $couleur . " forme " . $forme . " nombre " . $nombre . "\n");

        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "couleur" => $couleur,
                "forme" => $forme,
                "nombre" => $nombre,
                "msg" => "Infos déjà en base"),
        );
    } else {

        $couleurs = ["rouge", "vert", "bleu", "orange", "violet", "jaune", "rose"];
        $formes = ["carré", "cercle", "cube", "boule", "étoile", "triangle", "rectangle"];
        $nombre = rand(0, 100);

        $c = rand(0, 7);
        $f = rand(0, 7);

        $couleur = $couleurs[$c];
        $forme = $formes[$f];

        fwrite($ff, "c: " . $c . ", f: " . $f . "\n");
        $sql = "INSERT INTO `dujour`( `date`, `couleur`, `forme`, `nombre`) VALUES ('" . $date . "', '" . $couleur . "', '" . $forme . "', '" . $nombre . "')";

        fwrite($ff, $sql . "\n");

        $dblChk = $pdo->prepare($sql);
        $dblChk->execute();
        $tab = array(
            array("qui" => $_POST["action"],
                "error" => "0",
                "couleur" => $couleur,
                "forme" => $forme,
                "nombre" => $nombre,

                "msg" => "Création des infos en base"),
        );

    }

}

fclose($ff);
echo json_encode($tab);
