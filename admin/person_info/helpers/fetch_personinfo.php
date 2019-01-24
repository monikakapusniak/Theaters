<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    $query = "select concat(firstname, ' ', lastname) as name, description, id_person_info from Person_info natural join Person;";
    $result = mysqli_query($link, $query);

    if($result) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database person_info fetch fail.";
        exit();
    }
?>