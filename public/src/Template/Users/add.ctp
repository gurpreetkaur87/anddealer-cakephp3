<?= $this->Flash->render() ?>
<div class="row block instruction-block add-page-block instruction-add-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('User'), ['action' => 'index']) ?></li>
            <li><?= __('Add New') ?></li>
        </ul>
    </div>
    <div class="col-xs-12 add-form">
         <?= $this->Form->create($user) ?>
         
         <?php
            //echo $this->Form->input('type');
			echo $this->Form->input('company code', array('name' => 'code'));
            echo $this->Form->input('username');
            echo $this->Form->input('password', ['type' => 'text', 'value' => rand(1000, 9999)]);
            echo $this->Form->input('email');
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            //echo $this->Form->input('status');
			echo $this->Form->input('company');
            echo $this->Form->input('status', array(
                'options' => array('enabled','disabled'),
                'default' => 'enabled'
            ));
			echo $this->Form->input('admin', array(
                'options' => array('dealer'=>'no','admin'=>'yes'),
                'default' => 'dealer',
				'name' => 'type'
            ));
?>
            
			<!--input type="checkbox"-->
        <div class="input-checkbox-part">
		<?php
            echo $this->Form->input(' ', array('type' => 'checkbox', 'name' => 'send_mail'));
		    echo '<span>Send password to dealer</span>';
            //echo $this->Form->input('archived');
            //echo $this->Form->input('archived_date');
        ?>
        </div>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    </div>

</div>