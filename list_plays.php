<? 
$title = "Shakespeare Tool";
$security = true;

require "core/SSI/top.php";

$user = new user();
$user->load($_SESSION["user_id"]);

$api_key = $user->values["user_api_token"];
?>

<script>
    const API_TOKEN = "<?=$api_key?>";
    let apiUrlCall = 'https://csci.lakeforest.edu/~vorobevs/csci488/Shakespeare-API/api.php?token=' + API_TOKEN;
</script>
<script src="scripts/shakespeare_tool.js"></script>

<h3 class="shakespeare-tool-title">Works of Shakespeare</h3>
<div id="conjure_container" class="shakespeare-tool"></div>

<?
require "core/SSI/bottom.php";
?>