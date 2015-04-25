<?php

namespace App\Service\Form\Preorder;

use Illuminate\Support\MessageBag;
use App\Service\Validation\ValidableInterface;
use App\Repository\Product\ProductInterface;
use App\Repository\BranchProduct\BranchProductInterface;
use App\Repository\BranchProductPrice\BranchProductPriceInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Service\Form\OrderCash\OrderCashForm;
use App\Service\Form\OrderProduct\OrderProductForm;
use App\Service\Form\AttributeOrderProduct\AttributeOrderProductForm;
use App\Service\Form\AbstractForm;

class PreorderForm extends AbstractForm {

    /**
     * Preorder Form Service
     *
     */
    protected $messageBag;
    protected $product;
    protected $branchProduct;
    protected $branchProductPrice;
    protected $category;
    protected $commerce;
    protected $branch;
    protected $order;
    protected $orderstatus;
    protected $ordercash;
    protected $orderproduct;
    protected $attributeorderproduct;

    public function __construct(ValidableInterface $validator, ProductInterface $product, BranchProductInterface $branchProduct, BranchProductPriceInterface $branchProductPrice, CategoryInterface $category, CommerceInterface $commerce, BranchInterface $branch, OrderForm $order, OrderCashForm $ordercash, OrderStatusForm $orderstatus, OrderProductForm $orderproduct, AttributeOrderProductForm $attributeorderproduct) {
        parent::__construct($validator);
        $this->messageBag = new MessageBag();
        $this->product = $product;
        $this->branchProduct = $branchProduct;
        $this->branchProductPrice = $branchProductPrice;
        $this->category = $category;
        $this->commerce = $commerce;
        $this->branch = $branch;
        $this->order = $order;
        $this->orderstatus = $orderstatus;
        $this->ordercash = $ordercash;
        $this->orderproduct = $orderproduct;
        $this->attributeorderproduct = $attributeorderproduct;
    }

    public function all($sessionOrders) {

        $orders = NULL;

        if ($sessionOrders) {

            foreach ($sessionOrders as $branch_id => $order) {

                $orders[$branch_id]['commerce'] = $this->branch->find($branch_id, ['*'], ['commerce'])->commerce;

                foreach ($order as $productIndex => $branchProduct) {

                    $p = $this->branchProduct->find($branchProduct['id'], ['*'], ['product.tags', 'product.attributes.attribute_types', 'product.rules.rule_type'], ['active' => 1]);

                    if (!is_null($p)) {
                        $p->price = $this->branchProductPrice->find($branchProduct['price_id'], ['*'], ['size'], ['branch_product_id' => $p->id]);

                        if (is_null($p->price)) {//Update price if change while in basket
                            $p->price = $this->branchProductPrice->first(['*'], ['size'], ['branch_product_id' => $p->id]);
                            $product = \Session::get('orders.' . $branch_id . '.' . $productIndex);
                            $product['price_id'] = $p->price->id;
                            \Session::put('orders.' . $branch_id . '.' . $productIndex, $product);

                            $this->messageBag->add('notice', 'Uno o mas precios de algun producto en su carrito han sido actualizados.'); //TODO. Soporte Lang.
                        }

                        $p->attr = isset($branchProduct['attr']) ? $branchProduct['attr'] : NULL;
                        $p->qty = $branchProduct['qty'];
                        $orders[$branch_id]['products'][$productIndex] = $p;
                    } else {
                        $this->remove(array('branch' => $branch_id, 'item' => $productIndex));
                        //TODO. Notificar al cliente que un producto de la canasta fue pausado o removido por el comerciante.
                    }
                }
            }
        }
        $this->validator->messages($this->messageBag);
        return $orders;
    }

