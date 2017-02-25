<?php
class VladimirPopov_WebForms_Model_Captcha
	extends Mage_Core_Model_Abstract
{

	protected $_publicKey;
	protected $_privateKey;
	protected $_theme = 'standard';

	public function setPublicKey($value){
		$this->_publicKey = $value;
	}

	public function setPrivateKey($value){
		$this->_privateKey = $value;
	}

	public function setTheme($value){
		$this->_theme = $value;
	}

	public function verify($response) {

		//Get user ip
		$ip = $_SERVER['REMOTE_ADDR'];

		//Build up the url
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$full_url = $url.'?secret='.$this->_privateKey.'&response='.$response.'&remoteip='.$ip;

		//Get the response back decode the json
		$data = json_decode(file_get_contents($full_url));

		//Return true or false, based on users input
		if(isset($data->success) && $data->success == true) {
			return true;
		}

		return false;
	}

	public function getHtml(){

		return <<<HTML
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="g-recaptcha" data-sitekey="{$this->_publicKey}" data-theme="{$this->_theme}"></div>
HTML;

	}
}
