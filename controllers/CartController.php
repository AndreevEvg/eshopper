<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Cart;
use app\models\Order;
use app\models\OrderItems;

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
        $session = Yii::$app->session;
        $session->open();
        
        $this->setMeta('Корзина');
 
        $order = new Order();
        
        if ($order->load(Yii::$app->request->post())) {
            $order->qty = $session['cart.qty'];
            $order->sum = $session['cart.sum'];
           
            if ($order->save()) {
                $this->saveOrderItem($session['cart'], $order->id);
                Yii::$app->session->setFlash('contactFormSubmitted');
                
                /* Отправка сообщения на почту */
                Yii::$app->mailer->compose('order', ['session' => $session])
                        ->setFrom(['7253968@mail.ru' => 'E-shopper'])
                        ->setTo($order->email)
                        ->setSubject('Заказ с сайта')
                        ->send();
                
                /* Очищаем корзину */
                $session->remove('cart');
                $session->remove('cart.qty');
                $session->remove('cart.sum');
                
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('contactFormError');
            }
        }
        
        return $this->render('view', [
            'session' => $session,
            'order' => $order,
        ]);
    }
    
    protected function saveOrderItem($items, $order_id)
    {
        foreach ($items as $id => $item) {
            $order_items = new OrderItems();
            
            $order_items->order_id = $order_id;
            $order_items->product_id = $id;
            $order_items->name = $item['name'];
            $order_items->price = $item['price'];
            $order_items->qty_item = $item['qty'];
            $order_items->sum_item = $item['qty'] * $item['price'];
            
            $order_items->save();
        }
    }
}
