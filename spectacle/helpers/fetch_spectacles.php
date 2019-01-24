<?php
    include '../../database_connection.php';

    $query = "select * from view_all_spectacle_index";
    $result = mysqli_query($link, $query);

    if($result ) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database spectacles fetch fail.";
        exit();
    }
?>