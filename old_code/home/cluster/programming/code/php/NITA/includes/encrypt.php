<?
function encrypt_data($var){
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$key = md5(constant("ENCRYPTKEY"));
	$key = pack('H*', $key);
	$var = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $var, MCRYPT_MODE_ECB, $iv);
	return urlencode($var);
}
function decrypt_data($var){
	$var = urldecode($var);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$key = md5(constant("ENCRYPTKEY"));
	$key = pack('H*', $key);
	$var = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $var, MCRYPT_MODE_ECB, $iv);
	return $var;
}
?>