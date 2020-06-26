<?php

/* @var $userPermission boolean */

?>

<?php if (!empty($model->code)): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-12 article-box">
                <div class="row">
                    <div class="col-sm-12 sample-title">
                        <div class="row">
                            <div class="col-sm-6">Пример кода</div>
                            <?php if ($userPermission): ?>
                                <div class="col-sm-6 text-right">
                                    <a href="#update" title="Быстрое редактирование" onclick="codeUpdate('<?= $model->id ?>');">
                                        <i class="fa fa-fw fa-pencil"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <pre><code id="article-code" class="php"><?= $model->code ?></code></pre>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php if ($userPermission): ?>
        <div class="row">
            <div class="col-sm-12 text-center plus-box">
                <a href="#update" title="Добавить пример кода" onclick="codeUpdate('<?= $model->id ?>');">
                    <i class="fa fa-fw fa-plus"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>