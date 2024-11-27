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
}
?>