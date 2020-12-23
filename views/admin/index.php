<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations! Backend</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">
        <p><a class="btn btn-lg btn-success" href=<?= Url::to(['article']);?>>articles</a></p>

        <p>
            <?= Html::a('Создать новую', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
</div>
