<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";

    export let name;
    export let cacher;
    export let largeurTrait;

    let leTrait;
    let angle = 0;
    let leBtn;
    let trace_b = false;
    let valTrait = 0;

    switch (name) {
        case "h":
            angle = 0;
            valTrait = 1;
            break;
        case "b":
            angle = 0;
            valTrait = 4;
            break;
        case "d":
            angle = 90;
            valTrait = 2;
            break;
        case "g":
            angle = 90;
            valTrait = 8;
            break;
    }

    onMount(() => {
        if (cacher == "1") {
            leTrait.style.display = "none";
        }
        leTrait.style.width = largeurTrait + "px";

        if (name == "d") {
            leTrait.style.right = -(largeurTrait / 2) + "px";
        }
        if (name == "g") {
            leTrait.style.left = -(largeurTrait / 2) + "px";
        }

        // Btn transparent
        leBtn.style.width = largeurTrait + "px";
        leBtn.style.height = largeurTrait / 3 + "px";
        leBtn.style.top = -(largeurTrait / 6) + "px";
        // leBtn.style.left = -(largeurTrait/6) + "px";
    });

    const dispatch = createEventDispatcher();

    function eventClickTrait() {
        dispatch("recupValTrait", {
            trace_b: trace_b,
            name: name,
            valTrait: valTrait,
            leTrait: leTrait,
        });
    }

    // Appelée par updateVoisin de Case
    export function majTrait() {
        console.log("majTrait FX " + trace_b);
        updateTrait("#333");
    }

    // on click Trait
    function tournicote() {
        if (!trace_b) {
            updateTrait("#0F0");
            eventClickTrait();
        }
    }
    function updateTrait(color) {
        console.log("updateTrait color :" + color);
        if (!trace_b) {
            angle += 180;
            leTrait.style.backgroundColor = color;
            //  leTrait.style.transform = "rotate(" + angle + "deg)";
            leTrait.style.cursor = "default";
            //
            trace_b = true;
        }
    }
</script>

<div
    class="trait"
    class:h={name === "h"}
    class:d={name === "d"}
    class:b={name === "b"}
    class:g={name === "g"}
    bind:this={leTrait}
    on:click={tournicote}
>
    <div class="_btn" bind:this={leBtn} />
</div>

<style>
    .h {
        top: 0px;
        left: 50%;
        transform: rotate(0deg) translateX(-50%);
        /* background-color: #f00; */
    }
    .d {
        right: 0px;
        top: 50%;
        transform: rotate(90deg) translateY(-50%);
        /* background-color: #00f; */
    }
    .b {
        bottom: 0px;
        /* background-color: #0f0; */
        left: 50%;
        transform: rotate(0deg) translateX(-50%);
    }
    .g {
        left: 0;
        top: 50%;
        transform: rotate(90deg) translateY(-50%);
        /* background-color: #fff; */
    }
    .trait {
        background-color: #fff;
        position: absolute;
        z-index: 900;
        width: 33%;
        height: 4px;
        cursor: pointer;
        transition: all 0.1s linear;
        border-radius: 5px;
        /* margin: 5px;
        border: 1px solid #999; */
        /* background-color: beige; */
    }
    .trait:hover {
        background-color: #f00;
    }
    .trait ._btn {
        position: absolute;
        width: 0%;
        height: 200%;
        top: -50%;
        left: 0;
        background-color: rgba(255, 55, 255, 0.0000001);
        z-index: 1000;
    }
</style>
