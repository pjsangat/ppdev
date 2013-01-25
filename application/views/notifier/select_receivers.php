<?php

$assignedid = $task_data['assigned_to'];
$assignedid = explode(":", $assignedid);
if($assignedid[1]){
	$assignedid = $assignedid[1];
}
else{
	$assignedid = $assignedid[0];
}
?>
<fieldset id="emailNotification">
  <legend><?php echo lang('email notification') ?></legend>
  <p><?php echo lang('email notification desc') ?></p>
<?php foreach ($project->getCompanies() as $company) { ?>
<?php if (is_array($users = $company->getUsersOnProject($project)) && count($users)) { ?>
  <div class="companyDetails block">
    <div class="companyName header"><?php echo checkbox_field($post_data_name.'[notify_company_' . $company->getId() . ']', array_var($post_data, 'notify_company_' . $company->getId()), array('id' => 'notifyCompany' . $company->getId() )) ?> <label for="notifyCompany<?php echo $company->getId() ?>" class="checkbox"><?php echo clean($company->getName()) ?></label></div>
    <div class="companyMembers content">
      <?php
	  $t = count($users);
	  if($t){
		echo "<table style='width:100%'><tr><td>";	
		for($i=0; $i<$t; $i++){
			$user = $users[$i];
			echo "<div>".checkbox_field($post_data_name.'[notify_user_' . $user->getId() . ']', array_var($post_data, 'notify_user_' . $user->getId()), array('id' => 'notifyUser' . $user->getId() )) ?> <label for="notifyUser<?php echo $user->getId() ?>" class="checkbox"><?php echo clean($user->getDisplayName()) ?></label></div><?php
			if(($i+1)%5==0&&$i!=0)
				echo "</td><td>";
		}
		echo "</td></table>";
	  }
	  
	  /*
	  ?>
	  
	  <ul>
<?php foreach ($users as $user) { ?>
        <li><?php echo checkbox_field($post_data_name.'[notify_user_' . $user->getId() . ']', array_var($post_data, 'notify_user_' . $user->getId()), array('id' => 'notifyUser' . $user->getId() )) ?> <label for="notifyUser<?php echo $user->getId() ?>" class="checkbox"><?php echo clean($user->getDisplayName()) ?></label></li>
<?php } // foreach ?>
      </ul>
	  <?php */?>
    </div>
  </div>
<?php } // if ?>
<?php } // foreach ?>
</fieldset>
<script>
jQuery("#notifyUser<?php echo $assignedid; ?>").attr("checked", true);
</script>