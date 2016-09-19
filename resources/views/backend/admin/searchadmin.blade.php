@extends('backend.layouts.app')

@section('content')


    <div align="center"><h2>  Application Admins</h2></div>
    <section class="container">
        <div class="col-lg-3.9999 col-md-3 col-sm-3 col-xs-2 none"></div>
        <!-- <div  class="col-lg-6.5 col-md-6 col-sm-6 col-xs-7 none"> -->
        <div class="panel-body" style="background-color:white">

            <button class=" btn btn-primary"><a href="{{ Request::root() }}/backend/admin/addadmin">Add admin >></a></button>

            <form class="navbar-form"  role="form" method="get" action="{{ Request::root() }}/backend/admin/search">
                <div class="input-group">
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    <input type="text" class="form-control" placeholder="Search For a admin, Enter name or email" name="keyword" id="srch-term" size="80">

                    <div class="input-group-btn">
                        <button class="btn btn-default"  type="submit"  id="search"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </form>

            <script>
            </script>


            <table class="table table-striped table-bordered  table-hover" style="width:88%">
                <thead class="thead-inverse">
                <tr>
                    <th style="text-align: center">Admin Name</th>
                    <th style="text-align: center">Username</th>
                    <th style="text-align: center">Email</th>
                    <th style="text-align: center">Created at</th>
                    <th style="text-align: center">Type</th>
                    <th style="text-align: center">Actions</th>

                </tr>
                </thead>
                <tbody>

                @if(count($admins)>0)

                    @foreach($admins as $admin)
                        <tr>
                            <td><a href="">{{$admin->admin_name}}</a></td>
                            <td><a href="">{{$admin->user_name}}</a></td>
                            <td>{{$admin->email}}</td>
                            <td>{{$admin->created_at}}</td>
                            <td>
                                @if($admin->account_type_id==1)
                                    Root
                                @else
                                    Admin
                                @endif



                            </td>
                            <td><div class="btn-group">
                                    @if($admin->account_type_id==2)
                                        <button class="btn btn-primary"><a href="{{ Request::root() }}/backend/admin/editadmin?admin_id={{$admin->admin_id}}">Edit</a> </button>
                                        <button id="delete{{$admin->admin_id}}" class="btn btn-warning delete ">Delete</button>
                                        <script>

                                            $(function(){

                                                $("#delete{{$admin->admin_id}}").click(function () {
                                                    var $tr = $(this).closest('tr');
                                                    $.ajaxSetup(
                                                            {
                                                                headers:
                                                                {
                                                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                                                }
                                                            });
                                                    $.ajax({
                                                        type: "POST",
                                                        url : "{{ url()}}/backend/admin/deleteadmin",
                                                        data : {admin_id:'{{$admin->admin_id}}'},
                                                        success : function(data){
                                                            $tr.find('td').fadeOut(1000,function(){
                                                                $tr.remove();
                                                            });
                                                        },
                                                        error : function (errors) {
                                                            console.log(errors);
                                                        }
                                                    });

                                                });
                                            })
                                        </script>


                                        <button  id ={{$admin->admin_id}} type="button" class="@if($admin->is_active)
                                                ban
                                                @else
                                                unban

                                            @endif
                                                btn btn-danger" id={{$admin->admin_id}}>
                                            @if($admin->is_active)
                                                Suspend

                                            @else
                                                Activate
                                            @endif
                                        </button>



                                        <script>

                                            $(function(){
                                                $("#{{$admin->admin_id}}").click(function () {
                                                    $.ajaxSetup(
                                                            {
                                                                headers:
                                                                {
                                                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                                                }
                                                            });
                                                    $.ajax({
                                                        type: "POST",
                                                        url : "{{ url()}}/backend/admin/banchange",
                                                        data : {admin_id:'{{$admin->admin_id}}'},
                                                        success : function(data){
                                                            if($("#{{$admin->admin_id}}").hasClass('ban')){
                                                                $("#{{$admin->admin_id}}").removeClass('ban');
                                                                $("#{{$admin->admin_id}}").addClass('unban');
                                                                $("#{{$admin->admin_id}}").text('Activate')
                                                            }else {
                                                                $("#{{$admin->admin_id}}").removeClass('unban');
                                                                $("#{{$admin->admin_id}}").addClass('ban');
                                                                $("#{{$admin->admin_id}}").text('Suspend')

                                                            }
                                                        },
                                                        error : function (errors) {
                                                            console.log(errors);
                                                        }
                                                    });

                                                });
                                            })
                                        </script>
                                    @endif

                                </div></td>



                        </tr>
                    @endforeach
                @else
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        No admins found for this keyword
                    </div>

                @endif

                </tbody>
            </table>
            <div  align="center">
                {!! str_replace('/?', '?', $admins->appends(['keyword' => $keyword])->render()) !!}</div>
            {{--            <div  align="center">{!! $admins->render() !!}</div>--}}
        </div>
        </div>
        <!-- </div> -->

        </div>
    </section>

@endsection
