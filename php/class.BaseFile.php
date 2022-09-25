<?php

/*

var export

si ; => une seule ligne

si , => plusieurs lignes
=> boolPlusieurLignes = 1

Class...

getFunction
getVarExport
getFunctionExport
getEventDisPatch

 */

class BaseFile
{

    private $lines = "";
    private $lines_arr = [];
    private $globalVars = [];
    private $lesConsts = [];
    private $lesFx = [];
    private $lesImports = [];
    private $pos = ["scriptStart"=>0, "scriptEnd"=>0, "styleStart"=>0, "styleEnd"=>0];

    //
    public function __construct($_lines)
    {
        $this->lines = $_lines;
        $this->setLinesArr();

        $this->getImports();
        $this->getFonction();
        $this->getConst();
        $this->getVarGlobal();

        // $this->svelteInHtml();
    }

    public function svelteInHtml() {
        echo"<div class='svleteHtml'>";
        for ($i=$this->pos["scriptEnd"]+1; $i < $this->pos["styleStart"] ; $i++) { 
            $line  = $this->lines_arr[$i]["texte"];

            echo "<span class=' ";
            foreach ($this->lesImports as $data) {

                if (strstr($line, "&lt;".$data["nom"])) {
                    echo"import ";
                }
                
            }

            echo "'>".$this->lines_arr[$i]["texte"]."</span><br>";

        }
        echo"</div>";
    }

    //
    private function setLinesArr()
    {
        // on parcourt le tableau (fichier) original
        foreach ($this->lines as $line_num => $line) {
            $this->lines_arr[$line_num] = [];
            $this->lines_arr[$line_num]["texte"] = ltrim($this->unescapeHTML($line));
            $this->lines_arr[$line_num]["html"] = $line;
            $this->lines_arr[$line_num]["import"] = false;
            $this->lines_arr[$line_num]["export"] = false;
            $this->lines_arr[$line_num]["pointV"] = false;
            $this->lines_arr[$line_num]["function"] = false;
            $this->lines_arr[$line_num]["scriptStart"] = null;
            $this->lines_arr[$line_num]["scriptEnd"] = null;
            $this->lines_arr[$line_num]["styleStart"] = null;
            $this->lines_arr[$line_num]["styleEnd"] = null;
            $this->lines_arr[$line_num]['isFx'] = false;

            if (strstr($line, "<script")) {
                $this->lines_arr[$line_num]["scriptStart"] = $line_num;
                $this->pos["scriptStart"] = $line_num;
            }
            if (strstr($line, "</script>")) {
                $this->lines_arr[$line_num]["scriptEnd"] = $line_num;
                $this->pos["scriptEnd"] = $line_num;
            }
            if (strstr($line, "<style")) {
                $this->lines_arr[$line_num]["styleStart"] = $line_num;
                $this->pos["styleStart"] = $line_num;
            }
            if (strstr($line, "</style>")) {
                $this->lines_arr[$line_num]["styleEnd"] = $line_num;
                $this->pos["styleEnd"] = $line_num;
            }

            // import ?
            if (strstr($line, "import")) {
                $this->lines_arr[$line_num]["import"] = true;
            }
            // export ?
            if (strstr($line, "export")) {
                $this->lines_arr[$line_num]["export"] = true;
            }
            if (strstr($line, "function")) {
                $this->lines_arr[$line_num]["function"] = true;
            }
            if (strstr($line, "pointV")) {
                if (!strstr($line, "for(")) {
                    $this->lines_arr[$line_num]["pointV"] = true;
                }

            }
        }
    }

    public function getImports()
    {
        //$this->setLinesArr();
        $num = 0;
        foreach ($this->lines_arr as $line_num => $line) {
            if(strstr(explode(" ", ltrim($this->lines_arr[$line_num]['html']))[0], "//")) {
                continue;
            }
            if ($this->lines_arr[$line_num]["import"]) {
                $this->lesImports[$num] = [];
                // echo "<br>" . $this->lines_arr[$line_num]['html'];

                if (strstr($this->lines_arr[$line_num]['html'], 'from "svelte"')) {
                    $this->lesImports[$num]["svelte"] = true;
                } else {
                    $this->lesImports[$num]["svelte"] = false;
                }
                $this->lesImports[$num]["line_num"] = $line_num;
                $this->lesImports[$num]["nom"] = explode(" ", $this->extraitNomFx($this->lines_arr[$line_num]['html']))[0];
                $num++;
            }
        }
    }
    //

