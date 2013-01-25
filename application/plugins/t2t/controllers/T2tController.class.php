<?php

  /**
  * T2t Controller
  *
  * @http://www.activeingredient.com.au
  */
  class T2tController extends ApplicationController {
  
    /**
    * Construct the T2tController
    *
    * @access public
    * @param void
    * @return T2tController
    */
    function __construct() {
      parent::__construct();
      prepare_company_website_controller($this, 'project_website');
    } // __construct
    
    /**
    * Show T2t for project
    *
    * @access public
    * @param void
    * @return null
    */
    function index() {
		$conditions = DB::prepareString('`project_id` = ?', array(active_project()->getId()));
		list($tickets, $pagination) = ProjectTickets::paginate(
		array(
		  'conditions' => $conditions,
		  'order' => $order
		),
		config_option('tickets_per_page', 25), 
		$page
		); 
		tpl_assign('open_task_lists', active_project()->getOpenTaskLists());
		tpl_assign('tickets', $tickets);
		/*

      trace(__FILE__,'index()');
      $this->addHelper('textile');
      $this->addHelper('files', 'files');
      $links = ProjectLinks::getAllProjectLinks(active_project());
      tpl_assign('current_folder', null);
      tpl_assign('order', null);
      tpl_assign('page', null);
      tpl_assign('links', $links);
      tpl_assign('folders', active_project()->getFolders());
      //tpl_assign('folder_tree', ProjectFolders::getProjectFolderTree(active_project())); 
      tpl_assign('folder_tree', array() ); 
      $this->setSidebar(get_template_path('index_sidebar', 'files'));  
	  */  
    } // index
	
    } // T2tController

?>