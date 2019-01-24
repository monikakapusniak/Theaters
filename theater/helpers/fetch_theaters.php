<?php
    include '../../database_connection.php';

    $query = "select theater_name, CONCAT(street, ' ', number), city, postal_code, id_theater from Theater NATURAL JOIN Address NATURAL JOIN City;";
    $result = mysqli_query($link, $query);

    if($result ) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database theaters fetch fail.";
        exit();
    }
?>