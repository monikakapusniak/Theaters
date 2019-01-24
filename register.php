<?php 
  require('shared/header.php');
  require('shared/navbar.php');
?>

<div id="responseDiv"></div>
<div class="container">
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-info" role="alert">
            <strong>Zarejestruj się:</strong>
          </div>
          <div class="alert alert-info" role="alert">
            <p>Login powinnen składać się z:</p>
            <ul>
              <li>6-32 znaków</li>
              <li>zaczynać się od litery</li>
              <li>tylko liter oraz cyfr</li>
            </ul>
            <p>Hasło powinno składać się z:</p>
            <ul>
              <li>co najmniej 8 znaków</li>
              <li>co najmniej 1 cyfry</li>
              <li>co najmniej jednej dużej litery</li>
            </ul>
          </div>
            <form action="javascript:void(0);" onSubmit="tryCreateUser();">
              <div class="form-group">
                <label for="email"><b>E-mail:</b></label>
                <input type="text" class="form-control" id="email" placeholder="Podaj email" name="email" oninput="validateEmail();" required>
                <p id="emailInfo"></p>
              </div>
              <div class="form-group">
                <label for="login"><b>Login:</b></label>
                <input type="login" class="form-control" id="login" placeholder="Podaj login" name="login" oninput="validateLogin();"required>
                <p id="loginInfo"></p>
              </div>
              <div class="form-group">
                <label for="pswd"><b>Hasło:</b></label>
                <input type="password" class="form-control" id="pswd" placeholder="Podaj hasło" name="pswd" oninput="validatePassword();" required>
                <p id="passInfo"></p>
              </div>
              <button type="submit" class="btn btn-info" id="submit" disabled>Wyślij</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
  require('shared/footer.php');
?>

<script>
var loginCheck = false;
var passCheck = false;
var emailCheck = false;

function checkEmailRegex(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function checkLoginRegex(login) {
    var re = /^[A-Za-z]{1}[A-Za-z0-9]{5,31}$/;
    return re.test(String(login));
}

function checkPassRegex(pass) {
    var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
    return re.test(String(pass));
}

function validateEmail() {
  var email = document.getElementById("email").value;
  $('#emailInfo').html('').attr('');
  document.getElementById('submit').disabled = 'disabled';

  try { 
    if(!checkEmailRegex(email)) {
      throw("Niepoprawny email.");
    }

    emailCheck = true;
  }
  catch(err) {
    emailCheck = false;
    $('#emailInfo').html(err).attr('class', 'badge badge-pill badge-danger');
  }
}

function validateLogin() {
  var login = document.getElementById("login").value;
  $('#loginInfo').html('').attr('');
  document.getElementById('submit').disabled = 'disabled';

  try { 
    if(!checkLoginRegex(login)) {
      throw("Niepoprawny login.");
    }

    loginCheck = true;
  }
  catch(err) {
    loginCheck = false;
    $('#loginInfo').html(err).attr('class', 'badge badge-pill badge-danger');
  }
}

function validatePassword() {
  var pass = document.getElementById("pswd").value;
  $('#passInfo').html('').attr('');
  document.getElementById('submit').disabled = 'disabled';

  try { 
    if(!checkPassRegex(pass)) {
      throw("Niepoprawne hasło.");
    }
    passCheck = true;
  }
  catch(err) {
    passCheck = false;
    $('#passInfo').html(err).attr('class', 'badge badge-pill badge-danger');
  }
}

$('input').on('input', function() {
  if(passCheck && emailCheck && loginCheck) {
    document.getElementById('submit').disabled = false;
  }
});

function tryCreateUser() {
    $.post( "/register_helper.php", {login: $('#login').val(), pswd: $('#pswd').val(), email: $("#email").val()}, function() {
    }).done(function(data) {
      if(data == "registered") {
        window.location.href="/login.php";
      }
      else {
        $("#responseDiv").html(data);
      }
    });
  }

</script>