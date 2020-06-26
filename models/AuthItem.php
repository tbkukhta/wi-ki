<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::class, 'targetAttribute' => ['rule_name' => 'name']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::class, ['name' => 'rule_name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }
}