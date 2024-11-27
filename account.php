<? 
$title = "Shakespeare Account";
$security = false;
require "core/SSI/top.php";

$task = $get_post["task"];


switch($task) {

    case "save":
        $user = new user();
        $user->load_from_form_submit();

        // TODO: Validation

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
        break;

    case "edit":
        break;

    default:
}
?>

<div class="signup-content">

    <div class="notice">
        To access the features of the Shakespeare API, you must log in first. Please enter your credentials below.
    </div>

    <form class="account-form" name="signup-form" action="account.php" method="POST" style="width: 50%;">
        <input type="hidden" name="task" value="save">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <label for="user_fname">First Name:</label>
        <input type="text" name="user_fname" id="user_fname" value="">

        <label for="user_lname">Last Name:</label>
        <input type="text" name="user_lname" id="user_lname" value="">

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" id="user_email" value="">

        <div class="checkbox-group">
            <input type="checkbox" name="change_password" id="change_password" value="">
            <label for="change_password">Change password?</label>
        </div>

        <label for="user_password">Password:</label>
        <input type="password" name="user_password" id="user_password" value="">

        <label for="password_validate">Confirm Password:</label>
        <input type="password" name="password_validate" id="password_validate" value="">

        <button type="submit">Create Account</button>
    </form>
</div>

<?
require "core/SSI/bottom.php";
?>