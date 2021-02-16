function carousel(){
    
    let items = document.querySelectorAll('.carousel-item');
    
    if(items !== null){
      
        let itemsCount = items.length;
        let buttonNext = document.querySelector('.slide-next');
        let buttonPrev = document.querySelector('.slide-prev');
        
        //if items >1
        if(itemsCount > 1){
            //Fonction Suivant
            function slideNext(){
                //retrieve the active class of the current item
                let currentItem = document.querySelector('.active');
                let slideNext = currentItem.nextElementSibling;
                
                //If no slide next we go back to the first item
                if(slideNext === null){
                    slideNext = items[0];
                }
                
                //remove the active class of current item
                currentItem.classList.remove('active');
                
                //add the active class to the next item
                slideNext.classList.add('active');
            }
            //Previous function
            function slidePrev(){
                let currentItem = document.querySelector('.active');
                let slidePrev = currentItem.previousElementSibling;
                
                //If no slide prev we go back to the first item
                if(slidePrev === null){
                    
                    slidePrev = items[1];
                }
                //remove the active class
                currentItem.classList.remove('active');
                
               //and add the active class to the previous item
                slidePrev.classList.add('active');
            }
            //listen to the click on the next button
            buttonNext.addEventListener('click', ()=>{
                slideNext();
            });
            //listen to the click on the next button prev
            buttonPrev.addEventListener('click',()=>{
                 slidePrev();
                
            });
        }
    } 
}

//Loading the page
document.addEventListener('DOMContentLoaded', function() {
    
   carousel();
    
});