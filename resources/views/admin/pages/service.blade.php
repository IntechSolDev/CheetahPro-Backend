@extends('admin.layouts.layouts')
@section('content')

    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/admin/')}}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Service</li>
        </ol>
        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="pd-20">
                <div class="modal fade bs-example-modal-lg" id="ajaxModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Add new service</h4>
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
                                            <label for="image">Image</label>
                                            <input type="file" id="image" name="image"
                                                   class="form-control-file form-control height-auto"
                                                   accept="image/*" onchange="readURL('service-modal-preview','service_hidden_image','image');">
                                            <input type="hidden" class="emptyImage" name="service_hidden_image"
                                                   id="service_hidden_image">
                                            <img id="service-modal-preview"
                                                 src="https://via.placeholder.com/150" alt="Preview"
                                                 class="form-group hidden" width="100" height="100">
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Icon</label>
                                            <input type="file" id="icon" name="icon"
                                                   class="form-control-file form-control height-auto"
                                                   accept="image/*" onchange="readURL('service-modal-preview_icon','service_hidden_icon','icon');">
                                            <input type="hidden" class="emptyImage" name="service_hidden_icon"
                                                   id="service_hidden_icon">
                                            <img id="service-modal-preview_icon"
                                                 src="https://via.placeholder.com/150" alt="Preview"
                                                 class="form-group hidden" width="100" height="100">
                                        </div>
                                        <div class="form-group" style="display:none;">
                                            <label>Sort Order</label>
                                            <input class="form-control" type="number" min=0  max=100 id="order_by" name="order_by">
                                        </div>
                                        <div class="form-group" style="display:none;">
                                            <label>Status</label>
                                            <div class="custom-control custom-radio ">
                                                <input type="radio" id="status" name="status" value="1" class="custom-control-input" checked>
                                                <label class="custom-control-label" for="status">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio ">
                                                <input type="radio" id="status2" name="status" value="0" class="custom-control-input">
                                                <label class="custom-control-label" for="status2">Draft</label>
                                            </div>
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
                       Service
                    </div>
                    <div class="col-md-8">
                        <div class="pull-right">
                            <a href="#" class=" float-right btn btn-primary btn-sm "  id="createNewItem" data-toggle="modal" data-target="#bd-example-modal-lg" type="button">
                                Add Service
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
                            <th>Image</th>
                            <th>Icon</th>
                            <th>Title</th>
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
                        ajax: "{{ route('service.index') }}",
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
                            {data: 'image', name: 'image'},
                            {data: 'icon', name: 'icon'},
                            {data: 'title', name: 'title'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]

                    });

//Create New Notification
                    $('#createNewItem').click(function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#image-container').empty();
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        $('#saveBtn').val("create-Item");
                        $('#Item_id').val('');
                        $('#hidden_image').val('');
                        $('#service-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#service-modal-preview_icon').attr('src', 'https://via.placeholder.com/150');
                        $('#ItemForm').trigger("reset");
                        $('#modelHeading').html("Create New service");
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
                            url: "{{ route('service.store') }}",
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
                                alert(data.error);
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    });



//Edit
                    $('body').on('click', '.editItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#service-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#service-modal-preview_icon').attr('src', 'https://via.placeholder.com/150');
                        $('#saveBtn').show();
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        var Item_id = $(this).data('id');
                        $.get("{{ route('service.index') }}" +'/' + Item_id +'/edit', function (data) {
                            $('#modelHeading').html("Edit service");
                            $('#saveBtn').val("edit-service");
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('#title').val(data.title);
                            $('#description').val(data.description);
                            $('#order_by').val(data.order_by);
                            if(data.status == "0")
                            {
                                $("#status2").prop("checked", true);
                                $("#status").prop("checked", false);
                            }
                            else
                            {
                                $("#status2").prop("checked", false);
                                $("#status").prop("checked", true);
                            }
                            if (data.image) {
                                $('#service-modal-preview').attr('src', data.image);
                                var parts = data.image.split("/");
                                var last_part = parts[parts.length-1];
                                $('#service_hidden_image').val(last_part);
                            }
                            else
                            {
                                $('#service-modal-preview').attr('src', 'https://via.placeholder.com/150');
                            }
                             if (data.icon) {
                                $('#service-modal-preview_icon').attr('src', data.icon);
                                var parts = data.icon.split("/");
                                var last_part = parts[parts.length-1];
                                $('#service_hidden_icon').val(last_part);
                               
                            }
                            else
                            {
                                $('#service-modal-preview_icon').attr('src', 'https://via.placeholder.com/150');
                            }
                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });

//View
                    $('body').on('click', '.viewItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#service-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        var Item_id = $(this).data('id');
                        $.get("{{ route('service.index') }}" +'/' + Item_id +'/edit', function (data) {
                            $('#modelHeading').html("View service");
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
                            $('#order_by').val(data.order_by);
                            if(data.status == "0")
                            {
                                $("#status2").prop("checked", true);
                                $("#status").prop("checked", false);
                            }
                            else
                            {
                                $("#status2").prop("checked", false);
                                $("#status").prop("checked", true);
                            }
                               if (data.image) {
                                $('#service-modal-preview').attr('src', data.image);
                                var parts = data.image.split("/");
                                var last_part = parts[parts.length-1];
                                $('#service_hidden_image').val(last_part);
                            }
                            else
                            {
                                $('#service-modal-preview').attr('src', 'https://via.placeholder.com/150');
                            }
                             if (data.icon) {
                                $('#service-modal-preview_icon').attr('src', data.icon);
                                var parts = data.icon.split("/");
                                var last_part = parts[parts.length-1];
                                $('#service_hidden_icon').val(last_part);
                            }
                            else
                            {
                                $('#service-modal-preview_icon').attr('src', 'https://via.placeholder.com/150');
                            }
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
                                url: "{{ route('service.store') }}"+'/'+Item_id,
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

                function readURL(preview,hidden,id) {
                    var $i = $("#" + id), // Put file input ID here
                        input = $i[0]; // Getting the element from jQuery
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $("#" + preview).attr('src', e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                        $("#" + preview).removeClass(hidden);
                        $('#start').hide();
                    }
                }


            </script>
@endsection
