<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = $data['title'];
$this->params['breadcrumbs'][] = ['label' => '文章', 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;
//var_dump($data);
?>

<div class="row">
    <div class="col-lg-9">
        <div class="panel-title box-title">
            <h1><?= $data['title'] ?></h1>
        </div>
        <span>作者: <?= $data['user_name'] ?></span>
        <span>发布: <?= date('Y-m-d', $data['created_at']) ?></span>
        <span>浏览: <?= $data['extend']['browser']??0 ?>次</span>
        <div class="page-content">
            
            <?= $data['content'] ?>
        </div>
        <div class="page-tag">
            标签:
            <?php foreach ($data['tags'] as $tag): ?>
                <span><a href="#"><?= $tag ?></a></span>
            <?php endforeach; ?>
        
        </div>
    </div>
    <div class="col-lg-3">
    
    </div>
</div>
