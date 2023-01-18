@extends('admin.layouts.layouts')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/admin/')}}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Stores</li>
        </ol>
        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="pd-20">
                <div class="modal fade bs-example-modal-lg" id="ajaxModel" tabindex="-1" role="dialog"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Edit Store</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" id="ItemForm" name="ItemForm" enctype="multipart/form-data">
                                <input type="hidden" name="Item_id" id="Item_id">
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="store_name">Store Name</label>
                                                <input class="form-control" type="text" id="store_name" name="store_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="salesman_name">Salesman Name</label>
                                                <input class="form-control" type="text" id="salesman_name"
                                                       name="salesman_name"  >
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea class="form-control" name="address" id="address" cols="10" rows="3"></textarea>

                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone_no">Phone #</label>
                                                <input class="form-control" type="text" id="phone_no"
                                                       name="phone_no"  >
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input class="form-control" type="text" id="city"
                                                       name="city"  >
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <input class="form-control" type="text" id="state"
                                                       name="state"  >
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="zip_code">Zip Code</label>
                                                <input class="form-control" type="text" id="zip_code"
                                                       name="zip_code"  >
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                            <label>Active</label>
                                            <label class="switch">
                                                <input type="checkbox" id="status" name="status">
                                                <span class="slider round" ></span>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="text-center col-md-12 col-sm-12">
                                            <h4>Import by CSV or Excel</h4>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <input class="form-control-file" type="file" id="import_file" name="import_file" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="action_button" id="saveBtn" value="create"
                                            class="btn btn-primary">Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <i class="fas fa-table"></i>
                        Store
                    </div>
                    <div class="col-md-8">
                        <div class="pull-right">
                            <a href="#" class=" float-right btn btn-primary btn-sm " id="createNewItem"
                               data-toggle="modal" data-target="#bd-example-modal-lg" type="button">
                                Add new Store
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table-user" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Store</th>
                            <th>Salesman</th>
                            <th>Phone#</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip Code</th>
                            <th>Status</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Sticky Footer -->
        @endsection
        @section('page-script')
            <script type="text/javascript">
                $(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var table = $('.data-table-user').DataTable({
                        dom: 'Bfrtip',

                        "buttons": [
                            {
                                "extend": 'excel',
                                "text": '<i class="fa fa-file-excel" style="color: green;"> Excel</i>',
                                "titleAttr": 'Excel',
                                "action": newexportaction,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                "extend": 'csv',
                                "text": '<i class="fa fa-file" style="color: green;"> Csv</i>',
                                "titleAttr": 'CSV',
                                "action": newexportaction,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                "extend": 'pdf',
                                "text": '<i class="fa fa-file-pdf" style="color: green;"> Pdf</i>',
                                "titleAttr": 'PDF',
                                "action": newexportaction,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                "extend": 'print',
                                "text": '<i class="fa fa-print" style="color: green;"> Print</i>',
                                "titleAttr": 'Print',
                                "action": newexportaction,
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            'colvis'],

                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('store.index') }}",
                        scrollCollapse: true,
                        autoWidth: false,
                        responsive: true,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        language: {
                            info: "_START_-_END_ of _TOTAL_ entries",
                            searchPlaceholder: "Search",
                        },
                        columns: [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'store_name', name: 'store_name'},
                            {data: 'salesman_name', name: 'salesman_name'},
                            {data: 'phone_no', name: 'phone_no'},
                            {data: 'city', name: 'city'},
                            {data: 'state', name: 'state'},
                            {data: 'zip_code', name: 'zip_code'},
                            {data: 'status', render: function(data) {  if(data == 1) return '<span class="badge badge-success">Active</span>'; else  return '<span class="badge badge-warning">In-Active</span>';}},
                            {data: 'action', name: 'action'},
                        ]
                    });

                        $("#import_file").change(function(){
                            $('input').removeAttr('required');
                        });

//Create New Store
                    $('#createNewItem').click(function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('#password').attr('required',true);
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        $("input[type=text]").attr('required', true);
                        $('#saveBtn').val("create-Item");
                        $('#Item_id').val('');
                        $('#ItemForm').trigger("reset");
                        $('#modelHeading').html("Create New Store");
                        $('#ajaxModel').modal('show');
                        $('.ajax-loader').css("visibility", "hidden");
                    });

//Submit Edit and Create
                    $('body').on('submit', '#ItemForm', function (e) {
                        e.preventDefault();
                        $('#saveBtn').html('Sending..');
                        $('.ajax-loader').css("visibility", "visible");
                        var formData = new FormData(this);
                        $.ajax({
                            data: formData,
                            url: "{{ route('store.store') }}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (data) {
                                $('#saveBtn').html('Save Changes');
                                $('#ItemForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                $('.ajax-loader').css("visibility", "hidden");
                                table.draw();
                                alert(data.success);
                            },
                            error: function (data) {
                                alert(data.error);
                                $('.ajax-loader').css("visibility", "hidden");
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    });

//Edit
                    $('body').on('click', '.editItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('#password').attr('required',false);
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        var Item_id = $(this).data('id');
                        $('#image-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $.get("{{ route('store.index') }}" + '/' + Item_id + '/edit', function (data) {
                            $('#modelHeading').html("Edit Resaurant");
                            $('#saveBtn').val("edit-resaurant");
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('#store_name').val(data.store_name);
                            $('#salesman_name').val(data.salesman_name);
                            $('#address').val(data.address);
                            $('#phone_no').val(data.phone_no);
                            $('#city').val(data.city);
                            $('#state').val(data.state);
                            $('#zip_code').val(data.zip_code);
                            data.status == 1 ? $('#status').attr('checked', true) : $('#status').attr('checked', false);
                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });


//Delete
                    $('body').on('click', '.deleteItem', function () {
                        var Item_id = $(this).data("id");
                        if (confirm("Are You sure want to delete !")) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('store.store') }}" + '/' + Item_id,
                                success: function (data) {
                                    table.draw();
                                    alert(data.success);
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });
                        }

                    });

                });

                /* For Export Buttons available inside jquery-datatable "server side processing" - Start
                - due to "server side processing" jquery datatble doesn't support all data to be exported
                - below function makes the datatable to export all records when "server side processing" is on */

                function newexportaction(e, dt, button, config) {
                    var self = this;
                    var oldStart = dt.settings()[0]._iDisplayStart;
                    dt.one('preXhr', function (e, s, data) {
                        // Just this once, load all data from the server...
                        data.start = 0;
                        data.length = 2147483647;
                        dt.one('preDraw', function (e, settings) {
                            // Call the original action function
                            if (button[0].className.indexOf('buttons-copy') >= 0) {
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                            }
                            dt.one('preXhr', function (e, s, data) {
                                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                                // Set the property to what it was before exporting.
                                settings._iDisplayStart = oldStart;
                                data.start = oldStart;
                            });
                            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                            setTimeout(dt.ajax.reload, 0);
                            // Prevent rendering of the full data to the DOM
                            return false;
                        });
                    });
                    // Requery the server with the new one-time export settings
                    dt.ajax.reload();
                };
                //For Export Buttons available inside jquery-datatable "server side processing" - End
            </script>
@endsection
