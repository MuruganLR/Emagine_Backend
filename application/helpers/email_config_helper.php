<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  
   function set_email_configuration()
     {
        $config['protocol'] = 'sendmail';
     	$config['mailpath'] = '/usr/sbin/sendmail';
     	$config['charset'] = 'iso-8859-1';
     	$config['wordwrap'] = TRUE;
     	return $config;
     }
     