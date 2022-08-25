<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    let leContainer, propal_div, _actionUser, propalCouleur;

    onMount(() => {});

    function toBd(color) {
        const _data = "action=sendColor&color=" + color;

        fetch("http://tlpt.freelancetoulouse.com/php/getData.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },

            body: _data,
        })
            .then((response) => response.json())
                        .then((result) => {
                console.log("Qui : " + result[0].qui);
                console.log("Result color : ", result);
            });
    }

    export function cache(d = 0) {
        window.setTimeout(() => {
            propal_div.style.opacity = 0;
            dispatch("nuit", {});
        }, d);
    }
    export function jour() {
        _actionUser.style.display = "block";
        _actionUser.style.opacity = 1;
        showHide(true);
    }

    const dispatch = createEventDispatcher();

    function suite() {
        console.log("Couleur : " + propalCouleur.value);
        if (propalCouleur.value) {
            toBd(propalCouleur.value);
            dispatch("suiteExoCouleur", { couleur: propalCouleur.value });
        }
    }

    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
            dispatch("jour", {});
        } else {
            leContainer.style.display = "none";
            cache(5000);
        }
    }
</script>

<div class="container" bind:this={leContainer} on:mousemove={jour}>
    <div class="actionUser" bind:this={_actionUser}>
        <input type="text" bind:this={propalCouleur} />
        <input type="button" value="Proposition de couleur" on:click={suite} />
    </div>

    <div class="propal" bind:this={propal_div}>
        <h4>Exercices Couleurs</h4>

        <p>
            Prenez le temps que vous voulez pour percevoir la couleur que vous
            envoie cette page.
        </p>
    </div>
</div>

<style>
    .container {
        display: none;
        /* background-color: antiquewhite; */
    }
    .actionUser {
        opacity: 0;
        display: none;
        transition: opacity 2s ease;
    }
    .propal {
        opacity: 1;
        transition: opacity 4s ease;
    }
</style>
