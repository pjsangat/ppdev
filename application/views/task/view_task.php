<?php
  trace(__FILE__,'start');
  set_page_title(lang('view task'));
  project_tabbed_navigation('tasks');
  project_crumbs(array(
    array(lang('tasks'), get_url('task')),
    array($task_list->getName(), $task_list->getViewUrl()),
    array(lang('view task'))
  ));

  $options = array();
  if($task->canEdit(logged_user())) $options[] = '<a href="' . $task->getEditUrl() . '">' . lang('edit') . '</a>';
  if($task->canDelete(logged_user())) $options[] = '<a href="' . $task->getDeleteUrl() . '">' . lang('delete') . '</a>';
  if (plugin_active('time')) {
    if(ProjectTime::canAdd(logged_user(), active_project())) {
      $options[] = '<a href="' . get_url('time', 'add', array( 'task' => $task->getId() ) ) . '">' . lang('add time') . '</a>';
    }
  }
  if($task->canChangeStatus(logged_user())) {
    if ($task->isOpen()) {
      $options[] = '<a href="' . $task->getCompleteUrl() . '">' . lang('mark task as completed') . '</a>';
    } else {
      $options[] = '<a href="' . $task->getOpenUrl() . '">' . lang('open task') . '</a>';
    } // if
  } // if
?>

<div id="taskDetails" class="block">
  <div class="header"><?php echo (do_textile('[' .$task->getId() . '] ' . $task->getText())) ?></div>
  <div class="content">
    <div id="taskInfo">
<?php if (!is_null($task->getStartDate())) { ?>
<?php   if ($task->getStartDate()->getYear() > DateTimeValueLib::now()->getYear()) { ?>
      <div class="startDate"><span><?php echo lang('start date') ?>:</span> <?php echo format_date($task->getStartDate(), "Y-m-d H:i:s", 0) ?></div>
<?php   } else { ?>
      <div class="startDate"><span><?php echo lang('start date') ?>:</span> <?php echo format_descriptive_date($task->getStartDate(), 0, "Y-m-d H:i:s") ?></div>
<?php   } // if ?>
<?php }else{ // if ?>
	  <div class="startDate"><span><?php echo 'Start Date: ----------'; ?></span></div>
<?php }?>
<?php if (!is_null($task->getDueDate())) { ?>
<?php   if ($task->getDueDate()->getYear() > DateTimeValueLib::now()->getYear()) { ?>
      <div class="dueDate"><span><?php echo lang('due date') ?>:</span> <?php echo format_date($task->getDueDate(), "Y-m-d H:i:s", 0) ?></div>
<?php   } else { ?>
      <div class="dueDate"><span><?php echo lang('due date') ?>:</span> <?php echo format_descriptive_date($task->getDueDate(), 0, "Y-m-d H:i:s") ?></div>
<?php   } // if ?>
<?php }else{ // if ?>
	  <div class="dueDate"><span><?php echo 'Due Date: ----------'; ?></span></div>
<?php }?>

	  <?php if($task->getAssignedTo()) { 

		$sql = "select * from `".TABLE_PREFIX."contacts` where `user_id`='".$task->getAssignedToUserId()."'";
		$contact = DB::executeAll($sql);
		$contact = $contact[0];
		$cardurl = "index.php?c=contacts&a=card&active_project=".active_project()->getId()."&".time()."&id=".$contact['id'];
	  	?>
	    <div id="taskAssigned"><?php echo lang('milestone assigned to ') ?> <a href='<?php echo $cardurl; ?>'><?php echo clean($task->getAssignedTo()->getObjectName()) ?></a></div>
	  <?php } // if ?>

          <?php if($task->isCompleted()){


               ?>
                    <div id="taskAssigned">
                         <?php echo lang('milestone completed by ') ?>  <?php echo $task->getCompletedBy()->getDisplayName(); ?>
                         <br/>
                         <i><span style="font-size: 7pt;">Date Completed : <?php echo date("Y-m-d h:i:s", $task->getCompletedOn()->getTimestamp()); ?></span></i>
                    </div>
            <br/>
          <?php } ?>

	  <?php if(count($options)) { ?>
        <div id="taskOptions"><?php echo implode(' | ', $options) ?></div>
	   <?php } // if ?>
    </div>
  </div>
  <div class="clear"></div>
</div>

<?php echo render_object_comments($task, $task->getViewUrl()) ?>
