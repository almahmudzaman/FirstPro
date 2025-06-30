<?php
//
if (session_status() >= 0) {

    session_start();
    session_unset();
    session_destroy();
echo  "<div style='color: white; background-color: red; padding: 10px; border-radius: 10px; justify-content: center; align-items: center;'>Sign out Redirecting...</div>";
}

header("refresh: 2; url = index.html");
?>