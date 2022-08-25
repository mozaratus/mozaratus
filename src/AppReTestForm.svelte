<script>
    // npm i svelte-forms

    import PartieInscription from "./elem_svlt/partieInscription.svelte";

    $: _result = "";

    // function formHandler(event) {
    //     event.preventDefault();

    //     fetch("http://localhost/htdocs/2022/telepathons/src/php/recup.php", {
    //         method: "POST",
    //         headers: {
    //             "Content-Type": "application/json",
    //         },
    //         body: JSON.stringify({
    //             user_id: userId,
    //             title: title,
    //             body: body,
    //         }),
    //     })
    //         .then((response) => response.json())
    //                     .then((result) => {
                console.log("Qui : " + result[0].qui);
    //             console.log(result);
    //             _result = result[0].msg;
    //         });
    // }

    // function isRequired(value) {
    //     return value != null && value !== "";
    // }

    // const formHandler = (event) => {
    //     const formData = new FormData(event.target);

    //     let error = false;

    //     for (let field of formData) {
    //         const [key, value] = field;
    //         if (!isRequired(value)) {
    //             alert("Field " + key + " is required");
    //             error = true;
    //         }
    //     }

    //     if (!error) {
    //         alert("You submit the form.");
    //     }
    // };
    // const formHandler = () => alert('You submit the Form.');

    const postData = {
        userId: "1",
        title: "Title",
        body: "000",
    };

    function formHandler(event) {
        event.preventDefault();

        fetch("http://localhost/htdocs/2022/telepathons/src/php/recup.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                user_id: userId,
                title: title,
                body: body,
            }),
        })
            .then((response) => response.json())
            .then((result) => console.log(result));
    }
</script>

<main>X
    <p>{_result}</p>
    <form on:submit|preventDefault={formHandler}>
        <div>
            <label for="userId">UserID:</label>
            <input type="text" id="userId" bind:value={postData.userId} />
        </div>
        <div>
            <label for="title">Title: </label>
            <input type="text" id="title" bind:value={postData.title} />
        </div>
        <div>
            <label for="body">Body: </label>
            <textarea id="body" rows="6" cols="22" bind:value={postData.body} />
        </div>
        <button on:click={formHandler}>Create Post</button>

        <!-- <label for="first_name"> <strong> First Name </strong> </label>
        <input id="first_name" name="first_name" type="text" value="Houpla"  />

        <label for="last_name"> <strong> Last Name </strong> </label>
        <input id="last_name" name="last_name" type="text" value="Bob" />

        <label for="email"> <strong> Email ID </strong> </label>
        <input id="email" name="email" type="email" value="toto@toto.Fr" /> -->
<!-- 
        <br />
        <button type="submit">Save</button> -->
    </form>

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
