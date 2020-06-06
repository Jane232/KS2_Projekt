// written by @Jane232

// js zum automatischen laden nach 1000 ms (siehe Z.11 ) durch Aufrufen von chat.php: Feedback wird in #message geschrieben
$(document).ready(function(){
  var inteval = setInterval(function () {
    $.ajax({
      url: '../chat/chat.php',
      success: function(data) {
        $('#message').html(data);
      }
    });

  }, 1000);
});
