

let rondRef = document.querySelector(".rondRef");
let resultats_div = document.querySelector(".result");
let voyant = document.querySelector(".voyant");
let msg = document.querySelector(".result .msg");
//
let sonExo = document.querySelector("#pocTop");
let sonTic = document.querySelector("#ch");

sonTic.volume = document.querySelector("#vol1_range").value/100;
document.querySelector("#vol1_range").nextSibling.innerHTML = sonTic.volume * 100;

sonExo.volume = document.querySelector("#vol2_range").value/100;
document.querySelector("#vol2_range").nextSibling.innerHTML = sonExo.volume * 100;

// console.log(speed_range)
document.querySelector(".speed_range");
//
let nbPastilleUser = 8;
let cadences_cb = [0, 2, 4, 6];
let rangSpeed_b = false;
let rangVol1_b = false;
let rangVol2_b = false;
//
let now;
let delaiRythme = bpm(document.querySelector("#speed_range").value);
document.querySelector("#speed_range").nextSibling.innerHTML = document.querySelector("#speed_range").value + "BPM";

let start_b = false;
let timer_id;

let nbClick = 0;
let result = [];
result[0] = 0;
result[1] = 0;
result[2] = 0;
result[3] = 0;

let horloge = {};

// ms
let delaiPastille = 0;
let lesDelais = [];



window.onkeydown = function (e) {
    // console.log('E : ', e);
    if (e.code == 'Space' && start_b) {
        checkUser();
    }
    if (e.code == 'Enter') {
        init_cb();
        document.querySelector(".hello").style.display = 'none';
    }
}


