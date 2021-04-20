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
 * Admin settings and defaults
 *
 * @package local_leeloolxp_web_login_tracking
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$string['pluginname'] = 'Leeloo LXP Web Login and Attendance Integration';
$string['local_leeloolxp_web_login_trackingdescription'] = '<p>Integrate Leeloo LXP Login and
Attendance to your Moodle and track your students
login,logout and attendance.</p>';
$string['local_leeloolxp_web_login_trackingsettings'] = 'Settings';
$string['not_login_message'] = 'Please log in the Leeloo LXP Desktop App';
$string['wanna_track_message'] = 'Do you want to mark your attendance?';
$string['error_message_tracker_stop'] = "Leeloo LXP client didn\'t stop due to a
technical issue. Please stop the tracker manually.";
$string['tracker_stop'] = "You’re now logged out, your attendance has been registered.";
$string['web_loginlogout'] = 'Enable Leeloo LXP Login/Logout and Attendance Tracking';
$string['web_loginlogoutexplain'] = 'Starts the Leeloo LXP time tracker when a user
logs in and stops the tracker when the user logs out.';
$string['web_loginlogout_popup'] = 'Enable Leeloo LXP Login/Logout Popups';
$string['web_loginlogoutpopupexplain'] = 'The pop-ups will be shown on login and logout.';
$string['web_teamnio_license_we'] = 'Leeloo LXP License ID';
$string['logout_time_on_activity_help_we'] = "Leeloo LXP will first show the idle time
pop-up if no activity is registered, according to Leeloo LXP backend settings.";
$string['lable_logout_time_on_activity_we'] = "Idle time auto-logout (in minutes):";
$string['yes'] = "Yes";
$string['no'] = "No";
$string['ok'] = "Ok";
$string['still_learning'] = '¿Todavía estás conectado?';
$string['still_learning_yes'] = 'Yes!';
$string['still_learning_no'] = "No, I'm not";

$string['privacy:metadata'] = 'In order to integrate with Leeloo LXP, some user
data need to be sent to the Leeloo LXP client application (remote system).';
$string['privacy:metadata:leeloolxp_web_login_tracking'] = 'In order to integrate with Leeloo LXP, some user
data need to be sent to the Leeloo LXP client application (remote system).';
$string['privacy:metadata:id'] = 'The user ID';
$string['privacy:metadata:username'] = 'The user\'s username is sent to Leeloo LXP to allow a better user experience.';
$string['privacy:metadata:email'] = 'The user\'s email is sent to Leeloo LXP to allow a better user experience.';
$string['privacy:metadata:fullname'] = 'The user\'s full name is sent to Leeloo LXP to allow a better user experience.';
$string['privacy:metadata:city'] = 'The city of the user.';
$string['privacy:metadata:country'] = 'The country that the user is in.';
$string['privacy:metadata:timezone'] = 'The timezone of the user';
$string['privacy:metadata:skype'] = 'The Skype identifier of the user';
$string['privacy:metadata:idnumber'] = 'An identification number given by the institution';
$string['privacy:metadata:institution'] = 'The institution that this user is a member of.';
$string['privacy:metadata:department'] = 'The department that this user can be found in.';
$string['privacy:metadata:phone1'] = 'A phone number for the user.';
$string['privacy:metadata:phone2'] = 'An additional phone number for the user.';
$string['privacy:metadata:address'] = 'The address of the user.';
$string['privacy:metadata:firstaccess'] = 'The time that this user first accessed the site.';
$string['privacy:metadata:lastaccess'] = 'The time that the user last accessed the site.';
$string['privacy:metadata:lastlogin'] = 'The last login of this user.';
$string['privacy:metadata:lastip'] = 'The last login ip of this user.';
$string['privacy:metadata:description'] = 'General details about this user.';
$string['privacy:metadata:imagealt'] = 'Alternative text for the user\'s image.';
$string['privacy:metadata:alternatename'] = 'An alternative name for the user.';
$string['privacy:metadata:url'] = 'A URL related to this user.';
