<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'id', 'address_id', 'payment_method_id', 'delivery_id', 'ship_fee', 'delivery_date', 'status', 'created_at', 'updated_at', 'nhanh_order_id'
    ];

    public function insertOrder($data)
    {
        return Order::create($data);
    }

    public function getListContact()
    {
        return Order::orderBy('created_at', 'desc')->get();
    }

    public function getById($id){
        return Order::leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'orders.address_id')
            ->select('orders.*', 'addresses.name as receiver_name', 'addresses.address as receiver_address', 'addresses.phone as receiver_phone', 'addresses.email as receiver_email', 'addresses.phone as receiver_phone', 'payment_methods.name as payment_method_name')
            ->where('orders.id', '=', $id)->first();
    }

    public function updateOrder($id,$data){
        return Order::where('id', '=', $id)->update($data);
    }
}