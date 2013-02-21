<?php

include 'url.class.php';
$l = new URL($_SERVER['HTTP_HOST']);

if(isset($_GET['key']) && !empty($_GET['key'])){
	$link = $l->get_url($_GET['key']);
	if($link){
		if(preg_match('/http[s]*:\/\//', $link) < 1){
			$link = 'http://'.$link;
		}
		$l->add_hit($_GET['key']);
		header("Location: ".$link);
	} else {
		header("Location: ./");
	}
}
if(isset($_GET['url']) && !empty($_GET['url'])){
	$key = $l->get_key(urldecode($_GET['url']));
	if($key){
		echo "<a href='".$key."'>".$key."</a>";
	} else {
		echo "URL could not be added.";
	}
}

?>