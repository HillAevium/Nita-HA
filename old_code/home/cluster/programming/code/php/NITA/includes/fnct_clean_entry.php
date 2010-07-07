<?
function get_variable($variable,$default, $action = false, $clean = true){
	if($clean === true){
		$return = (isset($variable)) ? clean_variable($variable,$action) : $default;
	} else {
		$return = (isset($variable)) ? $variable : $default;
	}
	return $return;
}
function clean_variable($variable, $isnt_html = false){
	if(get_magic_quotes_gpc()){
		$variable = stripslashes($variable);
	}
	if($isnt_html === true){
		$variable = htmlentities($variable, ENT_QUOTES);
		$not_healthy = array(">","<","©","™","®");
		$healthy = array("&gt;","&lt;","&copy;","&trade;","&reg;");
		$variable = str_replace($not_healthy,$healthy,$variable);
	} else if($isnt_html == "Store") {
		$variable = str_replace("'","''",$variable);
	}
	return $variable;
}
function sanatizeentry($value, $is_html = false){
	if(!$is_html){
		$noaccept = array ("<",">","\r","\n","[\]r","[\]n","%0A","x0A","%0D","%20","x20","content-type:","charset=","mime-version:","multipart/mixed","bcc:","to:","from:","bc:","cc:","reply-to:");
		$replace = array(" "," ","","","","","","","","","","content","character set","version","mixed","carbon copy","to","from","carbon copy","carbon copy","reply to");
	} else {
		$noaccept = array ("\r","\n","[\]r","[\]n","%0A","x0A","%0D","%20","x20","content-type:","charset=","mime-version:","multipart/mixed","bcc:","to:","from:","bc:","cc:","reply-to:");
		$replace = array("","","","","","","","","","content","character set","version","mixed","carbon copy","to","from","carbon copy","carbon copy","reply to");

	}
	foreach($noaccept as $k => $v){
		do{
			$temp = strtolower($value);
			$pos = strpos($temp,$v);
			if($pos !== false){
				$value = substr($value,0,$pos).$replace[$k].substr($value,($pos+strlen($noaccept[$k])));
			}
		} while ($pos !== false);
	}
	return $value;
}
function utf8_to_NCS($STR){
	$NCS = array();
	$STR = mb_convert_encoding($STR,"UTF-8","auto");
	for($n = 0; $n<strlen($STR); $n++){
		$NCS[] = "&#".ord($STR[$n]).";";
	}
	return trim(implode("",$NCS));
}
function NCS_to_utf8($STR){
	$NCS = array();
	$STR = explode(";",$STR);
	foreach($STR as $r){
		$r = str_replace("&#","",$r);
		$NCS[] = chr($r);
	}
	return trim(implode("",$NCS));
}
function utf8_to_Hex($STR){
	$HEX = array();
	$STR = mb_convert_encoding($STR,"UTF-8","auto");
	for($n = 0; $n<strlen($STR); $n++){
		$HEX[] = "&#x".dechex(ord($STR[$n])).";";
	}
	return trim(implode("",$HEX));
}
function Hex_to_utf8($STR){
	$HEX = array();
	$STR = explode(";",$STR);
	foreach($STR as $r){
		$r = str_replace("&#","",$r);
		$HEX[] = chr(hexdec($r));
	}
	return trim(implode("",$HEX));
}
function encode_email($STR,$CDING){
	$Pattern = '/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/e';
	if($CDING == "NCS") return preg_replace($Pattern,"utf8_to_HEX('$1')",$STR);
	else if($CDING == "HEX") return preg_replace($Pattern,"utf8_to_HEX('$1')",$STR);
	else return $STR;
}
function removeSpecial($STR,$Html=true,$Chars=false){
	$Spec_HTML = array(
		'&lsquo;' 	=> '`', 		// ‘	 &lsquo;  left single quote 
		'&rsquo;' 	=> '\'', 		// ’ 	 &rsquo;  right single quote 
		'&sbquo;' 	=> ',', 		// ‚   &sbquo;  single low-9 quote
		'&ldquo;' 	=> '"', 		// “   &ldquo;  left double quote
		'&rdquo;' 	=> '"', 		// ”   &rdquo;  right double quote
		'&bdquo;' 	=> ',,', 		// „   &bdquo;  double low-9 quote
		'&dagger;' 	=> '(t)', 	// †   &dagger;  dagger
		'&Dagger;' 	=> '(Tt)', 	// ‡   &Dagger;  double dagger
		'&permil;' 	=> '', 			// ‰   &permil;  per mill sign
		'&lsaquo;' 	=> '&lt;',	// ‹   &lsaquo;  single left-pointing angle quote
		'&rsaquo;' 	=> '&gt;', 	// ›   &rsaquo;  single right-pointing angle quote 
		'&spades;' 	=> '*---', 	// ?   &spades;  black spade suit
		'&clubs;' 	=> '**--', 	// ?   &clubs;  black club suit
		'&hearts;' 	=> '***-', 	// ?   &hearts;  black heart suit
		'&diams;' 	=> '****', 	// ?   &diams;  black diamond suit
		'&oline;' 	=> '-',			// ?   &oline;  overline, = spacing overscore
		'&larr;' 		=> '&lt;-', // ?   &larr;  leftward arrow
		'&uarr;' 		=> '/\\',	 	// ?   &uarr;  upward arrow
		'&rarr;' 		=> '-&gt;', // ?   &rarr;  rightward arrow
		'&darr;' 		=> '\\/', 	// ?   &darr;  downward arrow 
		'&trade;' 	=> '(TM)',	// ™   &trade;  trademark sign
		'&reg;' 		=> '(R)',		// ®   &reg;  registration symble
		'&copy;' 		=> '(C)',		// ©   &copy;  trademark sign
		'&frasl;' 	=> '/', 		// /   &frasl; &#47; slash
		'&deg;' 		=> '*', 		// °   &deg; &#176; degree sign
		'&ordm;' 		=> '*', 		// º   &ordm; &#186; masculine ordinal
		'&plusmn;' 	=> '(+-)', 	// ±   &plusmn; &#177; plus or minus
		'&sup1;' 		=> '^1', 		// ¹   &sup1; &#185; superscript one
		'&sup2;' 		=> '^2', 		// ²   &sup2; &#178; superscript two
		'&sup3;' 		=> '^3', 		// ³   &sup3; &#179; superscript three
		'&acute;' 	=> '\'', 		// ´   &acute; &#180; acute accent
		'&micro;' 	=> '(|u)', 	// µ   &micro; &#181; micro sign
		'&para;' 		=> '(|P)', 	// ¶   &para; &#182; paragraph sign
		'&middot;' 	=> '.', 		// ·   &middot; &#183; middle dot
		'&cedil;' 	=> '.', 		// ¸   &cedil; &#184; cedilla
		'&raquo;' 	=> '&gt;&gt;', 	// »   &raquo; &#187; right angle quote
		'&laquo;' 	=> '&lt;&lt;', 	// «   &raquo; &#187; left angle quote
		'&frac14;' 	=> '1/4', 	// ¼   &frac14; &#188; one-fourth
		'&frac12;' 	=> '1/2', 	// ½   &frac12; &#189; one-half
		'&frac34;' 	=> '3/4', 	// ¾   &frac34; &#190; three-fourths 
		'&iquest;' 	=> '?', 		// ¿   &iquest; &#191; inverted question mark
		'&divide;' 	=> '/', 		// ÷   &divide; &#247; division sign
		'&times;' 	=> '*', 		// ×   &times; &#215; multiplication sign 
		'&mdash;' 	=> '-', 		// —	 &mdash; M Dash
		'&hellip;' 	=> '...',		// …	 &hellip; Hellip
	);
	$Spec_CHARS = array(
		'&Acirc;' 	=> 'A',			// Â   &Acirc; &#194; uppercase A, circumflex accent
		'&Atilde;' 	=> 'A',			// Ã   &Atilde; &#195; uppercase A, tilde
		'&Auml;' 		=> 'A',			// Ä   &Auml; &#196; uppercase A, umlaut
		'&AElig;' 	=> 'AE',		// Æ   &AElig; &#198; uppercase AE 
		'&Ccedil;' 	=> 'C',			// Ç   &Ccedil; &#199; uppercase C, cedilla 
		'&Egrave;' 	=> 'E',			// È   &Egrave; &#200; uppercase E, grave accent 
		'&Eacute;' 	=> 'E',			// É   &Eacute; &#201; uppercase E, acute accent 
		'&Ecirc;' 	=> 'E',			// Ê   &Ecirc; &#202; uppercase E, circumflex accent 
		'&Euml;' 		=> 'E',			// Ë   &Euml; &#203; uppercase E, umlaut 
		'&Igrave;' 	=> 'I',			// Ì   &Igrave; &#204; uppercase I, grave accent 
		'&Iacute;' 	=> 'I',			// Í   &Iacute; &#205; uppercase I, acute accent 
		'&Icirc;' 	=> 'I',			// Î   &Icirc; &#206; uppercase I, circumflex accent 
		'&Iuml;' 		=> 'I',			// Ï   &Iuml; &#207; uppercase I, umlaut 
		'&ETH;' 		=> 'D',			// Ð   &ETH; &#208; uppercase Eth, Icelandic 
		'&Ntilde;' 	=> 'N',			// Ñ   &Ntilde; &#209; uppercase N, tilde 
		'&Ograve;' 	=> 'O',			// Ò   &Ograve; &#210; uppercase O, grave accent 
		'&Oacute;' 	=> 'O',			// Ó   &Oacute; &#211; uppercase O, acute accent 
		'&Ocirc;' 	=> 'O',			// Ô   &Ocirc; &#212; uppercase O, circumflex accent 
		'&Otilde;' 	=> 'O',			// Õ   &Otilde; &#213; uppercase O, tilde 
		'&Ouml;' 		=> 'O',			// Ö   &Ouml; &#214; uppercase O, umlaut 
		'&Oslash;' 	=> 'O',			// Ø   &Oslash; &#216; uppercase O, slash 
		'&Ugrave;' 	=> 'U',			// Ù   &Ugrave; &#217; uppercase U, grave accent 
		'&Uacute;' 	=> 'U',			// Ú   &Uacute; &#218; uppercase U, acute accent 
		'&Ucirc;' 	=> 'U',			// Û   &Ucirc; &#219; uppercase U, circumflex accent 
		'&Uuml;' 		=> 'U',			// Ü   &Uuml; &#220; uppercase U, umlaut 
		'&Yacute;' 	=> 'Y',			// Ý   &Yacute; &#221; uppercase Y, acute accent 
		'&THORN;' 	=> 'P',			// Þ   &THORN; &#222; uppercase THORN, Icelandic 
		'&szlig;' 	=> 'B',			// ß   &szlig; &#223; lowercase sharps, German 
		'&agrave;' 	=> 'a',			// à   &agrave; &#224; lowercase a, grave accent 
		'&aacute;' 	=> 'a',			// á   &aacute; &#225; lowercase a, acute accent 
		'&acirc;' 	=> 'a',			// â   &acirc; &#226; lowercase a, circumflex accent 
		'&atilde;' 	=> 'a',			// ã   &atilde; &#227; lowercase a, tilde 
		'&auml;' 		=> 'a',			// ä   &auml; &#228; lowercase a, umlaut 
		'&aring;' 	=> 'a',			// å   &aring; &#229; lowercase a, ring 
		'&aelig;' 	=> 'ae',		// æ   &aelig; &#230; lowercase ae 
		'&ccedil;' 	=> 'c',			// ç   &ccedil; &#231; lowercase c, cedilla 
		'&egrave;' 	=> 'e',			// è   &egrave; &#232; lowercase e, grave accent 
		'&eacute;' 	=> 'e',			// é   &eacute; &#233; lowercase e, acute accent 
		'&ecirc;' 	=> 'e',			// ê   &ecirc; &#234; lowercase e, circumflex accent 
		'&euml;' 		=> 'e',			// ë   &euml; &#235; lowercase e, umlaut 
		'&igrave;' 	=> 'i',			// ì   &igrave; &#236; lowercase i, grave accent 
		'&iacute;' 	=> 'i',			// í   &iacute; &#237; lowercase i, acute accent 
		'&icirc;' 	=> 'i',			// î   &icirc; &#238; lowercase i, circumflex accent 
		'&iuml;' 		=> 'i',			// ï   &iuml; &#239; lowercase i, umlaut 
		'&eth;' 		=> 'o',			// ð   &eth; &#240; lowercase eth, Icelandic 
		'&ntilde;' 	=> 'n',			// ñ   &ntilde; &#241; lowercase n, tilde 
		'&ograve;' 	=> 'o',			// ò   &ograve; &#242; lowercase o, grave accent 
		'&oacute;' 	=> 'o',			// ó   &oacute; &#243; lowercase o, acute accent 
		'&ocirc;' 	=> 'o',			// ô   &ocirc; &#244; lowercase o, circumflex accent 
		'&otilde;' 	=> 'o',			// õ   &otilde; &#245; lowercase o, tilde 
		'&ouml;' 		=> 'o',			// ö   &ouml; &#246; lowercase o, umlaut 
		'&oslash;' 	=> 'o',			// ø   &oslash; &#248; lowercase o, slash 
		'&ugrave;' 	=> 'u',			// ù   &ugrave; &#249; lowercase u, grave accent 
		'&uacute;' 	=> 'u',			// ú   &uacute; &#250; lowercase u, acute accent 
		'&ucirc;' 	=> 'u',			// û   &ucirc; &#251; lowercase u, circumflex accent 
		'&uuml;' 		=> 'u',			// ü   &uuml; &#252; lowercase u, umlaut 
		'&yacute;' 	=> 'y',			// ý   &yacute; &#253; lowercase y, acute accent 
		'&thorn;' 	=> 'p',			// þ   &thorn; &#254; lowercase thorn, Icelandic 
		'&yuml;' 		=> 'y'			// ÿ   &yuml; &#255; lowercase y, umlaut 
	);
	$replace = array();
	$with = array();
	if($Html == true){
		$replace = array();
		$with = array();
		foreach($Spec_HTML as $k => $v){
			$replace[] = $k; $with[] = $v;
		}
	}
	if($Chars == true){
		foreach($Spec_CHARS as $k => $v){
			$replace[] = $k; $with[] = $v;
		}
	}
	if(count($replace) > 0)	$STR = str_replace($replace,$with,$STR);
	return $STR;
}
//Function to seperate multiple tags one line
function fix_newlines_for_clean_html($fixthistext)
{
	$fixthistext_array = explode("\n", $fixthistext);
	foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue)
	{
		//Makes sure empty lines are ignores
		if (!preg_match("/^(\s)*$/", $unfixedtextvalue))
		{
			$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
			$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
		}
	}
	return implode("\n", $fixedtext_array);
}

