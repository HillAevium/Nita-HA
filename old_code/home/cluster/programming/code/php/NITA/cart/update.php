<? $rp=''; for($n=0;$n<(count(explode("/",eregi_replace("//*","/",substr($_SERVER['PHP_SELF'],1))))-1);$n++) $rp .= "../";

require_once $rp.'includes/NitaProd.php';
$Cart=new NitaProd();
$Cart->changeCart(false);

header('Content-type: text/xml'); 

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<data>
	<saved>true</saved>
</data>';
?>