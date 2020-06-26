<?php

/* @var $userPermission boolean */

?>

<?php if (!empty($model->description)): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-12 article-box">
                <div class="row">
                    <div class="col-sm-12 sample-title">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6">Описание кода</div>
                            <?php if ($userPermission): ?>
                                <div class="col-xs-6 col-sm-6 text-right">
                                    <a id href="#update" title="Быстрое редактирование" onclick="descriptionUpdate('<?= $model->id ?>');">
                                        <i class="fa fa-fw fa-pencil"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="article-description" class="col-sm-12 description-body"><?= $model->description ?></div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php if ($userPermission): ?>
        <div class="row">
            <div class="col-sm-12 text-center plus-box">
                <a href="#update" title="Добавить описание кода" onclick="descriptionUpdate('<?= $model->id ?>')">
                    <i class="fa fa-fw fa-plus"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>