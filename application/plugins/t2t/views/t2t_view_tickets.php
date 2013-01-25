<script>
function convertT2t(id){
	tlid = document.getElementById("tl"+id).value;
	action = "index.php?c=task&a=add_task&"+(new Date()).getTime()+"&task_list_id="+tlid+"&active_project=<?php echo active_project()->getId(); ?>&back_to_list=1";
	fm = document.getElementById("fm"+id);
	fm.action = action;
	fm.submit();
}
</script>
<div class="block">
<?php if (isset($ticketsheader)) { echo "<div class=\"header\">$ticketsheader</div>"; } ?>
<div class=""><?php if (!isset($params) || !is_array($params)) {
  $params = array();
} // if ?>
  <table width="100%" cellpadding="2" border="0">
  <tr>
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'id' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'id', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'id', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("ticket") ?></a>
    </th>
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'summary' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'summary', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'summary', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("summary") ?></a>
    </th>
    
	<th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'type' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'type', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'type', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("type") ?></a>
    </th>
	<!--
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'category_id' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'category_id', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'category_id', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("category") ?></a>
    </th>
	-->
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'assigned_to_user_id' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'assigned_to_user_id', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'assigned_to_user_id', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("assigned to") ?></a>
    </th>
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'state' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'state', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'state', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("status") ?></a>
    </th>
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'priority' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'priority', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'priority', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("priority") ?></a>
    </th>
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'created_on' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'created_on', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'created_on', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("created on") ?></a>
    </th>
    <!--
	<th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'due_date' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'due_date', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'due_date', 'order' => 'ASC')));
        }
          ?>"><?php echo lang("due date") ?></a>
    </th>
    <th>
      <a href="<?php
        if (array_var($params, 'sort_by') == 'updated_on' && array_var($params, 'order') == 'ASC') {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'updated_on', 'order' =>'DESC')));
        } else {
          echo get_url('tickets', 'index', array_merge($params, array('sort_by' => 'updated_on', 'order' => 'ASC')));
        }
          ?>"><?php echo ucfirst(lang('updated on')) ?></a>
    </th>
	-->
	<th>
      Task List
    </th>
	<th>

    </th>
	
  </tr>
	<?php foreach ($tickets as $ticket) { 
	?>
	<tr class="<?php echo $ticket->getPriority(); ?>">
	<?php $ticket_description = strlen($ticket->getDescription()) > 200 ? clean(substr($ticket->getDescription(), 0, 200))."&hellip;" : clean($ticket->getDescription()); ?>
    <td><a href="<?php echo $ticket->getViewUrl() ?>" title="<?php echo $ticket_description ?>"><?php echo $ticket->getId() ?></a></td>
    <td><a href="<?php echo $ticket->getViewUrl() ?>" title="<?php echo $ticket_description ?>"><?php echo $ticket->getSummary() ?></a></td>
    <td><?php echo lang($ticket->getType()) ?></td>
    <!--
	<td>
		<?php
		if ($ticket->getCategory()) { ?>
			  <?php echo clean($ticket->getCategory()->getName()) ?><?php
		} // if{ ?>
    </td>
    -->
	<td>
		<?php if ($ticket->getAssignedTo()) { ?>
			  <?php echo "<a href=\"".$ticket->getAssignedTo()->getCardUrl()."\">".clean($ticket->getAssignedTo()->getObjectName())."</a>" ?>
		<?php } // if{ ?>
    </td>
    <td><?php echo lang($ticket->getStatus()); ?></td>
    <td><?php echo lang($ticket->getPriority()); ?></td>
    <td><?php echo $ticket->getCreatedOn()->format("Y-m-d"); ?></td>
    <!--
	<td><?php echo $ticket->hasDueDate() ? $ticket->getDueDate()->format("Y-m-d") : ''; ?></td>
    <td><?php echo $ticket->getUpdatedOn() ? $ticket->getUpdatedOn()->format("Y-m-d") : lang('n/a') ?></td>
	-->
	<?php
	$ticket_data = array(
	  'is_private' => $ticket->isPrivate(),
	  'summary' => $ticket->getSummary(),
	  'priority' => $ticket->getPriority(),
	  'state' => $ticket->getState(),
	  'type' => $ticket->getType(),
	  'category_id' => $ticket->getCategoryId(),
	  'assigned_to' => $ticket->getAssignedToCompanyId() . ':' . $ticket->getAssignedToUserId(),
	  'milestone_id' => $ticket->getMilestoneId(),
	); // array
	?>
	<td>
		<?php

		?>
		<select id='tl<?php echo $ticket->getId();?>'>
		<?php
			foreach($open_task_lists as $tl){
				?><option value="<?php echo $tl->getId(); ?>"><?php echo substr($tl->getName(), 0, 18)."..."; ?></option><?php
			}
		
		?>
		
		</select>
	</td>
	<td>
	<form method="post" id='fm<?php echo $ticket->getId();?>'>
	<input type="hidden" value="<?php echo htmlentities(array_var($ticket_data, 'summary'))." (converted from ticket id: [".$ticket->getId()."] )\n\n".htmlentities($ticket->getDescription()); ?>" name="task[text]]">
	<input type="hidden" value="" name="task_start_date">
	<input type="hidden" value="month short 9 13. 2012" name="task_due_date">
	<input type="hidden" value="<?php echo htmlentities(array_var($ticket_data, 'assigned_to')); ?>" name="task[assigned_to]">
	<input type="button" value="Convert" style='width:60px; padding:0px' onclick="convertT2t('<?php echo $ticket->getId();?>')">
	</form>
	</td>
  </tr>
   <?php
 }
 ?>
 </table></div></div>