<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<style>
  body {
    background-color: #f4f6fb;
  }

  .login-page-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: stretch;
  }

  .login-left-img {
    min-height: 100vh;
    object-fit: cover;
  }

  .login-card {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 30px 15px;
  }

  .login-card-inner {
    width: 100%;
    max-width: 420px;
    border-radius: 16px;
    border: 1px solid #e0e6f1;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    background: #ffffff;
    padding: 28px 26px 26px;
  }

  .login-brand {
    text-align: center;
    margin-bottom: 10px;
  }

  .login-brand img {
    max-height: 60px;
  }

  .login-title {
    text-align: center;
    margin-bottom: 6px;
  }

  .login-title h4 {
    margin-bottom: 0;
    font-weight: 700;
  }

  .login-title small {
    color: #6c757d;
  }

  .form-floating-label {
    position: relative;
    margin-bottom: 1rem;
  }

  .form-floating-label input {
    padding: 0.75rem 2.5rem 0.35rem 2.3rem;
    /* extra right space for eye icon */
    border-radius: 0.5rem;
  }

  .form-floating-label label {
    position: absolute;
    left: 2.35rem;
    top: 50%;
    transform: translateY(-50%);
    margin: 0;
    font-size: 0.85rem;
    color: #9ca3af;
    pointer-events: none;
    transition: all 0.15s ease;
    background: #fff;
    padding: 0 4px;
  }

  .form-floating-label input:focus+label,
  .form-floating-label input:not(:placeholder-shown)+label {
    top: -1px;
    font-size: 0.75rem;
    color: #2563eb;
  }

  .input-icon {
    position: absolute;
    left: 0.65rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 1rem;
  }

  .toggle-password {
    position: absolute;
    right: 0.7rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #9ca3af;
    font-size: 1.1rem;
  }

  .login-footer-text {
    font-size: 0.85rem;
    margin-top: 1rem;
    text-align: center;
  }

  .login-footer-text a {
    font-weight: 600;
  }

  .alert {
    font-size: 0.85rem;
  }

  @media (max-width: 991.98px) {
    .login-left-col {
      display: none;
    }

    .login-card {
      min-height: 100vh;
    }
  }
</style>

<body>

  <!-- Loader starts-->
  <div class="loader-wrapper">
    <div class="loader">
      <div class="loader-bar"></div>
      <div class="loader-bar"></div>
      <div class="loader-bar"></div>
      <div class="loader-bar"></div>
      <div class="loader-bar"></div>
      <div class="loader-ball"></div>
    </div>
  </div>
  <!-- Loader ends-->

  <section class="login-page-wrapper">
    <div class="container-fluid">
      <div class="row no-gutters">

        <!-- Left image -->
        <div class="col-xl-5 col-lg-5 col-md-4 login-left-col">
          <img class="login-left-img w-100"
            src="<?= base_url(); ?>assets/images/login/2.jpg"
            alt="Login background">
        </div>

        <!-- Right card -->
        <div class="col-xl-7 col-lg-7 col-md-8 d-flex justify-content-center p-0">
          <div class="login-card w-100">
            <div class="login-card-inner">

              <!-- Optional logo -->
              <div class="login-brand">
                <img src="<?= base_url(); ?>assets/images/logo/logo.png" alt="Online Appointment Booking">
              </div>

              <!-- Title + subtitle -->
              <div class="login-title">
                <h4>Online Appointment Booking System</h4>
                <small>Sign in to manage schedules, clients, and appointments.</small>
              </div>

              <!-- Flash messages -->
              <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                  <?= $this->session->flashdata('message'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

              <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                  <?= $this->session->flashdata('success'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

              <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                  <?= $this->session->flashdata('danger'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

              <!-- Login form -->
              <form action="<?= site_url('Login/auth'); ?>" method="post" class="mt-3">

                <!-- Username -->
                <div class="form-floating-label">
                  <span class="input-icon">
                    <i class="mdi mdi-email-outline"></i>
                  </span>
                  <input
                    type="text"
                    name="username"
                    class="form-control"
                    required
                    autocomplete="username"
                    placeholder=" ">
                  <label>Email / Username</label>
                </div>

                <!-- Password -->
                <div class="form-floating-label">
                  <span class="input-icon">
                    <i class="mdi mdi-lock-outline"></i>
                  </span>
                  <input
                    type="password"
                    id="loginPassword"
                    name="password"
                    class="form-control"
                    required
                    autocomplete="current-password"
                    placeholder=" ">
                  <label>Password</label>
                  <span class="toggle-password" data-target="#loginPassword">
                    <i class="mdi mdi-eye-outline"></i>
                  </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="rememberMe">
                    <label class="custom-control-label text-muted" for="rememberMe">Remember me</label>
                  </div>
                  <a class="link" href="<?= site_url('login/forgot'); ?>">Forgot Password?</a>
                  <!-- TODO: Wire this to your actual forgot-password route if available -->
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                  Sign in
                </button>

              </form>

              <div class="login-footer-text">
                Don't have an account?
                <a href="<?= site_url('login/registration'); ?>">
                  Create one here
                </a>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- JS -->
  <script src="<?= base_url(); ?>assets/js/jquery-3.5.1.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/icons/feather-icon/feather.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/icons/feather-icon/feather-icon.js"></script>
  <script src="<?= base_url(); ?>assets/js/config.js"></script>
  <script src="<?= base_url(); ?>assets/js/script.js"></script>

  <script>
    // Show / hide password
    $(document).on('click', '.toggle-password', function() {
      var targetSelector = $(this).data('target');
      var $input = $(targetSelector);
      var $icon = $(this).find('i');

      if ($input.attr('type') === 'password') {
        $input.attr('type', 'text');
        $icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
      } else {
        $input.attr('type', 'password');
        $icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
      }
    });
  </script>

</body>

</html>