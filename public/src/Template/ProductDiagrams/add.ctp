<div class="row block pd-block add-page-block pd-add-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Product Diagrams'), ['action' => 'index']) ?></li>
            <li><?= __('Add') ?></li>
        </ul>
    </div>
    <div class="col-xs-12 add-form">
        <?= $this->Form->create($productDiagram, ['type' => 'file']) ?>
         
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentProductDiagrams]);
            echo $this->Form->input('name');
            echo $this->Form->input('file_name', ['type' => 'file']);
        ?>
          
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    </div>

</div>