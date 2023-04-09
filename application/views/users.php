<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<body>

    <div class="container">
        <h2>Users list</h2>
        <button class="btn btn-primary" id="addNew">Add User</button>
        <p id="userMsg"></p>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Added On</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    <tr><td colspan='6'>Fetching records...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="actionModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="modalTitle">Edit Data</h4>
          </div>
          <div class="modal-body">
            <div class="mb-2">
                <label for="name">Name<sup class=text-danger>*</sup></label>
                <input class="form-control" type="text" id="name" name="name" placeholder="" />
            </div>
            <div class="mb-2">
                <label for="email">Email<sup class=text-danger>*</sup></label>
                <input class="form-control" type="text" id="email" name="email" placeholder="" />
            </div>
            <div class="mb-2">
                <label for="mobile">Mobile<sup class=text-danger>*</sup></label>
                <input class="form-control" type="text" id="mobile" name="mobile" placeholder="" />
            </div>
            <input type="hidden" id="formId">
            <p id="modalMsg"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" id="saveBtn">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>


</body>
</html>


<script type="text/javascript">

    fetchData();

    $('#addNew').click(function (){
        $('#formId').val(0);
        $('#modalTitle').html('Add New');
        ClearInputs();
        $('#actionModal').modal('show');
        $('#modalMsg').html('').removeClass('text-success text-danger');
    });

    function fetchData(){

        html = "<tr><td colspan='6' class='text-primary text-center'>Processing.</td></tr>";
        $('#table > tbody').html(html);
        $.ajax({

            url: "<?php echo base_url('welcome/fetchUsers') ?>",
            type: "GET",
            dataType: 'json',
            success: function (data) {
                $('#table > tbody').html('');
                if (data.code == 1) {

                    result = data.result;
                    result.forEach(function(row) {
                        formId = row.id;
                        html = '<tr><td>'+row.sno+'</td>';
                        html+= '<td>'+row.name+'</td>';
                        html+= '<td>'+row.email+'</td>';
                        html+= '<td>'+row.mobile+'</td>';
                        html+= '<td>'+row.added_on+'</td>';
                        html+= "<td><div class='d-flex'>"+
                            '<a class="btn btn-outline-warning font-16 btn-xs mr-2" data-toggle="tooltip" data-original-title="View News" onclick="editData('+formId+')">Edit </a>'+

                            '<a  onclick="confirmDelete('+formId+')" class="btn btn-outline-danger font-16 btn-xs" data-toggle="tooltip" title="Delete"> Delete </a>'+
                        "</div></td>";
                        html+= "</tr>";

                        $('#table > tbody').append(html);
                    });
                } else {

                    html = "<tr><td colspan='6' class='text-danger text-center'>No records found.</td></tr>";
                    $('#table > tbody').html(html);
                }
            },
            error: function (e) {
                html = "<tr><td colspan='6' class='text-danger text-center'>No records found.</td></tr>";
                $('#table > tbody').html(html);
            }
        });
    }


    function editData(formId, isEdit = 1) {

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "<?php echo base_url('welcome/getSingleUser') ?>",
            data: {
                formId: formId
            },
            success: function (data) {

                if (data.code == 1) {

                    if (isEdit == 1) {

                        $('#modalTitle').text('Edit data')
                        DisableAllInputs(false);
                    } else {
                        $('#modalTitle').text('View data')
                        DisableAllInputs(true);
                    }
                    ClearInputs();
                    ClearValidation();

                    $('#formId').val(data.row.id);

                    $('#name').val(data.row.name);
                    $('#email').val(data.row.email);
                    $('#mobile').val(data.row.mobile);

                    $('#actionModal').modal('show');

                } else {

                    $('#userMsg').html("Invalid details").addClass('text-danger');
                }
            },
            error: function (e) {
                $('#userMsg').html("Error occured on fetching details").addClass('text-danger');
            }
        });
    }


    $('#saveBtn').on('click', function () {

        if (ValidateInputs()) {

            $('#modalMsg').html('').removeClass('text-success text-danger');
            name = $('#name').val();
            email = $('#email').val();
            mobile = $('#mobile').val();
            formId = $('#formId').val();

            formData = { name : name, email : email, mobile : mobile, formId : formId };

            $.ajax({

                url: "<?php echo base_url('welcome/saveUserAjax') ?>",
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function (data) {

                    if (data.code == 1) {

                        $('#modalMsg').html(data.description).addClass('text-success');
                        setTimeout(function(){
                            $('#actionModal').modal('hide');
                            $('#modalMsg').html('').removeClass('text-success');
                            fetchData();
                        }, 3000);

                    } else {
                        $('#modalMsg').html(data.description).addClass('text-danger');
                    }
                },
                error: function (e) {
                    $('#modalMsg').html("Error while saving data").addClass('text-danger');
                }
            });

        }else{
            $('#modalMsg').html("Enter the required inputs").addClass('text-danger');
        }
    });


    function confirmDelete(formId){
        
    }


    function ValidateInputs() {
        ClearValidation();
        var IsValidated = true;

        var name = $('#name').val();
        var email = $('#email').val();
        var mobile = $('#mobile').val();

        if (name.trim() == "") {
            IsValidated = false;
            $('#name').addClass("is-invalid");
        }

        if (email.trim() == "") {
            IsValidated = false;
            $('#email').addClass("is-invalid");
        }
        if (mobile.trim() == "") {
            IsValidated = false;
            $('#mobile').addClass("is-invalid");
        }
        return IsValidated;
    }


    function ClearValidation() {
        $('#name').removeClass("is-invalid")
        $('#email').removeClass("is-invalid")
        $('#mobile').removeClass("is-invalid")
    }

    function DisableAllInputs(enable) {
        $('#name').prop("disabled", enable)
        $('#email').prop("disabled", enable)
        $('#mobile').prop("disabled", enable)
    }


    function ClearInputs() {
        $('#formId').val("0");
        $('#name').val("");
        $('#email').val("");
        $('#mobile').val("");
    }
</script>