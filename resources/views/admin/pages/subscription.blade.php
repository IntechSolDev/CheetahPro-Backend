@extends('admin.layouts.layouts')
@section('content')
    <style>
        .dataTable th, .dataTable td {
            max-width: 100px;
            min-width: 70px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dataTable th, .dataTable td:first-child {
            max-width: 20px;
            min-width: 20px;
        }
        .trialbtn
        {
            margin-right:10px;
        }
         .trialbtn input[type="number"] {
            width: 50px;
        }
    </style>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{url('/admin/')}}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Subscription</li>
        </ol>
        <!-- DataTables Example -->
        <div class="card mb-3">
            <div class="pd-20">
                <div class="modal fade bs-example-modal-lg" id="ajaxModel2" tabindex="-1" role="dialog"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Subscription</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" id="ItemForm" name="ItemForm2" enctype="multipart/form-data">
                                <input type="hidden" name="Item_id" id="Item_id">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control" id="title" placeholder="Title" name ="title"  required value="Trial">
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Sub Title</label>
                                            <input type="text" class="form-control" id="sub_title" placeholder="Sub Title" name ="sub_title" required value="{{old('sub_title')}}">
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea rows="5" name ="description" id="description"  required placeholder="Description" class="form-control" ></textarea>
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Duration</label>
                                            <input type="text" class="form-control" id="duration" placeholder="Duration" name ="duration" required value="{{old('duration')}}">
                                        </div>
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
                <div class="modal fade bs-example-modal-lg" id="ajaxModel" tabindex="-1" role="dialog"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modelHeading">Subscription</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" id="ItemForm" name="ItemForm" enctype="multipart/form-data">
                                <input type="hidden" name="Item_id" id="Item_id">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control" id="title" placeholder="Title" name ="title"  required value="{{old('title')}}">
                                            <p style="font-size:10px">*Title Must be Unique (Stripe Product Name)</p>
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Sub Title</label>
                                            <input type="text" class="form-control" id="sub_title" placeholder="Sub Title" name ="sub_title" required value="{{old('sub_title')}}">
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea rows="5" name ="description" id="description"  required placeholder="Description" class="form-control" ></textarea>
                                        </div>
                                        </div>
                                          <div class="col-md-6 col-sm-12 show_on_edit" style="display:none">
                                        <div class="form-group">
                                            <label>Stripe Product Id</label>
                                          <input type="text" class="form-control" id="stripe_product_id" placeholder="Stripe Product Id" name ="stripe_product_id" >
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <select class="form-control" id="currency" name="currency">
                                                <option value="USD" selected="selected" label="US dollar">USD</option>
                                                <option value="EUR" label="Euro">EUR</option>
                                                <option value="JPY" label="Japanese yen">JPY</option>
                                                <option value="GBP" label="Pound sterling">GBP</option>
                                                <option disabled>──────────</option>
                                                <option value="AED" label="United Arab Emirates dirham">AED</option>
                                                <option value="AFN" label="Afghan afghani">AFN</option>
                                                <option value="ALL" label="Albanian lek">ALL</option>
                                                <option value="AMD" label="Armenian dram">AMD</option>
                                                <option value="ANG" label="Netherlands Antillean guilder">ANG</option>
                                                <option value="AOA" label="Angolan kwanza">AOA</option>
                                                <option value="ARS" label="Argentine peso">ARS</option>
                                                <option value="AUD" label="Australian dollar">AUD</option>
                                                <option value="AWG" label="Aruban florin">AWG</option>
                                                <option value="AZN" label="Azerbaijani manat">AZN</option>
                                                <option value="BAM" label="Bosnia and Herzegovina convertible mark">BAM</option>
                                                <option value="BBD" label="Barbadian dollar">BBD</option>
                                                <option value="BDT" label="Bangladeshi taka">BDT</option>
                                                <option value="BGN" label="Bulgarian lev">BGN</option>
                                                <option value="BHD" label="Bahraini dinar">BHD</option>
                                                <option value="BIF" label="Burundian franc">BIF</option>
                                                <option value="BMD" label="Bermudian dollar">BMD</option>
                                                <option value="BND" label="Brunei dollar">BND</option>
                                                <option value="BOB" label="Bolivian boliviano">BOB</option>
                                                <option value="BRL" label="Brazilian real">BRL</option>
                                                <option value="BSD" label="Bahamian dollar">BSD</option>
                                                <option value="BTN" label="Bhutanese ngultrum">BTN</option>
                                                <option value="BWP" label="Botswana pula">BWP</option>
                                                <option value="BYN" label="Belarusian ruble">BYN</option>
                                                <option value="BZD" label="Belize dollar">BZD</option>
                                                <option value="CAD" label="Canadian dollar">CAD</option>
                                                <option value="CDF" label="Congolese franc">CDF</option>
                                                <option value="CHF" label="Swiss franc">CHF</option>
                                                <option value="CLP" label="Chilean peso">CLP</option>
                                                <option value="CNY" label="Chinese yuan">CNY</option>
                                                <option value="COP" label="Colombian peso">COP</option>
                                                <option value="CRC" label="Costa Rican colón">CRC</option>
                                                <option value="CUC" label="Cuban convertible peso">CUC</option>
                                                <option value="CUP" label="Cuban peso">CUP</option>
                                                <option value="CVE" label="Cape Verdean escudo">CVE</option>
                                                <option value="CZK" label="Czech koruna">CZK</option>
                                                <option value="DJF" label="Djiboutian franc">DJF</option>
                                                <option value="DKK" label="Danish krone">DKK</option>
                                                <option value="DOP" label="Dominican peso">DOP</option>
                                                <option value="DZD" label="Algerian dinar">DZD</option>
                                                <option value="EGP" label="Egyptian pound">EGP</option>
                                                <option value="ERN" label="Eritrean nakfa">ERN</option>
                                                <option value="ETB" label="Ethiopian birr">ETB</option>
                                                <option value="EUR" label="EURO">EUR</option>
                                                <option value="FJD" label="Fijian dollar">FJD</option>
                                                <option value="FKP" label="Falkland Islands pound">FKP</option>
                                                <option value="GBP" label="British pound">GBP</option>
                                                <option value="GEL" label="Georgian lari">GEL</option>
                                                <option value="GGP" label="Guernsey pound">GGP</option>
                                                <option value="GHS" label="Ghanaian cedi">GHS</option>
                                                <option value="GIP" label="Gibraltar pound">GIP</option>
                                                <option value="GMD" label="Gambian dalasi">GMD</option>
                                                <option value="GNF" label="Guinean franc">GNF</option>
                                                <option value="GTQ" label="Guatemalan quetzal">GTQ</option>
                                                <option value="GYD" label="Guyanese dollar">GYD</option>
                                                <option value="HKD" label="Hong Kong dollar">HKD</option>
                                                <option value="HNL" label="Honduran lempira">HNL</option>
                                                <option value="HKD" label="Hong Kong dollar">HKD</option>
                                                <option value="HRK" label="Croatian kuna">HRK</option>
                                                <option value="HTG" label="Haitian gourde">HTG</option>
                                                <option value="HUF" label="Hungarian forint">HUF</option>
                                                <option value="IDR" label="Indonesian rupiah">IDR</option>
                                                <option value="ILS" label="Israeli new shekel">ILS</option>
                                                <option value="IMP" label="Manx pound">IMP</option>
                                                <option value="INR" label="Indian rupee">INR</option>
                                                <option value="IQD" label="Iraqi dinar">IQD</option>
                                                <option value="IRR" label="Iranian rial">IRR</option>
                                                <option value="ISK" label="Icelandic króna">ISK</option>
                                                <option value="JEP" label="Jersey pound">JEP</option>
                                                <option value="JMD" label="Jamaican dollar">JMD</option>
                                                <option value="JOD" label="Jordanian dinar">JOD</option>
                                                <option value="JPY" label="Japanese yen">JPY</option>
                                                <option value="KES" label="Kenyan shilling">KES</option>
                                                <option value="KGS" label="Kyrgyzstani som">KGS</option>
                                                <option value="KHR" label="Cambodian riel">KHR</option>
                                                <option value="KID" label="Kiribati dollar">KID</option>
                                                <option value="KMF" label="Comorian franc">KMF</option>
                                                <option value="KPW" label="North Korean won">KPW</option>
                                                <option value="KRW" label="South Korean won">KRW</option>
                                                <option value="KWD" label="Kuwaiti dinar">KWD</option>
                                                <option value="KYD" label="Cayman Islands dollar">KYD</option>
                                                <option value="KZT" label="Kazakhstani tenge">KZT</option>
                                                <option value="LAK" label="Lao kip">LAK</option>
                                                <option value="LBP" label="Lebanese pound">LBP</option>
                                                <option value="LKR" label="Sri Lankan rupee">LKR</option>
                                                <option value="LRD" label="Liberian dollar">LRD</option>
                                                <option value="LSL" label="Lesotho loti">LSL</option>
                                                <option value="LYD" label="Libyan dinar">LYD</option>
                                                <option value="MAD" label="Moroccan dirham">MAD</option>
                                                <option value="MDL" label="Moldovan leu">MDL</option>
                                                <option value="MGA" label="Malagasy ariary">MGA</option>
                                                <option value="MKD" label="Macedonian denar">MKD</option>
                                                <option value="MMK" label="Burmese kyat">MMK</option>
                                                <option value="MNT" label="Mongolian tögrög">MNT</option>
                                                <option value="MOP" label="Macanese pataca">MOP</option>
                                                <option value="MRU" label="Mauritanian ouguiya">MRU</option>
                                                <option value="MUR" label="Mauritian rupee">MUR</option>
                                                <option value="MVR" label="Maldivian rufiyaa">MVR</option>
                                                <option value="MWK" label="Malawian kwacha">MWK</option>
                                                <option value="MXN" label="Mexican peso">MXN</option>
                                                <option value="MYR" label="Malaysian ringgit">MYR</option>
                                                <option value="MZN" label="Mozambican metical">MZN</option>
                                                <option value="NAD" label="Namibian dollar">NAD</option>
                                                <option value="NGN" label="Nigerian naira">NGN</option>
                                                <option value="NIO" label="Nicaraguan córdoba">NIO</option>
                                                <option value="NOK" label="Norwegian krone">NOK</option>
                                                <option value="NPR" label="Nepalese rupee">NPR</option>
                                                <option value="NZD" label="New Zealand dollar">NZD</option>
                                                <option value="OMR" label="Omani rial">OMR</option>
                                                <option value="PAB" label="Panamanian balboa">PAB</option>
                                                <option value="PEN" label="Peruvian sol">PEN</option>
                                                <option value="PGK" label="Papua New Guinean kina">PGK</option>
                                                <option value="PHP" label="Philippine peso">PHP</option>
                                                <option value="PKR" label="Pakistani rupee">PKR</option>
                                                <option value="PLN" label="Polish złoty">PLN</option>
                                                <option value="PRB" label="Transnistrian ruble">PRB</option>
                                                <option value="PYG" label="Paraguayan guaraní">PYG</option>
                                                <option value="QAR" label="Qatari riyal">QAR</option>
                                                <option value="RON" label="Romanian leu">RON</option>
                                                <option value="RON" label="Romanian leu">RON</option>
                                                <option value="RSD" label="Serbian dinar">RSD</option>
                                                <option value="RUB" label="Russian ruble">RUB</option>
                                                <option value="RWF" label="Rwandan franc">RWF</option>
                                                <option value="SAR" label="Saudi riyal">SAR</option>
                                                <option value="SEK" label="Swedish krona">SEK</option>
                                                <option value="SGD" label="Singapore dollar">SGD</option>
                                                <option value="SHP" label="Saint Helena pound">SHP</option>
                                                <option value="SLL" label="Sierra Leonean leone">SLL</option>
                                                <option value="SLS" label="Somaliland shilling">SLS</option>
                                                <option value="SOS" label="Somali shilling">SOS</option>
                                                <option value="SRD" label="Surinamese dollar">SRD</option>
                                                <option value="SSP" label="South Sudanese pound">SSP</option>
                                                <option value="STN" label="São Tomé and Príncipe dobra">STN</option>
                                                <option value="SYP" label="Syrian pound">SYP</option>
                                                <option value="SZL" label="Swazi lilangeni">SZL</option>
                                                <option value="THB" label="Thai baht">THB</option>
                                                <option value="TJS" label="Tajikistani somoni">TJS</option>
                                                <option value="TMT" label="Turkmenistan manat">TMT</option>
                                                <option value="TND" label="Tunisian dinar">TND</option>
                                                <option value="TOP" label="Tongan paʻanga">TOP</option>
                                                <option value="TRY" label="Turkish lira">TRY</option>
                                                <option value="TTD" label="Trinidad and Tobago dollar">TTD</option>
                                                <option value="TVD" label="Tuvaluan dollar">TVD</option>
                                                <option value="TWD" label="New Taiwan dollar">TWD</option>
                                                <option value="TZS" label="Tanzanian shilling">TZS</option>
                                                <option value="UAH" label="Ukrainian hryvnia">UAH</option>
                                                <option value="UGX" label="Ugandan shilling">UGX</option>
                                                <option value="USD" label="United States dollar">USD</option>
                                                <option value="UYU" label="Uruguayan peso">UYU</option>
                                                <option value="UZS" label="Uzbekistani soʻm">UZS</option>
                                                <option value="VES" label="Venezuelan bolívar soberano">VES</option>
                                                <option value="VND" label="Vietnamese đồng">VND</option>
                                                <option value="VUV" label="Vanuatu vatu">VUV</option>
                                                <option value="WST" label="Samoan tālā">WST</option>
                                                <option value="XAF" label="Central African CFA franc">XAF</option>
                                                <option value="XCD" label="Eastern Caribbean dollar">XCD</option>
                                                <option value="XOF" label="West African CFA franc">XOF</option>
                                                <option value="XPF" label="CFP franc">XPF</option>
                                                <option value="ZAR" label="South African rand">ZAR</option>
                                                <option value="ZMW" label="Zambian kwacha">ZMW</option>
                                                <option value="ZWB" label="Zimbabwean bonds">ZWB</option>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="form-control" id="amount" placeholder="Amount" name ="amount" required value="{{old('amount')}}">
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="plan_duration">Billing period</label>
                                            <select class="form-control" id="plan_duration" name="plan_duration" required>
                                                <option class="month" value="month,1">Month</option>
                                                <option class="month" value="month,6">Bi-Annual (6 month)</option>
                                                <option class="year" value="year,1">Annual (12 month)</option>
                                                <option class="year" value="year,1">Premium</option>
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Sort Order</label>
                                            <input type="number" class="form-control" placeholder="sort_order" id="sort_order" name ="sort_order" required value="{{old('sort_order')}}">
                                        </div>
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
                        Subscription
                    </div>
                                        <div class="col-md-8">
                                             <div class="pull-right">
                                                <a href="#" class=" float-right btn btn-primary btn-sm " id="createNewItem"
                                                   data-toggle="modal" data-target="#bd-example-modal-lg" type="button">
                                                    Add Subscription Plan
                                                </a>
                                            </div>
                                               <div class="pull-right">
                                                   <div class="trialbtn float-right">
                                                   <input  type="number" min="0" pattern="\d*" name="settrial" />
                                                <a href="#" class=" float-right btn btn-warning btn-sm set-trialbtn" id="createNewtrial"
                                                   data-toggle="modal" data-target="#bd-example-modal-lg" type="button">
                                                    Set Trial
                                                </a>
                                                 </div>
                                            </div>
                                        </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table-user" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Name</th>
                            <th>Intervals</th>
                            <th>Duration</th>
                            <th>Currency</th>
                            <th>Price</th>

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
                        ajax: "{{ route('subscription.index') }}",
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
                            {data: 'description', name: 'description'},
                            {data: 'duration', name: 'duration'},
                            {data: 'currency', name: 'currency'},
                            {data: 'price', name: 'price'},
                            {data: 'action', name: 'action'},
                        ]
                    });
