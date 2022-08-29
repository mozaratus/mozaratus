<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    import ResultatPrecedent from "./resultatPrecedent.svelte";

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

    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
            //dispatch("jour", {});
        } else {
            leContainer.style.display = "none";
            //cache(5000);
        }
    }

    export function allumeBtn(qui, couleur) {
        console.log("allumeBtn", qui, couleur);
        disabledBtn(qui, couleur);
    }

    export function disabledBtn(qui, color = "#000") {
        console.log("disabledBtn", qui, color);
        switch (qui) {
            case "couleur":
                champsExoFaits.couleur = true;
                _couleur.style.backgroundColor = color;
                _couleur.style.cursor = "default";
                break;
            case "forme":
                champsExoFaits.forme = true;
                _forme.style.backgroundColor = color;
                _forme.style.cursor = "default";
                break;
            case "nombre":
                champsExoFaits.nombre = true;
                _nombre.style.backgroundColor = color;
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

<ResultatPrecedent />

<div class="container" bind:this={leContainer}>
    <div class="legend">
        <table>
            <tr><td class="gris">Gris :</td><td>A faire</td></tr>
            <tr><td class="noir">Noir :</td><td>A vérifier</td></tr>
            <tr><td class="vert">Vert :</td><td>OK</td></tr>
            <tr><td class="rouge">Rouge :</td><td>Erreur</td></tr>
        </table>
    </div>
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
        /* margin-top: 30px; */
        position: relative;
        /* background-data: antiquewhite; */
    }
    .legend {
        position: absolute;
        top: 100px;
        left: 0px;
    }
    td{
        text-align: left;
    }
    td.vert, td.rouge, td.noir, td.gris {
        padding: 5px;
        text-align: right;
        color:#fff;
    }
    .vert {
        background-color: #080;
    }

    .gris {
        background-color: #999;
    }
    .noir {
        background-color: #000;
    }
    .rouge {
        background-color: #800;
    }

    .cercle {
        cursor: pointer;
        display: inline-block;
        margin: 50px;
        width: 100px;
        height: 56px;
        background-color: #888;
        color: white;
        border-radius: 50%;
        text-align: center;
        padding-top: 39px;
    }
    .cercle:hover {
        background-color: #555;
    }
</style>
