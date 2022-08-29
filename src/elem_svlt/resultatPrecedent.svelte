<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    const dispatch = createEventDispatcher();

    let leContainer;

    $: users = [];
    $: resultats = [];
    $: actifUser = {nom:"-"};

    onMount(() => {
        getResultats();
    });



    function getResultats() {
        // alert("getResultats " + userId);
        
        dispatch("showAttente", { bool: true });
        
        const _data = "action=getLeUserResult";

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
                dispatch("showAttente", { bool: false });
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


</script>

<div class="container" bind:this={leContainer}>
        <table>
            <tr>
                <th>Date</th>
                <th>Couleur</th>
                <th>Forme</th>
                <th>Nombre</th>
                <th>C-IA</th>
                <th>F-IA</th>
                <th>N-IA</th>
            </tr>
            {#each resultats as res}
                <tr class='data'>
                    <td>{res.date}</td>
                    <td>{res.couleur}</td>
                    <td>{res.forme}</td>
                    <td>{res.nombre}</td>
                    <td>{res.cia}</td>
                    <td>{res.fia}</td>
                    <td>{res.nia}</td>
                </tr>
            {/each}
        </table>
</div>

<style>
    .container {
        /* display: none; */
        /* z-index: 5000; */
    }

    table {
        border-collapse: collapse;
    }
    td { 
        padding: 2px 5px;
    }
    tr.data:nth-child(odd) {
        background-color: rgb(187, 187, 187);
    }
    tr.data:nth-child(even) {
        background-color: rgb(233, 226, 178);
    }

</style>
