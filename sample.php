<?php
include('Crypt/RSA.php');
// require "rsa.php";

$text = "Follow the white rabbit Néo.";
// $RSA = new RSA_Handler();
// $keys = $RSA->generate_keypair(1024);
// $encrypted = $RSA->encrypt($text, $keys[0]);
// $decrypted = $RSA->decrypt($encrypted, $keys[1]);


$rsa = new Crypt_RSA();
if(!is_dir("rsakey")){
	extract($rsa->createKey());
	$keys["private"] = $privatekey;
	$keys["public"]  = $publickey;
	mkdir("rsakey");
	file_put_contents("rsakey/public.rsa.key", $keys["public"]);
	file_put_contents("rsakey/private.rsa.key", $keys["private"]);
}
else{
	$keys["private"] = file_get_contents("rsakey/private.rsa.key");
	$keys["public"] = file_get_contents("rsakey/public.rsa.key");
}
$rsa->loadKey($keys["public"]); // public key

//$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_OAEP);
$encrypted = $rsa->encrypt($text);

$encrypted = base64_encode($encrypted);

$rsa->loadKey($keys["private"]); // private key
$decrypted = $rsa->decrypt(base64_decode($encrypted));

echo 'TEST 1 : Simple String\n\n';

echo "<pre>";
	var_dump($keys);
echo "</pre>";

echo "<pre>";
	var_dump($encrypted);
echo "</pre>";

echo "<pre>";
	var_dump($decrypted);
echo "</pre>";

echo "TEST 2 : Simple Function and call obstructation\n\n";

$text = "
function sum(\$a,\$b){
	echo 'lol '.(\$a + \$b);
}
sum(1,2);
";

// Nested functions combinations
// eval(gzinflate(base64_decode('Code')))
// eval(gzinflate(str_rot13(base64_decode('Code'))))
// eval(gzinflate(base64_decode(str_rot13('Code'))))
// eval(gzinflate(base64_decode(base64_decode(str_rot13('Code')))))
// eval(gzuncompress(base64_decode('Code')))
// eval(gzuncompress(str_rot13(base64_decode('Code'))))
// eval(gzuncompress(base64_decode(str_rot13('Code'))))
// eval(base64_decode('Code'))
// eval(str_rot13(gzinflate(base64_decode('Code'))))
// eval(gzinflate(base64_decode(strrev(str_rot13('Code')))))
// eval(gzinflate(base64_decode(strrev('Code'))))
// eval(gzinflate(base64_decode(str_rot13('Code'))))
// eval(gzinflate(base64_decode(str_rot13(strrev('Code')))))
// eval(base64_decode(gzuncompress(base64_decode('Code'))))
// eval(gzinflate(base64_decode(rawurldecode('Code'))))

$obfuscate = base64_encode(gzcompress(str_rot13($text)));
echo "inject Code :\n";
var_dump($obfuscate);
echo "\n";
echo gzuncompress(base64_decode($obfuscate));
eval(stripslashes(str_rot13(gzuncompress(base64_decode($obfuscate)))));





