@extends('metronic_admin.layouts.app')

@section('add_categories_active', 'active')
@section('page_title', 'Tạo danh mục mới')

@section('content')

<div class="row">
  <div class="col-md-12">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-red-sunglo">
          <i class="icon-settings font-red-sunglo"></i>
          <span class="caption-subject bold uppercase"> Tạo danh mục mới</span>
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
        <form action="{{ route('adMpostAddCategory') }}" method="POST" id="create-new" class="form-create">
          {{ csrf_field() }}
          <div class="form-body">
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-title" name="title" required="">
              <label for="form-title">Tên danh mục</label>
            </div>
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-slug" name="slug" required="">
              <label for="form-slug">Link thân thiện</label>
            </div>
            <div class="form-group form-md-line-input has-success">
              <input type="number" class="form-control" id="form-pos" name="pos">
              <label for="form-title">Thứ tự hiển thị</label>
            </div>
            <div class="form-group form-md-line-input has-success">
                <label class="control-label">Danh mục cha</label>
                
                    <select class="bs-select form-control" name="parentId" id="parentId">
                        <option value="" selected>(Không có danh mục cha)</option>
                        @foreach($parentCate as $key=>$item)
                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
            </div>
          </div>
          <div class="form-actions noborder">
            <input type="reset" value="RESET" class="btn btn-secondary" />

            <button type="submit" class="btn blue">Submit</button>
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
@endsection