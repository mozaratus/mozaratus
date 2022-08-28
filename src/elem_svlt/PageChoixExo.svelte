<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    let leContainer, _forme, _nombre, _couleur;

    let champsExoFaits = {
        couleur: false,
        nombre: false,
        forme: false,
    };

    onMount(() => {
        //console.log("exos ", champsExo);
    });

    const dispatch = createEventDispatcher();

    function suite() {
        dispatch("suiteExo", { Data: propalData.value });
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

    export function disabledBtn(qui) {
        switch (qui) {
            case "couleur":
                champsExoFaits.couleur = true;
                _couleur.style.backgroundColor = "#000";
                _couleur.style.cursor = "default";
                break;
            case "forme":
                champsExoFaits.forme = true;
                _forme.style.backgroundColor = "#000";
                _forme.style.cursor = "default";
                break;
            case "nombre":
                champsExoFaits.nombre = true;
                _nombre.style.backgroundColor = "#000";
                _nombre.style.cursor = "default";
                break;
            default:
                break;
        }
    }

    function couleur_fx() {
        if (!champsExoFaits.couleur) dispatch("showExo", { exo: "couleur" });
    }
    function forme_fx() {
        if (!champsExoFaits.forme) dispatch("showExo", { exo: "forme" });
    }
    function nombre_fx() {
        if (!champsExoFaits.nombre) dispatch("showExo", { exo: "nombre" });
    }
</script>

<div class="container" bind:this={leContainer}>
    <p>Gris : A faire | Noire : A vérifier | Vert : OK | Rouge : Erreur</p>
    <br /><br />
    <span class="cercle couleur" bind:this={_couleur} on:click={couleur_fx}
        >Couleur</span
    >
    <br />
    <span class="cercle forme" bind:this={_forme} on:click={forme_fx}
        >Forme</span
    >
    <span class="cercle nombre" bind:this={_nombre} on:click={nombre_fx}
        >Nombre</span
    >
</div>

<style>
    .container {
        display: none;
        text-align: center;
        margin-top: 30px;
        /* position: relative; */
        /* background-data: antiquewhite; */
    }
    .cercle {
        display: inline-block;
        margin: 50px;
        width: 100px;
        height: 56px;
        background-color: #888;
        color:white;
        border-radius: 50%;
        text-align: center;
        padding-top: 39px;
    }
    .cercle:hover {
        background-color: #8f8;
    }
</style>
