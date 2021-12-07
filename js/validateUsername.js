$('document').ready(function(){
 var username_state = false;
 var email_state = false;
 $('#username').on('blur', function(){
  var username = $('#username').val();
  if (username == '') {
    username_state = false;
    
  }else if (/\s/.test(username)) {
    username_state = false;
    $.confirm({
      boxWidth: '27%',
      title: 'Username contains whitespaces',
      content: 'Username shall not have any whitespaces. Please try again',
      type: 'red',
      typeAnimated: true,
      useBootstrap: false,
      buttons: {
        ok: {
          text: 'Okay',
          btnClass: 'btn-red',
          action: function(){
          }
        }
      }
    });
    $('#submit').prop('disabled', true);
    $('#reset').prop('disabled', true);
    $('#submit').css({ 'color': 'white', 'background-color': 'gray' });
    $('#reset').css({ 'color': 'white', 'background-color': 'gray' });
    return;
  }
  $.ajax({
    url: 'index.php',
    type: 'post',
    data: {
      'username_check' : 1,
      'username' : username,
    },
    success: function(response){
      if (response == 'taken' ) {
        username_state = false;
        $.confirm({
          boxWidth: '27%',
            title: 'Username already exist!',
            content: 'Sorry, the username entered is taken. Please use other username.',
            type: 'red',
            typeAnimated: true,
            useBootstrap: false,
            buttons: {
                ok: {
                    text: 'Okay',
                    btnClass: 'btn-red',
                    action: function(){
                    }
                }
            }
        });
        $('#submit').prop('disabled', true);
        $('#reset').prop('disabled', true);
        $('#submit').css({ 'color': 'white', 'background-color': 'gray' });
        $('#reset').css({ 'color': 'white', 'background-color': 'gray' });
      }else if (response == 'not_taken') {
        username_state = true;
        $('#submit').prop('disabled', false);
        $('#reset').prop('disabled', false);
        $('#submit').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
        $('#reset').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
      }
    }
  });
 });    



  $('#email').on('blur', function(){
  var email = $('#email').val();
  if (email == '') {
    email_state = false;
    return;
  }else if (/\s/.test(email)) {
    email_state = false;
    $.confirm({
      boxWidth: '27%',
      title: 'E-mail contains whitespaces',
      content: 'E-mail shall not have any whitespaces. Please try again',
      type: 'red',
      typeAnimated: true,
      useBootstrap: false,
      buttons: {
        ok: {
          text: 'Okay',
          btnClass: 'btn-red',
          action: function(){
          }
        }
      }
    });
    $('#submit').prop('disabled', true);
    $('#reset').prop('disabled', true);
    $('#submit').css({ 'color': 'white', 'background-color': 'gray' });
    $('#reset').css({ 'color': 'white', 'background-color': 'gray' });
    return;
  }
  $.ajax({
      url: 'index.php',
      type: 'post',
      data: {
        'email_check' : 1,
        'email' : email,
      },
      success: function(response){
        if (response == 'taken' ) {
          email_state = false;
          $.confirm({
          boxWidth: '27%',
            title: 'Email already exist!',
            content: 'Sorry, the email entered is taken. If you think this is a mistake, do proceed to sign in and click forget password.',
            type: 'red',
            typeAnimated: true,
            useBootstrap: false,
            buttons: {
                ok: {
                    text: 'Okay',
                    btnClass: 'btn-red',
                    action: function(){
                    }
                }
            }
        });
          $('#submit').prop('disabled', true);
          $('#reset').prop('disabled', true);
          $('#submit').css({ 'color': 'white', 'background-color': 'gray' });
          $('#reset').css({ 'color': 'white', 'background-color': 'gray' });
        }else if (response == 'not_taken') {
          email_state = true;
          $('#submit').prop('disabled', false);
          $('#reset').prop('disabled', false);
          $('#submit').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
          $('#reset').css({ 'color': '#fff', 'background-color': 'rgba(117, 20, 117, 0.6)' });
        }
      }
  });
 });
});