<?php

DEFINE("DB_SERVER", "localhost");
DEFINE("DB_USERNAME", "root");
DEFINE("DB_PASSWORD", "");
DEFINE("DB_NAME", "dct-ccs-finals");
// Start the session if not already started


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function openCon(){
    $con = mysqli_connect(DB_SERVER , DB_USERNAME , DB_PASSWORD , DB_NAME);
    if ($con == false) {
        die("ERROR: Could not connect" . mysqli_connect_error());
        return $con;
    }
}

function CloseCon(){
    $con = mysqli_connect(DB_SERVER , DB_USERNAME , DB_PASSWORD , DB_NAME);
    mysqli_close($con);
}

function getRec($con,$strSql){
    $arrRec = [];
    $i = 0;
    if ($rs = mysqli_query($con,$strSql)) {
        if (mysqli_num_rows($rs)== 1) {
            $rec = mysqli_fetch_array($rs);
            foreach ($rec as $key=> $value) {
                $arrRec[$key] = $value;
            }
        }
        elseif(mysqli_num_rows($rs)> 1){
            while ($rec = mysqli_fetch_array($rs)) {
               foreach ($rec as $key=> $value) {
                $arrRec[$i][$key] = $value;
                }
                $i++;
            }
        }
        mysqli_free_result($rs);
    }
    else
        die("ERROR: Could not connect");
    return $arrRec;
}

function exeuteInsertLastQuery($con,$strSql){
    if (mysqli_query($con,$strSql)) {
        return mysql_insert_id($con);
    }
    else
        return 0;
}
function sanitize($con,$input){
    return mysql_real_escape_string($con, stripcslashes(htmlspecialchars($input)));
}










// Function to validate email
function validateEmail($email) {
    if (empty($email)) {
        return "Email is required.";
        header('Location: Index.php');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email.";
    }
    return '';
}

// Function to validate password
function validatePassword($password) {
    if (empty($password)) {
        return "Password is required.";
    }
    return '';
}

// Function to authenticate user by email and password
function authenticateUser($email, $password, $con) {
    // Escape special characters and hash the password before checking
    $email = mysqli_real_escape_string($con, $email);
    $password = mysqli_real_escape_string($con, md5($password));

    // SQL query to check user credentials
    $strsql = "
        SELECT * FROM users 
        WHERE email = '$email' 
        AND password = '$password'
    ";

    // Execute the query
    if ($result = mysqli_query($con, $strsql)) {
        if (mysqli_num_rows($result) > 0) {
            // Redirect to the dashboard if user exists
            $_SESSION['email'] = $email; // Set session variable for email
            header('Location: admin/Dashboard.php');
            mysqli_free_result($result);
            exit;
        } else {
            return "Invalid email or password.";
        }
    } else {
        return "ERROR: Could not execute your request.";
    }
}

function dashboardguard(){
    $loginPage = '../index.php';
    if(!isset($_SESSION['email'])){
        header("Location: $loginPage");
    }
}
// Function to guard pages that should not be accessed by logged-in users
function guard(){   
    $dashboard = 'admin/dashboard.php';
    if(isset($_SESSION['email'])){
        header("Location: $dashboard");
    } 
}


function logout($indexPage) {
    // Unset the 'email' session variable
    unset($_SESSION['email']);

    // Destroy the session
    session_destroy();

    // Redirect to the login page (index.php)
    header("Location: $indexPage");
    exit;
}
?>


