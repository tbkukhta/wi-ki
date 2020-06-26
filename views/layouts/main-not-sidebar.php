<?php

use app\assets\NotSidebarAsset;
use yii\widgets\Breadcrumbs;

/* @var $content string */

NotSidebarAsset::register($this);

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <?= $this->render('//layouts/blocks/_head') ?>
    <body>
    <?php $this->beginBody() ?>
    <div id="global">
        <nav class="cm-navbar cm-navbar-primary">
            <?php if (isset($this->params['breadcrumbs'])): ?>
                <div class="cm-flex">
                    <h1>
                        <?= Breadcrumbs::widget([
                            'homeLink' => [
                                'label' => 'Главная',
                                'url' => '/',
                                'template' => '<li><b class="execute-btn" title="Перейти на главную страницу с проектами">{link}</b></li>'
                            ],
                            'links' => $this->params['breadcrumbs']
                        ]) ?>
                    </h1>
                </div>
            <?php else: ?>
                <div class="projects-nav projects-title">
                    <?= $this->title ?>
                </div>
            <?php endif; ?>
            <div class="projects-nav">
                <?= $this->render('//layouts/blocks/_userbox') ?>
            </div>
        </nav>
        <?= $content ?>
        <?= $this->render('//layouts/blocks/_footer') ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>