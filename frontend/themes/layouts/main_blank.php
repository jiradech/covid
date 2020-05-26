<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
// use kartik\nav\NavX;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\LoginForm;
// use common\models\Profile;
// use kartik\dropdown\DropdownX;
// use common\models\Menu;
// use common\components\MenuHelper;


AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">

    <!-- Twitter -->
    <meta name="twitter:site" content="@vueghost">
    <meta name="twitter:creator" content="@vueghost">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="magen-iot-admin">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://vueghost.com/magen-iot-admin/img/magen-iot-admin-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://vueghost.com/magen-iot-admin">
    <meta property="og:title" content="Bracket">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="http://vueghost.com/magen-iot-admin/img/magen-iot-admin-social.png">
    <meta property="og:image:secure_url" content="http://vueghost.com/magen-iot-admin/img/magen-iot-admin-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="vueghost">


    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>


  </head>

<body>

    <?php $this->beginBody() ?>



                <?= Alert::widget() ?>

                <?= $content ?>


<?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