function start() {
    if (!start_b) {
        horloge = new Horloge("canvas", { nbPastille: nbPastilleUser, cadence: cadences_cb }, sonExo, sonTic);
        delaiPastille = horloge.animSuivant();

        timer_id = window.setInterval(() => {
            delaiPastille = horloge.animSuivant();
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
            //console.log("i : " + i);
            cadences_cb.push(i);
        }
    }
    //
    horloge = new Horloge("canvas", { nbPastille: nbPastilleUser, cadence: cadences_cb }, sonExo, sonTic);
    delaiPastille = horloge.animSuivant();
    // console.log("T0 :: " + delaiPastille);
    // rondRef.classList.remove('animOff');
    timer_id = window.setInterval(() => {
        delaiPastille = horloge.animSuivant();
        // console.log("T0 :: " + delaiPastille);
    }, delaiRythme);

    start_b = true;

}

function nbPastilles(nb) {
    nbPastilleUser = nb;
    // génère CB
    let cbs = document.querySelector('#lesCb');
    let html = "<div class='lesCb'>";
    for (let i = 0; i < nb; i++) {
        html += '<label for="r_cb' + i + '">' + (i + 1) + '<br><input type="checkbox" id="r_cb' + i + '" name="rythme_cb" value="" onclick="genereCadence()"/></label>';
    }
    html += '</div>';

    cbs.innerHTML = html;
}

function init_cb() {
    document.querySelector('#r_cb0').checked = true;
    document.querySelector('#r_cb2').checked = true;
    document.querySelector('#r_cb4').checked = true;
    // document.querySelector('#r_cb0').checked = true;
    document.querySelector('#r_cb6').click();
    window.removeEventListener('mousemove', init_cb);
}

function checkUser() {
    // console.log(result);

    let d = Math.abs(Date.now() - delaiPastille);
    if (d > 10000) {
        d = 9999;
    }
    lesDelais.push(d);
    // console.log("DD : " + d);
    nbClick++;
    if (d < 100) {
        result[0]++;
        voyant.style.backgroundColor = "#F0F";
    } else if (d < 200) {
        result[1]++;
        voyant.style.backgroundColor = "#080";
    } else if (d < 300) {
        result[2]++;
        voyant.style.backgroundColor = "rgb(212, 138, 0)";
    } else {
        result[3]++
        voyant.style.backgroundColor = "#800";
    }

    if (nbClick >= 20) {
        let t = "<table><tr class='rouge'><td align='right'>Erreur</td><td> : " + result[3] + "</td></tr>";
        t += "<tr class='orange'><td align='right'>Boulette</td><td> : " + result[2] + "</td></tr>";
        t += "<tr class='vert'><td align='right'>Correct</td><td> : " + result[1] + "</td></tr>";
        t += "<tr class='rose'><td align='right'>Très bien</td><td> : " + result[0] + "</td></tr></table>";
        msg.innerHTML = t;

        afficherResultatCamembert();

        window.clearInterval(timer_id);
        start_b = false;

        let html = "<hr>Décalage en ms :<br>";
        lesDelais.forEach(d => {
            html += " | <span class='";

            if (d < 100) {
                html += "rose";
            } else if (d < 200) {
                html += "vert";
            } else if (d < 300) {
                html += "orange";
            } else {
                html += "rouge";
            }
            html += "'>" + d + "<span>";

        });
        document.querySelector('.lesMs').innerHTML = html+ "<hr>";
    }
}

function afficherResultatCamembert() {

    // console.log("afficherResultatCamembert");
    let canvas = document.getElementById("canvas_camembert");
    canvas.style.display = "inline-block";

    let ctx = canvas.getContext('2d');
    ctx.font = "30px Arial";
    ctx.fillStyle = "#f00";
    // ctx.strokeStyle = "#000";
    // ctx.lineWidth = 0.5;

    let posCentre = {};
    posCentre.x = canvas.width / 2;
    posCentre.y = canvas.height / 2;


    let nbResult = result[0] + result[1] + result[2] + result[3];
    //console.log("nbResult " + nbResult);

    // plus p'tite part du gateau.
    let pppdg = (2 * Math.PI) / nbResult;
   // console.log("pppdg : " + pppdg);
    let angle1 = result[0] * pppdg;
    let angle2 = result[1] * pppdg + angle1;
    let angle3 = result[2] * pppdg + angle2;
    let angle4 = result[3] * pppdg + angle3;

    //console.log("angle : " + angle1 + " " + angle2 + " " + angle3 + " " + angle4);

    ctx.beginPath();
    ctx.moveTo(posCentre.x, posCentre.y);
    // Rose : Expert !
    ctx.fillStyle = "#f0f";
    ctx.arc(posCentre.x, posCentre.y, 50, 0, angle1);
    ctx.lineTo(posCentre.x, posCentre.y);
    ctx.fill();
    // ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(posCentre.x, posCentre.y);
    ctx.fillStyle = "#080";
    ctx.arc(posCentre.x, posCentre.y, 50, angle1, angle2);
    ctx.lineTo(posCentre.x, posCentre.y);
    ctx.fill();
    // ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(posCentre.x, posCentre.y);
    ctx.fillStyle = "rgb(212, 138, 0)";
    ctx.arc(posCentre.x, posCentre.y, 50, angle2, angle3);
    ctx.lineTo(posCentre.x, posCentre.y);
    ctx.fill();
    // ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(posCentre.x, posCentre.y);
    ctx.fillStyle = "#800";
    ctx.arc(posCentre.x, posCentre.y, 50, angle3, angle4);
    ctx.lineTo(posCentre.x, posCentre.y);
    ctx.fill();
    // ctx.stroke();

    result = [];


}


function rangeSpeed_up() {
    rangSpeed_b = false;
    start();
}
function rangeSpeed_down() {
    rangSpeed_b = true;
}
function rangeSpeed(leRange) {
    if (rangSpeed_b) {
        // let bpm = leRange.value;
        // 60  BPM = 1bat/s = 1000ms
        // 120 BPM = 2bat/s = 500ms
        // 180 BPM = 3bat/s = 333ms
        // let  ms = Math.round(60/bpm * 1000);

        delaiRythme = bpm(leRange.value);

        window.clearInterval(timer_id);
        leRange.nextSibling.innerHTML = leRange.value + "BPM";
        start_b = false;
    }
}

function rangeVol1_up() {
    rangVol1_b = false;
}
function rangeVol1_down() {
    rangVol1_b = true;
}
function rangeVol1(leRange) {
    if (rangVol1_b) {
        sonTic.volume = leRange.value / 100;
    }
}

function rangeVol1_up() {
    rangVol1_b = false;
}
function rangeVol1_down() {
    rangVol1_b = true;
}
function rangeVol1(leRange) {
    if (rangVol1_b) {
        sonTic.volume = leRange.value / 100;
        leRange.nextSibling.innerHTML = leRange.value;
    }
}

function rangeVol2_up() {
    rangVol2_b = false;
}
function rangeVol2_down() {
    rangVol2_b = true;
}
function rangeVol2(leRange) {
    if (rangVol2_b) {
        sonExo.volume = leRange.value / 100;
        leRange.nextSibling.innerHTML = leRange.value;
    }
}


function bpm(v) {
    // 60  BPM = 1bat/s = 1000ms
    // 120 BPM = 2bat/s = 500ms
    // 180 BPM = 3bat/s = 333ms
    // let  ms = Math.round(60/bpm * 1000);
    return Math.round((60 / v) * 1000);
}


