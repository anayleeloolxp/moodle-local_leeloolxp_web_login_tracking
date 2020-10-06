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
 * Admin settings and defaults.
 *
 * @package local_leeloolxp_web_login_tracking
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author Leeloo LXP <info@leeloolxp.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$string['pluginname'] = 'Leeloo LXP Web Login and Attendance Integration';
$string['local_leeloolxp_web_login_trackingdescription'] = '<p>Integrate Leeloo LXP Login and
Attendance to your Moodle and track your students login,
logout and attendance.</p>';
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
