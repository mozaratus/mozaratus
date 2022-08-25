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

*/
    let modalShow_b = false;

    onMount(() => {
        console.log("Start");
        titreCouleur();
        // _showModal("", "<h2>AA</h2>bb");
    });

    let _header, _main, _ihmLogin, _admini, _partieInscription, _modal, _login;
    let _articleAccueil;

    function disparition() {
        _header.style.opacity = 0;
        _main.style.opacity = 0;
        window.addEventListener("mousemove", rallumeTout);
    }
    function rallumeTout() {
        apparition();
        _partieInscription.rallumeTout();
        window.removeEventListener("mousemove", rallumeTout);
    }

    function apparition() {
        _header.style.opacity = 1;
        _main.style.opacity = 1;
    }

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

    function accueilInscrit() {
        // alert("Allumage");
        rallumeTout();
        _partieInscription.cacheContainer();
        _articleAccueil.style.display = "block";
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
        _partieInscription.showExoo({nom:"Accueil"});
    }
</script>

<Admini bind:this={_admini} />

<Modal bind:this={_modal} on:close={_closeModal} msgModal={"/"} />

<header bind:this={_header}>
    <h1>Télépathons</h1>
    <Menu on:showAccueil={showPageAccueil} on:showExo={showExo_fx} />
    <IhmLogin
        bind:this={_ihmLogin}
        on:showFormLogin={loginShow}
        on:showHideAdmin_event={adminShow}
    />
</header>

<main bind:this={_main}>
    <Login
        on:goToAccueil={accueilInscrit}
        on:modal={_showModal}
        on:nomUser={nomUserShow}
        bind:this={_login}
    />
    <PartieInscription
        bind:this={_partieInscription}
        on:modal={_showModal}
        on:disparaitre={disparition}
        on:apparait={apparition}
        on:affNomUserEvt={nomUserShow}
        on:finInscription={accueilInscrit}
        etatModal={modalShow_b}
    />
    <article class="accueil" bind:this={_articleAccueil}>
        <h2>Très bien !</h2>
        <p>Vosu avez fini les exercices et il vous faut maintenant patienter, vous aurez vos résultats par mail.</p>
    </article>
</main>

<style>
    .accueil {
        display: none;
    }
</style>
