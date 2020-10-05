<?php 
defined('MOODLE_INTERNAL') || die();
require_once(dirname(dirname(__DIR__)) . '/config.php');
function local_leeloolxp_web_login_tracking_before_footer() {
	$config_leeloolxp_web_login_tracking = get_config('local_leeloolxp_web_login_tracking');
	global $USER;
	global $PAGE;
	global $DB;
	global $CFG;
   
	$baseurl = $CFG->wwwroot;
	$sesskey = $USER->sesskey;
	$logoutset_time_min = $config_leeloolxp_web_login_tracking->logout_time_on_activity;
	$logout_url  = $baseurl."/login/logout.php?sesskey=".$sesskey;
	$useremail = $USER->email;
	$cookie_name = "user_email";
	$cookie_value = $useremail;
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	if($useremail != '' && $useremail != 'root@localhost'){
		echo '<script type="text/javascript">
			var d = new Date();
			var cname = "popuptlt";
			var cvalue = "'.$useremail.'";
			var exdays = "1";
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+ d.toUTCString();
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
			</script>';
			$loginlogout = $config_leeloolxp_web_login_tracking->web_loginlogout;
			$create_student_record = $DB->get_record_sql("SELECT * FROM {config_plugins} where name =  'web_new_user_student' and `plugin` = 'leeloolxp_tracking_sso'");
			$usercreate_flag = $create_student_record->value;
			$default_user_designation_record = $DB->get_record_sql("SELECT * FROM {config_plugins} where name =  'default_student_position' and `plugin` = 'leeloolxp_tracking_sso'");
			$designation = $default_user_designation_record->value;
			if($usercreate_flag) {
				$usercreate_flag = 'yes';
			}else {
				$usercreate_flag = 'no';
			}
			$username = $USER->username;
			$user_email =  $USER->email;
			$user_firstname_last_name =  ucfirst($USER->firstname)." ".ucfirst($USER->lastname);
			$working_date = date('Y-m-d');
			$rand_num = rand();   
			$not_login_message =  get_string('not_login_message','local_leeloolxp_web_login_tracking');
			$wanna_track_message =  get_string('wanna_track_message','local_leeloolxp_web_login_tracking');
			$liacnse_key =  $config_leeloolxp_web_login_tracking->teamnio_web_license; // get liacnse_key
			$popup_is_on =  $config_leeloolxp_web_login_tracking->web_loginlogout_popup;
			$postData = '&license_key='.$liacnse_key;
			/* get Leelo teamnio url using license_key */
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
				return true;
			}

			$url = $teamnio_url.'/admin/sync_moodle_course/check_user_by_email/'.$user_email; // get task id from teamnio
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch);
			curl_close($ch);
			$user_exist_on_teamnio = $output;
			if($user_exist_on_teamnio=='0') {
				if($usercreate_flag=='no') {
					return true;
				}
			}
			$url = $teamnio_url.'/admin/sync_moodle_course/check_user_status_by_email/'.$user_email; // get user status from teamnio
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch);
			curl_close($ch);
			$user_status_on_teamnio = $output;
			if($user_status_on_teamnio == 0){
				return true;
			}
			if($loginlogout){ 
 			

				?>

				<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
				<div class="dialog-modal dialog-modal-clockin-startsss" id="dialog-modal-clockin-startsssssssss" style="display: none;">

				    <div class="dialog-modal-inn">

				        <div id="dialog" >

				            <h4><?php echo $wanna_track_message; ?></h4>

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
                            	<button data_id = "" onclick="still_working_okay();" class="btn btn_yes_activityunsync" ><?php echo get_string('still_learning_yes', 'local_leeloolxp_web_tat'); ?></button>

                                <button data_id = "" onclick="still_working_cancel();" class="btn btn_yes_activityunsync" ><?php echo get_string('still_learning_no', 'local_leeloolxp_web_tat'); ?></button>
                            </div>



                        </div>



                    </div>



                </div>

                <input type = 'hidden' value = '1' id='mouse_count'/>

                <input type = 'hidden' value = '1' id='key_count'/> 

                <style type="text/css">

                    .dialog-modal-inn{background:#fff;max-width:750px;padding:50px;text-align:center;width:100%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)}.dialog-modal-inn h4{font-weight:400;margin:0 0 25px;font-size:25px}.dialog-modal-inn .sure-btn button{font-size:20px;padding:.5rem 3rem;color:#fff;background-color:#74cfd0;border:none;display:inline-block;text-decoration:none;outline:0;box-shadow:none;margin:10px 0}.dialog-modal-inn div#dialog{font-size:17px}.dialog-modal-inn p{font-size:19px}.dialog-modal-inn h3{font-weight:500;font-size:22px;color:#f60000}.sure-btn{margin:50px 0 0}.anymore-link{margin:15px 0 0}.anymore-link a{color:#74cfd0;font-size:17px}#page-wrapper{z-index:-1!important}

                </style>


			<?php

			//* Get user and create new one if not exist  *//
			if(is_siteadmin()) { 
				$is_admin = '1';
			}else {
			 	$is_admin = '';
			}
			
			$sso_config = get_config('leeloolxp_tracking_sso');
			$user_approval = $sso_config->required_aproval_student;
			$lastlogin = date('Y-m-d h:i:s',$USER->lastlogin);
            $fullname = ucfirst($USER->firstname)." ".ucfirst($USER->middlename)." ".ucfirst($USER->lastname);
            $city =  $USER->city;
            $country = $USER->country;
            $timezone = $USER->timezone;
            $skype = $USER->skype;
            $idnumber = $USER->idnumber;
            $institution = $USER->institution;
            $department = $USER->department;
            $phone = $USER->phone1;
            $moodle_phone = $USER->phone2;
            $adress = $USER->adress;
            $firstaccess = $USER->firstaccess;
            $lastaccess = $USER->lastaccess;
            $lastlogin = $lastlogin;
            $lastip = $USER->lastip;
            $description = $USER->description;
            $description_of_pic = $USER->imagealt;
            $alternatename = $USER->alternatename;
            $web_page = $USER->url;
            $url = $teamnio_url.'/admin/sync_moodle_course/get_create_user/?user_email='.$user_email.'&username='.$username.'&name='.$fullname."&user_designation=".$designation."&is_company_admin=".$is_admin."&user_approval=".$user_approval."&can_user_create=".$can_create_user->value."&user_type=".$user_type."&city=".$city."&country=".$country."&timezone=".$timezone."&skype=".$skype."&idnumber=".$idnumber."&institution=".$institution."&department=".$department."&phone=".$phone."&moodle_phone=".$moodle_phone."&adress=".$adress."&firstaccess=".$firstaccess."&lastaccess=".$lastaccess."&lastlogin=".$lastlogin."&lastip=".$lastip."&user_profile_pic=".urlencode($moodle_pic_data)."&user_description=".$description."&picture_description=".$description_of_pic."&institution=".$institution."&alternate_name=".$alternatename."&web_page=".$web_page;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $user_id = curl_exec($ch);
            curl_close($ch);
            //* Get user and create new one if not exist  *//

            $url = $teamnio_url.'/login_api/get_shift_details_api/'.$user_id; // get task id from teamnio



            $ch = curl_init($url);



            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



            curl_setopt($ch,CURLOPT_HEADER, false); 



            $output = curl_exec($ch);



            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {



               



            }



            curl_close($ch);



            $Shift_details = $output;



            $s_detail = json_decode($Shift_details);

            $url = $teamnio_url.'/admin/sync_moodle_course/get_timezone/';



            $ch = curl_init($url);



            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



            curl_setopt($ch,CURLOPT_HEADER, false); 



             $output_time_zone = curl_exec($ch);



            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {







            }



            curl_close($ch);

			date_default_timezone_set($output_time_zone);

            
			$url = $teamnio_url.'/admin/sync_moodle_course/get_attendance_info/'.$user_id; // get task id from teamnio



            $ch = curl_init($url);



            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



            curl_setopt($ch,CURLOPT_HEADER, false); 



            $output = curl_exec($ch);



            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {



               



            }



            curl_close($ch);



            $start_time =  $output; 


       	if($s_detail->status == 'true') {

       		$shift_start_time = strtotime($s_detail->data->start);
            $shift_end_time = strtotime($s_detail->data->end);
            
            if($start_time=='0') {
                $start_time = date("Y-m-d h:i:s");
            }
          $actual_start_time = strtotime(date('h:i A', strtotime($start_time)));
            $actual_end_time = strtotime("now");
            if($actual_start_time>=$shift_end_time) {
                $start_time_status = 'Absent';
            } else {
                if($actual_start_time<$shift_start_time) {
                    $start_time_status = 'On Time';
                } else {
                    if($actual_start_time>=$shift_start_time) {
                       $start_time_status = 'Late'; 
                    }
                }
            }
            if($start_time_status=='Absent') {

            	$end_time_status = 'Absent'; 

            } else {
                if($shift_end_time>$actual_end_time) {
                    $end_time_status = 'On Time (Learning now)';
                }else {
                    if($actual_end_time>$shift_end_time) 
                    {
                        $end_time_status = 'On Time (Learning now)';
                    }
                }

            }
            $postData = '&user_id='.$user_id.'&start_status='.$start_time_status.'&end_status='.$end_time_status;
            $ch = curl_init();
       		$url = $teamnio_url.'/admin/sync_moodle_course/update_attendance_status/';



            



            curl_setopt($ch,CURLOPT_URL,$url);



            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);



            curl_setopt($ch,CURLOPT_HEADER, false); 



            curl_setopt($ch,CURLOPT_POST, count($postData));



            curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);  



            $output_status=curl_exec($ch);



            curl_close($ch);
        }

            /* get user track settings */
            $url = $teamnio_url.'/admin/sync_moodle_course/get_user_settings_tct_tat/'.$user_id; // get task id from teamnio
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $output = curl_exec($ch);
            $user_settings = json_decode($output);
            curl_close($ch);
            /*get user track settings close*/
            ?>
            <script>
            	function btn_yes_clockin_start() {
					localStorage.setItem("tracked", "1");
					document.getElementById('dialog-modal-clockin-start').style.display = 'none';
					//loadDoc_once(user_id,60*1000);
					setTimeout(function(){ 
						var tracking_on = localStorage.getItem("tracked");
						if(tracking_on=='1') {
							loadDoc_once(user_id,60*1000);
							setInterval(function() {
									loadDoc_every_two_m(user_id,60*1000);
								}, 60*1000); // 60 * 1000 milsec
						}
						// fetch break time and appned  //
                        var xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                var clockin_break_span = document.getElementById('clockin_break_span');
                                if (typeof(clockin_break_span) != 'undefined' && clockin_break_span != null){
                                    clockin_break_span.innerText = this.responseText;
                                }
                            }
                        };
                        xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/get_breacks/"+user_id, true);
                        xhttp.send();
                        // fetch break time and appned  //
                    }, 2000);
				}

				function btn_no_clockin_start() {

					localStorage.setItem("tracked", "0");

					localStorage.setItem("tracked_cancel",'1');

					document.getElementById('dialog-modal-clockin-start').style.display = 'none';

				}

				var user_id = '<?php echo $user_id; ?>';

				localStorage.setItem("login_user_id", user_id);

				var teamnio_url = '<?php echo $teamnio_url; ?>';

				var check_first =  localStorage.getItem("tracked");

				var for_popup = localStorage.getItem('tracked_cancel');

				var is_popup = '<?php echo $popup_is_on; ?>';

				if(is_popup=='1') { 

					if(for_popup != '1') {   

						if(check_first=='0') { 
							var id_of_body  = document.getElementsByTagName("body")[0].id;
							var d1 = document.getElementById(id_of_body);
							d1.insertAdjacentHTML('afterend', '<div class="dialog-modal dialog-modal-clockin-start" id="dialog-modal-clockin-start" style=""><div class="dialog-modal-inn"><div id="dialog" ><h4><?php echo $wanna_track_message; ?></h4><div class="sure-btn"><button data_id = "" onclick="btn_yes_clockin_start();" class="btn btn_yes_activityunsync" >Yes</button><button data_id = "" onclick="btn_no_clockin_start();" class="btn btn_yes_activityunsync" >No</button></div></div></div></div><style type="text/css"> .dialog-modal { align-self: center;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;background: rgba(0,0,0,0.7);display: flex;align-items: center;justify-content: center;}.dialog-modal-inn {background: #fff;max-width: 750px;padding: 50px;text-align: center;width: 100%;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);}.dialog-modal-inn h4 {font-weight: 400;margin: 0 0 25px;font-size: 25px;}.dialog-modal-inn .sure-btn button {font-size: 20px;padding: .5rem 3rem;color: #fff;background-color: #74cfd0;border: none;display: inline-block;text-decoration: none;outline: none;box-shadow: none;margin: 10px 0;}.dialog-modal-inn div#dialog {font-size: 17px;}.dialog-modal-inn p {font-size: 19px;}.dialog-modal-inn h3 {font-weight: 500;font-size: 22px;color: #f60000;}.sure-btn {margin: 50px 0 0;}.anymore-link {margin: 15px 0 0;}.anymore-link a {color: #74cfd0;font-size: 17px;}#page-wrapper { z-index: -1 !important;  } </style>');
									
							var script = "<script> function btn_yes_clockin_start() {  localStorage.setItem('tracked', '1');document.getElementById('dialog-modal-clockin-start').style.display = 'none';}function btn_no_clockin_start() {localStorage.setItem('tracked', '0');localStorage.setItem('tracked_cancel','1');document.getElementById('dialog-modal-clockin-start').style.display = 'none';<script>";
									d1.insertAdjacentHTML('afterend',script);
						}

						function loadDoc_once(user_id,time) {
							var xhttp = new XMLHttpRequest();
							xhttp.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {}
							};
							xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/update_clockin/?user_id="+user_id, false);
							xhttp.send();
						}
						function loadDoc_every_two_m(user_id,time) {
							var xhttp = new XMLHttpRequest();
							xhttp.onreadystatechange = function() {
								if (this.readyState == 4 && this.status == 200) {

								}
							};
							xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/update_clockin_every_m/?user_id="+user_id, true);
							xhttp.send();
						}
						
						var mouse_key_count_time = setInterval(function() {

                        var  for_popup_canceled_set_again  = localStorage.getItem('tracked_cancel');
                                if(for_popup_canceled_set_again=='1') {
                                    localStorage.setItem("tracked_cancel",'1');
                                }
                        },  60*1000);

						var mouse_count = 1;       

                        document.body.addEventListener("click", function(){

                            mouse_count++;

                            document.getElementById('mouse_count').value = mouse_count;

                        });
                        var logoutset_time_min  = '<?php echo $logoutset_time_min; ?>';
                        var key_count = 1;
                        document.body.addEventListener("keydown", function(){

                            key_count++;

                            document.getElementById('key_count').value = key_count;



                        });

                        var user_still_working_setting = '<?php echo $user_settings->user_data->student_still_working_pop_up;?>';
                        
                          // in Leleeo for disbaled value is 454544
                        if(user_still_working_setting!='454544')
                        { 
                        	setInterval(function() {
	                        	check_counts(mouse_key_count_time,user_still_working_setting);
	                        },  (60*1000)*user_still_working_setting); // 60 * 1000 milsec
	                    }
	                    function still_working_cancel() {
                         	window.location.href = '<?php echo $logout_url;?>';
                        }
                        function still_working_okay() {
                        	location.reload(true);
                        }
                        function logout_after_set_time() {
                        	window.location.href = '<?php echo $logout_url;?>';
                        }

                        function check_counts(myVar,user_still_working_setting) {
                            var for_popup_canceled = localStorage.getItem('tracked_cancel');
                            if(for_popup_canceled=='1') {
                                return false;
                            }                     
                           
                            	// in Leleeo for disbaled value is 454544   
                                if(user_still_working_setting!='454544') { 

                                	var key_countss = parseInt(document.getElementById('key_count').value);

                                	var mouse_countss = parseInt(document.getElementById('mouse_count').value);

                                if(key_countss <=1 && mouse_countss <= 1) {

                                    clearInterval(myVar);                        
                                   
                                    document.getElementById('dialog-modal-stillworking').style.display = 'block';

                                    setInterval(function() {
                                    	if(for_popup_canceled=='1') { 

                                    	} else {
                                        	logout_after_set_time();
                                        }
                                    },  (60*1000)*logoutset_time_min); // 60 * 1000 milsec

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
                        	if (this.readyState == 4 && this.status == 200) {

                        	}
                        };
                        xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/update_clockin/?user_id="+user_id, false);
                        xhttp.send();
                    }



					function loadDoc_every_two_m(user_id,time) {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {}
						};
						xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/update_clockin_every_m/?user_id="+user_id, true);
						xhttp.send();
					}

					
					loadDoc_once(user_id,60*1000);

					setInterval(function() {
							loadDoc_every_two_m(user_id,60*1000);
						}, 60*1000); // 60 * 1000 milsec
					}

					var mouse_key_count_time = setInterval(function() {


                        },  60*1000);

                        var mouse_count = 1;       

                        document.body.addEventListener("click", function(){

                            mouse_count++;

                            document.getElementById('mouse_count').value = mouse_count;

                        });
                        var logoutset_time_min  = '<?php echo $logoutset_time_min; ?>';


                        var key_count = 1;

                        document.body.addEventListener("keydown", function(){

                            key_count++;

                            document.getElementById('key_count').value = key_count;



                        });

                        var user_still_working_setting = '<?php echo $user_settings->user_data->student_still_working_pop_up;?>';

                        if(user_still_working_setting!='454544')
                        { 

                            setInterval(function() {
                            	if(is_popup!='1') {  
                                	check_counts_for_popup_disabled(mouse_key_count_time,user_still_working_setting);
                            	}
                            },  (60*1000)*user_still_working_setting); // 60 * 1000 milsec
                        }

                        function still_working_cancel() {
                            window.location.href = '<?php echo $logout_url;?>';
                        }

                        function still_working_okay() {

                            location.reload(true);

                        }

                        function logout_after_set_time() {

                            window.location.href = '<?php echo $logout_url;?>';

                        }

                        function check_counts_for_popup_disabled(myVar,user_still_working_setting) {
                          

                                // in Leleeo for disbaled value is 454544
                                if(user_still_working_setting!='454544') { 

                                var key_countss = parseInt(document.getElementById('key_count').value);

                                var mouse_countss = parseInt(document.getElementById('mouse_count').value);



                                if(key_countss <=1 && mouse_countss <= 1) {

                                    clearInterval(myVar);                        

                                    document.getElementById('dialog-modal-stillworking').style.display = 'block';



                                    setInterval(function() {

                                        logout_after_set_time();

                                    },  (60*1000)*logoutset_time_min); // 60 * 1000 milsec

                                    

                                } else {
                                    document.getElementById('key_count').value = '1';
                                    document.getElementById('mouse_count').value = '1';
                                }

                            }

                        }

			</script>


			<?php 
				// check its not activity if these are activity page then we will update clockin time from activity plugin.
				if ( $PAGE->pagetype=='mod-wespher-conference' || $PAGE->pagetype=='mod-wespher-view' ||  $PAGE->pagetype=='mod-resource-view' || $PAGE->pagetype=='mod-regularvideo-view' || $PAGE->pagetype=='mod-forum-view' || $PAGE->pagetype=='mod-book-view' || $PAGE->pagetype=='mod-assign-view' || $PAGE->pagetype=='mod-survey-view' || $PAGE->pagetype=='mod-page-view' || $PAGE->pagetype=='mod-quiz-view' || $PAGE->pagetype=='mod-quiz-attempt' || $PAGE->pagetype=='mod-quiz-summary' || $PAGE->pagetype=='mod-quiz-summary' || $PAGE->pagetype=='mod-chat-view' || $PAGE->pagetype=='mod-choice-view' || $PAGE->pagetype=='mod-lti-view' || $PAGE->pagetype == 'mod-feedback-view' || $PAGE->pagetype == 'mod-data-view' ||  $PAGE->pagetype == 'mod-forum-view' || $PAGE->pagetype == 'mod-glossary-view' ||  $PAGE->pagetype == 'mod-scorm-view' || $PAGE->pagetype == 'mod-wiki-view' || $PAGE->pagetype == 'mod-workshop-view' || $PAGE->pagetype == 'mod-folder-view' || $PAGE->pagetype == 'mod-imscp-view' || $PAGE->pagetype == 'mod-label-view' || $PAGE->pagetype == 'mod-url-view'  ) { 
				  } else { ?>
					  	<script type="text/javascript">
						  	window.onbeforeunload = function (e) {
							  	console.log("clockin time update on reload");
			                    var tracking_on = localStorage.getItem("tracked");
								if(tracking_on=='1') {
									var xhttp = new XMLHttpRequest();
									xhttp.onreadystatechange = function() {
										// check ajax state.
										if (this.readyState == 4 && this.status == 200) {

										}
									};
									xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/update_clockin_on_task_update/"+user_id, true);
									xhttp.send();
								}
							};
	                   	</script>
				  <?php }

			}
	}else{
		if( isset($_COOKIE['popuptlt']) && isset($_COOKIE['popuptlt'])!= '' ){

			
			$useremail = $_COOKIE['popuptlt'];

			$rand_num = rand();

			$liacnse_key =  $config_leeloolxp_web_login_tracking->teamnio_web_license;

			$postData = '&license_key='.$liacnse_key;

			$ch = curl_init();  

			$url = 'https://leeloolxp.com/api_moodle.php/?action=page_info';

			curl_setopt($ch,CURLOPT_URL,$url);

			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

			curl_setopt($ch,CURLOPT_HEADER, false); 

			curl_setopt($ch,CURLOPT_POST, count($postData));

			curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);  

			$output=curl_exec($ch);

			curl_close($ch);

			$info_teamnio  = json_decode($output);

			if($info_teamnio->status!='false') {
				$teamnio_url = $info_teamnio->data->install_url;
			} else {
				return true;
				$teamnio_url = 'https://leeloolxp.com/dev';
			}

				// update end time for attendance
				//* get and set timezone *//

				$url = $teamnio_url.'/admin/sync_moodle_course/get_timezone/';

	            $ch = curl_init($url);

	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	            curl_setopt($ch,CURLOPT_HEADER, false); 

	            $output_time_zone = curl_exec($ch);

	            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {



	            }

	            curl_close($ch);
				date_default_timezone_set($output_time_zone);
				//* get and set timezone *//

				$url = $teamnio_url.'/admin/sync_moodle_course/check_user_by_email/'.$useremail; // get task id from teamnio
				$ch = curl_init($url);
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	            $output = curl_exec($ch);
	            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {

	            }

	            curl_close($ch);

	            $user_id = $output;




				//* get shift details *//
				$url = $teamnio_url.'/login_api/get_shift_details_api/'.$user_id; // get task id from teamnio

	            $ch = curl_init($url);

	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	            curl_setopt($ch,CURLOPT_HEADER, false); 

	            $output = curl_exec($ch);

	            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {

	               

	            }

	            curl_close($ch);

	            $Shift_details = $output;

	            $s_detail = json_decode($Shift_details);

	        	if($s_detail->status =='true') {

		         	$shift_start_time = strtotime($s_detail->data->start);
		            $shift_end_time = strtotime($s_detail->data->end);
		            
		            if($start_time=='0') {
		            	$start_time = date("Y-m-d h:i:s");
		            }
		          	$actual_start_time = strtotime(date('h:i A', strtotime($start_time)));
		            $actual_end_time = strtotime("now");

		            if($actual_start_time>=$shift_end_time) {
		                $start_time_status = 'Absent';
		            } else {
		            	if($actual_start_time<$shift_start_time) {
		            		$start_time_status = 'On Time';
		            	} else {
		            		if($actual_start_time>=$shift_start_time) {
		            			$start_time_status = 'Late';
		            		}
		            	}
		            }

		            if($start_time_status=='Absent') {
		            	$end_time_status = 'Absent'; 
		            } else {
		            	if($actual_end_time<=$shift_end_time) {
		            		$end_time_status = 'Left Early';
		            	} else {
		            		if($actual_end_time>$shift_end_time) {
		            			$end_time_status = 'On Time';
		            		}
		            	}
		            }


		            $postData = '&user_id='.$user_id.'&start_status='.$start_time_status.'&end_status='.$end_time_status;

		            $ch = curl_init();  

		            $url = $teamnio_url.'/admin/sync_moodle_course/update_attendance_end_status/';

		            curl_setopt($ch,CURLOPT_URL,$url);

		            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

		            curl_setopt($ch,CURLOPT_HEADER, false); 

		            curl_setopt($ch,CURLOPT_POST, count($postData));

		            curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);  

		            $output_status=curl_exec($ch);
					
		            curl_close($ch);
	        	}

			$working_date = date('Y-m-d');

			$loginlogout = $config_leeloolxp_web_login_tracking->web_loginlogout;

			$error_message_tracker_stop =  get_string('error_message_tracker_stop','local_leeloolxp_web_login_tracking');

			$tracker_stop = get_string('tracker_stop','local_leeloolxp_web_login_tracking'); ?> 

			<div class="dialog-modal dialog-modal-clockin-start" id="dialog-modal-clockin-logout-old" style="display: none;">

				<div class="dialog-modal-inn">

					<div id="dialog" >

						<h4><?php echo $tracker_stop; ?></h4>

						<div class="sure-btn">

							<button data_id = "" onclick="btn_yes_clockin_logout_hide();" class="btn btn_yes_activityunsync" >Ok</button>

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



			<?php

			if($loginlogout){ ?><script>

						function btn_yes_clockin_logout_hide() {

							document.getElementById('dialog-modal-clockin-logout').style.display = 'none';	

						}

						var user_id = localStorage.getItem("login_user_id", user_id);

						var teamnio_url = '<?php echo $teamnio_url; ?>';

						var ca = localStorage.getItem("tracked");

						//alert(teamnio_url+"/admin/sync_moodle_course/stop_clockin/?user_id="+user_id);

						if(ca=="1") {

							var xhttp = new XMLHttpRequest();

							xhttp.onreadystatechange = function() {

								if (this.readyState == 4 && this.status == 200) {
									var d1 = document.getElementById('page-site-index');
									d1.insertAdjacentHTML('afterend', '<div class="dialog-modal dialog-modal-clockin-start" id="dialog-modal-clockin-logout" ><div class="dialog-modal-inn"><div id="dialog" ><h4><?php echo $tracker_stop; ?></h4><div class="sure-btn"><button data_id = "" onclick="btn_yes_clockin_logout_hide();" class="btn btn_yes_activityunsync" >Ok</button></div></div></div></div><style type="text/css"> .dialog-modal {align-self: center;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 9999;background: rgba(0,0,0,0.7);display: flex;align-items: center;justify-content: center;}.dialog-modal-inn {background: #fff;max-width: 750px;padding: 50px;text-align: center;width: 100%;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);}.dialog-modal-inn h4 {font-weight: 400;margin: 0 0 25px;font-size: 25px;}.dialog-modal-inn .sure-btn button {font-size: 20px;padding: .5rem 3rem;color: #fff;background-color: #74cfd0;border: none;display: inline-block;text-decoration: none;outline: none;box-shadow: none;margin: 10px 0;}.dialog-modal-inn div#dialog {font-size: 17px;}.dialog-modal-inn p {font-size: 19px;}.dialog-modal-inn h3 {font-weight: 500;font-size: 22px;color: #f60000;}.sure-btn {margin: 50px 0 0;}.anymore-link {margin: 15px 0 0;}.anymore-link a {color: #74cfd0;font-size: 17px;}#page-wrapper { z-index: -1 !important;  } </style>');
									var script = "<script> function btn_yes_clockin_logout_hide() { document.getElementById('dialog-modal-clockin-logout').style.display = 'none';}<script>";
										d1.insertAdjacentHTML('afterend',script);
								}

							};

							xhttp.open("GET", teamnio_url+"/admin/sync_moodle_course/stop_clockin/?user_id="+user_id, false);

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
?>