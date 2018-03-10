<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Banner'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="banners index large-9 medium-8 columns content">
    <h3><?= __('Banners') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('image') ?></th>
                <th><?= $this->Paginator->sort('link') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($banners as $banner): ?>
            <tr>
                <td><?= $this->Number->format($banner->id) ?></td>
                <td><?= h($banner->image) ?></td>
                <td><?= h($banner->link) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $banner->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $banner->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $banner->id], ['confirm' => __('Are you sure you want to delete # {0}?', $banner->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
 -->
<div class="row block instruction-block add-page-block instruction-add-page">

    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li class="heading"><?= __('Actions') ?></li>
            <li><?= $this->Html->link(__('New Banner'), ['action' => 'add']) ?></li>
        </ul>
    </div>
    <h3 class="col-xs-12"><?= __('Banners') ?></h3>
    <div class="col-xs-12 table dealer-table" style="text-align: center">
        <div class="table-header">
            <div class="table-cell"><?= $this->Paginator->sort('id') ?></div>
            <div class="table-cell"><?= $this->Paginator->sort('image') ?></div>  
            <div class="table-cell"><?= $this->Paginator->sort('link') ?></div>    
            <div class="table-cell"><?= __('Edit') ?></div>
            <!--div class="table-cell"><?= __('Detail') ?></div>       
            <div class="table-cell"><?= __('Delete') ?></div-->
        </div>
        <div class="table-body">
            <?php foreach ($banners as $banner): ?>
            <div class="table-row">
                <div class="table-cell"><?= $this->Number->format($banner->id) ?></div>
                <div class="table-cell"><?= h($banner->image) ?></div>        
                <div class="table-cell"><?= h($banner->link) ?></div>               
                <div class="table-cell">
                   <!--  <?= $this->Html->link(__('View'), ['action' => 'view', $banner->id]) ?> -->
                    <a class="img numbers" href="/banners/edit/<?= $this->Number->format($banner->id) ?>" >
                        <?php echo $this->Html->image('edit.png', ['alt' => 'edit']); ?>
                    </a>
                </div>
                <!--div class="table-cell">
                    <a class="img numbers" href="/banners/view/<?= $this->Number->format($banner->id) ?>" >
                       <?php echo $this->Html->image('view.png', ['alt' => 'Stock Enquiry']); ?>
                   </a>
                </div>
                <div class="table-cell last">
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $banner->id], ['confirm' => __('Are you sure you want to delete # {0}?', $banner->id)]) ?>
                </div-->
            </div>
            <?php endforeach; ?>
        </div>
    </div>
     <!--div class=" col-xs-12 paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div-->
</div>