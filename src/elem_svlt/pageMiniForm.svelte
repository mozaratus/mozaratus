<script>
    import { createEventDispatcher } from "svelte";

    let leContainer, email, nom, pw;
    // let _result = [];

    const nomSvt = "miniForm";

    export function getNom() {
        return nomSvt;
    }

    function formHandler(event) {
        event.preventDefault();

        const myDate = new Date();

        let dateInscription =
            myDate.getFullYear() +
            "-" +
            ("0" + (myDate.getMonth() + 1)).slice(-2) +
            "-" +
            ("0" + myDate.getDate()).slice(-2) +
            " " +
            myDate.getHours() +
            ":" +
            ("0" + myDate.getMinutes()).slice(-2) +
            ":" +
            myDate.getSeconds();

        // let res = now.toISOString().slice(0,10).replace(/-/g,"");
        console.log("Time : " + dateInscription);

        const _data =
            "action=miniForm&nom=" +
            nom.value +
            "&pw=" +
            pw.value +
            "&email=" +
            email.value +
            "&date=" +
            dateInscription;

        fetch("http://localhost/htdocs/2022/telepathons/src/php/getData.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },

            body: _data,
        })
            .then((response) => response.json())
                        .then((result) => {
                console.log("Qui : " + result[0].qui);
                //console.log(result);
                // _result = result;
                if (result[0].error) {
                    console.log("Adresse e-mail déjà utilisée.");
                    dispatch("modalEmailUsed", { msg: "Adresse e-mail déjà utilisée." });
                } else {
                    dispatch("nomUser", { nom: result[0].nom });
                    //
                    suite();
                }
            });
    }

    const dispatch = createEventDispatcher();
    function suite() {
        // !! valid email.
        var validRegex =
            /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

        if (email.value.match(validRegex)) {
            dispatch("suiteMiniForm", {
                leContainer: leContainer,
                email: email.value,
            });
        } else {
            alert("Email Invalide");
        }
    }

    // !!!
    export function jour(){}

    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
        } else {
            leContainer.style.display = "none";
        }
    }
</script>

<div class="container" bind:this={leContainer}>
    <br /><br />Avant de faire les 3 perceptions, il vous sera demandé de
    laisser votre e-mail pour vous envoyer votre résultat. Selon votre résultat,
    vous pourrez participer à d'autres exercices ou être mis en relation avec
    d'autres inscrits.
    <br /><br />Il n'y a pas d'IA qui vérifie ce que vous aurez envoyé, c'est
    moi qui m'y colle et selon la fréquentation du site, la réponse sera... pas
    immédiate en tout cas.
    <br /><br /><b>NB</b> :
    <i
        >Il n'y aura aucun autre mail qui vous sera envoyé, ni diffusion de
        votre adresse à droite à gauche. Tout reste sur ce site, qui souhaite
        faire que les gens passent un peu de temps à développer la télépathie,
        la clair voyance...</i
    >
    <br /><br />

    <form on:submit|preventDefault={formHandler}>
        <p>
            <label for="" />
            <span class="titreH4">Mini Inscription</span>
        </p>
        <p>
            <label for="">Nom</label>
            <input type="text" value="Boby" bind:this={nom} />
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" value="toto@toto.fr" bind:this={email} />
        </p>
        <p>
            <label for="">Mot de passe</label>
            <input type="password" value="123" bind:this={pw} />
        </p>
        <p>
            <label for="" />
            <input type="button" value="Suite" on:click={formHandler} />
        </p>
    </form>
</div>

<style>
    .container {
        display: none;
    }
    form {
        position: relative;
        width: 360px;
        /* background-color: #ff0; */
        left: 50%;
        transform: translateX(-50%);
    }
</style>
