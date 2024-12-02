<? 
$title = "Shakespeare Account";
$security = false;
$wrapper_class = "two-column";
$nav_links = ["Sign In" => "index.php", "Sign Up" => "account.php"];

require "core/init.php";

$task = $get_post["task"];
$user_id = "";
$notice = "Fill out the form below to sign up for access to the Shakespeare API.";

switch($task) {

    case "save":
        $user = new user();
        $user->load_from_form_submit();
        $options = ["cost" => 10];

        // Editing existing user
        if(!empty($user->get_id_value())) {

            // If the user auto completes the email, it also fills out the password field even when hidden
            if(trim($get_post["user_password"]) && !trim($get_post["password_validate"])) {
                header("Location: account.php?task=edit&err=Something went wrong, please don't auto complete the email field.");
                exit;
                break;
            }
            
            $validation = $get_post["change_password"] == "yes" ? $user->validate($get_post['password_validate']) : $user->validate();

            if($validation !== true) {
                header("Location: account.php?task=edit&err=" . $validation);
                exit;
                break;
            }

            // Populating existing fields, otherwise save() will update them to ''
            $old_user = new user();
            $old_user->load($user->get_id_value());
            
            if($get_post["change_password"] == "yes") {
                $user->values["user_password"] = password_hash($user->values["user_password"], PASSWORD_BCRYPT, $options);
            }else {
                $user->values["user_password"] = $old_user->values["user_password"];
            }
            $user->values["user_time_created"] = $old_user->values["user_time_created"];
            $user->values["user_ip_address"] = $old_user->values["user_ip_address"];
            $user->values["user_api_token"] = $old_user->values["user_api_token"];
            $user->save();

            header("Location: home.php");
            exit;
            break;
        }
        // End of editing, back to saving a new user

        // Validation
        $validation = $user->validate($get_post['password_validate']);
        if ($validation !== true) {
            $message = $validation;
            break;
        }

        $user->values["user_password"] = password_hash($user->values["user_password"], PASSWORD_BCRYPT, $options);
        $user->values["user_ip_address"] = $_SERVER["REMOTE_ADDR"];
        $user->values["user_api_token"] = hash("sha256", $user->values["user_email"] . time());
        $user->values["user_time_created"] = lib::nice_date("now", "mysql_timestamp");
        $user->save();
      
        // Login after sign-up
        $login = new login();
        $login->login_user($get_post["user_email"], $get_post["user_password"], false);
        $user->load($get_post["user_email"], "user_email");
        $_SESSION["user_id"] = $user->get_id_value();
        $_SESSION["user_email"] = $user->values["user_email"];
        header("Location: home.php");
        exit;
        break;

    case "delete":

        // TODO: Delete logic, also choose what happens in the database if user is deleted
        break;

    case "edit":

        $security = true;

        $user = new user();
        $user->load($_SESSION["user_id"]);
        $notice = "Fill out the form below to update your profile information for the Shakespeare API.";
        $message = $get_post["err"];
        
        $user_id = $user->get_id_value();
        $user_fname = $user->values["user_fname"];
        $user_lname = $user->values["user_lname"];
        $user_email = $user->values["user_email"];
        $user_password = "";
        
        break;

    default:
        // If already signed in
        if(isset($_SESSION["user_id"])) {
            header("Location: home.php");
            exit;
        }
}

require "core/SSI/top.php";
?>

<script>
    function togglePasswordFields() {
        let checkbox = document.getElementById("change_password");
        let passwordSection = document.getElementById("password-section");
        let passwordField = document.getElementById("user_password");
        let confirmPasswordField = document.getElementById("password_validate");

        if (checkbox.checked) {
            passwordSection.classList.add("open");
        } else {
            passwordSection.classList.remove("open");
            passwordField.value = '';
            confirmPasswordField.value = '';
        }

    function confirmDelete() {
        let confirmed = confirm("Are you sure you want to delete your account? This action cannot be undone.");
        if (confirmed) {
            let form = document.querySelector('.account-form');
            let taskInput = form.querySelector('input[name="task"]');
            taskInput.value = "delete";
            form.submit();
        }
    }
}
</script>

<div class="left-column" style="flex-direction: column; align-items: center;">

    <div class="notice" style="margin-top: 20px">
        <?= $notice ?>
    </div>

    <? if($message) { ?>
        <div class="error"><?=$message?></div><br>
    <? } ?>

    <form class="account-form" name="signup-form" action="account.php" method="POST" style="width: 70%;">
        <input type="hidden" name="task" value="save">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <label for="user_fname">First Name:</label>
        <input type="text" name="user_fname" id="user_fname" value="<?=$user_fname?>">

        <label for="user_lname">Last Name:</label>
        <input type="text" name="user_lname" id="user_lname" value="<?=$user_lname?>">

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" id="user_email" value="<?=$user_email?>">

        <? if(!empty($user_id)) { ?>
            <div class="checkbox-group">
                <input type="checkbox" name="change_password" id="change_password" value="yes" onclick="togglePasswordFields()">
                <label for="change_password">Change password?</label>
            </div>
        <? } ?>

        <div id="password-section" class="password-section<?echo (empty($user_id)) ? " open" : "";?>">
            <label for="user_password">Password:</label>
            <input type="password" name="user_password" id="user_password" value="">

            <label for="password_validate">Confirm Password:</label>
            <input type="password" name="password_validate" id="password_validate" value="">
        </div>
        <? if(!empty($user_id)) { ?>
            <div class="form-actions">
                <button type="submit">Save Changes</button>
                <button type="button" class="delete-button" onclick="confirmDelete()">Delete Account</button>
            </div>
        <? }else { ?>
            <button type="submit">Create Account</button>
        <? } ?>
    </form>
</div>
<div class="right-column" style="align-items: center; height: 100%;">
    <img src="graphics/shakespeare-bust.png" alt="A bust of William Shakespeare" class="shakespeare-bust">
</div>

<?
require "core/SSI/bottom.php";
?>