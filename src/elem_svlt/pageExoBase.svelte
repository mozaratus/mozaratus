<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    let leContainer, propal_div, _actionUser, propalData, leMsgDiv;
    let timerJour, timerMsg;

    export let champs = "";

    const dureeMessage = 1000;

    export function getNom() {
        return champs;
    }

    onMount(() => {});

    function toBd(data) {
        const _data =
            "action=sendDataPropal&data=" + data + "&champs=" + champs;

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
                if (result[0].id == 1) {
                    console.error("Result data : ", result);
                } else {
                    console.log("Result data : ", result);
                }
            });
    }

    export function cache(d = 0) {
        // possibilité d'annuler
        // timerJour = window.setTimeout(() => {
        //     propal_div.style.opacity = 0;
        //     //dispatch("nuit", {});
        // }, d);
    }
    export function jour() {
        window.clearTimeout(timerJour);
        // _actionUser.style.display = "block";
        // _actionUser.style.opacity = 1;
        propalData.focus();
        showHide(true);
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
</script>

<div class="container" bind:this={leContainer} on:mousemove={jour}>

    <!-- Entête -->
    <div class="propal" bind:this={propal_div} on:mousemove={jour}>
        <h4>Exercice <span class="champsTitre">{champs}</span></h4>
        <p class="propal">
            Prenez le temps que vous voulez pour percevoir l'information <br />
            <span class="champs">{champs}</span><br /> que vous envoie cette page.
        </p>
    </div>

    <!-- <hr /> -->

    <!-- Proposition -->
    <div class="actionUser" bind:this={_actionUser}>
        {#if champs == "nombre"}
            <input type="number" bind:this={propalData} on:focus={jour} />
        {:else}
            <input type="text" bind:this={propalData} on:focus={jour} />
        {/if}
        <input type="button" value="Proposition de {champs}" on:click={suite} />
    </div>
</div>

<style>
    .container {
        display: none;
        text-align: center;
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
        left:50%;
        transform: translate(-50%,-50%);
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
</style>
