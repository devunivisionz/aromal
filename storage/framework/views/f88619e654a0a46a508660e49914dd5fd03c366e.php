<?php $__env->startSection('title', 'Corrisoft'); ?>

<?php $__env->startSection('content'); ?>


<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo e(asset('vendor/adminpanel/assets/img/400PngdpiLogo 1.png')); ?>"><a href=""><b>Admin</b>Panel</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?php echo e(route('login')); ?>" method="post">
        <?php echo e(csrf_field()); ?>


        
        <div class="input-group mb-3">
            <input type="email" name="username" class="form-control <?php echo e($errors->has('username') ? 'is-invalid' : ''); ?>"
                   value="<?php echo e(old('username')); ?>" placeholder="username" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            <?php if($errors->has('username')): ?>
                <div class="invalid-feedback">
                    <strong><?php echo e($errors->first('username')); ?></strong>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control <?php echo e($errors->has('password') ? 'is-invalid' : ''); ?>"
                   placeholder="password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            <?php if($errors->has('password')): ?>
                <div class="invalid-feedback">
                    <strong><?php echo e($errors->first('password')); ?></strong>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember Me</label>
                </div>
            </div>
            <div class="col-5">
                <button type=submit class="btn btn-block <?php echo e(config('adminlte.classes_auth_btn', 'btn-flat btn-primary')); ?>">
                    <span class="fas fa-sign-in-alt"></span>
                    Sign In
                </button>
            </div>
        </div>

    </form>
     

      <p class="mb-1">
        <?php if(Route::has('password.request')): ?>
                                    <a  href="<?php echo e(route('password.request')); ?>">
                                        <?php echo e(__('Forgot Your Password?')); ?>

                                    </a>
                                <?php endif; ?>
      </p>
     
        
    <?php if(Route::has('register')): ?>
        <p class="my-0">
            <a href="<?php echo e(route('register')); ?>">
               Register 
            </a>
        </p>
    <?php endif; ?>
     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->



   
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.loginlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/db-preprod.com360degree.com/httpdocs/resources/views/auth/login.blade.php ENDPATH**/ ?>