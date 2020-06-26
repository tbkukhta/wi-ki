<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Class User
 * @package app\models
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const STATUS_INACTIVE_TEXT = 'Заблокирован';
    const STATUS_ACTIVE_TEXT = 'Активен';

    const ROLE_ADMIN = 'admin';
    const ROLE_MODER = 'moder';
    const ROLE_USER = 'user';

    const ROLE_ADMIN_TEXT = 'Администратор';
    const ROLE_MODER_TEXT = 'Модератор';
    const ROLE_USER_TEXT = 'Пользователь';

    /**
     * Default password
     *
     * @const string
     */
    const DEFAULT_PASSWORD = '123456';

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],

            ['login', 'trim'],
            ['login', 'required'],
            ['login', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот логин уже используется.'],
            ['login', 'string', 'min' => 3, 'max' => 30],
            ['login', 'match', 'pattern' => '/^\w+$/', 'message' => 'Логин может содержать только латинские, цифровые символы и знак подчёркивания.'],

            [['first_name', 'last_name'], 'string'],
            [['first_name', 'last_name'], 'trim'],
            ['first_name', 'required'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Эта почта уже используется.'],

            ['role', 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Почта',
            'role' => 'Роль',
            'status' => 'Статус'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by login
     *
     * @param string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Creates user with default password
     *
     * @return User|bool
     * @throws Exception
     */
    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->generateAuthKey();
        $this->setPassword(self::DEFAULT_PASSWORD);
        return $this->save() ? $this : false;
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['author_id' => 'id']);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => self::STATUS_ACTIVE_TEXT,
            self::STATUS_INACTIVE => self::STATUS_INACTIVE_TEXT
        ];
    }

    public static function getRoles()
    {
        return [
            self::ROLE_USER => self::ROLE_USER_TEXT,
            self::ROLE_MODER => self::ROLE_MODER_TEXT,
            self::ROLE_ADMIN => self::ROLE_ADMIN_TEXT
        ];
    }
}