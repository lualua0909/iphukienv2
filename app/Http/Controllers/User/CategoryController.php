<?php

namespace App\Http\Controllers\User;

use App\Cate;
use App\Color;
use App\Http\Controllers\Controller;
use App\Product;
use App\Size;
use App\Tag;
use App\Trademark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function show(Request $request, $id)
    {
        if ($request->sort) {
            switch ($request->sort) {
                case "az":
                    $this->data['listProduct'] = Product::where('category_id', $id)->orderBy('name', 'ASC')->paginate(5);
                    break;
                case "za":
                    $this->data['listProduct'] = Product::where('category_id', $id)->orderBy('name', 'DESC')->paginate(5);
                    break;
                case "pasc":
                    $this->data['listProduct'] = Product::where('category_id', $id)->orderBy('price', 'ASC')->orderBy('sale_price', 'ASC')->paginate(5);
                    break;
                case "pdesc":
                    $this->data['listProduct'] = Product::where('category_id', $id)->orderBy('price', 'DESC')->orderBy('sale_price', 'DESC')->paginate(5);
                    break;
                default:
                    $this->data['listProduct'] = Product::where('category_id', $id)->orderBy('id', 'DESC')->paginate(5);
            }

        } else {
            $this->data['listProduct'] = Product::where('category_id', $id)->orderBy('id', 'DESC')->paginate(5);
        }
        $this->data['category'] = (new Cate())->getCateById($id);
        $this->data['colors'] = (new Color())->getListColor();
        $this->data['sizes'] = (new Size())->getListSize();
        $this->data['trademarks'] = (new Trademark())->getListTrademark();
        $this->data['tags'] = (new Tag())->getListTags();

        $this->data['id'] = $id;

        if ($request->ajax()) {
            $listProduct = $this->data['listProduct'];
            return view('layouts.list-product', compact('listProduct'));
        }

        return view('user/category-details', $this->data);
    }

    public function searchAjax(Request $request, $id)
    {
        $data = $request->all();

        $product = Product::query();

        if (count(json_decode($data['colors'])) > 0) {
            $product = DB::table('products')
                ->join('product_color', 'products.id', 'product_color.product_id')
                ->join('colors', 'colors.id', 'product_color.color_id')
                ->whereIn('colors.id', json_decode($data['colors']));
        }

        if (count(json_decode($data['tags'])) > 0) {
            $product = DB::table('products')
                ->join('tags', 'tags.id', '=', 'products.tag_id')
                ->whereIn('products.tag_id', json_decode($data['tags']));
        }

        if (count(json_decode($data['sizes'])) > 0) {
            $product = DB::table('products')
                ->join('product_size', 'products.id', 'product_size.product_id')
                ->join('sizes', 'sizes.id', 'product_size.size_id')
                ->whereIn('sizes.id', json_decode($data['sizes']));
        }

        if (count(json_decode($data['trademarks'])) > 0) {
            $product = DB::table('products')
                ->join('trademarks', 'trademarks.id', 'products.trademark_id')
                ->whereIn('trademarks.id', json_decode($data['trademarks']));
        }

        if (count(json_decode($data['prices'])) > 0) {
            $product = DB::table('products')
                ->whereBetween('price', json_decode($data['prices']));
        }


        if (count(json_decode($data['tags'])) === 0 && count(json_decode($data['trademarks'])) === 0 && count(json_decode($data['sizes'])) === 0 && count(json_decode($data['colors'])) === 0 && count(json_decode($data['prices'])) === 0) {
            $product = Product::where('category_id', $id);
        } else {
            $product = $product->where('category_id', $id);
        }

        $listProduct = $product
            ->orderBy('products.id', 'DESC')
            ->paginate(5)
            ->appends(request()->query());

        $view = view('layouts.list-product', compact('listProduct'))->render();

        return response()->json(['currentPage' => $listProduct->currentPage(), 'total_page' => $listProduct->lastPage(), 'view' => $view], 200);
    }
}
