<?php
include("tangenerator.php");

require_once("utils/dbconnection.php");
require_once ('HTMLPurifier.standalone.php');

//newly added rbac provider
require_once '../PhpRbac/src/PhpRbac/Rbac.php';
$rbac = new \PhpRbac\Rbac();
//end of newly added rbac provider

$error=''; // Variable To Store Error Message
$success= false;

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);


if (isset($_POST['submit-register'])) {
    
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || (empty($_POST['account']))&&empty($_POST['employee'])) {
        $error = EMPTY_INPUT;
        return;
    }
    // Define $username and $password
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password_confirm = $_POST['confirmPassword'];
    $email = $_POST['email'];
    $account = $_POST['account'];

    if(isset($_POST['employee'])){
        $employee = 1;
    }
    else
    {
        $employee = 0;
    }
    // To protect MySQL injection for Security purpose
    $username = stripslashes($username);
    $password = stripslashes($password);
    $email = stripslashes($email);
    $account = stripslashes($account);
    
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    $account = mysql_real_escape_string($account);

    $username = $purifier->purify($username);

    mysqli_autocommit($connection, FALSE);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return;
    }
    
    // validate user input
    if(validateUserInput($username,$password,$email,$account,$password_confirm))
    {
        $password = md5($password);
        //check if user or account with same account number exits
        if(userExists($username, $email) || accountExists($account))
        {
            $error = USER_EXISTS;
            return;
        }
        else 
        {
            // if user does not exist insert him into the database 
            $sql="INSERT INTO users (u_name, u_email, u_password, u_type) VALUES ('$username', '$email', '$password','$employee')";
            if (!mysqli_query($connection,$sql)) {
                mysqli_rollback($connection);
                die('Error');
            }

            $memberid = mysqli_insert_id($connection);
            //if  user is not employee create an account
            if($employee == 0)
            {
            
                //set rbac client role
                $role_id = $rbac->Roles->returnId('client');
                $rbac->Users->assign($role_id, $memberid);
                $balance = "0";
                //Insert account
                $accountName = $username . ' account';
                $sql ="insert into accounts (a_user,a_number,a_balance, a_name) values ('$memberid','$account','$balance', '$accountName')";
                if (!mysqli_query($connection,$sql)) {
                    mysqli_rollback($connection);
                    die('Error');
                }
                $account_id = mysqli_insert_id($connection);
            
                //generate 100 tans
                generateTans($memberid,$account_id,100,$connection);
            }
            else {
                //set rbac employee role
                $role_id = $rbac->Roles->returnId('employee');
                $rbac->Users->assign($role_id, $memberid);
            }

            
            mysqli_commit($connection);
            mysqli_autocommit($connection, TRUE);
            mysqli_close($connection); // Closing Connection

            header('Location: index.php'); // Redirecting To Home Page
        }
    }

}

function validateUsername($username){
global $error;
if (!ctype_alnum($username)) {
            $error .= '<p class="error">Username should be alpha numeric characters only.</p>';
            return FALSE;
}
// if username is not 3-20 characters long, throw error
if (strlen($username) < 3 OR strlen($username) > 20) {
            $error .= '<p class="error">Username should be within 3-20 characters long.</p>';
            return FALSE;
}
return TRUE;

}

function validatePasswordPolicy($password){
global $error;
if (strlen($password) < 8 OR strlen($password) > 20) {
            $error .= '<p class="error">Password should be within 8-20 characters long.</p>';
            return FALSE;
}
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);

if(!$uppercase) {
      $error .= '<p class="error">Password should contain at least one uppercase character.</p>';
            return FALSE;
}
if(!$lowercase) {
      $error .= '<p class="error">Password should contain at least one lowercase character.</p>';
            return FALSE;
}
if(!$number) {
      $error .= '<p class="error">Password should contain at least a digit</p>';
            return FALSE;
}
return TRUE;

}

function validateConfirmPassword($password, $confirm_password){
# Validate Confirm Password #
global $error;
        if ($confirm_password != $password) {
            $error .= '<p class="error">Confirm password mismatch.</p>';
            return FALSE;
        }
return TRUE;
}

function validateAccountNumber($account){
global $error;
if (!ctype_digit($account) || strlen($account) != 10) {
            $error .= '<p class="error">Enter a valid account number (10 digits)</p>';
            return FALSE;
        }
return TRUE;
}


function validateEmail($email){
global $error;
    if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
        return FALSE;
    }
    return TRUE;
}

// does not allow a user with same username or email to register twice
function userExists($username, $email){
global $connection;
$sql = "select u_id from users where u_name = '$username' or u_email = '$email'"; 
$result = mysqli_query($connection,$sql);
$row_count = mysqli_num_rows($result);
        if($row_count == 1){
            return TRUE;
        }
        else {
            return FALSE;
        }
    return FALSE;
}


// does not allow accounts creation with the same account number
function accountExists($accountNumber){
global $connection;
$sql = "select a_number from accounts where a_number = '$accountNumber'"; 
$result = mysqli_query($connection,$sql);
$row_count = mysqli_num_rows($result);
        if($row_count == 1){
            return TRUE;
        }
        else {
            return FALSE;
        }
    return FALSE;
}
// validate userinput
function validateUserInput($username,$password,$email,$account,$password_confirm)
{
if(validateUsername($username) && validatePasswordPolicy($password) && validateConfirmPassword($password,$password_confirm) && validateAccountNumber($account) && validateEmail($email))
{
    return TRUE;
}
return FALSE;
}
?>
