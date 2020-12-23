<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $articles app\models\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<h1>Articles</h1>
<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'image',
        'title',
        'text',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<?php Pjax::end(); ?>



