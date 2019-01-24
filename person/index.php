<?php 
  require('../shared/header.php');
  require('../shared/navbar.php');
?>

<div class="container">  
  <div class="panel panel-info">
    <div class="panel-body">
        <div class="col-md-12"> 
            <div class="alert alert-info">
                <strong>Lista osób związanych z teatrem.</strong>
            </div>
            <table id="people" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Miejsce zamieszkania</th>
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
    $('#people').DataTable({
      ajax: 'helpers/fetch_people.php',
      columnDefs: [
      {
        targets: 3,
        render: function (data)
        {
          var detailsButton = "<a href=\"details.php?id=" + encodeURIComponent(data) + "\">" + "<button class='btn btn-info'>Szczegóły</button></a>";

          return detailsButton;
        }
      }]
    });
  });
</script>