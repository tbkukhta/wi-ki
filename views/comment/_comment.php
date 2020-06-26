<?php

use app\models\Comment;
use app\models\Attachment;
use app\helpers\PermissionHelper;
use yii\helpers\Html;

/* @var $index int */
/* @var $comment Comment */

?>

<div id="comment-<?= $comment->id ?>" class="comment-block" data-index="<?= $index ?>">
    <div class="comment-container">
        <div class="panel panel-default">
            <div class="comment-header panel-heading panel-heading--visible">
                <div class="row">
                    <div class="col-sm-6 text-left comments-title"><?= Html::encode($comment->title) ?></div>
                    <?php if (PermissionHelper::canGroup('comment') || $comment->author_permission): ?>
                        <div class="col-sm-6 text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" title="Править"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-fw fa-edit"></i> Править <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#update" title="Редактировать" onclick="commentUpdate(<?= $comment->id ?>);">
                                            <i class="fa fa-fw fa-pencil"></i> Редактировать
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#delete" title="Удалить" onclick="commentDelete(<?= $comment->id ?>);">
                                            <i class="fa fa-fw fa-remove"></i> Удалить
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-body"><?= Html::encode($comment->text) ?></div>
            <div class="comments-panel-footer">
                <?php if (!empty($comment->attachments)): ?>
                    <div class="col-sm-12 row comments-attachments-box">
                        <div class="comments-attachments-text"><b>Файлы:</b></div>
                        <div class="inline-block">
                            <?php foreach ($comment->attachments as $attachment): ?>
                                <a class="inline-block" title="Скачать"
                                   href="<?= '/' . Attachment::ATTACHMENTS_DIR . 'comment-' . $comment->id . '/' . $attachment->file ?>" download>
                                    <img src="<?= '/' . $attachment->image ?>">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 text-left">
                        <div><b>Автор:</b> <?= $comment->author->first_name ?></div>
                    </div>
                    <div class="col-xs-6 col-sm-6 text-right">
                        <i class="fa fa-fw fa-calendar"></i> <?= $comment->updated_at ?>
                    </div>
                </div>
                <?php if (!empty($comment->tags)): ?>
                    <hr class="comments-hr">
                    <div class="row">
                        <div class="col-sm-12 text-left">
                            <?php foreach ($comment->tags as $tag): ?>
                                <span class="badge badge-primary"> <?= $tag->name ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>