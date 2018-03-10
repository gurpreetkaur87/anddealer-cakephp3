<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Instructions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentInstructions
 * @property \Cake\ORM\Association\HasMany $ChildInstructions
 *
 * @method \App\Model\Entity\Instruction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Instruction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Instruction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Instruction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Instruction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Instruction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Instruction findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InstructionsTable extends Table
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

        $this->table('instructions');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentInstructions', [
            'className' => 'Instructions',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildInstructions', [
            'className' => 'Instructions',
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('file_name', 'create')
            ->notEmpty('file_name');

        $validator
            ->requirePresence('is_new', 'create')
            ->notEmpty('is_new');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentInstructions'));

        return $rules;
    }
}
