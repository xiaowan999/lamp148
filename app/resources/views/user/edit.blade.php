@extends('layout.index')
@section('title','用户修改')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">用户修改</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <!--显示闪存中的信息-->
            <!--                    @if(session('error'))
                                <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                           {{session('error')}}
                                </div>
                                @endif-->

            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif    
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <form role="form" action="{{url('admin/user/update')}}" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>用户名</label>
                            <input type="text" value="{{$user->username}}"  name="username" value="{{old('username')}}" class="form-control">
                            <p class="help-block"</p>
                        </div>

                        <div class="form-group">
                            <label>邮箱</label>
                            <input type="email" value="{{$user->email}}" name="email" value="{{old('email')}}" class="form-control">
                            <p class="help-block"</p>
                        </div>

                        <div class="form-group">
                            <label>手机号</label>
                            <input type="text" value="{{$user->phone}}" name="phone" value="{{old('phone')}}" class="form-control">
                            <p class="help-block"</p>
                        </div>
                        <input type="hidden" name="id" value="{{$user->id}}">
                        {{csrf_field()}}
                        <button class="btn btn-success" type="submit">修改用户</button>
                        <button class="btn btn-danger" type="reset">重新修改</button>
                    </form>
                </div>
                <!-- /.col-lg-6 (nested) -->

                <!-- /.col-lg-6 (nested) -->
            </div>
            <!-- /.row (nested) -->
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
@endsection