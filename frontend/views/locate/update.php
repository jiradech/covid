<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Locate */

$this->title = 'Update Locate: ' . $model->province;
$this->params['breadcrumbs'][] = ['label' => 'Locates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->province, 'url' => ['view', 'id' => $model->province]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="locate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