//Create New User
                    $('#createNewItem').click(function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('#password').attr('required',true);
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        $('#currency').removeAttr('disabled');
                        $('#plan_duration').removeAttr('disabled');
                        $('#amount').removeAttr('readonly');
                        $('#saveBtn').val("create-Item");
                        $('#Item_id').val('');
                        $('#hidden_image').val('');
                        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#image-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $('#ItemForm').trigger("reset");
                        $('#modelHeading').html("Create New Subscription");
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
                            url: "{{ route('subscription.store') }}",
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
                                var json =  JSON.parse(JSON.stringify(data))
                                alert(json.responseJSON.message);
                                $('.ajax-loader').css("visibility", "hidden");
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    });


//View
                    $('body').on('click', '.viewItem', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#category-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        var Item_id = $(this).data('id');
                        $.get("{{ route('subscription.index') }}" +'/' + Item_id +'/edit', function (data) {
                            $('#modelHeading').html("View Subscription");
                            $('#saveBtn').hide();
                            $('#ItemForm input').attr('readonly', true);
                            $('#ItemForm textarea').attr('readonly', true);
                            $('#ItemForm #thumbnail_image').hide();
                            $('#ItemForm input:radio').attr('disabled', true);
                            $('#ajaxModel').modal('show');
                            $('#currency').attr('disabled', 'disabled');
                            $('#plan_duration').attr('disabled', 'disabled');
                            $('#Item_id').val(data.id);
                            $('#title').val(data.title);
                            $('#sub_title').val(data.sub_title);
                            $('#description').val(data.description);
                            $('#currency').val(data.currency.toUpperCase()).attr("selected","selected");
                            $('#amount').val(data.price);
                            $('#plan_duration').val(data.plan_duration +','+data.duration).attr("selected","selected");
                            $('#sort_order').val(data.sort_order);
                            
                            $('.ajax-loader').css("visibility", "hidden");
                        })

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
                        $.get("{{ route('subscription.index') }}" + '/' + Item_id + '/edit', function (data) {
                            $('#modelHeading').html("Edit User");
                            $('#currency').attr('disabled', 'disabled');
                            $('#plan_duration').attr('disabled', 'disabled');
                            $('#amount').attr('readonly', true);
                            $('#title').attr('readonly', true);
                            $('#saveBtn').val("edit-user");
                            $('#ajaxModel').modal('show');
                            $('#Item_id').val(data.id);
                            $('#title').val(data.title);
                            $('#sub_title').val(data.sub_title);
                            $('#description').val(data.description);
                            $('#currency').val(data.currency.toUpperCase()).attr("selected","selected");
                            $('#amount').val(data.price);
                            $('#plan_duration').val(data.plan_duration +','+data.duration).attr("selected","selected");
                            $('#sort_order').val(data.sort_order);
                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });


//Delete
                    $('body').on('click', '.deleteItem', function () {

                        var Item_id = $(this).data("id");

                        if (confirm("Are You sure want to delete subscription plan!")) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('subscription.store') }}" + '/' + Item_id,
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


//Set Trial
                    $('body').on('click', '.set-trialbtn', function () {
                        $('.ajax-loader').css("visibility", "visible");
                        $('#saveBtn').show();
                        $('#password').attr('required',false);
                        $('input').attr('readonly', false);
                        $('textarea').attr('readonly', false);
                        $('input:radio').attr('disabled', false);
                        var Item_id = $(this).data('id');
                        $('#image-modal-preview').attr('src', 'https://via.placeholder.com/150');
                        $.get("{{ route('subscription.trial') }}", function (data) {
                            $('#modelHeading').html("Set Trial");
                            $('#saveBtn').val("edit-user");
                            $('#ajaxModel2').modal('show');
                            $('#Item_id').val(data.id);
                            $('#title').val(data.title);
                            $('#sub_title').val(data.sub_title);
                            $('#description').val(data.description);
                            $('#duration').val(data.duration);
                            $('#sort_order').val(data.sort_order);
                            $('.ajax-loader').css("visibility", "hidden");
                        })
                    });



//Submit Trial
                    $('body').on('submit', '#ItemForm2', function (e) {
                        e.preventDefault();
                        $('#saveBtn').html('Sending..');
                        $('.ajax-loader').css("visibility", "visible");
                        var formData = new FormData(this);
                        $.ajax({
                            data: formData,
                            url: "{{ route('subscription.trial.store') }}",
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
                                var json =  JSON.parse(JSON.stringify(data))
                                alert(json.responseJSON.message);
                                $('.ajax-loader').css("visibility", "hidden");
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    });



                });

                function readURL(preview, hidden, id) {
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
