<script>
    // npm i svelte-forms

    import PartieInscription from "./elem_svlt/partieInscription.svelte";
    import IhmLogin from "./elem_svlt/ihmLogin.svelte";
    import Admini from "./elem_svlt/admini.svelte";
    import Login from "./elem_svlt/login.svelte";
    import Menu from "./elem_svlt/Menu.svelte";
    import Modal from "./lib/Modal.svelte";

    import { onMount } from "svelte";
    import { colorHSL } from "./js/outils";

    /*
TODO : 

LiveReload enabled on port 35730

!!! Attente après clique !!!

BDD
Champs utilisateur actif

PHP
Envoie d'email après inscription
Liens permettant de valider l'email.
Liens permettant de changer de mot de passe.

JS
Page récup email validation et met MAJ de la BDD (actif)
Page message "envoi email pour valider"
Page : email déjà existant, proposer login.


Table "dujour" : 
Génération des infos du jour.
Infiltrer ... les choix dans les pages..


Créer une page affichant les résultats ET le choix d'exo jour associé.

*/
    let modalShow_b = false;
    let choixDuJour = {};

    onMount(() => {
        console.log("Start");
        titreCouleur();
        initDataDuJour();
        // _showModal("", "<h2>AA</h2>bb");
        if(localStorage.getItem('mail')) {
             _login.showHide(true);
        }

    });

    let _header, _main, _ihmLogin, _admini, _partieInscription, _modal, _login, _attente;
    let _articleAccueil;

    function nomUserShow(event) {
        // console.log("Nom User : " + event.detail.nom);
        _ihmLogin.setNom(event.detail.nom);
    }

    function adminShow(event) {
        _admini.showHide(event.detail.showAdmin);
    }
    function loginShow(event) {
        console.log("showLogin : " + event.detail.showLogin);
        _login.showHide(event.detail.showLogin);
    }

    function titreCouleur() {
        let titre = _header.querySelector("h1").innerHTML;
        let out = "";
        for (let i = 0; i < titre.length; i++) {
            let c = colorHSL(i, titre.length, 90, 7, 1);
            out += "<span style='color:" + c + ";'>" + titre[i] + "<span>";
        }
        _header.querySelector("h1").innerHTML = out;
    }

    function initDataDuJour() {
        const _data = "action=duJourInit";

        fetch("http://tlpt.freelancetoulouse.com/php/duJour.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },

            body: _data,
        })
            .then((response) => response.json())
            .then((result) => {
                console.log(
                    "Qui : " +
                        result[0].qui +
                        ": (error : " +
                        result[0].error +
                        ")"
                );

                choixDuJour.couleur = result[0].couleur;
                choixDuJour.forme = result[0].forme;
                choixDuJour.nombre = result[0].nombre;
            });
    }

    function     showHideAttente_fx(event) {
        attenteSH(event.detail.bool);
    }

    function attenteSH(bool=true) {
        if(bool) {
            _attente.style.display = 'block';
        } else {
            _attente.style.display = 'none';
        }
    }

    function accueilInscrit(event) {
        console.log(event);
        _partieInscription.pageChoixExo();

        if (event.detail.boss) {
            _ihmLogin.showBoss();
        }
    }

    function _closeModal() {
        _modal.hide();
        modalShow_b = false;
    }
    function _showModal(event, msg = "") {
        if (event) {
            // !!!
            _modal.show(event.detail.msg);
            if (event.detail.todo == "login") {
                _login.showHide(true);
            }
        } else {
            if (msg) {
                _modal.show(msg);
            }
        }
        modalShow_b = true;
    }

    function showExo_fx(event) {
        _partieInscription.showExoo(event.detail.exos);
    }

    function showPageAccueil() {
        _partieInscription.showExoo({ nom: "Accueil" });
    }
</script>

<div class="attenteCss" bind:this={_attente}>
    <div />
    <div />
    <div />
    <div />
</div>

<Admini bind:this={_admini} on:showAttente={showHideAttente_fx} />

<Modal bind:this={_modal} on:close={_closeModal} msgModal={"/"} />

<header bind:this={_header}>
    <h1>Télépathons</h1>
    <!-- <Menu on:showAccueil={showPageAccueil} on:showExo={showExo_fx} /> -->
    <IhmLogin
        bind:this={_ihmLogin}
        on:showFormLogin={loginShow}
        on:showHideAdmin_event={adminShow}
    />
</header>

<main bind:this={_main}>
    <Login
    on:showAttente={showHideAttente_fx}
        on:goToAccueil={accueilInscrit}
        on:modal={_showModal}
        on:nomUser={nomUserShow}
        bind:this={_login}
    />
    <PartieInscription
    on:showAttente={showHideAttente_fx}
        bind:this={_partieInscription}
        on:modal={_showModal}
        on:affNomUserEvt={nomUserShow}
        on:finInscription={accueilInscrit}
        {choixDuJour}
    />
    <!-- etatModal={modalShow_b} -->
    <!-- <article class="accueil" bind:this={_articleAccueil}>
        <h2>Très bien !</h2>
        <p>
            Vosu avez fini les exercices et il vous faut maintenant patienter,
            vous aurez vos résultats par mail.
        </p>
    </article> -->
</main>

<style>
    /* .accueil {
        display: none;
    } */
</style>
