var parametres = 'http://localhost:8000/api/partie/' + idPartie;

setInterval(function ()
{
    fetch(parametres)
    .then(response => {
        return response.json()
    })
    .then(data => {
        document.getElementById("creaJoueur").innerHTML = "<ul><li>Créateur de la partie: " + data["createur"] + " </li><li>Joueur: " + data["joueur"] + "</li></ul>";
    })
}, 5000);
