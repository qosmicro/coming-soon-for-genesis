<?php /*

Genesis Coming Soon
https://qosmicro.com/plugins/coming-soon-for-genesis
 _____     _____ _____ _             
|     |___|   __|     |_|___ ___ ___ 
|  |  | . |__   | | | | |  _|  _| . |
|__  _|___|_____|_|_|_|_|___|_| |___|
   |__|                              

================================================================== */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

//* Removing the options from the database.
delete_option( 'gcs-settings' );






















/* --- end */