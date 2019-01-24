
<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_person = mysqli_real_escape_string($link, $_POST['id_person']);
        $description = mysqli_real_escape_string($link, $_POST['description']);

        $date_of_info = mysqli_real_escape_string($link, $_POST['date_of_info']);
        $date_of_info = strtotime($date_of_info);
        $date_of_info = date('Y-m-d H:i', $date_of_info);

        //update
        if(isset($_POST['id_person_info'])) {  
            $id_person_info = mysqli_real_escape_string($link, $_POST['id_person_info']);
            $query =  
            "update 
                Person_info 
            set  
                description = '$description',
                id_person = '$id_person',
                date_of_info = '$date_of_info' 
            where
                id_person_info='$id_person_info';";

            $result = mysqli_query($link, $query);

            if($result) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
        //create
        else {          
            $query = mysqli_query($link, "insert into Person_info(description, id_person, date_of_info) values ('$description', '$id_person', '$date_of_info');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>