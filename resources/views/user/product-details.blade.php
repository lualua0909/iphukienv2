@extends('layouts.app')

@section('title', 'Tên của sản phẩm')

@section('header')
@include('layouts.header', ['status' => 'complete'])
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('public/assets/styles/iphukien/user/ipk-breadcrumb.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/styles/iphukien/user/product-details.css') }}">
<link rel="stylesheet" href="{{ asset('public/iphukien/user/list-product.css') }}">
@endsection

@section('fb-meta-tags')
<meta property="og:url" content="{{ url()->full() }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $product->name }}" />
<meta property="og:description" content="{{ $product->full_description }}" />
<meta property="og:image" content="{{ asset('public/assets/images/demo/watch.png') }}" />
@endsection

@section('content')
<div class="ipk-container product-breadcrumbs">
    <div class="ipk-content-container">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="{{ route('getHome') }}" class="breadcrumb">Trang chủ</a>
                    <a href="{{ route('categories.show', ['id' => $product->category_id]) }}" class="breadcrumb">{{ $product->category_name }}</a>
                    <a href="javascript:void(0)" class="breadcrumb">{{$product->name}}</a>
                </div>
            </div>
        </nav>
    </div>
</div>
<div class="ipk-container product-container">
    <div class="row ipk-content-container">

        @if(count($listImage) > 0)
        <div class="col l1 s3 list-thumb-wrapper">
            <div class="img-block">
                <a href="#!" class="up"></a>
                <div class="thumbs-wrapper">
                    <div class="thumbs">

                        @foreach($listImage as $item)
                        <span style="background-image: url({{ $item }})"
                            data-img="{{ $item }}"></span>
                        @endforeach

                    </div>
                </div>
                <a href="#!" class="down"></a>
            </div>
            <div class="video-icon"
                data-video="{{ $product->video }}">
                <a href="#!"></a>
            </div>
        </div>
        @else
        <div class="col l1 s3 list-thumb-wrapper">
            <div class="img-block">
                <a href="#!" class="up"></a>
                <div class="thumbs-wrapper">
                    <div class="thumbs">
                        <span style="background-image: url({{ asset('public/assets/images/header/logo.svg') }})"
                            data-img="{{ asset('public/assets/images/header/logo.svg') }}"></span>
                    </div>
                </div>
                <a href="#!" class="down"></a>
            </div>
            <div class="video-icon"
                data-video="{{ $product->video }}">
                <a href="#!"></a>
            </div>
        </div>
        @endif
        <div class="col l6 s9 main-image-wrapper">
            <span class="full-screen-btn modal-trigger" href="#list-image-popup"></span>
            @if(count($listImage) > 0)
            <div class="main-image is-image" style="background-image: url({{ $listImage[0] }})"></div>
            @else
            <div class="main-image is-image" style="background-image: url({{ asset('public/assets/images/header/logo.svg') }})"></div>
            @endif
        </div>
        <div class="col l5 product-infos">
            <div class="name" id="c-product-name">{{$product->name}}</div>
            <div class="description">{{$product->short_description}}</div>
            @if($product->tag_id != 0)
            <div class="list-tags">
                @if($product->tag_id == 11)
                    <span class="tag hang-moi"></span>
                @elseif($product->tag_id == 12)
                    <span class="tag ban-chay"></span>
                @elseif($product->tag_id == 13)
                    <span class="tag giam-gia"></span>
                @endif
            </div>
            @endif

            <div class="price">
                <div class="origin" id="origin-price">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                <div class="sale" id="sale-price">{{ number_format($product->sale_price, 0, ',', '.') }}đ <span>Giảm {{ round(($product->price-$product->sale_price) / $product->price * 100) }}%</span></div>
            </div>
            <div class="status-wrapper">
                <div class="status-label">Tình trạng</div>
                <div class="list-status">
                    <span class="status {{ $product->status_id == 11 ? 'het-hang' : '' }}"></span>
                    <span class="status {{ $product->status_id == 12 ? 'dat-truoc' : '' }}"></span>
                    <span class="status {{ $product->status_id == 13 ? 'con-hang' : '' }}"></span>
                </div>
            </div>
            <div class="sizes-wrapper">
                <div class="color-label">Màu sắc</div>
                <div class="colors">
                    @foreach($listColor as $item)
                    <span class="color"
                        data-colorname="{{ $item->name }}"
                        data-colorid="{{ $item->id }}"
                    >
                        {{ $item->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            <div class="sizes-wrapper">
                <div class="color-label">Kích thước <a href="{{ url('huong-dan-chon-size') }}" target="_blank"  style="text-transform:none;font-weight:400"> (Hướng dẫn chọn size)</a></div>
                <div class="sizes">
                    @foreach($listSize as $item)
                    <span class="size" 
                        data-sizename="{{ $item->name }}"
                        data-sizeid="{{ $item->id }}"
                    >
                        {{ $item->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            <div class="pre-order-block">
                <div class="quantity-input">
                    <span class="decrease-detail">-</span>
                    <input type="number" class="quantity" value="1" id="quantity-detail" />
                    <span class="increase-detail">+</span>
                </div>
                <a href="#!" class="add-to-card-btn-detail" >Thêm vào giỏ hàng</a>
                <a href="#!" class="buy-now-btn" id="buy-now-btn-detail" style="width:180px">Mua ngay</a>
            </div>
        </div>
    </div>
    <div class="product-rating ipk-content-container">
        <div class="fb-share-button" data-href="{{ url()->full() }}" data-layout="button_count"></div>
        <div class="add-wishlist-button" data-type="{{ !isset($wishlist) ? 'add-wishlist' : 'cancel-wishlist' }}">{{ !isset($wishlist) ? 'Thêm vào yêu thích' : 'Hủy yêu thích' }}</div>
    </div>
    <div class="product-descriptions ipk-content-container">
        <div class="title">Mô tả sản phẩm</div>
        <div class="content">
            {{ $product->full_description }}
        </div>
    </div>
    <div class="same-products-block ipk-content-container">
        <p class="block-title">Sản phẩm tương tự</p>
        @include('layouts.list-product', ['listProduct' => $listSameProduct, 'hasReadMore' => false])
    </div>
</div>
@include('layouts.quickview')
<!-- Modal Structure -->
<div id="list-image-popup" class="modal">
    <div class="modal-content">
        <a href="#!" class="modal-close close-list-image-popup"></a>
        @if(count($listImage) > 0)
        <div class="main-image-popup" style="background-image: url({{ $listImage[0] }})"></div>
       @endif
        <div class="list-thumbs-popup">
            <div class="img-block-popup">
                <a href="#!" class="arrow-left"></a>
                <div class="thumbs-wrapper-popup">
                    <div class="thumbs-popup">
                        @if(count($listImage) > 0)
                            @foreach($listImage as $item)
                            <span style="background-image: url({{ $item }})"
                                data-img="{{ $item }}"></span>
                            @endforeach
                        @endif
                    </div>
                </div>
                <a href="#!" class="arrow-right"></a>
            </div>
            <div class="video-icon-popup">
                <a href="#!"></a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('public/assets/scripts/iphukien/user/list-product.js') }}"></script>
<script>
let chooseProduct;
$(document).ready(function () {
    var elems = document.querySelectorAll('#list-image-popup');
    M.Modal.init(elems, {
        'onOpenEnd': calcListThumbsWidth
    });

    function calcListThumbsWidth() {
        let w = $('.thumbs-popup span').length * 122 + ($('.thumbs-popup span').length - 1) * 18;
        $('.thumbs-popup')[0].style.width = w + 'px';
    }

});
$(document).on("click", ".list-thumb-wrapper .up", function () {
    if ($(".thumbs")[0].offsetHeight - $(".thumbs span:not(.hide)")[0].offsetHeight <= 470) return;
    $.each($(".thumbs span"), function (index, value) {
        if (!value.classList.contains('hide')) {
            value.classList.add('hide');
            return false;
        }
    });
});
$(document).on("click", ".list-thumb-wrapper .down", function () {
    if ($(".thumbs span.hide").length == 0) return;
    $(".thumbs span.hide")[$(".thumbs span.hide").length - 1].classList.remove('hide');
});
$(document).on("click", ".color", function () {
    $('.color').removeClass('active');
    $(this).addClass('active');
    if($('.size.active').length > 0) {
        let sizeId = $('.size.active')[0].dataset.sizeid;
        let colorId = $('.color.active')[0].dataset.colorid;
        $.ajax({
            url: `{{route('ajax.get-child-product')}}`,
            type: 'get',
            data: {
                'productId': '{{$product->id}}',
                'colorId': colorId,
                'sizeId': sizeId,
                '_token': `{{ csrf_token() }}` }
        }).done(function (data) {
            chooseProduct = JSON.parse(data);
            console.log(chooseProduct);
            if(chooseProduct.product != null) {
                $('.main-image').addClass('is-image');
                $('.main-image').html('');
                $('.main-image')[0].style.backgroundImage = "url(" + chooseProduct.image + ")";
                $('#c-product-name').html(chooseProduct.product.name);
                $("#origin-price").html(chooseProduct.product.price + 'đ');
                $("#sale-price").html(chooseProduct.product.sale_price + 'đ');
            } else {
                M.toast({
                    html: 'Sản phẩm không tồn tại',
                    classes: 'add-cart-fail'
                })
            }

        })
        .fail(function () {
            M.toast({
                html: 'Sản phẩm không tồn tại',
                classes: 'add-cart-fail'
            })
        })
    }
});
$(document).on("click", ".size", function () {
    $('.size').removeClass('active');
    $(this).addClass('active');

    if($('.color.active').length > 0) {
        let sizeId = $('.size.active')[0].dataset.sizeid;
        let colorId = $('.color.active')[0].dataset.colorid;
        $.ajax({
            url: `{{route('ajax.get-child-product')}}`,
            type: 'get',
            data: {
                'productId': '{{$product->id}}',
                'colorId': colorId,
                'sizeId': sizeId,
                '_token': `{{ csrf_token() }}` }
        }).done(function (data) {
            chooseProduct = JSON.parse(data);
            console.log(chooseProduct);
            if(chooseProduct.product != null) {
                $('.main-image').addClass('is-image');
                $('.main-image').html('');
                $('.main-image')[0].style.backgroundImage = "url(" + chooseProduct.image + ")";
                $('#c-product-name').html(chooseProduct.product.name);
                $("#origin-price").html(numberWithCommas(chooseProduct.product.price) + 'đ');
                $("#sale-price").html(numberWithCommas(chooseProduct.product.sale_price) + 'đ');
            } else {
                M.toast({
                    html: 'Sản phẩm không tồn tại',
                    classes: 'add-cart-fail'
                })
            }
        })
        .fail(function () {
            M.toast({
                html: 'Sản phẩm không tồn tại',
                classes: 'add-cart-fail'
            })
        })
    }
});
$(document).on("click", ".decrease-detail", function () {
    if ($('.quantity').val() == 0) return;
    $('.quantity').val(parseInt($('.quantity').val()) - 1)
});
$(document).on("click", ".increase-detail", function () {
    $('.quantity').val(parseInt($('.quantity').val()) + 1)
});
$(document).on("click", ".list-thumbs-popup .arrow-left", function () {
    if ($(".thumbs-popup span.hide").length == 0) return;
    $(".thumbs-popup span.hide")[$(".thumbs-popup span.hide").length - 1].classList.remove('hide');
    let w = $('.thumbs-popup span:not(.hide)').length * 122 + ($('.thumbs-popup span:not(.hide)').length - 1) * 18;
    $('.thumbs-popup')[0].style.width = w + 'px';
    $('.thumbs-popup span:not(.hide)')[1].style.marginLeft = '18px';
});
$(document).on("click", ".list-thumbs-popup .arrow-right", function () {
    if ($(".thumbs-popup")[0].offsetWidth <= $(".thumbs-wrapper-popup")[0].offsetWidth) return;
    $.each($(".thumbs-popup span"), function (index, value) {
        if (!value.classList.contains('hide')) {
            value.classList.add('hide');
            // reset width
            let w = $('.thumbs-popup span:not(.hide)').length * 122 + ($('.thumbs-popup span:not(.hide)').length - 1) * 18;
            $('.thumbs-popup')[0].style.width = w + 'px';
            $('.thumbs-popup span:not(.hide)')[0].style.marginLeft = '0'
            // return
            return false;
        }
    });
});
$(document).on("click", ".thumbs-popup span", function () {
    $('.main-image-popup')[0].style.backgroundImage = "url(" + $(this).data('img') + ")";
});

$(document).on("click", ".add-wishlist-button", function () {
    $.ajax({
        url: `{{route('ajax.add-to-wishlist')}}`,
        type: 'post',
        data: { 'productId': '{{$product->id}}', 'type': $(this).data('type'), '_token': `{{ csrf_token() }}` }
    }).done(function (data) {
        console.log(data)
        if (JSON.parse(data).code == 1) {
            M.toast({
                html: 'Cập nhật thành công',
                classes: 'add-cart-success'
            })
            location.reload();
        } else {
            let mes = JSON.parse(data).message ? JSON.parse(data).message : 'Cập nhật không thành công';
            M.toast({
                html: mes,
                classes: 'add-cart-fail'
            })
        }
    })
    .fail(function () {
        alert('Cập nhật thất bại')
    })

});


$(document).on("click", ".thumbs span", function () {
    $('.main-image').addClass('is-image');
    $('.main-image').html('');
    $('.main-image')[0].style.backgroundImage = "url(" + $(this).data('img') + ")";
});
$(document).on("click", ".custom-fb-share-button", function () {
    $('.fb-share-button').trigger( "click" );
});
$(document).on("click", ".video-icon", function () {
    $(".ipk-preloader").removeClass('hide');
    let str = `<iframe class="yt-player" src="${$(this).data('video')}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
    $('.main-image')[0].style.backgroundImage = 'unset';
    $('.main-image').removeClass('is-image');
    $('.main-image').html(str);
    $('.yt-player').height($('.yt-player').width());
    $(".ipk-preloader").addClass('hide');
});

function updateCart() {
    let listSizeElement = $(".sizes .size.active");
    if(listSizeElement.length == 0) {
        M.toast({
            html: 'Vui lòng chọn màu sác - kích thước',
            classes: 'add-cart-fail'
        })
        return false;
    }
    let listColorElement = $(".colors .color.active");
    if(listColorElement.length == 0) {
        M.toast({
            html: 'Vui lòng chọn màu sác - kích thước',
            classes: 'add-cart-fail'
        })
        return false;
    }
    if(chooseProduct) {
        console.log(chooseProduct)
        let productid = chooseProduct.product.id;
        let choosenSize = $('.sizes .size.active')[0].dataset.sizename;
        let choosenColor = $('.colors .color.active')[0].dataset.colorname;
        let price = chooseProduct.product.sale_price;
        let nhanhProductId = chooseProduct.product.product_id_nhanh;
        let img = chooseProduct.image;
        let prodName = chooseProduct.product.name;
        let quantity = $("#quantity-detail").val() == 0 ? 1 : $("#quantity-detail").val();
        let cart = localStorage.getItem('ipk_cart') ? JSON.parse(localStorage.getItem('ipk_cart')) : {};

        if(cart[productid]) {
            cart[productid].quantity = parseInt(cart[productid].quantity) + parseInt(quantity);
        } else {
            cart[productid] = {
                color: choosenColor,
                size: choosenSize,
                quantity: quantity,
                salePrice: price,
                image: img,
                name: prodName,
                nhanhPorductId: nhanhProductId,
            };
        }
        localStorage.setItem('ipk_cart',  JSON.stringify(cart));
        return true;
    } else {
        M.toast({
            html: 'Sản phẩm không tồn tại',
            classes: 'add-cart-fail'
        })
    }

}
$(document).on("click","#buy-now-btn-detail",function() {
    if(!updateCart()) return;

    window.location.href = "{{ route('user.cart') }}";
});
$(document).on("click",".add-to-card-btn-detail",function() {
    let updateRes = updateCart();
    if(updateRes) {
        M.toast({
            html: 'Cập nhật giỏ hàng thành công',
            classes: 'add-cart-success'
        });
    }

});
</script>
@endsection
