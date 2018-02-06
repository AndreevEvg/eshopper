<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use app\models\Product;
use yii\data\Pagination;

class CategoryController extends AppController
{
    public function actionIndex()
    {
        $this->setMeta('E-SHOPPER');
        
        $hits = Product::find()->where(['hit' => '1'])->limit(6)->all();
        
        return $this->render('index', [
            'hits' => $hits,
        ]);
    }
    
    public function actionView($id)
    {
        //$id = Yii::$app->request->get('id');
        
        $category = Category::findOne($id);
        
        if ($category === null) { 
            throw new \yii\web\HttpException(404, 'Такой категории нет.');
        }
        
        //$products = Product::find()->where(['category_id' => $id])->all();
        $query = Product::find()->where(['category_id' => $id]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 3, 'forcePageParam' => false, 'pageSizeParam' => false]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        
        $this->setMeta('E-SHOPPER | ' . $category->name, $category->keywords, $category->description);
        
        return $this->render('view', [
            'products' => $products,
            'category' => $category,
            'products' => $products,
            'pages' => $pages,
        ]);
    }
    
    public function actionSearch()
    {
        
        $q = trim(Yii::$app->request->get('q'));
        
        $this->setMeta('E-SHOPPER | Поиск: ' . $q);
        
        if (!$q) {
            return $this->render('search', ['q' => $q]);
        }
        
        $query = Product::find()->where(['like', 'name', $q]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 3, 'forcePageParam' => false, 'pageSizeParam' => false]);
        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('search', [
            'products' => $products,
            'pages' => $pages,
            'q' => $q,
        ]);
    }
}
