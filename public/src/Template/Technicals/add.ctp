<div class="row block tech-block add-page-block tech-add-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Technicals'), ['action' => 'index']) ?></li>
            <li><?= __('Add New') ?></li>
        </ul>
    </div>
    <div class="col-xs-12 add-form">
        <?= $this->Form->create($technical, ['type' => 'file']) ?>
         
                <?php
                    echo $this->Form->input('parent_id', ['options' => $parentTechnicals]);
                    echo $this->Form->input('name');
                    echo $this->Form->input('description');
                    echo $this->Form->input('file_name', ['type' => 'file']);
                    //echo $this->Form->input('is_new');
					echo $this->Form->input('is_new', array(
                'options' => array('no'=>'no','yes'=>'yes'),
                'default' => 'no'
            ));
                ?>
          
            <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>

</div>