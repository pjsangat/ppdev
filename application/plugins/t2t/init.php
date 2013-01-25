<?php
  
  /**
  * All functions here are in the global scope so keep names unique by using
  *   the following pattern:
  *
  *   <name_of_plugin>_<pp_function_name>
  *   i.e. for the hook in 'add_dashboard_tab' use 't2t_add_dashboard_tab'
  */
  
  // add project tab
  add_action('add_project_tab', 't2t_add_project_tab');
  function t2t_add_project_tab() {
    add_tabbed_navigation_item(
      'convert tickets',
      'convert tickets',
      get_url('t2t', 'index')
    );
  }
  
  // overview page
  add_action('project_overview_page_actions','t2t_project_overview_page_actions');
  function t2t_project_overview_page_actions() {
    if (ProjectLink::canAdd(logged_user(), active_project())) {
      add_page_action(lang('add link'), get_url('t2t', 'add_link'));
    } // if
  }

  // my tasks dropdown
  add_action('my_tasks_dropdown','t2t_my_tasks_dropdown');
  function t2t_my_tasks_dropdown() {
    echo '<li class="header"><a href="'.get_url('t2t', 'index').'">'.lang('t2t').'</a></li>';
    if (ProjectLink::canAdd(logged_user(), active_project())) { 
      echo '<li><a href="'.get_url('t2t', 'add_link').'">'.lang('add link').'</a></li>';
    } // if 
  }
  
  /**
  * If you need an activation routine run from the admin panel
  *   use the following pattern for the function:
  *
  *   <name_of_plugin>_activate
  *
  *  This is good for creation of database tables etc.
  */
  function t2t_activate() {
  	/*
    $sql = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."project_t2t` (
        `id` int(10) unsigned NOT NULL auto_increment,
        `project_id` int(10) unsigned NOT NULL default '0',
        `folder_id` INT( 10 ) NOT NULL DEFAULT 0,
        `title` varchar(50) NOT NULL default '',
        `url` text,
        `description` TEXT DEFAULT '',
        `logo_file` VARCHAR( 50 ) DEFAULT '',
        `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
        `created_by_id` int(10) unsigned default NULL,
        PRIMARY KEY  (`id`),
        KEY `created_on` (`created_on`),
        KEY `project_id` (`project_id`)
      );";
    // create table
    DB::execute($sql);
	*/
    //add_permission('t2t', PermissionManager::CAN_ACCESS);
    //add_permission('t2t', PermissionManager::CAN_ADD);  // = add/edit
    //add_permission('t2t', PermissionManager::CAN_DELETE);
    //add_permission('t2t', PermissionManager::CAN_VIEW);
  }

  /**
  * If you need an de-activation routine run from the admin panel
  *   use the following pattern for the function:
  *
  *   <name_of_plugin>_deactivate
  *
  *  This is good for deletion of database tables etc.
  */
  function t2t_deactivate($purge=false) {
    // sample drop table
    if ($purge) {

      DB::execute("DROP TABLE IF EXISTS `".TABLE_PREFIX."project_t2t`");

	  // permissions not implemented yet for t2t 
      //remove_permission_source('t2t');
      // TODO: Remove any logo files
    }
  }
?>