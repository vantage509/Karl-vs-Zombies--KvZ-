<?php
ob_start();
session_start();
require_once('functions/functions.php');
require_once('functions/quick_con.php');
$config = load_config('settings/config.php');
$sql = my_quick_con($config) or die("MySQL problem");
include('template_top.php'); 

$query = "SELECT game_id FROM games WHERE active = 0 ORDER BY start_datetime DESC;";
$result = mysql_query($query) or die(mysql_error());
$game_summary_html = '';
if(mysql_num_rows($result) > 0) {
    while($row = mysql_fetch_assoc($result)) {
        $game_summary_html .= game_summary($row["game_id"]) . "<br>&nbsp;<br>";
    }
} else {
    $game_summary_html = '<p>No games found in history.</p>';
}


?>
<h1>Game Summary History</h1>

<?php 

echo $game_summary_html;

include('template_bottom.php'); 

ob_end_flush();
?>
