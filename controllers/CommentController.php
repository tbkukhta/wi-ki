<?php

namespace app\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\Comment;
use app\models\Attachment;
use app\models\Tag;
use app\models\User;
use yii\web\Response;
use app\helpers\ArticleHelper;

class CommentController extends BaseController
{
    /**
     * Create comment
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $form = $_POST['form'];
            $params = []; parse_str($form, $params);
            $model = new Comment();

            if ($model->load($params) && $model->save()) {
                if (!empty($_FILES)) {
                    $model->uploadAttachments();
                }

                if (!empty($params['tags'])) {
                    foreach ($params['tags'] as $tag) {
                        $tag_model = Tag::findOne($tag);
                        $model->link('tags', $tag_model);
                        unset($tag_model);
                    }
                }

                $data = [
                    'id' => $model->id,
                    'message' => 'Комментарий добавлен.'
                ];

                if (!$_POST['count']) {
                    $data['filter'] = $this->renderAjax('/comment/_filter', [
                        'tab' => $model->tab,
                        'authors' => User::find()->all(),
                        'tags' => Tag::find()->all()
                    ]);
                }

                return ['success' => $data];
            }

            return ['error' => 'Возникла ошибка при добавлении комментария.'];
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Update comment
     *
     * @return Response
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isPost) {
            $commentId = (int)Yii::$app->request->post('comment_id');
            $model = $this->findModel($commentId);
            $tags = Tag::find()->all();
            $modelTags = ArrayHelper::getColumn($model->tags, 'id');
            foreach ($tags as $tag) {
                if (in_array($tag->id, $modelTags)) {
                    $tag->checked = 1;
                }
            }

            return $this->asJson($this->renderAjax('_form-update', [
                'comment' => $model->updateData(),
                'tags' => $tags
            ]));
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Save comment
     *
     * @return array
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionSave()
    {
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $form = $_POST['form'];
            $params = []; parse_str($form, $params);
            $commentId = (int)$params['Comment']['comment_id'];
            $commentTitle = $params['Comment']['title'];
            $commentText = $params['Comment']['text'];
            $model = $this->findModel($commentId);

            if ($model !== null) {
                $deletedFiles = json_decode($_POST['deleted_files']);
                $tagsChanged = $model->tagsChanged($params['tags']);

                if ($model->title === $commentTitle && $model->text === $commentText) {
                    if (empty($deletedFiles) && empty($_FILES) && !$tagsChanged) {
                        return ['unchanged' => 'Комментарий сохранён без изменений.'];
                    } else {
                        $model->updated_at = time();
                    }
                } else {
                    $model->title = $commentTitle;
                    $model->text = $commentText;
                }

                if ($model->save()) {
                    if (!empty($deletedFiles)) {
                        foreach ($deletedFiles as $attach_id) {
                            $attachment = Attachment::findOne((int)$attach_id);
                            if ($attachment !== null) {
                                $attachment->deleteAttachmentFile();
                            }
                        }
                    }

                    if (!empty($_FILES)) {
                        $model->uploadAttachments();
                    }

                    if ($tagsChanged) {
                        $model->unlinkAll('tags', true);
                        if (isset($params['tags'])) {
                            foreach ($params['tags'] as $tag) {
                                $tagModel = Tag::findOne($tag);
                                $model->link('tags', $tagModel);
                            }
                        }
                    }

                    return ['success' => 'Комментарий изменён.'];
                }
            }

            return ['error' => 'Возникла ошибка при редактировании комментария.'];
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Delete comment
     *
     * @return array
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $commentId = (int)Yii::$app->request->post('comment_id');
            $model = $this->findModel($commentId);

            if ($model->delete()) {
                $commentDir = Attachment::ATTACHMENTS_DIR . 'comment-' . $commentId;
                if (file_exists($commentDir)) {
                    Attachment::deleteFolder($commentDir);
                }

                return ['success' => 'Комментарий удалён.'];
            }

            return ['error' => 'Возникла ошибка при удалении комментария.'];
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Display comments
     *
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionDisplay()
    {
        if (Yii::$app->request->isPost) {
            $commentParams = Yii::$app->request->post('comment_params');
            $comments = ArticleHelper::getComments($commentParams);
            $data = [];
            foreach ($comments as $index => $comment) {
                $data[] = $this->renderAjax('_comment', [
                    'comment' => $comment,
                    'index' => $index + 1
                ]);
            }

            return $this->asJson([
                'data' => $data,
                'count' => count($comments),
            ]);
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * @param $id
     * @return Comment|null
     * @throws Exception
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new Exception('Model not found.');
    }
}