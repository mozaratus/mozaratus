<script>
    import { onMount } from "svelte";
    import { createEventDispatcher } from "svelte";

    import PageAccueil from "./pageAccueil.svelte";
    import PageMiniForm from "./pageMiniForm.svelte";
    import PageExoBase from "./pageExoBase.svelte";
    import PageBeforeExoCouleur from "./pageBeforeExoCouleur.svelte";

    import { _msgModal } from "../js/data.js";

    export let _pageAccueil, _pageMiniForm, _pageBeforeExoCouleur;
    export let _pageExoBaseCouleur, _pageExoBaseForme, _pageExoBaseNombre;

    // property
    export let etatModal;

    let leContainer;
    let pageActive;
    onMount(() => {
        console.log("Start Partie Inscription");
        pageActive = _pageAccueil;
    });

    window.onkeydown = (e) => {
        //console.log(e.code);
        if (!etatModal && (e.code == "Enter" || e.code == "NumpadEnter")) {
            switch (pageActive.getNom()) {
                case _pageAccueil.getNom():
                    pageMiniForm();
                    break;
                case _pageBeforeExoCouleur.getNom():
                    pageExoCouleur();
                    break;
                default:
                    break;
            }
        }
    };

    const dispatch = createEventDispatcher();
    function ciao() {
        dispatch("disparaitre", {});
    }
    function hello() {
        dispatch("apparait", {});
    }

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
    function pageExoCouleur() {
        _pageBeforeExoCouleur.showHide(false);
        _pageExoBaseCouleur.showHide(true);
        pageActive = _pageExoBaseCouleur;
        // _pageExoBaseCouleur.cache(5000);
    }
    function pageExoForme() {
        _pageExoBaseCouleur.showHide(false);
        _pageExoBaseForme.showHide(true);
        pageActive = _pageExoBaseForme;
        dispatch("modal", { msg: _msgModal.couleur.msg });
    }

    function pageExoNombre() {
        _pageExoBaseForme.showHide(false);
        _pageExoBaseNombre.showHide(true);
        dispatch("modal", { msg: _msgModal.forme.msg });
        pageActive = _pageExoBaseNombre;
    }

    function pageExoFin() {
        _pageExoBaseNombre.showHide(false);
        dispatch("modal", { msg: _msgModal.nombre.msg });
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
                console.error('Pbm Exos');
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
    }
</script>

<div class="container" bind:this={leContainer}>
    <PageAccueil on:suiteAccueil={pageMiniForm} bind:this={_pageAccueil} />
    <PageMiniForm
        on:suiteMiniForm={beforeExo}
        on:nomUser={affNomUser}
        on:modalEmailUsed={modalMiniForm}
        bind:this={_pageMiniForm}
    />
    <PageBeforeExoCouleur
        on:suitBeforeExo={pageExoCouleur}
        bind:this={_pageBeforeExoCouleur}
    />

    <!-- Rouge, Orange, Jaune, Vert, Bleu, Rose, Violet -->
    <PageExoBase
        champs={"couleur"}
        on:suiteExo={pageExoForme}
        on:nuit={ciao}
        on:jour={hello}
        bind:this={_pageExoBaseCouleur}
    />

    <!-- cercle, carré, triangle, etoile, hexagone, boule, cube, pyramide -->
    <PageExoBase
        champs={"forme"}
        on:suiteExo={pageExoNombre}
        on:nuit={ciao}
        on:jour={hello}
        bind:this={_pageExoBaseForme}
    />
    <PageExoBase
        champs={"nombre"}
        on:suiteExo={pageExoFin}
        on:nuit={ciao}
        on:jour={hello}
        bind:this={_pageExoBaseNombre}
    />
</div>

<style>
    .container {
        display: block;
    }
</style>
