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
      $result = mysqli_query($link, "delete from Address where id_address = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć adresu, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateAddress();">Dodaj adres</button>
    
    <div id="createAddress" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania adresu.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateUser();">
          <div class="form-group">
              <label for="street">Ulica:</label>
              <input type="text" class="form-control" id="street" required>
          </div>

          <div class="form-group">
              <label for="number">Numer domu/mieszkania:</label>
              <input type="text" class="form-control" id="number" required>
          </div>

          <div class="form-group">
            <label for="id_city">Miasto:</label>
            
            <?php
              //select with cities
              include '../../database_connection.php';
              $query = mysqli_query($link, "select id_city, city from City;");
              echo "<select class=\"form-control\" id=\"id_city\" required>";
                while ($row = mysqli_fetch_assoc($query)){
                    extract($row);
                    echo "<option value=\"{$id_city}\">{$city}</option>";
                }
              echo "</select>";
            ?>
          </div>

          <div class="form-group">
              <label for="postal_code">Kod pocztowy:</label>
              <input type="text" class="form-control" id="postal_code" required>
          </div>

          <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista adresów.</strong>
    </div>
    <table id="addresses" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Ulica</th>
            <th>Numer domu/mieszkania</th>
            <th>Miasto</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateUser() {
    $.post( "helpers/address_helper.php", {street: $('#street').val(), number: $('#number').val(), id_city: $("#id_city option:selected").val(), postal_code: $("#postal_code").val()}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania adresu.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#addresses').DataTable().ajax.reload();
        $('#createAddress').find('input:text').val('');
        toggleCreateAddress();
        $('#createInfo').html('Pomyślnie dodano adres.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateAddress() {
    var createAddressDiv = document.getElementById("createAddress");
    if (createAddressDiv.style.display === "none") {
      createAddressDiv.style.display = "block";
    } else {
      createAddressDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#addresses').DataTable({
      ajax: 'helpers/fetch_addresses.php',
      columnDefs: [
      {
        targets: 3,
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

