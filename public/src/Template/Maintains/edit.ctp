<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $maintain->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $maintain->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Maintains'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Parent Maintains'), ['controller' => 'Maintains', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Maintain'), ['controller' => 'Maintains', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="maintains form large-9 medium-8 columns content">
    <?= $this->Form->create($maintain, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit Maintain') ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentMaintains]);
            echo $this->Form->input('name');
            echo $this->Form->input('description');
			echo 'current PDF File: '.$maintain->file_name;
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
            //echo $this->Form->input('is_new');
			echo $this->Form->input('is_new', array(
                'options' => array('no'=>'no','yes'=>'yes'),
                'default' => $maintain->is_new
            ));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
 -->
<div class="row block maintain-block add-page-block maintain-add-page">
    <div class="col-xs-12" style="position: relative;">
    <ul class="breadcrumb">
        <li><?= $this->Html->link(__('Maintenance Manual'), ['action' => 'index']) ?></li>
        <li><a href="#" class="current"><?= h($maintain->name) ?></a></li>
    </ul>
    <div class="delete-button">
<?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $maintain->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $maintain->id)]
            )
        ?>
        </div>
    </div>
    <div class="col-xs-12 add-form">
        <?= $this->Form->create($maintain, ['type' => 'file']) ?>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentMaintains]);
            echo $this->Form->input('name');
            echo $this->Form->input('description');
            echo '<div class="input"><label>Current PDF:</label>'.$maintain->file_name.'</div>';
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
            //echo $this->Form->input('is_new');
            echo $this->Form->input('is_new', array(
                'options' => array('no'=>'no','yes'=>'yes'),
                'default' => $maintain->is_new
            ));
        ?>

        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>

</div>