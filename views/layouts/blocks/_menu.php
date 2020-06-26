<?php

use app\services\MenuService;
use app\widgets\MenuWrapper;

?>

<?= MenuWrapper::widget([
    'items' => (new MenuService($project))->getItems(),
    'options' => ['class' => 'cm-menu-items'],
]); ?>