<?php

include('Crypt/RSA.php');

/**
* Class d'encode / decode de source Code
*
* On obfusque / deobfusque en fonction des différentes librairie disponible.
*/
class Obfuscator
{

	public $base64 = true;
	public $gzip = true;
	public $rot13 = true;
	public $keys = array();
	public $rsa;


	function __construct()
	{
		$this->keys["private"] = file_get_contents("rsakey/private.rsa.key");
		$this->keys["public"] = file_get_contents("rsakey/public.rsa.key");
		$this->rsa = new Crypt_RSA();
	}

	public function encrypt($text){
	  	$obfuscate = base64_encode(gzcompress(str_rot13($text)));
	  	if(!empty($this->keys["public"])){
			$this->rsa->loadKey($this->keys["public"]); // public key
			$obfuscate = $this->rsa->encrypt($obfuscate);
	  	}
	  	return $obfuscate;
	}

	public function decrypt($obfuscate){
	  	if(!empty($this->keys["private"])){
			$this->rsa->loadKey($this->keys["private"]); // private key
			$obfuscate = $this->rsa->decrypt($obfuscate);
	  	}
		return stripslashes(str_rot13(gzuncompress(base64_decode($obfuscate))));
	}

	public function run($code){
		eval($code);
	}

	public function escape($string){
		// Commentaire monoligne
		$string = preg_replace("#\/\/(.*)\s$#", "", $string);
		
		//Commentaire multilignes
		$string = preg_replace("#/\*(.*)\*/$#", "", $string);

		$string = str_replace("<?php", "", $string);
		$string = str_replace("?>", "", $string);

		//On echape les variables.
		$string = str_replace("$", "\$", $string);
		return addslashes($string);
	}
}


/***
 * On test !! 
***/

$obfuscator = new Obfuscator();

$functionAndCall = "function sum(\$a,\$b){
	return (\$a + \$b);
}

echo sum(1,4);
";


$obfuscate = $obfuscator->encrypt($functionAndCall);
var_dump($obfuscate);
$code = $obfuscator->decrypt($obfuscate);
var_dump($code);
$obfuscator->run($code);


$classe = file_get_contents("input.php");

$obfuscate = $obfuscator->encrypt($obfuscator->escape($classe));

var_dump($obfuscate);

$code = $obfuscator->decrypt($obfuscate);

var_dump($code);

$obfuscator->run($code);
