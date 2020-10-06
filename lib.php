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
require_once(dirname(dirname(__DIR__)) . '/config.php');
function local_leeloolxp_web_login_tracking_before_footer() {
    $configweblogintrack = get_config('local_leeloolxp_web_login_tracking');
    global $USER;
    global $PAGE;
    global $CFG;
    require_login();
    $baseurl = $CFG->wwwroot;
    $sesskey = $USER->sesskey;
    $logoutsettimemin = $configweblogintrack->logout_time_on_activity;
    $logouturl = $baseurl . "/login/logout.php?sesskey=" . $sesskey;
    $useremail = $USER->email;
    $cookiename = "user_email";
    $cookievalue = $useremail;
    setcookie($cookiename, $cookievalue, time() + (86400 * 30), "/");
    if ($useremail != '' && $useremail != 'root@localhost') {
        echo '<script type="text/javascript">
			var d = new Date();
			var cname = "popuptlt";
			var cvalue = "' . $useremail . '";
			var exdays = "1";
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+ d.toUTCString();
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			</script>';
        $loginlogout = $configweblogintrack->web_loginlogout;
        $configleeloolsso = get_config('leeloolxp_tracking_sso');
        $usercreateflag = $configleeloolsso->web_new_user_student;
        $designation = $configleeloolsso->default_student_position;
        if ($usercreateflag) {
            $usercreateflag = 'yes';
        } else {
            $usercreateflag = 'no';
        }
        $username = $USER->username;
        $useremail = $USER->email;
        $wannatrackmessage = get_string('wanna_track_message', 'local_leeloolxp_web_login_tracking');
        $liacnsekey = $configweblogintrack->teamnio_web_license;
        $popupison = $configweblogintrack->web_loginlogout_popup;
        $postdata = '&license_key=' . $liacnsekey;
        $ch = curl_init();
        $url = 'https://leeloolxp.com/api_moodle.php/?action=page_info';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postdata));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $output = curl_exec($ch);
        curl_close($ch);

        $infoteamnio = json_decode($output);
        if ($infoteamnio->status != 'false') {
            $teamniourl = $infoteamnio->data->install_url;
        } else {
            return true;
        }

        $url = $teamniourl . '/admin/sync_moodle_course/check_user_by_email/' . $useremail;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $userexistonteamnio = $output;
        if ($userexistonteamnio == '0') {
            if ($usercreateflag == 'no') {
                return true;
            }
        }
        $url = $teamniourl . '/admin/sync_moodle_course/check_user_status_by_email/' . $useremail;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $userstatusonteamnio = $output;
        if ($userstatusonteamnio == 0) {
            return true;
        }
        if ($loginlogout) {
            ?>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
            <div class="dialog-modal dialog-modal-clockin-startsss" id="dialog-modal-clockin-startsssssssss" style="display: none;">
                <div class="dialog-modal-inn">
                    <div id="dialog" >
                        <h4><?php echo $wannatrackmessage; ?></h4>
                        <div class="sure-btn">
                            <button data_id = "" onclick="btn_yes_clockin_start();" class="btn btn_yes_activityunsync" >Yes</button>
                            <button data_id = "" onclick="btn_no_clockin_start();" class="btn btn_yes_activityunsync" >No</button>
                        </div>
                    </div>
                </div>
            </div>
            <style type="text/css">
            .dialog-modal {
                align-self: center;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: rgba(0,0,0,0.7);
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .dialog-modal-inn {
                background: #fff;
                max-width: 750px;
                padding: 50px;
                text-align: center;
                width: 100%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .dialog-modal-inn h4 {
                font-weight: 400;
                margin: 0 0 25px;
                font-size: 25px;
            }
            .dialog-modal-inn .sure-btn button {
                font-size: 20px;
                padding: .5rem 3rem;
                color: #fff;
                background-color: #74cfd0;
                border: none;
                display: inline-block;
                text-decoration: none;
                outline: none;
                box-shadow: none;
                margin: 10px 0;
            }
            .dialog-modal-inn div#dialog {
                font-size: 17px;
            }
            .dialog-modal-inn p {
                font-size: 19px;
            }
            .dialog-modal-inn h3 {
                font-weight: 500;
                font-size: 22px;
                color: #f60000;
            }
            .sure-btn {
                margin: 50px 0 0;
            }
            .anymore-link {
                margin: 15px 0 0;
            }
            .anymore-link a {
                color: #74cfd0;
                font-size: 17px;
            }
            #page-wrapper {
                z-index: -1 !important;
            }
            </style>
            <div class="dialog-modal dialog-modal-stillworking" id="dialog-modal-stillworking" style="display: none;">
                <div class="dialog-modal-inn">
                    <div id="dialog" >
                        <h4><?php echo get_string('still_learning', 'local_leeloolxp_web_tat'); ?></h4>
                        <div class="sure-btn">
                            <button data_id = "" onclick="still_working_okay();" class="btn btn_yes_activityunsync" >
                            <?php echo get_string(
                                'still_learning_yes', 'local_leeloolxp_web_tat'
                                ); ?></button>
                                <button data_id = "" onclick="still_working_cancel();"
                                class="btn btn_yes_activityunsync" ><?php echo get_string('still_learning_no',
                                'local_leeloolxp_web_tat'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
                <input type = 'hidden' value = '1' id='mouse_count'/>
                <input type = 'hidden' value = '1' id='key_count'/>
                <style type="text/css">
                .dialog-modal-inn{background:#fff;max-width:750px;
                padding:50px;text-align:center;width:100%;position:absolute;top:50%;
                left:50%;transform:translate(-50%,-50%)}.
                dialog-modal-inn h4{font-weight:400;margin:0 0 25px;font-size:25px}
                .dialog-modal-inn .sure-btn button{font-size:20px;padding:.5rem 3rem;color:#fff;
                background-color:#74cfd0;border:none;display:inline-block;
                text-decoration:none;outline:0;box-shadow:none;margin:10px 0}
                .dialog-modal-inn div#dialog{font-size:17px}.dialog-modal-inn p{font-size:19px}
                .dialog-modal-inn h3{font-weight:500;font-size:22px;color:#f60000}
                .sure-btn{margin:50px 0 0}.anymore-link{margin:15px 0 0}
                .anymore-link a{color:#74cfd0;font-size:17px}#page-wrapper{z-index:-1!important}
                </style>
            <?php
            if (is_siteadmin()) {
                $isadmin = '1';
            } else {
                $isadmin = '';
            }
            $usertype = 'student';
            $ssoconfig = get_config('leeloolxp_tracking_sso');
            $userapproval = $ssoconfig->required_aproval_student;
            $lastlogin = date('Y-m-d h:i:s', $USER->lastlogin);
            $fullname = ucfirst($USER->firstname) . " " . ucfirst($USER->middlename) . " " . ucfirst($USER->lastname);
            $city = $USER->city;
            $country = $USER->country;
            $timezone = $USER->timezone;
            $skype = $USER->skype;
            $idnumber = $USER->idnumber;
            $institution = $USER->institution;
            $department = $USER->department;
            $phone = $USER->phone1;
            $moodlephone = $USER->phone2;
            $adress = $USER->adress;
            $firstaccess = $USER->firstaccess;
            $lastaccess = $USER->lastaccess;
            $lastlogin = $lastlogin;
            $lastip = $USER->lastip;
            $description = $USER->description;
            $descriptionofpic = $USER->imagealt;
            $alternatename = $USER->alternatename;
            $webpage = $USER->url;
            $url = $teamniourl . '/admin/sync_moodle_course/get_create_user/?user_email='
            . $useremail . '&username=' . $username . '&name=' . $fullname . "&user_designation="
            . $designation . "&is_company_admin=" . $isadmin . "&user_approval="
            . $userapproval . "&can_user_create=1&user_type="
            . $usertype . "&city=" . $city . "&country=" . $country . "&timezone=" . $timezone . "&skype="
            . $skype . "&idnumber=" . $idnumber . "&institution=" . $institution . "&department="
            . $department . "&phone=" . $phone . "&moodle_phone=" . $moodlephone . "&adress=" . $adress
            . "&firstaccess=" . $firstaccess . "&lastaccess=" . $lastaccess . "&lastlogin=" . $lastlogin
            . "&lastip=" . $lastip
            . "&user_description=" . $description . "&picture_description="
            . $descriptionofpic . "&institution=" . $institution . "&alternate_name="
            . $alternatename . "&web_page=" . $webpage;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $userid = curl_exec($ch);
            curl_close($ch);
            $url = $teamniourl . '/login_api/get_shift_details_api/' . $userid;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $output = curl_exec($ch);
            curl_close($ch);

            $shiftdetails = $output;
            $sdetail = json_decode($shiftdetails);
            $url = $teamniourl . '/admin/sync_moodle_course/get_timezone/';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $outputtimezone = curl_exec($ch);
            curl_close($ch);
            date_default_timezone_set($outputtimezone);
            $url = $teamniourl . '/admin/sync_moodle_course/get_attendance_info/' . $userid;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $output = curl_exec($ch);
            curl_close($ch);
            $starttime = $output;
            if ($sdetail->status == 'true') {
                $shiftstarttime = strtotime($sdetail->data->start);
                $shiftendtime = strtotime($sdetail->data->end);

                if ($starttime == '0') {
                    $starttime = date("Y-m-d h:i:s");
                }
                $actualstarttime = strtotime(date('h:i A', strtotime($starttime)));
                $actualendtime = strtotime("now");
                if ($actualstarttime >= $shiftendtime) {
                    $starttimestatus = 'Absent';
                } else {
                    if ($actualstarttime < $shiftstarttime) {
                        $starttimestatus = 'On Time';
                    } else {
                        if ($actualstarttime >= $shiftstarttime) {
                            $starttimestatus = 'Late';
                        }
                    }
                }
                if ($starttimestatus == 'Absent') {
                    $endtimestatus = 'Absent';
                } else {
                    if ($shiftendtime > $actualendtime) {
                        $endtimestatus = 'On Time (Learning now)';
                    } else {
                        if ($actualendtime > $shiftendtime) {
                            $endtimestatus = 'On Time (Learning now)';
                        }
                    }
                }
                $postdata = '&user_id=' . $userid . '&start_status=' . $starttimestatus . '&end_status=' . $endtimestatus;
                $ch = curl_init();
                $url = $teamniourl . '/admin/sync_moodle_course/update_attendance_status/';

                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_HEADER, false);

                curl_setopt($ch, CURLOPT_POST, count($postdata));

                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

                curl_exec($ch);

                curl_close($ch);
            }
            $url = $teamniourl . '/admin/sync_moodle_course/get_user_settings_tct_tat/' . $userid;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $usersettings = json_decode($output);
            curl_close($ch);
            ?>
            <script>
            function btn_yes_clockin_start() {
                localStorage.setItem("tracked", "1");
                document.getElementById('dialog-modal-clockin-start').style.display = 'none';
                setTimeout(function(){
                    var trackingon = localStorage.getItem("tracked");
                    if(trackingon=='1') {
                        loadDoc_once(userid,60*1000);
                        setInterval(function() {
                            loadDoc_every_two_m(userid,60*1000);
                            }, 60*1000);
                        }
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                var clockinbreakspan = document.getElementById('clockin_break_span');
                                if (typeof(clockinbreakspan) != 'undefined' && clockinbreakspan != null){
                                    clockinbreakspan.innerText = this.responseText;
                                }
                            }
                        };
                        xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/get_breacks/"+userid, true);
                        xhttp.send();
                    }, 2000);
                }
                function btn_no_clockin_start() {
                    localStorage.setItem("tracked", "0");
                    localStorage.setItem("tracked_cancel",'1');
                    document.getElementById('dialog-modal-clockin-start').style.display = 'none';
                }
                var userid = '<?php echo $userid; ?>';
                localStorage.setItem("login_user_id", userid);
                var teamniourl = '<?php echo $teamniourl; ?>';
                var checkfirst =  localStorage.getItem("tracked");
                var forpopup = localStorage.getItem('tracked_cancel');
                var ispopup = '<?php echo $popupison; ?>';
                if(ispopup=='1') {
                    if(forpopup != '1') {
                        if(checkfirst=='0') {
                            var idofbody  = document.getElementsByTagName("body")[0].id;
                            var d1 = document.getElementById(idofbody);
                            d1.insertAdjacentHTML('afterend', '<div class="dialog-modal dialog-modal-clockin-start" 
                            id="dialog-modal-clockin-start" style="">
                            <div class="dialog-modal-inn">
                            <div id="dialog" ><h4>
                            <?php echo $wannatrackmessage; ?></h4>
                            <div class="sure-btn"><button data_id = "" onclick="btn_yes_clockin_start();"
                            class="btn btn_yes_activityunsync" >Yes</button>
                            <button data_id = "" onclick="btn_no_clockin_start();"
                            class="btn btn_yes_activityunsync" >No</button></div></div></div>
                            </div>
                            <style type="text/css">.dialog-modal {
                                align-self: center;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;
                                background: rgba(0,0,0,0.7);display: flex;align-items: center;justify-content: center;}.
                                dialog-modal-inn {background: #fff;max-width: 750px;padding: 50px;text-align: center;width: 
                                100%;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);}.
                                dialog-modal-inn h4 {font-weight: 400;margin: 0 0 25px;font-size: 25px;}.dialog-modal-inn .
                                sure-btn button {font-size: 20px;padding: .5rem 3rem;color: #fff;background-color: #74cfd0;
                                border: none;display: inline-block;text-decoration: none;outline: none;box-shadow: none;
                                margin: 10px 0;}.dialog-modal-inn div#dialog {font-size: 17px;}.dialog-modal-inn p 
                                {font-size: 19px;}.dialog-modal-inn h3 {font-weight: 500;font-size: 22px;color: #f60000;}.
                                sure-btn {margin: 50px 0 0;}.anymore-link {margin: 15px 0 0;}.anymore-link a {color: #74cfd0;
                                font-size: 17px;}#page-wrapper { z-index: -1 !important;  } </style>');
                                var script = "<script> function btn_yes_clockin_start() {  localStorage.setItem('tracked', 
                                '1');document.getElementById('dialog-modal-clockin-start').style.display = 'none';}function 
                                btn_no_clockin_start() {localStorage.setItem('tracked', '0');localStorage.setItem
                                ('tracked_cancel','1');document.getElementById('dialog-modal-clockin-start').style.display = 
                                'none';<script>";
                                d1.insertAdjacentHTML('afterend',script);
                            }
                        function loadDoc_once(userid,time) {
                            var xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {}
                            };
                            xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/update_clockin/?user_id="+userid, false);
                            xhttp.send();
                        }
                        function loadDoc_every_two_m(userid,time) {
                            var xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {}
                            };
                            xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/update_clockin_every_m/?user_id="+userid, true);
                            xhttp.send();
                        }
                        var mousekeycounttime = setInterval(function() {
                            var  forpopupcanceledsetagain  = localStorage.getItem('tracked_cancel');
                            if(forpopupcanceledsetagain=='1') {
                                localStorage.setItem("tracked_cancel",'1');
                            }
                        },  60*1000);
                        var mousecount = 1;
                        document.body.addEventListener("click", function(){
                            mouse_count++;
                            document.getElementById('mouse_count').value = mousecount;
                        });
                        var logoutsettimemin  = '<?php echo $logoutsettimemin; ?>';
                        var keycount = 1;
                        document.body.addEventListener("keydown", function(){
                            key_count++;
                            document.getElementById('key_count').value = keycount;
                        });
                        var userstillworkingsetting = '<?php echo $usersettings->user_data->student_still_working_pop_up; ?>';
                        if(userstillworkingsetting!='454544') {
                            setInterval(function() {
                                check_counts(mousekeycounttime,userstillworkingsetting);
                            },  (60*1000)*userstillworkingsetting);
                        }
                        function still_working_cancel() {
                            window.location.href = '<?php echo $logouturl; ?>';
                        }
                        function still_working_okay() {
                            location.reload(true);
                        }
                        function logout_after_set_time() {
                            window.location.href = '<?php echo $logouturl; ?>';
                        }
                        function check_counts(myVar,user_still_working_setting) {
                            var forpopupcanceled = localStorage.getItem('tracked_cancel');
                            if(forpopupcanceled=='1') {
                                return false;
                            }
                            if(userstillworkingsetting!='454544') {
                                var keycountss = parseInt(document.getElementById('key_count').value);
                                var mousecountss = parseInt(document.getElementById('mouse_count').value);
                                if(keycountss <=1 && mousecountss <= 1) {
                                    clearInterval(myVar);
                                    document.getElementById('dialog-modal-stillworking').style.display = 'block';
                                    setInterval(function() {
                                        if(forpopupcanceled=='1') {}
                                        else {
                                            logout_after_set_time();
                                        }
                                    },(60*1000)*logoutsettimemin);
                                } else {
                                    document.getElementById('key_count').value = '1';
                                    document.getElementById('mouse_count').value = '1';
                                }
                            }
                        }
                    }
                } else {
                        function loadDoc_once(user_id,time) {
                            var xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {}
                            };
                            xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/
                            update_clockin/?user_id="+user_id, false);xhttp.send();
                        }
                        function loadDoc_every_two_m(user_id,time) {
                            var xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {}
                            };
                            xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/update_clockin_every_m/?user_id="+user_id, 
                            true);
                            xhttp.send();
                        }
                    loadDoc_once(user_id,60*1000);
                    setInterval(function() {
                        loadDoc_every_two_m(user_id,60*1000);
                        }, 60*1000);
                    }
                    var mouse_key_count_time = setInterval(function() {},  60*1000);
                    var mouse_count = 1;
                    document.body.addEventListener("click", function(){
                        mouse_count++;
                        document.getElementById('mouse_count').value = mouse_count;
                    });
                    var logoutsettimemin  = '<?php echo $logoutsettimemin; ?>';
                    var key_count = 1;
                    document.body.addEventListener("keydown", function(){
                        key_count++;
                        document.getElementById('key_count').value = key_count;
                    });
                    var user_still_working_setting = '<?php echo $usersettings->user_data->student_still_working_pop_up; ?>';
                    if(user_still_working_setting!='454544') {
                        setInterval(function() {
                            if(is_popup!='1') {
                                check_counts_for_popup_disabled(mouse_key_count_time,user_still_working_setting);
                            }
                        }, (60*1000)*user_still_working_setting);
                    }
                    function still_working_cancel() {
                        window.location.href = '<?php echo $logouturl; ?>';
                    }
                    function still_working_okay() {
                        location.reload(true);
                    }
                    function logout_after_set_time() {
                        window.location.href = '<?php echo $logouturl; ?>';
                    }
                    function check_counts_for_popup_disabled(myVar,user_still_working_setting) {
                        if(user_still_working_setting!='454544') {
                            var key_countss = parseInt(document.getElementById('key_count').value);
                            var mouse_countss = parseInt(document.getElementById('mouse_count').value);
                            if(key_countss <=1 && mouse_countss <= 1) {
                                clearInterval(myVar);
                                document.getElementById('dialog-modal-stillworking').style.display = 'block';
                                setInterval(function() {
                                    logout_after_set_time();
                                    },  (60*1000)*logoutsettimemin);
                                } else {
                                    document.getElementById('key_count').value = '1';
                                    document.getElementById('mouse_count').value = '1';
                                }
                            }
                        }
                    </script>
                <?php
                if ($PAGE->pagetype != 'mod-wespher-conference'
                || $PAGE->pagetype != 'mod-wespher-view' || $PAGE->pagetype != 'mod-resource-view'
                || $PAGE->pagetype != 'mod-regularvideo-view' || $PAGE->pagetype != 'mod-forum-view'
                || $PAGE->pagetype != 'mod-book-view' || $PAGE->pagetype != 'mod-assign-view'
                || $PAGE->pagetype != 'mod-survey-view' || $PAGE->pagetype != 'mod-page-view'
                || $PAGE->pagetype != 'mod-quiz-view' || $PAGE->pagetype != 'mod-quiz-attempt'
                || $PAGE->pagetype != 'mod-quiz-summary' || $PAGE->pagetype != 'mod-quiz-summary'
                || $PAGE->pagetype != 'mod-chat-view' || $PAGE->pagetype != 'mod-choice-view'
                || $PAGE->pagetype != 'mod-lti-view' || $PAGE->pagetype != 'mod-feedback-view'
                || $PAGE->pagetype != 'mod-data-view' || $PAGE->pagetype != 'mod-forum-view'
                || $PAGE->pagetype != 'mod-glossary-view' || $PAGE->pagetype != 'mod-scorm-view'
                || $PAGE->pagetype != 'mod-wiki-view' || $PAGE->pagetype != 'mod-workshop-view'
                || $PAGE->pagetype != 'mod-folder-view' || $PAGE->pagetype != 'mod-imscp-view'
                || $PAGE->pagetype != 'mod-label-view' || $PAGE->pagetype != 'mod-url-view') {?>
                    <script type="text/javascript">
                        window.onbeforeunload = function (e) {
                            console.log("clockin time update on reload");
                            var tracking_on = localStorage.getItem("tracked");
                            if(tracking_on=='1') {
                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {}
                                };
                                xhttp.open("GET", teamniourl+"
                                /admin/sync_moodle_course/update_clockin_on_task_update/"+user_id, true);
                                xhttp.send();
                            }
                        };
                    </script>
                    <?php  
                }
            }
    } else {
        if (isset($_COOKIE['popuptlt']) && isset($_COOKIE['popuptlt']) != '') {
            $useremail = $_COOKIE['popuptlt'];
            $liacnsekey = $configweblogintrack->teamnio_web_license;
            $postdata = '&license_key=' . $liacnsekey;
            $ch = curl_init();
            $url = 'https://leeloolxp.com/api_moodle.php/?action=page_info';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, count($postdata));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $output = curl_exec($ch);
            curl_close($ch);
            $infoteamnio = json_decode($output);
            if ($infoteamnio->status != 'false') {
                $teamniourl = $infoteamnio->data->install_url;
            } else {
                return true;
                $teamniourl = 'https://leeloolxp.com/dev';
            }
            $url = $teamniourl . '/admin/sync_moodle_course/get_timezone/';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $outputtimezone = curl_exec($ch);
            curl_close($ch);
            date_default_timezone_set($outputtimezone);
            $url = $teamniourl . '/admin/sync_moodle_course/check_user_by_email/' . $useremail;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $userid = $output;
            $url = $teamniourl . '/login_api/get_shift_details_api/' . $userid;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $output = curl_exec($ch);
            curl_close($ch);
            $shiftdetails = $output;
            $sdetail = json_decode($shiftdetails);
            if ($sdetail->status == 'true') {
                $shiftstarttime = strtotime($sdetail->data->start);
                $shiftendtime = strtotime($sdetail->data->end);
                if ($starttime == '0') {
                    $starttime = date("Y-m-d h:i:s");
                }
                $actualstarttime = strtotime(date('h:i A', strtotime($starttime)));
                $actualendtime = strtotime("now");
                if ($actualstarttime >= $shiftendtime) {
                    $starttimestatus = 'Absent';
                } else {
                    if ($actualstarttime < $shiftstarttime) {
                        $starttimestatus = 'On Time';
                    } else {
                        if ($actualstarttime >= $shiftstarttime) {
                            $starttimestatus = 'Late';
                        }
                    }
                }
                if ($starttimestatus == 'Absent') {
                    $endtimestatus = 'Absent';
                } else {
                    if ($actualendtime <= $shiftendtime) {
                        $endtimestatus = 'Left Early';
                    } else {
                        if ($actualendtime > $shiftendtime) {
                            $endtimestatus = 'On Time';
                        }
                    }
                }
                $postdata = '&user_id=' . $userid . '&start_status=' . $starttimestatus . '&end_status=' . $endtimestatus;
                $ch = curl_init();
                $url = $teamniourl . '/admin/sync_moodle_course/update_attendance_end_status/';
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_POST, count($postdata));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_exec($ch);
                curl_close($ch);
            }
            $loginlogout = $configweblogintrack->web_loginlogout;
            $trackerstop = get_string('tracker_stop', 'local_leeloolxp_web_login_tracking');?>
            <div class="dialog-modal dialog-modal-clockin-start"
            id="dialog-modal-clockin-logout-old" style="display: none;">
                <div class="dialog-modal-inn">
                    <div id="dialog" >
                        <h4><?php echo $trackerstop; ?></h4>
                        <div class="sure-btn">
                            <button data_id = "" onclick="btn_yes_clockin_logout_hide();"
                            class="btn btn_yes_activityunsync" >Ok</button>
                        </div>
                    </div>
                </div>
            </div>
            <style type="text/css">
            .dialog-modal {
                align-self: center;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;height: 100%;z-index: 9999;
                background: rgba(0,0,0,0.7);display: flex;
                align-items: center;justify-content: center;
            }
            .dialog-modal-inn {
                    background: #fff;
                    max-width: 750px;
                    padding: 50px;
                    text-align: center;
                    width: 100%;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }
                .dialog-modal-inn h4 {
                    font-weight: 400;
                    margin: 0 0 25px;
                    font-size: 25px;
                }
                .dialog-modal-inn .sure-btn button {
                    font-size: 20px;
                    padding: .5rem 3rem;
                    color: #fff;
                    background-color: #74cfd0;
                    border: none;
                    display: inline-block;
                    text-decoration: none;
                    outline: none;
                    box-shadow: none;
                    margin: 10px 0;
                }
                .dialog-modal-inn div#dialog {
                    font-size: 17px;
                }
                .dialog-modal-inn p {
                    font-size: 19px;
                }
                .dialog-modal-inn h3 {
                    font-weight: 500;
                    font-size: 22px;
                    color: #f60000;
                }
                .sure-btn {
                    margin: 50px 0 0;
                }
                .anymore-link {
                    margin: 15px 0 0;
                }
                .anymore-link a {
                    color: #74cfd0;
                    font-size: 17px;
                }
                #page-wrapper {
                    z-index: -1 !important;
                }
            </style>
            <?php
            if ($loginlogout) {
                ?><script>
                function btn_yes_clockin_logout_hide() {
                    document.getElementById('dialog-modal-clockin-logout').style.display = 'none';
                }
                var user_id = localStorage.getItem("login_user_id", user_id);
                var teamniourl = '<?php echo $teamniourl; ?>';
                var ca = localStorage.getItem("tracked");
                if(ca=="1") {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var d1 = document.getElementById('page-site-index');
                            d1.insertAdjacentHTML('afterend',
                            '<div class="dialog-modal dialog-modal-clockin-start" id="dialog-modal-clockin-logout">
                            <div class="dialog-modal-inn"><div id="dialog" ><h4><?php echo $trackerstop; ?></h4><div
                            class="sure-btn"><button data_id = "" onclick="btn_yes_clockin_logout_hide();" class="btn
                            btn_yes_activityunsync" >Ok</button></div></div></div></div><style type="text/css"> .
                            dialog-modal {align-self: center;position: fixed;top: 0;left: 0;width: 100%;height: 100%;
                            z-index: 9999;background: rgba(0,0,0,0.7);display: flex;align-items: center;
                            justify-content: center;}.dialog-modal-inn {background: #fff;max-width: 750px;padding:
                            50px;text-align: center;width: 100%;position: absolute;top: 50%;left: 50%;transform:
                            translate(-50%, -50%);}.dialog-modal-inn h4 {font-weight: 400;margin: 0 0 25px;font-size:
                            25px;}.dialog-modal-inn .sure-btn button {font-size: 20px;padding: .5rem 3rem;color: #fff;
                            background-color: #74cfd0;border: none;display: inline-block;text-decoration: none;
                            outline: none;box-shadow: none;margin: 10px 0;}.dialog-modal-inn div#dialog {font-size:
                            17px;}.dialog-modal-inn p {font-size: 19px;}.dialog-modal-inn h3 {font-weight: 500;
                            font-size: 22px;color: #f60000;}.sure-btn {margin: 50px 0 0;}.anymore-link {margin: 15px
                            0 0;}.anymore-link a {color: #74cfd0;font-size: 17px;}#page-wrapper { z-index: -1
                            !important;  } </style>');
                            var script = "<script> function btn_yes_clockin_logout_hide() { document.getElementById
                            ('dialog-modal-clockin-logout').style.display = 'none';}<script>";
                            d1.insertAdjacentHTML('afterend',script);
                            }
                        };
                        xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/stop_clockin/?user_id="+user_id, false);
                        xhttp.send();
                    }
                    localStorage.setItem("tracked",'0');
                    localStorage.setItem("tracked_cancel",'null');
                    localStorage.setItem("tracking_activity_id", "null");
                    </script>
<?php
            }
        }
    }
    return true;
}
