<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
  protected $table = 'product_color';

  /**
     * The attributes that are mass assignable.
     *
     * @var array
   */
  protected $fillable = [
    'product_id', 'color_id', 'image'
  ];

  public function insertProductColor($data){
  	return ProductColor::create($data);
  }
  public function getListProductColorByProduct($productId){
  	return ProductColor::where('product_id', '=', $productId)
      ->leftJoin('colors', 'colors.id', '=', 'product_color.color_id')
      ->select('product_color.product_id', 'product_color.color_id', 'product_color.image', 'colors.code')->get();
  }
  public function deleteProductColorByProduct($id){
  	return ProductColor::where('product_id', '=', $id)->delete();
  }
}
