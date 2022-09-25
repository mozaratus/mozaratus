<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="finder.css" />

    <title>Analyse TLPT</title>
</head>
<body>

<?php

include "class.BaseFile.php";

$theArr;


$base = '../src/libExemple/ptitTrait'; //elem_svlt'; //nom du dossier de référence. Le . (point) pour la racine.;
$fichierPhp = "analyseSvelte"; // nom de ce fichier (finder.php)


function analyseSvelte($file)
{
    $fileName = $_GET['name'];
    $lines = file($fileName);
    $lines_arr = [];


    echo "<div class='detail'>";
    echo "<h2>".$fileName."</h2>";

    $theArr = new BaseFile($lines);

    // $theArr->getFonction();
    // $theArr->getImports();
    // $theArr->getConst();
    // $theArr->getVarGlobal();
    
    $theArr->showImports();

    // $theArr->showConst();

    // $theArr->showVarGlobal();

    // $theArr->showFonction();

    $theArr->svelteInHtml();

    echo "</div>";
    //varGlobal($lines_arr);
    $theArr->afficheLigneDeCode($lines_arr);

}




// Démarrage !
echo "<div id='finder'>";

if (isset($_GET['type'])) {

    $type = $_GET['type'];
    // echo scanDossier( 'contenu' );

    $urls = explode('/', $_GET['name']);
    //var_dump($urls);
    $type = strtolower($type);

    switch ($type) {
        case 'dos':
            showDos('dos');
            break;

        case 'png':
        case 'svg':
        case 'jpg':
            showDos();
            //echo "<div class='finder'><h3> ".$_GET['name']."</h3>";
            echo "<a href='" . $_GET['name'] . "' target='_blank'>
                <img src='" . $_GET['name'] . "' width='500' height='auto' alt='' />
            </a>";
            break;

        case 'gif':
            showDos();
            //echo "<div class='finder'><h3> ".$_GET['name']."</h3>";
            echo "<img src='" . $_GET['name'] . "' width='50' height='50' alt='' />";
            break;

        case 'txt':
        case 'sql':
            showDos();
            //echo "<div class='finder '><h3> ".$_GET['name']."</h3>";
            echo "<div class='divText'>";

            $fileName = $_GET['name'];
            $lines = file($fileName);
            echo "<table>";
            foreach ($lines as $line_num => $line) {
                echo "<tr><td class='numLigne'>" . $line_num . "</td><td>" . $line . "</td></tr>";
            }
            echo "</table>";
            echo "</div>";
            echo "</div>";
            break;

        case 'svelte':
            showDos();
            analyseSvelte($_GET['name']);
            echo "</div>";
            break;

        case 'js':
        case 'css':
            showDos();
            //echo "<div class='finder '><h3> ".$_GET['name']."</h3>";

            echo "<div class='divText'>";
            highlight_file($_GET['name']);
            echo "</div>";

            echo "</div>";
            break;

        case 'html':
        case 'php':
            showDos();
            echo "<div class='finder '>";
            echo "<a id='ouvreHTML' href='" . $_GET['name'] . "' target='_blank'>Exécuter le fichier</a><br><br>";

            echo "<div class='divText'>";
            highlight_file($_GET['name']);
            echo "</div>";

            echo "</div>";
            break;

        case 'pdf':
            showDos('pdf');
            echo "<a id='ouvrePdf' href='" . $_GET['name'] . "' target='_blank'>PDF</a>";
            break;

        default:
            showDos();
            echo "<div class='divText'>";
            echo "<p class='ko'>Type inconnu</p>";
            highlight_file($_GET['name']);
            echo "</div>";
            // echo "<div class='finder'><h3> ".$_GET['name']."</h3>";
            break;

    }

} else {
    echo scanDossier($base);
}

echo "</div>";

/**
 * Affiche une colonne (un tableau) contenant les dossier et fichiers du dossier de référence.
 *
 * $dossierRef : le chemin ou le nom du dossier à parcourir
 */
