@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: Blockcodes')
<style>
    label {
        margin-bottom: 0px;
        font-weight: 600;
    }
    .form-control{
        border-radius: 0px;
        border: 0px;
    }
    .error{
        color: red;
        font-weight: 400 !important;
    }
</style>
    <div class="main-bg container">
        <div class="blockcode-listing-container">
            <div class="blockcode-listing-inner" style="height: auto;background: #dbdbda00;">
              <div class="row">
                  <div class="col-lg-12"> <h3 class="text-center m-0 "><b>SEND EMAIL</b></h3></div>
                 </div>
               <div class="sent-email-form">
                   <div class="row">
                       <div class="col-lg-12">
                           <p style="color: red; margin-bottom: 10px; font-weight: 600" id="error-mail-msg"></p>
                           <p style="color: #447c66; margin-bottom: 10px;font-weight: 600" id="mail-msg"></p>
                           @if(session('success'))
                               <div class="alert alert-success">  {{session('success')}}
                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                               </div>
                           @endif @if(session('error'))
                               <div class="alert alert-danger">  {{session('error')}}
                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                               </div>
                           @endif
                       </div>
                       <div class="col-lg-12">
                           <form id="feedback-form" action="{{url('/user/send-feedback')}}" method="post">
                               @csrf
                               <div class="row">
                                   <div class="col-lg-12">
                                       <div class="form-group">
                                           <label for="subject">Subject:</label>
                                           <input type="text" class="form-control" id="subject" placeholder="Enter Subject" name="subject">
                                       </div>
                                   </div>
                                   <div class="col-lg-12">
                                       <div class="form-group">
                                           <label for="description">Description:</label>
                                           <textarea rows="5"  class="form-control"  placeholder="Enter Description" name="description" id="description"></textarea>
                                       </div>
                                   </div>
                                   <div class="col-lg-12">
                                       <div class="form-group text-right">
                                           <button style="margin-right: 0px;" type="submit" class="next-btn btn btn-success pull-right">Submit <img id="next-btn-loader" style="height:20px; display: none" src="{{url('/public/assets/loader-form.gif')}}"></button>
                                       </div>
                                   </div>
                               </div>
                           </form>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
@endsection
@section('jsfiles')
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>

    <script>
        $(document).ready(function() {


            $("#feedback-form").validate({
                rules: {
                    subject : {
                        required: true,
                    },
                    description : {
                        required: true,
                    },

                },
                messages : {

                    subject: {  required: "Please enter  Subject"},
                    description: {  required: "Please description"},

                },
                submitHandler: function(form) {
                    $("#next-btn-loader").show();
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            console.log(response);
                            $('#error-mail-msg').empty();
                            $('#mail-msg').empty();
                            if(response.status ==1){
                                $("#next-btn-loader").hide();
                                $("#next-btn-loader").css("display", "none");
                                 $('#feedback-form')[0].reset();
                                $('#mail-msg').text(response.message);
                            }else {
                                $("#next-btn-loader").hide();
                                $("#next-btn-loader").css("display", "none");
                                $('#error-mail-msg').text(response.message);
                            }
                        }
                    });
                }
            });
        });

    </script>

@endsection
