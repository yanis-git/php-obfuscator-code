<?php

/**
* Try it
*/
class Sample
{
	 /**  Variable pour les données surchargées.  */
    private $data = array();


	function __construct($params)
	{
		foreach ($params as $name => $value) {
			$this->$name = $value;
		}
	}

	public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        else{
        	throw new Exception("L'attribut ".$name." n'existe pas.", 1);
        }
    }

    public function foo($izi, $pizi){
    	echo $izi.' => '.$pizi.' \n';
    }
}

$sample = new Sample(array("foo" => "bar"));
echo $sample->foo."\n";
echo $sample->foo('izi',"pizi");