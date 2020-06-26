<?php

namespace app\rbac;

use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;
use app\models\User;

class UserRoleRule extends Rule
{
    public $name = 'userRule';

    /**
     * @param int|string $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $role = Yii::$app->user->identity->role;

        if ($item->name === 'admin') {
            return $role == User::ROLE_ADMIN;
        } elseif ($item->name === 'moder') {
            return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER;
        } elseif ($item->name === 'user') {
            return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER || $role == User::ROLE_USER;
        }

        return false;
    }
}