<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $banner->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $banner->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Banners'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="banners form large-9 medium-8 columns content">
    <?= $this->Form->create($banner) ?>
    <fieldset>
        <legend><?= __('Edit Banner') ?></legend>
        <?php
            echo $this->Form->input('image');
            echo $this->Form->input('link');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div> -->
<div class="row block instruction-block add-page-block instruction-add-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Actions') ?></li>
            <li><?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $banner->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $banner->id)]
                )
            ?></li>
            <li><?= $this->Html->link(__('List Banners'), ['action' => 'index']) ?></li>
        </ul>
    </div>
    <div class="col-xs-12 add-form">
          <?= $this->Form->create($banner, ['type' => 'file']) ?>
         <label>Current Image:</label><img src="<?php echo DS.'webroot'.DS.'uploads'.DS.'banner'.DS.$banner->image; ?>" style="width: 200px;"/>
         <?php
            echo $this->Form->input('image', ['type' => 'file']);
            echo $this->Form->input('link');
 
        ?>
          
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    </div>

</div>