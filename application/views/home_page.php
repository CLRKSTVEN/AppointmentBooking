<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<style>
  body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
  }

  /* Animated background particles */
  body::before {
    content: '';
    position: fixed;
    width: 100%;
    height: 100%;
    background-image:
      radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    animation: float 8s ease-in-out infinite;
    pointer-events: none;
  }

  @keyframes float {

    0%,
    100% {
      transform: translateY(0px);
    }

    50% {
      transform: translateY(-20px);
    }
  }

  .login-page-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    z-index: 1;
  }

  .login-container {
    display: flex;
    max-width: 1100px;
    width: 100%;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
  }

  .login-left-section {
    flex: 1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 60px 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    position: relative;
    overflow: hidden;
  }

  .login-left-section::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    top: -100px;
    right: -100px;
    animation: pulse 4s ease-in-out infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      transform: scale(1);
      opacity: 0.3;
    }

    50% {
      transform: scale(1.1);
      opacity: 0.5;
    }
  }

  .welcome-content {
    position: relative;
    z-index: 2;
    text-align: center;
  }

  .welcome-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }

  .welcome-content p {
    font-size: 1.1rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: 30px;
  }

  .feature-list {
    text-align: left;
    margin-top: 30px;
  }

  .feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 0.95rem;
  }

  .feature-item i {
    font-size: 1.3rem;
    margin-right: 12px;
    background: rgba(255, 255, 255, 0.2);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
  }

  .login-right-section {
    flex: 1;
    padding: 60px 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .login-header {
    text-align: center;
    margin-bottom: 40px;
  }

  .login-brand {
    margin-bottom: 25px;
  }

  .login-brand img {
    max-height: 70px;
  }

  .login-header h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 8px;
  }

  .login-header p {
    color: #718096;
    font-size: 0.95rem;
  }

  .form-group-modern {
    margin-bottom: 24px;
    position: relative;
  }

  .form-group-modern label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
  }

  .input-wrapper {
    position: relative;
  }

  .input-wrapper .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 1.1rem;
    z-index: 2;
  }

  .form-group-modern input {
    width: 100%;
    padding: 14px 16px 14px 48px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f7fafc;
  }

  .form-group-modern input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .toggle-password {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #a0aec0;
    font-size: 1.2rem;
    z-index: 2;
    transition: color 0.3s ease;
  }

  .toggle-password:hover {
    color: #667eea;
  }

  .form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    font-size: 0.875rem;
  }

  .custom-checkbox-modern {
    display: flex;
    align-items: center;
  }

  .custom-checkbox-modern input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 8px;
    cursor: pointer;
    accent-color: #667eea;
  }

  .custom-checkbox-modern label {
    cursor: pointer;
    color: #4a5568;
    margin: 0;
  }

  .forgot-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .forgot-link:hover {
    color: #764ba2;
  }

  .btn-login {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  }

  .btn-login:active {
    transform: translateY(0);
  }

  .signup-prompt {
    text-align: center;
    margin-top: 24px;
    font-size: 0.9rem;
    color: #718096;
  }

  .signup-prompt a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .signup-prompt a:hover {
    color: #764ba2;
  }

  .alert {
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 0.875rem;
    border: none;
  }

  .alert-info {
    background: #e6f7ff;
    color: #0066cc;
  }

  .alert-success {
    background: #d4edda;
    color: #155724;
  }

  .alert-danger {
    background: #f8d7da;
    color: #721c24;
  }

  /* Loader styles */
  .loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.25s ease;
  }

  .loader {
    display: flex;
    gap: 8px;
  }

  .loader-bar {
    width: 4px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
    animation: load 1s ease-in-out infinite;
  }

  .loader-bar:nth-child(2) {
    animation-delay: 0.1s;
  }

  .loader-bar:nth-child(3) {
    animation-delay: 0.2s;
  }

  .loader-bar:nth-child(4) {
    animation-delay: 0.3s;
  }

  .loader-bar:nth-child(5) {
    animation-delay: 0.4s;
  }

  @keyframes load {

    0%,
    100% {
      height: 40px;
    }

    50% {
      height: 60px;
    }
  }

  .loader-ball {
    display: none;
  }

  @media (max-width: 991.98px) {
    .login-left-section {
      display: none;
    }

    .login-container {
      max-width: 500px;
    }

    .login-right-section {
      padding: 40px 30px;
    }
  }

  @media (max-width: 576px) {
    .login-right-section {
      padding: 30px 20px;
    }

    .login-header h3 {
      font-size: 1.5rem;
    }
  }
</style>

<body>

  <!-- Loader -->
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

  <section class="login-page-wrapper">
    <div class="login-container">

      <!-- Left Section - Welcome -->
      <div class="login-left-section">
        <div class="welcome-content">
          <h2>Welcome Back!</h2>
          <p>Streamline your appointment management with our comprehensive booking system</p>

          <div class="feature-list">
            <div class="feature-item">
              <i class="mdi mdi-calendar-check"></i>
              <span>Easy scheduling & management</span>
            </div>
            <div class="feature-item">
              <i class="mdi mdi-account-multiple"></i>
              <span>Client relationship tracking</span>
            </div>
            <div class="feature-item">
              <i class="mdi mdi-clock-outline"></i>
              <span>Real-time availability updates</span>
            </div>
            <div class="feature-item">
              <i class="mdi mdi-bell-outline"></i>
              <span>Automated reminders</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Section - Login Form -->
      <div class="login-right-section">
        <div class="login-header">
          <div class="login-brand">
            <img src="<?= base_url(); ?>assets/images/Attendance.png" alt="AppointFlow">
          </div>
          <h3>Sign In</h3>
          <p>Enter your credentials to access your AppointFlow account</p>
        </div>

        <!-- Flash messages -->
        <?php if ($this->session->flashdata('message')): ?>
          <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('message'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('danger')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('danger'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= site_url('Login/auth'); ?>" method="post">

          <!-- Username -->
          <div class="form-group-modern">
            <label for="username">Email / Username</label>
            <div class="input-wrapper">
              <span class="input-icon">
                <i class="mdi mdi-email-outline"></i>
              </span>
              <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                required
                autocomplete="username"
                placeholder="Enter your email or username">
            </div>
          </div>

          <!-- Password -->
          <div class="form-group-modern">
            <label for="loginPassword">Password</label>
            <div class="input-wrapper">
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
                placeholder="Enter your password">
              <span class="toggle-password" data-target="#loginPassword">
                <i class="mdi mdi-eye-outline"></i>
              </span>
            </div>
          </div>

          <!-- Options -->
          <div class="form-options">
            <div class="custom-checkbox-modern">
              <input type="checkbox" id="rememberMe">
              <label for="rememberMe">Remember me</label>
            </div>
            <a href="<?= site_url('login/forgot'); ?>" class="forgot-link">Forgot Password?</a>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn-login">
            Sign In
          </button>
        </form>

        <!-- Signup Prompt -->
        <div class="signup-prompt">
          Don't have an account?
          <a href="<?= site_url('login/registration'); ?>">Create one here</a>
        </div>
      </div>

    </div>
  </section>

  <!-- JS -->
  <script src="<?= base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>
    // Hide loader once page is ready (fallback timeout included)
    (function() {
      function hideLoader() {
        var el = document.querySelector('.loader-wrapper');
        if (!el || el.dataset.hidden === '1') return;
        el.dataset.hidden = '1';
        el.style.opacity = '0';
        setTimeout(function() {
          el.style.display = 'none';
        }, 250);
      }
      window.addEventListener('load', hideLoader);
      setTimeout(hideLoader, 1500);
    })();

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
