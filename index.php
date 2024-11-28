<? 
$title = "Shakespeare Login";
$security = false;
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

        // TODO: Login logic

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