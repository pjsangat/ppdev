<?php
  trace(__FILE__,'begin');
  set_page_title(lang('E-mail Tickets Config'));
  project_tabbed_navigation('email tickets');
  project_crumbs(array(
    array("email tickets", get_url('email tickets', 'index')),
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
<script>
function fetchEmails(){
	jQuery("#fetchbutton").attr("disabled", true);
	jQuery("#fetchbutton").val('Fetching E-mails...');
	jQuery.ajax({
		type: 'POST',
		url: "index.php?c=emailtickets&instance=<?php echo $_GET['instance']; ?>&a=index&active_project=<?php echo active_project()->getId(); ?>&ajax=1",
		data: jQuery('#formvars').serialize(),
		success: function(html){
			message = html;
			alert(message);
			/*
			$("#success").show();
			$("#success").html(message);
			setTimeout(function(){
				s = $("#success");
				if (s.css("display") != 'none'){
				  $("#success").fadeTo("slow", 0.25, function () {
					  $("#success").hide(1000);
					});
				}
			  }, 5000);
  			*/
			jQuery("#fetchbutton").val('Fetch E-mails - Done');
			jQuery("#fetchbutton").attr("disabled", false);
		},
	});
	
}
</script>
<form method='post' style='width:100%' id='formvars'>
<table>
<tr>
	<td>E-mail Server:<br>e.g. imap1.accessauthority.com</td>
	<td><input type='text' name='email_server' value="<?php echo htmlentities($config['email_server']) ?>" > </td>
</tr>
<tr>
	<td>E-mail Port:<br>e.g. <br> /imap:143/notls <br> :993/imap/ssl/novalidate-cert 
	</td>
	<td><input type='text' name='email_port' value="<?php echo htmlentities($config['email_port']) ?>" ></td>
</tr>
<tr>
	<td>E-mail Login:<br>e.g. tp@directopen.com</td>
	<td><input type='text' name='email_login' value="<?php echo htmlentities($config['email_login']) ?>" ></td>
</tr>
<tr>
	<td>E-mail Password:</td>
	<td><input type='password' name='email_password' value="<?php echo htmlentities($config['email_password']) ?>" ></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type='submit' value='Save'></td>
</tr>
<?php 
if($config['email_server']){
	?>
	<tr>
		<td colspan="2" align="center"><input type='submit' id='fetchbutton' onclick="fetchEmails(); return false;" value='Fetch E-mails'></td>
	</tr>
	<?php
}
?>
</table>
</form>