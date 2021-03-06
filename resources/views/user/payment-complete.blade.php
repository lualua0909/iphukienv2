@extends('layouts.app')

@section('title', 'Thanh toán thành công')

@section('header')
    @include('layouts.header', ['status' => 'complete'])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/styles/iphukien/user/payment-complete.css') }}">
@endsection

@section('content')
    <div class="ipk-container">
        <div class="ipk-content-container">
            <div class="payment-complete-container">
                <div class="complete-avatar"></div>
                <div class="complete-title">
                    @if(!empty($order->status) && $order->status === 'New')
                        Đơn đặt hàng đang được xử lý
                    @elseif(!empty($order->status) && $order->status === 'PaymentSuccess')
                        Đặt hàng thành công
                    @else
                        Đơn đặt hàng đang được xử lý
                    @endif
                </div>
                <div class="complete-info-txt">Thông tin đơn hàng của quý khách</div>
                <div class="order-code">Mã đơn hàng: {{ $order->nhanh_order_id }}</div>
                <div class="order-name">
                    Tên sản phẩm: {{ $productName }}
                </div>
                <div class="order-cost">
                    Tổng tiền: {{ number_format($totalCost , 0, ',', '.') }} đ
                </div>
                <div class="welcome-txt">Rất hân hạnh được phục vụ bạn</div>
                <div class="list-btn">
                    <a href="{{ route('getHome') }}">Tiếp tục mua sản phẩm</a>
                    <a href="{{ route('user.order-details', $order->id) }}">Chi tiết đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
