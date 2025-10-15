<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
'crlf' => "\r\n",
'newline' => "\r\n",
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'server2.dnsboost.com', 
    'smtp_port' => 587,
    '_smtp_auth' => TRUE,
    'smtp_user' => 'info@tirupatiinsurance.com',
    'smtp_pass' => 'Vikas@8951',
    'smtp_crypto' => 'tsl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'utf-8',
    'wordwrap' => FALSE,
);
