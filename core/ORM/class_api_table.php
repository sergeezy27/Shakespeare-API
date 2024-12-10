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
  static function get_total_hits() {
    $query = "SELECT COUNT(*) as 'count'
                FROM " . API_TABLE ."
                WHERE 1";
    $result = lib::db_query($query);
    while ( $row = $result->fetch_assoc() ) {
      return (int)$row["count"];
    }
    return 0;
  }

  public static function get_hit_breakdown() {
    $query = "SELECT api_query
                FROM " . API_TABLE ."
                WHERE 1";
    $result = lib::db_query($query);
    $breakdown = ["works" => 0, "acts_scenes" => 0, "paragraphs" => 0, "invalid" => 0];

    while ($row = $result->fetch_assoc()) {
      $api_query = $row["api_query"];

      // Check if the query was invalid
      if ($api_query === "invalid") {
        $breakdown["invalid"]++;
        continue;
      }

      // Parse the query into an assoc array
      parse_str(parse_url($api_query, PHP_URL_QUERY), $params);

      if (empty($params)) {
        $breakdown["works"]++;
      } elseif (isset($params["work"]) && !isset($params["act"]) && !isset($params["scene"])) {
        $breakdown['acts_scenes']++;
      } elseif (isset($params["work"]) && isset($params["act"]) && isset($params["scene"])) {
        $breakdown["paragraphs"]++;
      }
    }

    return $breakdown;
}

  static function get_recent_hit() {
    $query = "SELECT api_time_accessed 
              FROM " . API_TABLE . " 
              ORDER BY api_time_accessed DESC 
              LIMIT 1";
    $result = lib::db_query($query);

    if ($row = $result->fetch_assoc()) {
        return $row["api_time_accessed"];
    }
    return null;
  }
}
?>