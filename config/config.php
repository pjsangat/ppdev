<?php
@session_start();
//echo "<pre>";
//print_r($_SESSION);
//print_r($_COOKIE);
//setcookie("idpp088pp088_", "1", time()+(60*60*24));

if($_GET['instance']){
	$_SESSION['instance'] = $_GET['instance'];
	$configfile = dirname(__FILE__)."/config_".$_SESSION['instance'].".php";
	if(file_exists($configfile)){
		include_once($configfile);
	}
	return true;
}
/*
if($_SESSION['instance']){
	$configfile = dirname(__FILE__)."/config_".$_SESSION['instance'].".php";
	if(file_exists($configfile)){
		include_once($configfile);
	}
	return true;
}
*/
else if($_SERVER['HTTP_HOST']=='localhost'){

	$_SESSION['instance'] = "pp2";
	define('DB_ADAPTER', 'mysql'); 
	define('DB_HOST', 'localhost'); 
	define('DB_USER', 'root'); 
	define('DB_PASS', ''); 
	define('DB_NAME', 'pp2'); 
	define('DB_PREFIX', 'pp088_'); 
	define('DB_CHARSET', 'utf8'); 
	define('DB_PERSIST', false); 
	return true;
}
else if($_SERVER['HTTP_HOST']=='projectsdev.directopen.com'){
	$_SESSION['instance'] = "do";
	include_once(dirname(__FILE__)."/config_do.php");
}
else if($_SERVER['HTTP_HOST']=='projects.touchpointdata.com'){
	$_SESSION['instance'] = "tp";
	include_once(dirname(__FILE__)."/config_tp.php");
}
else if($_SERVER['HTTP_HOST']=='projects.adpartners.com'){
	$_SESSION['instance'] = "ap";
	include_once(dirname(__FILE__)."/config_ap.php");
}
else if($_SERVER['HTTP_HOST']=='projects.mailfresh.com'){
	$_SESSION['instance'] = "mf";
	include_once(dirname(__FILE__)."/config_mf.php");
}
else if($_SERVER['HTTP_HOST']=='projects.cleansender.com'){
	$_SESSION['instance'] = "cs";
	include_once(dirname(__FILE__)."/config_cs.php");
}
else if($_SERVER['HTTP_HOST']=='projects.thelanghamgroup.com'){
	?>
	<script>
	self.location = "http://thelanghamgroup.com/~thelangh/projects/index.php?c=access&a=login";
	</script>
	<?php
	
	
	
	$_SESSION['instance'] = "tl";
	include_once(dirname(__FILE__)."/config_tl.php");
}
else{
	$_SESSION['instance'] = "do";
	include_once(dirname(__FILE__)."/config_do.php");	
}


/*
  define('DB_ADAPTER', 'mysql'); 
  define('DB_HOST', 'localhost'); 
  define('DB_USER', 'root'); 
  define('DB_PASS', ''); 
  define('DB_NAME', 'pp2'); 
  define('DB_PREFIX', 'pp088_'); 
  define('DB_CHARSET', 'utf8'); 
  define('DB_PERSIST', false); 
  return true;
 */
?>
