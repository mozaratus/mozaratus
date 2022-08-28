<script>
    import { onMount } from "svelte";
    import { createEventDispatcher } from "svelte";

    import PageAccueil from "./pageAccueil.svelte";
    import PageMiniForm from "./pageMiniForm.svelte";
    import PageExoBase from "./pageExoBase.svelte";
    import PageChoixExo from "./pageChoixExo.svelte";
    import PageBeforeExoCouleur from "./pageBeforeExoCouleur.svelte";

    import PageFinExoDuJour from "./pageFinExoDuJour.svelte";

    import { _msgModal } from "../js/data.js";

    export let _pageAccueil,
        _pageMiniForm,
        _pageBeforeExoCouleur,
        _pageChoixExos;
    export let _pageExoBaseCouleur,
        _pageExoBaseForme,
        _pageExoBaseNombre,
        _pageFinExoDuJour;

    // property
    // export let etatModal;
    export let choixDuJour;

    let leContainer;
    let pageActive;
    onMount(() => {
        console.log("Start Partie Inscription");
        pageActive = _pageAccueil;
    });

    // window.onkeydown = (e) => {
    //     //console.log(e.code);
    //     if (!etatModal && (e.code == "Enter" || e.code == "NumpadEnter")) {
    //         switch (pageActive.getNom()) {
    //             case _pageAccueil.getNom():
    //                 pageMiniForm();
    //                 break;
    //             case _pageBeforeExoCouleur.getNom():
    //                 pageExoCouleur();
    //                 break;
    //             default:
    //                 break;
    //         }
    //     }
    // };

    const dispatch = createEventDispatcher();

    // depuis Accueil
    function pageMiniForm() {
        // event.detail
        _pageAccueil.showHide(false);
        _pageMiniForm.showHide(true);
        pageActive = _pageMiniForm;
    }

    function beforeExo() {
        _pageMiniForm.showHide(false);
        _pageBeforeExoCouleur.showHide(true);
        pageActive = _pageBeforeExoCouleur;
    }

    function showExo_fx(event) {
        showExo_fx2(event.detail.exo);
    }

    // check result puis route
    function showExo_fx2(page) {
        cacheTout();

        // ? exos faits
        const _data = "action=exoFaits";

        fetch("http://tlpt.freelancetoulouse.com/php/getData.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },

            body: _data,
        })
            .then((response) => response.json())
            .then((result) => {
                console.log("Qui : " + result[0].qui + "", result);

                if (result[0].error == "1") {
                    // error
                    console.error("PBM : " + result[0].msg);
                } else {
                    let nbFini = 0;

                    if (result[0].error != "2") {
                        if (result[1].couleur) {
                            nbFini++;
                            _pageChoixExos.disabledBtn("couleur");
                        }
                        if (result[1].forme) {
                            nbFini++;
                            _pageChoixExos.disabledBtn("forme");
                        }
                        if (result[1].nombre) {
                            nbFini++;
                            _pageChoixExos.disabledBtn("nombre");
                        }
                    }

                    if (nbFini == 3) {
                        _pageChoixExos.showHide(false);
                        _pageFinExoDuJour.showHide(true);
                    } else {
                        switch (page) {
                            case "couleur":
                                dispatch("modal", {
                                    msg: _msgModal.couleur.msg,
                                });
                                pageExoCouleur();
                                break;
                            case "forme":
                                dispatch("modal", {
                                    msg: _msgModal.forme.msg,
                                });
                                pageExoForme();
                                break;
                            case "nombre":
                                dispatch("modal", {
                                    msg: _msgModal.nombre.msg,
                                });
                                pageExoNombre();
                                break;
                            default:
                                //
                                break;
                        }
                    }
                }
            });
    }

    export function pageChoixExo() {
        showExo_fx2();
        _pageChoixExos.showHide(true);
    }

    function pageExoCouleur() {
        cacheTout();
        _pageChoixExos.showHide(false);

        _pageExoBaseCouleur.showHide(true);
        pageActive = _pageExoBaseCouleur;
        // _pageExoBaseCouleur.cache(5000);
    }
    function pageExoForme() {
        cacheTout();
        //_pageExoBaseCouleur.showHide(false);

        _pageChoixExos.showHide(false);
        _pageExoBaseForme.showHide(true);
        pageActive = _pageExoBaseForme;
        // dispatch("modal", { msg: _msgModal.couleur.msg });
    }

    function pageExoNombre() {
        cacheTout();
        // _pageExoBaseForme.showHide(false);

        _pageChoixExos.showHide(false);
        _pageExoBaseNombre.showHide(true);
        //   dispatch("modal", { msg: _msgModal.forme.msg });
        pageActive = _pageExoBaseNombre;
    }

    function pageExoFin() {
        _pageExoBaseNombre.showHide(false);
        //   dispatch("modal", { msg: _msgModal.nombre.msg });
        dispatch("finInscription", {});
    }
    function affNomUser(event) {
        // console.log("Nom User : " + event.detail.nom);
        dispatch("affNomUserEvt", { nom: event.detail.nom });
    }

    export function rallumeTout() {
        if (pageActive) {
            console.log("Nom : " + pageActive.getNom());
            //pageActive.jour();
        }
    }

    export function cacheContainer() {
        leContainer.style.display = "none";
    }

    function modalMiniForm(event) {
        _pageMiniForm.showHide(false);
        dispatch("modal", { msg: event.detail.msg, todo: "login" });
    }

    export function showExoo(exos) {
        cacheTout();
        switch (exos.nom) {
            case "Accueil":
                _pageAccueil.showHide(true);
                break;
            case "Couleur":
                _pageExoBaseCouleur.showHide(true);
                break;
            case "Forme":
                _pageExoBaseForme.showHide(true);
                break;

            case "Nombre":
                _pageExoBaseNombre.showHide(true);
                break;
            default:
                console.error("Pbm Exos");
                break;
        }
    }

    function cacheTout() {
        console.log("Cache Tout");
        _pageAccueil.showHide(false);
        _pageMiniForm.showHide(false);
        _pageBeforeExoCouleur.showHide(false);
        _pageExoBaseCouleur.showHide(false);
        _pageExoBaseForme.showHide(false);
        _pageExoBaseNombre.showHide(false);
        _pageChoixExos.showHide(false);
    }
