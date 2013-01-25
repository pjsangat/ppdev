<?php

  /**
  * Emailtickets Controller
  *
  * @http://www.activeingredient.com.au
  */
  class EmailticketsController extends ApplicationController {
  
    /**
    * Construct the EmailticketsController
    *
    * @access public
    * @param void
    * @return EmailticketsController
    */
    function __construct() {
      parent::__construct();
      prepare_company_website_controller($this, 'project_website');
    } // __construct
    
    /**
    * Show Emailtickets for project
    *
    * @access public
    * @param void
    * @return null
    */
    function index() {
		$project_id = active_project()->getId();
                
		if($project_id){
			if($_GET['ajax']){

				ob_start();
//
				$sql = "select * from `".TABLE_PREFIX."project_emailtickets` where `project_id`='".$project_id."'";
				$config = DB::executeAll($sql);
//                                $config['email_server'] = 'imap1.accessauthority.com';
//                                $config['email_port'] = '/imap:993/ssl/novalidate-cert';
//                                $config['email_login'] = 'project@directopen.com';
//                                $config['email_password'] = 'EM3CbNc2';
				$config = $config[0];

                                
				include_once(dirname(__FILE__)."/../library/class.emailtodb.php");		
				//include_once(dirname(__FILE__)."/../library/snoopy_curl/Snoopy.class.php");	
					
				$cfg["db_host"] = DB_HOST;
				$cfg["db_user"] = DB_USER;
				$cfg["db_pass"] = DB_PASS;
				$cfg["db_name"] = DB_NAME;

                                
				$mysql_pconnect = mysql_pconnect($cfg["db_host"], $cfg["db_user"], $cfg["db_pass"]);
				if(!$mysql_pconnect){echo "Connection Failed"; exit; }
				$db = mysql_select_db($cfg["db_name"], $mysql_pconnect);
				if(!$db){echo"DB Select Failed"; exit;}

//                                pass= EM3CbNc2;
//                                email = project@directopen.com;
				$edb = new EMAIL_TO_DB();
                                
				$edb->file_path = dirname(__FILE__)."/../attachments/";
				$edb->connect($config['email_server'], $config['email_port'], $config['email_login'], $config['email_password']);


				$edb->do_action();
				$mailbox = md5($config['email_server'].$config['email_login'].$config['email_password']);
				
				//ticketize ticket
				$sql = "select `emailtodb_email`.* from `emailtodb_email` where `Ticketized`='0' and `Mailbox`='".$mailbox."'";
				$tickets = DB::executeAll($sql);





				$t = count($tickets);
				$tcount = $t;
				for($i=0; $i<$t; $i++){
					$sql = "select * from `emailtodb_attach` where `IDEmail` = '".$tickets[$i]['ID']."' ";
					$attachments = DB::executeAll($sql);
					$tickets[$i]['ATTACHMENTS'] = $attachments;
					$ticket = $tickets[$i];
					$summary = $ticket['Subject'];
					$description = 
					"From email: ".$ticket['EmailFrom']."\n".
					"Email To: ".$ticket['EmailTo']."\n".
					"Email Date: ".$ticket['DateE']."\n\n\n".
					$ticket['Message'];
					 /*
						[EmailFrom] => test@nmgdev.com
						[EmailFromP] => No_name
						[EmailTo] => test@nmgdev.com
						[DateE] => 2012-09-18 03:11:39
						[DateDb] => 2012-09-18 03:11:50
						[DateRead] => 0000-00-00 00:00:00
						[DateRe] => 0000-00-00 00:00:00
						[Status] => 0
						[Type] => 0
						[Del] => 0
						[Subject] => message 3
						[Message] => message 3
						[Message_html] => 
					*/
	
					$sql = "insert into `".TABLE_PREFIX."project_tickets` set 
					`summary` = '".$summary."',
					`type` = 'support',
					`description` = '".$description."',
					`project_id` = '".$project_id."',
					`created_on` = NOW()
					";
					DB::executeAll($sql);
					$ticket_id = DB::lastInsertId();
					$ticketdb = ProjectTickets::findById($ticket_id);
					
					//print_r($ticketdb);
					//attachments
					//print_r($ticket['ATTACHMENTS']);
					/*
					[0] => Array
						(
							[ID] => 2
							[IDEmail] => 4
							[FileNameOrg] => april_o__neil_by_davidrapozaart-d3cux3t.jpg
							[Filename] => 2012_09/4april_o__neil_by_davidrapozaart-d3cux3t.jpg
						)
					*/
					$ta = count($ticket['ATTACHMENTS']);
					for($ia=0; $ia<$ta; $ia++){
						$afile = $ticket['ATTACHMENTS'][$ia];
						/*
						echo dirname(__FILE__)."/../attachments/".$afile['Filename']."\n";
						echo dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']);
						echo is_file(dirname(__FILE__)."/../attachments/".$afile['Filename']);
						*/
						copy(dirname(__FILE__)."/../attachments/".$afile['Filename'], 
						dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']));
						
						$result = array(); // we'll put all files here
						$expiration_time = DateTimeValueLib::now()->advance(1800, false);
						
						$uploaded_file['name'] = basename($afile['Filename']);
						$uploaded_file['size'] = filesize(dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']));
						$uploaded_file['type'] = mime_content_type (dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']));
						$uploaded_file['tmp_name'] = dirname(__FILE__)."/../attachments/".$afile['Filename'];
	
						$file = new ProjectFile();
						$file->setProjectId($project_id);
						$file->setFilename($uploaded_file['name']);
						$file->setIsVisible(true);
						$file->setExpirationTime($expiration_time);
						$file->save();
						$file->handleUploadedFile($uploaded_file); // initial version
						$result[] = $file;
						$ticketdb->attachFile($file);
		  
						/*
						$filename = $ticket['ATTACHMENTS'][$i][]
						$sql = "insert into `".TABLE_PREFIX."_project_files` set
						`filename` = '".mysql_escape_string($filename)."',
						`project_id` = '".$project_id."',
						`created_on` = now(),
						`updated_on` = now()
						";
						
						$sql = "insert into `".TABLE_PREFIX."attached_files` set 
						`rel_object_manager`='ProjectTickets',
						`rel_object_id` = '".$ticket_id."'
						`file_id` = '".$file_id."'"
						;
						*/
					}
					
					$sql = "update `emailtodb_email` set `Ticketized`='1' and `Mailbox`='".$mailbox."' where `ID`='".$ticket['ID']."'";
					DB::executeAll($sql);
				
				}
				ob_end_clean();
				if($tcount==1){
					echo $edb->status." - ".$tcount." ticket fetched.";
				}
				else{
					echo $edb->status." - ".$tcount." tickets fetched.";	
				}
				//echo "<pre>";
				//print_r($tickets);
				exit();
			}
			if($_POST){

				$sql = "select * from `".TABLE_PREFIX."project_emailtickets` where `project_id`='".$project_id."'";
				
				$config = DB::executeAll($sql);
				$config = $config[0];
				
				
				if(!$config['id']){
					$sql = "insert into `".TABLE_PREFIX."project_emailtickets` set 
						`email_server` = '".mysql_escape_string($_POST['email_server'])."',
						`email_port` = '".mysql_escape_string($_POST['email_port'])."',
						`email_login` = '".mysql_escape_string($_POST['email_login'])."',
						`email_password` = '".mysql_escape_string($_POST['email_password'])."',
						`project_id` = '".mysql_escape_string($project_id)."'
					";
					DB::executeAll($sql);
				}
				else{
					$sql = "update `".TABLE_PREFIX."project_emailtickets` set 
						`email_server` = '".mysql_escape_string($_POST['email_server'])."',
						`email_port` = '".mysql_escape_string($_POST['email_port'])."',
						`email_login` = '".mysql_escape_string($_POST['email_login'])."',
						`email_password` = '".mysql_escape_string($_POST['email_password'])."',
						`project_id` = '".mysql_escape_string($project_id)."'
						
						where `project_id`='".$project_id."'
					";
					DB::executeAll($sql);
				}
			}
			$sql = "select * from `".TABLE_PREFIX."project_emailtickets` where `project_id`='".$project_id."'";
			$config = DB::executeAll($sql);
			$config = $config[0];
			tpl_assign('config', $config);
			flash_success("E-mail Tickets Config Saved!");
		}
		else{
			tpl_assign('message', "Please select a project.");
		}
    } // index



    /**
    * Get Emailtickets for each project in an instance
    *
    * @access public
    * @param void
    * @return null
    */
    function fetch_email() {
          if(logged_user()->isAdministrator()){
          try{
               
               //email to db
               include_once(dirname(__FILE__)."/../library/class.emailtodb.php");

               //database connection configuration
               $cfg["db_host"] = DB_HOST;
               $cfg["db_user"] = DB_USER;
               $cfg["db_pass"] = DB_PASS;
               $cfg["db_name"] = DB_NAME;

               //database connection string
               $mysql_pconnect = mysql_pconnect($cfg["db_host"], $cfg["db_user"], $cfg["db_pass"]);

               //exit on error in connection
               if(!$mysql_pconnect){
                    echo "MySql Connection Failed";
                    $this->write_log("\nMySql Connection Failed. - ".date("Y-m-d H:i:s"));
                    exit;
                }
                //select the instance db
               $db = mysql_select_db($cfg["db_name"], $mysql_pconnect);

               //exit on error selecting db
               if(!$db){
                    echo "DB Select Failed";
                    $this->write_log("\nDB Select Failed. - ".date("Y-m-d H:i:s"));
                    exit;
               }

               //get all projects in an instance
               $sql = "select * from ".TABLE_PREFIX."projects where emailFetch = 0 limit 1";
               $projects = DB::executeAll($sql);
               if(empty($projects)){
                    $sql = "update ".TABLE_PREFIX."projects set emailFetch = 0";
                     DB::executeAll($sql);
                    $sql = "select * from ".TABLE_PREFIX."projects where emailFetch = 0 limit 1";
                    $projects = DB::executeAll($sql);
               }
               
               if(!empty($projects)){
                    //loop on projects so that every email setup will be captured
                    for($ctr = 0; $ctr  < count($projects); $ctr++){
				$sql = "select * from `".TABLE_PREFIX."project_emailtickets` where `project_id`='".$projects[$ctr]['id']."'";
				$config = DB::executeAll($sql);

                                $config = $config[0];
                                $edb = new EMAIL_TO_DB();

				$edb->file_path = dirname(__FILE__)."/../attachments/";
				$edb->connect($config['email_server'], $config['email_port']."adasd", $config['email_login'], $config['email_password']);
                                if(count($edb->error) > 0){
                                     for($x = 0; $x < count($edb->error); $x++){
                                        $this->write_log("\n{$edb->error[$x]}. - ".date("Y-m-d H:i:s"));
                                        echo "Imap Error ".$edb->error[$x]."<br/>";
                                     }
                                   $sql = "update ".TABLE_PREFIX."projects set emailFetch = 0 where id = ".$projects[$ctr]['id'];
                                    DB::executeAll($sql);
                                    exit;
                                }
				$edb->do_action();
				$mailbox = md5($config['email_server'].$config['email_login'].$config['email_password']);
                                
				//ticketize ticket
				$sql = "select `emailtodb_email`.* from `emailtodb_email` where `Ticketized`='0' and `Mailbox`='".$mailbox."'";
				$tickets = DB::executeAll($sql);

				$t = count($tickets);
				$tcount = $t;
				for($i=0; $i<$t; $i++){
					$sql = "select * from `emailtodb_attach` where `IDEmail` = '".$tickets[$i]['ID']."' ";
					$attachments = DB::executeAll($sql);
					$tickets[$i]['ATTACHMENTS'] = $attachments;
					$ticket = $tickets[$i];
					$summary = $ticket['Subject'];
					$description =
					"From email: ".$ticket['EmailFrom']."\n".
					"Email To: ".$ticket['EmailTo']."\n".
					"Email Date: ".$ticket['DateE']."\n\n\n".
					$ticket['Message'];
					 /*
						[EmailFrom] => test@nmgdev.com
						[EmailFromP] => No_name
						[EmailTo] => test@nmgdev.com
						[DateE] => 2012-09-18 03:11:39
						[DateDb] => 2012-09-18 03:11:50
						[DateRead] => 0000-00-00 00:00:00
						[DateRe] => 0000-00-00 00:00:00
						[Status] => 0
						[Type] => 0
						[Del] => 0
						[Subject] => message 3
						[Message] => message 3
						[Message_html] =>
					*/

					$sql = "insert into `".TABLE_PREFIX."project_tickets` set
					`summary` = '".$summary."',
					`type` = 'support',
					`description` = '".addslashes($description)."',
					`project_id` = '".$projects[$ctr]['id']."',
					`created_on` = NOW()
					";
					DB::executeAll($sql);
					$ticket_id = DB::lastInsertId();
					$ticketdb = ProjectTickets::findById($ticket_id);

					//print_r($ticketdb);
					//attachments
					//print_r($ticket['ATTACHMENTS']);
					/*
					[0] => Array
						(
							[ID] => 2
							[IDEmail] => 4
							[FileNameOrg] => april_o__neil_by_davidrapozaart-d3cux3t.jpg
							[Filename] => 2012_09/4april_o__neil_by_davidrapozaart-d3cux3t.jpg
						)
					*/
					$ta = count($ticket['ATTACHMENTS']);
					for($ia=0; $ia<$ta; $ia++){
						$afile = $ticket['ATTACHMENTS'][$ia];
						/*
						echo dirname(__FILE__)."/../attachments/".$afile['Filename']."\n";
						echo dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']);
						echo is_file(dirname(__FILE__)."/../attachments/".$afile['Filename']);
						*/
						copy(dirname(__FILE__)."/../attachments/".$afile['Filename'],
						dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']));

						$result = array(); // we'll put all files here
						$expiration_time = DateTimeValueLib::now()->advance(1800, false);

						$uploaded_file['name'] = basename($afile['Filename']);
						$uploaded_file['size'] = filesize(dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']));
						$uploaded_file['type'] = mime_content_type (dirname(__FILE__)."/../../../../tmp/".basename($afile['Filename']));
						$uploaded_file['tmp_name'] = dirname(__FILE__)."/../attachments/".$afile['Filename'];

						$file = new ProjectFile();
						$file->setProjectId($projects[$ctr]['id']);
						$file->setFilename($uploaded_file['name']);
						$file->setIsVisible(true);
						$file->setExpirationTime($expiration_time);
						$file->save();
						$file->handleUploadedFile($uploaded_file); // initial version
						$result[] = $file;
						$ticketdb->attachFile($file);

						/*
						$filename = $ticket['ATTACHMENTS'][$i][]
						$sql = "insert into `".TABLE_PREFIX."_project_files` set
						`filename` = '".mysql_escape_string($filename)."',
						`project_id` = '".$project_id."',
						`created_on` = now(),
						`updated_on` = now()
						";

						$sql = "insert into `".TABLE_PREFIX."attached_files` set
						`rel_object_manager`='ProjectTickets',
						`rel_object_id` = '".$ticket_id."'
						`file_id` = '".$file_id."'"
						;
						*/
					}

					$sql = "update `emailtodb_email` set `Ticketized`='1' and `Mailbox`='".$mailbox."' where `ID`='".$ticket['ID']."'";
					DB::executeAll($sql);
                                }

                    $sql = "update ".TABLE_PREFIX."projects set emailFetch = 1 where id = ".$projects[$ctr]['id'];
                     DB::executeAll($sql);
                    }

                     
               }
				if($tcount==1){
					echo $edb->status." - ".$tcount." ticket fetched.";
                                        $this->write_log("\n".$edb->status." - ".$tcount." ticket fetched.".date("Y-m-d H:i:s"));
				}
				else{
					echo $edb->status." - ".$tcount." tickets fetched.";
                                        $this->write_log("\n".$edb->status." - ".$tcount." ticket fetched.".date("Y-m-d H:i:s"));
				}
				//echo "<pre>";
				//print_r($tickets);
				exit();
          }catch(Exception $e){
               echo $e;
               $this->write_log("\n$e. -".date("Y-m-d H:i:s"));
               exit;
          }
         
          }else{
               echo "must be admin user to run this script";
               $this->write_log("\nmust be admin user to run this script. -".date("Y-m-d H:i:s"));
               exit;
          }
    } //fetch_email

     public function write_log($log){
          if($fh  = @fopen("public/log.txt", "a+")){
               fputs( $fh, $log, strlen($log) );
               fclose( $fh );
               return( true );
          }else{
               return( false );
          }
     }
    
    } // EmailticketsController

?>