    //
    public function showImports()
    {
        echo "<h3>Imports</h3>";

        echo "<div class='flex'>";
        $html = "";
        foreach ($this->lesImports as $data) {
            $html .= "<div class='import ";
            if($data["svelte"]) {
                $html .= "svelte ";
            }
            $html .= "'><a href='#line_" . ($data["line_num"]) . "'>" . $data['nom'] . "</a></div>";
        }
        echo $html;
        echo "</div>";
    }
    //
    public function getFonction()
    {
        // avant dernière function n'a pas num fin fx !!!

        $numFx = 0;
        foreach ($this->lines_arr as $line_num => $line) {
            if ($this->lines_arr[$line_num]["function"] && !strstr($this->lines_arr[$line_num]['html'], "window.setTimeout")) {

                $this->lesFx[$numFx] = [];
                $this->lesFx[$numFx]["startFx"] = $line_num;
                $this->lesFx[$numFx]["endFx"] = ""; // !!!
                $this->lesFx[$numFx]["export"] = $this->isExport($this->lines_arr[$line_num]['texte']);
                $this->lesFx[$numFx]["nom"] = $this->extraitNomFx($this->lines_arr[$line_num]['html']);
                $numFx++;
            } else if (strstr($this->lines_arr[$line_num]['html'], "onMount(")) {
                $this->lesFx[$numFx] = [];
                $this->lesFx[$numFx]["startFx"] = $line_num;
                $this->lesFx[$numFx]["endFx"] = ""; // !!!
                $this->lesFx[$numFx]["export"] = false;
                $this->lesFx[$numFx]["nom"] = "onMount";
                $numFx++;
            }

        }

        // cherche la fin de la fonction... précédente
        for ($i = 1; $i < count($this->lesFx); $i++) {

            $cStart = $this->lesFx[$i]['startFx'];
            $c = $cStart;
            //
            $finWhile = false;
            do {
                $c--;
                if (strstr($this->lines_arr[$c]['html'], "}")) {
                    $this->lesFx[$i - 1]["endFx"] = $c;
                    $finWhile = true;
                }
            } while (!$finWhile && $c > ($cStart - 50) && $c > 1);
        }
        //
        // la fin de la dernière fonction
        foreach ($this->lines_arr as $line_num => $line) {
            if ($line_num === $this->lines_arr[$line_num]["scriptEnd"]) {
                $cStart = $line_num;
                $c = $cStart;
                //
                $finWhile = false;
                do {
                    $c--;
                    if (strstr($this->lines_arr[$c]['html'], "}")) {
                        $this->lesFx[count($this->lesFx) - 1]["endFx"] = $c;
                        $finWhile = true;
                    }
                } while (!$finWhile && $c > ($cStart - 50) && $c > 1);
            }
        }

        foreach ($this->lesFx as $line_num => $line) {
            for ($i = $this->lesFx[$line_num]['startFx']; $i < $this->lesFx[$line_num]['endFx']; $i++) {
                $this->lines_arr[$i]['isFx'] = true;
            }
        }
    }

