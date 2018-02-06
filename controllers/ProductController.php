<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Product;

class ProductController extends AppController
{
    /* карточка товара */
    public function actionView($id)
    {
        //$id = Yii::$app->request->get('id');
        
        $product = Product::findOne($id); // ленивая загрузка
        
        if ($product === null) { 
            throw new \yii\web\HttpException(404, 'Такого товара нет.');
        }
        
        //$product = Product::find()->with('category')->where(['id' => $id])->limit(1)->one(); // жадная загрузка
        
        // вывод популярных товаров
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();
        
        $this->setMeta('E-SHOPPER | ' . $product->name, $product->keywords, $product->description);
        
        return $this->render('view', [
            'product' => $product,
            'hits' => $hits,
        ]);
    }
    
}
