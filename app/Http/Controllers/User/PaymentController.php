<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Order;
use App\PaymentMethod;

class PaymentController extends Controller
{
    public function index($id)
    {
        $orderInfo = Order::with(['OrderDetailInfo'])->where(['order_code' => $id])->first();
        $amount = 0;

        if(empty($orderInfo)){
            toast()->error('Order not found', 'Alert');
            return redirect('/');
        }
        if(!empty($orderInfo['OrderDetailInfo'])){
            foreach ($orderInfo['OrderDetailInfo'] as $value){
                $amount += $value['total_price'];
            }
        }
        if($amount === 0 || !is_numeric($amount)){
            toast()->error('Invalid amount', 'Alert');
            return redirect('/');
        }


        if(!empty($orderInfo->status) && $orderInfo->status === 'PaymentSuccess'){
            toast()->error('Order already confirmed', 'Alert');
            return redirect('/');
        }

        $vnp_Url = env('URL_VNPAY');
        $vnp_Returnurl = env('URL_CALLBACK_VNPAY') . "/payment/vnpay/verify";
        $vnp_TmnCode = env('WEBSITE_CODE');
        $vnp_HashSecret = env('CHECKSUM_CODE');

        $urlVnPay = PaymentMethod::getUrlPaymentVnPay($id, $amount, $vnp_Url, $vnp_Returnurl, $vnp_TmnCode, $vnp_HashSecret);

        return redirect($urlVnPay);
    }

    public function verify()
    {
        $request = request()->all();

        $vnp_TxnRef = !empty($request['vnp_TxnRef']) ? $request['vnp_TxnRef'] : null;
        $vnp_ResponseCode = !empty($request['vnp_ResponseCode']) ? $request['vnp_ResponseCode'] : '99';
        $vnp_Amount = !empty($request['vnp_Amount']) ? $request['vnp_Amount'] : 0;
        $vnp_SecureHash = !empty($request['vnp_SecureHash']) ? $request['vnp_SecureHash'] : 0;
        $vnp_HashSecret = env('CHECKSUM_CODE');
        $order = Order::with(['OrderDetailInfo'])->where(['order_code' => $vnp_TxnRef])->first();

        if (!empty($order)) {
            $checkPayment = Order::checkResponseVnPay($order, $vnp_TxnRef, $vnp_ResponseCode, $vnp_Amount, $vnp_SecureHash, $vnp_HashSecret);
            $order->status = $checkPayment['status'];
            if($checkPayment['type'] === 'error'){
                toast()->error($checkPayment['Message']);
            }else {
                toast()->success($checkPayment['Message']);
            }
        } else {
            toast()->error('Order not found');
        }
        return redirect('/');
    }

    public function verifyConfirm()
    {
        $request = request()->all();

        $vnp_TxnRef = !empty($request['vnp_TxnRef']) ? $request['vnp_TxnRef'] : null;
        $vnp_ResponseCode = !empty($request['vnp_ResponseCode']) ? $request['vnp_ResponseCode'] : '99';
        $vnp_Amount = !empty($request['vnp_Amount']) ? $request['vnp_Amount'] : 0;
        $vnp_SecureHash = !empty($request['vnp_SecureHash']) ? $request['vnp_SecureHash'] : 0;
        $vnp_HashSecret = env('CHECKSUM_CODE');
        $order = Order::with(['OrderDetailInfo'])->where(['order_code' => $vnp_TxnRef])->first();
        $checkPayment = Order::checkResponseVnPay($order, $vnp_TxnRef, $vnp_ResponseCode, $vnp_Amount, $vnp_SecureHash, $vnp_HashSecret);
        if(!empty($order)){
            $order->status = $checkPayment['status'];
            $order->save();
        }
        return response()->json(['RspCode' => $checkPayment['RspCode'], 'Message' => $checkPayment['Message']], 200);
    }
}