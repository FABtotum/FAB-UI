<?php
/**
 *
 * @return boolean
 */
function need_setup_wizard() {
	
	return file_exists('/var/www/WIZARD');
	
}

