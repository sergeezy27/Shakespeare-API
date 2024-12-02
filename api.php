<?
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

require_once 'core/init.php';

$token = $_GET['token'];

if(empty($token)) {
    http_response_code(401); // Unauthorized code
    echo json_encode(["error" => "Unauthorized", "message" => "API token is required."]);
    exit;
}

$user = new user();
$user->load($token, "user_api_token");

if(empty($user->get_id_value())) {
    http_response_code(401); // Unauthorized code
    echo json_encode(["error" => "Unauthorized", "message" => "API token is invalid."]);
    exit;
}

function fetchWorks() {
    $output = [];
    $sql = "SELECT * FROM " . S_WORK_TABLE . " ORDER BY work_title";
    $result = lib::db_query($sql);

    while ($row = $result->fetch_assoc()) {
        $output[] = $row;
    }
    return $output;
}
function fetchScenes($work_id) {
    $sql = "SELECT * FROM " . S_CHAPTERS_TABLE . " WHERE chap_work_id = '$work_id' ORDER BY chap_id";
    $result = lib::db_query($sql);

    // if work id exists creates an output array
    if($result->num_rows > 0) {
        $output = [];
        while ($row = $result->fetch_assoc()) {
            $chapter = ["scene_id" => $row['chap_id'],
                        "scene_work_id" => $row['chap_work_id'],
                        "scene_act" => $row['chap_act'],
                        "scene_scene" => $row['chap_scene'],
                        "scene_location" => $row['chap_description']];
        $output[] = $chapter;
        }
    }else { // else output is null
        $output = null;
    }
    return $output;
}

function fetchScenesAndActs($work_id, $act, $scene) {
    $sql = "SELECT * FROM " . S_PARAGRAPHS_TABLE . " as p LEFT JOIN " . S_CHAPTERS_TABLE . " as c on 
    p.par_work_id = c.chap_work_id and p.par_act = c.chap_act and p.par_scene = c.chap_scene 
    WHERE p.par_work_id = '$work_id' and p.par_act = '$act' and p.par_scene = '$scene' ORDER BY p.par_number";
    $result = lib::db_query($sql);

    if($result->num_rows > 0) {
        $flag = true;
        $paragraphs = [];
        while ($row = $result->fetch_assoc()) {
            if($flag) {
                $output = ["scene_location" => $row['chap_description']];
                $flag = false;
            }
            $paragraphs[] = [$row['par_number'], $row['par_char_id'], $row['par_text']];
        }
        $output["paragraphs"] = $paragraphs;
    }else { // else output is null
        $output = null;
    }
    return $output;
}

$work_id = $_GET['work'];
$act = $_GET['act'];
$scene = $_GET['scene'];

$api_access = new api_access();
$api_access->values["api_user_id"] = $user->get_id_value();
$api_access->values["api_time_accessed"] = lib::nice_date("now", "mysql_timestamp");
$api_access->values["api_ip_address"] = $_SERVER["REMOTE_ADDR"];

// if work id is defined
if(isset($work_id)) {
    $work_id = addslashes($work_id);
    if(isset($act) && isset($scene)) { // if act and scene are defined
        $act = addslashes($act);
        $scene = addslashes($scene);
        $output = fetchScenesAndActs($work_id, $act, $scene);
        $api_access->values["api_query"] = "?work=" . $work_id . "&act=" . $act . "&scene=" . $scene;
    }else { // if act and scene are not defined
        $output = fetchScenes($work_id);
        $api_access->values["api_query"] = "?work=" . $work_id;
    }
}else { // if work id is not defined
    $output = fetchWorks();
    $api_access->values["api_query"] = "?";
}

if(empty($output)) {
    $api_access->values["api_query"] = "invalid";
}

$api_access->save();
http_response_code(200);
echo json_encode($output);
exit;
?>