<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();


    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_address = mysqli_real_escape_string($link, $_POST['id_address']);
        $street = mysqli_real_escape_string($link, $_POST['street']);
        $number = mysqli_real_escape_string($link, $_POST['number']);
        $id_city = mysqli_real_escape_string($link, $_POST['id_city']);
        $id_city = intval($id_city);
        $postal_code = mysqli_real_escape_string($link, $_POST['postal_code']);

        //update
        if(isset($_POST['id_address'])) {          
            $query =  
            "update 
                Address 
            set  
                street = '$street', 
                number = '$number', 
                id_city = '$id_city', 
                postal_code = '$postal_code'
            where
                id_address='$id_address';";
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
            $query = mysqli_query($link, "insert into Address(street, number, id_city, postal_code) values ('$street', '$number', '$id_city', '$postal_code');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>