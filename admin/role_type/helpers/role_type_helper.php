
<?php
    include '../../../database_connection.php';
    require($_SERVER["DOCUMENT_ROOT"] . "/authorization_helper.php");
    $user_type = getUserType();

    if($user_type != "Admin") {
        header("location: /shared/error.php");
    }

    if(isset($_POST)) {
        $id_role_type = mysqli_real_escape_string($link, $_POST['id_role_type']);
        $role_type = mysqli_real_escape_string($link, $_POST['role_type']);

        //update
        if(isset($_POST['id_role_type'])) {          
            $query =  
            "update 
                Role_type 
            set  
                role_type = '$role_type' 
            where
                id_role_type='$id_role_type';";

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
            $query = mysqli_query($link, "insert into Role_type(role_type) values ('$role_type');");
            if($query) {
                echo "success";
            }
            else {
                echo "fail";
            }
        }
    }
?>