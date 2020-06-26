<?php

namespace app\widgets;

use Yii;
use yii\widgets\Menu;

class MenuWrapper extends Menu
{
    protected function isItemActive($item)
    {
        if (isset($item['url'])) {
            $requestUrl = Yii::$app->request->url;
            if (preg_match('/topic-items$/', $requestUrl)) {
                $requestUrl = '/project/' . Yii::$app->request->get('slug') . '/topics';
            }
            if ($requestUrl === $item['url'] || $requestUrl === $item['url'] . '/update') {
                return true;
            }
        }
        return false;
    }
}