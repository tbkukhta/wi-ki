<?php

use app\models\Comment;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $comment Comment */
/* @var $tab int */
/* @var $tags array|ActiveRecord[] */

?>

<?php $form = ActiveForm::begin([
    'id' => "comment-create-form-$tab",
    'options' => ['enctype' => 'multipart/form-data'],
    'validateOnChange' => false,
    'validateOnBlur' => false
]) ?>

<?= $form->field($comment, 'author_id')->hiddenInput([
    'id' => "author_id-$tab",
    'value' => Yii::$app->user->identity->id,
    'maxlength' => true
])->label(false) ?>

<?= $form->field($comment, $comment->article_model . '_id')->hiddenInput([
    'id' => $comment->article_model . "_id-$tab",
    'value' => $comment->article_id,
    'maxlength' => true
])->label(false) ?>

<?= $form->field($comment, 'tab')->hiddenInput([
    'id' => "tab-$tab",
    'value' => $tab,
    'maxlength' => true
])->label(false) ?>

<?= $form->field($comment, 'title')->textInput([
    'id' => "title-$tab",
    'maxlength' => true
]) ?>

<?= $form->field($comment, 'text')->textarea([
    'id' => "text-$tab",
    'maxlength' => true,
    'rows' => 8,
    'style' => 'resize:vertical'
]) ?>

<?= $form->field($comment, '_attachments[]')->input('file', [
    'id' => "file-input-create-$tab",
    'multiple' => true
]) ?>

<?php if (!empty($tags)): ?>
    <div class="tag-button" title="Добавить теги">
        <span class="badge badge-success" data-toggle="collapse" data-target="#hide-me-tag-<?= $tab ?>">Добавить теги</span>
    </div>
    <div id="hide-me-tag-<?= $tab ?>" class="collapse tags-box">
        <?php foreach ($tags as $tag): ?>
            <label><input type="checkbox" name="tags[]" value="<?= $tag->id ?>"><?= $tag->name ?></label>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <hr class="comments-hr">
    <div class="form-group text-right">
        <button type="button" class="btn btn-default" onclick="$('#comment-create-add-<?= $tab ?>').trigger('click');">Отмена</button>
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>