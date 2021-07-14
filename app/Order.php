<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'id',
        'address_id',
        'payment_method_id',
        'delivery_id',
        'ship_fee',
        'delivery_date',
        'status',
        'created_at',
        'updated_at',
        'nhanh_order_id',
        'order_code',
        'user_id'
    ];

    public function insertOrder($data)
    {
        return Order::create($data);
    }

    public function getListOrderByUser($userId)
    {
        return Order::where('orders.user_id', '=', $userId)
            ->orderBy('orders.created_at', 'desc')->get();
    }

    public function getById($id)
    {
        return Order::leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
            ->leftJoin('addresses', 'addresses.id', '=', 'orders.address_id')
            ->select('orders.*', 'addresses.name as receiver_name', 'addresses.address as receiver_address', 'addresses.phone as receiver_phone', 'addresses.email as receiver_email', 'addresses.phone as receiver_phone', 'payment_methods.name as payment_method_name')
            ->where('orders.id', '=', $id)->first();
    }

    public function updateOrder($id, $data)
    {
        return Order::where('id', '=', $id)->update($data);
    }

    public function OrderDetailInfo()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public static function checkResponseVnPay($order, $vnp_TxnRef, $vnp_ResponseCode, $vnp_Amount, $vnp_SecureHash, $vnp_HashSecret)
    {
        $model['RspCode'] = '00';
        $model['Message'] = 'Confirm Success';
        $model['status'] = 'PaymentSuccess';
        $model['type'] = 'success';
        $amount = 0;

        if (!empty($order['OrderDetailInfo'])) {
            foreach ($order['OrderDetailInfo'] as $value) {
                $amount += $value['total_price'];
            }
        }

        if (!empty($order)) {
            $vnp_Returnurl = env('URL_CALLBACK_VNPAY') . "/payment/vnpay/verify";
            $vnp_TmnCode = env('WEBSITE_CODE');
            /// code in order
            $inputData = PaymentMethod::makeInput($order->order_code, $amount, $vnp_Returnurl, $vnp_TmnCode);
            $hash_Order = PaymentMethod::makeMergeInput($inputData)['hashdata'];
            $vnpSecureHash_Order = hash('sha256', $vnp_HashSecret . $hash_Order);

            // code in request
            $inputData = PaymentMethod::makeInput($vnp_TxnRef, $vnp_Amount / 100, $vnp_Returnurl, $vnp_TmnCode);
            $hash_Request = PaymentMethod::makeMergeInput($inputData)['hashdata'];
            $vnpSecureHash_Request = hash('sha256', $vnp_HashSecret . $hash_Request);

            $checkHashKey = hash_equals($vnpSecureHash_Order, $vnpSecureHash_Request);

            if ($amount != $vnp_Amount / 100 || $vnp_ResponseCode === '04') {
                $model['RspCode'] = '04';
                $model['Message'] = 'Invalid amount';
                $model['status'] = 'PaymentError';
                $model['type'] = 'error';
                return $model;
            }

            if (!$checkHashKey || $vnp_ResponseCode === '97') {
                $model['RspCode'] = '97';
                $model['Message'] = 'Invalid Checksum';
                $model['status'] = 'PaymentError';
                $model['type'] = 'error';
                return $model;
            }

            if ($order->status === 'PaymentSuccess' || $vnp_ResponseCode === '02') {
                $model['RspCode'] = '02';
                $model['Message'] = 'Order already confirmed';
                $model['status'] = 'PaymentSuccess';
                $model['type'] = 'error';
                return $model;
            }
        } else {
            $model['RspCode'] = '01';
            $model['Message'] = 'Order Not Found';
            $model['status'] = 'PaymentError';
            $model['type'] = 'error';
            return $model;
        }
    }
}
