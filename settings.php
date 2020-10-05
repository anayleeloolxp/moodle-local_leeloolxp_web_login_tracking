<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_leeloolxp_web_login_tracking
 * @category    admin
 * @copyright   2020 leeloolxp.com <info@leeloolxp.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ( $hassiteconfig ){
	global $PAGE;
	$PAGE->requires->jquery();
	$config_tat = get_config('local_leeloolxp_web_login_tracking');
    $licensekey = $config_tat->teamnio_web_license;    
	$postData = '&license_key='.$licensekey;
	
	$ch = curl_init();  

	$url = 'https://leeloolxp.com/api_moodle.php/?action=page_info';
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_POST, count($postData));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  

	$output=curl_exec($ch);

	curl_close($ch);

	$info_teamnio  = json_decode($output);

	if($info_teamnio->status!='false') {
		$teamnio_url = $info_teamnio->data->install_url; 
	} else {
		$teamnio_url = 'https://leeloolxp.com/alara'; 
	}

	
	// insert moodle url in Leeloo LXP tbl_config
	$moodle_url =  $CFG->wwwroot;
    $ch = curl_init();  
	$post_var = '&moodle_url='.$moodle_url;
    $url = $teamnio_url.'/admin/sync_moodle_course/set_moodle_url';
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($post_var));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_var);  
    $output=curl_exec($ch);
    
    curl_close($ch);

   
 	

	// Create the new settings page
	// - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
	// $settings will be NULL
	$settings = new admin_settingpage( 'local_leeloolxp_web_login_tracking', 'Leeloo LXP Login and Attendance Integration' );
 
	// Create 
	$ADMIN->add( 'localplugins', $settings );
 
	// Add a setting field to the settings for this page
	$settings->add(new admin_setting_configtext('local_leeloolxp_web_login_tracking/teamnio_web_license', get_string('web_teamnio_license_we', 'local_leeloolxp_web_login_tracking'), get_string('web_teamnio_license_we', 'local_leeloolxp_web_login_tracking'), 0));
	
	$settings->add(new admin_setting_configcheckbox('local_leeloolxp_web_login_tracking/web_loginlogout',get_string('web_loginlogout', 'local_leeloolxp_web_login_tracking'), get_string('web_loginlogoutexplain', 'local_leeloolxp_web_login_tracking'), 1));

	$settings->add(new admin_setting_configcheckbox('local_leeloolxp_web_login_tracking/web_loginlogout_popup',get_string('web_loginlogout_popup', 'local_leeloolxp_web_login_tracking'), get_string('web_loginlogoutpopupexplain', 'local_leeloolxp_web_login_tracking'), 1));
	$minutes_arr = array(1=>'1',2=>'2',5=>'5',10=>'10' );
	$name = 'local_leeloolxp_web_login_tracking/logout_time_on_activity';
	$title = get_string('lable_logout_time_on_activity_we', 'local_leeloolxp_web_login_tracking');
	$description = get_string('logout_time_on_activity_help_we', 'local_leeloolxp_web_login_tracking');

	$settings->add( new admin_setting_configselect($name, $title, $description, '', $minutes_arr));
    ?>  
<?php  

}
