<form action="{{$action}}" method="post" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label for="email">title ar:</label>
        <input type="text" class="form-control"  name="title_ar" value="{{$topic->title_ar}}">
    </div>
    <div class="form-group">
        <label for="email">title en:</label>
        <input type="text" class="form-control"  name="title_en" value="{{$topic->title_en}}">
    </div>
    <div class="form-group">
        <label for="email">place:</label>
        <input type="text" class="form-control"  name="place" value="{{$topic->place}}">
    </div>
    <input type="hidden" name="discussion_topic_id" value="{{$topic->discussion_topic_id}}" />
    <button type="submit" class="btn btn-primary">Save</button>

</form>
