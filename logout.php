<?php
    require('config.php');
    $_SESSION=[];
    session_destroy();
    if(!isset($_SESSION['role'])){
        header('Location: login.php');
    }
?>
