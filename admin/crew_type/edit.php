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

  $query = "select kind, id_crew_type from Crew_type where id_crew_type='$id';";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database crew types fetch fail.";
    exit();
  }
  $row['kind'] = htmlspecialchars($row['kind']);  
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/crew_type/"><button class='btn btn-info'>Powrót do listy posad</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateCrewType();">
      <div class="form-group">
          <label for="kind">Nazwa posady:</label>
          <input type="text" class="form-control" id="kind" value="<?php echo $row['kind']; ?>" required>
      </div>
      
      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryUpdateCrewType() {
      $.post( "helpers/crewtype_helper.php", 
      {kind: $('#kind').val(), id_crew_type: <?php echo $row['id_crew_type']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji posady.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano posade.').attr('class', 'alert alert-success');
        }
      });
  }
</script>