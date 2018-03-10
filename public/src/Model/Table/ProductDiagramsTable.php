<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductDiagrams Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentProductDiagrams
 * @property \Cake\ORM\Association\HasMany $ChildProductDiagrams
 *
 * @method \App\Model\Entity\ProductDiagram get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductDiagram newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductDiagram[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductDiagram|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductDiagram patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductDiagram[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductDiagram findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductDiagramsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('product_diagrams');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentProductDiagrams', [
            'className' => 'ProductDiagrams',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildProductDiagrams', [
            'className' => 'ProductDiagrams',
            'foreignKey' => 'parent_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('file_name', 'create')
            ->notEmpty('file_name');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentProductDiagrams'));

        return $rules;
    }
}
