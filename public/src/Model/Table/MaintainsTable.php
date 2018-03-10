<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Maintains Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentMaintains
 * @property \Cake\ORM\Association\HasMany $ChildMaintains
 *
 * @method \App\Model\Entity\Maintain get($primaryKey, $options = [])
 * @method \App\Model\Entity\Maintain newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Maintain[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Maintain|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Maintain patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Maintain[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Maintain findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MaintainsTable extends Table
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

        $this->table('maintains');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentMaintains', [
            'className' => 'Maintains',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildMaintains', [
            'className' => 'Maintains',
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentMaintains'));

        return $rules;
    }
}