    public function process($customer_id, $productsByBranch, $payCash) {

        if (!$productsByBranch) {
            $this->messageBag->add('error', 'No hay elementos en el pedido.'); //TODO. Soporte Lang.
            $this->validator->errors = $this->messageBag;
            return false;
        }

        //Start transaction
        \DB::beginTransaction();

        foreach ($productsByBranch as $branchId => $products) {
            
            //Order

            $delivery = 0;
            $payCashAmount = NULL;

            if (\Session::get('delivery')) {

                if (!isset($payCash['pay']) || !isset($payCash['pay'][$branchId])) {
                    $this->messageBag->add('error', 'Por favor indique con que monto se abonara el pedido.'); //TODO. Soporte Lang.
                    $this->validator->errors = $this->messageBag;
                    return false;
                }

                $delivery = 1;
                $payCashAmount = $payCash['pay'][$branchId] == 'custom' ? $payCash['amount'][$branchId] : $payCash['pay'][$branchId];
            }

            $orderInput = array(
                'branch_id' => $branchId,
                'user_id' => $customer_id,
                'delivery' => $delivery,
                    //'paycash' => $payCashAmount
            );

            $order = $this->order->save($orderInput);

            if (!$order) {
                \DB::rollback();
                $this->validator->errors = $this->order->errors();
                return false;
            }

            //OrderStatus
            
            $inputStatus = array(
                'order_id' => $order->id,
                'status_id' => \Config::get('cons.order_status.pending'),
                'motive_id' => false,
                'observations' => false
            );

            $orderStatus = $this->orderstatus->save($inputStatus);

            if (!$orderStatus) {
                \DB::rollback();
                $this->validator->errors = $this->orderstatus->errors();
                return false;
            }

            //OrderProduct & AttributeOrderProduct

            $total = 0;

            foreach ($products as $product) {

                $orderProductInput = array(
                    'order_id' => $order->id,
                    'branch_product_id' => $product['id'],
                    'product_qty' => $product['qty'],
                    'branch_product_price_id' => $product['price_id']
                );

                $orderProduct = $this->orderproduct->save($orderProductInput);

                if (!$orderProduct) {
                    \DB::rollback();
                    $this->validator->errors = $this->orderproduct->errors();
                    return false;
                }


                if (isset($product['attr']) && !is_null($product['attr'])) {

                    $sync = $this->attributeorderproduct->syncAttributes($product['attr'], $orderProduct->id);

                    if (!$sync) {
                        \DB::rollback();
                        $this->validator->errors = $this->attributeorderproduct->errors();
                        return false;
                    }
                }

                $branchProductPrice = $this->branchProductPrice->find($product['price_id'], ['*'], [], ['branch_product_id' => $product['id']]);

                $total = $total + $branchProductPrice->price * $product['qty'];
            }
            
            $order->total = $total;
            $order->save();
            //OrderCash

            if ($delivery) {

                if (!($payCashAmount >= $order->total)) {
                    $this->validator->errors = new MessageBag(['paycash' => 'El monto con el que abonará el pedido debe ser mayor al valor total de la comanda.']);
                    return false;
                }

                $order->paycash = $payCashAmount;
                $order->change = $payCashAmount - $order->total;

                $orderCash = $this->ordercash->save($order);

                if (!$orderCash) {
                    \DB::rollback();
                    $this->validator->errors = $this->ordercash->errors();
                    return false;
                }
            }
        }

        \DB::commit();

        return true;
    }

    public function add($input) {

        $branchProduct = $this->branchProduct->find($input['productid'], ['*'], ['prices']);

        //$product = $this->product->findWhereBranchId($productForm['id'], $productForm['branch']);

        /* TODO. Logica de tiempos de entrega de sucursales y asignacion de comandas.
         * 
         * if($product->branch->count>1){
         * 
         *      foreach($product->branch as $branch){
         *          
         *          if(existeEnComanda($branch->id)){
         * 
         *              $guardado = true;
         *              Guardar producto en la comanda para la sucursal $branch->id
         *              break;
         * 
         *          }else{
         * 
         *              $minutos[$branch->id] = preguntamos en cuantos minutos promedio entrega la sucursal $branch->id
         *          }
         *      }
         * }
         * 
         * if(!$guardado){
         *      $menorTiempo = false;
         *      foreach($minutos as $minutosSucursal){
         *          if($menorTiempo){
         *              $menorTiempo = $menorTiempo <= $minutosSucursal ? $menorTiempo : $minutosSucursal;
         *          }else{
         *              $menorTiempo = $minutosSucursal;
         *          }
         *      }
         * }
         */

        if (!is_null($branchProduct)) {

            \Session::push('orders.' . $branchProduct->branch_id, [
                'id' => $branchProduct->id,
                'price_id' => $input['priceid'],
                'qty' => 1
            ]);

            /* foreach ($branchProduct->prices as $price) {

              if ($price->id == $input['priceid']) {
              \Session::push('orders.' . $branchProduct->branch_id, [
              'id' => $branchProduct->id,
              'price_id' => $price->id,
              'qty' => 1
              ]);

              $priceVerified = true;
              break;
              }
              }

              if (!$priceVerified) {
              \Session::push('orders.' . $branchProduct->branch_id, [
              'id' => $branchProduct->id,
              'price_id' => $branchProduct->prices[0]->price,
              'qty' => 1
              ]);
              } */
        }

        $basket = \Session::get('orders');

        return $basket;
    }

    public function configQty($input) {

        if ($input['branch'] && $input['item'] !== false && $input['qty'] > 0) {

            if (\Session::has('orders.' . $input['branch'] . '.' . $input['item'])) {
                $product = \Session::get('orders.' . $input['branch'] . '.' . $input['item']);
                $product['qty'] = $input['qty'];
                \Session::put('orders.' . $input['branch'] . '.' . $input['item'], $product);
            }
        }

        return \Session::get('orders');
    }

    public function configAttr($input) {

        if ($input['branch'] && $input['item'] !== false && $input['attr']) {

            if (\Session::has('orders.' . $input['branch'] . '.' . $input['item'])) {
                $product = \Session::get('orders.' . $input['branch'] . '.' . $input['item']);
                $product['attr'] = $input['attr'];
                \Session::put('orders.' . $input['branch'] . '.' . $input['item'], $product);
            }
        }

        return \Session::get('orders');
    }

    public function remove($productForm) {

        if ($productForm['branch'] && $productForm['item'] !== false) {

            if (\Session::has('orders.' . $productForm['branch'] . '.' . $productForm['item']))
                \Session::forget('orders.' . $productForm['branch'] . '.' . $productForm['item']);

            if (!count(\Session::get('orders.' . $productForm['branch'])) > 0)
                \Session::forget('orders.' . $productForm['branch']);
        }

        return \Session::get('orders');
    }

}
