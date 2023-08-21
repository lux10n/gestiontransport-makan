<?php
    require('config.php');
    if(isset($_SESSION['role'])){
        header('Location: '.$_SESSION['role']);
    }else{
        header('Location: login.php');
    }
?>