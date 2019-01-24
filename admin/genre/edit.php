<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
  require('../../shared/header.php');
  require('../../shared/navbar.php');

  if($user_type != "Admin") {
      header("location: /shared/error.php");
  }

  $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
  include '../../database_connection.php';

  $query = "select genre, id_genre from Genre where id_genre='$id';";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database city fetch fail.";
    exit();
  }
  $row['genre'] = htmlspecialchars($row['genre']);  
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/genre/"><button class='btn btn-info'>Powrót do listy informacji o osobach</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateGenre();">
      <div class="form-group">
          <label for="genre">Nazwa gatunku:</label>
          <input type="text" class="form-control" id="genre" value="<?php echo $row['genre']; ?>" required>
      </div>
      
      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryUpdateGenre() {
      $.post( "helpers/genre_helper.php", 
      {genre: $('#genre').val(), id_genre: <?php echo $row['id_genre']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji miasta.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano miasto.').attr('class', 'alert alert-success');
        }
      });
  }
</script>