</script>

<div class="container" bind:this={leContainer}>
    <PageAccueil on:suiteAccueil={pageMiniForm} bind:this={_pageAccueil} />
    <PageFinExoDuJour bind:this={_pageFinExoDuJour} />

    <PageMiniForm
        on:suiteMiniForm={beforeExo}
        on:nomUser={affNomUser}
        on:modalEmailUsed={modalMiniForm}
        bind:this={_pageMiniForm}
    />
    <PageBeforeExoCouleur
        on:suitBeforeExo={pageChoixExo}
        bind:this={_pageBeforeExoCouleur}
    />

    <PageChoixExo
        champsExo={["couleur", "Forme", "Nombre"]}
        bind:this={_pageChoixExos}
        on:showExo={showExo_fx}
    />

    <!-- Rouge, Orange, Jaune, Vert, Bleu, Rose, Violet -->
    <PageExoBase
        chx={choixDuJour.couleur}
        champs={"couleur"}
        on:suiteExo={pageChoixExo}
        bind:this={_pageExoBaseCouleur}
    />

    <!-- cercle, carré, triangle, etoile, hexagone, boule, cube, pyramide -->
    <PageExoBase
        chx={choixDuJour.forme}
        champs={"forme"}
        on:suiteExo={pageChoixExo}
        bind:this={_pageExoBaseForme}
    />
    <PageExoBase
        chx={choixDuJour.nombre}
        champs={"nombre"}
        on:suiteExo={pageChoixExo}
        bind:this={_pageExoBaseNombre}
    />
</div>

<style>
    .container {
        display: block;
    }
</style>
