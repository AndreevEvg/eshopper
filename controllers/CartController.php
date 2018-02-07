<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Cart;

class CartController extends AppController
{
    /* Добавление товаров в корзину */
    public function actionAdd()
    {
        $id = Yii::$app->request->get('id');
        $qty = (int)Yii::$app->request->get('qty');
        
        $qty = !$qty ? 1 : $qty;
        
        $product = Product::findOne($id);
        
        if (empty($product)) {
            return false;
        }
        
        $session = Yii::$app->session;
        $session->open();
        
        $cart = new Cart();
        $cart->addToCart($product, $qty);
        
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        $this->layout = false;
        
        return $this->render('cart-modal', [
            'session' => $session,
        ]);
    }
    
    /* Очистка корзины */
    public function actionClear()
    {
        $session = Yii::$app->session;
        $session->open();
        
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        
        $this->layout = false;
        
        return $this->render('cart-modal', [
            'session' => $session,
        ]);
    }
    
    /* Удаление товаров из корзины */
    public function actionDelItem()
    {
        $id = Yii::$app->request->get('id');
        
        $session = Yii::$app->session;
        $session->open();
        
        $cart = new Cart();
        $cart->recalc($id);
        
        $this->layout = false;
        
        return $this->render('cart-modal', [
            'session' => $session,
        ]);
    }
    
    /* Показать корзину */
    public function actionShow()
    {
        $session = Yii::$app->session;
        $session->open();
        
        $this->layout = false;
        
        return $this->render('cart-modal', [
            'session' => $session,
        ]);
    }
    
    /* Переход по ссылке "Оформить заказ" */
    public function actionView()
    {
        return $this->render('view');
    }
}
