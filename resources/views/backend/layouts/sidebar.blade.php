@if(Session::has('user'))
<div class="navbar-collapse collapse templatemo-sidebar">
  <ul class="templatemo-sidebar-menu">
    <!-- <li>
      <form class="navbar-form">
        <input type="text" class="form-control" id="templatemo_search_box" placeholder="Search...">
        <span class="btn btn-default">Go</span>
      </form>
    </li> -->
    <li class="active" ><a href="#"><i class="fa fa-home"></i>Dashboard</a></li>
    <li
      @if(Request::segment(2)=='realstate'|| Request::segment(2)=='unittype')
        class="sub   open  "
        @else
        class="sub  "
      @endif
      >
      <a href="javascript:;">
        <i class="fa fa-building-o" ></i> Real State  <div class="pull-right"><span class="caret"></span></div>
      </a>
      <ul class="templatemo-submenu">
        <li><a href="{{ Request::root() }}/backend/realstate">RealState online</a></li>
        <li><a href="{{ Request::root() }}/backend/realstate/hidden">RealState Hidden</a></li>
        <li><a href="{{ Request::root() }}/backend/unittype">RealState unit type</a></li>
      </ul>
    </li>
    <li
    @if(Request::segment(2)=='serviceplace'|| Request::segment(2)=='serviceplacecategory')
      class="sub   open  "
      @else
      class="sub  "
    @endif
        >
      <a href="javascript:;">
        <i class="fa fa-building-o"></i> service place  <div class="pull-right"><span class="caret"></span></div>
      </a>
      <ul class="templatemo-submenu">
        <li><a href="{{ Request::root() }}/backend/serviceplace">service place</a></li>
        <li><a href="{{ Request::root() }}/backend/serviceplacecategory">serviceplace category</a></li>
      </ul>
    </li>
    <li
    @if(Request::segment(2)=='discussion'||Request::segment(2)=='discussiontopic')
      class="sub   open  "
      @else
      class="sub  "
    @endif
    >
      <a href="javascript:;">
        <i class="fa fa-files-o"></i> Discussion  <div class="pull-right"><span class="caret"></span></div>
      </a>
      <ul class="templatemo-submenu">
        <li><a href="{{ Request::root() }}/backend/discussiontopic">Discussion Topics</a></li>
        <li><a href="{{ Request::root() }}/backend/discussion">Discussions</a></li>
        <li><a href="{{ Request::root() }}/backend/discussion/hidden">Hidden Discussions</a></li>
      </ul>
    </li>
    <li
    @if(Request::segment(2)=='neighbarhood')
      class="active "
    @endif
    ><a href="{{ Request::root() }}/backend/neighbarhood"><i class="fa fa-cubes"></i>Neighbarhood</a></li>

     <li
     @if(Request::segment(2)=='users')
       class="active "
     @endif
     ><a href="{{ Request::root() }}/backend/users/listall"><i class="fa fa-users"></i>Manage Users</a></li>
    @if(Session::get('user')[0]['admin_type']==1)
        <li
        @if(Request::segment(2)=='admin')
          class="active "
        @endif

        ><a href="{{ Request::root() }}/backend/admin/adminslist"><i class="fa fa-users"></i>Manage Admins</a></li>
    @endif
    <li
    @if(Request::segment(2)=='statistic')
      class="active "
    @endif
    ><a href="{{ Request::root() }}/backend/statistic"><i class="fa fa-cog"></i>statistic</a></li>
    <li>
      {{--@if(Session::has('user'))--}}
      {{--<form class="delete" action="{{ Request::root() }}/backend/admin/logout" method="POST">--}}
        {{--<input type="hidden" name="_method" value="DELETE">--}}
        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}" />--}}

       {{--<li><button type="submit" class="fa fa-sign-out  ">Sign Out</button></li>--}}
      {{--</form>--}}
    {{--@endif--}}
    </li>
    {{--<li><a href="javascript:;" data-toggle="modal" data-target="#confirmModal"><i class="fa fa-sign-out"></i>Sign Out</a></li>--}}
  </ul>
</div><!--/.navbar-collapse -->

  @endif
