<? 
$title = "Shakespeare Account";
$security = false;
$wrapper_class = "two-column-layout";
require "core/SSI/top.php";

$task = $get_post["task"];
$user = new user();

switch($task) {

    case "save":
        $user = new user();
        $user->load_from_form_submit();

        // Trim spaces at the front and end, also I'm allowing the use of spaces in the password (except if it is only space characters)
        $user->values['user_fname'] = trim($user->values['user_fname']);
        $user->values['user_lname'] = trim($user->values['user_lname']);
        $user->values['user_email'] = trim($user->values['user_email']);

        // Validation
        if ($user->values['user_fname'] || $user->values['user_lname'] || $user->values['user_email'] || !trim($user->values['user_password'])) {
            $message = "One of the fields is empty, please provide a name, email, and password";
            break;
        }
        if (strlen($user->values['user_fname']) < 2 || strlen($user->values['user_lname']) < 2) {
            $message = "Your first and last name must be at least 2 characters long.";
            break;
        }
        $validation = new user();
        $validation->load($user->values['user_email'], 'user_email');
        if (!empty($validation->get_id_value())) {
            $message = "User with this email already exists. Please use a different email address.";
            break;
        }
        if (strlen($user->values['user_password']) < 8) {
            $message = "Your Password must be at least 8 characters long.";
            break;
        }
        if ($user->values['user_password'] != $get_post['password_validate']) {
            $message = "Password fields don't match. Please try again.";
            break;
        }

        $options = ["cost" => 10];
        $user->values["user_password"] = password_hash($user->values["user_password"], PASSWORD_BCRYPT, $options);
        $user->values["user_ip_address"] = $_SERVER["REMOTE_ADDR"];
        $user->values["user_api_token"] = hash("sha256", $user->values["user_email"] . time());
        $user->values["user_time_created"] = lib::nice_date("now", "mysql_timestamp");
        $user->save();
      
        // TODO: Login and redirect

    //   $usr->load($usr->values['usr_email'], 'usr_email');
    //   $redirect = "page_thank_you.php?id=".$usr->get_id_value();

    //   header ("Location: ".$redirect);
        break;

    case "delete":

        // TODO: Delete logic
        break;

    case "edit":

        // TODO: Edit logic
        break;

    default:
}
?>

<div class="left-column" style="flex-direction: column; align-items: center;">

    <div class="notice" style="margin-top: 20px">
        Fill out the form below to sign up for access to the Shakespeare API.
    </div>

    <? if ($message) { ?>
        <div class="error"><?=$message?></div><br>
    <? } ?>

    <form class="account-form" name="signup-form" action="account.php" method="POST" style="width: 50%;">
        <input type="hidden" name="task" value="save">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <label for="user_fname">First Name:</label>
        <input type="text" name="user_fname" id="user_fname" value="">

        <label for="user_lname">Last Name:</label>
        <input type="text" name="user_lname" id="user_lname" value="">

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" id="user_email" value="">

        <? if (!empty($user->get_id_value())) { ?>
            <div class="checkbox-group">
                <input type="checkbox" name="change_password" id="change_password" value="">
                <label for="change_password">Change password?</label>
            </div>
        <? } ?>

        <label for="user_password">Password:</label>
        <input type="password" name="user_password" id="user_password" value="">

        <label for="password_validate">Confirm Password:</label>
        <input type="password" name="password_validate" id="password_validate" value="">

        <button type="submit">Create Account</button>
    </form>
</div>
<div class="right-column" style="align-items: center; height: 100%;">
    <img src="graphics/shakespeare-bust.png" alt="A bust of William Shakespeare" class="shakespeare-bust">
</div>

<?
require "core/SSI/bottom.php";
?>