<form action="{{$action}}" method="post" >
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  <div class="form-group">
      <label for="email">title ar:</label>
      <input type="text" class="form-control"  name="title_ar" value="{{$unitType->title_ar}}">
    </div>
  <div class="form-group">
      <label for="email">title en:</label>
      <input type="text" class="form-control"  name="title_en" value="{{$unitType->title_en}}">
    </div>
    <input type="hidden" name="unit_type_id" value="{{$unitType->unit_type_id}}" />
    <button type="submit" class="btn btn-primary">Save</button>

</form>
