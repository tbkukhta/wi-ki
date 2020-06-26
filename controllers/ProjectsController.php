<?php

namespace app\controllers;

use app\models\Project;

class ProjectsController extends BaseController
{
    public $layout = 'main-not-sidebar';

    /**
     * Displays start app page
     * @return string
     */
    public function actionIndex(): string
    {
        $projects = Project::find()->all();
        return $this->render('index', ['projects' => $projects]);
    }
}