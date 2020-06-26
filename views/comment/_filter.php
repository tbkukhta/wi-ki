<?php

use yii\db\ActiveRecord;

/* @var $tab int */
/* @var $authors array|ActiveRecord[] */
/* @var $tags array|ActiveRecord[] */

?>

<div id="filter-row-<?= $tab ?>" class="col-sm-12">
    <div class="col-sm-12 filter-box box">
        <div class="form-inline">
            <div class="form-group filter-title">Фильтр комментариев</div>
            <div class="form-group filter-box-in-box">
                <div class="input-daterange input-group">
                    <input type="text" class="form-control from-date" name="from-date" placeholder="С даты">
                    <span class="input-group-addon"><i class="fa fa-fw fa-arrows-h"></i></span>
                    <input type="text" class="form-control to-date" name="to-date" placeholder="По дату">
                </div>
            </div>
            <div class="form-group filter-box-in-box">
                <select class="form-control by-author">
                    <option hidden value="0">По автору</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?= $author->id ?>"><?= $author->first_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group filter-box-in-box">
                <select class="form-control by-tag">
                    <option hidden value="0">По тегу</option>
                    <?php foreach ($tags as $tag): ?>
                        <option value="<?= $tag->id ?>"><?= $tag->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group filter-box-in-box">
                <button type="button" class="form-control filter-clear">Очистить фильтр</button>
            </div>
        </div>
    </div>
</div>