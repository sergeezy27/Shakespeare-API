<?php

class user extends data_operations {

  // Constructor - must have same name as class.
  function user() {

    $table = USER_TABLE;
    $id_field = 'usr_id';
    $id_field_is_ai = true;
    $fields = array(
      'usr_name',
      'usr_email',
      'usr_password',
      'usr_time_stamp',
      'usr_ip_address'
    );

    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }
}
?>