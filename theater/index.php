<?php 
  require('../shared/header.php');
  require('../shared/navbar.php');
?>

<div class="container">  
  <div class="panel panel-info">
    <div class="panel-body">
        <div class="col-md-12"> 
            <div class="alert alert-info">
                <strong>Lista teatrów.</strong>
            </div>
            <table id="theaters" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Adres</th>
                        <th>Miejscowość</th>
                        <th>Kod pocztowy</th>
                        <th>Akcja</th>
                    </tr>
                </thead>
            </table>
        </div>   
    </div>
  </div>
</div>

<?php 
  require('../shared/footer.php');
?>

<script>
  $(document).ready(function() {
    $('#theaters').DataTable({
      ajax: 'helpers/fetch_theaters.php',
      columnDefs: [
      {
        targets: 4,
        render: function (data)
        {
          var detailsButton = "<a href=\"details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";

          return detailsButton;
        }
      }]
    });
  });
</script>