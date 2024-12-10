<? 
$title = "Shakespeare API";
$security = true;
$wrapper_class = "center";

require "core/SSI/top.php";

$user = new user();
$user->load($_SESSION["user_id"]);

$api_key = $user->values["user_api_token"];
?>
<div class="home-section api-instructions">
    <h2>How to Use the API</h2>
    <p><strong>Your API Token:</strong> <code id="api-token"><?= $api_key; ?></code></p>
    <ol>
        <li>Include your API key in the query string,  proceding <code>token=</code> to gain access.</li>
        <li>Access information about Shakespeare's plays using the following example query strings:
            <ul>
                <strong>Returns a list of plays:</strong>
                <li><code>https://csci.lakeforest.edu/~vorobevs/csci488/Shakespeare-API/api.php?token=<b>{your_token}</b></code></li>
                <strong>Returns all acts and scenes from Hamlet:</strong>
                <li><code>https://csci.lakeforest.edu/~vorobevs/csci488/Shakespeare-API/api.php?token=<b>{your_token}</b>&work=hamlet</code></li>
                <strong>Returns the script for Hamlet's act one, scene one:</strong>
                <li><code>https://csci.lakeforest.edu/~vorobevs/csci488/Shakespeare-API/api.php?token=<b>{your_token}</b>&work=hamlet&act=1&scene=1</code></li>
            </ul>
        </li>
    </ol>
</div>

<div class="home-section account-features">
    <h2>Account Management</h2>
    <p>Use the navigation bar above to:</p>
    <ul>
        <li><strong>Edit Your Account:</strong> Update your personal details and preferences.</li>
        <li><strong>View Login History:</strong> See your recent login activity for security purposes.</li>
        <li><strong>API Analytics:</strong> Monitor your API usage and performance metrics.</li>
    </ul>
</div>
<?
require "core/SSI/bottom.php";
?>