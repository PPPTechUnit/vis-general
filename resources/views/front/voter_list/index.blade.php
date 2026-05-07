@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: Blockcodes')
<style>
    .blockcode-table tbody {
        display: block;
        max-height: 280px;
        overflow-y: auto !important;
    }
</style>
<div class="main-bg">
    <div class="blockcode-listing-container">
        <div class="blockcode-listing-inner">
            <div class="row">
                <div class="col-lg-12"> <h3 class="text-center m-0 "><b>SELECT BLOCK CODE</b></h3></div>
                <div class="col-lg-12" style="padding: 0px 30px;">
                    <input type="text" class="form-control" name="blockcode" id="blockcode_id" placeholder="Search Blockcode">
                    <input type="hidden" class="form-control" name="tehsil" id="tehsil" value="{{$tehsil_id}}">
                </div>
                <div class="col-lg-12 blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar" >
                    <table class="table blockcode-table ">
                        <thead>
                        <tr>
                            <th>S.NO</th>
                            <th>BLOCK CODE</th>
                            <th>Eloctoral Area Name</th>
                            <th>Circle Name</th>
                            <th>Village / City</th>
                            <th>Total Voters</th>
                            <th>Voter Count</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="blockcodes-tbody">
                        @foreach($blockcodes as $key=> $code)
                            <tr  class="code-clicktr"  id="blocktr-{{$code->blockcode}}" blockcode="{{$code->blockcode}}"  onclick="selectBlockCode({{$code->blockcode}})">
                                <td>{{$key+1}}</td>
                                <td>{{$code->blockcode}}</td>
                                <td>{{$code->eloctoral_area_name}}</td>
                                <td>{{$code->circle_name}}</td>
                                <td>{{$code->village_city}}</td>
                                <td>{{$code->total_voters}}</td>
                                <td>{{$code->count_voters}} / {{$code->count_updated}}  </td>
                                <td>
                                    <button
                                            class="btn btn-sm status-btn {{ $code->completed == 1 ? 'btn-success' : 'btn-warning' }}"
                                            data-id="{{ $code->id }}"
                                            data-status="{{ $code->completed }}">
                                        {{ $code->completed == 1 ? 'Completed' : 'Pending' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12"><button class="next-btn btn btn-success pull-right" disabled id="next-btn">NEXT <img id="next-btn-loader" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" style="display: none; height: 20px"> </button></div>
            </div>
        </div>
    </div>

</div>



@endsection
@section('jsfiles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '.status-btn', function () {
            const btn    = $(this);
            const id     = btn.data('id');
            const status = btn.data('status');
            const newStatus = status == 1 ? 0 : 1;

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
                    url: '/update-completed-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        completed: newStatus
                    },
                    success: function (res) {
                        if (res.success) {
                            btn.data('status', newStatus);
                            btn.text(newStatus == 1 ? 'Completed' : 'Pending');
                            btn.removeClass('btn-success btn-warning')
                                .addClass(newStatus == 1 ? 'btn-success' : 'btn-warning');

                            Swal.fire({
                                title: "Updated!",
                                text: "Status has been updated successfully.",
                                icon: "success"
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            icon: "error"
                        });
                    }
                });
            }
        });
        });
        function selectBlockCode(val){
            $('.code-clicktr').removeClass('checked');
            $('#blocktr-'+val+"").addClass('checked');
            $("#next-btn").prop('disabled', false);
            $("#next-btn").removeAttr("disabled");
        }

        $("#next-btn").on('click', function(){
            $("#next-btn-loader").show();
            var code = $('.code-clicktr.checked').attr("blockcode")
            window.location = "{{url('/user/voterlist/')}}/"+code;
        });


        $("#blockcode_id").keypress(function (e) {
            var block_code = $('#blockcode_id').val();
            var block_code = block_code.length;

            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            var maxChars = 10;
            //console.log($(this).val().length);
            if ($(this).val().length > maxChars) {
                $(this).val($(this).val().substr(0, maxChars));
            }
        });


        var blockCodeXHR = null;
        var blockCodeTimer = null;

        $('#blockcode_id').on('input', function(e) {
            var block_code = $(this).val().trim();
            var tehsil = $('#tehsil').val();

            // Clear previous timer
            clearTimeout(blockCodeTimer);

            // Abort previous AJAX if still running
            if (blockCodeXHR) {
                blockCodeXHR.abort();
                blockCodeXHR = null;
            }

            if (block_code.length === 0) {

                $.ajax({
                    type: 'POST',
                    url: '{{ url("/ajax/search-blockcode") }}',
                    data: {
                        block_code: block_code,
                        tehsil: tehsil,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#block_code_loader').show();
                    },
                    complete: function() {
                        $('#block_code_loader').hide();
                    },
                    success: function(data) {
                        var html = '';

                        if (data.blockcodes && data.blockcodes.length > 0) {
                            $.each(data.blockcodes, function(index, val) {

                                html += `
                                    <tr class="code-clicktr"
                                        id="blocktr-${val.blockcode}"
                                        blockcode="${val.blockcode}"
                                        onclick="selectBlockCode('${val.blockcode}')">

                                        <td>${index + 1}</td>
                                        <td>${val.blockcode ?? ''}</td>
                                        <td>${val.eloctoral_area_name ?? ''}</td>
                                        <td>${val.circle_name ?? ''}</td>
                                        <td>${val.village_city ?? ''}</td>
                                        <td>${val.male_voters ?? ''}</td>
                                        <td>${val.female_voters ?? ''}</td>
                                        <td>${val.book_number ?? ''}</td>
                                          <td>
                                            <button
                                                class="btn btn-sm status-btn ${val.completed == 1 ? 'btn-success' : 'btn-warning'}"
                                                data-id="${val.id}"
                                                data-status="${val.completed}">
                                                ${val.completed == 1 ? 'Completed' : 'Pending'}
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            html = "<tr><td colspan='3' style='text-align:center'>Blockcode not found</td></tr>";
                        }

                        $("#blockcodes-tbody").append(html);
                    },
                    error: function(xhr) {
                        var message = 'Something went wrong. Please try again.';

                        // Show the actual validation message if available
                        if (xhr.status === 422) {
                            var errors = JSON.parse(xhr.responseText).errors;
                            var firstError = Object.values(errors)[0][0];
                            message = firstError;
                        }

                        $("#blockcodes-tbody").html(
                            "<tr><td colspan='5' style='text-align:center;color:red;'>" + message + "</td></tr>"
                        );
                        console.error(xhr.responseText);
                    }
                });
            }

            if (!tehsil) {
                $("#blockcodes-tbody").html(
                    "<tr><td colspan='8' style='text-align:center;color:orange;'>Please select a Tehsil first.</td></tr>"
                );
                return;
            }

            // Debounce — wait 400ms after user stops typing
            blockCodeTimer = setTimeout(function() {

                $("#blockcodes-tbody").empty();

                blockCodeXHR = $.ajax({
                    type: 'POST',
                    url: '{{ url("/ajax/search-blockcode") }}',
                    data: {
                        block_code: block_code,
                        tehsil: tehsil,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('#block_code_loader').show();
                    },
                    complete: function() {
                        $('#block_code_loader').hide();
                        blockCodeXHR = null;
                    },
                    success: function(data) {
                        var html = '';

                        if (data.blockcodes && data.blockcodes.length > 0) {
                            $.each(data.blockcodes, function(index, val) {
                                html += `
                            <tr class="code-clicktr"
                                id="blocktr-${val.blockcode}"
                                blockcode="${val.blockcode}"
                                onclick="selectBlockCode('${val.blockcode}')">
                                <td>${index + 1}</td>
                                <td>${val.blockcode ?? ''}</td>
                                <td>${val.eloctoral_area_name ?? ''}</td>
                                <td>${val.circle_name ?? ''}</td>
                                <td>${val.village_city ?? ''}</td>
                                <td>${val.male_voters ?? ''}</td>
                                <td>${val.female_voters ?? ''}</td>
                                <td>${val.book_number ?? ''}</td>
                                  <td>
                                    <button
                                        class="btn btn-sm status-btn ${val.completed == 1 ? 'btn-success' : 'btn-warning'}"
                                        data-id="${val.id}"
                                        data-status="${val.completed}">
                                        ${val.completed == 1 ? 'Completed' : 'Pending'}
                                    </button>
                                </td>
                            </tr>
                        `;
                            });
                        } else {
                            html = "<tr><td colspan='8' style='text-align:center'>Blockcode not found</td></tr>";
                        }

                        $("#blockcodes-tbody").html(html); // use .html() not .append()
                    },
                    error: function(xhr) {
                        // Ignore aborted requests
                        if (xhr.statusText === 'abort') return;

                        var message = 'Something went wrong. Please try again.';

                        if (xhr.status === 422) {
                            var errors = JSON.parse(xhr.responseText).errors;
                            message = Object.values(errors)[0][0];
                        }

                        $("#blockcodes-tbody").html(
                            "<tr><td colspan='8' style='text-align:center;color:red;'>" + message + "</td></tr>"
                        );
                    }
                });

            }, 400);
        });
    </script>
@endsection
