<? 
$title = "Shakespeare Login";
$security = false;
$nav_links = ["Sign Up" => "account.php"];

require "core/SSI/top.php";

$task = $get_post["task"];
$user = new user();

switch($task) {

    case "login":

        $user->load($get_post["user_email"], "user_email");

        // Seperate in case I need to trouble shoot, both errors can be combined with an or statement
        if(empty($user->get_id_value())) {
            $message = "Unable to log in. Please check your email and password, then try again.";
            break;
        }
        if(!password_verify($get_post["user_password"], $user->values["user_password"])) {
            $message = "Unable to log in. Please check your email and password, then try again.";
            break;
        }
        $message = "";

        $now = time();
        $login = new login();

        $record_id = hash("md5", $user->values["user_email"] . $now);
        $login->set_id_value($record_id);
        $login->values["login_user_id"] = $user->get_id_value();
        $login->values["login_record_created"] = lib::nice_date($now, "mysql_timestamp");
        $login->values["login_record_updated"] = lib::nice_date($now, "mysql_timestamp");
        $login->values["login_record_ip_address"] = $_SERVER["REMOTE_ADDR"];
        $login->save();
        
        // TODO: Remember me logic
        // if(!isset($_COOKIE["first_load"])) {
        //     setcookie("first_load", $time_stamp, $expires_timestamp);
        // }

        $_SESSION["user_id"] = $user->get_id_value();
        header ("Location: home.php");
        exit;
        break;
       
    case "logout":
        // TODO: Logout logic
        break;

    default:
}
?>

<img src="graphics/flowers-from-shakespeare-s-garden-clipart.svg" alt="Woman in front of a Shakespeare statue" class="shakespeare-svg"/>

<div class="login-content" clearfix>

    <div class="notice">
        To access the features of the Shakespeare API, you must log in first. Please enter your credentials below.
    </div>

    <? if ($message) { ?>
        <div class="error"><?=$message?></div><br>
    <? } ?>

    <form class="account-form" name="login-form" action="index.php" method="POST" style="width: 300px;">
        <input type="hidden" name="task" value="login">

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" id="user_email" value="">

        <label for="user_password">Password:</label>
        <input type="password" name="user_password" id="user_password" value="">

        <div class="checkbox-group">
            <input type="checkbox" name="remember_me" id="remember_me" value="">
            <label for="remember_me">Remember me?</label>
        </div>

        <button type="submit">Login</button>
    </form>
    <div class="signup-prompt">Don't have an account? <a href="account.php" class="signup-link">Sign up here</a></div>
</div>

<?
require "core/SSI/bottom.php";
?>