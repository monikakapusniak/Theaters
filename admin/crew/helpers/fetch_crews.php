<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    $query = "select kind, CONCAT(firstname, ' ', lastname) as name, concat(spectacle_name, ' ', convertDatetimeToDateString(show_date)) as showdate, id_crew from Crew c NATURAL JOIN Crew_type natural join Person Left join Shows sh on sh.id_show = c.id_show left join Spectacle sp on sp.id_spectacle = sh.id_spectacle;";
    $result = mysqli_query($link, $query);

    if($result) {
        echo json_encode(array("data" => $result->fetch_all()));
    }
    else {
        echo "Database characters fetch fail.";
        exit();
    }
?>