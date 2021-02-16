//Function for displaying sentences in the header
function displaySentence(){

    let sentences = [
        ' Vous ne trouverez jamais ce que vous ne cherchez pas<br>Marcel Proust',
        ' Accepte ce qui est, laisse aller ce qui était, aie confiance en ce qui sera<br>Bouddha',
        "N'aie pas peur d'avancer lentement. Aie peur de rester immobile<br>Proverbe chinois"
    ];
    //initialization
    let i = 0;
    //get the element of the property id = sentence
    let sentence = document.getElementById("sentence");

    //Display the sentence with an interval of 6 sec
    let interval = setInterval( ()=>{
        sentence.innerHTML = sentences[i = (i + 1) % sentences.length ] ;
    }, 6000);
}

//Responsive menu display function if page <980px
function displayMenuResponsive(){
    
    //item recovery
    let navButtonResponsive = document.getElementById('nav-button');
    let menuResponsive = document.getElementById('nav-link');
    //no menu display by default
    menuResponsive.style.display = 'none';
    
    //listen to the click on the responsive button
    navButtonResponsive.addEventListener('click', ()=>{
        //if different from none, the menu is not displayed
        if( menuResponsive.style.display != 'none' ){
            
            menuResponsive.style.display = 'none';
        }
        //otherwise we display the menu
        else{
            
            menuResponsive.style.display = 'block';
        }
    });
    
    //listen to the window if resized then we set the menu display to none
    window.addEventListener('resize', ()=>{
        menuResponsive.style.display = 'none';
       
    });
    
}

//loading page
document.addEventListener('DOMContentLoaded', function() {
    
    displaySentence();
    displayMenuResponsive();
    
});