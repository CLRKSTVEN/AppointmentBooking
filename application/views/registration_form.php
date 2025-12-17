<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
    max-width: 500px;
    border-radius: 16px;
    border: 1px solid #e0e6f1;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    background: #ffffff;
    padding: 28px 26px 26px;
    max-height: 90vh;
    overflow-y: auto;
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

  .form-floating-label input,
  .form-floating-label select {
    padding: 0.75rem 0.95rem 0.35rem 2.3rem;
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
  .form-floating-label input:not(:placeholder-shown)+label,
  .form-floating-label select:focus+label,
  .form-floating-label select:not([value=''])+label {
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

  .row-two-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
  }

  .row-two-cols .form-floating-label {
    margin-bottom: 0.75rem;
  }

  @media (max-width: 991.98px) {
    .login-left-col {
      display: none;
    }

    .login-card {
      min-height: 100vh;
    }

    .row-two-cols {
      grid-template-columns: 1fr;
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
            alt="Registration background">
        </div>

        <!-- Right card -->
        <div class="col-xl-7 col-lg-7 col-md-8 d-flex justify-content-center p-0">
          <div class="login-card w-100">
            <div class="login-card-inner">

              <!-- Logo -->
              <div class="login-brand">
                <img src="<?= base_url(); ?>assets/images/logo/logo.png" alt="Online Appointment Booking">
              </div>

              <!-- Title + subtitle -->
              <div class="login-title">
                <h4>Create an appointment account</h4>
                <small>Sign up to book and manage appointments.</small>
              </div>

              <!-- Flash messages -->
              <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                  <?= htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

              <?php if ($this->session->flashdata('msg')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                  <?= htmlspecialchars($this->session->flashdata('msg'), ENT_QUOTES, 'UTF-8'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

              <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                  <?= validation_errors(); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php endif; ?>

              <!-- Registration form -->
              <form action="<?= site_url('login/registration'); ?>" method="post" class="mt-3">

                <div class="alert alert-info small" role="alert">
                  Use your email address as your login username. After submitting, sign in with that email and your password.
                </div>

                <!-- First Name & Middle Name (two columns) -->
                <div class="row-two-cols">
                  <div class="form-floating-label">
                    <span class="input-icon">
                      <i class="mdi mdi-account-outline"></i>
                    </span>
                    <input
                      type="text"
                      name="fName"
                      class="form-control"
                      required
                      placeholder=" "
                      value="<?= set_value('fName'); ?>">
                    <label>First Name</label>
                  </div>

                  <div class="form-floating-label">
                    <span class="input-icon">
                      <i class="mdi mdi-account-outline"></i>
                    </span>
                    <input
                      type="text"
                      name="mName"
                      class="form-control"
                      required
                      placeholder=" "
                      value="<?= set_value('mName'); ?>">
                    <label>Middle Name</label>
                  </div>
                </div>

                <!-- Last Name (full width) -->
                <div class="form-floating-label">
                  <span class="input-icon">
                    <i class="mdi mdi-account-outline"></i>
                  </span>
                  <input
                    type="text"
                    name="lName"
                    class="form-control"
                    required
                    placeholder=" "
                    value="<?= set_value('lName'); ?>">
                  <label>Last Name</label>
                </div>

                <!-- Email -->
                <div class="form-floating-label">
                  <span class="input-icon">
                    <i class="mdi mdi-email-outline"></i>
                  </span>
                  <input
                    type="email"
                    id="empEmail"
                    name="empEmail"
                    class="form-control"
                    required
                    placeholder=" "
                    value="<?= set_value('empEmail'); ?>">
                  <label>Email Address</label>
                </div>
                <!-- Email validation feedback -->
                <div id="emailFeedback" class="small mb-2" style="display: none;"></div>

                <!-- Password -->
                <div class="form-floating-label">
                  <span class="input-icon">
                    <i class="mdi mdi-lock-outline"></i>
                  </span>
                  <input
                    type="password"
                    id="regPassword"
                    name="password"
                    class="form-control"
                    required
                    minlength="8"
                    placeholder=" "
                    autocomplete="new-password">
                  <label>Password</label>
                  <span class="toggle-password" data-target="#regPassword">
                    <i class="mdi mdi-eye-outline"></i>
                  </span>
                </div>

                <small class="text-muted d-block mb-2">Password must be at least 8 characters long.</small>

                <!-- reCAPTCHA widget (if site key provided) -->
                <?php if (!empty($recaptcha_site_key)): ?>
                  <div class="form-group mt-2 mb-2 text-center">
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($recaptcha_site_key, ENT_QUOTES, 'UTF-8'); ?>"></div>
                  </div>
                <?php else: ?>
                  <div class="alert alert-warning mt-2 mb-2" role="alert">
                    <strong>reCAPTCHA not configured.</strong> The registration form will show a captcha if your site key is saved in the <code>srms_settings</code> table.
                    <div class="mt-1">Add the keys using an SQL update (example):</div>
                    <pre class="small">UPDATE srms_settings SET recaptcha_site_key = 'YOUR_SITE_KEY', recaptcha_secret_key = 'YOUR_SECRET_KEY' WHERE id = 1;</pre>
                    <div class="mt-1">If your settings use other column names, the code looks for: <code>recaptcha_site_key</code>, <code>recaptcha_site</code>, <code>google_site_key</code>, <code>site_key</code>.</div>
                  </div>
                <?php endif; ?>

                <button type="submit" name="register" value="1" class="btn btn-primary btn-block">
                  <i class="mdi mdi-account-plus-outline mr-1"></i> Create Account
                </button>

              </form>

              <div class="login-footer-text">
                Already have an account?
                <a href="<?= site_url('Login'); ?>">
                  Sign in here
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

    // Dismiss alerts
    $(document).ready(function() {
      $('.alert-dismissible .close').on('click', function() {
        $(this).closest('.alert').fadeOut();
      });

    });
  </script>

</body>

</html>
