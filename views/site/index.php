<?php

use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $articles app\models\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<h1>Articles</h1>

<div class="category-list">
    <?php foreach ($articles  as $article) { ?>
        <a href="<?= \yii\helpers\Url::to(['site/article', 'id' => $article->id]) ?>" class="category-list-item">
            <div class="cover"><img src="<?= $article->image ?>" alt=""></div>
            <div class="title"><?= $article->title ?></div>
        </a>
    <?php } ?>
</div>




<?= LinkPager::widget(['pagination' => $pagination]) ?>
