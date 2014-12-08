<?php

namespace App\Service\Form\Preorder;

use App\Repository\Product\ProductInterface;
use App\Repository\Category\CategoryInterface;
use App\Repository\Commerce\CommerceInterface;
use App\Repository\Branch\BranchInterface;
use App\Service\Form\Order\OrderForm;
use App\Service\Form\OrderStatus\OrderStatusForm;
use App\Service\Form\OrderProduct\OrderProductForm;
use App\Service\Form\AttributeOrderProduct\AttributeOrderProductForm;

class PreorderForm {

    /**
     * Preorder Form Service
     *
     */
    protected $product;
    protected $category;
    protected $commerce;
    protected $branch;
    protected $order;
    protected $orderstatus;
    protected $orderproduct;
    protected $attributeorderproduct;

    public function __construct(ProductInterface $product, CategoryInterface $category, CommerceInterface $commerce, BranchInterface $branch, OrderForm $order, OrderStatusForm $orderstatus, OrderProductForm $orderproduct, AttributeOrderProductForm $attributeorderproduct) {
        $this->product = $product;
        $this->category = $category;
        $this->commerce = $commerce;
        $this->branch = $branch;
        $this->order = $order;
        $this->orderstatus = $orderstatus;
        $this->orderproduct = $orderproduct;
        $this->attributeorderproduct = $attributeorderproduct;
    }

    public function all($sessionOrders) {

        $orders = NULL;

        if ($sessionOrders) {

            foreach ($sessionOrders as $branch_id => $order) {

                $orders[$branch_id]['commerce_name'] = $this->branch->find($branch_id, ['*'], ['commerce'])->commerce->commerce_name;

                foreach ($order as $productIndex => $product) {

                    $p = $this->product->find($product['id'], ['*'], ['tags', 'attributes.attribute_types', 'rules.rule_type']);
                    $p->qty = $product['qty'];
                    $p->attr = $product['attr'];
                    $orders[$branch_id]['products'][$productIndex] = $p;
                }
            }
        }

        return $orders;
    }

    public function process($customer_id, $productsByBranch, $payCash) {

        if (!$productsByBranch) {
            $res['error'] = 'No hay elementos en el pedido.'; //TODO: lang;
            return $res;
        }

        //Start transaction
        \DB::beginTransaction();

        foreach ($productsByBranch as $branchId => $products) {
            
            $delivery = 0;
            $payCashAmount = NULL;

            if (\Session::get('delivery')) {
                
                if (!isset($payCash['pay']) || !isset($payCash['pay'][$branchId])) {
                    
                    $res['error'] = 'Por favor indique con que monto se abonara el pedido.'; //TODO: lang;
                    return $res;
                }
                
                $delivery = 1;
                $payCashAmount = $payCash['pay'][$branchId] == 'custom' ? $payCash['amount'][$branchId] : $payCash['pay'][$branchId];
            }
            
            $orderInput = array(
                'branch_id' => $branchId,
                'user_id' => $customer_id,
                'delivery' => $delivery,
                'paycash' => $payCashAmount
            );

            $order = $this->order->save($orderInput);

            if (!$order) {
                \DB::rollback();
                $res['error'] = $this->order->errors();
                return $res;
            }

            $order->status_id = 1; //TODO. escribir estado en constante.

            $orderStatus = $this->orderstatus->save($order);

            if (!$orderStatus) {
                \DB::rollback();
                $res['error'] = $this->orderstatus->errors();
                return $res;
            }

            foreach ($products as $product) {

                $orderProductInput = array(
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'product_qty' => $product['qty']
                );

                $orderProduct = $this->orderproduct->save($orderProductInput);

                if (!$orderProduct) {
                    \DB::rollback();
                    $res['error'] = $this->orderproduct->errors();
                    return $res;
                }


                if (!is_null($product['attr'])) {

                    $sync = $this->attributeorderproduct->syncAttributes($product['attr'], $orderProduct->id);

                    if (!$sync) {
                        \DB::rollback();
                        $res['error'] = $this->attributeorderproduct->errors();
                        return $res;
                    }
                }
            }
        }

        \DB::commit();

        return true;
    }

    public function add($productForm) {

        $product = $this->product->findWhereBranchId($productForm['id'], $productForm['branch']);

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

        if (!is_null($product)) {
            \Session::push('orders.' . $productForm['branch'], [
                'id' => $product->id,
                'qty' => isset($productForm['qty']) ? $productForm['qty'] : 1,
                'attr' => isset($productForm['attr']) ? $productForm['attr'] : NULL
            ]);
        }

        return true;
    }

    public function config($productForm) {

        $sessionProduct = \Session::get('orders.' . $productForm['branch'] . '.' . $productForm['index']);

        if ($sessionProduct) {
            $product = $this->product->findWhereBranchId($sessionProduct['id'], $productForm['branch']);

            if (!is_null($product)) {
                \Session::put('orders.' . $productForm['branch'] . '.' . $productForm['index'], [
                    'id' => $product->id,
                    'qty' => isset($productForm['qty']) && $productForm['qty'] > 0 ? $productForm['qty'] : 1,
                    'attr' => isset($productForm['attr']) ? $productForm['attr'] : NULL
                ]);
            }
        }

        return true;
    }

    public function remove($productForm) {

        if ($productForm['branch'] && $productForm['item'] !== false) {

            if (\Session::has('orders.' . $productForm['branch'] . '.' . $productForm['item']))
                \Session::forget('orders.' . $productForm['branch'] . '.' . $productForm['item']);

            if (!count(\Session::get('orders.' . $productForm['branch'])) > 0)
                \Session::forget('orders.' . $productForm['branch']);
        }

        return true;
    }

}
