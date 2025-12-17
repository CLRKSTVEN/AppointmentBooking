<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Login_model', 'Staff_model', 'Office_model', 'Accomplishment_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    /**
     * Login screen (staff/admin).
     * Uses application/views/home_page.php
     */
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            return redirect('dashboard');
        }

        $this->load->view('home_page');
    }

    /**
     * Handle login form POST.
     */
    public function auth()
    {
        $username = trim($this->input->post('username', TRUE));
        $password = (string) $this->input->post('password', TRUE);

        if ($username === '' || $password === '') {
            $this->session->set_flashdata('auth_error', 'Username and password are required.');
            return redirect('login');
        }

        $user = $this->Login_model->authenticate($username, $password);

        if (!$user) {
            $this->session->set_flashdata('auth_error', 'Invalid username or password.');
            return redirect('login');
        }

        // Ensure every user has a staff profile for booking/visibility.
        if (empty($user->staff_id)) {
            $nameParts = explode('@', (string)$user->username);
            $fallbackName = ucfirst($nameParts[0] ?? 'User');
            $this->db->insert('staff', [
                'first_name' => $fallbackName,
                'last_name'  => '',
                'position_title' => 'Client',
                'is_active'  => 1,
                'is_public'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $newStaffId = $this->db->insert_id();
            $this->db->where('id', $user->id)->update('users', ['staff_id' => $newStaffId]);
            $user->staff_id = $newStaffId;
        }

        $fullName = trim(
            ($user->first_name ?? '') . ' ' .
                ($user->middle_name ? substr($user->middle_name, 0, 1) . '. ' : '') .
                ($user->last_name ?? '')
        );

        $sessionData = [
            'user_id'   => $user->id,
            'staff_id'  => $user->staff_id,
            'username'  => $user->username,
            'full_name' => $fullName,
            'role'      => $user->role,
            'logged_in' => TRUE,
        ];

        $this->session->set_userdata($sessionData);
        return redirect('dashboard');
    }

    public function dashboard()
    {
        $this->_require_login();

        if ($this->_is_admin()) {
            return $this->_render_admin_dashboard();
        }

        return $this->_render_staff_overview();
    }

    public function accomplishments()
    {
        $this->_require_login();

        $nav = [
            ['label' => 'Back to dashboard', 'url' => site_url('dashboard')],
        ];

        if ($this->_is_admin()) {
            $nav[] = ['label' => 'Register staff', 'url' => site_url('register')];
        }

        return $this->_render_staff_dashboard($nav);
    }

    public function save_accomplishment()
    {
        $this->_require_login();
        if (!$this->_is_client()) {
            return redirect('dashboard');
        }
        $staffId = (int) $this->session->userdata('staff_id');
        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Unable to save accomplishment without a staff profile.');
            return redirect('dashboard');
        }

        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
        $this->form_validation->set_rules('symptoms', 'Symptoms/Reason', 'required|trim');
        $this->form_validation->set_rules('doctor', 'Preferred doctor', 'trim');
        $this->form_validation->set_rules('insurance_provider', 'Insurance provider', 'trim');
        $this->form_validation->set_rules('is_public', 'Visibility', 'integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            return redirect('dashboard/log');
        }

        $startDate = $this->input->post('start_date', TRUE);
        $endDate   = $this->input->post('end_date', TRUE);
        // Availability check: prevent double booking same location + date if not declined
        if ($this->Accomplishment_model->has_conflict($startDate, $this->input->post('location', TRUE))) {
            $this->session->set_flashdata('error', 'Selected slot/location is already booked. Please choose another date/location.');
            return redirect('dashboard/log');
        }

        $attachmentName = null;
        if (!empty($_FILES['attachment']['name'])) {
            $uploadDir = FCPATH . 'upload/appointments/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
            $safeName = 'appt_' . time() . '_' . mt_rand(1000, 9999) . '.' . strtolower($ext);
            $target = $uploadDir . $safeName;
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array(strtolower($ext), $allowed)) {
                $this->session->set_flashdata('error', 'Invalid attachment type. Allowed: PDF/JPG/PNG.');
                return redirect('dashboard/log');
            }
            if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $target)) {
                $this->session->set_flashdata('error', 'Failed to upload attachment.');
                return redirect('dashboard/log');
            }
            $attachmentName = $safeName;
        }

        $payload = [
            'staff_id'   => $staffId,
            'title'      => $this->input->post('title', TRUE),
            'category'   => $this->input->post('category', TRUE),
            'location'   => $this->input->post('location', TRUE),
            'description'=> $this->input->post('description', TRUE),
            'symptoms'   => $this->input->post('symptoms', TRUE),
            'payment_reference' => $this->input->post('payment_reference', TRUE),
            'doctor'     => $this->input->post('doctor', TRUE),
            'insurance_provider' => $this->input->post('insurance_provider', TRUE),
            'start_date' => $startDate !== '' ? $startDate : null,
            'end_date'   => $endDate !== '' ? $endDate : null,
            'is_public'  => (int)$this->input->post('is_public', TRUE),
        ];
        if ($attachmentName) {
            $payload['attachment'] = $attachmentName;
        }

        $this->Accomplishment_model->create($payload);
        $this->session->set_flashdata('success', 'Accomplishment saved.');
        redirect('dashboard/log');
    }

    public function update_accomplishment()
    {
        $this->_require_login();
        if (!$this->_is_client()) {
            return redirect('dashboard');
        }
        $staffId = (int) $this->session->userdata('staff_id');
        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Unable to update accomplishment without a staff profile.');
            return redirect('dashboard');
        }

        $this->form_validation->set_rules('id', 'Accomplishment', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
        $this->form_validation->set_rules('symptoms', 'Symptoms/Reason', 'required|trim');
        $this->form_validation->set_rules('doctor', 'Preferred doctor', 'trim');
        $this->form_validation->set_rules('insurance_provider', 'Insurance provider', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            return redirect('dashboard/log');
        }

        $id = (int)$this->input->post('id', TRUE);
        $existing = $this->Accomplishment_model->find($id, $staffId);
        if (!$existing) {
            $this->session->set_flashdata('error', 'Accomplishment not found.');
            return redirect('dashboard');
        }

        $startDate = $this->input->post('start_date', TRUE);
        $endDate   = $this->input->post('end_date', TRUE);
        $payload = [
            'title'      => $this->input->post('title', TRUE),
            'category'   => $this->input->post('category', TRUE),
            'location'   => $this->input->post('location', TRUE),
            'description'=> $this->input->post('description', TRUE),
            'symptoms'   => $this->input->post('symptoms', TRUE),
            'payment_reference' => $this->input->post('payment_reference', TRUE),
            'doctor'     => $this->input->post('doctor', TRUE),
            'insurance_provider' => $this->input->post('insurance_provider', TRUE),
            'start_date' => $startDate !== '' ? $startDate : null,
            'end_date'   => $endDate !== '' ? $endDate : null,
            'is_public'  => (int)$this->input->post('is_public', TRUE),
        ];

        $this->Accomplishment_model->update($id, $staffId, $payload);
        $this->session->set_flashdata('success', 'Accomplishment updated.');
        redirect('dashboard/log');
    }

    public function delete_accomplishment($id = null)
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        if ($staffId <= 0) {
            $this->session->set_flashdata('error', 'Unable to remove accomplishment without a staff profile.');
            return redirect('dashboard');
        }

        $id = (int)$id;
        if ($id <= 0) {
            $this->session->set_flashdata('error', 'Invalid accomplishment.');
            return redirect('dashboard');
        }

        $existing = $this->Accomplishment_model->find($id, $staffId);
        if (!$existing) {
            $this->session->set_flashdata('error', 'Accomplishment not found.');
            return redirect('dashboard');
        }

        $this->Accomplishment_model->delete($id, $staffId);
        $this->session->set_flashdata('success', 'Accomplishment deleted.');
        redirect('dashboard/log');
    }

    public function update_accomplishment_status()
    {
        $this->_require_login();
        if (!($this->_is_admin() || $this->_is_staff())) {
            return redirect('dashboard');
        }

        $id = (int)$this->input->post('id', TRUE);
        $status = strtolower((string)$this->input->post('status', TRUE));
        $allowed = ['pending', 'accepted', 'declined', 'completed'];
        if ($id <= 0 || !in_array($status, $allowed, true)) {
            $this->session->set_flashdata('error', 'Invalid appointment or status.');
            return redirect('dashboard/log');
        }

        $this->Accomplishment_model->update_status($id, $status, (int)$this->session->userdata('staff_id'));
        $this->session->set_flashdata('success', 'Status updated.');
        redirect('dashboard/log');
    }


    /**
     * Logout.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    /**
     * Show staff registration form.
     */
    public function register()
    {
        $loggedIn = (bool) $this->session->userdata('logged_in');
        $isAdmin  = $this->_is_admin();

        // Only admins can access while logged in. Guests may still self-register.
        if ($loggedIn && !$isAdmin) {
            return redirect('dashboard');
        }

        // Load offices + addresses (province/city/brgy) for dropdowns
        $data['offices']  = $this->db->get('offices')->result();
        $data['positions'] = $this->_position_options();
        $addrRows = $this->db->get('address')->result();

        $provinces = [];
        $citiesByProvince = [];
        $barangayByCity = [];

        foreach ($addrRows as $row) {
            $prov = trim((string) $row->Province);
            $city = trim((string) $row->City);
            $brgy = trim((string) $row->Brgy);

            if ($prov === '' || $city === '' || $brgy === '') {
                continue;
            }

            $provinces[$prov] = true;
            $citiesByProvince[$prov][$city] = true;
            $barangayByCity[$city][] = [
                'id'   => (int) $row->AddID,
                'name' => $brgy,
            ];
        }

        $data['provinces'] = array_keys($provinces);
        $data['citiesByProvince'] = $citiesByProvince;
        $data['barangayByCity'] = $barangayByCity;
        $data['addresses'] = $addrRows;

        $this->load->view('staff_register', $data);
    }

    /**
     * Public registration (renders form and handles POST signup)
     */
    public function registration()
    {
        $recaptcha = $this->_recaptcha_config();
        $addrRows = $this->db->get('address')->result();

        $provinces = [];
        $citiesByProvince = [];
        $barangayByCity = [];

        foreach ($addrRows as $row) {
            $prov = trim((string) $row->Province);
            $city = trim((string) $row->City);
            $brgy = trim((string) $row->Brgy);

            if ($prov === '' || $city === '' || $brgy === '') {
                continue;
            }

            $provinces[$prov] = true;
            $citiesByProvince[$prov][$city] = true;
            $barangayByCity[$city][] = [
                'id'   => (int) $row->AddID,
                'name' => $brgy,
            ];
        }

        $data = [
            'recaptcha_site_key' => $recaptcha['site_key'],
            'provinces' => array_keys($provinces),
            'citiesByProvince' => $citiesByProvince,
            'barangayByCity' => $barangayByCity,
        ];

        if ($this->input->method() === 'post' && $this->input->post('register')) {
            $this->form_validation->set_rules('fName', 'First Name', 'required|trim');
            $this->form_validation->set_rules('mName', 'Middle Name', 'trim');
            $this->form_validation->set_rules('lName', 'Last Name', 'required|trim');
            $this->form_validation->set_rules('empEmail', 'Email', 'required|trim|valid_email|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
            $this->form_validation->set_rules('address_id', 'Address', 'required|integer');

            $recaptchaSecret = $recaptcha['secret_key'] ?? '';
            $recaptchaResponse = $this->input->post('g-recaptcha-response');
            if ($recaptchaSecret !== '') {
                if (!$this->_verify_recaptcha($recaptchaSecret, $recaptchaResponse)) {
                    $this->session->set_flashdata('msg', 'reCAPTCHA validation failed. Please try again.');
                    return redirect('login/registration');
                }
            }

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('msg', validation_errors());
                return redirect('login/registration');
            }

            $first = $this->input->post('fName', TRUE);
            $middle = $this->input->post('mName', TRUE);
            $last = $this->input->post('lName', TRUE);
            $email = $this->input->post('empEmail', TRUE);
            $password = (string)$this->input->post('password', TRUE);
            $addressId = (int)$this->input->post('address_id');

            // Guard against missing/invalid address rows to avoid FK errors
            if (!$this->_address_exists($addressId)) {
                $this->session->set_flashdata('msg', 'Selected address is invalid. Please choose a province/city/barangay again.');
                return redirect('login/registration');
            }

            $this->db->trans_start();

            // Create staff profile with minimal info
            $this->db->insert('staff', [
                'first_name'     => $first,
                'middle_name'    => $middle,
                'last_name'      => $last,
                'position_title' => null,
                'office_id'      => null,
                'address_id'     => $addressId > 0 ? $addressId : null,
                'photo'          => null,
                'short_bio'      => null,
                'is_active'      => 1,
                'is_public'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $staffId = $this->db->insert_id();

            // Create user tied to staff
            $this->db->insert('users', [
                'staff_id'      => $staffId,
                'username'      => $email, // email used as username
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role'          => 'client',
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('msg', 'Registration failed. Please try again.');
                return redirect('login/registration');
            }

            $this->session->set_flashdata('message', 'Account created. You may now sign in using your email and password.');
            return redirect('login');
        }

        $this->load->view('registration_form', $data);
    }

    /**
     * Handle staff registration POST.
     * Creates record in staff + users (role=staff).
     */
    public function register_save()
    {
        $loggedIn = (bool) $this->session->userdata('logged_in');
        if ($loggedIn && !$this->_is_admin()) {
            return redirect('dashboard');
        }

        // Basic validation
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('last_name',  'Last Name',  'required|trim');
        $this->form_validation->set_rules('position_title', 'Position', 'required|trim');
        $this->form_validation->set_rules('office_id', 'Office', 'required|integer');
        $this->form_validation->set_rules('address_id', 'Address', 'required|integer');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[3]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            // Reload form with errors
            $data['offices']   = $this->db->get('offices')->result();
            $data['positions'] = $this->_position_options();
            $addrRows = $this->db->get('address')->result();
            $provinces = [];
            $citiesByProvince = [];
            $barangayByCity = [];
            foreach ($addrRows as $row) {
                $prov = trim((string) $row->Province);
                $city = trim((string) $row->City);
                $brgy = trim((string) $row->Brgy);

                if ($prov === '' || $city === '' || $brgy === '') {
                    continue;
                }

                $provinces[$prov] = true;
                $citiesByProvince[$prov][$city] = true;
                $barangayByCity[$city][] = [
                    'id'   => (int) $row->AddID,
                    'name' => $brgy,
                ];
            }
            $data['provinces'] = array_keys($provinces);
            $data['citiesByProvince'] = $citiesByProvince;
            $data['barangayByCity'] = $barangayByCity;
            $data['addresses'] = $addrRows;
            return $this->load->view('staff_register', $data);
        }

        // Collect staff fields
        $staffData = [
            'staff_code'     => NULL, // you can generate or assign later
            'first_name'     => $this->input->post('first_name', TRUE),
            'middle_name'    => $this->input->post('middle_name', TRUE),
            'last_name'      => $this->input->post('last_name', TRUE),
            'suffix'         => $this->input->post('suffix', TRUE),
            'position_title' => $this->input->post('position_title', TRUE),
            'office_id'      => (int)$this->input->post('office_id'),
            'address_id'     => (int)$this->input->post('address_id'),
            'photo'          => NULL, // upload later if you want
            'short_bio'      => $this->input->post('short_bio', TRUE),
            'is_active'      => 1,
            'is_public'      => 1,
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        $username = $this->input->post('username', TRUE);
        $password = (string)$this->input->post('password', TRUE);

        $this->db->trans_start();

        // Insert staff
        $this->db->insert('staff', $staffData);
        $staff_id = $this->db->insert_id();

        // Insert user (role = staff)
        $userData = [
            'staff_id'      => $staff_id,
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'staff',
            'status'        => 1,
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('users', $userData);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('auth_error', 'Registration failed. Please try again.');
            return redirect('register');
        }

        // Success: redirect to login
        $this->session->set_flashdata('auth_error', 'Registration successful. You may now log in.');
        redirect('login');
    }

    /**
     * Admin overview dashboard.
     */
    private function _render_admin_dashboard()
    {
        $staffId = (int) $this->session->userdata('staff_id');

        $stats = [
            'total_staff'            => $this->Staff_model->count_active(),
            'total_offices'          => $this->Office_model->count_all(),
            'total_accomplishments'  => $this->Accomplishment_model->count_all(),
            'my_accomplishments'     => $this->Accomplishment_model->count_for_staff($staffId),
            'public_accomplishments' => $this->Accomplishment_model->count_public_for_staff($staffId),
        ];

        $data = [
            'stats' => $stats,
            'dashboard_nav' => [
                ['target' => site_url('dashboard/log'), 'label' => 'Accomplishments', 'count' => $stats['my_accomplishments']],
                ['target' => site_url('register'), 'label' => 'Register staff', 'count' => $stats['total_staff']],
            ],
            'recent_accomplishments' => $this->Accomplishment_model->recent(5),
            'latest_staff'           => $this->Staff_model->recent(6),
        ];

        $this->load->view('dashboard_admin', $data);
    }

    /**
     * Staff-focused accomplishments dashboard.
     */
    private function _render_staff_dashboard(?array $overviewNav = null)
    {
        $data = $this->_staff_dashboard_data($overviewNav);
        $this->load->view('dashboard_staff', $data);
    }

    private function _render_staff_overview()
    {
        $data = $this->_staff_overview_data();
        $this->load->view('dashboard_overview', $data);
    }

    private function _staff_dashboard_data(?array $overviewNav = null)
    {
        $staffId = (int) $this->session->userdata('staff_id');
        $role    = strtolower((string)$this->session->userdata('role'));
        $isAdmin = $this->_is_admin();
        $isStaff = $this->_is_staff();
        $isClient = ($role === 'client');

        $accomplishments = ($staffId > 0 && $isClient)
            ? $this->Accomplishment_model->get_for_staff($staffId)
            : $this->Accomplishment_model->all_with_staff();

        $categories = $staffId > 0
            ? $this->Accomplishment_model->categories_for_staff($staffId)
            : [];

        $addresses = $this->db->get('settings_address')->result();
        $appointmentTypes = $this->db->table_exists('appointment_types')
            ? $this->db->get('appointment_types')->result()
            : [];
        $appointmentRooms = $this->db->table_exists('appointment_rooms')
            ? $this->db->get_where('appointment_rooms', ['is_active' => 1])->result()
            : [];
        $doctors = $this->db->table_exists('doctors')
            ? $this->db->get_where('doctors', ['is_active' => 1])->result()
            : [];

        if ($overviewNav === null) {
            $overviewNav = $this->_is_admin()
                ? [
                    ['label' => 'Dashboard overview', 'url' => site_url('dashboard')],
                    ['label' => 'Register staff', 'url' => site_url('register')],
                ]
                : [];
        }

        return [
            'accomplishments' => $accomplishments,
            'accomplishment_categories' => array_filter(array_map(function ($row) {
                return $row->category;
            }, $categories)),
            'can_manage_accomplishments' => $isClient && $staffId > 0,
            'overview_nav' => $overviewNav,
            'addresses' => $addresses,
            'appointment_types' => $appointmentTypes,
            'appointment_rooms' => $appointmentRooms,
            'is_admin' => $isAdmin,
            'is_staff' => $isStaff,
            'is_client' => $isClient,
            'current_staff_id' => $staffId,
            'doctors' => $doctors,
        ];
    }

    private function _staff_overview_data()
    {
        $staffId = (int) $this->session->userdata('staff_id');
        $role = strtolower((string)$this->session->userdata('role'));
        $isStaff = ($role === 'staff');
        $isClient = ($role === 'client');
        $isAdmin = $this->_is_admin();

        $hasStaffProfile = $staffId > 0;
        if ($isStaff || $isAdmin) {
            $accomplishments = $this->Accomplishment_model->recent_all_with_staff(10);
            $stats = [
                'total_appointments'  => $this->Accomplishment_model->count_all(),
                'pending'             => $this->Accomplishment_model->count_by_status('pending'),
                'accepted'            => $this->Accomplishment_model->count_by_status('accepted'),
            ];
        } else {
            $accomplishments = $hasStaffProfile
                ? $this->Accomplishment_model->recent_for_staff($staffId, 10)
                : [];
            $stats = [
                'total_accomplishments' => $hasStaffProfile ? $this->Accomplishment_model->count_for_staff($staffId) : 0,
                'public_accomplishments'=> $hasStaffProfile ? $this->Accomplishment_model->count_public_for_staff($staffId) : 0,
            ];
        }

        $dashboardNav = [
            ['label' => 'Log appointments', 'target' => site_url('dashboard/log')],
        ];

        return [
            'dashboard_nav' => $dashboardNav,
            'stats'         => $stats,
            'accomplishments' => $accomplishments,
            'has_staff_profile' => $hasStaffProfile,
            'is_staff' => $isStaff,
            'is_client' => $isClient,
            'is_admin' => $isAdmin,
        ];
    }

    private function _is_admin()
    {
        return strtolower((string) $this->session->userdata('role')) === 'admin';
    }

    private function _is_staff()
    {
        return strtolower((string) $this->session->userdata('role')) === 'staff';
    }

    private function _is_client()
    {
        return strtolower((string) $this->session->userdata('role')) === 'client';
    }

    /**
     * Simple guard.
     */
    private function _require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    private function _position_options(): array
    {
        return [
            'Administrator',
            'Booking Officer',
            'Receptionist',
            'Doctor',
            'Nurse',
            'Counselor',
            'Therapist',
            'Support Staff',
        ];
    }

    /**
     * Resolve reCAPTCHA keys from config, then override from srms_settings/o_srms_settings if present.
     */
    private function _recaptcha_config(): array
    {
        $this->config->load('recaptcha');
        $siteKey   = $this->config->item('recaptcha_site_key');
        $secretKey = $this->config->item('recaptcha_secret_key');

        // Prefer keys stored in srms_settings tables if available
        $settingsTable = null;
        if ($this->db->table_exists('srms_settings')) {
            $settingsTable = 'srms_settings';
        } elseif ($this->db->table_exists('o_srms_settings')) {
            $settingsTable = 'o_srms_settings';
        }

        if ($settingsTable) {
            $row = $this->db->limit(1)->get($settingsTable)->row();
            if ($row) {
                $siteCandidates = ['recaptcha_site_key', 'recaptcha_site', 'google_site_key', 'site_key'];
                $secretCandidates = ['recaptcha_secret_key', 'google_secret_key', 'secret_key', 'sec_key'];

                foreach ($siteCandidates as $col) {
                    if (isset($row->$col) && trim((string)$row->$col) !== '') {
                        $siteKey = trim((string)$row->$col);
                        break;
                    }
                }
                foreach ($secretCandidates as $col) {
                    if (isset($row->$col) && trim((string)$row->$col) !== '') {
                        $secretKey = trim((string)$row->$col);
                        break;
                    }
                }
            }
        }

        return [
            'site_key'   => $siteKey,
            'secret_key' => $secretKey,
        ];
    }

    /**
     * Verify reCAPTCHA token with Google.
     */
    private function _verify_recaptcha(string $secret, ?string $response): bool
    {
        $token = trim((string) $response);
        if ($secret === '' || $token === '') {
            return false;
        }

        $payload = http_build_query([
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $this->input->ip_address(),
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 5,
            ],
        ];

        $context = stream_context_create($opts);
        $result = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($result === false) {
            return false;
        }

        $json = json_decode($result, true);
        return is_array($json) && !empty($json['success']);
    }

    private function _address_exists(int $addressId): bool
    {
        if ($addressId <= 0) {
            return false;
        }
        // Try the new address table first, fallback to legacy settings_address
        if ($this->db->table_exists('address')) {
            return $this->db->where('AddID', $addressId)->limit(1)->get('address')->num_rows() > 0;
        }
        if ($this->db->table_exists('settings_address')) {
            return $this->db->where('id', $addressId)->limit(1)->get('settings_address')->num_rows() > 0;
        }
        return false;
    }
}