function clean_html_code($uncleanhtml)
{
	//Set wanted indentation
	$indent = "    ";


	//Uses previous function to seperate tags
	$fixed_uncleanhtml = fix_newlines_for_clean_html($uncleanhtml);
	$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
	//Sets no indentation
	$indentlevel = 0;
	foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml)
	{
		//Removes all indentation
		$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
		$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);
		
		$replaceindent = "";
		
		//Sets the indentation from current indentlevel
		for ($o = 0; $o < $indentlevel; $o++)
		{
			$replaceindent .= $indent;
		}
		
		//If self-closing tag, simply apply indent
		if (preg_match("/<(.+)\/>/", $currentuncleanhtml))
		{ 
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If doctype declaration, simply apply indent
		else if (preg_match("/<!(.*)>/", $currentuncleanhtml))
		{ 
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If opening AND closing tag on same line, simply apply indent
		else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
		{ 
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If closing HTML tag or closing JavaScript clams, decrease indentation and then apply the new level
		else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml))
		{
			$indentlevel--;
			$replaceindent = "";
			for ($o = 0; $o < $indentlevel; $o++)
			{
				$replaceindent .= $indent;
			}
			
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
		}
		//If opening HTML tag AND not a stand-alone tag, or opening JavaScript clams, increase indentation and then apply new level
		else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml))
		{
			$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			
			$indentlevel++;
			$replaceindent = "";
			for ($o = 0; $o < $indentlevel; $o++)
			{
				$replaceindent .= $indent;
			}
		}
		else
		//Else, only apply indentation
		{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
	}
	//Return single string seperated by newline
	return implode("\n", $cleanhtml_array);	
}
?>