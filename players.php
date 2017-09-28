<?php
ob_start();
session_start();
require_once('functions/functions.php');
require_once('functions/quick_con.php'); 
$config = load_config('settings/config.php');
$table_u = $config['user_table'];
$table_v = $config['var_table'];
$table_t = $config['time_table'];
$sql = my_quick_con($config) or die("MySQL problem"); 
// Get game settings
$ret = mysql_query("SELECT zone, starve_time FROM $table_t");
$row = mysql_fetch_assoc($ret);
date_default_timezone_set($row['zone']);
$starve_time = $row['starve_time'];

// Kill starved zombies
mysql_query("UPDATE $table_u SET state = 0, starved = feed + INTERVAL $starve_time hour
			WHERE state < 0 AND now() > feed + INTERVAL $starve_time hour AND starved = '0000-00-00 00:00:00';");
// Get OZ Revealed setting
$ret = mysql_query("SELECT value FROM $table_v WHERE keyword='oz-revealed';");
$reveal_oz = mysql_result($ret, 0);
$state_translate = array('-3'=>'Zombie', '-2'=>'Original Zombie', '-1'=>'Zombie', '0'=>'Starved', '1'=>'Human', '2'=>'Human');

if($_POST['submit'] == 'Refresh') {
	$post_faction_array = array('a'=>'1 = 1', 'r'=>'state > 0', 'h'=>'state < 0', 'd'=>'state = 0'); 
	if(!$reveal_oz) {
		$post_faction_array['r'] = '(state > 0 OR state = -2)';
		$post_faction_array['h'] = '(state = -1 OR state = -3)';
	}
	$post_sort_by_array = array('ln'=>'lname', 'fn'=>'fname', 'ks'=>'kills', 'kd'=>'killed_by', 'fd'=>'feed', 'sd'=>'starved');
	$post_order_array = array('a'=>'ASC', 'd'=>'DESC');

	$faction = $post_faction_array[$_POST['faction']];
	if(!isset($faction)) $faction = '1';
	$sort_by = $post_sort_by_array[$_POST['sort_by']];
	if(!isset($sort_by)) $sort_by = 'lname';
	$order = $post_order_array[$_POST['order']];
	if(!isset($order)) $order = 'DESC';

	$show_pics = $_POST['show_pics'];
	$show_kills = $_POST['show_kills'];
	$show_killed = $_POST['show_killed'];
	$show_feed = $_POST['show_feed']; 
	$show_starved = $_POST['show_starved']; 
} else {
        $faction = '1';
	$sort_by = 'lname';
	$order = 'ASC'; 
	$show_pics = 1;
	$show_kills = 1; 
	$show_killed = 0;
	$show_feed = 1; 
	$show_starved = 0;
}
?>
<?php include('template_top.php');
 
?>


<h3>Player List</h3>
<?php
$zom = mysql_query("SELECT count(id) FROM $table_u WHERE (`state`=-1 or `state`=-2) and active");
$znum = mysql_result($zom, 0);
$dead = mysql_query("SELECT count(id) FROM $table_u WHERE `state`=0 and active");
$dnum = mysql_result($dead, 0);
$hum = mysql_query("SELECT count(id) FROM $table_u  WHERE `state`=1 and active");
$hnum = mysql_result($hum, 0);
echo "$hnum Human"; echo $hnum != 1 ? "s<br>": "<br>"; // Add an 's' if more than one
echo "$znum Zombie"; echo $znum != 1 ? "s<br>": "<br>"; // Add an 's' if more than one
echo $dnum > 0 ? "$dnum Deceased<br>" : "";
?>

<form method=POST action="players.php">
<center>
<?php

$faction_array = array('a'=>'All', 'r'=>'Humans', 'h'=>'Zombies', 'd'=>'Starved');
$sort_by_array = array('ln'=>'Last Name', 'fn'=>'First Name', 'ks'=>'Tags', 'kd'=>'Time Tagged', 'fd'=>'Last Feeding', 'sd'=>'Time Starved');
$order_array = array('a'=>'Ascending', 'd'=>'Descending');
print "<select name='faction'>";
while(list($k,$v) = each($faction_array)) {
	print "<option value='$k'";
	if($_POST['faction'] == $k) print "selected";
	print ">$v</option>";
}
print "</select><select name='sort_by'>";
while(list($k,$v) = each($sort_by_array)) {
	print "<option value='$k'";
	if($_POST['sort_by'] == $k) print " selected";
	print ">$v</option>";
}
print "</select><select name='order'>";
while(list($k,$v) = each($order_array)) {
	print "<option value='$k'";
	if($_POST['order'] == $k) print "selected";
	print ">$v</option>";
}
print "</select>";

?>
<input type='submit' name='submit' value='Refresh'><br>
<input type='checkbox' name='show_pics' value='1' <?php if($show_pics) print "checked"; ?>> Pictures
<input type='checkbox' name='show_kills' value='1' <?php if($show_kills) print "checked"; ?>> Tags
<input type='checkbox' name='show_killed' value='1' <?php if($show_killed) print "checked"; ?>> Time Tagged
<input type='checkbox' name='show_feed' value='1' <?php if($show_feed) print "checked"; ?>> Last Fed
<input type='checkbox' name='show_starved' value='1' <?php if($show_starved) print "checked"; ?>> Time Starved
</center>
<table width="80%" border="0">
<tr>
<?php
if($show_pics) print "<th>Picture</th>";
?>
<th>Stats</th>
</tr>

<?php
$ret = mysql_query("SELECT fname, lname, state, killed_by, UNIX_TIMESTAMP(killed), UNIX_TIMESTAMP(feed), kills, UNIX_TIMESTAMP(starved), 
        if(state IN (0, -1," . ($reveal_oz?" -2,":"") . " -3), pic_path_z, pic_path) pic_path, 
        id FROM $table_u WHERE $faction AND active ORDER BY $sort_by $order;"); 
for($i = 0; $i < mysql_num_rows($ret); $i++) {
	
	print "<tr><td>";
	$row = mysql_fetch_array($ret);
    $no_photo = $row[2]>0?"images/hum_no_photo.jpg":"images/zom_no_photo.jpg";
	if($row[2] == -2 && !$reveal_oz) {
		$status_row = "Human<br>";
        $no_photo = "images/hum_no_photo.jpg";
	} else {
		$status_row = "{$state_translate[$row[2]]}<br>";
	}
	if($show_pics) {
		$pic_path = strlen($row["pic_path"]) > 0 ? $row["pic_path"] : $no_photo;
		$img_size = getimagesize($pic_path);
		//echo '<!-- '; var_dump($img_size); echo ' -->';
		$img_size = $img_size[1] < 200 ? $img_size[1] : '200';
		print "<center><img src='{$pic_path}' height='{$img_size}'></center></td><td>";
	}
	print "<b>{$row[0]} {$row[1]}</b><br>\n{$status_row}";
	if($show_kills && ($row[6] > 0 || $row[2] <= 0)) {
		print "Tags: {$row[6]}<br>";
	}
	if($show_killed && $row[2] <= 0 && ($row[2] != -2 || $reveal_oz)) {
        print "Tagged: " . date('M jS @ g:ia', $row[4]) . "<br>";
	}
	if($show_feed && $row[2] <= 0 && ($row[2] != -2 || $reveal_oz)) { 
		print "Last Fed:  " . date('M jS @ g:ia', $row[5]) . "<br>";
	}
	if($show_starved && $row[2] == 0) {
		print "Starved:  " . date('M jS @ g:ia', $row[7]) . "<br>";
	}
	print "</td></tr>";

}
?>


</table>
<center><i>
<?php
	$num = mysql_num_rows($ret); 
	print "$num players listed";
?>
</i></center>
</form>

<?php include('template_bottom.php');
mysql_free_result($ret);
mysql_close($sql);
ob_end_flush();
?>
