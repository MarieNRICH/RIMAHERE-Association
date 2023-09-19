document.addEventListener("DOMContentLoaded"), () => {
  

var video = document.getElementById("background-video");
var btn = document.getElementById("btnVideo");

function playAndPause () {
  if (video.paused) {
    video.play();
    btn.innerHTML = "Pause II";
  } else {
    video.pause();
    btn.innerHTML = "Play ▶";
  }
}


};

/*Récupération des références html*/
const submitBtn = document.getElementById('submitBtn');
const cocktailInput = document.getElementById('cocktailInput');
const cocktailContainer = document.getElementById('cocktailContainer');

/* On écoute l'évènement click sur le bouton submit*/
submitBtn.addEventListener('click', (event) => {

    /*Au clic, on stop l'envoi du formulaire pour empêcher la page se recharge*/
    event.preventDefault();

    /* on récupère la valeur du champs texte*/
    const cocktail = cocktailInput.value;

    /*on lance r l'url de l'api + le param*/
    fetch('https://www.thecocktaildb.com/api/json/v1/1/search.php?s=' + cocktail)
/* on retourne la réponse */
        .then(response => response.json())
        /* on récupère la data et on lance une fonction*/
        .then((data) => {
            console.log(data);
            /*On vide le container avant de créer les nouvelles cartes*/
            cocktailContainer.innerHTML = "";
            /* On boucle sur la liste des cocktails*/
            if(data.drinks != null){
            
                        
            for (let index = 0; index < data.drinks.length; index++) {
                /*pour chaque cocktail, je créer une carte en injectant les bonnes valeurs*/
                cocktailContainer.innerHTML += `
                <div class="card" style="width: 18rem;">
                    <img src="${data.drinks[index].strDrinkThumb}" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">${data.drinks[index].strDrink}</h5>
                    <p class="card-text">${data.drinks[index].strInstructions}</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                  </div>
                </div>
                `;
            }
            }else{
                cocktailContainer.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    Pas de cocktails trouvés !
                    </div>`;
            }

        });

});
