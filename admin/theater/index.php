<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
  require('../../shared/header.php');
  require('../../shared/navbar.php');

  if($user_type != "Admin") {
      header("location: /shared/error.php");
  }
?>

<div class="container">  
  <div class="col-md-12">
  <?php
  if(isset($_GET['id']))
  {
      $id = mysqli_real_escape_string($link, $_GET['id']);
      
      $result = mysqli_query($link, "delete from Theater where id_theater = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć informacji o teatrze, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateTheater();">Dodaj teatr</button>
    
    <div id="createTheater" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania teatru.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateTheater();">
          <div class="form-group">
              <label for="theater_name">Nazwa teatru:</label>
              <input type="text" class="form-control" id="theater_name" required>
          </div>

          <div class="form-group">
            <label for="id_address" >Adres:</label>
            
            <?php
              //select address
              include '../../database_connection.php';
              $query = mysqli_query($link, "select Address.id_address, Address.street, Address.number, City.city, Address.postal_code from Address join City on (Address.id_city = City.id_city);");
              echo "<select class=\"form-control\" id=\"id_address\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_address}\">{$street}, {$number}, {$city}, {$postal_code}</option>";
                }
              echo "</select>";
            ?>

              <input type="button" onclick="location.href='../address/'" value="Dodaj adres">

          </div>

          <div class="form-group">
            <label for="photo_name">Wybierz zdjęcie teatru:</label>
            <?php
              $dir = '../../media/';
              $files = array_diff(scandir($dir), array('..', '.'));
              echo "<select class=\"form-control\" id=\"photo_name\" >";
              foreach ($files as $value){
                echo "<option value=\"{$value}\">{$value}</option>";
              }
              echo "</select>";
            ?>

            <input type="button" onclick="location.href='../images/'" value="Dodaj zdjęcie"> 
          </div>
          

          <button type="submit" class="btn btn-info" name="form_submit">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista teatrów.</strong>
    </div>

    <table id="theaters" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Nazwa teatru</th>
            <th>Adres</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateTheater() {
    $.post( "helpers/theater_helper.php", {theater_name: $('#theater_name').val(), id_address: $("#id_address option:selected").val(), photo_name: $("#photo_name").val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania teatru.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#theaters').DataTable().ajax.reload();
        $('#createTheater').find('input:text').val('');
        toggleCreateTheater();
        $('#createInfo').html('Pomyślnie dodano teatr.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateTheater() {
    var createTheaterDiv = document.getElementById("createTheater");
    if (createTheaterDiv.style.display === "none") {
      createTheaterDiv.style.display = "block";
    } else {
      createTheaterDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#theaters').DataTable({
      ajax: 'helpers/fetch_theaters.php',
      columnDefs: [
      {
        targets: 2,
        render: function (data)
        {
          var detailsButton = "<a href=\"details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";
          var editButton = "<a href=\"edit.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-warning'>Edycja</button></a>";
          var deleteButton = "<a href=\"?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-danger'>Usuń</button></a>";
          return detailsButton + " " + editButton + " " + deleteButton;
        }
      }]
    });
  });
</script>

