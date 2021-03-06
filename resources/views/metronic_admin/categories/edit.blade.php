@extends('metronic_admin.layouts.app')

@section('edit_categories_active', 'active')

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase"> Chỉnh sửa danh mục</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn btn-sm green dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                            Danh sách danh mục
                        </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body form">
                <form action="{{route('adMpostEditCategory', ['id' => $cate->id])}}" method="POST" id="create-new"
                    class="form-create" enctype='multipart/form-data'>
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control" id="form-title" name="title" required="" value="{{ $cate->title }}">
                            <label for="form-title">Tên danh mục</label>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                            <input type="text" class="form-control" id="form-slug" name="slug" required="" value="{{ $cate->slug }}">
                            <label for="form-slug">Link thân thiện</label>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                            <input type="number" class="form-control" id="form-pos" name="pos" value="{{ $cate->pos }}">
                            <label for="form-title">Thứ tự hiển thị</label>
                        </div>
                        <div class="form-group form-md-line-input has-success">
                            <label class="col-sm-2 form-control-label">Hình đại diện</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="form-image" name="image">
                                <label class="custom-file-label" for="form-image">
                                    @if($cate->image != "")
                                        {{ substr($cate->image,0,strpos($cate->image,'?')) }}
                                    @else
                                        Choose file
                                    @endif
                                </label>
                            </div>
                            <img id="file-show" @if($cate->image != "")
                            src="{{ asset('/public/' .$cate->image) }}" @else
                            class="hidden" @endif >
                        </div>
                        <div class="form-group form-md-line-input has-success">
                            <label class="control-label">Danh mục cha</label>

                            <select class="bs-select form-control" name="parentId" id="parentId">
                                <option value="" {{$cate->parent_id == NULL ? 'selected' : ''}}>(Không có danh mục cha)</option>
                                @foreach($parentCate as $key=>$item)
                                <option value="{{ $item->id }}" {{$cate->parent_id == $item->id ? 'selected' : ''}}>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-title" name="meta_title"  value="{{ $cate->meta_title }}">
              <label for="form-title">meta title</label>
            </div>
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-title" name="meta_des"  value="{{ $cate->meta_des }}">
              <label for="form-title">meta description</label>
            </div>
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-title" name="meta_url"  value="{{ $cate->meta_url }}">
              <label for="form-title">meta url</label>
            </div>
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-title" name="meta_keywords"  value="{{ $cate->meta_keywords }}">
              <label for="form-title">meta keywords</label>
            </div>
            <div class="form-group form-md-line-input has-success">
                            <label class="col-sm-2 form-control-label">meta image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="form-image-meta" name="meta_image">
                                <label class="custom-file-label" for="form-image">
                                    @if($cate->meta_image != "")
                                        {{ substr($cate->meta_image,0,strpos($cate->meta_image,'?')) }}
                                    @else
                                        Choose file
                                    @endif
                                </label>
                            </div>
                            <img id="file-show-meta" @if($cate->meta_image != "")
                            src="{{ asset('/public/' .$cate->meta_image) }}" @else
                            class="hidden" @endif >
                        </div>


                    </div>
                    <div class="form-actions noborder">
                        <input type="reset" value="RESET" class="btn btn-secondary" />

                        <button type="submit" class="btn blue">CẬP NHẬT</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
@endsection

@section('admin_js')
<script src="{{ asset('public/metronic_assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/metronic_assets/pages/scripts/components-bootstrap-select.min.js') }}" type="text/javascript"></script>
<script>
$(document).on('change', "#form-image", function (evt) {
  var file = evt.target.files[0]

  //Get tmp path
  var tmp = URL.createObjectURL(event.target.files[0])
  //Get name extension
  var nameExtension = file.type

  //Check image file
  if (nameExtension.search('image') > -1 && file.size < (5 * 1024 * 1024)) {
    $(this).next('label').text(file.name)
    $("#file-show").attr('src', tmp)
    $("#file-show").removeClass('hidden')


  } else {
    alert("Vui lòng chọn hình có dung lượng nhỏ hơn 5MB", 0)
    $(this).next('label').text("Choose file")
    $("#file-show").addClass('hidden')
    $(this).val('')
  }

})
$(document).on('focusout', '#form-title', function () {
  $("#form-slug").val(changeToSlug($(this).val()))
})
</script>
<script>
$(document).on('change', "#form-image-meta", function (evt) {
  var file = evt.target.files[0]

  //Get tmp path
  var tmp = URL.createObjectURL(event.target.files[0])
  //Get name extension
  var nameExtension = file.type

  //Check image file
  if (nameExtension.search('image') > -1 && file.size < (5 * 1024 * 1024)) {
    $(this).next('label').text(file.name)
    $("#file-show-meta").attr('src', tmp)
    $("#file-show-meta").removeClass('hidden')


  } else {
    alert("Vui lòng chọn hình có dung lượng nhỏ hơn 5MB", 0)
    $(this).next('label').text("Choose file")
    $("#file-show").addClass('hidden')
    $(this).val('')
  }

})
</script>
@endsection
