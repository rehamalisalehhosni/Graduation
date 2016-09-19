@extends('backend.layouts.app')

@section('content')
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Display images </button>
<br/>
<p class="clearfix"></p>
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th class="success">user name</th>
       <td>{{$realState->user->first_name}} {{$realState->user->last_name}}</td>
     </tr>
     <tr>
       <th class="success">user email</th>
       <td>{{$realState->user->email}}</td>
     </tr>
     <tr>
       <th class="success">title</th>
       <td>{{$realState->title}}</td>
     </tr>
     <tr>
       <th class="success">description</th>
       <td>{{$realState->description}}</td>
     </tr>
     <tr>
       <th class="success">location</th>
       <td>{{$realState->location}}</td>
     </tr>
     <tr>
       <th class="success">number of rooms</th>
       <td>{{$realState->number_of_rooms}}</td>
     </tr>
     <tr>
       <th class="success">number of bathrooms</th>
       <td>{{$realState->number_of_bathrooms}}</td>
     </tr>
     <tr>
       <th class="success">area</th>
       <td>{{$realState->area}}</td>
     </tr>
     <tr>
       <th class="success">longitude</th>
       <td>{{$realState->longitude}}</td>
     </tr>
     <tr>
       <th class="success">latitude</th>
       <td>{{$realState->latitude}}</td>
     </tr>
     <tr>
       <th class="success">owner name</th>
       <td>{{$realState->owner_name}}</td>
     </tr>
     <tr>
       <th class="success">owner mobile</th>
       <td>{{$realState->owner_mobile}}</td>
     </tr>
     <tr>
       <th class="success">owner email</th>
       <td>{{$realState->owner_email}}</td>
     </tr>
     <tr>
       <th class="success">unit type</th>
       <td>{{$realState->unitType->title_en }}</td>
     </tr>
     <tr>
       <th class="success">neighbarhood</th>
       <td>{{$realState->neighbarhood->title_en }}</td>
     </tr>
     <tr>
       <th class="success">amenities</th>
       <td>{{$realState->amenity->title_en }}</td>
     </tr>
     <tr>
       <th class="success">Cover image</th>
       <td><img src= "{{ Request::root() }}{{ $image_path }}{{ $realState->cover_image }} " class="img-responsive" /></td>
     </tr>
     <tr>
       <th class="success">Options </th>
       <td>
              <form action="{{ Request::root() }}/backend/realstate/saveoptions" method="post">
                <div class="checkbox ">
                    <label><input id="checkbox" type="checkbox" name="on_home"  value="1" @if ($realState->on_home) checked @endif > display on home</label>
                </div>
                <div class="checkbox">
                    <label><input id="checkbox" type="checkbox" name="is_featured" value="1" @if ($realState->is_featured) checked @endif > display as feature </label>
                </div>
                <input type="hidden" name="realstate_id" value="{{$realState->real_estate_ad_id}}" />
                <input type="submit" name="submit" class="btn btn-danger" value="Save" />
              </form>
       </td>
     </tr>
   </thead>
   <tbody>
   </tbody>
 </table>


   <!-- Modal -->
   <div class="modal fade" id="myModal" role="dialog">
     <div class="modal-dialog">
       <!-- Modal content-->
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h4 class="modal-title">Realstate Images</h4>
         </div>
         <div class="modal-body">
           <form action="{{ Request::root() }}/backend/realstate/saveimages" method="post">
           <table class="table table-striped table-bordered  table-hover">
                <tr>
                  <th >image</th>
                  <th>is primary</th>
                </tr>
               @foreach($realState->realEstateAdImage as $image)
                <tr>
                  <td>
                    <img src= "{{ Request::root() }}{{ $image_path }}{{ $image->image }} " class="img-responsive" />
                  </td>
                  <td>
                  <label><input id="checkbox" type="radio" name="is_primary"  value="{{$image->real_estate_ad_image_id}}" @if ($image->is_primary) checked @endif > display on home</label>
                </td>
                </tr>
               @endforeach

            </table>
            <input type="hidden" name="realstate_id" value="{{$realState->real_estate_ad_id}}" />
            <input type="submit" name="submit" class="btn btn-danger" value="Save" />
          </form>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
       </div>

     </div>
   </div>
@stop
