<?php
    include '../../../database_connection.php';

    $query = "select * from view_all_person_2_index;";
    $result = mysqli_query($link, $query);

    if($result) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database person fetch fail.";
        exit();
    }
?>