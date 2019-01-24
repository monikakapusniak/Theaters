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
      
      $result = mysqli_query($link, "delete from Role_type where id_role_type = '$id' limit 1");
      if(!$result) {
        echo "<b>Nie można usunąć informacji o roli, ponieważ jest już wykorzystana w innym miejscu!</b></br>";
      }
  }
?>
    <button type="button" class="btn btn-alert" onclick="toggleCreateRole();">Dodaj rolę pełnioną przez osobę:</button>
    
    <div id="createRole" style="display: none;">
    <br>
      <div class="well"> 
        <div class="alert alert-warning">
          <strong>Formularz dodawania roli.</strong>
        </div>

        <form action="javascript:void(0)" onSubmit="tryCreateRole();">
          <div class="form-group">
              <label for="role_type">Nazwa roli:</label>
              <input type="text" class="form-control" id="role_type" required>
          </div>

          <button type="submit" class="btn btn-info">Dodaj</button>
        </form>
      </div>
    </div>

    <div id="createInfo"></div>
    <br><br>
    <div class="alert alert-info">
      <strong>Lista ról.</strong>
    </div>
    <table id="roles" class="display" style="width:100%">
      <thead>
          <tr>
            <th>Nazwa roli</th>
            <th>Akcje</th>
          </tr>
      </thead>
    </table>    
</div>

<?php 
  require('../../shared/footer.php');
?>


<script>
  function tryCreateRole() {
    $.post( "helpers/role_type_helper.php", {role_type: $('#role_type').val(),}, function() {
    }).done(function(data) {
      if(data == "fail") {
        $('#createInfo').html('Błąd podczas dodawania roli.').attr('class', 'badge badge-pill badge-danger');
      }
      else {
        var table = $('#roles').DataTable().ajax.reload();
        $('#createRole').find('input:text').val('');
        toggleCreateRole();
        $('#createInfo').html('Pomyślnie dodano rolę.').attr('class', 'badge badge-pill badge-success');
      }
    });
  }

  function toggleCreateRole() {
    var createRoleDiv = document.getElementById("createRole");
    if (createRoleDiv.style.display === "none") {
      createRoleDiv.style.display = "block";
    } else {
      createRoleDiv.style.display = "none";
    }
    $('#createInfo').html("");
  }

  $(document).ready(function() {
    $('#roles').DataTable({
      ajax: 'helpers/fetch_roles.php',
      columnDefs: [
      {
        targets: 1,
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

