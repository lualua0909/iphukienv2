<?php

namespace App\Http\Controllers\User;

use App\Cate;
use App\Http\Controllers\Controller;
use App\Mail;
use App\Product;
use App\ProductColor;
use App\SaleProduct;
use App\StaticPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function changeLanguage($language)
    {
        \Session::put('website_language', $language);

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $data = [];
        $this->data['cates'] = Cate::take(5)->get();
        $this->data['proNew'] = Product::take(8)->orderBy('created_at', 'desc')->get();
        $this->data['proTopSold'] = Product::take(8)->orderBy('sold', 'desc')->get();
        $flashSale = (new SaleProduct())->getListValidSaleProduct();
        $productColorModel = new ProductColor();
        $this->data['flashSale'] = [];
        foreach ($flashSale as $i) {
            $obj = new \stdClass();
            $obj->img = asset('public/' . $productColorModel->getFirstImage($i->product_id)->image);
            $obj->product = $i;
            array_push($this->data['flashSale'], $obj);
        }

        return view('user.home', $this->data);
    }

    public function postContact(Request $req)
    {
        $m = new Mail;
        $m->email = $req->email;
        $m->created_at = date('Y-m-d');
        $m->save();

        toast()->success('Đăng ký email thành công');
        return redirect()->back();
    }

    public function getStaticPage($url)
    {
        $data = StaticPage::where('url', $url)->first();
        if ($data) {
            $this->data['data'] = $data;
            return view('user.blank', $this->data);
        } else {
            return redirect()->route('getHome');
        }
    }
}
