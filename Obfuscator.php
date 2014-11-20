<?php

include('Crypt/RSA.php');

/**
* Source code encode / decode with RSA Encryption system
* Yanis Ghidouche
* On obfusque / deobfusque en fonction des différentes librairie disponible.
*/

class Obfuscator
{

	public $base64 = true;
	public $gzip = true;
	public $rot13 = true;
	public $keys = array();
	public $rsa;


	function __construct($publicPath = "", $privatePath = "")
	{
		if(empty($publicPath))
			$this->keys["public"] = file_get_contents("rsakey/public.rsa.key");
		else
			$this->keys["public"] = file_get_contents($privatePath);


		if(empty($privatePath))
			$this->keys["private"] = file_get_contents("rsakey/private.rsa.key");
		else
			$this->keys["private"] = file_get_contents($privatePath);

		$this->rsa = new Crypt_RSA();
	}

	public function encrypt($text){
	  	$obfuscate = base64_encode(gzcompress(str_rot13($text)));
	  	if(!empty($this->keys["public"])){
			$this->rsa->loadKey($this->keys["public"]); // public key
			$obfuscate = $this->rsa->encrypt($obfuscate);
	  	}
	  	else{
	  		throw new Exception("Public key doesn't exist.", 1);	
	  	}
	  	return $obfuscate;
	}

	public function decrypt($obfuscate){
	  	if(!empty($this->keys["private"])){
			$this->rsa->loadKey($this->keys["private"]); // private key
			$obfuscate = $this->rsa->decrypt($obfuscate);
	  	}
	  	else{
	  		throw new Exception("Private Key doesn't exist.", 1);
	  		
	  	}
		return stripslashes(str_rot13(gzuncompress(base64_decode($obfuscate))));
	}

	public function run($code){
		eval($code);
	}

	public function escape($string){
		// Single line Comment
		$string = preg_replace("#\/\/(.*)\s$#", "", $string);
		
		//Multi lines comments
		$string = preg_replace("#/\*(.*)\*/$#", "", $string);

		$string = str_replace("<?php", "", $string);
		$string = str_replace("?>", "", $string);

		//Var escape
		$string = str_replace("$", "\$", $string);
		return addslashes($string);
	}
}