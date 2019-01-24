<?php 
  include 'database_connection.php';

  function isValidEmail($email){ 
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Email nie jest poprawny!");
    }
    return true;
  }

  function isValidPassword($pass){ 
    $uppercase = preg_match('@[A-Z]@', $pass);
    $lowercase = preg_match('@[a-z]@', $pass);
    $number    = preg_match('@[0-9]@', $pass);

    if(!$uppercase || !$lowercase || !$number || strlen($pass) < 8) {
      throw new Exception("Haslo powinno miec minimum 8 znakow, co najmniej 1 cyfra, minimum 1 duża litera.");
    }
    return true;
}

  function isValidLogin($login){ 
    if(!preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{5,31}$/', $login)) {
      throw new Exception("Login powinien zaczynac sie z litery, miec miedzy 6-32 znaki, tylko litery oraz cyfry.");
    }
    return true;
  }

  function isNotEmptyEmail($email){ 
    if(empty($email)) {
      throw new Exception("Musisz podac email!");
    }
    return true;
  }

  function isNotEmptyLogin($login){ 
    if(empty($login)) {
      throw new Exception("Musisz podac login!");
    }
    return true;
  }

  function isNotEmptyPass($pass){ 
    if(empty($pass)) {
      throw new Exception("Musisz podac haslo!");
    }
    return true;
  }

  foreach ($_SERVER as $k=>$v) {$_SERVER[$k] = mysqli_real_escape_string($link, $v);}
  if (isset($_POST['login'])){
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $salt = sha1(rand());
    $pass = mysqli_real_escape_string($link, $_POST['pswd']);
    $email = mysqli_real_escape_string($link, $_POST['email']);

    try {
      isNotEmptyEmail($email);
      isNotEmptyLogin($login);
      isNotEmptyPass($pass);
      isValidEmail($email);
      isValidLogin($login);
      isValidPassword($pass);
      
      $result = mysqli_query($link, "select login, email FROM User WHERE login ='$login' OR email='$email'");
      if(mysqli_num_rows($result) == 0) {
        mysqli_query($link,"SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");
        mysqli_autocommit($link, FALSE);
        // $query = mysqli_query($link, "insert into User(login, hash, salt, id_user_type, email) values 
        // ('$login', sha1(concat('$salt','@@', '$pass')), '$salt', 3, '$email');");

        $query = mysqli_query($link, "CALL createUser('$login', sha1(concat('$salt','@@', '$pass')),'$salt', 3, '$email');");
        
        mysqli_commit($link);
        mysqli_autocommit($link, TRUE);

        echo "registered";
      } else {
        $row = mysqli_fetch_assoc($result);
        if($login == $row['login']) {
          throw new Exception ("<div class=\"alert alert-danger\"><center>Użytownik o tym loginie już istnieje.</center></div>");
        }
        else if($email == $row['email']) {
          throw new Exception ("<div class=\"alert alert-danger\"><center>Email w użyciu.</center></div>");
        }
        else {
          throw new Exception ("<div class=\"alert alert-danger\"><center>Blad podczas dodawania rekordu do bazy!</center></div>");
        }
      } 
    }
      catch (Exception $error) {
          mysqli_rollback($link);
          echo $error->getMessage();
      }
    }   
?>