@extends('layout.index')
@section('title','用户列表')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    {{session('success')}} <a href="#" class="alert-link"></a>.
</div>
@endif            
<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-heading">

        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="dataTable_wrapper">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="dataTables-example_wrapper">
                    <form action="{{url('/admin/user/index')}}" method="get">
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="dataTables-example_length" class="dataTables_length">
                                    <label>显示 
                                        <select name="num" class="form-control input-sm" aria-controls="dataTables-example" name="dataTables-example_length">
                                            <option value="10"
                                                    @if(!empty($request['num']) && $request['num']==10)
                                                    selected
                                                    @endif    
                                                    >10</option>
                                            <option value="25"
                                                    @if(!empty($request['num']) && $request['num']==25)
                                                    selected
                                                    @endif  
                                                    >25</option>
                                            <option value="50"
                                                    @if(!empty($request['num']) && $request['num']==50)
                                                    selected
                                                    @endif  
                                                    >50</option>
                                            <option value="100"
                                                    @if(!empty($request['num']) && $request['num']==100)
                                                    selected
                                                    @endif  
                                                    >100</option>
                                            <option value="100" disabled>100000000</option>
                                        </select>
                                        条
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter" id="dataTables-example_filter">
                                    <label>关键字: &nbsp; <input 
                                            @if(!empty($request['keywords']))
                                            value="{{$request['keywords']}}"
                                            @endif
                                            name="keywords" aria-controls="dataTables-example" placeholder="" class="form-control input-sm" type="search">
                                            &nbsp;<button class="btn btn-primary">搜 索</button>
                                    </label></div>
                            </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table aria-describedby="dataTables-example_info" role="grid" class="table table-striped table-bordered table-hover dataTable no-footer" id="dataTables-example">
                            <thead>
                                <tr role="row">
                                    <th aria-label="Rendering engine: activate to sort column descending" aria-sort="ascending" style="width: 112.2px;" colspan="1" rowspan="1" aria-controls="dataTables-example" tabindex="0" class="sorting_asc">ID</th>
                                    <th aria-label="Browser: activate to sort column ascending" style="width: 131.2px;" colspan="1" rowspan="1" aria-controls="dataTables-example" tabindex="0" class="sorting">用户名</th>
                                    <th aria-label="Platform(s): activate to sort column ascending" style="width: 125.2px;" colspan="1" rowspan="1" aria-controls="dataTables-example" tabindex="0" class="sorting">邮箱</th>
                                    <th aria-label="Platform(s): activate to sort column ascending" style="width: 125.2px;" colspan="1" rowspan="1" aria-controls="dataTables-example" tabindex="0" class="sorting">状态</th>
                                    <th aria-label="Engine version: activate to sort column ascending" style="width: 94.2px;" colspan="1" rowspan="1" aria-controls="dataTables-example" tabindex="0" class="sorting">头像</th>
                                    <th aria-label="CSS grade: activate to sort column ascending" style="width: 69px;" colspan="1" rowspan="1" aria-controls="dataTables-example" tabindex="0" class="sorting">操作</th></tr>
                            </thead>
                            <tbody>   
                                <!--class="gradeA even"   class="gradeA odd"-->
                                @foreach($users as $k=>$v)
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1 sid">{{$v->id}}</td>
                                    <td>{{$v->username}}</td>
                                    <td>{{$v->email}}</td>
                                    <td class="center">{{str_replace([1,2],['未激活','激活'],$v->status)}}</td>
                                    <td class="center"><img width="50px" src="{{$v->pic}}"></td>
                                    <td class="center">
                                        <button type="button" class="btn btn-warning btn-circle btn_delete"><i class="fa fa-times"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="/admin/user/edit?id={{$v->id}}" class="btn btn-primary btn-circle"><i class="fa fa-list"></i></a></td>
                                </tr>    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8" >
                        <div id="dataTables-example_paginate" class="dataTables_paginate paging_simple_numbers">
                            {!! $users->appends($request)->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->
</div>
@endsection
@section('myjs')
<script type="text/javascript">
    $('.btn_delete').click(function () {
//        alert(4);
        var id = $(this).parents('tr').find('.sid').html();
        var btn = $(this);
        /**
         * ajax post请求 会出现 Token错误 csrf保护问题  500
         * 1,在header 中 添加下面标签
         * <meta name="csrf-token" content="{{ csrf_token() }}">
         * 2,设置ajax全局配置 
         * $.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
         });
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //发送ajax post请求
        $.post('{{url("/admin/user/ajaxdelete")}}', {id: id}, function (data) {
//            console.log(data); 
               if(data == 1 ){
//                   $(this).parents('tr').remove();  X
                       btn.parents('tr').remove();
               }
        });
    })
</script>
@endsection