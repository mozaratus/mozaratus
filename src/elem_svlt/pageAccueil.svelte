<script>
    import { createEventDispatcher } from "svelte";

    let leContainer;
    const nomSvt='accueil';

    export function getNom() {
        return nomSvt;
    }

    const dispatch = createEventDispatcher();
    function suite() {
        dispatch("suiteAccueil", { leContainer: leContainer });
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
    <h4>Accueil</h4>

    Ce site, un peu fou, faut l'dire, à la prétention ou le souhait d'aider tout
    un chacun à utiliser la télépathie.
    Ce moyen de communication silencieux qui met en relation deux (au moins) : <br />
    - une personne qui envoie une information et
    <br />- une autre personne qui reçoit cette information.<br />
    <br />
    Vous n'apprenez rien pour l'instant. Mais le point de vue importe car il n'y a pas d'histoire de don qu'auraient certaines
    personnes et pas d'autres. Que certains aient plus de facilités que d'autres,
    c'est certains, mais c'est en nous tous. Tous ! Et évidemment c'est comme Tout,
    il faut <b>pratiquer pour s'améliorer</b>. Et réussire à faire confiance en nos perceptions.
    <br />
    <br /> Je vais donc vous envoyer, dans un premier temps, une couleur. Et il
    vous sera demandé de percevoir (on perce et ensuite on voit) cette couleur
    et de noter ce que vous avez perçu.
    <br />Puis il y aura deux autres pages du même style, une avec des formes
    simples et une avec un chiffre compris entre 0 et 100.<br /><br />
   
    <div style='text-align: right;'><input type="button" value="Suite" on:click={suite} /></div>
</div>
