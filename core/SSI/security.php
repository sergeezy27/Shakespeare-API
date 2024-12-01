<?
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
$user = new user();
$user->load($_SESSION["user_id"]);

if(empty($user->get_id_value())) {
    header("Location: index.php");
    exit;
}
$nav_links = ["Home" => "home.php", "Analytics" => "analytics.php", "History" => "history.php"];
?>