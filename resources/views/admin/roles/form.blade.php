@extends('admin.app')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Roles</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/roles') }}">Roles</a></li>
                    <li class="breadcrumb-item active">{!!$action!!} Role</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                        {!!$action!!} Role
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{!! url('/admin/roles') !!}" method="post" name="settingsForm" id="settingsForm">
                        <div class="card-body">

                            @csrf
                            <input type="hidden" name="id" value="{!!@$role->id!!}">
                            <input type="hidden" name="action" value="{!!$action!!}">

                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name ?? '' }}" maxlength="255" aria-describedby="name" required />
                                    @error('name')
                                    <div id="name-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="guard_name" value="admin"  />
                            
                            <div class="form-group">
                                <label for="name">Permissions</label>
                                @foreach ($permissions as $permission)
                                @if(isset($assignedPermission) && in_array($permission->id, $assignedPermission))
                                @php
                                $check = 'checked';
                                @endphp
                                @else
                                @php
                                $check = '';
                                @endphp
                                @endif
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="permissions{{$permission->id ?? '' }}" value="{{$permission->id ?? '' }}" name="permissions[]" {{$check}} required>
                                    <label for="permissions{{$permission->id ?? '' }}" class="custom-control-label">{{ucfirst($permission->name)}}</label>
                                </div>
                                @error('permissions')
                                <div id="permissions-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                @enderror
                                @endforeach
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6"></div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection