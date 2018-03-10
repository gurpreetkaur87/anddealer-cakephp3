<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Banner'), ['action' => 'edit', $banner->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Banner'), ['action' => 'delete', $banner->id], ['confirm' => __('Are you sure you want to delete # {0}?', $banner->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Banners'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Banner'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="banners view large-9 medium-8 columns content">
    <h3><?= h($banner->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Image') ?></th>
            <td><?= h($banner->image) ?></td>
        </tr>
        <tr>
            <th><?= __('Link') ?></th>
            <td><?= h($banner->link) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($banner->id) ?></td>
        </tr>
    </table>
</div> -->
<div class="row block tech-block" >
    <div class="col-xs-12" style="position: relative">
        <ul class="breadcrumb">
            <li><?= $this->Html->link(__('Banner'), ['action' => 'index']) ?></li>
            <li><a href="#">View</a></li>
        </ul>
        
            <div class="add-button"><?= $this->Html->link(__('New Banner'), ['action' => 'add']) ?></div>
         
    </div>
    <div class="table install-table">
        <div class="table-header install-table-header">
            <div class="table-cell install-table-cell"><?= __('Image') ?></div>
            <div class="table-cell install-table-cell"><?= __('Link') ?></div>

        </div>
        <div class="table-body install-table-body">
      
            <div class="table-row install-table-row">
                
                
                
                <div class="table-cell install-table-cell">
                    
                    <?= h($banner->image) ?>
                </div>
                <div class="table-cell install-table-cell">
                    
                   <?= h($banner->link) ?>
                </div>
        </div>
    </div>

</div>
