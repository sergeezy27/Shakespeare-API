<? 
$title = "Shakespeare API";
$security = true;
$nav_links = ["Log Out" => "index.php?task=logout", "Edit" => "account.php?task=edit", "Analytics" => "#"];

require "core/SSI/top.php";

// TODO: Home page explanations, plus analytics page link and edit

//temp get rid of the session:
//session_unset();

require "core/SSI/bottom.php";
?>