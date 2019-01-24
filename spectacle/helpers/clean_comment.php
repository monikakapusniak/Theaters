<?php 
  include '../../database_connection.php';
  include '../../authorization_helper.php';

  //czyszczenie komentarza
  if(isset($_POST['clean_id'])) {
    if(checkIfLoggedIn()) {
      if(getUserType() == "Admin") {
        $comment_id = mysqli_real_escape_string($link, $_POST['clean_id']);

        $query = "update Comments set comment_text = '' where id_comment='$comment_id';";
          $result = mysqli_query($link, $query);
          if(!$result) {
              echo "Fail";
          }
        echo "Success";
      }
      else {
        echo("Access denied.");
      }
    }
    else {
      echo "Not logged in.";
    }
  }