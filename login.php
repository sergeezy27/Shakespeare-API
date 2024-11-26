<? 
$title = "Shakespeare Login";
$security = false;
require 'core/SSI/top.php';
?>

<img src="graphics/flowers-from-shakespeare-s-garden-clipart.svg" alt="Woman in front of a Shakespeare statue" class="shakespeare-svg"/>

<div class="login-content" clearfix>

    <div class="notice">
        To access the features of the Shakespeare API, you must log in first. Please enter your credentials below.
    </div>

    <form class="login-form" name="login-form" action="page_form.php" method="POST">
        <input type="hidden" name="task" value="save">

        <label for="usr_email">Email:</label>
        <input type="email" name="usr_email" id="usr_email" value="">

        <label for="usr_password">Password:</label>
        <input type="password" name="usr_password" id="usr_password" value="">

        <div class="checkbox-group">
            <input type="checkbox" name="remember_me" id="remember_me" value="">
            <label for="remember_me">Remember me?</label>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

<?
require 'core/SSI/bottom.php';
?>