@extends('layouts.admin')
@section('title', " :: Blockcode Users")

@section('content')
    <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>


    <style>
        td, th{
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>

    <div class="page-header page-header-light"  style="padding: 5px;">


        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('admin.dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Blockcode Users
                     <p class="text-center" style="color: #000;    display: contents;"> <==> <b>Blockcode Info</b># ({{$block_info['blockcode']}}), Total ({{$block_info['total_voters']}}),
                            <b>Voterlist Info#</b>
                         @php $total_count_voters = 0; @endphp

                         @foreach($voters_count as $gender_key => $vot_count)
                             <span style="text-transform: capitalize">
                        {{ strtolower($gender_key) }}: {{$vot_count}}
                    </span>@if(!$loop->last), @endif
                             @php $total_count_voters += $vot_count; @endphp
                         @endforeach
                         <strong>Total: {{$total_count_voters}}</strong>
                        </p>
                    </span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <!-- Default alerts -->
        <div class="card">

            <div class="card-body" style="padding: 5px;">
                <div class="row">

                    <div class="col-lg-12 p-1" style="text-align: right">

                        <button
                                class="btn btn-sm status-btn {{ $block_info['completed'] == 1 ? 'btn-success' : 'btn-warning' }}"
                                data-id="{{ $block_info['id'] }}"
                                data-status="{{ $block_info['tested'] }}">
                            {{ $block_info['tested'] == 1 ? 'Tested' : 'Pending' }}
                        </button>
                    </div>
                    <div class="col-lg-12 blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar">


                        <div id="accordion">

                            <div class="card">
                                <div class="card-header" style="padding: 7px 15px;">
                                    <a class="card-link" data-toggle="collapse" href="#collapseOne">
                                        PDF VIEW
                                    </a>
                                </div>
                                <div id="collapseOne" class="collapse show">
                                    <div class="card-body"  style="padding: 5px;">
                                        <div>
                                            <iframe src="{{ asset('blockcode_files/'.$block_info['blockcode'].'.pdf') }}#zoom=100" style="height:400px;width:100%;" title="PDF Preview"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header"  style="padding: 7px 15px;">
                                    <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                                        Voterlist
                                    </a>
                                </div>
                                <div id="collapseTwo" class="collapse">
                                    <div class="card-body">
                                        <table class="table blockcode-table2 table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="width: 28%;font-size: 14px; text-align: left">Address</th>
                                                <th style="width: 5%;font-size: 14px;">Age</th>
                                                <th style="width: 12%;font-size: 14px;">CNIC</th>
                                                <th style="width: 12%;font-size: 14px;">Father / Husband's <br>Name</th>
                                                <th style="width: 8%;font-size: 14px;">Father /<br> Husband</th>
                                                <th style="width: 8%;font-size: 14px;">Gender</th>
                                                <th style="width: 12%;font-size: 14px;">Name</th>
                                                <th style="width: 5%;font-size: 14px;">Gharana</th>
                                                <th style="width: 5%;font-size: 14px;">Silsila</th>
                                                <th style="width: 5%;font-size: 14px;">Update</th>

                                            </tr>
                                            </thead>
                                            <tbody id="blockcodes-tbody">
                                            @foreach($voters as $key => $code)
                                                <tr data-id="{{ $code->id }}" data-blockcode="{{ $code->blockcode }}">

                                                    {{-- ADDRESS --}}
                                                    <td style="width: 28%; text-align: left;vertical-align: bottom;" class="voter-address">
                                                        <textarea class="form-control" name="address" rows="2"  style="text-transform: uppercase;">{{ $code->address }}</textarea>
                                                    </td>
                                                    {{-- AGE --}}
                                                    <td style="width: 5%;vertical-align: bottom" class="voter-age">
                                                        <input type="text"
                                                               class="form-control age-input"
                                                               name="age"
                                                               value="{{ $code->age }}"
                                                               oninput="validateAge(this)"
                                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                    </td>
                                                    {{-- CNIC --}}
                                                    <td style="width: 12%; vertical-align: bottom;" class="voter-cnic">

                                                        <input type="text" class="form-control" name="cnic" value="{{ $code->cnic }}">
                                                    </td>
                                                    {{-- FATHER/HUSBAND NAME --}}
                                                    <td style="width: 12%; vertical-align: bottom;" class="voter-father">
                                                        <input type="text" class="form-control" name="father_husband_name" value="{{ $code->father_husband_name }}" style="text-transform: uppercase;">
                                                    </td>
                                                    {{-- FATHER/HUSBAND TYPE --}}
                                                    <td style="width: 8%; vertical-align: bottom; vertical-align: bottom;" class="voter-father-type">
                                                        <select class="form-control" name="father_husband">
                                                            <option value="Father"  {{ $code->father_husband == 'Father'  ? 'selected' : '' }}>Father</option>
                                                            <option value="Husband" {{ $code->father_husband == 'Husband' ? 'selected' : '' }}>Husband</option>
                                                        </select>
                                                    </td>
                                                    {{-- GENDER --}}
                                                    <td style="width: 8%; vertical-align: bottom;" class="voter-gender">
                                                        <select class="form-control" name="gender">
                                                            <option value="MALE"     {{ $code->gender == 'MALE'     ? 'selected' : '' }}>MALE</option>
                                                            <option value="FEMALE"   {{ $code->gender == 'FEMALE'   ? 'selected' : '' }}>FEMALE</option>
                                                            <option value="A-MALE"   {{ $code->gender == 'A-MALE'   ? 'selected' : '' }}>A-MALE</option>
                                                            <option value="A-FEMALE" {{ $code->gender == 'A-FEMALE' ? 'selected' : '' }}>A-FEMALE</option>
                                                        </select>
                                                    </td>
                                                    {{-- NAME --}}
                                                    <td style="width: 12%;vertical-align: bottom;" class="voter-name">
                                                        <input type="text" class="form-control" name="name" value="{{ $code->name }}" style="text-transform: uppercase;">
                                                    </td>
                                                    <td style="width: 5%; vertical-align: bottom;" class="voter-gharana">
                                                        <input type="text"
                                                               class="form-control numeric-check"
                                                               name="gharana_no"
                                                               value="{{ $code->gharana_no }}">
                                                    </td>

                                                    <td style="width: 5%; vertical-align: bottom;" class="voter-silsila">
                                                        <input type="text"
                                                               class="form-control numeric-check"
                                                               name="silsila_no"
                                                               value="{{ $code->silsila_no }}">
                                                    </td>
                                                    {{-- UPDATE BUTTON --}}
                                                    <td style="width: 5%; vertical-align: bottom;vertical-align: bottom;">
                                                        <button id="voter-id-{{$code->id}}" class="btn  update-btn @if($code->updated_by ==0) btn-danger @else btn-success @endif "
                                                                data-id="{{ $code->id }}"
                                                                data-blockcode="{{ $code->blockcode }}">
                                                            Update
                                                        </button>
                                                    </td>



                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('jsfiles')
    <script src="{{ asset('frontend/lib/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $(document).ready(function () {  // ← wrap it in document.ready

            $(document).on('click', '.status-btn', function () {
                const btn       = $(this);
                const id        = btn.data('id');
                const status    = parseInt(btn.data('status')); // ← force integer
                const newStatus = status === 1 ? 0 : 1;

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to change the status?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                    $.ajax({
                        url: '/update-testing-status',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            tested: newStatus
                        },
                        success: function (res) {
                            if (res.success) {
                                btn.data('status', newStatus);
                                btn.text(newStatus === 1 ? 'Tested' : 'Pending');
                                btn.removeClass('btn-success btn-warning')
                                    .addClass(newStatus === 1 ? 'btn-success' : 'btn-warning');

                                Swal.fire("Tested!", "Status updated successfully.", "success");
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
            });

        });
</script>

@endsection
