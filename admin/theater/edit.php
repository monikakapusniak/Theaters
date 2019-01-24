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

  $query = "select Theater.id_theater, Theater.theater_name, Address.id_address, Address.street, Address.number, City.city, Address.postal_code, Theater.photo_name from Theater JOIN (Address, City) on (Theater.id_address=Address.id_address and Address.id_city=City.id_city) WHERE id_theater='$id'";
  $result = mysqli_query($link, $query);
  if($result) {
    $row = mysqli_fetch_assoc($result);
  }
  else {
    echo "Database theater fetch fail.";
    exit();
  }
  $row['theater_name'] = htmlspecialchars($row['theater_name']);
  $row['street'] = htmlspecialchars($row['street']);
  $row['number'] = htmlspecialchars($row['number']);
  $row['city'] = htmlspecialchars($row['city']);
  $row['postal_code'] = htmlspecialchars($row['postal_code']);
?>

<div class="container">  
  <div class="col-md-12">
  <a href="/admin/theater/"><button class='btn btn-info'>Powrót do listy teatrów</button></a><br/><br/>
    
    <div id="updateInfo"></div>

    <form action="javascript:void(0)" onSubmit="tryChangeTheater();">
      <div class="form-group">
          <label for="theater_name">Nazwa:</label>
          <input type="text" class="form-control" id="theater_name" value="<?php echo $row['theater_name']; ?>" required>
      </div>
      <div class="form-group">
        <label for="id_address">Adres:</label>
        <?php
            $query = mysqli_query($link, "select Address.id_address, Address.street, Address.number, City.city, Address.postal_code from Address join City on (Address.id_city = City.id_city);");
            echo "<select class=\"form-control\" id=\"id_address\" required>";
            while ($rowSelect = mysqli_fetch_assoc($query)){
                $row['street'] = htmlspecialchars($row['street']);
                $row['number'] = htmlspecialchars($row['number']);
                $row['city'] = htmlspecialchars($row['city']);
                $row['postal_code'] = htmlspecialchars($row['postal_code']);
                if($rowSelect['id_address'] == $row['id_address']) {
                  echo "<option value=\"$rowSelect[id_address]\" selected>";
                }
                else {
                  echo "<option value=\"$rowSelect[id_address]\">";
                }
                echo "$rowSelect[street] $rowSelect[number], $rowSelect[city] $rowSelect[postal_code]</option>";
            }
          echo "</select>";
        ?>
        <input type="button" onclick="location.href='../address/'" value="Dodaj adres">
      </div>
      <div class="form-group">
            <label for="photo_name">Zmień zdjęcie teatru:</label>
            <?php
              $dir = '../../media/';
              $files = array_diff(scandir($dir), array('..', '.'));
              echo "<select class=\"form-control\" id=\"photo_name\" >";
              foreach ($files as $value){
                if($value == $row['photo_name']) {
                  echo "<option value=\"{$value}\" selected>{$value}</option>>";
                }
                else {
                  echo "<option value=\"{$value}\">{$value}</option>>";
                }
              }
              echo "</select>";
            ?>

            <input type="button" onclick="location.href='../images/'" value="Dodaj zdjęcie"> 
          </div>

      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
  require('../../shared/footer.php');
?>

<script>
  function tryChangeTheater() {
      $.post( "helpers/theater_helper.php", 
      {theater_name: $('#theater_name').val(), id_address: $("#id_address option:selected").val(), photo_name: $("#photo_name").val(),
      id_theater: <?php echo $row['id_theater']; ?>}, function() {
      }).done(function(data) {
        if(data == "fail") {
          $('#updateInfo').html('Błąd podczas aktualizacji opisu teatru.').attr('class', 'alert alert-warning');
        }
        else {
          $('#updateInfo').html('Pomyślnie zaktualizowano opis teatru.').attr('class', 'alert alert-success');
        }
      });
  }
</script>