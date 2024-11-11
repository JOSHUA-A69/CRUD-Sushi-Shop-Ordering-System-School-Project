<?php 
function redirect($url){
    header("location: " . $url);
    exit();
}

function sanitizeInput($data){
    return htmlspecialchars(stripslashes(trim($data)));
}

function isAdminLoggedIn(){
    return isset($_SESSION['adminID']);
}

function isCustomerLoggedIn(){
    return isset($_SESSION['customerID']);
}
?>
