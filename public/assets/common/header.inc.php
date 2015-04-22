<!DOCTYPE html>
<html xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<title><?php echo $page_title;?></title>
	<?php foreach ($css_files as $css):?>
		<link rel="stylesheet" type="text/css" href="assets/css/<?php echo $css;?>" media = "screen, projection"/>
	<?php endforeach;?>
</head>
<body>
