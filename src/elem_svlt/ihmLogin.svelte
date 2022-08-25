<script>
    import { createEventDispatcher } from "svelte";
    
    const dispatch = createEventDispatcher();

    $: _nom="Invité";
    let showAdmin = false;
    let showLogin = false;


    function showHideLogin() {
        showLogin = !showLogin;
        dispatch("showFormLogin", {showLogin:showLogin});
    }

    function showHideAdmin() {
        showAdmin = !showAdmin;
        dispatch("showHideAdmin_event", { showAdmin: showAdmin});
    }

    export function setNom(nom) {
        _nom = nom;
    }
</script>

<div class="ihm">
    <img src="img/tete.png" width="30" height="30" alt="" class='admin' on:click={showHideAdmin} />
    <span class='nom'>{_nom}</span>
    <div class="pointe">
        <img src="img/tete.png" alt="" on:click={showHideLogin} />
    </div>
</div>

<style>
    .ihm {
        position: relative;
        margin-right: 0px;
        display: flex;
        width: 250px;
        padding: 5px;
        text-align: right;
        justify-content:flex-end;
        z-index: 10;
    }
    .ihm .nom {
        margin-right: 30px;
    }
    .ihm .pointe {
        width: 4px;
        height: 4px;
        position: relative;
        /* top: 0px;
        left: 40px;  */
    }
    .ihm .pointe:hover {
        /* margin-left: 10px; */
        cursor: pointer;
        animation: trembole 1.5s linear infinite;
    }
    .ihm .pointe img {
        position: absolute;
        top: 0px;
        width: 30px;
        left: -15px; /* image/2 */
    }

    img.admin {
        transform: rotate(180deg);
        margin-right: 10px;
    }

    @keyframes trembole {
        0% {
            transform: rotate(0deg);
        }
        25% {
            transform: rotate(-10deg);
        }
        50% {
            transform: rotate(0deg);
        }
        75% {
            transform: rotate(10deg);
        }
        100% {
            transform: rotate(0deg);
        }
    }
</style>
