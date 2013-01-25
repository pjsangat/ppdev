<?php
  
  /**
  * All functions here are in the global scope so keep names unique by using
  *   the following pattern:
  *
  *   <name_of_plugin>_<pp_function_name>
  *   i.e. for the hook in 'add_dashboard_tab' use 'emailtickets_add_dashboard_tab'
  */
  
  // add project tab
  
  
  add_action('administration_dropdown','emailtickets_add_project_tab');
  function emailtickets_add_project_tab() {
    echo '<li class="header"><a href="'.get_url('emailtickets', 'index').'">fetch tickets from email</a></li>';
  }
  /*
  add_action('add_project_tab', 'emailtickets_add_project_tab');
  function emailtickets_add_project_tab() {
    add_tabbed_navigation_item(
      'email tickets',
      'email tickets',
      get_url('emailtickets', 'index')
    );
  }
  */
  

  
  /**
  * If you need an activation routine run from the admin panel
  *   use the following pattern for the function:
  *
  *   <name_of_plugin>_activate
  *
  *  This is good for creation of database tables etc.
  */
  function emailtickets_activate() {
  	
    $sql = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."project_emailtickets` (
        `id` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`project_id` INT( 2 ) NOT NULL ,
		`email_server` VARCHAR( 128 ) NOT NULL ,
		`email_port` VARCHAR( 128 ) NOT NULL ,
		`email_login` VARCHAR( 128 ) NOT NULL ,
		`email_password` VARCHAR( 128 ) NOT NULL
      );";
    // create table
    DB::execute($sql);
	

	
	$sql = "CREATE TABLE IF NOT EXISTS `emailtodb_attach` (
	  `ID` int(11) NOT NULL auto_increment,
	  `IDEmail` int(11) NOT NULL default '0',
	  `FileNameOrg` varchar(255) NOT NULL default '',
	  `Filename` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`ID`),
	  KEY `IDEmail` (`IDEmail`)
	) ENGINE=MyISAM;";
	DB::execute($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `emailtodb_dir` (
	  `IDdir` int(11) NOT NULL auto_increment,
	  `IDsubdir` int(11) NOT NULL default '0',
	  `Sort` int(11) NOT NULL default '0',
	  `Name` varchar(25) NOT NULL default '',
	  `Status` tinyint(3) NOT NULL default '0',
	  `CatchMail` varchar(150) NOT NULL default '',
	  `Icon` varchar(250) NOT NULL default '',
	  PRIMARY KEY  (`IDdir`),
	  KEY `IDsubdir` (`IDsubdir`)
	) ENGINE=MyISAM;";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_dir` VALUES (1, 0, 0, 'Spam', 1, '', '');";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_dir` VALUES (2, 0, 1, 'Trash', 1, '', '');";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_dir` VALUES (3, 0, 2, 'Orders', 1, '', '');";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_dir` VALUES (4, 0, 3, 'Personal', 1, '', '');";
	DB::execute($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `emailtodb_email` (
	  `ID` int(11) NOT NULL auto_increment,
	  `Ticketized` tinyint(1) NOT NULL default '0',
	  `Mailbox` varchar(150) NOT NULL default '',
	  `IDEmail` varchar(255) NOT NULL default '0',
	  `EmailFrom` varchar(255) NOT NULL default '',
	  `EmailFromP` varchar(255) NOT NULL default '',
	  `EmailTo` varchar(255) NOT NULL default '',
	  `DateE` datetime NOT NULL default '0000-00-00 00:00:00',
	  `DateDb` datetime NOT NULL default '0000-00-00 00:00:00',
	  `DateRead` datetime NOT NULL default '0000-00-00 00:00:00',
	  `DateRe` datetime NOT NULL default '0000-00-00 00:00:00',
	  `Status` tinyint(3) NOT NULL default '0',
	  `Type` tinyint(3) NOT NULL default '0',
	  `Del` tinyint(3) NOT NULL default '0',
	  `Subject` varchar(255) default NULL,
	  `Message` text NOT NULL,
	  `Message_html` text NOT NULL,
	  `MsgSize` int(11) NOT NULL default '0',
	  `Kind` tinyint(2) NOT NULL default '0',
	  `IDre` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`ID`),
	  KEY `IDEmail` (`IDEmail`),
	  KEY `EmailFrom` (`EmailFrom`)
	) ENGINE=MyISAM;";
	DB::execute($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `emailtodb_list` (
	  `IDlist` int(11) NOT NULL auto_increment,
	  `Email` varchar(255) NOT NULL default '',
	  `Type` char(2) NOT NULL default 'B',
	  PRIMARY KEY  (`IDlist`),
	  KEY `Email` (`Email`)
	) ENGINE=MyISAM;";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_list` VALUES (1, 'spam@spamserver.com', 'B');";
	DB::execute($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `emailtodb_log` (
	  
	  `IDlog` int(11) NOT NULL auto_increment,
	  `IDemail` int(11) NOT NULL default '0',
	  `Email` varchar(150) NOT NULL default '',
	  `Info` varchar(255) NOT NULL default '',
	  `FSize` int(11) NOT NULL default '0',
	  `Date_start` datetime NOT NULL default '0000-00-00 00:00:00',
	  `Date_finish` datetime NOT NULL default '0000-00-00 00:00:00',
	  `Status` int(3) NOT NULL default '0',
	  `Dif` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`IDlog`)
	) ENGINE=MyISAM;";
	DB::execute($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `emailtodb_words` (
	  `IDw` int(11) NOT NULL auto_increment,
	  `Word` varchar(100) NOT NULL default '',
	  PRIMARY KEY  (`IDw`),
	  KEY `Word` (`Word`)
	) ENGINE=MyISAM;";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_words` VALUES (1, 'viagvra');";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_words` VALUES (2, 'rjolex');";
	DB::execute($sql);
	
	$sql = "INSERT INTO `emailtodb_words` VALUES (3, 'viajagra');";
	DB::execute($sql);

    //add_permission('emailtickets', PermissionManager::CAN_ACCESS);
    //add_permission('emailtickets', PermissionManager::CAN_ADD);  // = add/edit
    //add_permission('emailtickets', PermissionManager::CAN_DELETE);
    //add_permission('emailtickets', PermissionManager::CAN_VIEW);
  }

  /**
  * If you need an de-activation routine run from the admin panel
  *   use the following pattern for the function:
  *
  *   <name_of_plugin>_deactivate
  *
  *  This is good for deletion of database tables etc.
  */
  function emailtickets_deactivate($purge=false) {
    // sample drop table
    if ($purge) {

      DB::execute("DROP TABLE IF EXISTS `".TABLE_PREFIX."project_emailtickets`");
	  DB::execute("DROP TABLE IF EXISTS `emailtodb_attach`");
	  DB::execute("DROP TABLE IF EXISTS `emailtodb_dir`");
	  DB::execute("DROP TABLE IF EXISTS `emailtodb_email`");
	  DB::execute("DROP TABLE IF EXISTS `emailtodb_list`");
	  DB::execute("DROP TABLE IF EXISTS `emailtodb_log`");
	  DB::execute("DROP TABLE IF EXISTS `emailtodb_words`");

	  // permissions not implemented yet for emailtickets 
      //remove_permission_source('emailtickets');
      // TODO: Remove any logo files
    }
  }
?>