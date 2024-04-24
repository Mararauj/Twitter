
document.getElementById("postButton").addEventListener("click", (e) => {
    var input = document.getElementById('photo');

    if(document.getElementById("postContent").value.trim() != '' || (input.files && input.files.length > 0)){
        if(input.files && input.files.length > 0){
            var fichier = input.files[0].name;
        }
        else{
            var fichier = null;
        }

        $.post('tweet.php', {
            content: document.getElementById("postContent").value,
            photo: fichier
        });
        document.getElementById("postContent").value='';
        load();
    }

});

var scrollPositions = [];
var maj = -1;

// Fonction load pour raffraichir la page et ajouter les nouveaux tweets sans raffraichir tout en gardant la position qu'on regardait 

function load(){

    var currentPosition = window.scrollY || window.pageYOffset;
    scrollPositions.push(currentPosition);

    $.ajax({
        type: "GET",
        url: 'tweet.php?t=true',
        success: function (response) {
            if(response.length != 0){
                if(response.t.length != maj){
                    document.getElementById("all").innerHTML = '';
                    maj = response.t.length;

                    for (let i = 0; i < response.t.length; i++) {
                        if(response.t[i].id_response == null){
                            if(response.t[i].content.substr(response.t[i].content.indexOf("./")).length > 6){
                                $('.tweetotal').append('<div id="' + response.t[i].id + '" class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.t[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.t[i].username +'</h3></div></div><h4>'+ response.t[i].at_user_name +'</h4><p>' + hash_aro(response.t[i].content.substring(0, response.t[i].content.indexOf("./"))) + '</p></div><img class="img-post" src="'+response.t[i].content.substr(response.t[i].content.indexOf("./"))+'" alt="Image du tweet"><div class="logo-post"><img id="repost" onclick="commentaire(' + response.t[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.t[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.t[i].id + ')" src="./images/like.png" alt="like"></div>');

                            }
                            else{
                                $('.tweetotal').append('<div id="' + response.t[i].id + '" class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.t[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.t[i].username +'</h3></div></div><h4>'+ response.t[i].at_user_name +'</h4><p>' + hash_aro(response.t[i].content) + '</p></div><div class="logo-post"><img id="repost" onclick="commentaire(' + response.t[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.t[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.t[i].id + ')" src="./images/like.png" alt="like"></div>');
                            }
                        } 
                    }
                    
                    for (let i = 0; i < response.t.length; i++) {
                        if(response.t[i].id_response != null){
                            $rep = "#" + response.t[i].id_response;
                            if(response.t[i].content.substr(response.t[i].content.indexOf("./")).length > 6){
                                $(''+$rep+'').append('<div id="' + response.t[i].id + '" class="tweet-rep"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.t[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.t[i].username +'</h3></div></div><h4>'+ response.t[i].at_user_name +'</h4><p>' + hash_aro(response.t[i].content.substring(0, response.t[i].content.indexOf("./"))) + '</p></div><img class="img-post" src="'+response.t[i].content.substr(response.t[i].content.indexOf("./"))+'" alt="Image du tweet"><div class="logo-post"><img id="repost" onclick="commentaire(' + response.t[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.t[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.t[i].id + ')" src="./images/like.png" alt="like"></div>');
                            }
                            else{
                                $(''+$rep+'').append('<div id="' + response.t[i].id + '" class="tweet-rep"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.t[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.t[i].username +'</h3></div></div><h4>'+ response.t[i].at_user_name +'</h4><p>' + hash_aro(response.t[i].content) + '</p></div><div class="logo-post"><img id="repost" onclick="commentaire(' + response.t[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.t[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.t[i].id + ')" src="./images/like.png" alt="like"></div>');
                            }
                        }
                    }
                    var scrollPosition = scrollPositions.shift() || 0;
                    window.scrollTo(0, scrollPosition);
                }
                else{
                    var scrollPosition = scrollPositions.shift() || 0;
                    window.scrollTo(0, scrollPosition);
                }
            }
            else {
                if(response.length != maj){
                    maj = response.length;
                    document.getElementById("all").innerHTML = '';
                    $('.tweetotal').append('<div class="tweet-content"><div><h1>Suivez des personnes ou tweetez pour commencer votre nouvelle aventure !</h1></div></div>');
                    var scrollPosition = scrollPositions.shift() || 0;
                    window.scrollTo(0, scrollPosition);
                }
                var scrollPosition = scrollPositions.shift() || 0;
                    window.scrollTo(0, scrollPosition);
            }
        }
    });
}

// Fonction pour trouver tous les # et @ dans le contenu du tweet

function hash_aro(content){
    var replacedContent = content.replace(/#(\w+)/g, '<a href="accueil.php?hashtag=#$1">#$1</a>');
    replacedContent = replacedContent.replace(/@(\w+)/g, '<a href="profil.php?arobase=@$1">@$1</a>');
    return replacedContent;
}


function like(id){
    $.post('tweet.php', {idL: id});
}

function retweet(id){
    $.post('tweet.php', {idR: id});
}

function commentaire(id){
    window.clearInterval(intervalId);
    var commentaireDiv = document.createElement("div");
    commentaireDiv.id = "commentaireDiv";
    commentaireDiv.innerHTML = "<h2>Écrire un commentaire</h2><textarea style=\"resize: none;\" id='commentaireTextarea' rows='5' cols='35' maxlength=\"140\"></textarea><br><div><button onclick='repondre_tweet("+id+")'>Répondre</button></div><div><button onclick='annuler()'>Fermer</button></div>";
    document.body.appendChild(commentaireDiv);
    commentaireDiv.style.display = "block";
}

function annuler(){
    var commentaireDiv = document.getElementById("commentaireDiv");
    commentaireDiv.remove();
    intervalId = window.setInterval(function(){
        load();
      }, 3000);
}

function repondre_tweet(id){
    var commentaireDiv = document.getElementById("commentaireDiv");
    if(document.getElementById("commentaireTextarea").value.trim()!=""){
        $.post('tweet.php', {repondre: document.getElementById("commentaireTextarea").value, id_t: id});
    }
    commentaireDiv.remove();
    intervalId = window.setInterval(function(){
        load();
      }, 3000);
}

// Fonction pour répéter la fonction load toutes les 3 secondes

var intervalId = window.setInterval(function(){
    load();
  }, 3000);


function recherche(){
    window.clearInterval(intervalId);
    document.getElementsByClassName("tweet")[0].innerHTML = "";

    var divT = document.createElement('div');
    divT.className = 'tweetotal';
    if(document.getElementById("barre").value.trim() != ""){
        if(document.getElementById("barre").value[0] === "@"){
            $.ajax({
                type: "GET",
                url: 'tweet.php?arobase=' + document.getElementById("barre").value,
                success: function (response) {
                    
                    if(response.length != 0){
                        divT.style = "margin-left: 35%;";
                        document.getElementsByClassName("tweet")[0].append(divT);
                        for (let i = 0; i < response.ar.length; i++) {
                            $('.tweetotal').append('<a href="profil.php?arobase=' + response.ar[i].at_user_name + '"<div class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="' +response.ar[i].profile_picture+'" alt="Avatar de l\'utilisateur"></div><div><h3>'+response.ar[i].username+'</h3></div></div><h4>'+response.ar[i].at_user_name+'</h4></div></div></a>');
                        }
                    }
                    else {
                        divT.style = "margin-left: 0%;";
                        document.getElementsByClassName("tweet")[0].append(divT);
                        $('.tweetotal').append('<div class="tweet-content"><div><h1>Aucun utilisateur trouvé</h1></div></div>');

                    }
                }
            });
        }
        if(document.getElementById("barre").value[0] === "#"){
            $.ajax({
                type: "GET",
                url: 'tweet.php?hashtag_list=%23' + document.getElementById("barre").value.substring(1),
                success: function (response) {
                    
                    if(response.length != 0){
                        divT.style = "margin-left: 35%;";
                        document.getElementsByClassName("tweet")[0].append(divT);
                        for (let i = 0; i < response.hs.length; i++) {
                            $('.tweetotal').append('<a href="accueil.php?hashtag=' + response.hs[i].hashtag + '"<div class="tweet-content"><div><div><h3>'+response.hs[i].hashtag+'</h3></div></div></div></div></a>');
                        }
                    }
                    else {
                        divT.style = "margin-left: 0%;";
                        document.getElementsByClassName("tweet")[0].append(divT);
                        $('.tweetotal').append('<div class="tweet-content"><div><h1>Aucun hashtag trouvé</h1></div></div>');

                    }
                }
            });
        }
    }
}

// Fonction pour verifier si le paramètre GET est dans l'URL

function isParamInURL(param) {
    var queryParams = window.location.search.substring(1).split('&');
    for (var i = 0; i < queryParams.length; i++) {
        var pair = queryParams[i].split('=');
        if (pair[0] === param) {
            return true;
        }
    }
    return false;
}

if (isParamInURL('hashtag')) {
    window.clearInterval(intervalId);
    document.getElementsByClassName("tweet")[0].innerHTML = "";

    var divT = document.createElement('div');
    divT.className = 'tweetotal';
    $.ajax({
        type: "GET",
        url: 'tweet.php?hashtag=%23' + window.location.hash.substring(1),
        success: function (response) { 
            document.getElementsByClassName("tweet")[0].append(divT);
            for (let i = 0; i < response.h.length; i++) {
                if(response.h[i].content.substr(response.h[i].content.indexOf("./")).length > 6){
                    $('.tweetotal').append('<div id="' + response.h[i].id + '" class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.h[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.h[i].username +'</h3></div></div><h4>'+ response.h[i].at_user_name +'</h4><p>' + hash_aro(response.h[i].content.substring(0, response.t[i].content.indexOf("./"))) + '</p></div><img class="img-post" src="'+response.h[i].content.substr(response.h[i].content.indexOf("./"))+'" alt="Image du tweet"><div class="logo-post"><img id="repost" onclick="commentaire(' + response.h[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.h[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.h[i].id + ')" src="./images/like.png" alt="like"></div>');
                }
                else{
                    $('.tweetotal').append('<div id="' + response.h[i].id + '" class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.h[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.h[i].username +'</h3></div></div><h4>'+ response.h[i].at_user_name +'</h4><p>' + hash_aro(response.h[i].content) + '</p></div><div class="logo-post"><img id="repost" onclick="commentaire(' + response.h[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.h[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.h[i].id + ')" src="./images/like.png" alt="like"></div>');

                }
            }
        }
    });
} 
else {
    load();
    intervalId = window.setInterval(function(){
        load();
      }, 3000);
}