<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helpers;
use App\User;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Wishlist;
use App\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AjaxController extends Controller
{
    public function getLocation(Request $request, $type, $parentId = null) {
        return json_encode(Helpers::callNhanhApi([
            "type" => $type,
            "parentId" => $parentId
        ], "/shipping/location"));
    }

    public function loginWithSocial(Request $request) {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $res = false;

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // Authentication passed...
            if (Auth::user()->role_id == 2) {
                return json_encode(['code' => 1]);
            }
        } else {
            $userModal = new User();
            $userCheck = $userModal->getUserByEmail($email);
            if($userCheck != null) {
                Auth::loginUsingId($userCheck->id);
            } else {
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'role_id' => 2,
                ]);
                Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);
            }
            
            return json_encode(['code' => 1]);
        }

        return json_encode(['code' => 0, 'mesmessages' => 'Đăng nhập không thành công']);
    }

    public function calcShippingFee(Request $request) {
        $resArray = Helpers::callNhanhApi([
            'fromCityName' => 'Hồ Chí Minh',
            'fromDistrictName' => 'Quận Phú Nhuận',
            'toCityName' => $request->input('toCityName'),
            'toDistrictName' => $request->input('toDistrictName'),
            'codMoney' => $request->input('codMoney'),
            'productIds' => $request->input('productIds')
        ], '/shipping/fee');
        $returnItem = NULL;
        foreach($resArray as $item) {
            if(!$returnItem) {
                $returnItem = $item;
            } else {
                if($returnItem->shipFee > $item->shipFee) {
                    $returnItem = $item;
                }
            }
        }

        return json_encode($returnItem);
    }

    public function createOrder(Request $request) {
        if (!Auth::check() || Auth::user()->role_id != 2) {
            return json_encode(['code' => 0, 'message' => 'Vui lòng đăng nhập']);
        }
        // create order
        $addressId = $request->input('addressId');
        $paymentMethodId = $request->input('paymentMethodId');
        $deliveryId = $request->input('deliveryId');
        $shipFee = $request->input('shipFee');
        $deliveryDate = $request->input('deliveryDate');
        $listProduct = json_decode($request->input('productList'));
        
        $dataInsertOrder = [
            'address_id' => $addressId,
            'payment_method_id' => $paymentMethodId,
            'delivery_id' => $deliveryId,
            'ship_fee' => $shipFee,
            'delivery_date' => $deliveryDate,
            'status' => 'New',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'order_code' => date('YmdHis') . '_' . Auth::user()->id,
            'user_id' => Auth::user()->id
        ];
        $addedOrder = (new Order())->insertOrder($dataInsertOrder);
        $addedOrderCode = $addedOrder->order_code;
        $addedOrderId = $addedOrder->id;

        foreach ($listProduct as $product) {
            $dataInsertDetail = [
                'order_id' => $addedOrderId,
                'product_id' => $product->id,
                'total_price' => $product->quantity * $product->price,
                'total_count' => $product->quantity,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            (new OrderDetail())->insertOrderDetail($dataInsertDetail);
        }
        //call nhanh
        $nhanhRes = Helpers::callNhanhApi([
            'id' => $addedOrderCode,
            'type' => 'Shipping',
            'customerCityName' => $request->input('customerCityName'),
            'customerDistrictName' => $request->input('customerDistrictName'),
            'customerWardLocationName' => $request->input('customerWardLocationName'),
            'customerAddress' => $request->input('customerAddress'),
            'customerName' => $request->input('customerName'),
            'customerMobile' => $request->input('customerMobile'),
            'customerEmail' => $request->input('customerEmail'),
            'paymentMethod' => 'COD',
            'carrierId' => $request->input('carrierId'),
            'status' => 'New',
            'productList' => $listProduct,
            'customerShipFee' => $shipFee
        ], '/order/add');
        //update nhanh order id
        (new Order())->updateOrder($addedOrderId, ['nhanh_order_id' => $nhanhRes->$addedOrderCode]);
        //send mail

        return json_encode(['code' => 1, 'orderId' => $addedOrderId]);
    }

    public function getQuickViewProduct(Request $request, $productId) {
        $data = [];
        $data['product'] = (new Product())->getProductById($productId);
        $listChildProduct = (new Product())->getListChildProduct($productId);
        $productImageModel = new ProductImage();
        $data['listChildProduct'] = [];
        $data['listImage'] = [];
        foreach($listChildProduct as $p) {
            $obj = new \stdClass();
            $obj->product = $p;
            $obj->listImage = $productImageModel->getListProductImageByProduct($p->id);
            foreach($obj->listImage as $i) {
                array_push($data['listImage'], asset('public/'.$i->image));
            }
            array_push($data['listChildProduct'], $obj);
        }
        if (Auth::check() && Auth::user()->role_id == 2) {
            $data['wishlist'] = (new Wishlist())->getWishlistByUserAndProduct(Auth::user()->id, $productId);
        }

        return json_encode($data);
    }

    public function addToWishlist(Request $request) {
        if (Auth::check() && Auth::user()->role_id == 2) {
            if($request->input('type') == 'add-wishlist') {
                $dataInsert = [
                    'product_id' => $request->input('productId'),
                    'user_id' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                (new Wishlist())->insertWishlist($dataInsert);
            } else {
                (new Wishlist())->deleteWishlist(Auth::user()->id, $request->input('productId'));
            }

            return json_encode(['code' => 1]);
        }
        
        return json_encode(['code' => 0, 'message' => 'Vui lòng đăng nhập']);
    }

    public function getChildProduct(Request $request) {
        $obj = new \stdClass();
        $obj->product = (new Product())->getChildProductByParentSizeColor(
            $request->input('productId'), 
            $request->input('sizeId'), 
            $request->input('colorId')
        );
        if($obj->product != null) {
            $listImg = (new ProductImage())->getListProductImageByProduct($obj->product->id);
            $obj->image = count($listImg) > 0 ? asset('public/'.$listImg[0]->image) : asset('public/assets/images/header/logo.svg');
        }

        return json_encode($obj);
    }

    public function doChangePassword(Request $request) {
        if (!Auth::check() || Auth::user()->role_id != 2) {
            return json_encode(['code' => 0, 'message' => 'Vui lòng đăng nhập']);
        }
        $cUser = (new User())->getUserById(Auth::user()->id);
        if(isset($cUser) && Hash::check($request->input('currentPass'), $cUser->password)) {
            (new User())->updateUser(Auth::user()->id, ['password' => bcrypt($request->input('newPass'))]);

            return json_encode(['code' => 1]);
        } 

        return json_encode(['code' => 0, 'message' => 'Mật khẩu hiện tại không đúng']);
    }

}
