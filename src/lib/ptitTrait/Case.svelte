<script>
    import { createEventDispatcher } from "svelte";
    import { onMount } from "svelte";
    import Trait from "./Trait.svelte";

    export let posTop = 0,
        posRight = 0,
        posBottom = 0,
        posLeft = 0,
        ligne,
        colonne,
        largeurCase,
        user;

    let valCase = 0;
    let laCase;

    let largeurTrait = largeurCase * 0.75;

    let leTrait_H, leTrait_D, leTrait_B, leTrait_G;
    let lesVals = {
        h: 1,
        d: 2,
        b: 4,
        g: 8,
    };

    const dispatch = createEventDispatcher();

    //

    onMount(() => {
        laCase.style.height = largeurCase + "px";
        laCase.style.width = largeurCase + "px";
        // window.setTimeout(() => {
        // }, 5000)
    });

    // event après le clic sur un trait.
    function sayHello(nameTrait) {
        dispatch("recupValCase", {
            nameTrait: nameTrait,
            valCase: valCase,
            ligne: ligne,
            colonne: colonne,
            nameTrait: nameTrait,
            posTop: posTop,
            posRight: posRight,
            posLeft: posLeft,
            posBottom: posBottom,
        });
    }

    function recupValTrait_fx(event) {
        console.log("recupValTrait_fx() " + event.detail.trace_b);

        if (event.detail.trace_b) {
            valCase += event.detail.valTrait;
            caseFinie();
        }
        console.log("valCase " + valCase + " lc : ", ligne, colonne);

        sayHello(event.detail.name);
    }

    function caseFinie() {
        // case finie ?
        if (valCase == 15) {
            if (user == "PC") {
                laCase.querySelector(".fond").style.backgroundColor = "#0f0";
            } else {
                laCase.querySelector(".fond").style.backgroundColor = "#00f";
            }
        }
    }

    export function updateVoisin(nameModif) {
        console.log("updateVoisin : ", nameModif, ligne, colonne);
        switch (nameModif) {
            case "h":
                leTrait_H.majTrait();
                break;
            case "d":
                leTrait_D.majTrait();
                break;
            case "b":
                leTrait_B.majTrait();
                break;
            case "g":
                leTrait_G.majTrait();
                break;
            default:
                break;
        }
        valCase += lesVals[nameModif];
        caseFinie();
    }

    /* 
            1
        8       2
            4
    */

    export function getDatas() {
        return {
            ligne: ligne,
            colonne: colonne,
            posLeft: posLeft,
            posRight: posRight,
            posTop: posTop,
            posBottom: posBottom,
        };
    }

    export function coche(nomTrait) {
        console.log("coche() nomTrait : " + nomTrait + ", valCase " + valCase);
        switch (nomTrait) {
            case "h": // 1
                console.log("H");
                if (
                    valCase != 1 &&
                    valCase != 3 &&
                    valCase != 5 &&
                    valCase != 7 &&
                    valCase != 9 &&
                    valCase != 11 &&
                    valCase != 13 &&
                    valCase != 15
                ) {
                    leTrait_H.majTrait();
                    valCase += lesVals[nomTrait];
                    caseFinie();
                    return true;
                }
                break;
            case "d": // 2
                console.log("D");
                if (
                    valCase != 2 &&
                    valCase != 3 &&
                    valCase != 6 &&
                    valCase != 7 &&
                    valCase != 10 &&
                    valCase != 11 &&
                    valCase != 14 &&
                    valCase != 15
                ) {
                    leTrait_D.majTrait();
                    valCase += lesVals[nomTrait];
                    caseFinie();
                    return true;
                }
                break;
            case "b": // 4
                // console.log("B");
                if (
                    valCase != 4 &&
                    valCase != 5 &&
                    valCase != 6 &&
                    valCase != 7 &&
                    valCase != 12 &&
                    valCase != 13 &&
                    valCase != 14 &&
                    valCase != 15
                ) {
                    leTrait_B.majTrait();
                    valCase += lesVals[nomTrait];
                    caseFinie();
                    return true;
                }
                break;
            case "g": // 8
                // console.log("G");
                if (
                    valCase != 8 &&
                    valCase != 9 &&
                    valCase != 10 &&
                    valCase != 11 &&
                    valCase != 12 &&
                    valCase != 13 &&
                    valCase != 14 &&
                    valCase != 15
                ) {
                    leTrait_G.majTrait();
                    valCase += lesVals[nomTrait];
                    caseFinie();
                    return true;
                }
                break;
            default:
                console.error("??? nomTrait : " + nomTrait);
                break;
        }

        console.log("----  Trait déjà fait ?!");
        return false;
    }

    export function allume(color) {
        laCase.style.backgroundColor = color;
        window.setTimeout(() => {
            laCase.style.backgroundColor = "transparent"; //"rgba(255, 255, 255,0.3)";
        }, 500);
    }
</script>

<div class="case" bind:this={laCase}>
    <Trait
        cacher={posTop == 1 ? 0 : 1}
        bind:this={leTrait_H}
        {largeurTrait}
        name="h"
        on:recupValTrait={recupValTrait_fx}
    />
    <Trait
        cacher={posRight == 1 ? 0 : 1}
        bind:this={leTrait_D}
        {largeurTrait}
        name="d"
        on:recupValTrait={recupValTrait_fx}
    />
    <Trait
        cacher="0"
        bind:this={leTrait_B}
        {largeurTrait}
        name="b"
        on:recupValTrait={recupValTrait_fx}
    />
    <Trait
        cacher="0"
        bind:this={leTrait_G}
        name="g"
        {largeurTrait}
        on:recupValTrait={recupValTrait_fx}
    />

    <span class="valCase">{valCase}</span>
    <span class="fond" />
</div>

<style>
    .case {
        display: inline-block;
        position: relative;
        width: 10px;
        height: 10px;
        /* background-color: rgba(255, 255, 255, 0.4);
        border: 1px solid #fff; */
    }

    .case .fond {
        display: inline-block;
        position: absolute;
        width: 80%;
        height: 80%;
        left: 10%;
        top: 10%;
        border-radius: 2%;
        /* background-color: rgba(255, 255, 55, 0.3); */
    }

    .case .valCase {
        display:none;
        position: absolute;
        left: 10%;
        top: 15%;
        font-size: 0.7em;
        line-height: 0;
    }
</style>
