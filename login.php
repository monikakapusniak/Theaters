<?php 
  require('shared/header.php');
  require('shared/navbar.php');
  include 'database_connection.php';

  foreach ($_POST as $k=>$v) {$_POST[$k] = mysqli_real_escape_string($link, $v);}
  foreach ($_SERVER as $k=>$v) {$_SERVER[$k] = mysqli_real_escape_string($link, $v);}

  if (isset($_POST['login'])){
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $pass = mysqli_real_escape_string($link, $_POST['pass']);
    $q = mysqli_fetch_assoc(mysqli_query($link, "select count(*) cnt, id_user, salt from User where login='{$_POST['login']}' 
    and hash=sha1(concat(salt, '@@', '$pass'));"));

    if ($q['cnt']) {
      $id = md5(rand(-10000,10000) . microtime()) . md5(crc32(microtime()) . $_SERVER['REMOTE_ADDR']);
      mysqli_query($link, "delete from Session where id_user = '$q[id_user]';"); 	
      mysqli_query($link, "insert into Session (id_user, id_cookie_session, ip_address, webbrowser) values
      ('$q[id_user]','$id','$_SERVER[REMOTE_ADDR]','$_SERVER[HTTP_USER_AGENT]')");

      if (!mysqli_errno($link)){
        setcookie("id", $id);
        header("location: index.php");
      } 
      else {
        echo "<div class=\"alert alert-danger\"><center>Blad podczas logowania!</center></div>";
      }
    } 
    else {
      echo "<div class=\"alert alert-danger\"><center>Błędny login lub hasło!</center></div>";
    } 
  }
?>

<div class="container">
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
          <div class="alert alert-info" role="alert">
            <strong>Zaloguj się:</strong>
          </div>
          <form method="post">
            <div class="form-group">
              <label for="login">Login:</label>
              <input type="text" class="form-control" id="login" placeholder="Podaj login" name="login">
            </div>
            <div class="form-group">
              <label for="pwd">Hasło:</label>
              <input type="password" class="form-control" id="pass" placeholder="Podaj hasło" name="pass">
            </div>
            <button type="submit" class="btn btn-info">Wyślij</button>
            <a href="/register.php/"><button type="button" class='btn btn-info'>Zarejestruj się</button></a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
  require('shared/footer.php');
?>