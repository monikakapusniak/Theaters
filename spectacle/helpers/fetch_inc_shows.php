<?php
    include '../../database_connection.php';

    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

    $query = "select DATE_FORMAT(show_date,'%d/%m/%Y %H:%i'), id_show from Shows where id_spectacle = '$id' and show_date > SYSDATE();";
    $result = mysqli_query($link, $query);

    if($result ) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database inc spectacles fetch fail.";
        exit();
    }
?>