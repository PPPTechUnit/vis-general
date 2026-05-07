@extends('layouts.admin')
@section('title', " :: Edit User's Constituencies")

@section('content')


    <script src="{{ asset('public/admin/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{ asset('public/admin/assets/js/app.js')}}"></script>

    <script src="https://demo.interface.club/limitless/demo/Template/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
    <script src="https://demo.interface.club/limitless/demo/Template/global_assets/js/plugins/forms/selects/select2.min.js"></script>
    <script src="https://demo.interface.club/limitless/demo/Template/layout_1/LTR/default/full/assets/js/app.js"></script>
    <script src="https://demo.interface.club/limitless/demo/Template/global_assets/js/demo_pages/form_select2.js"></script>


     <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><a href="{{ url()->previous() }}"><i class="icon-arrow-left52 mr-2"></i> </a><span class="font-weight-semibold">User's Constituencies</span>  </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{route('constituencies.index')}}" class="breadcrumb-item"> User's Constituencies </a>
                    <span class="breadcrumb-item active">Edit </span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <!-- /page header -->
    <!-- Content area -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{route('constituencies.update', $constituency['user_id'])}}" method="post" enctype="multipart/form-data">
                @csrf
                    @method('PATCH')
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Add New User</legend>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select User</label>
                                    <div class="col-lg-12">
                                        <select name="user" id="user" class="form-control">
                                            @foreach($user as $usr)
                                                <option value="{{$usr['id']}}" >{{$usr['name']}} ({{$usr['email']}})</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('user'))<div class="alert alert-danger">  {{ $errors->first('user') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Province</label>
                                    <div class="col-lg-12">
                                        <select name="province" id="province" class="form-control">
                                            @foreach($province as $provinc)
                                                <option value="{{$provinc->id}}">{{$provinc->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province'))<div class="alert alert-danger">  {{ $errors->first('province') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Provincial Constituencies</label>
                                    <div class="col-lg-12 input-group">
                                        <select name="provincial_constituencies[]" id="provincial_constituencies" class="form-control select"   data-fouc multiple="multiple">
                                            @foreach($province_cons as $cons)
                                                <option value="{{$cons->id}}" @if(in_array($cons->id, $userConstituency)) selected @endif>{{$cons->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('provincial_constituencies'))<div class="alert alert-danger">  {{ $errors->first('provincial_constituencies') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('jsfiles')
     <script>
        $(document).ready(function(){
            $("#province").change(function() {
                var id = $(this).val();
                var key='non';
                var token= $('#token').val();
                $.ajax({
                    url:  "{{url('/ajax/get-provinces')}}",
                    type: 'POST',
                    data: {'province_id' : id , '_token' :  token},
                }).done(function(response){
                    $('#provincial_constituencies').empty();

                    var  html = '';
                   // html += "<option value=''>Select Provincial Constituencies</option>";
                    if(response.length > 0) {
                        for(var i =0 ; i < response.length ; i++){
                            html += "<option value=" + response[i].id + ">" + response[i].name + "</option>";
                        }
                        $("#provincial_constituencies").append(html);
                    }
                });
            });
            //$('.multiselect').multiselect();
        });



     </script>

@endsection
