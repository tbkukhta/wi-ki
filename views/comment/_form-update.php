<?php

use app\models\Attachment;
use app\models\Comment;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $comment Comment */
/* @var $tags array|ActiveRecord[] */

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Редактировать комментарий</h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => "comment-update-form-$comment->id",
            'options' => ['enctype' => 'multipart/form-data'],
            'validateOnChange' => false,
            'validateOnBlur' => false
        ]) ?>

        <?= $form->field($comment, 'comment_id')->hiddenInput([
            'id' => "comment_id-$comment->id",
            'value' => $comment->id
        ])->label(false) ?>

        <?= $form->field($comment, 'title')->textInput([
            'id' => "title-$comment->id",
            'value' => $comment->title,
            'maxlength' => true
        ]) ?>

        <?= $form->field($comment, 'text')->textarea([
            'id' => "text-$comment->id",
            'value' => $comment->text,
            'maxlength' => true,
            'rows' => 8,
            'style' => 'resize:vertical'
        ]) ?>

        <?= $form->field($comment, '_attachments[]')->input('file', [
            'id' => "file-input-$comment->id",
            'multiple' => true
        ]) ?>

        <?php if (!empty($comment->attachments)): ?>
            <div class="col-sm-12 row attachments-update">
                <?php foreach ($comment->attachments as $attachment): ?>
                    <a id="<?= $attachment->id ?>" class="attachment" title="Удалить"
                       href="<?= '/' . Attachment::ATTACHMENTS_DIR . 'comment-' . $comment->id . '/' . $attachment->file ?>">
                        <img src="<?= '/' . $attachment->image ?>"><span class="button-remove">×</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($tags)): ?>
            <div><b>Теги</b></div>
            <div class="tags-box">
                <?php foreach ($tags as $tag): ?>
                    <label><input type="checkbox" name="tags[]" value="<?= $tag->id ?>" <?= $tag->checked ? 'checked' : '' ?>><?= $tag->name ?></label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <hr class="comments-hr">
        <div class="form-group text-right">
            <button type="button" class="btn btn-default" onclick="commentCancelUpdate(<?= $comment->id ?>);">Отмена</button>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>