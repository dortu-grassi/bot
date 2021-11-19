<?php
	

	include "CChatBot.php";
	$cb=new CChatBot();
	echo $cb->ascolta($_POST['cerca']);
?>
