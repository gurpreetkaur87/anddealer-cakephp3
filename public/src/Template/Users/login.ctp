<?= $this->Form->create() ?>
	<h1 class="title login-title">Dealer Portal Login</h1>

	<div class="input-field">
		<label>Company Code:</label>
		<input type="text" name="company_code" class="login-input" />
	</div>
	<div class="input-field">
		<label>Username:</label>
		<input type="text" name="username" class="login-input" />
	</div>
	<div class="input-field">
		<label>Password:</label>
		<input type="password" name="password" class="login-input" />
	</div>
	<button type="sumbit" class="btn" id="login-button">Login</button>
<?php //$this->Form->button(__('Login')); ?>
<?= $this->Form->end() ?>
<?php echo $this->Flash->render(); ?>
<?php //echo $this->Flash->render('auth'); ?>