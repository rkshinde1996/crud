<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<style type="text/css">
			body{
			  background-color: #000;
			}

			.card{

			  width: 400px;
			  border:none;

			}


			.btr{

			  border-top-right-radius: 5px !important;
			}


			.btl{

			  border-top-left-radius: 5px !important;
			}

			.btn-dark {
			    color: #fff;
			    background-color: #0d6efd;
			    border-color: #0d6efd;
			}


			.btn-dark:hover {
			    color: #fff;
			    background-color: #0d6efd;
			    border-color: #0d6efd;
			}


			.nav-pills{

			  display:table !important;
			  width:100%;
			}

			.nav-pills .nav-link {
			    border-radius: 0px;
			        border-bottom: 1px solid #0d6efd40;

			}

			.nav-item{
			      display: table-cell;
			       background: #0d6efd2e;
			}


			.form{

			  padding: 10px;
			      height: 300px;
			}

			.form input{

			  margin-bottom: 12px;
			  border-radius: 3px;
			}


			.form input:focus{

			  box-shadow: none;
			}


			.form button{

			  margin-top: 20px;
			}
	</style>
</head>
<body>
	<div class="d-flex justify-content-center align-items-center mt-5">
        <div class="card">

            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item text-center">
                  <a class="nav-link active btl" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Login</a>
                </li>
                <li class="nav-item text-center">
                  <a class="nav-link btr" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Signup</a>
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                  
                  <div class="form px-4 pt-5">

                    <input type="text" name="email" id="l_email" class="form-control" placeholder="Email id">

                    <input type="text" name="password" id="l_password" class="form-control" placeholder="Password">
                    <button class="btn btn-dark btn-block" id="loginBtn">Login</button>
                    <div>
                    	<p id="loginMsg"></p>
                    </div>

                  </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                  <div class="form px-4">

                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">

                    <input type="text" name="email" id="email" class="form-control" placeholder="Email">

                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile">

                    <input type="text" name="password" id="password" class="form-control" placeholder="Password">

                    <button class="btn btn-dark btn-block" id="signupBtn">Signup</button>
                    <div>
                    	<p id="signupMsg"></p>
                    </div>

                  </div>
                </div>
                
            </div>
        </div>
      </div>
</body>
</html>


<script type="text/javascript">

    $('#loginBtn').on('click', function () {

        $('#l_email').removeClass("is-invalid")
        $('#l_password').removeClass("is-invalid")

        var IsValidated = true;

        var email = $('#l_email').val();
        var password = $('#l_password').val();

        if (email.trim() == "") {
            IsValidated = false;
            $('#l_email').addClass("is-invalid");
        }
        if (password.trim() == "") {
            IsValidated = false;
            $('#l_password').addClass("is-invalid");
        }

        if (IsValidated) {
        		$('#loginMsg').html('').removeClass('text-success text-danger');

            formData = { email : email, password : password };

            $.ajax({

                url: "<?php echo base_url('welcome/loginAction') ?>",
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function (data) {

                    if (data.code == 1) {

                        $('#loginMsg').html('Login success, redirecting to list.').addClass('text-success');
                        setTimeout(function(){
                        	window.location = "<?php echo base_url('users') ?>";
                        }, 3000);

                    } else {
                        $('#loginMsg').html(data.description).addClass('text-danger');
                    }
                },
                error: function (e) {
                    $('#loginMsg').html("Error while saving data").addClass('text-danger');
                }
            });

        }else{
            $('#loginMsg').html("Enter the required inputs").addClass('text-danger');
        }
    });

    $('#signupBtn').on('click', function () {

        if (ValidateInputs()) {
        		$('#signupMsg').html('').removeClass('text-success text-danger');
            name = $('#name').val();
            email = $('#email').val();
            mobile = $('#mobile').val();
            password = $('#password').val();

            formData = { name : name, email : email, mobile : mobile, password : password };

            $.ajax({

                url: "<?php echo base_url('welcome/signupAction') ?>",
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function (data) {

                    if (data.code == 1) {

                        $('#signupMsg').html('Registration success, redirecting to list.').addClass('text-success');
                        setTimeout(function(){
                        	window.location = "<?php echo base_url('users') ?>";
                        }, 3000);

                    } else {
                        $('#signupMsg').html(data.description).addClass('text-danger');
                    }
                },
                error: function (e) {
                    $('#signupMsg').html("Error while saving data").addClass('text-danger');
                }
            });

        }else{
            $('#signupMsg').html("Enter the required inputs").addClass('text-danger');
        }
    });


    // validate inputes
    function ValidateInputs() {
        ClearValidation();
        var IsValidated = true;

        var name = $('#name').val();
        var email = $('#email').val();
        var mobile = $('#mobile').val();
        var password = $('#password').val();

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
        if (password.trim() == "") {
            IsValidated = false;
            $('#password').addClass("is-invalid");
        }
        return IsValidated;
    }


    function ClearValidation() {
        $('#name').removeClass("is-invalid")
        $('#email').removeClass("is-invalid")
        $('#mobile').removeClass("is-invalid")
        $('#password').removeClass("is-invalid")
    }
</script>