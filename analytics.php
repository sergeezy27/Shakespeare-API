<? 
$title = "Shakespeare API";
$security = true;
$wrapper_class = "center";

require "core/SSI/top.php";

$user = new user();
$user->load($_SESSION["user_id"]);

$hit_breakdown = api_access::get_hit_breakdown();

$listing = new pg_api();
$listing->add_column("user_email", "User Email");
$listing->add_column("api_query", "Query");
$listing->add_column("api_time_accessed", "Time");
$listing->init_list();
?>

<div class="home-section analytics-page">
    <h2>API Analytics</h2>
    <p>This page contains information about general API usage, including total hits, invalid queries, and category breakdowns.</p>
    <ul>
        <li><strong>Total Hits:</strong> <?= api_access::get_total_hits() ?></li>
        <li><strong>Invalid Queries:</strong> <?= $hit_breakdown["invalid"] ?></li>
        <li><strong>Works Accessed:</strong> <?= $hit_breakdown["works"] ?></li>
        <li><strong>Acts and Scenes Accessed:</strong> <?= $hit_breakdown["acts_scenes"] ?></li>
        <li><strong>Paragraphs Accessed:</strong> <?= $hit_breakdown["paragraphs"] ?></li>
        <li><strong>Most Recent Hit:</strong> <?= api_access::get_recent_hit() ?></li>
    </ul>
</div>

<h2 class="table-title">Recent Hits</h2>
<?= $listing->get_html() ?>

<?
require "core/SSI/bottom.php";
?>