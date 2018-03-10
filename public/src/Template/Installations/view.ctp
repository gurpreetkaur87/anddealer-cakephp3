<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Installation'), ['action' => 'edit', $installation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Installation'), ['action' => 'delete', $installation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $installation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Installations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Installation'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="installations view large-9 medium-8 columns content">
    <h3><?= h($installation->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($installation->name) ?></td>
        </tr>
        <tr>
            <th><?= __('File Name') ?></th>
            <td><?= h($installation->file_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($installation->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($installation->created) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Comment') ?></h4>
        <?= $this->Text->autoParagraph(h($installation->comment)); ?>
    </div>
    <div class="row">
        <h4><?= __('Is New') ?></h4>
        <?= $this->Text->autoParagraph(h($installation->is_new)); ?>
    </div>
</div>
