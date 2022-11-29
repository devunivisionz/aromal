@extends('layouts.loginlayout')
@section('content')

<div class="register-box">
  <div class="register-logo">
     <img src="{{asset('vendor/adminpanel/assets/img/400PngdpiLogo 1.png')}}"><a href=""><b>Admin</b>Panel</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>
    <form action="{{ route('register') }}" method="post">
        {{ csrf_field() }}

        

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="username" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope "></span>
                </div>
            </div>
            @if($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock "></span>
                </div>
            </div>
            @if($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
            @endif
        </div>

        {{-- Confirm password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                   placeholder="Retype Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock "></span>
                </div>
            </div>
            @if($errors->has('password_confirmation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group mb-3">
                            
                                <select class="form-control" name="role_id" id='role_id' required>
                                    <option value="-1">Select Admin Role</option>
                                  
                                        <option value="1">admin</option>
                                   
                                    
                                   
                                </select>
                          
                        </div>
         <div class="input-group mb-3">
            <input type="text" name="emp_record_emp_code" class="form-control {{ $errors->has('emp_record_emp_code') ? 'is-invalid' : '' }}"
                   value="{{ old('emp_record_emp_code') }}" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope "></span>
                </div>
            </div>
            @if($errors->has('emp_record_emp_code'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('emp_record_emp_code') }}</strong>
                </div>
            @endif
        </div>

                       
        {{-- Register button --}}
        <button type="submit" class="btn btn-block ">
            <span class="fas fa-user-plus"></span>
           Register
        </button>

    </form>
</div>
</div>
</div>




<script src="{{asset('vendor/adminpanel/plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap 4 -->
<script src="{{asset('vendor/adminpanel/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('vendor/adminpanel/assets/js/adminlte.js')}}"></script>

<script type="text/javascript">
$('.js-states').change(function(){
    
   var selectedVal = $('#state_id').val();

  // select all
  if (selectedVal == -1) {
    return;
  }
  var path={!! json_encode(url('/')) !!} + '/api/state_jurisdiction/'+selectedVal;
 
  $.ajax({
  type: 'get',
  url: path ,
   //data: {state_id:selectedVal},
   
  success: function (datas) {
    if (!datas || datas.length === 0) {
       return;
    }

    for (var  i = 0; i < datas.length; i++) {
       
      $('.js-jurisdiction').append($('<option>', {
        value: datas[i].id,
        text: datas[i].name
    }));
    }
  },
  error: function (ex) {
  }
  });
});


</script>
@endsection