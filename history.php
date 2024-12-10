<? 
$title = "Shakespeare History";
$security = true;
$wrapper_class = "center";

require "core/SSI/top.php";

$user = new user();
$user->load($_SESSION["user_id"]);

$listing = new pg_hist($_SESSION["user_id"]);
$listing->add_column("user_fname", "First Name");
$listing->add_column("user_lname", "Last Name");
$listing->add_column("user_email", "Email");
$listing->add_column("login_created", "Login Record Created");
$listing->add_column("login_updated", "Login Record Updated");
$listing->add_column("login_ip_address", "Login IP Address");
$listing->init_list();
?>

<div class="home-section history-page">
    <h2>Your Personal Login History</h2>
    <p>This page contains your login history records. This includes informationsuch as dates, times, and IP addresses associated with your logins.</p>
</div>

<?= $listing->get_html() ?>

<?
require "core/SSI/bottom.php";
?>