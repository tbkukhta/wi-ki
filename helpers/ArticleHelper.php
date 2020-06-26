<?php

namespace app\helpers;

use Yii;
use yii\db\Exception;
use app\models\Project;
use app\models\Topic;
use app\models\TopicItem;
use app\models\User;
use app\models\Tag;
use app\models\Comment;

class ArticleHelper
{
    /**
     * @param $name
     * @param null $id
     * @param null $url
     * @return Project|Topic|TopicItem|null
     * @throws Exception
     */
    public static function getArticle($name, $id = null, $url = null)
    {
        switch ($name) {
            case 'project':
                return Project::findModel($id, $url);
            case 'topic':
                return Topic::findModel($id, $url);
            case 'topic_item':
                return TopicItem::findModel($id, $url);
        }

        throw new Exception('Article not found.');
    }

    /**
     * @param $authorId
     * @return bool
     */
    public static function authorPermission($authorId)
    {
        $isAuthor = $authorId == Yii::$app->user->identity->id;
        $isUser = Yii::$app->user->identity->role === 'user';
        return $isAuthor && !$isUser;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public static function getComments(array $params)
    {
        $articleModel = $params['article_model'];
        $articleId = self::getArticle($articleModel, null, $params['article_url'])->id;

        if (
            isset($params['date_from']) &&
            isset($params['date_to']) &&
            $params['date_from'] !== '' &&
            $params['date_to'] !== ''
        ) {
            $dateFrom = self::convertDate($params['date_from']);
            $dateTo = self::convertDate($params['date_to']);

            if ($dateFrom === $dateTo) {
                $dateTo = $dateFrom + 60 * 60 * 24;
            }

            $comments = Comment::find()
                ->where(['>=', 'updated_at', $dateFrom])
                ->andWhere([$articleModel . '_id' => $articleId])
                ->andWhere(['tab' => $params['article_tab']])
                ->andWhere(['<', 'updated_at', $dateTo])
                ->orderBy('updated_at DESC')
                ->all();
        } elseif (isset($params['author_id']) && $params['author_id'] !== '0') {
            $author = User::findOne(['id' => $params['author_id']]);

            $comments = $author->getComments()
                ->where([$articleModel . '_id' => $articleId])
                ->andWhere(['tab' => $params['article_tab']])
                ->orderBy('updated_at DESC')
                ->all();
        } elseif (isset($params['tag_id']) && $params['tag_id'] !== '0') {
            $tag = Tag::findOne(['id' => $params['tag_id']]);

            $comments = $tag->getComments()
                ->where([$articleModel . '_id' => $articleId])
                ->andWhere(['tab' => $params['article_tab']])
                ->orderBy('updated_at DESC')
                ->all();
        } else {
            $comments = Comment::find()
                ->where([$articleModel . '_id' => $articleId])
                ->andWhere(['tab' => $params['article_tab']])
                ->orderBy('updated_at DESC')
                ->all();
        }

        foreach ($comments as $comment) {
            $comment->updateData();
        }

        return $comments;
    }

    /**
     * @param $date
     * @return array|false|int|string
     */
    private static function convertDate($date)
    {
        $date = explode('-', $date);
        $date = array_reverse($date);
        $date[1] = self::translateMonth($date[1]);
        $date = implode('-', $date);
        $date = strtotime($date);

        return $date;
    }

    /**
     * @param $str
     * @return string
     */
    private static function translateMonth($str)
    {
        switch ($str) {
            case 'Янв':
                $str = '01';
                break;
            case 'Фев':
                $str = '02';
                break;
            case 'Мар':
                $str = '03';
                break;
            case 'Апр':
                $str = '04';
                break;
            case 'Май':
                $str = '05';
                break;
            case 'Июн':
                $str = '06';
                break;
            case 'Июл':
                $str = '07';
                break;
            case 'Авг':
                $str = '08';
                break;
            case 'Сен':
                $str = '09';
                break;
            case 'Окт':
                $str = '10';
                break;
            case 'Ноя':
                $str = '11';
                break;
            case 'Дек':
                $str = '12';
                break;
        }

        return $str;
    }
}