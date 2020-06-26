<?php

use app\assets\NotSidebarAsset;

/* @var $content string */

NotSidebarAsset::register($this);

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <?= $this->render('//layouts/blocks/_head') ?>
    <body class="cm-login">
    <?php $this->beginBody() ?>
    <div>
        <div class="login-logo text-center">
            <div class="login-text">Wi-Ki</div>
        </div>
        <?= $content ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>