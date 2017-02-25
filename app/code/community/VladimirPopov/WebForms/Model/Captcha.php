<?php

class VladimirPopov_WebForms_Model_Captcha
    extends Mage_Core_Model_Abstract
{

    protected $_publicKey;
    protected $_privateKey;
    protected $_theme = 'standard';

    public function setPublicKey($value)
    {
        $this->_publicKey = $value;
    }

    public function setPrivateKey($value)
    {
        $this->_privateKey = $value;
    }

    public function setTheme($value)
    {
        $this->_theme = $value;
    }

    public function verify($response)
    {

        //Get user ip
        $ip = $_SERVER['REMOTE_ADDR'];

        //Build up the url
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $full_url = $url . '?secret=' . $this->_privateKey . '&response=' . $response . '&remoteip=' . $ip;

        //Get the response back decode the json
        $data = json_decode(file_get_contents($full_url));

        //Return true or false, based on users input
        if (isset($data->success) && $data->success == true) {
            return true;
        }

        return false;
    }

    public function getHtml()
    {
        $output = '';
        $rand = Mage::helper('webforms')->randomAlphaNum();
        if (!Mage::registry('webforms_recaptcha_gethtml')){
            $output .= '<script>var reWidgets =[];</script>';
        }

        $output .= <<<HTML
<script>
    reWidgets.push({id:'{$rand}',inst : ''});
</script>
<div id="g-recaptcha{$rand}"></div>
HTML;

        if (!Mage::registry('webforms_recaptcha_gethtml')) {
            $output .= <<<HTML
<script>
    function recaptchaOnload(){
        for(var i=0; i<reWidgets.length;i++){
            reWidgets[i].inst = grecaptcha.render('g-recaptcha'+reWidgets[i].id,{
                'sitekey' : '{$this->_publicKey}',
                'theme' : '{$this->_theme}'
            });
        }
    }
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaOnload&render=explicit" async defer></script>
HTML;
        }
        if(!Mage::registry('webforms_recaptcha_gethtml')) Mage::register('webforms_recaptcha_gethtml', true);

        return $output;
    }
}
