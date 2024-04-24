
const ls = localStorage.getItem("selected");
let selected = false;
var list = document.querySelectorAll(".list");
var content = document.querySelector(".content");
var input = document.querySelector(".message-footer input");
var open = document.querySelector(".open a");
document.getElementById("mess").style.display = "none";


function click(l, index) {
  list.forEach(x => { x.classList.remove("active"); });
  if(l) {
    l.classList.add("active");
    document.querySelector("sidebar").classList.remove("opened");
    
    const img = l.querySelector("img").src,
          user = l.querySelector(".user").innerText,
          time = l.querySelector(".time").innerText;

    content.querySelector("img").src = img;
    content.querySelector(".info .user").innerHTML = user;
    content.querySelector(".info .time").innerHTML = time;

    const inputPH = input.getAttribute("data-placeholder");
    input.placeholder = inputPH.replace("{0}", user.split(' ')[0]);

    document.querySelector(".message-wrap").scrollTop = document.querySelector(".message-wrap").scrollHeight;
    
    localStorage.setItem("selected", index);
  }
}

open.addEventListener("click", (e) => {
  const sidebar = document.querySelector("sidebar");
  sidebar.classList.toggle("opened");
});

function message(id, img, user){

  document.getElementById("mess").style.display = "";

  if(document.getElementById("sup")){
    document.getElementById("sup").remove();
    var image_sup = document.getElementById('supp');
    image_sup.parentNode.removeChild(image_sup);
  }
  
  let image = document.createElement('img');
  image.src = img;
  image.setAttribute("id", "supp");
  document.querySelector("header").insertBefore(image, document.getElementById("user"));
  document.getElementById("user").innerHTML = '<span id="sup" class="user">' + user + '</span>';

  var url = 'chat.php';

  $('#submit').off('click');

  $('#submit').on('click', function () {
    $.post(url, {
      message: $('#message').val(),
      from: id
    });
    $('#message').val('');
    load(id);
    return false;
  });

  load(id);

  function load(cId){
    document.getElementById("m").innerHTML = '';
    $.ajax({
      type: "GET",
      url: url + '?id_convo=' + cId,
      success: function (response) {
          for (let i = 0; i < response.mess.length; i++) {
            if(response.mess[i].content != ''){
              if(response.mess[i].id_convo!=cId){
                $('.message-wrap').append('<div class="message-list me"><div class="msg"><p>' +response.mess[i].content + '</p></div></div>');
              }
              else {
                $('.message-wrap').append('<div class="message-list"><div class="msg"><p>' +response.mess[i].content + '</p></div></div>');
              }
            }
          }
          $('.message-wrap').animate({scrollTop: $('.message-wrap')[0].scrollHeight});
      }
    });
  }

}