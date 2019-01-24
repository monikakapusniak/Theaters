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

  $query = "select id_address, street, number, city, postal_code, id_city from Address NATURAL JOIN City WHERE id_address='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database address fetch fail.";
    exit();
  }

  $row['id_address'] = htmlspecialchars($row['id_address']);
  $row['street'] = htmlspecialchars($row['street']);
  $row['number'] = htmlspecialchars($row['number']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['postal_code'] = htmlspecialchars($row['postal_code']);
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/address/"><button class='btn btn-info'>Powrót do listy adresów</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryUpdateUser();">
      <div class="form-group">
          <label for="street">Ulica:</label>
          <input type="text" class="form-control" id="street" value="<?php echo $row['street']; ?>" required>
      </div>
      <div class="form-group">
          <label for="number">Numer domu/mieszkania:</label>
          <input type="text" class="form-control" id="number" value="<?php echo $row['number']; ?>" required>
      </div>
      <div class="form-group">
        <label for="id_city">Miasto:</label>
        <?php
          $query = mysqli_query($link, "select id_city, city from City;");
          echo "<select class=\"form-control\" id=\"id_city\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $rowSelect['city'] = htmlspecialchars($rowSelect['city']);
                if($rowSelect['id_city'] == $row['id_city']) {
                  echo "<option value=\"$rowSelect[id_city]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_city]\">";
                }
                echo "$rowSelect[city]</option>";
            }
          echo "</select>";
        ?>
      </div>
      <div class="form-group">
          <label for="postal_code">Kod pocztowy:</label>
          <input type="text" class="form-control" id="postal_code" value="<?php echo $row['postal_code']; ?>" required>
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
      $.post( "helpers/address_helper.php", 
      {street: $('#street').val(), number: $('#number').val(), id_city: $("#id_city option:selected").val(), postal_code: $("#postal_code").val(),
      id_address: <?php echo $row['id_address']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji adresu.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano adres.').attr('class', 'alert alert-success');
        }
      });
  }
</script>