<?php
    if(isset($_POST['set_session'])){
        session_start();
        $_SESSION['url'] = $_POST['session_value'];
    }
 echo  $_SESSION['session_value'];?>
