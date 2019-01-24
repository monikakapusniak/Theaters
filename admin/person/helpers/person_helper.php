<?php
    include '../../../database_connection.php';

    if(isset($_POST)) {
        $id_person = mysqli_real_escape_string($link, $_POST['id_person']);
        $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
        $firstname = mysqli_real_escape_string($link, $_POST['firstname']);
        $id_gender = mysqli_real_escape_string($link, $_POST['id_gender']);
        $id_gender = intval($id_gender);
        $id_city = mysqli_real_escape_string($link, $_POST['id_city']);
        $id_city = intval($id_city);
        $photo_name = mysqli_real_escape_string($link, $_POST['photo_name']);
        $date_of_birth = mysqli_real_escape_string($link, $_POST['date_of_birth']);
        //$date_of_birth = validateDate($date_of_birth, 'Y-m-d');
        $date_of_death = mysqli_real_escape_string($link, $_POST['date_of_death']);
        //$date_of_death = validateDate($date_of_death, 'Y-m-d');
        $date_of_birth = strtotime($date_of_birth);
        $date_of_birth = date('Y-m-d H:i', $date_of_birth);
        $date_of_death = strtotime($date_of_death);
        $date_of_death = date('Y-m-d H:i', $date_of_death);

        //update
        if(isset($_POST['id_person'])) {          
            $query =  
            "update 
                Person 
            set  
                lastname = '$lastname', 
                firstname = '$firstname', 
                id_gender = '$id_gender', 
                id_city = '$id_city', 
                photo_name = '$photo_name',
                date_of_birth = '$date_of_birth', 
                date_of_death = '$date_of_death'
            where
                id_person='$id_person';";

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
            $query = mysqli_query($link, "insert into Person(lastname, firstname, id_gender, id_city, photo_name, date_of_birth, date_of_death) values ('$lastname', '$firstname', '$id_gender', '$id_city', '$photo_name', '$date_of_birth', '$date_of_death');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>