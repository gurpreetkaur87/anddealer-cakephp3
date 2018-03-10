<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Form->input('type');
            echo $this->Form->input('username');
            echo $this->Form->input('password');
            echo $this->Form->input('email');
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('status');
            echo $this->Form->input('code');
            echo $this->Form->input('company');
            echo $this->Form->input('archived');
            echo $this->Form->input('archived_date');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>  -->


<div class="row block instruction-block add-page-block instruction-add-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('User'), ['action' => 'index']) ?></li>
            <li><?= __('Edit Users') ?></li>
        </ul>
        <div class="delete-button">
            <?= $this->Form->postLink(
                    __('Delete User'),
                    ['action' => 'delete', $user->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
                )
            ?>
        </div>
    </div>
    <div class="col-xs-12 add-form">
         <?= $this->Form->create($user) ?>
         
         <?php
           
            //echo $this->Form->input('type');
			echo $this->Form->input('company code', array('name' => 'code', 'value' => $user->code ));
            echo $this->Form->input('username');
            echo $this->Form->input('password', ['type' => 'text']);
            echo $this->Form->input('email');
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            //echo $this->Form->input('status');
			echo $this->Form->input('company');
			echo $this->Form->input('status', array(
                'options' => array('enabled','disabled'),
                'default' => $user->status
            ));
			echo $this->Form->input('admin', array(
                'options' => array('dealer'=>'no','admin'=>'yes'),
                'default' => $user->type,
				'name' => 'type'
            ));
            
            
            //echo $this->Form->input('archived');
            //echo $this->Form->input('archived_date');
        
        ?>
          
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    </div>

</div>