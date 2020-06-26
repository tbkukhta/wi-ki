<?php

namespace app\models;

use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class ChangePasswordForm extends ActiveRecord
{
    public $_userId;
    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;

    public function __construct($userId, array $config = [])
    {
        if ($userId) {
            $this->_userId = $userId;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'confirmNewPassword'], 'required'],
            ['oldPassword', 'validateOldPassword'],
            [['newPassword', 'confirmNewPassword'], 'string', 'min' => 6],
            ['newPassword', 'compare', 'compareAttribute' => 'oldPassword', 'operator' => '!=', 'message' => 'Новый пароль должен отличаться от старого.'],
            ['confirmNewPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли различаются.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'confirmNewPassword' => 'Ещё раз новый пароль'
        ];
    }

    public function validateOldPassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError('oldPassword', 'Старый пароль введён неверно.');
            }
        }
    }

    /**
     * @return User|null|IdentityInterface
     */
    public function getUser()
    {
        return User::findIdentity($this->_userId);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function savePassword()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->newPassword);
            return $user->save(false);
        }
        return false;
    }
}