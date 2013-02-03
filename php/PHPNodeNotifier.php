<?php
class PHPNodeNotifier {
	private $ch;
	function __construct($host="localhost",$port=9091){
		$this->ch = curl_init();
		//This SHOULD NOT CHANGE BASED ON ENVIRONMENT unless we place our Node.JS server somewhere other than localhost.
		curl_setopt($this->ch,CURLOPT_URL, "http://".$host.":".$port."/emit.js");
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
	}
	
	/*
		Possible constraints:
		url  : JS regex (Via string.match())
		user : User ID(s). Either string, number, or array.
		guest: true/false
		*****************************
		PARAMETERS ARE OR'D TOGETHER.
		*****************************
		
		PHPNodeNotifier::emit(string $tag, mixed $data, optional array constraints);
	*/
	
	public function emit($event, $data, $constraints = array()){
	
		if (is_array($data)){
			$data = json_encode($data);
		}
		$params = 2;
		$ext = "";
		foreach ($constraints as $key => $value){
			$ext .= "&";
			if (is_array($value)){
				$ext .= $key."=".json_encode($value);
			}else{
			 if ($value == 0){$value="0";}
				$ext .= $key.'=["'.$value.'"]';
			}
			$params++;
		}
		
		
		curl_setopt($this->ch,CURLOPT_POST, true);
		
		curl_setopt($this->ch,CURLOPT_POSTFIELDS, "handler=".$event."&broadcast=".$data.$ext);
		curl_exec($this->ch);
		return $this;
	}
}