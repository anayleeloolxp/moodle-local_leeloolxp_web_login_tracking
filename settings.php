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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
if ($hassiteconfig) {
    $settings = new admin_settingpage('local_leeloolxp_web_login_tracking', get_string('pluginname', 'local_leeloolxp_web_login_tracking'));
    $ADMIN->add('localplugins', $settings);
    $settings->add(new admin_setting_configtext(
        'local_leeloolxp_web_login_tracking/teamnio_web_license',
        get_string('web_teamnio_license_we', 'local_leeloolxp_web_login_tracking'),
        get_string('web_teamnio_license_we', 'local_leeloolxp_web_login_tracking'),
        0
    ));
    $settings->add(new admin_setting_configcheckbox(
        'local_leeloolxp_web_login_tracking/web_loginlogout',
        get_string('web_loginlogout', 'local_leeloolxp_web_login_tracking'),
        get_string('web_loginlogoutexplain', 'local_leeloolxp_web_login_tracking'),
        1
    ));
    $settings->add(new admin_setting_configcheckbox(
        'local_leeloolxp_web_login_tracking/web_loginlogout_popup',
        get_string('web_loginlogout_popup', 'local_leeloolxp_web_login_tracking'),
        get_string('web_loginlogoutpopupexplain', 'local_leeloolxp_web_login_tracking'),
        1
    ));
    $minutesarr = array(1 => '1', 2 => '2', 5 => '5', 10 => '10');
    $name = 'local_leeloolxp_web_login_tracking/logout_time_on_activity';
    $title = get_string('lable_logout_time_on_activity_we', 'local_leeloolxp_web_login_tracking');
    $description = get_string('logout_time_on_activity_help_we', 'local_leeloolxp_web_login_tracking');
    $settings->add(new admin_setting_configselect($name, $title, $description, '', $minutesarr));
}
