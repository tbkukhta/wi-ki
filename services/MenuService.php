<?php

namespace app\services;

use Yii;
use app\models\Topic;
use app\helpers\PermissionHelper;
use app\helpers\ArticleHelper;

class MenuService
{
    public $project;
    private $result;

    /**
     * MenuService constructor.
     *
     * @param $project
     */
    public function __construct($project) {
        $this->project = $project;
    }

    /**
     * Get menu items
     *
     * @return mixed
     */
    public function getItems(): array
    {
        $items = [[
            'label' => 'Общая информация',
            'url' => '/project/' . $this->project->slug,
            'template' => '<a href="{url}" class="sf-house execute-btn" title="{label}">{label}</a>'
        ]];

        foreach ($items as $item) {
            $this->result[] = $item;
        }

        $topics = Topic::find()
            ->with('topicItems')
            ->where(['project_id' => $this->project->id])
            ->all();

        if ($topics !== null) {
            foreach ($topics as $topic) {
                $this->result[$topic['id']] = [
                    'visible' => $topic->status,
                    'label' => $topic->name,
                    'options' => ['class' => 'cm-submenu' . ($this->hasActiveItem($topic) ? ' open' : '')],
                    'template' => '<a class="sf-address-book" title="{label}">{label}<span class="caret"></span></a>'
                ];
                $this->result[$topic['id']]['items'][] = [
                    'label' => 'Просмотр темы',
                    'url' => '/project/' . $this->project->slug . '/' . $topic->slug,
                    'template' => '<a href="{url}" class="tab-menu md-remove-red-eye execute-btn" title="{label}">{label}</a>'
                ];
                if (!empty($topic->topicItems)) {
                    foreach ($topic->topicItems as $topicItem) {
                        $this->result[$topic['id']]['items'][] = [
                            'visible' => $topicItem->status,
                            'label' => $topicItem->name,
                            'url' => '/project/' . $this->project->slug . '/' . $topic->slug . '/' . $topicItem->slug,
                            'template' => '<a href="{url}" class="tab-menu sf-address-book-alt execute-btn" title="{label}">{label}</a>'
                        ];
                    }
                }
                if (PermissionHelper::can('createTopicItem') || ArticleHelper::authorPermission($this->project->author_id)) {
                    $this->result[$topic['id']]['items'][] = [
                        'label' => 'Добавить раздел в тему',
                        'url' => '/project/' . $this->project->slug . '/' . $topic->slug . '/topic-item/create',
                        'template' => '<a href="{url}" class="tab-menu md-add-circle execute-btn" title="{label}">{label}</a>'
                    ];
                }
            }
        }

        if (PermissionHelper::can('createTopic') || ArticleHelper::authorPermission($this->project->author_id)) {
            $createTopic = [
                'label' => 'Добавить новую тему',
                'url' => '/project/' . $this->project->slug . '/topic/create',
                'template' => '<a href="{url}" class="sf-sign-add execute-btn" title="{label}">{label}</a>'
            ];

            $management = [
                'label' => 'Управление',
                'url' => '/project/' . $this->project->slug . '/topics',
                'template' => '<a href="{url}" class="sf-wrench execute-btn" title="{label}">{label}</a>'
            ];

            $items = [
                'createTopic' => $createTopic,
                'management' => $management
            ];

            foreach ($items as $item) {
                $this->result[] = $item;
            }
        }

        return $this->result;
    }

    /**
     * @param $topic
     * @return bool
     */
    private function hasActiveItem($topic)
    {
        $requestUrl = Yii::$app->request->url;
        $topicUrl = '/project/' . $this->project->slug . '/' . $topic->slug;

        if (
            $requestUrl === $topicUrl ||
            $requestUrl === $topicUrl . '/update' ||
            $requestUrl === $topicUrl . '/topic-item/create'
        ) {
            return true;
        }

        if (!empty($topic->topicItems)) {
            foreach ($topic->topicItems as $topicItem) {
                if (
                    $requestUrl === $topicUrl . '/' . $topicItem->slug ||
                    $requestUrl === $topicUrl . '/' . $topicItem->slug . '/update'
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}