<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Restaurants Model
 *
 * @property \Cake\ORM\Association\HasMany $Categories
 * @property \Cake\ORM\Association\HasMany $Cousines
 * @property \Cake\ORM\Association\HasMany $Orders
 * @property \Cake\ORM\Association\BelongsToMany $Admins
 *
 * @method \App\Model\Entity\Restaurant get($primaryKey, $options = [])
 * @method \App\Model\Entity\Restaurant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Restaurant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Restaurant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Restaurant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Restaurant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Restaurant findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RestaurantsTable extends Table
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

        $this->table('restaurants');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Categories', [
            'foreignKey' => 'restaurant_id'
        ]);
        $this->hasMany('Cousines', [
            'foreignKey' => 'restaurant_id'
        ]);
        $this->hasMany('Orders', [
            'foreignKey' => 'restaurant_id'
        ]);
        $this->belongsToMany('Admins', [
            'foreignKey' => 'restaurant_id',
            'targetForeignKey' => 'admin_id',
            'joinTable' => 'admins_restaurants'
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
            ->allowEmpty('name_en');

        $validator
            ->allowEmpty('name_zh');

        $validator
            ->allowEmpty('address');

        $validator
            ->allowEmpty('mobile');

        return $validator;
    }
}