<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag".
 *
 * @property int $name
 */
class Tag extends ActiveRecord
{
    public $checked;

    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'min' => 3, 'max' => 30],
            ['name', 'required'],
            ['name', 'unique', 'message' => 'Такой тег уже существует!'],
            ['name', 'trim']
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['id' => 'comment_id'])
            ->viaTable('comment_tag', ['tag_id' => 'id']);
    }
}