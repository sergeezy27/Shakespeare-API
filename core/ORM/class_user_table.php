<?php

class user extends data_operations {

  // Constructor - must have same name as class.
  function user() {

    $table = USER_TABLE;
    $id_field = "user_id";
    $id_field_is_ai = true;
    $fields = array(
      "user_fname",
      "user_lname",
      "user_email",
      "user_password",
      "user_time_created",
      "user_ip_address",
      "user_api_token"
    );

    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }

  public function validate() {
    // Trim spaces at the front and end, also I'm allowing the use of spaces in the password (except if it is only space characters)
    $this->values['user_fname'] = trim($this->values['user_fname']);
    $this->values['user_lname'] = trim($this->values['user_lname']);
    $this->values['user_email'] = trim($this->values['user_email']);

    // Check if any of the fields are empty
    if (!$this->values['user_fname'] || !$this->values['user_lname'] || !$this->values['user_email'] || !trim($this->values['user_password'])) {
        return "One of the fields is empty, please provide a name, email, and password";
    }

    // Check if the first and last name are at least 2 characters
    if (strlen($this->values['user_fname']) < 2 || strlen($this->values['user_lname']) < 2) {
        return "Your first and last name must be at least 2 characters long.";
    }

    // Check if the email already exists in the database
    $validation = new user();
    $validation->load($this->values['user_email'], 'user_email');
    if (!empty($validation->get_id_value())) {
        return "User with this email already exists. Please use a different email address.";
    }

    // Check if password is at least 8 characters long
    if (strlen($this->values['user_password']) < 8) {
        return "Your Password must be at least 8 characters long.";
    }

    return true;
}
}
?>