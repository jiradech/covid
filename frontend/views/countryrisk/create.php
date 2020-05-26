<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Countryrisk */

$this->title = 'Create Countryrisk';
$this->params['breadcrumbs'][] = ['label' => 'Countryrisks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countryrisk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
