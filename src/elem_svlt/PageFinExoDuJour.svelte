<script>
    // import { createEventDispatcher } from "svelte";

    let leContainer;
    const nomSvt='finExo';

    // const dispatch = createEventDispatcher();


    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
        } else {
            leContainer.style.display = "none";
        }
    }
</script>

<div class="container" bind:this={leContainer}>
    <h4>Fin des exercies du jour.</h4>
</div>

<style>
.container {
    display: none;
    margin-top: 30px;
}
</style>