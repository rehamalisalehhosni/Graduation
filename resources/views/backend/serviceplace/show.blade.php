@extends('backend.layouts.app')

@section('content')
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Display images </button>
<br/>
<p class="clearfix"></p>
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th class="success">user name</th>
       <td>{{$serviceplace->user->first_name}} {{$serviceplace->user->last_name}}</td>
     </tr>
     <tr>
       <th class="success">user email</th>
       <td>{{$serviceplace->user->email}}</td>
     </tr>
     <tr>
       <th class="success">title</th>
       <td>{{$serviceplace->title}}</td>
     </tr>

     <tr>
       <th class="success">Description</th>
       <td>{{$serviceplace->description}}</td>
     </tr>

     <tr>
       <th class="success">Address</th>
       <td>{{$serviceplace->address}}</td>
     </tr>

     <tr>
       <th class="success">Longitude</th>
       <td>{{$serviceplace->longitude}}</td>
     </tr>

     <tr>
       <th class="success">Latitude</th>
       <td>{{$serviceplace->latitude}}</td>
     </tr>
     

     <tr>
       <th class="success">Mobile 1</th>
       <td>{{$serviceplace->mobile_1}}</td>
     </tr>

     <tr>
       <th class="success">Mobile 2</th>
       <td>{{$serviceplace->mobile_2}}</td>
     </tr>

     <tr>
       <th class="success">Mobile 3</th>
       <td>{{$serviceplace->mobile_3}}</td>
     </tr>


     <tr>
       <th class="success">Created At</th>
       <td>{{$serviceplace->created_at}}</td>
     </tr>

     <tr>
       <th class="success">Updated At</th>
       <td>{{$serviceplace->updated_at}}</td>
     </tr>

     <tr>
       <th class="success">Service Main Category</th>
       <td>{{$serviceplace->mainCat -> title_en}}</td>
     </tr>

     <tr>
       <th class="success">Service Sub Main Category</th>
       <td>{{$serviceplace->subMainCat -> title_en}}</td>
     </tr>

     <tr>
       <th class="success">Total Rate</th>
       <td>{{$serviceplace->total_rate}}</td>
     </tr>

     <tr>
       <th class="success">Neighbarhood</th>
       <td>{{$serviceplace->neighberhood->title_en}}</td>
     </tr>

     <tr>
       <th class="success">Opening Hourse</th>
       <td>{{$serviceplace->opening_hours}}</td>
     </tr>


     <tr>
       <th class="success">Cover image</th>
       <td><img src= "{{ Request::root() }}{{ $image_path }}{{ $serviceplace->cover_image }} " class="img-responsive" /></td>
     </tr>
     <tr>
       <th class="success">Logo </th>
       <td><img src= "{{ Request::root() }}{{ $image_path }}logos/{{ $serviceplace->logo }} " class="img-responsive" /></td>
     </tr>
     <tr>
       <th class="success">Options </th>
       <td>
              <form action="{{ Request::root() }}/backend/serviceplace/saveoptions" method="post">
                <div class="checkbox ">
                    <label><input id="checkbox" type="checkbox" name="on_home"  value="1" @if ($serviceplace->on_home) checked @endif > display on home</label>
                </div>
                <div class="checkbox">
                    <label><input id="checkbox" type="checkbox" name="is_featured" value="1" @if ($serviceplace->is_featured) checked @endif > display as feature </label>
                </div>
                <div class="checkbox ">
                    <label><input id="checkbox" type="checkbox" name="is_hide"  value="1" @if ($serviceplace->is_hide) checked @endif > is Hidden</label>
                </div>
                <input type="hidden" name="serviceplace_id" value="{{$serviceplace->service_place_id}}" />
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
           <h4 class="modal-title">Service Place Images</h4>
         </div>
         <div class="modal-body">
           <table class="table table-striped table-bordered  table-hover">
                <tr>
                  <th >image</th>
                  
                </tr>
               @foreach($serviceplace->ServicePlaceImage as $image)
                <tr>
                  <td>
                    <img src= "{{ Request::root() }}{{ $image_path }}{{ $image->image }} " class="img-responsive" />
                  </td>
                </tr>
               @endforeach

            </table>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
       </div>

     </div>
   </div>
      <div style="width:1000px;height:500px;"><?php echo  Mapper::render(); ?></div>
@stop
