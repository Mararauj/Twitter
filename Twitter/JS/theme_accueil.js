
var theme = false;

const switchBox = document.querySelector('.switch-box');
const body = document.querySelector('body');
const btn = document.querySelector('.btn');
const icone = document.querySelector('i');
const container = document.querySelector('.container');
const titre = document.querySelectorAll('h1');
const titre1 = document.querySelectorAll('h2');
const titre2 = document.querySelectorAll('h3');
const titre3 = document.querySelectorAll('h4');
const texte = document.querySelectorAll('p');
const liste = document.querySelectorAll('li');


switchBox.addEventListener('click', function(){
    theme = true;
    icone.classList.toggle('fa-sun');
    container.classList.toggle('container-change');
    body.classList.toggle('body-change');

    icone.classList.toggle('icone-change');
    switchBox.classList.toggle('switch-change');
    

    for(let i=0; i < document.querySelectorAll('li').length; i++){
        liste[i].classList.toggle('text-change');
    }

});