

let rondRef = document.querySelector(".rondRef");
let voyant = document.querySelector(".result .voyant");
let msg = document.querySelector(".result .msg");
//
let sonExo = document.querySelector("#pocTop");
let sonTic = document.querySelector("#ch");
//
let nbPastilleUser = 0;

//
let now;
let delaiRythme = 200;
let start_b = false;
let timer_id;

let nbClick = 0;
let result = [];
result[0] = 0;
result[1] = 0;
result[2] = 0;

let horloge = {};

function start() {
    if (!start_b) {

        // horloge = new Horloge("canvas", { nbPastille: 8, cadence: [0, 2, 4, 6] }, son);
        // horloge = new Horloge("canvas", { nbPastille: 8, cadence: [0, 3, 5, 6] }, sonExo, sonTic);
        horloge = new Horloge("canvas", { nbPastille: 12, cadence: [0, 3, 5, 6, 7, 8, 10] }, sonExo, sonTic);
        // horloge.dessine();
        horloge.animSuivant();

        // rondRef.classList.remove('animOff');
        timer_id = window.setInterval(() => {
            horloge.animSuivant();
            // console.log("now : " + now);
            // document.body.style.backgroundColor = "#000";
            // son.play();

            // now = Date.now();
            // window.setTimeout(() => {
            //     document.body.style.backgroundColor = "#fff";
            // }, delaiRythme / 5)
        }, delaiRythme);
    } else {
        window.clearInterval(timer_id);
        nbClick = 0;
        msg.innerHTML = "";
    }

    start_b = !start_b;
}

function genereCadence() {
    window.clearInterval(timer_id);

    //
    // lit les CB
    let checkboxes = document.getElementsByName("rythme_cb");
    // console.log(checkboxes);
    cadences_cb = [];
    for (let i = 0; i < checkboxes.length; i++) {

        if (checkboxes[i].checked) {
            console.log("i : " + i);
            cadences_cb.push(i);
        }
    }
    //
    horloge = new Horloge("canvas", { nbPastille: nbPastilleUser, cadence: cadences_cb }, sonExo, sonTic);
    horloge.animSuivant();

    // rondRef.classList.remove('animOff');
    timer_id = window.setInterval(() => {
        horloge.animSuivant();
    }, delaiRythme);
}

function nbPastilles(nb) {
    nbPastilleUser = nb;
    // génère CB
    let cbs = document.querySelector('#lesCb');
    let html = "";
    for (let i = 0; i < nb; i++) {
        html += '<input type="checkbox" name="rythme_cb" value="" onclick="genereCadence()"/>';
    }
    html += '<input type="button" value="check"  />';

    cbs.innerHTML = html;

}


function clickUser() {
    nbClick++;
    let d = Date.now() - now;
    console.log(d);
    if (d < 200 || d > delaiRythme - 50 && d < delaiRythme + 50) {
        result[0]++;
        voyant.style.backgroundColor = "#0f0";
    } else if (d < 400 || d > delaiRythme - 150 && d < delaiRythme + 150) {
        result[1]++;
        voyant.style.backgroundColor = "orange";
    } else {
        result[2]++
        voyant.style.backgroundColor = "#800";
    }

    if (nbClick >= 20) {
        let t = result[2] + " faute grave sur /20 <br>";
        t += result[1] + " faute pas trop grave sur /20 <br>";
        t += result[0] + " TB sur /20 <br>";
        msg.innerHTML = t;

        window.clearInterval(timer_id);
        start_b = false;
    }
}
