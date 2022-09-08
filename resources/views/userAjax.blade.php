<!DOCTYPE html>
<html>
<head>
    <title>Laravel </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>   -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>






</head>
<body>
      
<div class="container">
    <h1>User Records</h1>
    <a class="btn btn-success"  style="float:right;" id="createNewProduct"> Create New User Record</a><br><br><br>

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Experience</th>
                
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
     
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="productForm" name="productForm" class="form-horizontal" enctype="multipart/form-data">
                 
                <!-- <input type="hidden" name="product_id" id="product_id"> -->
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Full Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter Full Name" value="" maxlength="50" required="" required>
                            @if($errors->has('fullname'))
                                <span class="invalid-feedback">
                                   <strong> {{ $errors->first('fullname') }}</strong></span>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" maxlength="50" required="">
                        </div>
                    </div>
       
                    <div class="form-group">
                        <label class="col-sm-6 control-label">Date of Joining</label>
                        <div class="col-sm-6">
                        <input type="date" class="form-control" id="doj" name="doj" placeholder="Enter DOJ" value=""  required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label">Date of Living </label>
                        <div class="col-sm-6">
                        <input type="date" class="form-control" id="dol" name="dol" placeholder="Enter DOL" value=""  required="">
                        </div>
                        <div class="col-sm-6">
                        <fieldset id="check_options">
                            <br>
                                 <input type="checkbox" class="flat-red options" value="1" id="work_status[]" name="work_status" checked>&nbsp;Still Working
                                
                                 <input type="hidden" class="flat-red options" value="0" id="work_status[]" name="work_status">
                                 
                                 
                              </fieldset>
                        </div>
                    </div>
                     
                    <div class="form-group">
                        <label class="col-sm-6 control-label">Upload Image</label>
                        <div class="col-sm-12">
                        <input type="file"  id="image1" name="image1" >
                        </div>
                    </div>
        
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
      
</body>


<script type="text/javascript">
  $(function () {
    var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
      
    /*------------------------------------------
     --------------------------------------------
     Pass Header Token
     --------------------------------------------
     --------------------------------------------*/ 
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    $('#createNewProduct').click(function () {
        //alert('coming');
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Create New User Record");
        $('#ajaxModel').modal('show');
    });


    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
        // let x = new FormData();
        // x.append("image1", data);
        var files = $('#image1')[0].files;
       // var formData = new FormData();
       // var formData = $("form").serialize()
        //formData = new FormData(this);
        var formData = new FormData($('#productForm')[0]);
        

        // Append data 
        formData.append('image1',files[0]);
       // formData.append('image1',files[0]);
        formData.append('_token',CSRF_TOKEN);
        $( "#productForm" ).validate({
        rules: {
     fullname: "required",
       
       image1: {
                      required: true,
                      extension: "jpg|jpeg|png|webp",
                       
                   },
                }
            });

        $.ajax({

          data: formData,
          url: "{{ route('users-ajax-crud.store') }}",
          type: "POST",
          //enctype: 'multipart/form-data',
          dataType: 'json',
          cache:false,
          contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
          processData: false, 
          success: function (data) {
       
              $('#productForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
           
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });
    /*------------------------------------------
    --------------------------------------------
    Render DataTable
    --------------------------------------------
    --------------------------------------------*/
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users-ajax-crud.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'fullname', name: 'fullname'},
            {data: 'email', name: 'email'},
            {data: 'exp', name: 'exp'},
           

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
      
    /*------------------------------------------
    --------------------------------------------
    Click to Button
    --------------------------------------------
    --------------------------------------------*/
 
      
    /*------------------------------------------
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.editProduct', function () {
      var product_id = $(this).data('id');
      $.get("{{ route('users-ajax-crud.index') }}" +'/' + product_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Product");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#product_id').val(data.id);
          $('#name').val(data.name);
          $('#detail').val(data.detail);
      })
    });
      
    /*------------------------------------------
    --------------------------------------------

      
    /*------------------------------------------
    --------------------------------------------
    Delete Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deleteProduct', function () {
     
        var product_id = $(this).data("id");
        confirm("Are You sure want to delete !");
        
        $.ajax({
            type: "DELETE",
            url: "{{ route('users-ajax-crud.store') }}"+'/'+product_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
       
  });
</script>

</html>