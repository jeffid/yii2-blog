<?php

use frontend\widgets\post\PostWidget;
use yii\base\Widget;
use \yii\helpers\Url;

?>
<div class="row">
    <div class="col-lg-9">
        <?= PostWidget::widget(['limit' => '1']) ?>
    </div>
    <div class="col-lg-3">
        <div class="panel">
            <?php if (!\Yii::$app->user->isGuest): ?>
                <a href="<?= Url::to('post/create') ?>" class="btn btn-success btn-block btn-post">创建文章</a>
            <?php endif; ?>
        </div>
        <!--热门文章-->
        <?= \frontend\widgets\hot\HotWidget::widget() ?>
    </div>
</div>
