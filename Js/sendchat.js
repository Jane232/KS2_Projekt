// written by @Jane232

// Js zum Senden neuer Nachrichten
// Ajax funktion
$('#messageForm').submit(function() {
  // Message in Variable eingtragen
  let message = $('#messageTextInput').val();
  // Bei success wird ein Feedback 6 s angezeigt

  $.ajax({
    url: '../chat/send.php',
    data: {message: message},
    success: function(data){
      $('#chatFeedback').html(data);
      $('#chatFeedback').fadeIn('slow',function(){
        $('#chatFeedback').fadeOut(6000);
      });

      $('#messageTextInput').val('');
    }
  });
  return false;

});
