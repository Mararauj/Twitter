var maj = -1;

var url = window.location.href;


if (url.match(/@(.*)/)) {
    var arobase = url.substring(url.indexOf("@"));
  
} else {
    var arobase = "nul";
}

var maj = -1;

$.ajax({
    type: "GET",
    url: 'prof.php?arobase='+ arobase,
    success: function (response) {
        if(response.length != 0){
            if(document.getElementById("ba")){
                if(response.suivre){
                    document.getElementById("ba").textContent = "Suivi";
                }
                else{
                    document.getElementById("ba").textContent = "Suivre";
                }
            }
            if(response.t.length != maj){
                document.getElementById("post").innerHTML = '';
                document.getElementById("post").innerHTML = '<h2 id="title-post">Tweets</h2>';
                maj = response.t.length;

                for (let i = 0; i < response.t.length; i++) {
                    if(response.t[i].id_response == null){
                        if(response.t[i].content.substr(response.t[i].content.indexOf("./")).length > 6){
                            $('.post').append('<div id="' + response.t[i].id + '" class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.t[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.t[i].username +'</h3></div></div><h4>'+ response.t[i].at_user_name +'</h4><p>' + hash_aro(response.t[i].content.substring(0, response.t[i].content.indexOf("./"))) + '</p></div><img class="img-post" src="'+response.t[i].content.substr(response.t[i].content.indexOf("./"))+'" alt="Image du tweet"><div class="logo-post"><img id="repost" onclick="commentaire(' + response.t[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.t[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.t[i].id + ')" src="./images/like.png" alt="like"></div>');
                        }
                        else{
                            $('.post').append('<div id="' + response.t[i].id + '" class="tweet-content"><div><div class="img-pseudo"><div><img class="img-border" src="'+ response.t[i].profile_picture +'" alt="Avatar de l\'utilisateur"></div><div><h3>'+ response.t[i].username +'</h3></div></div><h4>'+ response.t[i].at_user_name +'</h4><p>' + hash_aro(response.t[i].content) + '</p></div><div class="logo-post"><img id="repost" onclick="commentaire(' + response.t[i].id + ')" src="./images/commentaire.png" alt="commentaire"><img id="retweet" onclick="retweet(' + response.t[i].id + ')" src="./images/retweet.png" alt="retweet"><img id="like" onclick="like(' + response.t[i].id + ')" src="./images/like.png" alt="like"></div>');
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

            }
        }
        else {
            if(response.length != maj){
                maj = response.length;
                document.getElementById("title-post").innerHTML = '';
                $('#title-post').append('<div class="tweet-content"><div><h1>Cette personne n\'a aucun tweet et/ou intéraction</h1></div></div>');
            }
        }
    }
});

if(document.getElementById("ba")){
    document.getElementById("ba").addEventListener("click", function() {
        var abos = document.getElementById("abonnes");
        if(document.getElementById("ba").textContent ==  "Suivre"){
            abos.textContent = (parseInt(abos.textContent) + 1) + " Abonnés";
            document.getElementById("ba").textContent = "Suivi";
            $.post('prof.php', {aroA: arobase});
        }
        else{
            abos.textContent = (parseInt(abos.textContent) - 1) + " Abonnés";
            document.getElementById("ba").textContent =  "Suivre";
            $.post('prof.php', {aroD: arobase});
        }
    });
}



if(document.getElementById("msg")){
    document.getElementById("msg").removeEventListener("click", handleClick);
    document.getElementById("msg").addEventListener("click", handleClick);

    function handleClick() {
        $.post('prof.php', {aroMSG: arobase}, function() {
            window.location.href = "messagerie.php";
        });
    }
}

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
    var commentaireDiv = document.createElement("div");
    commentaireDiv.id = "commentaireDiv";
    commentaireDiv.innerHTML = "<h2>Écrire un commentaire</h2><textarea style=\"resize: none;\" id='commentaireTextarea' rows='5' cols='35' maxlength=\"140\"></textarea><br><div><button onclick='repondre_tweet("+id+")'>Répondre</button></div><div><button onclick='annuler()'>Fermer</button></div>";
    document.body.appendChild(commentaireDiv);
    commentaireDiv.style.display = "block";
}

function annuler(){
    var commentaireDiv = document.getElementById("commentaireDiv");
    commentaireDiv.remove();
    
}

function repondre_tweet(id){
    var commentaireDiv = document.getElementById("commentaireDiv");
    if(document.getElementById("commentaireTextarea").value.trim()!=""){
        $.post('tweet.php', {repondre: document.getElementById("commentaireTextarea").value, id_t: id});
    }    
    commentaireDiv.remove();
    
}