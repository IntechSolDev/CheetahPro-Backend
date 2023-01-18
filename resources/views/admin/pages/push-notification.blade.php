@extends('admin.layouts.layouts')
@section('content')

    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/admin/')}}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Notification</li>
        </ol>
        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="pd-20">
                <div class="modal fade bs-example-modal-lg" id="ajaxModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Add new Notification</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" id="ItemForm" name="ItemForm"  enctype="multipart/form-data">
                                <input type="hidden" name="Item_id" id="Item_id">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input class="form-control" type="text" id="title" name="title">
                                    </div>
                                        <div class="form-group">
                                        <label>Sub Title</label>
                                        <input class="form-control" type="text" id="subtitle" name="subtitle">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="action_button" id="saveBtn" value="create" class="btn btn-primary">Save Changes</button>
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
                        Notifications
                    </div>
                <div class="col-md-8">
                    <div class="pull-right">
                        <a href="#" class=" float-right btn btn-primary btn-sm "  id="createNewItem" data-toggle="modal" data-target="#bd-example-modal-lg" type="button">
                            Add Notification
                        </a>
                    </div>
                </div>
            </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table-level"  width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Sub Title</th>
                            <th>Last Send</th>
                            <th class="datatable-nosort" >Action</th>
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
                        buttons: ['csv', 'excel', 'pdf', 'print'],
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('push-notification.index') }}",
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
                            {data: 'title', name: 'title'},
                            {data: 'subtitle', name: 'subtutle'},
                            {data: 'last_send_at', name: 'last_send_at'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]

                    });

//Create New Notification
                    $('#createNewItem').click(function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        $('#saveBtn').val("create-Item");
                        $('#Item_id').val('');
                        $('#hidden_image').val('');
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#image-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#ItemForm').trigger("reset");
                        $('#modelHeading').html("Create New Notification");
                        $('#ajaxModel').modal('show');
                        $('.ajax-loader').css("visibility", "hidden");
                    });

//Submit Edit and Create
                    $('body').on('submit', '#ItemForm', function (e) {
                        e.preventDefault();
                        $('#saveBtn').html('Sending..');
                        var formData = new FormData(this);
                        $.ajax({
                            data: formData,
                            url: "{{ route('push-notification.store') }}",
                            type: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (data) {
                                $('#saveBtn').html('Save Changes');
                                $('#ItemForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                alert(data.error);
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    });
                    
//Send Notification
                    $('body').on('click', '.sendNoti', function (e) {
                         e.preventDefault();
                         
                          $('.ajax-loader').css("visibility", "visible");
                        var Item_id = $(this).data('id');
                        $('#sendnoti' + Item_id).html('Sending..');
                        var currentdate = new Date(); 
                        var datetime =   currentdate.getFullYear()+ "-"
                                        + (currentdate.getMonth()+1)  + "-" 
                                        + currentdate.getDate()  + " "  
                                        + currentdate.getHours() + ":"  
                                        + currentdate.getMinutes() + ":" 
                                        + currentdate.getSeconds();
                                        
                          var data = {
                                id: Item_id,
                                last_send_at:datetime,
                            };
                        $.ajax({
                            data: JSON.stringify(data),
                            url: "{{ route('send.notification') }}",
                            type: "POST",
                            cache: false,
                            contentType: "application/json",
                            processData: false,
                            dataType: 'json',
                            success: function (data) {
                                 alert(data.success);
                                 table.draw();
                                 $('#sendnoti' + Item_id).html('Sent');
                            },
                            error: function (data) {
                                $('#sendnoti' + Item_id).html('Sent');
                            }
                           
                        });
                          $('.ajax-loader').css("visibility", "hidden");
                    });

//Edit
                    $('body').on('click', '.editItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                     $('#image-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        var Item_id = $(this).data('id');
                        $.get("{{ route('push-notification.index') }}" +'/' + Item_id +'/edit', function (data) {
                            $('#modelHeading').html("Edit Notification");
                            $('#saveBtn').val("edit-notification");
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('#title').val(data.title);
                            $('#subtitle').val(data.subtitle);
                            $('#description').val(data.description);
                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });

//View
                    $('body').on('click', '.viewItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                         $('#image-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        var Item_id = $(this).data('id');
                        $.get("{{ route('push-notification.index') }}" +'/' + Item_id +'/edit', function (data) {
                            $('#modelHeading').html("View Notification");
                            $('#saveBtn').hide();
                            $('#ItemForm input').attr('readonly', true);
                            $('#ItemForm textarea').attr('readonly', true);
                            $('#ItemForm #thumbnail_image').hide();
                            $('#ItemForm input:radio').attr('disabled', true);
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('#title').val(data.title);
                            $('#subtitle').val(data.subtitle);
                            $('#description').val(data.description);
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
                                url: "{{ route('push-notification.store') }}"+'/'+Item_id,
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
            

            </script>
@endsection
