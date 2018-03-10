<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('First Name') ?></th>
            <td><?= h($user->first_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Name') ?></th>
            <td><?= h($user->last_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Code') ?></th>
            <td><?= h($user->code) ?></td>
        </tr>
        <tr>
            <th><?= __('Company') ?></th>
            <td><?= h($user->company) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Archived Date') ?></th>
            <td><?= h($user->archived_date) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Updated') ?></th>
            <td><?= h($user->updated) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Type') ?></h4>
        <?= $this->Text->autoParagraph(h($user->type)); ?>
    </div>
    <div class="row">
        <h4><?= __('Status') ?></h4>
        <?= $this->Text->autoParagraph(h($user->status)); ?>
    </div>
    <div class="row">
        <h4><?= __('Archived') ?></h4>
        <?= $this->Text->autoParagraph(h($user->archived)); ?>
    </div>
</div> -->
<div class="row block tech-block">
    <ul class="breadcrumb">
        <li><?= $this->Html->link(__('User'), ['action' => 'index']) ?></li>        
       <li><?= __('View Users') ?></li>
    </ul>
    <div class="button-set">
        <div class="add-button"><?= $this->Html->link(__('Add New Users'), ['action' => 'add']) ?></div>
        <div class="edit-button"><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?></div>
        <div class="list-button"><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </div>
    </div>
    <div class="row-set">
		<div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Company Code') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->code) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Username') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->username) ?></div>
        </div>
		<div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Password') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->password) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Email') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->email) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('First Name') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->first_name) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Last Name') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->last_name) ?></div>
        </div>
        
		 <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Company') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->company) ?></div>
        </div>
        <!--div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Id') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->id) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Archived Date') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->archived_date) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Created') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->created) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Updated') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->updated) ?></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Type') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->type) ?></div>
        </div-->
        <div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Status') ?></div>
            <div class="col-xs-12 col-md-6"> <?= $this->Text->autoParagraph(h($user->status)); ?></div>
        </div>
		<div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Admin') ?></div>
            <div class="col-xs-12 col-md-6"><?php if($user->type == 'admin'){ echo 'Yes'; }else{ echo 'No'; } ?></div>
        </div>
		
		<div class="row">
            <div class="col-xs-12 col-md-2"></div>
            <div class="col-xs-12 col-md-6">
				<button id="sendPassword_btn" name="send" onclick="sendPassword(<?php echo $user->id; ?>)" >Send password to dealer</button>
				<div id="sendPasswordResultPopup" class="alert" style="display:none; width: 100%;"></div>
			</div>
        </div>
        <!--div class="row">
            <div class="col-xs-12 col-md-2"><?= __('Archived') ?></div>
            <div class="col-xs-12 col-md-6"><?= h($user->archived) ?></div>
        </div-->
    </div>
</div>
<script>
	function sendPassword(id)
    {
        //var datastring = new FormData(jQuery("#quoteData")[0]);
        var datastring = 'id='+id;
        jQuery.ajax({
            url: "/users/sendPassword",
            type: "post",
            //data: form_data,
            data: datastring,
            dataType: 'json',
			beforeSend: function() {
				jQuery('#sendPasswordResultPopup').removeClass('alert-success');
				jQuery('#sendPasswordResultPopup').removeClass('alert-danger');
				jQuery('#sendPasswordResultPopup').html('Sending mail <img class="loader" src="/img/loading2.gif" style="width:20px;">');
				jQuery('#sendPasswordResultPopup').css('display','block');
				jQuery('#sendPassword_btn').prop('disabled', true);
			},
            success: function (results)
            {            	
				console.log(results);
				console.log(results.result.value);
				if(results.result.value == true)
				{
					jQuery('#sendPasswordResultPopup').addClass('alert-success');
					jQuery('#sendPasswordResultPopup').html('Mail has been successfully sent. Thanks!!');
					jQuery('#sendPassword_btn').prop('disabled', false);
				}
				else
				{
					jQuery('#sendPasswordResultPopup').addClass('alert-danger');
					jQuery('#sendPasswordResultPopup').html('Mail has not sent. Please try again!!');
					jQuery('#sendPassword_btn').prop('disabled', false);
				}
            }
        });  

        return false;
    }
</script>
