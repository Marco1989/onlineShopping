<?php
    session_start();
    if (!isset($_SESSION['loggedIn']))
    {
        header("Location: index.php");
    }
?>
