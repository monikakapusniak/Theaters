<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    $query = "select spectacle_name, convertDatetimeToDateString(date_of_premiere) as date_of_premiere, genre, id_spectacle from Spectacle natural join Genre;";
    $result = mysqli_query($link, $query);

    if($result) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database shows fetch fail.";
        exit();
    }
?>