<?php
function load_config($config_file_name) {
	$game_name = substr($_SERVER["HTTP_HOST"], 0, strpos($_SERVER["HTTP_HOST"], "."));
	
	require_once($config_file_name);
	
	return $config;
}
?>
