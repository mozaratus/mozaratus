<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    let leContainer, propalCouleur;

    $: users = [];
    $: resultats = [];

    onMount(() => {
        chopUser();
    });

    function chopUser() {
        const _data = "action=getUser";

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
                console.log("Result user : ", result);
                users = result;
            });
    }

    //    const dispatch = createEventDispatcher();
    //         dispatch("suiteExoCouleur", { couleur: propalCouleur.value });

    function getResultats(userId) {
        // alert("getResultats " + userId);
        const _data = "action=getResultats&idUser=" + userId;

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
                console.log("Resultata : ", result);

                resultats = result;
            });
    }

    export function showHide(bool) {
        if (bool) {
            chopUser();
            leContainer.style.display = "block";
        } else {
            leContainer.style.display = "none";
        }
    }

    function changeCB(num, idResultat) {
        updateBDDResultat(
            idResultat,
            resultats[num].rcouleur,
            resultats[num].rforme,
            resultats[num].rnombre
        );
    }

    function updateBDDResultat(idResultat, rc, rf, rn) {
        const _data =
            "action=updateBDDResultat&idResultat=" +
            idResultat +
            "&rc=" +
            rc +
            "&rf=" +
            rf +
            "&rn=" +
            rn;

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
                console.log("Resultata updateBDDResultat : ", result);
            });
    }
</script>

<div class="container" bind:this={leContainer}>
    <div class="admini">
        <table>
            <tr><th>Nom</th><th>Email</th></tr>
            {#each users as user}
                <tr class='data' on:click={getResultats(user.id)}>
                    <td>{user.nom}</td><td>{user.email}</td>
                </tr>
            {/each}
        </table>
        <hr />
        <table>
            <tr
                ><th>Date</th><th>Couleur</th><th>?C</th><th
                    >Forme</th
                ><th>?F</th><th>Nombre</th><th>?N</th><th>Cia</th><th>Fia</th><th>Nia</th></tr
            >
            {#each resultats as res, i}
                <!--  id`, `date`, `userId`, `couleur`, `rcouleur`, `forme`, `rforme`, `nombre`, `rnombre, cia, fia, nia -->
                <tr class='data'>
                    <!-- <td>{res.id}</td> -->
                    <td>{res.date}</td>
                    <td>{res.couleur}</td>
                    <td
                        ><input
                            type="checkbox"
                            id="couleur_{res.id}"
                            bind:checked={res.rcouleur}
                            on:change={changeCB(i, res.id)}
                        /></td
                    >
                    <td>{res.forme}</td>
                    <td
                        ><input
                            type="checkbox"
                            id="forme_{res.id}"
                            bind:checked={res.rforme}
                            on:change={changeCB(i, res.id)}
                        /></td
                    >
                    <td>{res.nombre}</td>
                    <td
                        ><input
                            type="checkbox"
                            id="nombre_{res.id}"
                            bind:checked={res.rnombre}
                            on:change={changeCB(i, res.id)}
                        /></td
                    >
                    <td>{res.cia}</td>
                    <td>{res.fia}</td>
                    <td>{res.nia}</td>
                </tr>
            {/each}
        </table>
    </div>
</div>

<style>
    .container {
        display: none;
        z-index: 5000;
    }

    .admini {
        position: absolute;
        top: 15%;
        left: 5%;
        width: 90%;
        height: 75%;
        background-color: white;
    }

    table {
        border-collapse: collapse;
    }
    td { 
        padding: 2px 5px;
    }
    tr.data:nth-child(odd) {
        background-color: #e5e;
    }
    tr.data:nth-child(even) {
        background-color: #5e5;
    }

</style>
