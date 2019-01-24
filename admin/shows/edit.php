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

  $query = "select id_show, spectacle_name, show_date, hall_name, id_spectacle, id_hall from Shows NATURAL JOIN Spectacle NATURAL JOIN Hall WHERE id_show='$id'";  
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database show fetch fail.";
    exit();
  }
  $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
  $row['show_date'] = htmlspecialchars($row['show_date']);
  $row['hall_name'] = htmlspecialchars($row['hall_name']);
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/shows/"><button class='btn btn-info'>Powrót do listy występów</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateUser();">

    <div class="form-group">
        <label for="id_spectacle">Spektakl:</label>
        <?php
          $query = mysqli_query($link, "select id_spectacle, spectacle_name from Spectacle;");
          echo "<select class=\"form-control\" id=\"id_spectacle\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $row['spectacle_name'] = htmlspecialchars($row['spectacle_name']);
                if($rowSelect['id_spectacle'] == $row['id_spectacle']) {
                  echo "<option value=\"$rowSelect[id_spectacle]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_spectacle]\">";
                }
                echo "$rowSelect[spectacle_name]</option>";
            }
          echo "</select>";
        ?>
      </div>

      <div class="form-group">
          <label for="show_date">Data występu:</label>
          <input type="datetime-local" class="form-control" id="show_date" value="<?php echo date('Y-m-d\TH:i', strtotime($row['show_date'])); ?>" required>
      </div>
      <div class="form-group">
        <label for="id_hall">Teatr:</label>
        <?php
          $query = mysqli_query($link, "select id_hall, hall_name from Hall;");
          echo "<select class=\"form-control\" id=\"id_hall\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $row['hall_name'] = htmlspecialchars($row['hall_name']);
                if($rowSelect['id_hall'] == $row['id_hall']) {
                  echo "<option value=\"$rowSelect[id_hall]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_hall]\">";
                }
                echo "$rowSelect[hall_name]</option>";
            }
          echo "</select>";
        ?>
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
      $.post( "helpers/show_helper.php", 
      {id_spectacle: $("#id_spectacle option:selected").val(), show_date: $('#show_date').val(), id_hall: $("#id_hall option:selected").val(),
      id_show: <?php echo $row['id_show']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji występu.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano występ.').attr('class', 'alert alert-success');
        }
      });
  }

</script>