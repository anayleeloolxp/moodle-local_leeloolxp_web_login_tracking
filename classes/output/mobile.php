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
 * Output in mobile app for local_leeloolxp_web_login_tracking.
 *
 * @package    local_leeloolxp_web_login_tracking
 * @author Leeloo LXP <info@leeloolxp.com>
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_leeloolxp_web_login_tracking\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Provider implementation for local_leeloolxp_web_login_tracking.
 *
 */
class mobile {

    public static function view_leeloolxp_web_login_tracking() {
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => '<h1 class="text-center">checkingblocktemplate</h1>',
                ],
            ],
        ];
    }
    
    public static function mobile_init_leeloolxp_web_login_tracking($args) {

        global $CFG;
        require_once($CFG->dirroot . '/local/leeloolxp_web_login_tracking/lib.php');
        require_once($CFG->dirroot . '/lib/filelib.php');
        
        $returnjs = 'class AddonLocalLeeloolxpweblogintrackingOfflineProvider {
            constructor() {

                var thisurl = window.location.href;
                var checkifloginpage = thisurl.includes("/login/");
                if(!checkifloginpage){
                '; 
        //$returnjs = ''; 

        $configleeloolsso = get_config('auth_leeloolxp_tracking_sso');
        if (!isset($configleeloolsso->web_new_user_student) && isset($configleeloolsso->web_new_user_student) == '') {
            return [
                'templates' => [
                    [
                        'id' => 'main',
                        'html' => '<h1 class="text-center">checkingblockinit</h1>',
                    ],
                ],
                'javascript' => 'console.log("auth_leeloolxp_tracking_sso 1")',
            ];
        }
        $configweblogintrack = get_config('local_leeloolxp_web_login_tracking');

        if (!isset($configweblogintrack->logout_time_on_activity) && isset($configweblogintrack->logout_time_on_activity) == '') {
            return [
                'templates' => [
                    [
                        'id' => 'main',
                        'html' => '<h1 class="text-center">checkingblockinit</h1>',
                    ],
                ],
                'javascript' => 'console.log("auth_leeloolxp_tracking_sso 2")',
            ];
        }

        $loginlogout = $configweblogintrack->web_loginlogout;
        if (!$loginlogout) {
            return [
                'templates' => [
                    [
                        'id' => 'main',
                        'html' => '<h1 class="text-center">checkingblockinit</h1>',
                    ],
                ],
                'javascript' => 'console.log("auth_leeloolxp_tracking_sso 3")',
            ];
        }

        $teamniourl = local_leeloolxp_web_login_tracking_get_leelooinstall();
        if ($teamniourl == 'no') {
            return [
                'templates' => [
                    [
                        'id' => 'main',
                        'html' => '<h1 class="text-center">checkingblockinit</h1>',
                    ],
                ],
                'javascript' => 'console.log("auth_leeloolxp_tracking_sso 4")',
            ];
        }

        global $USER;
        global $PAGE;
        global $CFG;
        
        $baseurl = $CFG->wwwroot;
        $sesskey = $USER->sesskey;
        $starttimestatus = '';
        $endtimestatus = '';

        $logoutsettimemin = $configweblogintrack->logout_time_on_activity;
        //$logouturl = $baseurl . "/login/logout.php?sesskey=" . $sesskey;
        $logouturl = '/login/sites';

        if (!isset($USER->email) && isset($USER->email) == '') {
            $useremail = '';
        } else {
            $useremail = $USER->email;
        }

        if ($useremail != '' && $useremail != 'root@localhost') {
            $returnjs .= 'var d = new Date();
                var cname = "popuptlt";
                var cvalue = "' . $useremail . '";
                var exdays = "1";
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            ';
            
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
            $postdata = array('license_key' => $liacnsekey);

            $output = local_leeloolxp_web_login_tracking_checkuser($teamniourl, $useremail);
            if ($output == 'no') {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 5")',
                ];
            }

            if ($output == '0') {
                if ($usercreateflag == 'no') {
                    return [
                        'templates' => [
                            [
                                'id' => 'main',
                                'html' => '<h1 class="text-center">checkingblockinit</h1>',
                            ],
                        ],
                        'javascript' => 'console.log("auth_leeloolxp_tracking_sso 6")',
                    ];
                }
            }

            $userstatusonteamnio = local_leeloolxp_web_login_tracking_checklltstatus($teamniourl, $useremail);
            if ($userstatusonteamnio == 'no') {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 7")',
                ];
            }
            if ($userstatusonteamnio == 0) {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 8")',
                ];
            }
            
            if (is_siteadmin()) {
                $isadmin = '1';
            } else {
                $isadmin = '';
            }
            $usertype = 'student';
            $ssoconfig = get_config('auth_leeloolxp_tracking_sso');
            $userapproval = $ssoconfig->required_aproval_student;
            $lastlogin = date('Y-m-d h:i:s', $USER->lastlogin);
            $fullname = fullname($USER);
            $city = $USER->city;
            $country = $USER->country;
            $timezone = $USER->timezone;
            $skype = $USER->skype;
            $idnumber = $USER->idnumber;
            $institution = $USER->institution;
            $department = $USER->department;
            $phone = $USER->phone1;
            $moodlephone = $USER->phone2;
            $adress = $USER->address;
            $firstaccess = $USER->firstaccess;
            $lastaccess = $USER->lastaccess;
            $lastlogin = $lastlogin;
            $lastip = $USER->lastip;
            if (!isset($USER->description) && isset($USER->description) == '') {
                $description = '';
            } else {
                $description = $USER->description;
            }
            $descriptionofpic = $USER->imagealt;
            $alternatename = $USER->alternatename;
            $webpage = $USER->url;

            $userid = local_leeloolxp_web_login_tracking_getuserid(
                $teamniourl,
                $useremail,
                $username,
                $fullname,
                $designation,
                $isadmin,
                $userapproval,
                $usertype,
                $city,
                $country,
                $timezone,
                $skype,
                $idnumber,
                $institution,
                $department,
                $phone,
                $moodlephone,
                $adress,
                $firstaccess,
                $lastaccess,
                $lastlogin,
                $lastip,
                $description,
                $descriptionofpic,
                $alternatename,
                $webpage
            );

            if ($userid == 'no') {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 9")',
                ];
            }

            $shiftdetails = local_leeloolxp_web_login_tracking_shiftdetails($teamniourl, $userid);
            if ($shiftdetails == 'no') {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 10")',
                ];
            }

            $sdetail = json_decode($shiftdetails);
            $outputtimezone = local_leeloolxp_web_login_tracking_gettimezone($teamniourl);
            if ($outputtimezone == 'no') {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 11")',
                ];
            }

            date_default_timezone_set($outputtimezone);

            $starttime = local_leeloolxp_web_login_tracking_get_attendance_info( $teamniourl, $userid );

            if ($sdetail->status == 'true') {
                if (isset($sdetail->data->start)) {
                    @$shiftstarttime = strtotime($sdetail->data->start);
                    @$shiftendtime = strtotime($sdetail->data->end);

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
                    $postdata = array('user_id' => $userid, 'start_status' => $starttimestatus, 'end_status' => $endtimestatus);
                    local_leeloolxp_web_login_tracking_update_attendance_status($teamniourl, $postdata);
                }
            }

            $output = local_leeloolxp_web_login_tracking_tattctsetting($teamniourl, $userid);
            if ($output == 'no') {
                return [
                    'templates' => [
                        [
                            'id' => 'main',
                            'html' => '<h1 class="text-center">checkingblockinit</h1>',
                        ],
                    ],
                    'javascript' => 'console.log("auth_leeloolxp_tracking_sso 12")',
                ];
            }
            $usersettings = json_decode($output);

            $returnjs .= 'var d1 = document.getElementsByTagName("body")[0]; var maindiv = \'<div class="dialog-modal dialog-modal-clockin-startsss" id="dialog-modal-clockin-startsss" style="display: none;"><div class="dialog-modal-inn"><div id="dialog" ><h4>'.$wannatrackmessage.'</h4><div class="sure-btn"><button data_id="" id="btnid_yes_clockin_start" class="btn btn_yes_activityunsync" > '.get_string("yes", "local_leeloolxp_web_login_tracking").' </button> <button data_id="" id="btnid_no_clockin_start" class="btn btn_yes_activityunsync" > '.get_string('no', 'local_leeloolxp_web_login_tracking').' </button> </div></div></div></div><style type="text/css">.dialog-modal{align-self: center;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;background: rgba(0,0,0,0.7);display: flex;align-items: center;justify-content: center;}.dialog-modal-inn{background: #fff;max-width: 750px;padding: 50px;text-align: center;width: 100%;position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);}.dialog-modal-inn h4{color: black;font-weight: 400; margin: 0 0 25px; font-size: 25px;}.dialog-modal-inn .sure-btn button{font-size: 20px; padding: .5rem 3rem; color: #fff; background-color: #74cfd0; border: none; display: inline-block; text-decoration: none; outline: none; box-shadow: none; margin: 10px;}.dialog-modal-inn div#dialog{font-size: 17px;}.dialog-modal-inn p{font-size: 19px;}.dialog-modal-inn h3{font-weight: 500; font-size: 22px; color: #f60000;}.sure-btn{margin: 50px 0 0;}.anymore-link{margin: 15px 0 0;}.anymore-link a{color: #74cfd0; font-size: 17px;}#page-wrapper{z-index: -1 !important;}</style> <div class="dialog-modal dialog-modal-stillworking" id="dialog-modal-stillworking" style="display: none;"> <div class="dialog-modal-inn"> <div id="dialog" > <h4>'.get_string('still_learning', 'local_leeloolxp_web_login_tracking').'</h4> <div class="sure-btn"> <button data_id="" id="stillid_working_okay" class="btn btn_yes_activityunsync" > '.get_string('still_learning_yes', 'local_leeloolxp_web_login_tracking').' </button> <button data_id="" id="stillid_working_cancel" class="btn btn_yes_activityunsync" > '.get_string('still_learning_no', 'local_leeloolxp_web_login_tracking').' </button> </div></div></div></div><input type="hidden" value="1" id="mouse_count"/> <input type="hidden" value="1" id="key_count"/> <style type="text/css"> .dialog-modal-inn{background:#fff;max-width:750px; padding:50px;text-align:center;width:100%;position:absolute;top:50%; left:50%;transform:translate(-50%,-50%)}. dialog-modal-inn h4{font-weight:400;margin:0 0 25px;font-size:25px}.dialog-modal-inn .sure-btn button{font-size:20px;padding:.5rem 3rem;color:#fff; background-color:#74cfd0;border:none;display:inline-block; text-decoration:none;outline:0;box-shadow:none;margin:10px}.dialog-modal-inn div#dialog{font-size:17px}.dialog-modal-inn p{font-size:19px}.dialog-modal-inn h3{font-weight:500;font-size:22px;color:#f60000}.sure-btn{margin:50px 0 0}.anymore-link{margin:15px 0 0}.anymore-link a{color:#74cfd0;font-size:17px}#page-wrapper{z-index:-1!important}</style>\';d1.insertAdjacentHTML(\'afterend\',maindiv);';

            $returnjs .= '
            
                    function loadDoc_once(userid,time) {
                        var xhttp = new XMLHttpRequest();
                        var starttimestatus =  "'.$starttimestatus.'";
                        var endtimestatus =  "'.$endtimestatus.'";

                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {}
                        };
                        xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/update_clockin/?user_id="+userid+"&starttimestatus="+starttimestatus+"&endtimestatus="+endtimestatus+"&installlogintoken='.$_COOKIE['installlogintoken'].'", false);
                        xhttp.send();
                    }

                    function loadDoc_every_two_m(userid,time) {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {}
                        };
                        xhttp.open("GET", teamniourl+
                        "/admin/sync_moodle_course/update_clockin_every_m/?user_id="+
                        userid+"&installlogintoken='.$_COOKIE['installlogintoken'].'", true);
                        xhttp.send();
                    }

                    function btn_yes_clockin_start() {
                        sessionStorage.setItem("tracked", "1");
                        document.getElementById("dialog-modal-clockin-start").style.display = "none";
                        setTimeout(function(){
                            var trackingon = sessionStorage.getItem("tracked");
                            if(trackingon=="1") {
                                loadDoc_once(userid,60*1000);
                                setInterval(function() {
                                    loadDoc_every_two_m(userid,60*1000);
                                }, 60*1000);
                            }
                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        var clockinbreakspan = document.getElementById("clockin_break_span");
                                        if (typeof(clockinbreakspan) != "undefined" && clockinbreakspan != null){
                                            clockinbreakspan.innerText = this.responseText;
                                        }
                                    }
                                };
                                xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/get_breacks/"+userid+"&installlogintoken='.$_COOKIE['installlogintoken'].'", true);
                                xhttp.send();
                        }, 2000);
                    }
                    function btn_no_clockin_start() {
                        sessionStorage.setItem("tracked", "0");
                        sessionStorage.setItem("tracked_cancel",\'1\');
                        document.getElementById(\'dialog-modal-clockin-start\').style.display = \'none\';
                    }
                    var userid = "'.$userid.'";
                    sessionStorage.setItem("login_userid", userid);
                    var teamniourl = "'.$teamniourl.'";
                    var checkfirst =  sessionStorage.getItem("tracked");
                    var forpopup = sessionStorage.getItem(\'tracked_cancel\');
                    var ispopup = "'.$popupison.'";

                    sessionStorage.setItem("leeloolxpurl", teamniourl);

                    if(forpopup != \'1\') {

                        

                        var mousekeycounttime = setInterval(function() {
                            var  forpopupcanceledsetagain  = sessionStorage.getItem(\'tracked_cancel\');
                            if(forpopupcanceledsetagain==\'1\') {
                                sessionStorage.setItem("tracked_cancel",\'1\');
                            }
                        },  60*1000);

                        var mousecount = 1;
                        document.body.addEventListener("click", function(){
                            mousecount++;
                            document.getElementById(\'mouse_count\').value = mousecount;
                        });

                        var logoutsettimemin  = "'.$logoutsettimemin.'";
                        var keycount = 1;
                        document.body.addEventListener("keydown", function(){
                            keycount++;
                            document.getElementById(\'key_count\').value = keycount;
                        });

                        

                        window.onmessage = function(e) {
                            if (e.data == \'leeloo_social_click\' || e.data == \'leeloo_hero_click\') {
                                mousecount++;
                                document.getElementById(\'mouse_count\').value = mousecount;
                            }

                            if (e.data == \'leeloo_social_key\' || e.data == \'leeloo_hero_key\') {
                                keycount++;
                                document.getElementById(\'key_count\').value = keycount;
                            }
                        };

                        var userstillworkingsetting = "'.$usersettings->user_data->student_still_working_pop_up.'";
                        if(userstillworkingsetting !=\'454544\' ||
                        userstillworkingsetting !="" || userstillworkingsetting !=\'0\') {
                            setInterval(function() {
                                check_counts(mousekeycounttime,userstillworkingsetting);
                            },  (60*1000)*userstillworkingsetting);
                        }

                        function still_working_cancel() {
                            onlogout();
                            window.location.href = "'.$logouturl.'";
                        }

                        function still_working_okay() {
                            location.reload(true);
                        }

                        function logout_after_set_time() {
                            onlogout();
                            window.location.href = "'.$logouturl.'";
                        }

                        function check_counts(myVar,user_still_working_setting) {
                            var forpopupcanceled = sessionStorage.getItem(\'tracked_cancel\');
                            if(forpopupcanceled=="1") {
                                return false;
                            }
                            if(userstillworkingsetting!="454544") {
                                var keycountss = parseInt(document.getElementById(\'key_count\').value);
                                var mousecountss = parseInt(document.getElementById(\'mouse_count\').value);
                                if(keycountss <=1 && mousecountss <= 1) {
                                    clearInterval(myVar);
                                    document.getElementById(\'dialog-modal-stillworking\').style.display = \'block\';
                                    setInterval(function() {
                                        if(forpopupcanceled=="1") {}
                                        else {
                                            logout_after_set_time();
                                        }
                                    },(60*1000)*logoutsettimemin);
                                } else {
                                    document.getElementById("key_count").value = "1";
                                    document.getElementById("mouse_count").value = "1";
                                }
                            }
                        }

                        if(checkfirst=="0" || checkfirst === null) {
                            //var idofbody  = document.getElementsByTagName("body")[0].id;
                            //var d1 = document.getElementById(idofbody);
                            var d1 = document.getElementsByTagName("body")[0];
                            var maindiv = \'<div class="dialog-modal dialog-modal-clockin-start"\';
                            maindiv += \' id="dialog-modal-clockin-start" style="">\';
                            maindiv += \'<div class="dialog-modal-inn"><div id="dialog" ><h4>\';
                            maindiv += \''.$wannatrackmessage.'</h4><div class="sure-btn">\';
                            maindiv += \'<button data_id = "" id="btnid_yes_clockin_start"\';
                            maindiv += \' class="btn btn_yes_activityunsync" >'.get_string('yes', 'local_leeloolxp_web_login_tracking').'</button>\';
                            maindiv += \'<button data_id = "" id="btnid_no_clockin_start"\';
                            maindiv += \' class="btn btn_yes_activityunsync" >'.get_string('no', 'local_leeloolxp_web_login_tracking').'</button>\';
                            maindiv += \'</div></div></div></div><style type="text/css">\';
                            maindiv += \'.dialog-modal {align-self: center;position: fixed;top: 0;left: 0;\';
                            maindiv += \'width: 100%;height: 100%;z-index: 9999;background: rgba(0,0,0,0.7);\';
                            maindiv += \'display: flex;align-items: center;justify-content: center;}\';
                            maindiv += \'.dialog-modal-inn {background: #fff;max-width: 750px;padding: 50px;\';
                            maindiv += \'text-align: center;width: 100%;position: absolute;top: 50%;left: 50%;\';
                            maindiv += \'transform: translate(-50%, -50%);}.dialog-modal-inn h4 {font-weight: 400;\';
                            maindiv += \'margin: 0 0 25px;font-size: 25px;color:black;}.dialog-modal-inn .sure-btn\';
                            maindiv += \'button {font-size: 20px;padding: .5rem 3rem;color: #fff;\';
                            maindiv += \'background-color: #74cfd0;border: none;display: inline-block;\';
                            maindiv += \'text-decoration: none;outline: none;box-shadow: none;\';
                            maindiv += \'margin: 10px 0;}.dialog-modal-inn div#dialog {font-size: 17px;}\';
                            maindiv += \'.dialog-modal-inn p {font-size: 19px;}.dialog-modal-inn h3\';
                            maindiv += \'{font-weight: 500;font-size: 22px;color: #f60000;}\';
                            maindiv += \'.sure-btn {margin: 50px 0 0;}.anymore-link {margin: 15px 0 0;}\';
                            maindiv += \'.anymore-link a {color: #74cfd0;font-size: 17px;}\';
                            maindiv += \'#page-wrapper { z-index: -1 !important;  } </style>\';

                            d1.insertAdjacentHTML(\'afterend\',maindiv);

                            if(ispopup != "1"){
                                btn_yes_clockin_start();
                            }
                        }else{
                            setInterval(function() {
                                loadDoc_every_two_m(userid,60*1000);
                            }, 60*1000);
                        }

                        document.getElementById("btnid_yes_clockin_start").addEventListener("click", function() {
                            console.log("btn_yes_clockin_start");
                            btn_yes_clockin_start();
                        });

                        document.getElementById("btnid_no_clockin_start").addEventListener("click", function() {
                            console.log("btn_no_clockin_start");
                            btn_no_clockin_start();
                        });

                        document.getElementById("stillid_working_okay").addEventListener("click", function() {
                            console.log("still_working_okay");
                            still_working_okay();
                        });

                        document.getElementById("stillid_working_cancel").addEventListener("click", function() {
                            console.log("still_working_cancel");
                            still_working_cancel();
                        });

                    }
                }
                ';

                $trackerstop = get_string('tracker_stop', 'local_leeloolxp_web_login_tracking');

                $returnjs .= '
                
                function onlogout(){
                    function btn_yes_clockin_logout_hide() {
                        document.getElementById("dialog-modal-clockin-logout").style.display = "none";
                    }

                    var userid = sessionStorage.getItem("login_userid", userid);
                    var teamniourl = "'.$teamniourl.'";
                    var ca = sessionStorage.getItem("tracked");
                    if(ca=="1") {
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                var d1 = document.getElementsByTagName("body")[0];
                                var inhtml = \'<div class="dialog-modal dialog-modal-clockin-start"\';
                                inhtml += \' id="dialog-modal-clockin-logout"><div class="dialog-modal-inn">\';
                                inhtml += \'<div id="dialog" ><h4>'.$trackerstop.'</h4>\';
                                inhtml += \'<div class="sure-btn"><button data_id = ""\';
                                inhtml += \' id="btnid_yes_clockin_logout_hide"\';
                                inhtml += \' class="btn btn_yes_activityunsync" >'.get_string('ok', 'local_leeloolxp_web_login_tracking').'</button></div></div></div></div>\';
                                inhtml += \'<style type="text/css"> .dialog-modal {align-self: center;position: fixed;top: 0;\';
                                inhtml += \'left: 0;width: 100%;height: 100%;z-index: 9999;background: rgba(0,0,0,0.7);\';
                                inhtml += \'display: flex;align-items: center;justify-content: center;}.\';
                                inhtml += \'dialog-modal-inn {background: #fff;max-width: 750px;padding:50px;text-align: center;\';
                                inhtml += \'width: 100%;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);}\';
                                inhtml += \'.dialog-modal-inn h4 {color: black;font-weight: 400;margin: 0 0 25px;font-size: 25px;}\';
                                inhtml += \'dialog-modal-inn .sure-btn button {font-size: 20px;padding: .5rem 3rem;color: #fff;\';
                                inhtml += \'background-color: #74cfd0;border: none;display: inline-block;text-decoration: none;\';
                                inhtml += \'outline: none;box-shadow: none;margin: 10px 0;}.dialog-modal-inn div#dialog\';
                                inhtml += \'{font-size:17px;}.dialog-modal-inn p {font-size: 19px;}.dialog-modal-inn h3\';
                                inhtml += \'{font-weight: 500;font-size: 22px;color: #f60000;}.sure-btn {margin: 50px 0 0;}.\';
                                inhtml += \'anymore-link {margin: 15px0 0;}.anymore-link a {color: #74cfd0;font-size: 17px;}\';
                                inhtml += \'#page-wrapper { z-index: -1!important;  } </style>\';
                                d1.insertAdjacentHTML("afterend", inhtml);

                                document.getElementById("btnid_yes_clockin_logout_hide").addEventListener("click", function() {
                                    console.log("btn_yes_clockin_logout_hide");
                                    btn_yes_clockin_logout_hide();
                                });
        
                                var ispopup = "'.$configweblogintrack->web_loginlogout_popup.'";
                                if(ispopup != "1"){
                                    btn_yes_clockin_logout_hide();
                                }
                            }
                        };
                        xhttp.open("GET", teamniourl+"/admin/sync_moodle_course/stop_clockin/?user_id="+userid+"&installlogintoken='.$_COOKIE['installlogintoken'].'", false);
                            xhttp.send();
                    }
                    sessionStorage.setItem("tracked","0");
                    sessionStorage.setItem("tracked_cancel","null");
                    sessionStorage.setItem("tracking_activity_id", "null");
        
                    var d = new Date();
                    var cname = "popuptlt";
                    var cvalue = "";
                    var exdays = "1";
                    d.setTime(d.getTime() + (exdays*24*60*60*1000));
                    var expires = "expires="+ d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                }

                const myInterval = setInterval(function() {
                    var thisurl = window.location.href;
                    var checkifmorepage = thisurl.includes("/main/more");
                    //console.log(thisurl);
                    if( checkifmorepage ){
                        clearInterval(myInterval);
                        console.log("added addEventListener");

                        document.getElementById("logout_changesite_btn").addEventListener("click", function() {
                            console.log("logout_changesite_btn");
        
                            onlogout();
        
                        });

                    }
                }, 1000);

                

                ';

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
                || $PAGE->pagetype != 'mod-label-view' || $PAGE->pagetype != 'mod-url-view') {
                    
                    $returnjs .= 'window.onbeforeunload = function (e) {
                            var tracking_on = sessionStorage.getItem("tracked");
                            if(tracking_on=="1") {
                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        console.log("clockin time updated on reload");
                                    }
                                };
                                var newurl = teamniourl;
                                newurl += "/admin/sync_moodle_course/update_clockin_on_task_update/"+userid+"&installlogintoken='.$_COOKIE['installlogintoken'].'";
                                xhttp.open("GET",newurl,true);
                                xhttp.send();
                            }
                        };';
                }
        } else {
            return [
                'templates' => [
                    [
                        'id' => 'main',
                        'html' => '<h1 class="text-center">checkingblockinit</h1>',
                    ],
                ],
                'javascript' => 'console.log("auth_leeloolxp_tracking_sso else")',
            ];
            
            
        }


        $returnjs .= '} }

        const weblogintrackingOffline = new AddonLocalLeeloolxpweblogintrackingOfflineProvider();
        const result = {
            weblogintrackingOffline: weblogintrackingOffline,
        };
        
        result;
        ';

        file_put_contents(dirname(__FILE__).'/returnjs.js', print_r($returnjs, true));
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => '<h1 class="text-center">checkingblockinit</h1>',
                ],
            ],
            'javascript' => 'console.log("auth_leeloolxp_tracking_sso end"); '.$returnjs.' ',
            //'javascript' => 'console.log("auth_leeloolxp_tracking_sso end \'.$returnjs.\' "); ',
        ];
    }

}
