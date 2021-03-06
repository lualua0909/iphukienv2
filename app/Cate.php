<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
  protected $table = 'categories';

  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'title', 'slug', 'pos', 'parent_id', 'image', 'meta_title', 'meta_des', 'meta_url', 'meta_image', 'meta_keywords'
  ];

  public function insertCate($data){
  	return Cate::create($data);
  }

  public function updateCate($id,$data){
  	return Cate::where('id', '=', $id)->update($data);
  }

  public function getCateById($id){
  	return Cate::where('id', '=', $id)->first();
  }
  public function getCateBySlug($slug){
    return Cate::where('slug', '=', $slug)->first();
  }
  public function getListCate(){
  	return Cate::orderBy('created_at','desc')->get();
  }
  public function deleteCate($id){
  	return Cate::where('id', '=', $id)->delete();
  }
  public function getListCateRelate($slug){
    return Cate::where('slug', '!=', $slug)->orderBy('created_at','desc')->limit(8)->get();
  }
  public function getListParentCate(){
  	return Cate::whereNull('parent_id')->get();
  }
}
