<?php
include('Obfuscator.php');
/***
 * Let's try 
***/


// Default Public/private path = ./rsakey/public.rsa.key && ./rsakey/private.rsa.key
$obfuscator = new Obfuscator();
// You can Set public /private custom path like this : new Obfuscator("rsakey/public.rsa.key","rsakey/private.rsa.key");


echo "\n TEST WITH SIMPLE FUNCTION \n";

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


echo "\n TEST WITH SIMPLE STRING ECHO \n";
$text = "echo 'Follow the white rabbit Néo.';";

$obfuscate = $obfuscator->encrypt($text);
var_dump($obfuscate);
$code = $obfuscator->decrypt($obfuscate);
var_dump($code);
$obfuscator->run($code);

echo "\n TEST WITH PHP CLASS :  \n";
$classe = file_get_contents("input.php");

$obfuscate = $obfuscator->encrypt($obfuscator->escape($classe)); // CLass must need to be escape with 'escape' method.
var_dump($obfuscate);
$code = $obfuscator->decrypt($obfuscate);
var_dump($code);
$obfuscator->run($code);


