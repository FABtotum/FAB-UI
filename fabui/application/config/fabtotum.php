<?php
$config['fabtotum_network_interfaces']  = '/etc/network/interfaces';
$config['fabtotum_config_folder']    	= '/var/www/fabui/config/';
$config['fabtotum_config_units']     	= '/var/www/fabui/config/config.json';
$config['fabtotum_custom_config_units'] = '/var/www/fabui/config/custom_config.json';
$config['fabtotum_default_eeprom']      = '/var/www/fabui/config/eeprom_default.json';
$config['fabtotum_custom_eeprom']       = '/var/www/fabui/config/eeprom_custon.json';
$config['fabtotum_suggestions_url']  	= 'http://update.fabtotum.com/mail/suggestions.php';
$config['fabtotum_bugs_url']         	= 'http://update.fabtotum.com/mail/bugs.php';
$config['fabtotum_password_url']     	= 'http://update.fabtotum.com/mail/recovery_password.php';
$config['fabtotum_twitter_feed']     	= '/var/www/temp/twitter.json';
$config['fabtotum_instagram_feed']   	= '/var/www/temp/instagram_feed.json';
$config['fabtotum_instagram_hash']   	= '/var/www/temp/instagram_hash.json';
$config['fabtotum_faq']              	= '/var/www/temp/faq.json';
$config['fabtotum_python_path']      	= '/var/www/fabui/python/';
$config['fabtotum_support_url']      	= 'http://support.fabtotum.com/tickets/';
$config['fabtotum_manual_url']       	= 'http://support.fabtotum.com/manual.pdf';
$config['fabtotum_wiki_url']         	= 'http://wiki.fabtotum.com/doku.php';
$config['fabtotum_blog_url']         	= 'http://blog.fabtotum.com/';
$config['fabtotum_forum_url']        	= 'http://forum.fabtotum.com/';
$config['fabtotum_configurations_url'] 	= 'https://github.com/FABtotum/FAB_Configs';
//=================================================================
$config['fabtotum_serial_port']      = '/dev/ttyAMA0';
$config['fabtotum_serial_boud_rate'] = '115200';
//=================================================================

$config['heads_list']= array(
	'hybrid'   => 'Hybrid Head',
	'print_v2' => 'Printing Head V2',
	'mill_v2'  => 'Milling Head V2',
//	'laser'    => 'Laser Head',
);


$config['heads_pids']= array(
	'hybrid'   => 'M301P15 I5 D30',
	'print_v2' => 'M301 P20 I3.5 D30',
	'mill_v2'  => '',
//	'laser'    => '',
);

$config['heads_descriptions']= array(
	'hybrid'   => array('desc'=> 'The <strong>Hybrid head</strong> is the original multipurpose head of the FABtotum Personal Fabricator. It\'s capable of 3D printing PLA and ABS up to 235°C and Milling up to 14000 RPM.<br> The milling chuck supports 1/8" bits', 'more' => 'https://store.fabtotum.com/eu/hybrid-head-v2-hyb.html'),
	'print_v2' => array('desc'=> 'The <strong>printing Head V2</strong> is capable of printing  PLA, PETG, ABS, NYLON, HIPS, PC up to 260°C. It features a full metal replaceable nozzle and 40W heating cartridge for better performances, as well as a secondary cooling fan.<br> The design is clog-proof and In addition can be fully disassembled and cleaned when necessary.', 'more' => 'https://store.fabtotum.com/eu/store/printing-head-v2.html'),
	'mill_v2'  => array('desc'=> 'The <strong>Milling head V2</strong> features a 200W Brushless Milling motor. It\'s capable of reaching 14000 RPM but it\'s quieter and more balanced than the Hybrid Head. The Milling Head V2 supports milling bits with a shank diameter in the range of 3.0 to 3.5mm (0.12 inches or 1/8“).<br> The Standard ER8 Collet can be swapped with another compatible to this industry standard so it can be equipped with endbits with shank diameter from 0.1 to 6mm', 'more' => 'https://store.fabtotum.com/eu/store/milling-head-v2.html')
//	'laser'    => '',
);


$config['git_releases_json'] = '/var/www/temp/git_releases.json';
$config['git_latest_release_json'] = '/var/www/temp/git_latest_release.json';

?>