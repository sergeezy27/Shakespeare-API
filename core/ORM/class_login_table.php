<?php

class login extends data_operations {

  // Constructor - must have same name as class.
  function login() {

    $table = LOGIN_TABLE;
    $id_field = "login_id";
    $id_field_is_ai = false;
    $fields = array(
      "login_user_id",
      "login_created",
      "login_updated",
      "login_ip_address"
    );

    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }

  // User login
  public function login_user($user_email, $user_password, $remember_me = false) {

    $user = new user();
    $user->load($user_email, "user_email");

    // Check if user exists
    if (empty($user->get_id_value())) {
      return "Unable to log in. Please check your email and password, then try again.";
    }

    // Verify password
    if (!password_verify($user_password, $user->values["user_password"])) {
      return "Unable to log in. Please check your email and password, then try again.";
    }

    $now = time();
    $record_id = hash("md5", $user->values["user_email"] . $now);
    $this->set_id_value($record_id);
    $this->values["login_user_id"] = $user->get_id_value();
    $this->values["login_created"] = lib::nice_date($now, "mysql_timestamp");
    $this->values["login_updated"] = lib::nice_date($now, "mysql_timestamp");
    $this->values["login_ip_address"] = $_SERVER["REMOTE_ADDR"];
    $this->save();

    // Handle "Remember me" functionality
    if ($remember_me) {
      setcookie("log_id", $record_id, $now + 60 * 60 * 24 * 7); // 7 days
    }

    // Return success message
    return null;  // No errors
  }
}
?>