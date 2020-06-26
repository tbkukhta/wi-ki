<?php

use app\models\Comment;
use app\models\Tag;

/* @var $comment Comment */
/* @var $tab int */
/* @var $comments_count int */

?>

<div class="comments-box">
    <div class="col-sm-12 comments-box-count">
        <hr>
        <i class="fa fa-comment"></i> комментарии: <span id="comments-count-<?= $tab ?>"><?= $comments_count ?></span>
    </div>
    <div class="col-sm-12 comments-add-comment-box">
        <div class="add-comment-container">
            <div class="comments-button text-right" title="Добавить комментарий">
                <button type="button" id="comment-create-add-<?= $tab ?>" class="btn btn-primary" data-toggle="collapse" data-target="#hide-me-<?= $tab ?>">
                    Добавить комментарий
                </button>
            </div>
            <div id="hide-me-<?= $tab ?>" class="collapse">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Добавить комментарий</h3>
                    </div>
                    <div class="panel-body">
                        <?= $this->render('_form', [
                            'comment' => $comment,
                            'tab' => $tab,
                            'tags' => Tag::find()->all(),
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="comments-row-<?= $tab ?>" class="col-sm-12"></div>
    <div id="pagination-<?= $tab ?>" class="col-sm-12"></div>
</div>