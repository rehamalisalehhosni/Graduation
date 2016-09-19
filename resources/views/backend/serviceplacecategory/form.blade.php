<form action="{{$action}}" method="post" enctype="multipart/form-data">

  <div class="row">
      <div class="form-group ">
        <label for="status">Categories:</label>
        <select class="form-control" name="menu" id="myselect">
          <option value="0"  >All Main Category</option>
            
              @foreach ($main as $menu)
                <option  <?php if( $cat->main_category === $menu->service_main_category_Id ){ echo "selected"; }  ?> value="{{$menu->service_main_category_Id}}" > {{$menu->title_en }} </option> 
             @endforeach

          
        </select>

        <span id="submenu"></span>
      </div>
    </div>

  <div class="form-group">
      <label for="email">title ar:</label>
      <input type="text" class="form-control"  name="title_ar" value="{{$cat->title_ar}}">
    </div>
  <div class="form-group">
      <label for="email">title en:</label>
      <input type="text" class="form-control"  name="title_en" value="{{$cat->title_en}}">
    </div>
    
    <div class="form-group">
      <label for="email">Logo:</label>
      <input type="file" class="form-control"  name="logo">
    </div>

    <input type="hidden" name="service_main_category_id" value="{{$cat->service_main_category_Id}}" />
    <button type="submit" class="btn btn-primary">Save</button>

</form>
