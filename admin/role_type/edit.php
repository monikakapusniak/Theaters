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

  $query = "select id_role_type, role_type from Role_type where id_role_type='$id';";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database role_type fetch fail.";
    exit();
  }
  $row['role_type'] = htmlspecialchars($row['role_type']);  
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/role_type/"><button class='btn btn-info'>Powrót do listy typów ról</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateUser();">
      <div class="form-group">
          <label for="role_type">Nazwa roli:</label>
          <input type="text" class="form-control" id="role_type" value="<?php echo $row['role_type']; ?>" required>
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
      $.post( "helpers/role_type_helper.php", 
      {role_type: $('#role_type').val(),
      id_role_type: <?php echo $row['id_role_type']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji roli.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano roli.').attr('class', 'alert alert-success');
        }
      });
  }
</script>