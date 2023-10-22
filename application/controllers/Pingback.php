<?php
class Pingback extends CI_Controller {
var $currency_name = 'gems';
    public function index()
    {
        $this->load->database('users');
/**  
 *  Pingback Listener Script
 *  For Virtual Currency API
 *  Copyright (c) 2010-2013 Paymentwall Team
 */
 
/**  
 *  Define your application-specific options
 */
define('SECRET', '1f94857a416c88613a9cf8229cde691d'); // secret key of your application
define('IP_WHITELIST_CHECK_ACTIVE', true);

define('CREDIT_TYPE_CHARGEBACK', 2);

/**  
 *  The IP addresses below are Paymentwall's
 *  servers. Make sure your pingback script
 *  accepts requests from these addresses ONLY.
 *
 */
$ipsWhitelist = array(
    '174.36.92.186',
    '174.36.96.66',
    '174.36.92.187',
    '174.36.92.192',
    '174.37.14.28'
);

/**  
 *  Collect the GET parameters from the request URL
 */
$userId = isset($_GET['uid']) ? $_GET['uid'] : null;
$credits = isset($_GET['currency']) ? $_GET['currency'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;
$refId = isset($_GET['ref']) ? $_GET['ref'] : null;
$signature = isset($_GET['sig']) ? $_GET['sig'] : null;
$sign_version = isset($_GET['sign_version']) ? $_GET['sign_version'] : null;

$result = false;

/**  
 *  If there are any errors encountered, the script will list them
 *  in an array.
 */
$errors = array ();

if (!empty($userId) && !empty($credits) && isset($type) && !empty($refId) && !empty($signature)) {
    $signatureParams = array();
    
    /** 
     *  version 1 signature
     */
    if (empty($sign_version) || $sign_version <= 1) {
         $signatureParams = array(
            'uid' => $userId,
            'currency' => $credits,
            'type' => $type,
            'ref' => $refId
        );
    }
    /** 
     *  version 2+ signature
     */
    else {
        $signatureParams = array();
        foreach ($_GET as $param => $value) {    
            $signatureParams[$param] = $value;
        }
        unset($signatureParams['sig']);
    }
    function calculatePingbackSignature($params, $secret, $version) {
    $str = '';
    if ($version == 2) {
        ksort($params);
    }
    foreach ($params as $k=>$v) {
        $str .= "$k=$v";
    }
    $str .= $secret;
    return md5($str);
}
    /** 
     *  check if IP is in whitelist and if signature matches    
     */
    $signatureCalculated = calculatePingbackSignature($signatureParams, SECRET, $sign_version);
    
    /** 
     *  Run the security check -- if the request's origin is one
     *  of Paymentwall's servers, and if the signature matches
     *  the parameters.
     */
    if (!IP_WHITELIST_CHECK_ACTIVE || in_array($_SERVER['REMOTE_ADDR'], $ipsWhitelist)) {
        if ($signature == $signatureCalculated) {
            $result = true;
            if ($type == CREDIT_TYPE_CHARGEBACK) {
              $this->db->query("UPDATE users SET user_sapphires = user_sapphires + '".$credits."' WHERE user_id ='".$userId."'");    
              $this->db->query("UPDATE users SET donated = '0' WHERE user_id ='".$userId."'");   
            } else {
                $this->db->query("UPDATE users SET user_sapphires = user_sapphires + '".$credits."' WHERE user_id ='".$userId."'");    
                $this->db->query("UPDATE users SET donated = '1' WHERE user_id ='".$userId."'");   
            }
        } else {
            $errors['signature'] = 'Signature is not valid!';    
        }
    } else {
        $errors['whitelist'] = 'IP not in whitelist!';
    }
} else {
    $errors['params'] = 'Missing parameters!';
}

/**  
 *  Always make sure to echo OK so Paymentwall
 *  will know that the transaction is successful.
 */
if ($result) {
    echo 'OK';
} else {
    echo implode(' ', $errors);
}

/**  
 *  Signature calculation function
 */

    }
}
?>
