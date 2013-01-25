<?php 

//email to db
include_once("class.emailtodb.php");		

//database connection configuration

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '5w0rdf15h');
define('DB_PREFIX', 'pp088_');

$db = array();
$db[] = "ppdev";
$db[] = "ppdev_ap";
$db[] = "ppdev_ke";

try{

          //database connection string
          $conn = mysql_connect(DB_HOST, DB_USER, DB_PASS);

          if(!$conn){
               echo "MySql Connection Failed";
               write_log("\nMySql Connection Failed. - ".date("Y-m-d H:i:s"));
               exit;
          }
          
          for($d = 0; $d < count($db); $d++){
               
               $sql = "select * from {$db[$d]}.".DB_PREFIX."projects where emailFetch = 0 limit 1";
               $rs = mysql_query($sql);

               $projects = formatRetArr($rs);
               
                if(empty($projects)){
                     
                    $sql = "update {$db[$d]}.".DB_PREFIX."projects set emailFetch = 0";
                     mysql_query($sql);

                     
                    $sql = "select * from ".DB_PREFIX."projects where emailFetch = 0 limit 1";
                    $rs = mysql_query($sql);
                    $projects = formatRetArr($rs);

               }

               if(!empty($projects)){
                    //loop on projects so that every email setup will be captured
                    for($ctr = 0; $ctr  < count($projects); $ctr++){

                         $sql = "select * from `{$db[$d]}`.`".DB_PREFIX."project_emailtickets` where `project_id`='".$projects[$ctr]['id']."'";
                         $rs = mysql_query($sql);

                         $config = formatRetArr($rs);

                         $config = $config[0];

                         if(!empty($config)){
                              $edb = new EMAIL_TO_DB();

                              $edb->file_path = dirname(__FILE__)."/../attachments/";
                              $edb->connect($config['email_server'], $config['email_port'], $config['email_login'], $config['email_password']);


                              if(count($edb->error) > 0){

                                   for($x = 0; $x < count($edb->error); $x++){
                                        write_log("\n{$edb->error[$x]}. - ".date("Y-m-d H:i:s"));
                                        echo "Imap Error ".$edb->error[$x]."<br/>";
                                   }

     //                              $sql = "update ".DB_PREFIX."projects set emailFetch = 0 where id = ".$projects[$ctr]['id'];
     //                              DB::executeAll($sql);
     //                              exit;
                              }

                              $edb->do_action();
                              $mailbox = md5($config['email_server'].$config['email_login'].$config['email_password']);


                                      //ticketize ticket
                                     $sql = "select `emailtodb_email`.* from `{$db[$d]}`.`emailtodb_email` where `Ticketized`='0' and `Mailbox`='".$mailbox."'";
                                     $rs = mysql_query($sql);
                                     $tickets = formatRetArr($rs);

                                     $t = count($tickets);
                                     $tcount = $t;

                                     for($i=0; $i<$t; $i++){

                                             $sql = "select * from `{$db[$d]}`.`emailtodb_attach` where `IDEmail` = '".$tickets[$i]['ID']."' ";
                                             $rs = mysql_query($sql);
                                             $attachments = formatRetArr($rs);
     //                                        $attachments = DB::executeAll($sql);

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

                                             $sql = "insert into `{$db[$d]}`.`".DB_PREFIX."project_tickets` set
                                             `summary` = '".$summary."',
                                             `type` = 'support',
                                             `description` = '".addslashes($description)."',
                                             `project_id` = '".$projects[$ctr]['id']."',
                                             `created_on` = NOW()
                                             ";
                                             mysql_query($sql);
     //					$ticket_id = DB::lastInsertId();
                                             $ticket_id = mysql_insert_id($conn);

     //                                        $sql = "select * from `".DB_PREFIX."project_tickets` where ";
     //
     //					$ticketdb = ProjectTickets::findById($ticket_id);

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

     //						$file = new ProjectFile();
     //						$file->setProjectId($projects[$ctr]['id']);
     //						$file->setFilename($uploaded_file['name']);
     //						$file->setIsVisible(true);
     //						$file->setExpirationTime($expiration_time);
     //						$file->save();
     //						$file->handleUploadedFile($uploaded_file); // initial version
     //						$result[] = $file;
     //						$ticketdb->attachFile($file);

                                                     /*
                                                     $filename = $ticket['ATTACHMENTS'][$i][]
                                                     $sql = "insert into `".DB_PREFIX."_project_files` set
                                                     `filename` = '".mysql_escape_string($filename)."',
                                                     `project_id` = '".$project_id."',
                                                     `created_on` = now(),
                                                     `updated_on` = now()
                                                     ";

                                                     $sql = "insert into `".DB_PREFIX."attached_files` set
                                                     `rel_object_manager`='ProjectTickets',
                                                     `rel_object_id` = '".$ticket_id."'
                                                     `file_id` = '".$file_id."'"
                                                     ;
                                                     */
                                             }

                                             $sql = "update `{$db[$d]}`.`emailtodb_email` set `Ticketized`='1' and `Mailbox`='".$mailbox."' where `ID`='".$ticket['ID']."'";
                                             DB::executeAll($sql);
                                     }


                                     $sql = "update `{$db[$d]}`.".DB_PREFIX."projects set emailFetch = 1 where id = ".$projects[$ctr]['id'];
                                     mysql_query($sql);

                                     if($tcount==1){
                                             echo $edb->status." - ".$tcount." ticket fetched.";
                                             write_log("\n".$edb->status." - ".$tcount." ticket fetched.".date("Y-m-d H:i:s"));
                                     }
                                     else{
                                             echo $edb->status." - ".$tcount." tickets fetched.";
                                             write_log("\n".$edb->status." - ".$tcount." ticket fetched.".date("Y-m-d H:i:s"));
                                     }



                              }else{
                                             echo $edb->status." - ".$tcount." tickets fetched.";
                                             write_log("\nNo Config on Project id {$projects[$ctr]['id']}.".date("Y-m-d H:i:s"));

                                     $sql = "update `{$db[$d]}`.".DB_PREFIX."projects set emailFetch = 1 where id = ".$projects[$ctr]['id'];
                                     mysql_query($sql);
                           }

               
                         }
                    }
               }
          }catch(Exception $e){
               echo $e;
               write_log("\n$e. -".date("Y-m-d H:i:s"));
               exit;
          }



          function p($arr = array()){
               print "<pre>";
               print_r($arr);
               print "</pre>";
          }

          function pe($arr = array()){
               p($arr);
               exit;
          }

          function formatRetArr($result){
               $arrResult = array();

               while($rows = mysql_fetch_assoc($result)){
                    $arrResult[] = $rows;
               }

               return $arrResult;
          }

          function write_log($log){
               if($fh  = @fopen("log.txt", "a+")){
                    fputs( $fh, $log, strlen($log) );
                    fclose( $fh );
                    return( true );
               }else{
                    return( false );
               }
          }

?>
