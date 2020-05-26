<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Countryrisk */

$this->title = 'Update Countryrisk: ' . $model->countryid;
$this->params['breadcrumbs'][] = ['label' => 'Countryrisks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->countryid, 'url' => ['view', 'id' => $model->countryid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="countryrisk-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
