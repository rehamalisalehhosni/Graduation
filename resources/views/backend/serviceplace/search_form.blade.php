<br/>
  <form method="get" action="{{ Request::root() }}/backend/serviceplace/search" class="form-inline " >
    <div class="row">
    	<div class="form-group ">
	      <label for="email">Search:</label>
	      <input type="search" class="form-control" name="search">
	    </div>
    </div>
    <br>
    <div class="row">
    	<div class="form-group ">
	      <label for="status">Status:</label>
	      <select class="form-control" name="status">
	      	<option value="3">Select Status</option>
	      	<option value="1"> Approved </option>
	      	<option value="0">Not Approved</option>	
	      </select>
	    </div>
    </div>
    <br>
    <div class="row">
    	<div class="form-group ">
	      <label for="status">Categories:</label>
	      <select class="form-control" name="menu" id="myselect">
	      	<option value="0">All Main Category</option>
	      		
	      	@foreach ($mainCat as $menu)
			    <option value="{{$menu->service_main_category_Id}}">{{$menu->title_en}}</option> 
			 @endforeach

	      	
	      </select>

	      <span id="submenu"></span>
	    </div>
    </div>

    <div class="row text-right">
    	<br>
    	<button type="submit" class="btn btn-primary">Search</button>
    </div>	
	    
	    
    <br/>
  </form>
<br/>
<p class="clear-fix"></p>

<script>
$(document).ready(function(){
	
	$('#myselect').change(function() {
	
//var reserv=$('#reserv_type').val();

		var reserv=$('#myselect').val();
		//console.log("menu : " + reserv);	
			$.ajax(
			{
				type : 'POST',
				data : 'menu=' + reserv,
				url : "{{ Request::root() }}/backend/serviceplace/submenu" ,
				success : function( result )
				{
					var returnValue = '';
					//console.log( result.response[0] );
					var returnCount = result.response.length;
					 if(returnCount > 0){
					 	returnValue += 	'<select class="form-control" name="submenu" >';
						returnValue +=  '<option value="0">All Sub Main Category</option>'
					 	
					 	for(var i=0 ; i<returnCount ; i++){
					 		returnValue +=  '<option value="'+result.response[i].service_main_category_Id+'">'+result.response[i].title_en+'</option>'
					 		//console.log("submenu_id : "+result.response[i].service_main_category_Id + " Name : " +result.response[i].title_en  );	
					 	}

					 	returnValue += '</select>'
					 		
					 }
					 else{
					 	returnValue = "no SubMenues";
					 }

					 $("#submenu").html(returnValue);
	//				console.log(returnCount);
					//$("#viewCheeckSearchResult").html(result);	
				}
			});	
 	});
});	
</script>