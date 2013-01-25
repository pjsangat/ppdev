
        <form id="loginform" action="<?php echo get_url('access', 'login') ?>" method="post">
          <?php tpl_display(get_template_path('form_errors')) ?>

		<p class="loginwith">Login with your username and password below.</p>
		<label for="loginUsername"><?php echo lang('username') ?>:</label>
		<?php echo text_field('login[username]', array_var($login_data, 'username'), array('id' => 'loginUsername', 'class' => 'inputs', 'tabindex' => 1)) ?>
		<div class="clear"></div>
		<label for="loginPassword"><?php echo lang('password') ?>:</label>
		<?php echo password_field('login[password]', null, array('id' => 'loginPassword', 'class' => 'inputs', 'tabindex' => 2)) ?>
		<div class="clear"></div>
		<label class="checkbox" style="float:left" for="loginRememberMe"><?php echo lang('remember me', duration(config_option('remember_login_lifetime'))) ?></label>
        <?php echo checkbox_field('login[remember]', (array_var($login_data, 'remember') == 'checked'), array('id' => 'loginRememberMe')) ?>
		<div class="clear"></div>
        <a class="forgot-pass" href="<?php echo get_url('access', 'forgot_password') ?>"><?php echo lang('forgot password') ?></a>
		<button class="loginBtn-large" border="0">Send</button>

	</form>
