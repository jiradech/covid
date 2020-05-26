<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LocalQuarantine */

$this->title = 'Update Local Quarantine: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Local Quarantines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="local-quarantine-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
