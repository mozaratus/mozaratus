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
                console.log("Result user : ", result);
                users = result;
            });
    }

    //    const dispatch = createEventDispatcher();
    //         dispatch("suiteExoCouleur", { couleur: propalCouleur.value });

    function getResultats(userId) {
        // alert("getResultats " + userId);
        const _data = "action=getResultats&idUser=" + userId;

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
                console.log("Resultata updateBDDResultat : ", result);
            });
    }
</script>

<div class="container" bind:this={leContainer}>
    <div class="admini">
        <table>
            <tr><th>ID</th><th>Nom</th><th>Email</th></tr>
            {#each users as user}
                <tr on:click={getResultats(user.id)}>
                    <td>{user.id}</td><td>{user.nom}</td><td>{user.email}</td>
                </tr>
            {/each}
        </table>
        <hr />
        <table>
            <tr
                ><th>ID</th><th>Date</th><th>Couleur</th><th>R-C</th><th
                    >Forme</th
                ><th>R-F</th><th>Nombre</th><th>R-N</th></tr
            >
            {#each resultats as res, i}
                <!--  id`, `date`, `userId`, `couleur`, `rcouleur`, `forme`, `rforme`, `nombre`, `rnombre -->
                <tr>
                    <td>{res.id}</td>
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
        top: 10%;
        left: 5%;
        width: 90%;
        height: 90%;
        background-color: white;
    }
</style>
