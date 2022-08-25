<script>
    import Case from "./Case.svelte";
    import { onMount } from "svelte";

    const MAX_LINES = 20;

    let user = "Dédé";
    let nbLignes = 5;
    let largeurPlateau = 500;
    let largeurCase = Math.round(largeurPlateau / nbLignes);

    // tableau d'objets Case
    let cases = [];
    for (let i = 0, max = nbLignes; i < max; i++) {
        cases[i] = [];
    }

    // génère un tableau qui via {#each tabPos as uneCase, i} dans le code HTML, permettra de créer les cases.
    let tabPos = [];
    function alimenteTab() {
        let posTop = 0,
            posRight = 0,
            posBottom = 0,
            posLeft = 0;
        tabPos = [];
        let c = "";
        for (let i = 0, max = nbLignes; i < max; i++) {
            for (let ii = 0, max = nbLignes; ii < max; ii++) {
                (posTop = 0), (posRight = 0), (posBottom = 0), (posLeft = 0);
                // top
                if (i == 0) {
                    // left
                    if (ii == 0) {
                        posTop = 1;
                        posLeft = 1;
                        // right
                    } else if (ii == nbLignes - 1) {
                        posTop = 1;
                        posRight = 1;
                    } else {
                        posTop = 1;
                    }
                    // bottom
                } else if (i == nbLignes - 1) {
                    if (ii == 0) {
                        posBottom = 1;
                        posLeft = 1;
                    } else if (ii == nbLignes - 1) {
                        posBottom = 1;
                        posRight = 1;
                    } else {
                        posBottom = 1;
                    }
                } else if (ii == 0) {
                    posLeft = 1;
                } else if (ii == nbLignes - 1) {
                    posRight = 1;
                }

                tabPos.push({
                    posTop: posTop,
                    posLeft: posLeft,
                    posRight: posRight,
                    posBottom: posBottom,
                    ligne: i,
                    colonne: ii,
                    num: i,
                });
            }
        }
    }
    alimenteTab();

    //------------------------------------------------------------------- START
    // intialisation de la taille du plateau
    onMount(() => {
        // console.log("largeurPlateau " + largeurPlateau);
        document.querySelector(".plateau").style.height = largeurPlateau + "px";
        document.querySelector(".plateau").style.width = largeurPlateau + "px";
    });
    //---------------------------------------------------------------- FX
    function valCase_fx(event) {
        user = "Dédé";
        if (event.detail.trace_b) {
            valCase += event.detail.valTrait;
        }

        let v = getVoisin(
            event.detail.nameTrait,
            event.detail.ligne,
            event.detail.colonne,
            event.detail.posTop,
            event.detail.posRight,
            event.detail.posBottom,
            event.detail.posLeft
        );

        if (v.nameModif) {
            cases[v.l][v.c].updateVoisin(v.nameModif);
        }

        window.setTimeout(function () {
            user = "PC";
            caseHasard();
        }, 500);
    }

    function caseHasard() {
        let choix = ["h", "d", "b", "g"];
        let caseHaz, nomTraitChoisi, l_haz, c_haz, cochage;
        let compteur = 0;

        console.log("------------ start loop");

        do {
            caseHaz = Math.round(Math.random() * (choix.length - 1));
            nomTraitChoisi = choix[caseHaz];
            l_haz = Math.round(Math.random() * (nbLignes - 1));
            c_haz = Math.round(Math.random() * (nbLignes - 1));

            console.log("Boucle :: nomTraitChoisi... ");
            console.log(nomTraitChoisi + " l_haz " + l_haz + " c_haz " + c_haz);

            cases[l_haz][c_haz].allume("red");

            cochage = cases[l_haz][c_haz].coche(nomTraitChoisi);
            console.log("cochage " + cochage);
        } while (!cochage && ++compteur < 20);
        cases[l_haz][c_haz].allume("orange");

        console.log("-------------- END loop compteur: " + compteur);
        console.log("case ", cases[l_haz][c_haz]);

        let o = cases[l_haz][c_haz].getDatas();
        let v = getVoisin(
            nomTraitChoisi,
            o.ligne,
            o.colonne,
            o.posTop,
            o.posRight,
            o.posBottom,
            o.posLeft
        );

        console.log("\nDATA du trait de ref : ", o);
        console.log("Voisin : ", v);

        if (v) {
            cases[v.l][v.c].updateVoisin(v.nameModif);
        }
    }

    // recherche la case voisine.
    function getVoisin(
        nameTrait,
        ligne,
        colonne,
        posTop,
        posRight,
        posBottom,
        posLeft
    ) {
        console.log("getVoisin FX");
        let l, c, nameModif;

        // check si pas dernière ligne ou ...
        console.log(
            "trait Ref : ",nameTrait,
            " ligne:",ligne,
            " colonne:",colonne,
            posTop,
            posRight,
            posBottom,
            posLeft
        );
        switch (nameTrait) {
            case "h":
                // si pas en haut
                if (!posTop && ligne) {
                    l = ligne - 1;
                    c = colonne;
                    nameModif = "b";
                } else {
                    return false;
                }
                break;
            case "d":
                // si pas à droite
                if (!posRight && colonne < nbLignes - 1) {
                    l = ligne;
                    c = colonne + 1;
                    nameModif = "g";
                } else {
                    return false;
                }
                break;
            case "b":
                // si pas en bas
                if (!posBottom && ligne < nbLignes - 1) {
                    l = ligne + 1;
                    c = colonne;
                    nameModif = "h";
                } else {
                    return false;
                }
                break;
            case "g":
                // si pas en bas
                if (!posLeft && colonne) {
                    l = ligne;
                    c = colonne - 1;
                    nameModif = "d";
                } else {
                    return false;
                }
                break;
        }

        console.log("return : l=" + l + " c=" + c);
        return { nameModif: nameModif, l: l, c: c };
    }
</script>

<button on:click={caseHasard}>??</button>

<section class="plateau">
    <!-- on indique à chaque case sa position sur le plateau -->
    {#each tabPos as uneCase, i}
        <Case
            bind:this={cases[uneCase.ligne][uneCase.colonne]}
            {largeurCase}{user}
            posTop={uneCase.posTop}
            posLeft={uneCase.posLeft}
            posRight={uneCase.posRight}
            posBottom={uneCase.posBottom}
            ligne={uneCase.ligne}
            colonne={uneCase.colonne}
            on:recupValCase={valCase_fx}
        />
    {/each}
</section>

<style>
    .plateau {
        height: 50px;
        width: 50px;
        position: relative;
        margin-bottom: 50px;
        /* display: grid;
        grid-template-columns: repeat(5, 1fr);
        justify-content: center; */
        /* background-color: aqua; */
    }
</style>
