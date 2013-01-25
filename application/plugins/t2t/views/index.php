<?php
  trace(__FILE__,'begin');
  set_page_title(lang('Convert Ticket to Task'));
  project_tabbed_navigation('convert tickets');
  project_crumbs(array(
    array("Convert Ticket", get_url('t2t', 'index')),
    array(lang('index'))
  ));
  if (ProjectLink::canAdd(logged_user(), active_project())) {
      //add_page_action(lang('add link'), get_url('links', 'add_link'));
  } // if
  add_stylesheet_to_page('project/files.css');
  $counter = 0;

  add_stylesheet_to_page('project/tickets.css');

  $options_pagination = array('page' => '#PAGE#');


?>
<div id="tickets">
<?php if(isset($tickets) && is_array($tickets) && count($tickets)) { ?>
  <div id="messagesPaginationTop"><?php echo advanced_pagination($tickets_pagination, get_url('tickets', 'index', $options_pagination)) ?></div>


<?php
  //$this->assign('ticketsheader', lang('tickets'));
	
  $this->assign('tickets', $tickets);
  $this->includeTemplate(dirname(__FILE__)."/t2t_view_tickets.php");

?>
  <div id="messagesPaginationBottom"><?php echo advanced_pagination($tickets_pagination, get_url('tickets', 'index', $options_pagination)) ?></div>
<?php } else { ?>
<p><?php echo lang('no tickets in project') ?></p>
<?php } // if ?>
</div>