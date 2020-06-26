<?php

namespace app\services;

use Exception;
use Yii;

class PermissionService
{
    /**
     * Text for permission denied to action
     *
     * @var string
     */
    const PERMISSION_DENIED = 'У вас недостаточно прав для данного действия.';

    /**
     * Permissions array name (session)
     *
     * @const string
     */
    const PERMISSIONS_ARRAY = 'userPermissions';

    /**
     * User permissions
     *
     * @var array
     */
    private static $permissions = [
        'createProject', 'updateProject', 'deleteProject',
        'createTopic', 'updateTopic', 'deleteTopic',
        'createTopicItem', 'updateTopicItem', 'deleteTopicItem',
        'createComment', 'updateComment', 'deleteComment',
        'createUser', 'updateUser', 'deleteUser', 'showUsers',
        'createTag', 'updateTag', 'deleteTag', 'showTags'
    ];

    /**
     * User permissions by group
     *
     * @var array
     */
    public static $groupPermissions = [
        'project' => [
            'createProject', 'updateProject', 'deleteProject'
        ],
        'topic' => [
            'createTopic', 'updateTopic', 'deleteTopic'
        ],
        'topic_item' => [
            'createTopicItem', 'updateTopicItem', 'deleteTopicItem'
        ],
        'comment' => [
            'createComment', 'updateComment', 'deleteComment'
        ],
        'user' => [
            'createUser', 'updateUser', 'deleteUser', 'showUsers'
        ],
        'tag' => [
            'createTag', 'updateTag', 'deleteTag', 'showTags'
        ],
    ];

    /**
     * Run permissions service
     *
     * @return void
     */
    public static function execute(): void
    {
        foreach (self::$permissions as $permission) {
            self::setPermission($permission);
        }
    }

    /**
     * Set user permissions
     *
     * @param $permission
     * @return void
     */
    private static function setPermission($permission): void
    {
        $_SESSION[self::PERMISSIONS_ARRAY][$permission] = Yii::$app->user->can($permission) ? 1 : 0;
    }

    /**
     * @param $permission
     * @throws Exception
     */
    public static function can($permission): void
    {
        if (!Yii::$app->session->get(self::PERMISSIONS_ARRAY)[$permission]) {
            throw new Exception(self::PERMISSION_DENIED);
        }
    }

    /**
     * @param $groupName
     * @return void
     * @throws Exception
     */
    public static function canGroup($groupName): void
    {
        foreach (self::$groupPermissions[$groupName] as $permission) {
            if (!Yii::$app->session->get(self::PERMISSIONS_ARRAY)[$permission]) {
                throw new Exception(self::PERMISSION_DENIED);
            }
        }
    }
}