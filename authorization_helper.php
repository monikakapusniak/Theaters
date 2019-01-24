<?php
    function checkIfLoggedIn() {
        include 'database_connection.php';

        foreach ($_COOKIE as $k=>$v) {$_COOKIE[$k] = mysqli_real_escape_string($link, $v);}
        foreach ($_SERVER as $k=>$v) {$_SERVER[$k] = mysqli_real_escape_string($link, $v);}

        if (!isset($_COOKIE['id'])) {
            return false;
        }
    
        $q = mysqli_fetch_assoc(mysqli_query($link, "select id_user from Session where 
        id_cookie_session = '$_COOKIE[id]' and webbrowser = '$_SERVER[HTTP_USER_AGENT]' AND ip_address = '$_SERVER[REMOTE_ADDR]';"));
    
        if (empty($q['id_user'])){
            return false;
        }
        return true;
    }

    function getIdUser() {
        include 'database_connection.php';

        foreach ($_COOKIE as $k=>$v) {$_COOKIE[$k] = mysqli_real_escape_string($link, $v);}
        foreach ($_SERVER as $k=>$v) {$_SERVER[$k] = mysqli_real_escape_string($link, $v);}

        $q = mysqli_fetch_assoc(mysqli_query($link, "select id_user from Session where id_cookie_session = '$_COOKIE[id]' and webbrowser = '$_SERVER[HTTP_USER_AGENT]' AND ip_address = '$_SERVER[REMOTE_ADDR]';"));
        $return = $q['id_user'];

        return $return;
    } 

    function getUserType() {
        include 'database_connection.php';

        foreach ($_COOKIE as $k=>$v) {$_COOKIE[$k] = mysqli_real_escape_string($link, $v);}
        foreach ($_SERVER as $k=>$v) {$_SERVER[$k] = mysqli_real_escape_string($link, $v);}

        $q = mysqli_fetch_assoc(mysqli_query($link, "select user_type from Session NATURAL JOIN User NATURAL JOIN User_type where id_cookie_session = '$_COOKIE[id]' and webbrowser = '$_SERVER[HTTP_USER_AGENT]' AND ip_address = '$_SERVER[REMOTE_ADDR]';"));
        $return = $q['user_type'];

        return $return;
    }

    function getUserLogin() {
        include 'database_connection.php';

        foreach ($_COOKIE as $k=>$v) {$_COOKIE[$k] = mysqli_real_escape_string($link, $v);}
        foreach ($_SERVER as $k=>$v) {$_SERVER[$k] = mysqli_real_escape_string($link, $v);}

        $q = mysqli_fetch_assoc(mysqli_query($link, "select login from Session NATURAL JOIN User where id_cookie_session = '$_COOKIE[id]' and webbrowser = '$_SERVER[HTTP_USER_AGENT]' AND ip_address = '$_SERVER[REMOTE_ADDR]';"));
        $return = $q['login'];

        return $return;
    }  
?>
