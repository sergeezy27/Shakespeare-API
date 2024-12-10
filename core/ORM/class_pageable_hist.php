<?php
class pg_hist extends pg_list{

    function pg_hist($user_id) {

        $query = "SELECT * FROM " . LOGIN_TABLE . " LEFT JOIN " . USER_TABLE . " ON login_user_id = user_id WHERE user_id = " . $user_id;
        $id_field = "login_id";
        $default_sort_by = "login_updated";
        $default_sort_dir = "DESC";
    
        parent::pg_list($query, $id_field, $default_sort_by, $default_sort_dir, '', '', 1, 5, true, 10, 'even-row-css', 'odd-row-css', 'highlight-css');
      }
}
?>