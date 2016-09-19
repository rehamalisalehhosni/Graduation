@extends('landingPage.app')

@section('content')

      <div class="page content">
          <h3>إتصل بنا</h3>
          <div class="row " >

            <div class="col-md-6  col-md-offset-3">
              <div class="result"></div>
              <form role="form" class="form_data" id="form"  >
                <div class="form-group col-md-6">
                  <input type="text" class="form-control" name="name" id="name" placeholder="الاسم">
                </div>
                <div class="form-group col-md-6">
                  <input type="email" class="form-control" name="email" id="email" placeholder="البريد الالكتروني">
                </div>
                <div class="form-group col-md-12">
                  <input type="subject" class="form-control" id="subject" name="subject" placeholder="الموضوع الرسالة ">
                </div>
                <div class="form-group col-md-12">
                  <textarea class="form-control" id="message" name="message" placeholder="نص الرسالة " cols="4" rows="4"></textarea>
                </div>
                <div class="form-group col-md-12">
                  <button type="button" class="btn btn-primary col-md-12 " onclick="validation()" >إرسل الان </button>
                </div>
              </form>
              <p class="clearfix"></p>
            </div>
        </div>
      </div>
      <p class="clearfix"></p>
<script>
function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};

      function validation(){
          var missing_data=false;
          if($('#name').val() == ''){
            missing_data=true;
            $("#name").css({
                'background-color':'#FAE4E5',
                'border-color':'#FF92A7'
            });
          }else{
            $("#name").css({
              'background-color':'#fff',
              'border-color':'#ccc'
            });
          }

          if($('#email').val() == ''){
             missing_data=true;
            $("#email").css({
                'background-color':'#FAE4E5',
                'border-color':'#FF92A7'
            });
          }else{
            $("#email").css({
              'background-color':'#fff',
              'border-color':'#ccc'
            });
          }


          if($('#subject').val() == ''){
             missing_data=true;
            $("#subject").css({
                'background-color':'#FAE4E5',
                'border-color':'#FF92A7'
            });
          }else{
            $("#subject").css({
              'background-color':'#fff',
              'border-color':'#ccc'
            });
          }

          if($('#message').val() == ''){
             missing_data=true;
            $("#message").css({
                'background-color':'#FAE4E5',
                'border-color':'#FF92A7'
            });
          }else{
            $("#message").css({
              'background-color':'#fff',
              'border-color':'#ccc'
            });
          }


         if(missing_data==true){
           $('.result').html("<p class='alert alert-danger'>يرجي ادخال جميع الحقول </p>");
         }else{
           if( !isValidEmailAddress( $('#email').val() ) ) {

              $(".result").html("<p class='alert alert-danger'> البريد الإليكترونى الذى أدخلته غير صالح</p>");

              return; // stop executing the following code (the rest code of the function)
            }else {
                 $(".result").html("");
                 $.ajax({
                      type   : 'POST',
                      url    : '{{ Request::root() }}/app/contact',
                      data   : $('#form').serialize(), // more info --> http://www.jstiles.com/Blog/How-To-Submit-a-Form-with-jQuery-and-AJAX
                      success: function(response){
                          console.log(response);
                          if(response['code']==0){
                            $('.result').html(response['message']);
                          }else{
                              $('.result').html(response['message']);
                              $('#form').remove();
                          }

                      }

                  });
          }
         }
      }
</script>
 @show

 @endsection
