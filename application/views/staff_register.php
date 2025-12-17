<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="page-title-box d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="page-title mb-0">Register staff</h4>
                                </div>
                                <a class="btn btn-outline-primary btn-sm" href="<?= site_url('dashboard'); ?>">&larr; Back to dashboard</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-box">
                                <h5 class="mb-3">Account details</h5>
                                <form action="<?= site_url('register/save'); ?>" method="post" id="registerForm">
                                    <?php $selectedPosition = set_value('position_title'); ?>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="first_name">First name</label>
                                            <input id="first_name" name="first_name" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="middle_name">Middle name</label>
                                            <input id="middle_name" name="middle_name" class="form-control">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="last_name">Last name</label>
                                            <input id="last_name" name="last_name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="suffix">Suffix</label>
                                            <input id="suffix" name="suffix" class="form-control" placeholder="Jr / Sr / III">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="position_title">Position / role</label>
                                            <select id="position_title" name="position_title" class="form-control" required>
                                                <option value="">Select position</option>
                                                <?php if (!empty($positions)): ?>
                                                    <?php foreach ($positions as $pos): ?>
                                                        <option value="<?= htmlentities($pos); ?>" <?= ($selectedPosition === $pos) ? 'selected' : ''; ?>>
                                                            <?= htmlentities($pos); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="office_id">Office</label>
                                            <select id="office_id" name="office_id" class="form-control" required>
                                                <option value="">Select office</option>
                                                <?php if (!empty($offices)): ?>
                                                    <?php foreach ($offices as $office): ?>
                                                        <option value="<?= $office->id; ?>"><?= htmlentities($office->name); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="province">Province</label>
                                            <select id="province" class="form-control" required>
                                                <option value="">Select province</option>
                                                <?php if (!empty($provinces)): ?>
                                                    <?php foreach ($provinces as $prov): ?>
                                                        <option value="<?= htmlentities($prov); ?>"><?= htmlentities($prov); ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="city">City</label>
                                            <select id="city" class="form-control" required disabled>
                                                <option value="">Select city</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="barangay">Barangay</label>
                                            <select id="barangay" class="form-control" required disabled>
                                                <option value="">Select barangay</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" id="address_id" name="address_id" value="">

                                    <div class="form-group">
                                        <label for="short_bio">Short bio / specialization</label>
                                        <textarea id="short_bio" name="short_bio" class="form-control" rows="3" placeholder="Area of expertise, services provided"></textarea>
                                    </div>

                                    <h5 class="mt-3">Login credentials</h5>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="username">Username</label>
                                            <input id="username" name="username" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" name="password" class="form-control" required minlength="6">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="password_confirm">Confirm password</label>
                                            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required minlength="6">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Create account</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/themecustomizer.php'); ?>
    <?php include('includes/footer_plugins.php'); ?>

    <script>
        (function() {
            const citiesByProvince = <?= json_encode($citiesByProvince ?? []); ?>;
            const barangayByCity = <?= json_encode($barangayByCity ?? []); ?>;
            const provinceEl = document.getElementById('province');
            const cityEl = document.getElementById('city');
            const barangayEl = document.getElementById('barangay');
            const addressIdEl = document.getElementById('address_id');

            function resetSelect(el, placeholder) {
                el.innerHTML = '';
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = placeholder;
                el.appendChild(opt);
                el.value = '';
            }

            function populateCities(province) {
                resetSelect(cityEl, 'Select city');
                resetSelect(barangayEl, 'Select barangay');
                cityEl.disabled = true;
                barangayEl.disabled = true;
                addressIdEl.value = '';

                if (!province || !citiesByProvince[province]) return;
                Object.keys(citiesByProvince[province]).sort().forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = city;
                    cityEl.appendChild(opt);
                });
                cityEl.disabled = false;
            }

            function populateBarangay(city) {
                resetSelect(barangayEl, 'Select barangay');
                barangayEl.disabled = true;
                addressIdEl.value = '';

                if (!city || !barangayByCity[city]) return;
                barangayByCity[city].forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    barangayEl.appendChild(opt);
                });
                barangayEl.disabled = false;
            }

            provinceEl.addEventListener('change', (e) => {
                populateCities(e.target.value);
            });

            cityEl.addEventListener('change', (e) => {
                populateBarangay(e.target.value);
            });

            barangayEl.addEventListener('change', (e) => {
                addressIdEl.value = e.target.value || '';
            });
        })();
    </script>
</body>

</html>
