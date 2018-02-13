<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orders */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

    <h1>Просмотр заказа №<?= $model->id ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'updated_at',
            'qty',
            'sum',
            [
                'attribute' => 'status',
                'value' => !$model->status ? '<span class="text-danger">Активен</span>' : '<span class="text-success">Завершен</span>',
                'format' => 'html',
            ],
            //'status',
            'name',
            'email:email',
            'phone',
            'address',
        ],
    ]) ?>
    
    <?php $items = $model->ordersItems; ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Наименование товара</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><a href="<?= Url::to(['/product/view', 'id' => $item->product_id]) ?>"><?= $item['name'] ?></a></td>
                        <td><?= $item['qty_item'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['sum_item'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
