<?php 

  // Set page title and set crumbs to index
  set_page_title(lang('add ticket'));
  project_tabbed_navigation(PROJECT_TAB_TICKETS);
  project_crumbs(array(
    array(lang('tickets'), get_url('tickets')),
    array(lang('add ticket'))
  ));
  
  add_stylesheet_to_page('project/tickets.css');
?>
hello