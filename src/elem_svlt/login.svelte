<script>
    import { createEventDispatcher } from "svelte";

    let leContainer, email, pw, _forgotPw;

    function formHandler(event) {
        event.preventDefault();

        const _data = "action=login&pw=" + pw.value + "&email=" + email.value;

        fetch("http://tlpt.freelancetoulouse.com/php/getData.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },

            body: _data,
        })
            .then((response) => response.json())
            .then((result) => {
                console.log(
                    "Qui : " +
                        result[0].qui +
                        ": (error : " +
                        result[0].error +
                        ")"
                );
                if (result[0].error == "1") {
                    dispatch("modal", {
                        msg: result[0].msg,
                        todo: "envoie mot de passe",
                    });
                    // error
                    console.error("PBM : " + result[0].msg);
                    //_forgotPw.style.display = "block";
                } else {
                    // console.log("logué : " + result[0].nom);
                    dispatch("nomUser", { nom: result[0].nom });

                    // dispatch("modal", {
                    //     msg: "Ok : " + result[0].msg,
                    //     todo: "ok",
                    // });
                    dispatch("goToAccueil", {});
                    showHide(false);
                }
            });
        suite();
    }

    const dispatch = createEventDispatcher();

    function suite() {
        var validRegex =
            /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

        if (email.value.match(validRegex)) {
            dispatch("suiteMiniForm", {
                leContainer: leContainer,
                email: email.value,
            });
        } else {
            alert("Email Invalide");
        }
    }

    export function showHide(bool) {
        if (bool) {
            leContainer.style.display = "block";
        } else {
            leContainer.style.display = "none";
        }
    }

    function forgotPW() {
        // affiche fenêtre avec email pour envoie de lien pour réinitialiser le mot de passe.
        console.log("re-initialiser le mot de passe");

        const _data = "action=newPw&email=" + email.value;

        // 
        // http://tlpt.freelancetoulouse.com/php/getDataFT.php
        //fetch("http://tlpt.freelancetoulouse.com/php/getDataFT.php", {
        fetch("http://tlpt.freelancetoulouse.com/php/getData.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: _data,
        })
            .then((response) => response.json())
            .then((result) => {
                console.log(
                    "Qui : " +
                        result[0].qui +
                        ": (error : " +
                        result[0].error +
                        ")"
                );
                if (result[0].error == "1") {
                    // dispatch("modal", {
                    //     msg: result[0].msg,
                    //     todo: "envoie mot de passe",
                    // });
                } else {
                    let link= result[0].link;
                    sendMail(link)
                    // console.log("logué : " + result[0].nom);
                    // dispatch("nomUser", { nom: result[0].nom });
                    // dispatch("goToAccueil", {});
                    // showHide(false);
                }
            });
    }

    // send Mail
    function sendMail(link) {
        let _data = "action=sendMail&email=" + email.value+"&link="+link;
        // http://tlpt.freelancetoulouse.com/php/
        fetch("http://tlpt.freelancetoulouse.com/php/sendMail.php", {
            method: "post",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: _data,
        })
            .then((response) => response.json())
            .then((result) => {
                console.log(
                    "Qui : " +
                        result[0].qui +
                        ": (error : " +
                        result[0].error +
                        ")"
                );
                if (result[0].error == "1") {
                    // dispatch("modal", {
                    //     msg: result[0].msg,
                    //     todo: "envoie mot de passe",
                    // });
                } else {
                                        
                    // console.log("logué : " + result[0].nom);
                    // dispatch("nomUser", { nom: result[0].nom });
                    // dispatch("goToAccueil", {});
                    // showHide(false);
                }
            });
    }
</script>

<div class="container" bind:this={leContainer}>
    <div class="box">
        <h3>Login</h3>
        <form on:submit|preventDefault={formHandler}>
            <p>
                <label for="email">Email</label>
                <input type="email" value="toto@toto.fr" bind:this={email} />
            </p>
            <p>
                <label for="">Mot de passe</label>
                <input type="password" value="123" bind:this={pw} />
            </p>
            <p>
                <label for="" />
                <button on:click={formHandler}>Login</button>
            </p>
        </form>
        <p>
            <label for="" />
            <span class="link" bind:this={_forgotPw} on:click={forgotPW}
                >Mot de passe oublié</span
            >
        </p>
    </div>
</div>

<style>
    .container {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: hsla(0, 39%, 99%, 0.8);
        z-index: 10;
    }
    .container .box {
        margin-top: 30%;
        margin-left: 20%;
        width: 360px;
        padding: 10px;
        border-radius: 4px;
        background-color: rgba(238, 238, 238, 0.9);
        border: 1px solid #666;
        box-shadow: 3px 3px 5px 7px rgba(0, 0, 0, 0.3);
    }
    .link {
        display: none;
        color: rgb(22, 91, 231);
        cursor: pointer;
    }
    .link:hover {
        color: rgb(222, 91, 231);
    }
</style>
