<?= $this->Flash->render() ?>
<div class="row block instruction-block add-page-block instruction-add-page">

    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li>Admin</li>
            <li><?= __('Dealer List') ?></li>
        </ul>
    </div>
    <p class="col-xs-12">This information is sorted alphabetically by the Code and then Username</p>
    <div class="col-xs-12 table dealer-table">
        <div class="table-header">
            <div class="table-cell"><?= $this->Paginator->sort('code') ?></div>
            <div class="table-cell"><?= $this->Paginator->sort('username') ?></div>
            <div class="table-cell"><?= $this->Paginator->sort('password') ?></div>
      
            <div class="table-cell"><?= $this->Paginator->sort('first_name') ?></div>
            <div class="table-cell"><?= $this->Paginator->sort('last_name') ?></div>
            <div class="table-cell"><?= $this->Paginator->sort('company') ?></div>
            
            <div class="table-cell"><?= __('Edit') ?></div>
            <div class="table-cell"><?= __('Detail') ?></div>
            
            <div class="table-cell"><?= __('Delete') ?></div>
        </div>
        <div class="table-body">
            <?php foreach ($users as $user): ?>
            <div class="table-row">
                <div class="table-cell"><?= h($user->code) ?></div>
                <div class="table-cell"><?= h($user->username) ?></div>
                <div class="table-cell"><?= h($user->password) ?></div>
  
        
                <div class="table-cell"><?= h($user->first_name) ?></div>
                <div class="table-cell"><?= h($user->last_name) ?></div>
                
                <div class="table-cell"><?= h($user->company) ?></div>
                
               
                <div class="table-cell">
                <a class="img numbers" href="/users/edit/<?= $this->Number->format($user->id) ?>" >
                    <?php echo $this->Html->image('edit.png', ['alt' => 'edit']); ?>
                </a>
                </div>
                 <div class="table-cell"><a class="img numbers" href="/users/view/<?= $this->Number->format($user->id) ?>" >
                    <?php echo $this->Html->image('view.png', ['alt' => 'Stock Enquiry']); ?>
                    </a>
                </div>
                <div class="table-cell last"><?= $this->Form->postLink(__('delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
     <div class=" col-xs-12 paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>