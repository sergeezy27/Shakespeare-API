<?php

class login extends data_operations {

  // Constructor - must have same name as class.
  function login() {

    $table = LOGIN_TABLE;
    $id_field = "login_record_id";
    $id_field_is_ai = false;
    $fields = array(
      "login_user_id",
      "login_record_created",
      "login_record_updated",
      "login_record_ip_address"
    );

    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }
}
?>