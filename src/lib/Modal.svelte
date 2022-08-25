<script>
    import { createEventDispatcher, onDestroy } from "svelte";
    import { cubicInOut } from "svelte/easing";
    import { fadeScale } from "../js/outils.js";

    const dispatch = createEventDispatcher();
    const close = () => dispatch("close");

    let modal, _container;
    export let msgModal = "<b>C</b>oucou";

    const handle_keydown = (e) => {
        switch (e.key) {
            case "Escape":
            case "Enter":
            case "PadEnter":
                close();
                return;
        }
    };

    const previously_focused =
        typeof document !== "undefined" && document.activeElement;

    if (previously_focused) {
        onDestroy(() => {
            previously_focused.focus();
        });
    }

    export function hide() {
        _container.style.display = "none";
    }
    export function show(html) {
        _container.style.display = "block";
        msgModal = html;
    }
</script>

<svelte:window on:keydown={handle_keydown} />

<div class="container" bind:this={_container}>
    <div class="modal-background" on:click={close} />

    <div
        class="modal"
        role="dialog"
        aria-modal="true"
        bind:this={modal}
        transition:fadeScale={{
            delay: 250,
            duration: 1000,
            easing: cubicInOut,
            baseScale: 0.5,
        }}
    >
        <slot name="header" />
        <hr />
        {@html msgModal}
        <slot />
        <hr />

        <!-- svelte-ignore a11y-autofocus -->
        <div class="forButton">
            <input type="button" on:click={close} value="ok" />
        </div>
    </div>
</div>

<style>
    .container {
        display: none;
        position: absolute;
        z-index: 500000;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .modal-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
    }

    .modal {
        position: absolute;
        left: 50%;
        top: 50%;
        width: calc(100vw - 4em);
        max-width: 32em;
        max-height: calc(100vh - 4em);
        overflow: auto;
        transform: translate(-50%, -50%);
        padding: 1em;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: white;
        box-shadow: 5px 5px 10px 5px rgba(0, 0, 0, 0.4);
    }
    .forButton {
        padding-top: 30px;
        text-align: right;
    }
    hr {
        margin: 15px 0;
    }
</style>
