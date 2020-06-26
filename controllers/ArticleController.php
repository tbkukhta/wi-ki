<?php

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Response;
use app\models\Project;
use app\helpers\ArticleHelper;
use app\helpers\PermissionHelper;

class ArticleController extends BaseController
{
    /**
     * Update title
     *
     * @return string
     * @throws Exception
     */
    public function actionTitleUpdate()
    {
        $articleId = Yii::$app->request->post('article_id');
        $articleModel = Yii::$app->request->post('article_model');
        $article = ArticleHelper::getArticle($articleModel, $articleId);

        return $this->renderAjax('_form-title-update', [
            'model' => $article,
        ]);
    }

    /**
     * Save title
     *
     * @return array
     * @throws Exception
     */
    public function actionTitleSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = Yii::$app->request->post('form');
        $articleId = $form[1]['value'];
        $articleName = $form[2]['value'];
        $articleModel = Yii::$app->request->post('article_model');
        $article = ArticleHelper::getArticle($articleModel, $articleId);
        $article->name = $articleName;
        $project = $articleModel === 'project' ? $article : Project::findOne(['slug' => Yii::$app->request->post('project_model')]);
        $userPermission = PermissionHelper::canGroup($articleModel) || ArticleHelper::authorPermission($article->author_id);

        if (Yii::$app->request->isAjax && $article->save(false)) {
            $viewTitle = $this->renderAjax('_title', [
                'model' => $article,
                'userPermission' => $userPermission
            ]);
            $viewMenu = $this->renderAjax('//layouts/blocks/_menu', ['project' => $project]);

            return [
                'slug' => $article->slug,
                'name' => $articleName,
                'view_title' => $viewTitle,
                'view_menu' => $viewMenu
            ];
        }

        throw new Exception('Возникла ошибка при сохранении названия.');
    }

    /**
     * Update description
     *
     * @return string
     * @throws Exception
     */
    public function actionDescriptionUpdate()
    {
        $articleId = Yii::$app->request->post('article_id');
        $articleModel = Yii::$app->request->post('article_model');
        $article = ArticleHelper::getArticle($articleModel, $articleId);

        return $this->renderAjax('_form-description-update', [
            'model' => $article
        ]);
    }

    /**
     * Save description
     *
     * @return array|string
     * @throws Exception
     */
    public function actionDescriptionSave()
    {
        $form = Yii::$app->request->post('form');
        $articleId = $form[1]['value'];
        $articleDescription = $form[2]['value'];
        $articleModel = Yii::$app->request->post('article_model');
        $article = ArticleHelper::getArticle($articleModel, $articleId);
        $article->description = $articleDescription;
        $userPermission = PermissionHelper::canGroup($articleModel) || ArticleHelper::authorPermission($article->author_id);

        if (Yii::$app->request->isAjax && $article->save(false)) {
            return $this->renderAjax('_description', [
                'model' => $article,
                'userPermission' => $userPermission
            ]);
        }

        throw new Exception('Возникла ошибка при сохранении описания кода.');
    }

    /**
     * Update code
     *
     * @return string
     * @throws Exception
     */
    public function actionCodeUpdate()
    {
        $articleId = Yii::$app->request->post('article_id');
        $articleModel = Yii::$app->request->post('article_model');
        $article = ArticleHelper::getArticle($articleModel, $articleId);

        return $this->renderAjax('_form-code-update', [
            'model' => $article
        ]);
    }

    /**
     * Save code
     *
     * @return array|string
     * @throws Exception
     */
    public function actionCodeSave()
    {
        $form = Yii::$app->request->post('form');
        $articleId = $form[1]['value'];
        $articleCode = $form[2]['value'];
        $articleModel = Yii::$app->request->post('article_model');
        $article = ArticleHelper::getArticle($articleModel, $articleId);
        $article->code = $articleCode;
        $userPermission = PermissionHelper::canGroup($articleModel) || ArticleHelper::authorPermission($article->author_id);

        if (Yii::$app->request->isAjax && $article->save(false)) {
            return $this->renderAjax('_code', [
                'model' => $article,
                'userPermission' => $userPermission
            ]);
        }

        throw new Exception('Возникла ошибка при сохранении примера кода.');
    }
}