<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
  require('../shared/header.php');
  require('../shared/navbar.php');

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../database_connection.php';

  $query = "select spectacle_name, description, number_of_ratings, sum_of_ratings, genre, createUrlPathToImg(photo_name) as photo_name, convertDatetimeToDateString(date_of_premiere) as date_of_premiere, duration from Spectacle NATURAL JOIN Genre where id_spectacle='$id';";
  $result = mysqli_query($link, $query);

  if(mysqli_num_rows($result) == 0) {
    header("location: /shared/error.php");
  }

  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database fetch fail.";
    exit();
  }

  $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
  $row['description'] = htmlspecialchars($row['description']);
  $row['number_of_ratings'] = htmlspecialchars($row['number_of_ratings']);
  $row['sum_of_ratings'] = htmlspecialchars($row['sum_of_ratings']);
  $row['genre'] = htmlspecialchars($row['genre']);
  $row['photo_name'] = htmlspecialchars($row['photo_name']);
  $row['date_of_premiere'] = htmlspecialchars($row['date_of_premiere']);
  $row['duration'] = htmlspecialchars($row['duration']);

  //reply koment
  if(isset($_POST['comment_reply_text']) && !empty($_POST['comment_reply_text'])) {
    if($isLoggedIn) {
      $id_user = getIdUser();
      $comment_text = mysqli_real_escape_string($link, $_POST['comment_reply_text']);
      $reply_id = mysqli_real_escape_string($link, $_POST['reply_id']);
      $node_id = mysqli_real_escape_string($link, $_POST['node_id']);

      if(empty($node_id)) {
        $node_id = $reply_id;
      }

      $query = mysqli_query($link, "insert into Comments(comment_text, comment_date, id_user, id_spectacle, id_node, id_reply)
      values ('$comment_text', NOW(), '$id_user', '$id', '$node_id', '$reply_id');");
      if(!$query) {
        echo "Insert fail.";
        exit();
      }
      $new_comment_id =  mysqli_insert_id($link);
      echo '<meta http-equiv="refresh" content="0; URL=/spectacle/details.php?id='.$id.'&comment='.$new_comment_id.'">';
      // die(header("location: /spectacle/details.php?id=$id&comment=$new_comment_id"));
    }
    else {
      echo "Not logged in.";
    }
  }

  //main koment
  if(isset($_POST['comment_main_text']) && !empty($_POST['comment_main_text'])) {
    if($isLoggedIn) {
      $id_user = getIdUser();
      $result = mysqli_query($link, "select id_comment FROM Comments WHERE id_user='$id_user' and id_spectacle='$id' and id_node is null and id_reply is null;");
      if(mysqli_num_rows($result) != 0) {
        echo "Juz glosowales!";
        exit();
      }

      $comment_text = mysqli_real_escape_string($link, $_POST['comment_main_text']);
      $rating = mysqli_real_escape_string($link, $_POST['rating']);
      $rating = intval($rating);

      $query = mysqli_query($link, "insert into Comments(comment_text, comment_date, id_user, id_spectacle, id_node, id_reply)
      values ('$comment_text', NOW(), '$id_user', '$id', null, null);");
      if(!$query) {
        echo "Insert fail.";
        exit();
      }
      $new_comment_id =  mysqli_insert_id($link);

      if($rating == 5 || $rating == 4 || $rating == 3 || $rating == 2 || $rating == 1) {
        $query = mysqli_query($link, "UPDATE Spectacle SET sum_of_ratings=sum_of_ratings+'$rating', number_of_ratings=number_of_ratings+1 WHERE id_spectacle='$id';");
        if(!$query) {
          echo "Update fail.";
        }
      }
      echo '<meta http-equiv="refresh" content="0; URL=/spectacle/details.php?id='.$id.'&comment='.$new_comment_id.'">';
      // die(header("location: /spectacle/details.php?id=$id&comment=$new_comment_id"));
    }
    else {
      echo "Not logged in.";
    }
  }
?>
<div class="container">  
  <a href="/spectacle"><button class='btn btn-info'>Powrót do listy spektakli</button></a><br/><br/>
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
      <div class="col-md-3" align="center"><img src="<?php echo $row['photo_name'];?>" class="img-responsive" alt="Image not found" onerror="this.src='/media/default/default_spectacle.png';"> </div>        
        <div class=" col-md-9"> 
          <div class="alert alert-info" role="alert">
            <strong>Podstawowe informacje:</strong>
          </div>
          <table class="table table-user-information">
            <tbody>
              <tr>
                <td><strong>Nazwa:</strong></td>
                <td><?php echo $row['spectacle_name']; ?></td>
              </tr>
              <tr>
                <td><strong>Gatunek:</strong></td>
                <td><?php echo $row['genre']; ?></td>
              </tr>
              <tr>
                <td><strong>Opis:</strong></td>
                <td><?php echo $row['description']; ?></td>
              </tr>
              <tr>
                <td><strong>Czas trwania:</strong></td>
                <td><?php echo $row['duration']; ?></td>
              </tr>
              <tr>
                <td><strong>Data premiery:</strong></td>
                <td><?php echo $row['date_of_premiere']; ?></td>
              </tr>
              <tr>
                <td><strong>Średnia ocena:</strong></td>
                <td>
                <?php
                if($row['number_of_ratings'] != 0) {
                  echo round($row['sum_of_ratings']/$row['number_of_ratings'], 2);
                  echo " (Liczba głosujących: $row[number_of_ratings])";
                }
                else {
                  echo "Brak głosów.";
                }
                ?>
                </td>
              </tr>           
            </tbody>
          </table>
        </div>
      </div>
      <br/>
      <div class="row">
        <div class=" col-md-6"> 
          <table class="table table-user-information">
          <div class="alert alert-info" role="alert">
            <strong>Najbliższe występy:</strong>
          </div>
            <tbody>
            <table id="incomingShows" class="display" style="width:100%">
              <thead>
                  <tr>
                    <th>Data</th>
                    <th>Akcja</th>
                  </tr>
              </thead>
            </table>  
            </tbody>
          </table>
        </div>
        <div class=" col-md-6"> 
          <table class="table table-user-information">
          <div class="alert alert-info" role="alert">
            <strong>Archiwalne występy:</strong>
          </div>
            <tbody>
              <table id="pastShows" class="display" style="width:100%">
                <thead>
                    <tr>
                      <th>Data</th>
                      <th>Akcja</th>
                    </tr>
                </thead>
              </table>  
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="panel panel-info">
    <div class="panel-body">
      <div class="col-md-12"> 
        <div class="alert alert-info" role="alert"  id='scrollFocus'>
          <strong>Opinie:</strong>
        </div>

        <?php
          //glowny koment
          if(checkIfLoggedIn()) {
            $userId = getIdUser();
            $result = mysqli_query($link, "select id_comment FROM Comments WHERE id_user='$userId' and id_spectacle='$id' and id_node is null and id_reply is null;");
            if(mysqli_num_rows($result) == 0) {
              echo 
              "<div class='panel panel-info' id='comment_main_div'>
              <div class='panel-body'>
                <h4>Zostaw swoją opinie</h4>
                <form action='' method='post'>
                  <div>
                      <div class='form-group'>
                        <label for='stars_rating'>Twoja ocena:</label>
                        <div class='star-rating'>
                          <input id='star-5' type='radio' name='rating' value='5'>
                          <label for='star-5' title='5 gwiazdek'>
                              <i class='active fa fa-star' aria-hidden='true'></i>
                          </label>
                          <input id='star-4' type='radio' name='rating' value='4'>
                          <label for='star-4' title='4 gwiazdki'>
                              <i class='active fa fa-star' aria-hidden='true'></i>
                          </label>
                          <input id='star-3' type='radio' name='rating' value='3'>
                          <label for='star-3' title='3 gwiazdki'>
                              <i class='active fa fa-star' aria-hidden='true'></i>
                          </label>
                          <input id='star-2' type='radio' name='rating' value='2'>
                          <label for='star-2' title='2 gwiazdki'>
                              <i class='active fa fa-star' aria-hidden='true'></i>
                          </label>
                          <input id='star-1' type='radio' name='rating' value='1' checked>
                          <label for='star-1' title='1 gwiazdka'>
                              <i class='active fa fa-star' aria-hidden='true'></i>
                          </label>
		                    </div>
                      </div>
                  </div>
                  <div>
                      <div class='form-group'>
                        <label for='comment_main_text'>Treść komentarza:</label>
                        <textarea class='form-control' name='comment_main_text' id='comment_main_text' rows='5'></textarea>
                      </div>
                  </div>
                  <div>
                      <button type='submit' name='comment_main_submit' id='comment_main_submit' class='btn btn-info'>Dodaj</button>
                  </div>
                </form>
              </div>
            </div>";
            }
            echo "
            <!-- formularz odpowiedzi na komentarz -->
            <div class='panel panel-info' style='display: none;' id='comment_reply_div'>
              <div class='panel-body'>
                <form action='' method='post'>
                  <div class='form-group'>
                    <div class='panel panel-info'>
                      <div class='panel-heading clearfix'>
                        <strong>Odpowiadasz na komentarz:</strong>
                        <div class='btn-group pull-right'>
                          <button class='btn btn-info btn-sm' onClick='deleteCommentReply(); return false;'>Usuń odpowiedź</button>
                        </div>
                      </div>
                      <div class='panel-body' style='word-break: break-all;'>
                        <div id='comment_quote_text'></div>
                      </div>
                    </div>
                  </div>
                  <div>
                      <div class='form-group'>
                        <label for='comment_text'>Treść komentarza:</label>
                        <textarea class='form-control' name='comment_reply_text' id='comment_reply_text' rows='5'></textarea>
                      </div>
                  </div>
                  <div>
                      <input type='hidden' name='reply_id' id='reply_id' value=''/>
                      <input type='hidden' name='node_id' id='node_id' value=''/>
                      <button type='submit' name='comment_reply_submit' id='comment_reply_submit' class='btn btn-info'>Dodaj</button>
                  </div>
                </form>
              </div>
            </div>";
          }
        ?>

        <div class="panel panel-info">
          <div class="panel-body">
            <h4>Opinie innych użytkowników:</h4>
            <?php
              require("helpers/get_comments.php");
            ?>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>

<?php
  require('../shared/footer.php');
?>

<script>
  function getParameterByName(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"); 
      var results = regex.exec(location.search);

      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }
  $(document).ready(function() {
    $('#incomingShows').DataTable({
      "ajax": {     
        "url": "helpers/fetch_inc_shows.php",
        "data": {id: <?php echo $id;?>} 
      },
      columnDefs: [
      {
        targets: 1,
        render: function (data)
        {
          var detailsButton = "<a href=\"/show/details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";
          return detailsButton;
        }
      }]
    });

    $('#pastShows').DataTable({
      "ajax": {     
        "url": "helpers/fetch_past_shows.php",
        "data": {id: <?php echo $id;?>} 
      },
      columnDefs: [
      {
        targets: 1,
        render: function (data)
        {
          var detailsButton = "<a href=\"/show/details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";
          return detailsButton;
        }
      }]
    });

    var comment = getParameterByName("comment"); 

    $('html, body').animate({scrollTop: $("#comment_" + comment).closest('li').offset().top}, 700);
  });

  function sendIdComment(id){
    var replyTextDiv = document.getElementById("comment_" + id);
    if(replyTextDiv !== null) {
      var replyText = replyTextDiv.textContent;
      $('#comment_quote_text').html(replyText)
      $('#reply_id').val(id);
    }

    var nodeId = $("#comment_" + id).closest('ul').attr('id');
    if(nodeId === undefined) {
      nodeId = '';
    } 
    else {
      nodeId = nodeId.match(/\d+/)[0];
      $('#node_id').val(nodeId);
    }

    var commentReplyDiv = document.getElementById("comment_reply_div");
    if(commentReplyDiv !== null) {
      commentReplyDiv.style.display = "block";
    }
    var commentMainDiv = document.getElementById("comment_main_div");
    if(commentMainDiv !== null) {
      commentMainDiv.style.display = "none";
    }

    $(window).scrollTop($('#scrollFocus').offset().top-20);
  }

  function deleteCommentReply(){
    $('#comment_quote_text').html("")
    $('#reply_id').val('');
    $('#node_id').val('');

    var commentReplyDiv = document.getElementById("comment_reply_div");
    if(commentReplyDiv !== null) {
      commentReplyDiv.style.display = "none";
    }
    var commentMainDiv = document.getElementById("comment_main_div");
    if(commentMainDiv !== null) {
      commentMainDiv.style.display = "block";
    }
  }

  function navigateToComment(id) {
    $('html, body').animate({scrollTop: $("#comment_" + id).closest('li').offset().top}, 700);
  }

  function cleanComment(id) {
    $.post("helpers/clean_comment.php", {clean_id: id}, function() {
    }).done(function(data) {
      alert(data);
      if(data == "Success") {
        var idSpectacle = getParameterByName("id");
        window.location.href="/spectacle/details.php?id=" + idSpectacle + "&comment=" + id;
      }
    });
  }
</script>