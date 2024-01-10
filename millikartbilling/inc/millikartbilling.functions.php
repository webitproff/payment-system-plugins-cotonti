<?php

defined('COT_CODE') or die('Wrong URL');

// Requirements
require_once cot_langfile('millikartbilling', 'plug');

class Millikart {
	private $test_url		= "http://test.millikart.az:8513";
	private $pro_url		= "http://pay.millikart.az";
	public 	$mid;
	public 	$secretkey;
	public 	$currency;
  public 	$language;
	public 	$description;
	public 	$amount;
	public 	$reference;
  public 	$mode; //0 - test mode, 1 - real mode

  public function __construct($cfg, $amount, $reference, $description){
    $this->mode = $cfg['mk_mode'];
    $this->mid = $cfg['mk_mid'];
    $this->secretkey = $cfg['mk_secretkey'];
    $this->currency = $cfg['mk_currency'];
    $this->language = $cfg['mk_language'];
    $this->amount = $amount*100;
		//$this->description = $description;
    $this->description = '';
		$this->reference = $reference;
	}

	public function signature() {
		$data = strlen($this->mid);
		$data .= $this->mid;
		$data .= strlen($this->amount);
		$data .= $this->amount;
		$data .= strlen($this->currency);
		$data .= $this->currency;
		if(!empty($this->description)) {
			$data .= strlen($this->description);
			$data .= $this->description;
		}
		else{
			$data .= "0";
		}

		$data .= strlen($this->reference);
		$data .= $this->reference;
		$data .= strlen($this->language);
		$data .= $this->language;
		$data .= $this->secretkey;
		$data = md5($data);
		$data = strtoupper($data);

		return $data;
	}
	public function getURL(){
		$data_url ="/gateway/payment/register?mid=".$this->mid."&amount=".$this->amount."&currency=".$this->currency."&description=".$this->description."&reference=".$this->reference."&language=".$this->language."&signature=".$this->signature();
		if($this->mode == "0") {
			$url = $this->test_url.$data_url;
		}
		else {
			$url = $this->pro_url.$data_url;
		}
		$xml = file_get_contents($url);
		$xml = simplexml_load_string($xml);
		return array('code' => $xml->code, 'redirect' => $xml->redirect, 'description' => $xml->description);
	}
}

?>