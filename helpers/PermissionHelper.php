<?php

namespace app\helpers;

use Yii;
use app\services\PermissionService;

class PermissionHelper
{
    /**
     * @param $permission
     * @return bool
     */
    public static function can($permission): bool
    {
        if (!Yii::$app->session->get(PermissionService::PERMISSIONS_ARRAY)[$permission]) {
            return false;
        }
        return true;
    }

    /**
     * @param $groupName
     * @return bool
     */
    public static function canGroup($groupName): bool
    {
        foreach (PermissionService::$groupPermissions[$groupName] as $permission) {
            if (!Yii::$app->session->get(PermissionService::PERMISSIONS_ARRAY)[$permission]) {
                return false;
            }
        }
        return true;
    }
}