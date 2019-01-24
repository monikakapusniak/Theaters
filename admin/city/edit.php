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

  $query = "select id_city, city from City where id_city='$id';";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database city fetch fail.";
    exit();
  }
  $row['city'] = htmlspecialchars($row['city']);  
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/city/"><button class='btn btn-info'>Powrót do listy miast</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateUser();">
      <div class="form-group">
          <label for="city">Nazwa miasta:</label>
          <input type="text" class="form-control" id="city" value="<?php echo $row['city']; ?>" required>
      </div>
      
      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryUpdateUser() {
      $.post( "helpers/city_helper.php", 
      {city: $('#city').val(),
      id_city: <?php echo $row['id_city']; ?>}, function() {
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