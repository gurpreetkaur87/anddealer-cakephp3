<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $instruction->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $instruction->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Instructions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Parent Instructions'), ['controller' => 'Instructions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Instruction'), ['controller' => 'Instructions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="instructions form large-9 medium-8 columns content">
    <?= $this->Form->create($instruction, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit Instruction') ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentInstructions]);
            echo $this->Form->input('name');
            echo $this->Form->input('description');
			echo 'current PDF File: '.$instruction->file_name;
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
            //echo $this->Form->input('is_new');
			echo $this->Form->input('is_new', array(
                'options' => array('no'=>'no','yes'=>'yes'),
                'default' => $instruction->is_new));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
 -->
 <div class="row block maintain-block add-page-block maintain-add-page">
    <div class="col-xs-12" style="position: relative;">
    <ul class="breadcrumb">
        <li><?= $this->Html->link(__('Instruction Manuals'), ['action' => 'index']) ?></li>
        <li><a href="#" class="current"><?= h($instruction->name) ?></a></li>
    </ul>
            <div class="delete-button">
<?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $instruction->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $instruction->id)]
            )
        ?>
        </div>
    </div>
    <div class="col-xs-12 add-form">
    <?= $this->Form->create($instruction, ['type' => 'file']) ?>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentInstructions]);
            echo $this->Form->input('name');
            echo $this->Form->input('description');
            echo '<div class="input"><label>current PDF:</label>'.$instruction->file_name.'</div>';
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
            //echo $this->Form->input('is_new');
            echo $this->Form->input('is_new', array(
                'options' => array('no'=>'no','yes'=>'yes'),
                'default' => $instruction->is_new));
        ?>

        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>

</div>