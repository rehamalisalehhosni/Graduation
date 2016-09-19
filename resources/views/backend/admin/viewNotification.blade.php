@extends('backend.layouts.app')

@section('content')

    <div >


    <ul class=" notifications" role="menu" aria-labelledby="dLabel">

        <div class="notification-heading"><B><h2 class="menu-title">Notifications</h2></B><h4 class="menu-title pull-right"></h4>
        </div>
        <li class="divider"></li>
        <div class="notifications-wrapper" id="notifications-wrapper">

            @foreach($users as $user)
                <a class="content" href="{{ Request::root() }}/backend/users/listreports?user_id={{$user->user_id}}">

                    <div class="notification-item">
                        <h4 class="item-title">a user with id {{$user->user_id}} has {{$user->report_count}} reports </h4>
                    </div>

                </a>

            @endforeach

                @foreach($service_place as $ser_place)
                    <a class="content" href="{{ Request::root() }}/backend/serviceplace/reports/{{$ser_place->reported_id}}" >

                        <div class="notification-item">
                            <h4 class="item-title">a service place with id {{$ser_place->reported_id}} has {{$ser_place->report_count}} reports </h4>
                        </div>

                    </a>
                @endforeach

                @foreach($service_place_review as $ser_place_rev)
                    <a class="content" href="{{ Request::root() }}/backend/serviceplace/reviewsreports/{{$ser_place_rev->reported_id}}" >

                        <div class="notification-item">
                            <h4 class="item-title">a service place review with id {{$ser_place_rev->reported_id}} has {{$ser_place_rev->report_count}} reports </h4>
                        </div>

                    </a>
                @endforeach

                @foreach($real_estate_ad as $real_estate)
                    <a class="content" href="{{ Request::root() }}/backend/realstate/reports/{{$real_estate->reported_id}}" >

                        <div class="notification-item">
                            <h4 class="item-title">a real estate with id {{$real_estate->reported_id}} has {{$real_estate->report_count}} reports </h4>
                        </div>

                    </a>
                @endforeach

                @foreach($real_estate_ad_comment as $real_estate_comm)
                    <a class="content" href="{{ Request::root() }}/backend/realstate/commentreports/{{$real_estate_comm->reported_id}}" >

                        <div class="notification-item">
                            <h4 class="item-title">a real estate ad comment with id {{$real_estate_comm->reported_id}} has {{$real_estate_comm->report_count}} reports </h4>
                        </div>

                    </a>
                @endforeach

                @foreach($discussion as $disc)
                    <a class="content" href="{{ Request::root() }}/backend/discussion/reports/{{$disc->reported_id}}" >

                        <div class="notification-item">
                            <h4 class="item-title">a discussion with id {{$disc->reported_id}} has {{$disc->report_count}} reports </h4>
                        </div>

                    </a>
                @endforeach

                @foreach($discussion_comment as $disc_comment)
                    <a class="content" href="{{ Request::root() }}/backend/discussioncomment/reports/{{$disc_comment->reported_id}}" >

                        <div class="notification-item">
                            <h4 class="item-title">a discussion comment with id {{$disc_comment->reported_id}} has {{$disc_comment->report_count}} reports </h4>
                        </div>

                    </a>
                @endforeach







        </div>
    </ul>

</div>

@endsection