function scanDossier($dossierRef, $surligne = '')
{
    global $fichierPhp;

    $monDossier = opendir($dossierRef) or die('Erreur');

    $tailleTotale = 0;

    // on sélectionne les fichiers et les dossiers
    // de façon à mettre en premier les dossiers.
    $dossiers = [];
    $fichiers = [];
    while ($fileDoss = readdir($monDossier)) {
        if (is_dir($dossierRef . '/' . $fileDoss)) {
            if ($fileDoss != '.' && $fileDoss != '..') {
                $dossiers[] = $fileDoss;
            }
        } else {
            $fichiers[] = $fileDoss;
        }
    }

    closedir($monDossier);

    // tri
    sort($dossiers);
    sort($fichiers);

    $files = array_merge($dossiers, $fichiers);
    //------------------------------------------------------

    $html = "<div class='finder'>"; //<h3> $dossierRef</h3>";
    $html2 = "";

    // parcourt de tous les éléments à afficher
    foreach ($files as $entry) {

        $html2 .= "\n<tr class='";

        if ($surligne == $entry) {
            // affichage de $entry cliqué, ou cliqué précédement...
            $html2 .= "actif ";
        }

        $html2 .= "' >";

        if (is_dir($dossierRef . '/' . $entry)) {
            $type = "DOS";
            $html2 .= '<td class="dossier">' . $type . '</td>';
            $html2 .= '<td class="link"><a href="' . $fichierPhp . '.php?type=dos&name=' . $dossierRef . '/' . $entry . '">' . $entry . '</a></td>';
            //$html2 .= '<td></td>';

        } else {
            // extension du fichier
            $ext = explode(".", $entry);
            $type = $ext[count($ext) - 1];
            // type
            $html2 .= '<td class="file ' . $type . '">' . strtoupper($type) . '</td>';
            // nom du fichier
            if ($type == 'pdf') {
                $html2 .= '<td class="link"><a target="_blank" href="' . $dossierRef . '/' . $entry . '">' . $entry . '</a></td>';
            } else {
                $html2 .= '<td class="link"><a href="' . $fichierPhp . '.php?type=' . $type . '&name=' . $dossierRef . '/' . $entry . '">' . $entry . '</a></td>';
            }

            // taille
            $tailleOctet = filesize($dossierRef . '/' . $entry);
            $tailleTotale += $tailleOctet;
            //$html2 .= '<td align="right">'.number_format($tailleOctet, 0, ',', '&nbsp;').' Octets</td>';

        }
        $html2 .= "</tr>";
    }

    $html2 .= "</table>";
    $html2 .= "</div>";

    // est mis à la fin du code pour récupérer la taille totale.
    // mais sera affiché en premier.
    $html1 = "\n<table class='arbo' border='0'>";
    $html1 .= "<tr>";
    $html1 .= '<th>Type</th>';
    $html1 .= '<th>Name</th>';
    //$html1 .= '<th>Size&nbsp;(~'.number_format($tailleTotale/1024, 0, ',', '&nbsp;').'&nbsp;Ko)</th>';

    $html1 .= "</tr>";

    $html .= $html1 . $html2;

    return $html;
}

//
//
//
function showDos($type = 'toto')
{
   
    global $urls;
    global $base;

    // echo scanDossier($base);
    // return;

    $useUrl = "";

    if (count($urls)) {
        // on ne prends pas la dernière case, si c'est une image.
        $limite = 0;

        if ($type != 'dos') {
            $limite = -1;
        }

        // $surligne : contient le nom du prochain dossier/fichier à afficher.
        // cette information sera utile, lors de l'affichage du tableau,
        // pour mettre en surbrillance l'élément cliqué.
        $surligne = "";

        for ($i = 0; $i < count($urls) + $limite; $i++) {

            if ($useUrl) {
                $useUrl .= "/" . $urls[$i];
            } else {
                $useUrl .= $urls[$i];
            }

            if ($i < count($urls) - 1) {
                // on doit afficher une colonne (dossier/fichier) n
                // la colonne qui suivra, affichera le dossier/fichier suivant n+1
                // donc, en n (affichage de la colonne n), je récupère le n+1, pour pouvoir l'allumer...

                $surligne = $urls[$i + 1];
            }

            echo scanDossier($useUrl, $surligne);
        }

    } else {
        echo scanDossier($base);
    }
}

?>

</body>
</html>


