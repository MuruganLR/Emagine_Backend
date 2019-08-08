<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Mailer Configuration
$config['mail_mailer']          = 'PHPMailer';
$config['mail_debug']           = 0; // default: 0, debugging: 2, 'local'
$config['mail_debug_output']    = 'html';
$config['mail_smtp_auth']       = true;
$config['mail_smtp_secure']     = 'tls'; // default: '' | tls | ssl |
$config['mail_charset']         = 'utf-8';


// Templates Path and optional config
//remove cmt for tmplate
$config['mail_template_folder'] = 'templates/email';

$config['mail_template_options'] = array(
                                     /*   'BENGALURU_SITE_NAME' => 'BENGALURU FC',
                                       'BENGALURU_SITE_LOGO' => base_url().'assets/images/academy/thumbnails/Bengaluru_FC_logo.png',
									   'BENGALURU_SITE_LOGO_PATH' => 'assets/images/academy/thumbnails/Bengaluru_FC_logo.png',
                                       'BASE_URL'  => base_url(),
										'ACADEMY_LOGO_PATH'           => asset_url().'images/academy/academy_generic_logo.png',
										'ACADEMY_THUMBNAIL_LOGO_PATH'           =>asset_url().'images/academy/thumbnails/academy_generic_logo.png', */
										'ACADEMY_SITE_NAME'			=>	'AcadWare'
                                    );



// Server Configuration
//$config['mail_smtp']            = 'email-smtp.us-west-2.amazonaws.com';
//$config['mail_port']            = '587'; // for gmail default 587 with tls
//$config['mail_user']            = 'AKIAJKKFD3JWWQK3SQIQ';
//$config['mail_pass']            = 'AvcS+4fOuHyqFxp7UjgUDw7g1dysls1xAJV9NvT3xjHe';
//$config['mail_from_mail']       = 'talentserv@talentserv.co.in';
//$config['mail_from_name']       = 'EmagineX';

//$config['mailpath']            = '/usr/sbin/sendmail'
//$config['protocol']             ='sendmail'
$config['smtp_host']            = 'sg2plcpnl0206.prod.sin2.secureserver.net';
$config['smtp_port']            = '465'; // for gmail default 587 with tls
$config['smtp_user']            = 'vendorpartner@emagine.co.in';
$config['smtp_pass']            = 'vendorpartner1!';

// Mailer config Sender/Receiver Addresses
/*
$config['mail_admin_mail']      = 'someone@example.com';
$config['mail_admin_name']      = 'WebMaster';



$config['mail_replyto_mail']    = 'someone@example.com';
$config['mail_replyto_name']    = 'Reply to Name';

// BCC and CC Email Addresses
$config['mail_bcc']             = 'someone@example.com';
$config['mail_cc']              = 'someone@example.com';
*/
// BCC and CC enable config, default disabled;
$config['mail_setBcc']          = false;
$config['mail_setCc']           = false;


/* End of file mail_config.php */
/* Location: ./application/config/mail_config.php */
