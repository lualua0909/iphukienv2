@extends('metronic_admin.layouts.app')

@section('add_roles_active', 'active')
@section('page_title', 'Tạo vai trò')

@section('content')

<div class="row">
  <div class="col-md-12">
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-red-sunglo">
          <i class="icon-settings font-red-sunglo"></i>
          <span class="caption-subject bold uppercase"> Tạo vai trò</span>
        </div>
        <div class="actions">
          <div class="btn-group">
            <a class="btn btn-sm green dropdown-toggle" href="javascript:;" data-toggle="dropdown">
              Danh sách vai trò
            </a>
          </div>
        </div>
      </div>
      <div class="portlet-body form">
        <form action="{{ route('adMpostAddRole') }}" method="POST" id="create-new" class="form-create">
          {{ csrf_field() }}
          <div class="form-body">
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-name" name="name" required="">
              <label for="form-name">Tên vai trò</label>
            </div>
            <div class="form-group form-md-line-input has-success">
              <input type="text" class="form-control" id="form-display-name" name="display-name" required="">
              <label for="form-display-name">Tên hiển thi</label>
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