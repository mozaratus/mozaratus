<script>
    // !!! mettre à jour les CB au login

    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    const dispatch = createEventDispatcher();

    let _topMenu;

    $: exos = [
        { nom: "Couleur", result: false },
        { nom: "Forme", result: true },
        { nom: "Nombre", result: false },
    ];

    function exo_fx(num) {
        dispatch("showExo", { exos: exos[num] });
    }

    function accueil_fx() {
        dispatch("showAccueil", {});
    }
    function info_fx() {
        dispatch("showInfos", {});
    }
</script>

<nav class="topMenu" bind:this={_topMenu}>
    <ul>
        <li on:click={() => accueil_fx()}><span>Accueil</span></li>
        <li class="over">
            Exercices
            <ul>
                {#each exos as exo, i}
                    <li on:click={() => exo_fx(i)}>
                        <span>
                            <input
                                disabled
                                type="checkbox"
                                id="exoCb_{i.nom}"
                                bind:checked={exo.result}
                            />&nbsp;
                            {exo.nom}
                        </span>
                        <!--  -->
                    </li>
                {/each}
            </ul>
        </li>
        <li on:click={() => info_fx()}><span>Infos</span></li>
    </ul>
</nav>

<style>
    :root {
        --color-survol: #080;
    }
    nav.topMenu {
        position: absolute;
        z-index: 100;
        top: 18px;
        left: -45px;
    }
    li {
        list-style-type: none;
        cursor: pointer;
    }
    nav.topMenu > ul > li {
        margin: 5px;
    }
    nav.topMenu > ul > li:hover {
        color: var(--color-survol);
    }
    nav.topMenu > ul > li:hover > ul > li {
        color: #000;
    }
    nav.topMenu > ul {
        display: flex;
    }
    ul > li > span {
        display: inline-block;
        padding: 5px;
        background-color: rgba(255, 255, 255, 0.9);
    }
    nav.topMenu > ul > li {
        display: inline-block;
    }
    nav.topMenu > ul > li.over {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 5px;
        width: 100px;
    }
    nav.topMenu > ul > li.over > ul > li > span {
        background-color: rgba(255, 255, 255, 0);
    }
    nav.topMenu > ul > li.over ul {
        padding: 0;
        display: none;
    }
    nav.topMenu > ul > li.over ul li:hover {
        /* background-color: rgba(55, 55, 55, 0.4); */
        color: var(--color-survol);
    }

    nav.topMenu > ul > li.over:hover ul {
        display: block;
    }
</style>