    //
    // affichage des fonctions du tableau lesFx[]
    public function showFonction()
    {
        echo "<h3>Fonctions</h3>";

        echo "<div class='flex'>";
        $html = "";
        foreach ($this->lesFx as $fx) {
            if (isset($fx['nom'])) {
                $html .= "<div class='";
                if ($fx['export']) {
                    $html .= "export";
                }
                $html .= " uneFx'><a href='#line_" . ($fx["startFx"]) . "'>" . $fx['nom'] . " (" . $fx["startFx"] . "-" . $fx["endFx"] . ")</a></div>";
            }
        }
        echo $html;
        echo "</div>";
    }
    //
    //
    private function isExport($line)
    {
        if (strstr($line, "export")) {
            return true;
        }
        return false;
    }
    //
    //
    //
    private function extraitNomFx($line)
    {
        if (strstr($line, "function")) {
            $line = str_replace("function", "", $line);
        }
        if (strstr($line, "import")) {
            $line = str_replace("import", "", $line);
        }
        if (strstr($line, "export")) {
            $line = str_replace("export", "", $line);
        }
        if (strstr($line, "{")) {
            $line = str_replace("{", "", $line);
        } else {
            $line .= ")";
        }
        return ltrim($line);
    }
    //
    //
    private function unescapeHTML($escapedHTML)
    {
        $search = array('<', '>', '    ');
        $replace = array('&lt;', '&gt;', '&nbsp;&nbsp;&nbsp;&nbsp;');
        return str_replace($search, $replace, $escapedHTML);
    }
    //
    /**
     * rempli le tableau $globalVars[] par des variables séparées d'une virgule.
     */
    public function chopeVarVirgule($ligneText, $line_num, $export_b, $plusieurLignes_b)
    {
        $var = [];

        // Suppression de tout ce qui suit un égal. !!!
        // plusieurs = sur une même ligne ? !!!
        $pos = strrpos($ligneText, "=");
        if ($pos) {
            $ligneText = substr($ligneText, 0, $pos);
        }
        //
        $ligneText = ltrim($ligneText, " \n\r\t\v\x00");

        if (strstr($ligneText, ",")) {

            $rVirg = explode(",", $ligneText);

            // plusieurs variables déclarées sur un mêmme ligne.
            if (count($rVirg) == 2 && $plusieurLignes_b) {
                // foreach ($rVirg as $var) {
                // echo "<br>EXp : " . $export_b;
                $l = count($var);
                $var[$l] = [];
                $var[$l]["value"] = $rVirg[0]; //"AA " .$rVirg[0];
                $var[$l]["line_num"] = $line_num;
                $var[$l]["export"] = $export_b;
                // }
            } else if ((count($rVirg) == 2 && !$plusieurLignes_b) || count($rVirg) > 2) {
                foreach ($rVirg as $_var) {
                    // echo "<br>EXp : " . $export_b;
                    $l = count($var);
                    $var[$l] = [];
                    $var[$l]["value"] = $_var; //"BB " .$var;
                    $var[$l]["line_num"] = $line_num;
                    $var[$l]["export"] = $export_b;
                }
            } else {
                $l = count($var);
                $var[$l] = [];
                $var[$l]["value"] = $rVirg[0]; //"CC " . $rVirg[0];
                $var[$l]["line_num"] = $line_num;
                $var[$l]["export"] = $export_b;
            }

        } else {
            $r = explode(" ", $ligneText);

            $l = count($var);

            $var[$l] = [];
            $var[$l]["value"] = $r[0]; // "DD " . $r[0];
            $var[$l]["line_num"] = $line_num;
            $var[$l]["export"] = $export_b;
        }

        return $var;
    }

    /**
     *
     */
    public function getConst()
    {
        $plusieurLignes_b = false;
        $export_b = false;

        foreach ($this->lines_arr as $line_num => $line) {
            $ligneText = $this->lines_arr[$line_num]["html"];

            if(strstr(explode(" ", ltrim($ligneText))[0], "//")) {
                continue;
            }

            if (strstr($ligneText, "export")) {
                $ligneText = str_replace("export", "", $ligneText);
                $export_b = true;
            }

            if ($this->lines_arr[$line_num]['isFx']) {
                continue;
            }
            if (count($this->lesFx)) {
                if ($line_num >= $this->lesFx[0]["startFx"]) {
                    break;
                }
            }
            if (strstr($ligneText, "const")  || $plusieurLignes_b) {
                // echo " - " . $ligneText;
                if ($plusieurLignes_b) {
                    if (strstr($this->lines_arr[$line_num]["html"], ";")) {
                        $plusieurLignes_b = false;
                    }
                } else {
                    $ligneText = str_replace("const ", "", $ligneText);


                    if (strstr($ligneText, "{")) {
                        $ligneText = str_replace("{", "", $ligneText);
                    } else if (strstr($ligneText, ";")) {
                        $ligneText = str_replace(";", "", $ligneText);
                        $export_b = false;
                    } else {
                        $plusieurLignes_b = true;
                    }

                }

                $this->lesConsts = array_merge(
                    $this->lesConsts, 
                    $this->chopeVarVirgule($ligneText, $line_num, $export_b, $plusieurLignes_b)
                );
            }
        }
    }

