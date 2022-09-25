<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    export let champs = "";
    export let chx;

    let leContainer, propalData;


    export function getNom() {
        return champs;
    }

    onMount(() => {});

    function toBd(data) {
        dispatch("showAttente", { bool: true });
        const _data =
            "action=sendDataPropal&data=" + data + "&champs=" + champs;

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
                if (result[0].id == 1) {
                    console.error("Result data : ", result);
                } else {
                    console.log("Result data : ", result);
                }
                dispatch("showAttente", { bool: false });
            });
    }

    const dispatch = createEventDispatcher();

    function suite() {
        // console.log("Data : " + propalData.value);
        if (propalData.value) {
            toBd(propalData.value);
            //afficheMessage();
            timerMsg = window.setTimeout(() => {
                leContainer.style.display = "none";
                dispatch("suiteExo", { Data: propalData.value });
            }, 50);
        }
    }

    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
            //dispatch("jour", {});
        } else {
            leContainer.style.display = "none";
            //cache(5000);
        }
    }
    let __couleurs = [
        "rouge",
        "vert",
        "bleu",
        "orange",
        "violet",
        "jaune",
        "rose",
    ];
    let __formes = [
        "carré",
        "cercle",
        "cube",
        "boule",
        "étoile",
        "triangle",
        "rectangle",
    ];
</script>

<div class="container" bind:this={leContainer}>
    <span class="totroove chop_{chx}">{chx}</span>
    <!-- Entête -->
    <div class="valReference">
        Au choix :
        {#if champs == "couleur"}
            {#each __couleurs as ccc}
                <span>{ccc} </span>
            {/each}
        {/if}
        {#if champs == "forme"}
            {#each __formes as ccc}
                <span>{ccc} </span>
            {/each}
        {/if}
        {#if champs == "nombre"}
            <span>Nombre compris entre 0 et 100</span>
        {/if}
    </div>

    <div class="propal">
        <h4>Exercice <span class="champsTitre">{champs}</span></h4>
        <p class="propal">
            Prenez le temps que vous voulez pour percevoir l'information <br />
            <span class="totroove chop_{chx}">{chx}</span><span class="champs"
                >{champs}</span
            ><span class="totroove chop_{chx}">{chx}</span><br /> que vous envoie
            cette page.
        </p>
    </div>

    <!-- <hr /> -->

    <!-- Proposition -->
    <div class="actionUser">
        {#if champs == "nombre"}
            <input type="number" bind:this={propalData} />
        {:else}
            <input type="text" bind:this={propalData} />
        {/if}
        <input type="button" value="Proposition de {champs}" on:click={suite} />
    </div>
</div>

<style>
    .container {
        display: none;
        text-align: center;
        margin-top: 30px;
        /* position: relative; */
        /* background-data: antiquewhite; */
    }
    .actionUser {
        transition: opacity 2s ease;
        position: absolute;
        bottom: 34px;
        right: 30px;
        text-align: center;
    }
    div.propal {
        opacity: 1;
        transition: opacity 2s ease;
        text-align: center;
    }
    p.propal {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .propal p .champs {
        line-height: 2em;
        font-size: 2em;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .champsTitre::first-letter {
        font-size: 1.83em;
    }
    .totroove {
        opacity: 0;
    }
</style>
