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

  $query = "select id_hall, hall_name, theater_name, capacity, id_theater from Hall NATURAL JOIN Theater WHERE id_hall='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database hall fetch fail.";
    exit();
  }
  $row['hall_name'] = htmlspecialchars($row['hall_name']);
  $row['theater_name'] = htmlspecialchars($row['theater_name']);
  $row['capacity'] = htmlspecialchars($row['capacity']);
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/hall/"><button class='btn btn-info'>Powrót do listy sal</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateUser();">
      <div class="form-group">
          <label for="hall_name">Nazwa sali:</label>
          <input type="text" class="form-control" id="hall_name" value="<?php echo $row['hall_name']; ?>" required>
      </div>

      <div class="form-group">
        <label for="id_theater">Teatr:</label>
        <?php
          $query = mysqli_query($link, "select id_theater, theater_name from Theater;");
          echo "<select class=\"form-control\" id=\"id_theater\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['theater_name'] = htmlspecialchars($rowSelect['theater_name']);
                if($rowSelect['id_theater'] == $row['id_theater']) {
                  echo "<option value=\"$rowSelect[id_theater]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_theater]\">";
                }
                echo "$rowSelect[theater_name]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
          <label for="capacity">Pojemność:</label>
          <input type="text" class="form-control" id="capacity" value="<?php echo $row['capacity']; ?>" required>
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
      $.post( "helpers/hall_helper.php", 
      {hall_name: $('#hall_name').val(), id_theater: $("#id_theater option:selected").val(), capacity: $('#capacity').val(),
      id_hall: <?php echo $row['id_hall']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji sali.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano salę.').attr('class', 'alert alert-success');
        }
      });
  }
</script>