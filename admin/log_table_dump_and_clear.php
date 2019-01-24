<?php 
    require('../shared/header.php');
    require('../shared/navbar.php');
    include '../database_connection.php';

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    function checkIfFileOpenedProperly($f) {
        if(!$f) {
            throw new Exception("Blad podczas otwarcia pliku!");
        }
    }

    function checkIfFileClosedProperly($f) {
        if(!$f) {
            throw new Exception("Blad podczas zamkniecia pliku!");
        }
    }

    if(isset($_POST['db_name'])) {
        include '../database_connection.php';
        $db_name = mysqli_real_escape_string($link, $_POST['db_name']);
        $date = date("d_m_Y_H_i");
        $path = "db_dumps/{$db_name}_{$date}.txt";

        try {
            mysqli_query($link,"SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");
            mysqli_autocommit($link, FALSE);

            $fp = fopen($path, "w");
            checkIfFileOpenedProperly($fp);
            $res = mysqli_query($link, "select * from Login_logs;");

            $numResults = mysqli_num_rows($res);
            if($numResults == 0) {
                throw new Exception("Brak danych w tabeli");
            }

            //fetch column names
            $row = mysqli_fetch_assoc($res);
            $line = "";
            $comma = "";
            foreach($row as $name => $value) {
                $line .= $comma . '"' . str_replace('"', '""', $name) . '"';
                $comma = ",";
            }
            $line .= "\n";
            fputs($fp, $line);
            
            //back to the start
            mysqli_data_seek($res, 0);
            
            //fetch data
            while($row = mysqli_fetch_assoc($res)) {
            
                $line = "";
                $comma = "";
                foreach($row as $value) {
                    $line .= $comma . '"' . str_replace('"', '""', $value) . '"';
                    $comma = ",";
                }
                $line .= "\n";
                fputs($fp, $line);
            
            }

            $res = mysqli_query($link, "Delete from Login_logs;");

            mysqli_commit($link);
            mysqli_autocommit($link, TRUE);
            fclose($fp);
            checkIfFileClosedProperly($fp);
            echo "pomyslnie stworzono dumpa :)";
        } 
        catch (Exception $error) {
            mysqli_rollback($link);
            echo $error->getMessage();
        }
    }
?>

<div class="container">
  <div class="panel panel-info">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-12"> 
            <form method="post">
                <div class="form-group">
                    <div class="alert alert-info" role="alert">
                        <strong>Stwórz kopię tabeli z logami użytkowników do pliku txt i wyczyść tabelę:</strong>
                    </div>
                    <?php                       
                        echo "<select class=\"form-control\" name=\"db_name\">";
                        echo "<option value=\"Login_logs\">Login_logs</option>";
                        echo "</select>";
                    ?>
                </div>
                <button type="submit" class="btn btn-primary">Zapisz</button>
            </form>
        </div>
        </div>
    </div>
  </div>
</div>



<?php 
  require('../shared/footer.php');
?>