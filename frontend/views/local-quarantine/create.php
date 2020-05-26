<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LocalQuarantine */

$this->title = 'Create Local Quarantine';
$this->params['breadcrumbs'][] = ['label' => 'Local Quarantines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="local-quarantine-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'amphur' => $amphur,
            'tambon' => $tambon,
            'village' => $village,
    ]) ?>

</div>
