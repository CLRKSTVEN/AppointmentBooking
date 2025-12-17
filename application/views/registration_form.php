<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
    max-width: 1200px;
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
    font-size: 2.4rem;
    font-weight: 700;
    margin-bottom: 16px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }

  .welcome-content p {
    font-size: 1.05rem;
    opacity: 0.95;
    line-height: 1.6;
    margin-bottom: 26px;
  }

  .feature-list {
    text-align: left;
    margin-top: 28px;
  }

  .feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.95rem;
  }

  .feature-item i {
    font-size: 1.2rem;
    margin-right: 12px;
    background: rgba(255, 255, 255, 0.2);
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
  }

  .login-right-section {
    flex: 1.2;
    padding: 50px 45px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .login-header {
    text-align: center;
    margin-bottom: 28px;
  }

  .login-brand {
    margin-bottom: 18px;
  }

  .login-brand img {
    max-height: 60px;
  }

  .login-header h3 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 6px;
  }

  .login-header p {
    color: #718096;
    font-size: 0.95rem;
  }

  .form-grid-two {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
  }

  .form-group-modern {
    margin-bottom: 18px;
    position: relative;
  }

  .form-group-modern label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 6px;
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

  .form-group-modern input,
  .form-group-modern select {
    width: 100%;
    padding: 13px 14px 13px 48px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f7fafc;
  }

  .form-group-modern select {
    appearance: none;
    background-image: linear-gradient(45deg, transparent 50%, #a0aec0 50%),
      linear-gradient(135deg, #a0aec0 50%, transparent 50%);
    background-position: calc(100% - 20px) calc(50% + 2px), calc(100% - 14px) calc(50% + 2px);
    background-size: 6px 6px, 6px 6px;
    background-repeat: no-repeat;
  }

  .form-group-modern input:focus,
  .form-group-modern select:focus {
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
    font-size: 1.1rem;
    z-index: 2;
    transition: color 0.3s ease;
  }

  .toggle-password:hover {
    color: #667eea;
  }

  .small-note {
    font-size: 0.85rem;
    color: #6b7280;
    margin-top: -6px;
    margin-bottom: 10px;
  }

  .btn-primary {
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

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .login-footer-text {
    font-size: 0.9rem;
    margin-top: 16px;
    text-align: center;
    color: #4a5568;
  }

  .login-footer-text a {
    font-weight: 600;
    color: #667eea;
  }

  .alert {
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 16px;
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
      max-width: 600px;
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
          <h2>Join our booking platform</h2>
          <p>Create your account to schedule and manage appointments with ease.</p>

          <div class="feature-list">
            <div class="feature-item">
              <i class="mdi mdi-account-plus"></i>
              <span>Set up your profile in minutes</span>
            </div>
            <div class="feature-item">
              <i class="mdi mdi-city"></i>
              <span>Select your province, city, and barangay</span>
            </div>
            <div class="feature-item">
              <i class="mdi mdi-shield-check"></i>
              <span>Secure login with reCAPTCHA</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Section - Registration Form -->
      <div class="login-right-section">
        <div class="login-header">
          <div class="login-brand">
            <img src="<?= base_url(); ?>assets/images/Attendance.png" alt="AppointFlow">
          </div>
          <h3>Create your AppointFlow account</h3>
          <p>Sign up to book and manage appointments with AppointFlow.</p>
        </div>

        <!-- Flash messages -->
        <?php if ($this->session->flashdata('message')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('msg')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($this->session->flashdata('msg'), ENT_QUOTES, 'UTF-8'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (validation_errors()): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= validation_errors(); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <form action="<?= site_url('login/registration'); ?>" method="post">

          <div class="alert alert-info small" role="alert">
            Use your email address as your login username. After submitting, sign in with that email and your password.
          </div>

          <div class="form-grid-two">
            <div class="form-group-modern">
              <label for="fName">First Name</label>
              <div class="input-wrapper">
                <span class="input-icon"><i class="mdi mdi-account-outline"></i></span>
                <input type="text" id="fName" name="fName" value="<?= set_value('fName'); ?>" required>
              </div>
            </div>

            <div class="form-group-modern">
              <label for="mName">Middle Name (optional)</label>
              <div class="input-wrapper">
                <span class="input-icon"><i class="mdi mdi-account-outline"></i></span>
                <input type="text" id="mName" name="mName" value="<?= set_value('mName'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="lName">Last Name</label>
            <div class="input-wrapper">
              <span class="input-icon"><i class="mdi mdi-account-outline"></i></span>
              <input type="text" id="lName" name="lName" value="<?= set_value('lName'); ?>" required>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="empEmail">Email Address</label>
            <div class="input-wrapper">
              <span class="input-icon"><i class="mdi mdi-email-outline"></i></span>
              <input type="email" id="empEmail" name="empEmail" value="<?= set_value('empEmail'); ?>" required>
            </div>
            <div id="emailFeedback" class="small-note" style="display: none;"></div>
          </div>

          <div class="form-grid-two">
            <div class="form-group-modern">
              <label for="province">Province</label>
              <div class="input-wrapper">
                <span class="input-icon"><i class="mdi mdi-map-marker-outline"></i></span>
                <select id="province" required>
                  <option value="" disabled selected>Select province...</option>
                  <?php if (!empty($provinces)): ?>
                    <?php foreach ($provinces as $prov): ?>
                      <option value="<?= htmlentities($prov); ?>"><?= htmlentities($prov); ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <div class="form-group-modern">
              <label for="city">City</label>
              <div class="input-wrapper">
                <span class="input-icon"><i class="mdi mdi-city"></i></span>
                <select id="city" required disabled>
                  <option value="" disabled selected>Select city...</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="barangay">Barangay</label>
            <div class="input-wrapper">
              <span class="input-icon"><i class="mdi mdi-home-outline"></i></span>
              <select id="barangay" name="address_id" required disabled>
                <option value="" disabled selected>Select barangay...</option>
              </select>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="regPassword">Password</label>
            <div class="input-wrapper">
              <span class="input-icon"><i class="mdi mdi-lock-outline"></i></span>
              <input type="password" id="regPassword" name="password" required minlength="8" autocomplete="new-password">
              <span class="toggle-password" data-target="#regPassword">
                <i class="mdi mdi-eye-outline"></i>
              </span>
            </div>
            <div class="small-note">Password must be at least 8 characters long.</div>
          </div>

          <?php if (!empty($recaptcha_site_key)): ?>
            <div class="form-group-modern text-center">
              <script src="https://www.google.com/recaptcha/api.js" async defer></script>
              <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($recaptcha_site_key, ENT_QUOTES, 'UTF-8'); ?>"></div>
            </div>
          <?php else: ?>
            <div class="alert alert-warning" role="alert">
              <strong>reCAPTCHA not configured.</strong> The registration form will show a captcha if your site key is saved in the <code>srms_settings</code> table.
            </div>
          <?php endif; ?>

          <button type="submit" name="register" value="1" class="btn-primary">
            <i class="mdi mdi-account-plus-outline mr-1"></i> Create Account
          </button>

        </form>

        <div class="login-footer-text">
          Already have an account?
          <a href="<?= site_url('Login'); ?>">Sign in here</a>
        </div>

      </div>

    </div>
  </section>

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

    // Province / City / Barangay cascading selects
    document.addEventListener('DOMContentLoaded', function() {
      const citiesByProvince = <?= json_encode($citiesByProvince ?? []); ?>;
      const barangayByCity = <?= json_encode($barangayByCity ?? []); ?>;
      const provinceEl = document.getElementById('province');
      const cityEl = document.getElementById('city');
      const barangayEl = document.getElementById('barangay');

      function resetSelect(el, placeholder) {
        el.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = placeholder;
        opt.disabled = true;
        opt.selected = true;
        el.appendChild(opt);
      }

      function populateCities(province) {
        resetSelect(cityEl, 'Select city...');
        resetSelect(barangayEl, 'Select barangay...');
        cityEl.disabled = true;
        barangayEl.disabled = true;
        if (!province || !citiesByProvince[province]) return;
        Object.keys(citiesByProvince[province]).sort().forEach(function(city) {
          const opt = document.createElement('option');
          opt.value = city;
          opt.textContent = city;
          cityEl.appendChild(opt);
        });
        cityEl.disabled = false;
      }

      function populateBarangays(city) {
        resetSelect(barangayEl, 'Select barangay...');
        barangayEl.disabled = true;
        if (!city || !barangayByCity[city]) return;
        barangayByCity[city].forEach(function(item) {
          const opt = document.createElement('option');
          opt.value = item.id;
          opt.textContent = item.name;
          barangayEl.appendChild(opt);
        });
        barangayEl.disabled = false;
      }

      provinceEl.addEventListener('change', function(e) {
        populateCities(e.target.value);
      });
      cityEl.addEventListener('change', function(e) {
        populateBarangays(e.target.value);
      });

      // Rehydrate if preselected after validation errors
      if (provinceEl.value) {
        populateCities(provinceEl.value);
      }
      if (cityEl.value) {
        populateBarangays(cityEl.value);
      }
    });
  </script>

</body>

</html>
