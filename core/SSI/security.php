<?
$path = "Location: index.php";
$now = time();
if(!isset($_COOKIE["login_id"])) {
    if(isset($_COOKIE["sess_active"])) {
        setcookie("sess_active", false, $now - 3600);
        $path = $path . "?message=Session timed out, please sign in again.";
    }
    header($path);
    exit;
}
$login = new login();
$login->load($_COOKIE["login_id"]);
$user = new user();
$user->load($login->values["login_user_id"]);

if(empty($user->get_id_value())) {
    header($path);
    exit;
}
$_SESSION["user_id"] = $user->get_id_value();
$_SESSION["user_email"] = $user->values["user_email"];

// Extends the login state when a user goes to a secure page
setcookie("login_id", $login->get_id_value(), $now + LOGIN_TIME);

$nav_links = ["Home" => "home.php", "Analytics" => "analytics.php", "History" => "history.php"];
?>