@extends('admin.layouts.layouts')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/admin/')}}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Order</li>
        </ol>
        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="pd-20">
                   <div class="modal fade bs-example-modal-lg"  id="ajaxModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 1500px;">
                           <div class="modal-dialog modal-lg modal-dialog-centered"  style="max-width: 1500px;">
                        <div class="modal-content">
                             <div class="modal-body">
                                    <div class="card">
                                        <div class="card-header">Invoice
                                            <strong >#<span class="invoice_order_id">BBB-10010110101938</span></strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <div class="col-sm-4">
                                                    <h6 class="mb-3">From:</h6>
                                                    <div>
                                                        <strong id="customer_name">BBBootstrap.com</strong>
                                                    </div>
                                                    <div id="customer_address"></div>
                                                    <div>Email: <span id="customer_email"></span></div>
                                                    <div>Phone: <span id="customer_phone"></span></div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <h6 class="mb-3">To:</h6>
                                                    <div>
                                                        <strong id="provider_name"></strong>
                                                    </div>
                                                    <div id="provider_address"></div>
                                                    <div>Email: <span id="provider_email"></span></div>
                                                     <div>Phone: <span id="provider_phone"></span></div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <h6 class="mb-3">Details:</h6>
                                                    <div>Invoice
                                                        <strong>#<span class="invoice_order_id"></span></strong>
                                                    </div>
                                                    <div >Date: <span id="booking_date"></span></div>
                                                    <div id="booking_address">VAT: NYC09090390</div>
                                                    <div><strong>Amount: $<span class="booking_amount"></span></strong></div>

                                                </div>
                                            </div>
                                            <div class="table-responsive-sm">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th class="center">#</th>
                                                        <th>Service</th>
                                                        <th class="right">Service Charges</th>
                                                        <th class="right">Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="invoice_product">

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-sm-5"></div>
                                                <div class="col-lg-4 col-sm-5 ml-auto">
                                                    <table class="table table-clear">
                                                        <tbody style="text-align: right;">
                                        
                                                        <tr>
                                                            <td class="right">
                                                                <strong>Total</strong>
                                                            </td>
                                                            <td class="right">
                                                                <strong>$ <span id="grandtotal"></span></strong>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                   
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <!--<button id="btnPrint" type="button" onclick=" window.print();"  class="btn btn-default"><i class="fa fa-print"></i> Print</button>-->
                                </div>
                                    </div>
                                    </div>
                    </div>
                </div>

                <div  class="modal fade bs-example-modal-lg" id="ajaxModel2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div style="max-width: 920px;" class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Products</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-body">

                                        <div class="table-responsive protbl">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <i class="fas fa-table"></i>
                        Order
                    </div>
{{--                    <div class="col-md-8">--}}
{{--                        <div class="pull-right">--}}
{{--                            <a href="#" class=" float-right btn btn-primary btn-sm "  id="createNewItem" data-toggle="modal" data-target="#bd-example-modal-lg" type="button">--}}
{{--                                Add Order--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table-level"  width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Booking ID</th>
                            <th>Username</th>
                            <th>Provider</th>
                            <th>Amount</th>
                            <th>Created at</th>
                            <th>Status</th>
                            <th class="datatable-nosort" >Booking Services</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--End Section -->
        @endsection
        @section('page-script')
            <script type="text/javascript">
                $(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var table = $('.data-table-level').DataTable({
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
                        ajax: "{{ route('order.index') }}",
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
                            {data: 'booking_uuid', name: 'booking_uuid'},
                            {data: 'username', name: 'username'},
                            {data: 'provider_name', name: 'provider_name'},
                            {data: 'total_amount', name: 'total_amount'},
                            {data: 'booking_date', name: 'booking_date'},
                            {data: 'booking_status', render: function(data) { 
                                if(data == 'request_completed') 
                                 return '<span class="badge badge-success">Request Completed</span>'; 
                                  else if(data == 'arrived')  
                                 return '<span class="badge badge-warning">Arrived</span>';
                                  else if(data == 'pending')  
                                 return '<span class="badge badge-info" style="background:#000000ad">Pending</span>';
                                  else if(data == 'on_the_way')  
                                 return '<span class="badge badge-light">On The Way</span>';
                                  else if(data == 'completed')  
                                 return '<span class="badge badge-success">Completed</span>';
                                  else if(data == 'cancelled')
                                 return '<span class="badge badge-danger">Canceled</span>';
                                  else if(data == 'accepted')  
                                 return '<span class="badge badge-info">Accepted</span>';
                            }},
                             {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]

                    });
                    $("#import_file").change(function(){
                        $('input').removeAttr('required');
                    });
//Create New Product
                    $('#createNewItem').click(function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#image-container').empty();
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        $("input[type=text]").attr('required', true);
                        $("input[type=number]").attr('required', true);
                        $('#saveBtn').val("create-Item");
                        $('#Item_id').val('');
                        $('#ItemForm').trigger("reset");
                        $('#modelHeading').html("Create New Product");
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
                            url: "{{ route('order.store') }}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (data) {
                                $('#saveBtn').html('Save Changes');
                                $('#ItemForm').trigger("reset");
                                alert(data.success);
                                $('#ajaxModel').modal('hide');
                                $('.ajax-loader').css("visibility", "hidden");
                                table.draw();
                            },
                            error: function (data) {
                                $('.ajax-loader').css("visibility", "hidden");
                                alert(data.error);
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    });



//Edit
                    $('body').on('click', '.editItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        var Item_id = $(this).data('id');
                        $.get("{{ route('order.index') }}" +'/' + Item_id +'/edit', function (data) {
                            $('#modelHeading').html("Edit Product");
                            $('#saveBtn').val("edit-product");
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('#sku').val(data.sku);
                            $('#name').val(data.name);
                            $('#unitPrice').val(data.unitPrice);
                            $('#minQty').val(data.minQty);
                            $('#multQty').val(data.multQty);
                            $('#barcode').val(data.barcode);
                            $('#longDesc').val(data.longDesc);
                            $('#category').val(data.category);
                            if(data.status === 0)
                            {
                                $("#status2").prop("checked", true);
                                $("#status").prop("checked", false);
                            }
                            else
                            {
                                $("#status2").prop("checked", false);
                                $("#status").prop("checked", true);
                            }
                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });




//View
                    $('body').on('click', '.viewItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        var Item_id = $(this).data('id');
                        $.get("{{ route('invoice') }}" +'/' + Item_id, function (data) {
                            $(".invoice_product").empty();
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('.invoice_order_id').text(data.booking.booking_uuid);
                            $('#booking_date').text(data.booking.booking_date);
                            $('.booking_amount').text(data.booking.total_amount);
                            $('#booking_address').text(data.booking.address);
                            $('#customer_name').text(data.customer.first_name +' '+ data.customer.last_name);
                            $('#customer_address').text(data.customer.address);
                            $('#customer_email').text(data.customer.email);
                            $('#customer_phone').text(data.customer.contactno);
                            $('#provider_name').text(data.provider.first_name +' '+ data.provider.last_name);
                            $('#provider_address').text(data.provider.address);
                            $('#provider_postalcode').text(data.provider.postal_code);
                            $('#provider_email').text(data.provider.email);
                            $('#provider_phone').text(data.provider.contactno);
                      
                            $.each(data.booking_service_data, function( key, value ) {
                                var unit_price = value.service_charges;
                                $(".invoice_product").append('<tr>');
                                $.each( value, function( key_inner1, value_inner1 ) {
                                    if(key_inner1 == 'subservice')
                                    {
                                        key = key+1;
                                         $(".invoice_product").append('<td>'+key+'</td>');
                                          const product_data = [];
                                            $.each( value_inner1, function( key_inner2, value_inner2 ) {
                                                if(key_inner2 == 'title')
                                                {
                                                    product_data.push(value_inner2);
                                                }
                                                if(key_inner2 == 'description')
                                                {
                                                    var res = value_inner2;
                                                    product_data.push(res);
                                                }
                                              });
                                        $(".invoice_product").append('<td class="left">'+product_data[0]+'</td>\n' +
                                            '                                                        <td class="right">$'+unit_price+'</td>\n' +
                                            '                                                        <td class="right">$'+unit_price+'</td>\n' +
                                            '                                                       ');
                                       
                                    }
                                });
                                 $(".invoice_product").append('</tr>');
                            });
                            $('#grandtotal').text(data.booking.total_amount);

                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });



//Delete
                    $('body').on('click', '.deleteItem', function () {

                        var Item_id = $(this).data("id");

                        if( confirm("Are You sure want to delete !"))
                        {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('order.store') }}"+'/'+Item_id,
                                success: function (data) {
                                    table.draw();
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
