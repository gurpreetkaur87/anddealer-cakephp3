<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $installation->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $installation->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Installations'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="installations form large-9 medium-8 columns content">
    <?= $this->Form->create($installation) ?>
    <fieldset>
        <legend><?= __('Edit Installation') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('comment');
            echo $this->Form->input('file_name');
            echo $this->Form->input('is_new');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div> -->
<div class="row block maintain-block add-page-block maintain-add-page">
    <div class="col-xs-12" style="position: relative;">
    <ul class="breadcrumb">
        <li><?= $this->Html->link(__('Instruction Manuals'), ['action' => 'index']) ?></li>
        <li><a href="#" class="current"><?= h($installation->name) ?></a></li>
    </ul>

    </div>
    <div class="col-xs-12 add-form">
        <?= $this->Form->create($installation, ['type' => 'file']) ?>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('comment');
			echo 'current PDF File: '.$installation->file_name;
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
            //echo $this->Form->input('is_new');
			echo $this->Form->input('is_new', array(
                'options' => array('no'=>'no','yes'=>'yes'),
                'default' => $installation->is_new
            ));
        ?>

        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>

</div>