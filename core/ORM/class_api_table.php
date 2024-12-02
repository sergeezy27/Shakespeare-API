<?php

class api_access extends data_operations {

  // Constructor - must have same name as class.
  function api_access() {

    $table = API_TABLE;
    $id_field = "api_id";
    $id_field_is_ai = true;
    $fields = array(
      "api_user_id",
      "api_time_accessed",
      "api_ip_address",
      "api_query"
    );

    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }
}
?>