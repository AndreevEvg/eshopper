<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\assets\AdminAppAsset;
use app\assets\LtAppAsset; // подключение скриптов для IE ниже 9 версии
use yii\bootstrap\Modal;


LtAppAsset::register($this);
AdminAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <?= Html::csrfMetaTags() ?>
        <title>Admin | <?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>      
        <link rel="shortcut icon" href="images/ico/favicon.ico">
    </head><!--/head-->

    <body>
    <?php $this->beginBody() ?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">E-SHOPPER</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li class="active"><a href="#">Overview</a></li>
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Analytics</a></li>
                    <li><a href="#">Export</a></li>
                    <li><a href="<?= Url::to(['/']) ?>">Exit</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <div class="row placeholders">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>