    //
    //
    //
    public function showConst(){  
        echo "<h3>Constantes</h3>";

        echo "<div class='lesConst'>";
        foreach ($this->lesConsts as $data) {
            echo "<div class='uneConst ";

            if ($data["export"]) {
                echo "export";
            }

            echo "'><a href='#line_" . ($data["line_num"]) . "'>" . $data["value"] . "</a></div>";
        }
        echo "</div>";
    }


    /**
     *
     */
    public function getVarGlobal()
    {
        $plusieurLignes_b = false;
        $export_b = false;

        foreach ($this->lines_arr as $line_num => $line) {

            // $ligneText = $this->lines_arr[$line_num]["texte"];
            $ligneText = $this->lines_arr[$line_num]["html"];

            if(strstr(explode(" ", ltrim($ligneText))[0], "//")) {
                continue;
            }

            if (strstr($ligneText, "export")) {
                $ligneText = str_replace("export", "", $ligneText);
                $export_b = true;
                //echo "<br>export :: " . $ligneText . " :: ".$export_b;
            }

            if ($this->lines_arr[$line_num]['isFx']) {
                continue;
            }
            if (count($this->lesFx)) {
                if ($line_num >= $this->lesFx[count($this->lesFx) - 1]["endFx"]) {
                    break;
                }}
            if (strstr($ligneText, "let")  || $plusieurLignes_b) {

                if ($plusieurLignes_b) {
                    // on a déjà commencé à traiter la ligne et on poursuit.
                    if (strstr($this->lines_arr[$line_num]["html"], ";")) {
                        //echo ":: Fin plusieursLignes " . $ligneText;
                        $plusieurLignes_b = false;
                    }
                } else {
                    $ligneText = str_replace("let ", "", $ligneText);


                    if (strstr($ligneText, "{")) {
                        $ligneText = str_replace("{", "", $ligneText);
                    } else if (strstr($ligneText, ";")) {
                        $ligneText = str_replace(";", "", $ligneText);
                        $export_b = false;
                    } else {
                        $plusieurLignes_b = true;
                    }

                }

                $this->globalVars = array_merge($this->globalVars,$this->chopeVarVirgule($ligneText, $line_num, $export_b, $plusieurLignes_b));

            }
        }
    }

    //
    //
    public function showVarGlobal(){
        echo "<h3>Variables Globales</h3>";
        // var_dump($this->globalVars);

        echo "<div class='lesVars'>";
        foreach ($this->globalVars as $data) {
            echo "<div class='uneVar ";

            if ($data["export"]) {
                echo "export";
            }

            echo "'><a href='#line_" . ($data["line_num"]) . "'>" . $data["value"] . "</a></div>";
        }
        echo "</div>";
    }

    public function afficheLigneDeCode()
    {

        echo "<div class='divText'>";
        // var_dump($lines_arr);

        echo "<table border='1'>";

        $html = "";
        foreach ($this->lines_arr as $line_num => $line) {
            if ($this->lines_arr[$line_num]["scriptStart"] >= "0") { // 
                $html .= "<tr><td colspan='2'>Script</td></tr>";
            }
            if ($this->lines_arr[$line_num]["styleStart"]) {
                $html .= "<tr><td colspan='2'>STYLE</td></tr>";
            }

            $html .= "<tr>";
            $html .= "<td class='numLigne' id='line_" . $line_num . "'>" . $line_num . "</td>";
            $html .= "<td class='";
            if ($this->lines_arr[$line_num]["function"]) {
                $html .= "function_code ";
            }
            if ($this->lines_arr[$line_num]["export"]) {
                $html .= "export_code ";
            }
            if ($this->lines_arr[$line_num]["import"]) {
                $html .= "import_code ";
            }
            $html .= "'>" . $this->lines_arr[$line_num]["texte"] . "</td>";
            $html .= "</tr>";

            if ($this->lines_arr[$line_num]["scriptEnd"]) {
                $html .= "<tr><td colspan='2'>Fin Script</td></tr>";
            }
            if ($this->lines_arr[$line_num]["styleEnd"]) {
                $html .= "<tr><td colspan='2'>Fin Style</td></tr>";
            }
        }
        echo $html;
        echo "</table>";

        echo "</div>";
    }

}
