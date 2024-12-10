<?php
class pg_api extends pg_list{

    function pg_api($user_id="") {

        $query = "SELECT * FROM " . API_TABLE . " LEFT JOIN " . USER_TABLE . " ON api_user_id = user_id WHERE 1 ";

        if(!empty($user_id)) {
          $query = $query . "AND user_id = " . $user_id;
        }

        $id_field = "api_id";
        $default_sort_by = "api_time_accessed";
        $default_sort_dir = "DESC";
    
        parent::pg_list($query, $id_field, $default_sort_by, $default_sort_dir, '', '', 1, 5, true, 5, 'even-row-css', 'odd-row-css', 'highlight-css');
      }
}
?>