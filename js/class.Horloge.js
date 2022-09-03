class Horloge {

    canvas = null;
    ctx = null;
    posCentre = {};
    posPastille = [];
    nbPastille = 0;
    numPastilleActive = 0;
    cadence = [];
    sonExo = null;
    sonTic = null;

    rGcercle = 120;
    rGcercleAiguille = 100;
    rpastille = 25;

    constructor(canv, props, sonExo, sonTic) {
        this.sonTic = sonTic;
        this.sonExo = sonExo;
        this.canvas = document.getElementById(canv);
        this.ctx = canvas.getContext('2d');
        this.ctx.font = "30px Arial";
        this.posCentre.x = this.canvas.width / 2;
        this.posCentre.y = this.canvas.height / 2;

        this.cadence = props.cadence;
        this.nbPastille = props.nbPastille;
        this.genereInfos()
    }

    genereInfos() {
        for (let i = 0; i < this.nbPastille; i++) {
            let angle = (2 * Math.PI / this.nbPastille) * i;
            this.posPastille[i] = {};
            this.posPastille[i].x = Math.cos(angle) * this.rGcercle;
            this.posPastille[i].y = Math.sin(angle) * this.rGcercle;

            this.posPastille[i].xAiguille = Math.cos(angle) * this.rGcercleAiguille;
            this.posPastille[i].yAiguille = Math.sin(angle) * this.rGcercleAiguille;

            this.posPastille[i].color = "#800"; // "hsl(" + (Math.round(360 / this.nbPastille) * i) + ", 80%, 60%)";
        }
    }

    traceTraitAiguille() {
        this.ctx.strokeStyle = "#000";
        this.ctx.lineWidth = 2;

        this.ctx.beginPath();
        this.ctx.moveTo(this.posCentre.x, this.posCentre.y);

        let x2 = this.posCentre.x + this.posPastille[this.numPastilleActive].xAiguille;
        let y2 = this.posCentre.y + this.posPastille[this.numPastilleActive].yAiguille;
        this.ctx.lineTo(x2, y2);

        // console.log(x1, y1, x2, y2);

        this.ctx.stroke();
        // this.ctx.fill();
    }

    animSuivant() {
        this.dessine();

        this.traceTraitAiguille();

        this.allumePastille(this.numPastilleActive);


        if (this.cadence.includes(this.numPastilleActive)) {
            //console.log(this.numPastilleActive);
            this.sonExo.play();
        } else {
            this.sonTic.play();
        }

        this.numPastilleActive++;
        if (this.numPastilleActive >= this.nbPastille) {
            this.numPastilleActive = 0;
        }
    }

    /**
     * Allume la pastille de référence par rapport au rythme.
     */
    allumePastille(num) {
        this.numPastilleActive = num;
        let pastille = this.posPastille[this.numPastilleActive];
        // this.ctx.strokeStyle = "hsl("+angle+", 60%, 60%)";
        this.ctx.fillStyle = "#000";

        this.ctx.beginPath();
        this.ctx.arc(this.posCentre.x + pastille.x, this.posCentre.y + pastille.y, this.rpastille, 0, 2 * Math.PI);
        // this.ctx.stroke();
        this.ctx.fill();
    }

    dessine() {
        this.ctx.clearRect(0, 0, this.ctx.canvas.width, this.ctx.canvas.height);
        let i = 0;

        this.ctx.beginPath();
        this.ctx.arc(this.posCentre.x, this.posCentre.y, this.rGcercle, 0, 2 * Math.PI);
        this.ctx.stroke();

        // DESSINE TOUTES LES pastilles
        this.posPastille.forEach(pastille => {
            
            if (this.cadence.includes(i)) {

                // this.ctx.strokeStyle = pastille.color;
                this.ctx.fillStyle = pastille.color;

                this.ctx.beginPath();
                this.ctx.arc(this.posCentre.x + pastille.x, this.posCentre.y + pastille.y, this.rpastille, 0, 2 * Math.PI);
                // this.ctx.stroke();
                this.ctx.fill();

            } else {
                //this.ctx.strokeStyle = pastille.color;
                this.ctx.fillStyle = "#fff";

                this.ctx.beginPath();
                this.ctx.arc(this.posCentre.x + pastille.x, this.posCentre.y + pastille.y, this.rpastille, 0, 2 * Math.PI);
                // this.ctx.stroke();
                this.ctx.fill();
            }
            //
            if (!i) {
                this.ctx.fillStyle = "#000";
                this.ctx.beginPath();
                this.ctx.fillText("1", this.posCentre.x + pastille.x - 10, this.posCentre.y + pastille.y + 10);
                this.ctx.fill();
            }
            i++;
        });
    }
}