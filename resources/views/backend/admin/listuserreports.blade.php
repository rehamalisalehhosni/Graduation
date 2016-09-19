{{--listusers.blade--}}
@extends('backend.layouts.app')

@section('content')



                <div align="center"><b><strong><h2>Reported User info</h2></strong></b></div>
                <table class="table table-striped table-bordered  table-hover">
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



                    <tr>
                        <td><img src="{{ URL::to('/') }}/uploads/usersimages/original/{{$user->image}}"  alt=""></td>
                        <td><a href="">{{$user->first_name}}&nbsp{{$user->last_name}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobile_number}}</td>
                        <td>{{$user->user_reports}}</td>
                        <td><div class="btn-group">
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

                </tbody>
            </table>
            <br><br><br>


            <div align="center"><b><strong><h2>Reports</h2></strong></b></div>
            <table class="table" style="text-align: center">
                <thead class="thead-inverse">
                <tr>
                    <th style="text-align: center">Reporter Image</th>
                    <th style="text-align: center">Reporter Name</th>
                    <th style="text-align: center">Report Reason English</th>
                    <th style="text-align: center">Report Reason Arabic</th>
                    <th style="text-align: center">Created at</th>
                </tr>
                </thead>
                <tbody>


                @foreach($user_reports as $user_report)
                    <tr>
                        <td><img src="{{ URL::to('/') }}/uploads/usersimages/original/{{$user_report->image}}"  alt=""></td>
                        <td><a href="">{{$user_report->first_name}}&nbsp{{$user_report->last_name}}</a></td>
                        <td>{{$user_report->title_en}}</td>
                        <td>{{$user_report->title_ar}}</td>
                        <td>{{$user_report->created_at}}</td>


                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>
@endsection
