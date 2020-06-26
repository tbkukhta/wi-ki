<?php

namespace app\commands;

use app\rbac\UserRoleRule;
use yii\console\Controller;

class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        /**
         * comment
         */
        $createComment = $auth->createPermission('createComment');
        $createComment->description = 'Создание комментария';
        $auth->add($createComment);

        $updateComment = $auth->createPermission('updateComment');
        $updateComment->description = 'Обновление комментария';
        $auth->add($updateComment);

        $deleteComment = $auth->createPermission('deleteComment');
        $deleteComment->description = 'Удаление комментария';
        $auth->add($deleteComment);

        /**
         * Project
         */
        $createProject = $auth->createPermission('createProject');
        $createProject->description = 'Создание проекта';
        $auth->add($createProject);

        $updateProject = $auth->createPermission('updateProject');
        $updateProject->description = 'Обновление проекта';
        $auth->add($updateProject);

        $deleteProject = $auth->createPermission('deleteProject');
        $deleteProject->description = 'Удаление проекта';
        $auth->add($deleteProject);

        /**
         * Topic
         */
        $createTopic = $auth->createPermission('createTopic');
        $createTopic->description = 'Создание топика';
        $auth->add($createTopic);

        $updateTopic = $auth->createPermission('updateTopic');
        $updateTopic->description = 'Обновление топика';
        $auth->add($updateTopic);

        $deleteTopic = $auth->createPermission('deleteTopic');
        $deleteTopic->description = 'Удаление топика';
        $auth->add($deleteTopic);

        /**
         * Topic item
         */
        $createTopicItem = $auth->createPermission('createTopicItem');
        $createTopicItem->description = 'Создание топик итема';
        $auth->add($createTopicItem);

        $updateTopicItem = $auth->createPermission('updateTopicItem');
        $updateTopicItem->description = 'Обновление топик итема';
        $auth->add($updateTopicItem);

        $deleteTopicItem = $auth->createPermission('deleteTopicItem');
        $deleteTopicItem->description = 'Удаление топик итема';
        $auth->add($deleteTopicItem);

        /**
         * user
         */
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Создание пользователя';
        $auth->add($createUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Обновление пользователя';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Удаление пользователя';
        $auth->add($deleteUser);

        $showUsers = $auth->createPermission('showUsers');
        $showUsers->description = 'Просмотр пользователей';
        $auth->add($showUsers);

        /**
         * tag
         */
        $createTag = $auth->createPermission('createTag');
        $createTag->description = 'Создание тегов';
        $auth->add($createTag);

        $updateTag = $auth->createPermission('updateTag');
        $updateTag->description = 'Редактирование тегов';
        $auth->add($updateTag);

        $deleteTag = $auth->createPermission('deleteTag');
        $deleteTag->description = 'Удаление тегов';
        $auth->add($deleteTag);

        $showTags = $auth->createPermission('showTags');
        $showTags->description = 'Просмотр тегов';
        $auth->add($showTags);

        /**
         * user role
         */
        $userRule = new UserRoleRule();
        $auth->add($userRule);

        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $user->ruleName = $userRule->name;
        $auth->add($user);

        /**
         * moder role
         */
        $moder = $auth->createRole('moder');
        $moder->description = 'Модератор';
        $moder->ruleName = $userRule->name;
        $auth->add($moder);
        $auth->addChild($moder, $user);
        $auth->addChild($moder, $createTag);
        $auth->addChild($moder, $updateTag);
        $auth->addChild($moder, $deleteTag);
        $auth->addChild($moder, $showTags);
        $auth->addChild($moder, $createProject);

        /**
         * admin role
         */
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $admin->ruleName = $userRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $moder);
        $auth->addChild($admin, $updateProject);
        $auth->addChild($admin, $deleteProject);
        $auth->addChild($admin, $createTopic);
        $auth->addChild($admin, $updateTopic);
        $auth->addChild($admin, $deleteTopic);
        $auth->addChild($admin, $createTopicItem);
        $auth->addChild($admin, $updateTopicItem);
        $auth->addChild($admin, $deleteTopicItem);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $showUsers);
        $auth->addChild($admin, $createComment);
        $auth->addChild($admin, $updateComment);
        $auth->addChild($admin, $deleteComment);
    }
}