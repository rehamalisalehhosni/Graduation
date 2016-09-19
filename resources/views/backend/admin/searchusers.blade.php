{{--listusers.blade--}}
@extends('backend.layouts.app')

@section('content')

    <div align="center"><h2>   Search result for application Users</h2></div>

    <section class="container">
        <div class="col-lg-3.9999 col-md-3 col-sm-3 col-xs-2 none"></div>
        <!-- <div  class="col-lg-6.5 col-md-6 col-sm-6 col-xs-7 none"> -->
        <div class="panel-body" style="background-color:white">

            <form class="navbar-form"  method="get" action="{{ Request::root() }}/backend/users/search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search For a user, Enter name or email" name="keyword" id="srch-term" size="80">
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    <div class="input-group-btn">
                        <button class="btn btn-default"    type="submit" id="search"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </form>

            <script>
                {{--$(function(){--}}
                {{--$("#search").click(function () {--}}
                {{--var keyword=$('#srch-term').val();--}}
                {{--$.ajaxSetup(--}}
                {{--{--}}
                {{--headers:--}}
                {{--{--}}
                {{--'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')--}}
                {{--}--}}
                {{--});--}}
                {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url : "{{ url()}}/backend/users/search",--}}
                {{--data : {keyword:keyword},--}}
                {{--success : function(data){--}}

                {{--},--}}
                {{--error : function (errors) {--}}
                {{--console.log(errors);--}}
                {{--}--}}
                {{--});--}}

                {{--});--}}
                {{--})--}}

            </script>


            <table class="table" style="text-align: center">
                <thead class="thead-inverse">
                <tr>
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Username</th>
                    <th style="text-align: center">Email</th>
                    <th style="text-align: center">Mobile Number</th>
                    <th style="text-align: center">Number of Reports</th>
                    <th style="text-align: center">Actions</th>
                </tr>
                </thead>
                <tbody>

            @if(count($users)>0)
                @foreach($users as $user)
                    <tr>
                        <td><img src="{{ URL::to('/') }}/uploads/usersimages/original/{{$user->image}}"  alt=""></td>
                        <td><a href="">{{$user->first_name}}&nbsp{{$user->last_name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobile_number}}</td>
                        <td>{{$user->user_reports}}</td>
                        <td><div class="btn-group">
                                <button class="btn btn-primary"><a href="{{ Request::root() }}/backend/users/listreports?user_id={{$user->user_id}}">View reports</a> </button>

                                <button  id ={{$user->user_id}} type="button" class="@if($user->is_active)
                                        ban
                                        @else
                                        unban

                                    @endif
                                        btn btn-danger" id={{$user->user_id}}>
                                    @if($user->is_active)
                                        Suspend

                                    @else
                                        Activate
                                    @endif
                                </button>



                                <script>

                                    $(function(){
                                        $("#{{$user->user_id}}").click(function () {
                                            $.ajaxSetup(
                                                    {
                                                        headers:
                                                        {
                                                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                                        }
                                                    });
                                            $.ajax({
                                                type: "POST",
                                                url : "{{ url()}}/backend/users/banchange",
                                                data : {user_id:'{{$user->user_id}}'},
                                                success : function(data){
                                                    if($("#{{$user->user_id}}").hasClass('ban')){
                                                        $("#{{$user->user_id}}").removeClass('ban');
                                                        $("#{{$user->user_id}}").addClass('unban');
                                                        $("#{{$user->user_id}}").text('Activate')
                                                    }else {
                                                        $("#{{$user->user_id}}").removeClass('unban');
                                                        $("#{{$user->user_id}}").addClass('ban');
                                                        $("#{{$user->user_id}}").text('Suspend')

                                                    }
                                                },
                                                error : function (errors) {
                                                    console.log(errors);
                                                }
                                            });

                                        });
                                    })
                                </script>


                            </div></td>

                    </tr>
                @endforeach
             @else
                <div class="alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     No user found for this keyword
                </div>

             @endif

                </tbody>
            </table>
            <div  align="center"> <?php echo $users->appends(['keyword' => $keyword])->render(); ?></div>

{{--            <div  align="center">{!! $users->render() !!}</div>--}}
        </div>
        </div>
        <!-- </div> -->

        </div>
    </section>

@endsection
