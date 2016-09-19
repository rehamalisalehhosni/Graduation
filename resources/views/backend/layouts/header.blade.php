<div class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
      <div class="logo pull-left"><img src='{{ asset('img/logo.png') }}'  class="pull-left"/><h1 class=" pull-right word">Dashboard - Jeeran </h1></div>
        {{--notification bar --}}
        @if(Session::has('user'))
        <div id="notification_menue" class="dropdown" size>
            <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
             <h3><i  id ="notification-count" data-count="2" class="glyphicon glyphicon-bell notification-icon">
                     {{--<span class="notification-counter">1</span>--}}
                 </i></h3>
            </a>

            <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">

                <div class="notification-heading"><h4 class="menu-title">Notifications</h4><h4 class="menu-title pull-right"></h4>
                </div>
                <li class="divider"></li>
                <div class="notifications-wrapper" id="notifications-wrapper">
                </div>
                <li class="divider"></li>
                <a href="{{ Request::root() }}/backend/notify/listall"> <div class="notification-footer"><h4 class="menu-title">View all<i class="glyphicon glyphicon-circle-arrow-right"></i></h4></div>
                </a>
            </ul>

        </div>

            <script>

                $(function(){

                    $("#notification_menue").click(function () {

                        $.ajax({
                            type: "POST",
                            url : "{{ url()}}/backend/notify/seen",
                            success : function(data){

                            },
                            error : function (errors) {
                                console.log(errors);
                            }
                        });

                    });
                })
            </script>

            <script>


                var notificationNum=0;
                (function notification() {

                    $.ajax({
                        url: '{{ url()}}/backend/notify/notify',
                        success: function(data) {

                            var i=0;
                            $.each(data, function (key, value) {

                                if(key=="users"){
                                    $.each(data.users, function() {

                                        if( !$('#user'+this.user_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='user"+this.user_id+"'class='content' href='{{ Request::root() }}/backend/users/listreports?user_id="
                                                    +this.user_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a user with id "
                                                    +this.user_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });

                                }else if (key=="service_place"){
                                    $.each(data.service_place, function() {

                                        if( !$('#service_place'+this.reported_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='service_place"+this.reported_id+"'class='content' href='{{ Request::root() }}/backend/serviceplace/reports/"
                                                    +this.reported_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a service place with id "
                                                    +this.reported_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });
                                }else if (key=="service_place_review"){
                                    $.each(data.service_place_review, function() {

                                        if( !$('#service_place_review'+this.reported_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='service_place_review"+this.reported_id+"'class='content' href='{{ Request::root() }}/backend/serviceplace/reviews/"
                                                    +this.reported_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a service place review with id "
                                                    +this.reported_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });
                                } else if (key=="discussion"){
                                    $.each(data.discussion, function() {

                                        if( !$('#discussion'+this.reported_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='discussion"+this.reported_id+"'class='content' href='{{ Request::root() }}/backend/discussion/reports/"
                                                    +this.reported_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a discussion with id "
                                                    +this.reported_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });
                                } else if (key=="discussion_comment"){
                                    $.each(data.discussion_comment, function() {

                                        if( !$('#discussion_comment'+this.reported_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='discussion_comment"+this.reported_id+"'class='content' href='{{ Request::root() }}/backend/discussioncomment/reports/"
                                                    +this.reported_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a discussion comment with id "
                                                    +this.reported_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });
                                } else if (key=="real_estate_ad"){
                                    $.each(data.real_estate_ad, function() {

                                        if( !$('#real_estate_ad'+this.reported_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='real_estate_ad"+this.reported_id+"'class='content' href='{{ Request::root() }}/backend/realstate/reports/"
                                                    +this.reported_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a  real estate ad with id "
                                                    +this.reported_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });
                                } else if (key=="real_estate_ad_comment"){
                                    $.each(data.real_estate_ad_comment, function() {

                                        if( !$('#real_estate_ad'+this.reported_id).length )
                                        {
                                            $( "#notifications-wrapper" ).append(
                                                    "<a  id ='real_estate_ad"+this.reported_id+"'class='content' href='{{ Request::root() }}/backend/realstate/commentreports/"
                                                    +this.reported_id+ "'>" +
                                                    "<div class='notification-item'>" +
                                                    "<h4 class='item-title'>" +
                                                    "a  real_estate_ad_comment with id "
                                                    +this.reported_id+
                                                    "  has "+this.report_count+" reports  " +
                                                    "</h4>" +
                                                    "</div>" +
                                                    "</a>"
                                            );
                                            i++;
                                            notificationNum=i;
                                        }



                                    });
                                }

                                //////////////


                            })

                            if(Object.keys(data).length>0){
                                if( $('#counter').length )
                                {
                                    $("#counter").text(notificationNum);
                                }else {
                                    $( "#notification-count" ).append( " <span id ='counter'class='notification-counter'>"+notificationNum+"</span>" );

                                }
                                $('title').text("("+notificationNum+") Dashboard");
//                                document.title = "Dashboard"+notificationNum;
                            }else {
                                if( $('#counter').length )
                                {
                                    $("#counter").remove();
//                                    document.title = "Dashboard";
                                    $('title').text("Dashboard");
                                }

                            }
                        },
                        complete: function() {
                            setTimeout(notification, 5000);
                        }
                    });
                })();



            </script>

            <div class="pull-right user">

        <h3><i class="fa fa-user"></i> {{Session::get('user')[0]['user_name']}}</h3>
        <form class="delete pull-right" action="{{ Request::root() }}/backend/admin/logout" method="POST" >
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <button type="submit" class="fa fa-sign-out  " style="background: none;border: none;">Sign Out</button>
        </form>
      @endif
      </div>
     <p class="clearfix"></p>
    </div>
  </div>
