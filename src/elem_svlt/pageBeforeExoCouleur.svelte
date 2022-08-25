<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    let leContainer;

    const nomSvt = "beforeExoCouleur";

    export function getNom() {
        return nomSvt;
    }

    onMount(() => {});

    const dispatch = createEventDispatcher();

    function suite() {
        dispatch("suitBeforeExo", { leContainer: leContainer });
    }

    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
        } else {
            leContainer.style.display = "none";
        }
    }
</script>

<div class="container" bind:this={leContainer}>
    Avant de faire un exercice, il serait bon de vous mettre en condition. Etre
    <b
        >détendu, au calme, avec une respiration tranquille et un petit sourire
    </b>
    indiquant l'anthousiasme de faire certte connexion/perception.
    <br /><br />Pour chaque exercice, une fois arrivé sur la page, vous avez le
    temps que vous voulez pour cliquer sur le bouton qui vous permettra d'entrer
    votre proposition.
    <br />Il faut noter que vous êtes dans une situation de réception, c'est une
    information qui vous est envoyé, donc, il n'y a pas de logique, d'analyse à
    faire. Simplement être ouvert à recevoir, une couleur, puis une forme, puis
    un chiffre. Et <b>la première perception</b> que vous aurez est la seule qui
    nous intéresse. Il faut un peu débrancher le cerveau et simplement
    <i>"écouter"</i>.
    <p />
    <p>
        Par la suite, si vous réussisez les exercices, vous pourrez être mis en
        relation, entre vous, pour vous éxercer avec une autre personne, dans le
        pur anonymat et avec des possibilités d'échanges, via le site, limités.
        Si vous arrivez à vous envoyer vos adresses mail par télépathie, vous
        n'aurez plus besoin de venir vous amusez ici.
    </p>
    <div style="text-align: right;">
        <input type="button" value="Suite" on:click={suite} />
    </div>
</div>

<style>
    .container {
        display: none;
        margin-top: 30px;
    }
</style>
