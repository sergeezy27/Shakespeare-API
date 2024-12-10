<?
$path = "Location: index.php";
if(!isset($_SESSION["user_id"])) {
    if(isset($_COOKIE["sess_active"])) {
        setcookie("sess_active", false, time() - 3600);
        $path = $path . "?message=Session timed out, please sign in again.";
    }
    header($path);
    exit;
}
$user = new user();
$user->load($_SESSION["user_id"]);

if(empty($user->get_id_value())) {
    header($path);
    exit;
}
$nav_links = ["Home" => "home.php", "Analytics" => "analytics.php", "History" => "history.php"];
?>