<? $rp=''; for($n=0;$n<(count(explode("/",eregi_replace("//*","/",substr($_SERVER['PHP_SELF'],1))))-1);$n++) $rp .= "../";
require_once($rp.'includes/NitaPager.php');
$Page = new NitaPager();

require_once $rp.'includes/NitaProd.php';
$Cart=new NitaProd();

$Cart->displayCart(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $rp.'includes/_metadata.php'; ?>
<title>Nita</title>
<link rel="stylesheet" type="text/css" href="/css/nita.css" media="all" />
<script type="text/javascript" src="/js/jquery.form.js"></script>
</head>
<body style="font-size:62.5%;">
<? include $rp.'includes/_navigation.php'; ?>
<div id="Container"> <? echo $Cart; ?> </div>
<? include $rp.'includes/_footer.php'; ?>
</body>
</html>
