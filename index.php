<? 
$title = "Shakespeare Login";
$security = false;
$nav_links = ["Sign In" => "index.php", "Sign Up" => "account.php"];

require "core/SSI/top.php";

$task = $get_post["task"];
$message = $get_post["message"];
$login = new login();
$now = time();

// Check if login cookie exists and if user exists
$auto_login = false;
if (isset($_COOKIE["remember_me_id"])) {
    $login->load($_COOKIE["remember_me_id"]);
    if ($login->get_id_value()) {
        $user_id = $login->values["login_user_id"];
        $user = new user();
        $user->load($user_id);
        if($user->get_id_value()) {
            $user_email = $user->values["user_email"];
            $auto_login = true;
        }
    }
}

switch($task) {

    case "login":

        // Can't login if you're already logged in
        if(isset($_COOKIE["login_id"])) {
            header("Location: home.php");
        }
        
        if($auto_login) {
            $login->values["login_updated"] = lib::nice_date("now", "mysql_timestamp");
            $login->save();

            setcookie("login_id", $login->get_id_value(), $now + LOGIN_TIME);
            setcookie("sess_active", true, $now + REMEMBER_ME_TIME);
            $_SESSION["user_id"] = $user->get_id_value();
            $_SESSION["user_email"] = $user->values["user_email"];

            header("Location: home.php");
            exit;
            break;
        }

        $message = $login->login_user($get_post["user_email"], $get_post["user_password"], isset($get_post["remember_me"]));
        if ($message) {
            break;
        }

        header("Location: home.php");
        exit;
        break;
       
    case "logout":
        $was_logged_in = isset($_COOKIE["login_id"]);

        setcookie("login_id", "", $now - 3600);
        setcookie("sess_active", false, $now - 3600);
        setcookie("remember_me_id", "", $now - 3600);
        session_unset();
        session_destroy();

        if ($was_logged_in) {
            ?>
            <div class="logout-message">
                You have been successfully logged out. <br>
                Thank you for using Shakespeare API. <br>
                <a href="index.php" class="general-link">Log back in</a>
            </div>
            <?
            require "core/SSI/bottom.php";
            exit;
        }
        break;

    default:
        if (isset($_COOKIE["login_id"])) {
            $login = new login();
            $login->load($_COOKIE["login_id"]);
            $user = new user();
            $user->load($login->values["login_user_id"]);
        // Check if user ID exists in the database
            if (!empty($user->get_id_value())) {
                header("Location: home.php");
                exit;
            } else {
                // User ID doesn't exist in the database, log them out
                setcookie("login_id", "", $now - 3600);
                setcookie("sess_active", false, $now - 3600);
                setcookie("remember_me_id", "", $now - 3600);
                session_unset();
                session_destroy();
                break;
            }
        }
}
?>

<img src="graphics/flowers-from-shakespeare-s-garden-clipart.svg" alt="Woman in front of a Shakespeare statue" class="shakespeare-svg"/>

<div class="login-content" clearfix>

    <div class="notice">
        To access the features of the Shakespeare API, you must log in first. Please enter your credentials below.
    </div>

    <? if($message) { ?>
        <div class="error"><?=$message?></div><br>
    <? } ?>

    <form class="account-form" name="login-form" action="index.php" method="POST" style="width: 300px;">
        <input type="hidden" name="task" value="login">

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" id="user_email" value="<?= $auto_login ? $user_email : ''; ?>" <?= $auto_login ? 'readonly' : ''; ?>>

        <label for="user_password">Password:</label>
        <input type="password" name="user_password" id="user_password" value="<?= $auto_login ? '********' : ''; ?>" <?= $auto_login ? 'disabled' : ''; ?>>

        <div class="checkbox-group">
            <input type="checkbox" name="remember_me" id="remember_me" value="yes" 
                <?= $auto_login ? 'checked disabled' : ''; ?>>
            <label for="remember_me">Remember me?</label>
        </div>

        <button type="submit">Login</button>
    </form>
    <div class="signup-prompt">Don't have an account? <a href="account.php" class="general-link">Sign up here</a></div>
</div>

<?
require "core/SSI/bottom.php";
?>