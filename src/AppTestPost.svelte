<script>
    import PartieInscription from "./elem_svlt/partieInscription.svelte";

    import { testsStore } from "./js/store.js";

    let todos = [];

    const unsubscribe = testsStore.subscribe((data) => {
        console.log(data);
        todos = data;
    });

    let action = "total";
    let result = null;

    async function doPost() {
        const res = await fetch(
            "http://localhost/htdocs/2022/telepathons/src/php/recup.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ action: action }),
            }
        );

        const json = await res.json();
        result = json[0].msg; //JSON.stringify(json)
    }
</script>

<main>
    <input bind:value={action} />
    <button type="button" on:click={doPost}> Post it. </button>
    <p>Result :</p>
    <p>{result}</p>

    <!-- <h1>Todos:</h1>
    {#each todos as item}
        <p>{item.title}</p>
    {/each} -->

    <h1>Télépathons</h1>
    <PartieInscription />
</main>

<style>
    main {
        padding: 10px;
        width: 90%;
        margin: 10px auto;
        background-color: #ddd;
        border-radius: 10px;
    }
</style>
