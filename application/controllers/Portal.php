<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Portal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
        $this->load->model('Accomplishment_model');
    }

    public function appointments()
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        $rows = $staffId > 0 ? $this->Accomplishment_model->get_for_staff($staffId) : [];
        $this->load->view('portal_appointments', [
            'appointments' => $rows,
            'heading' => 'My Appointments',
            'subheading' => 'View your booked appointments.',
            'mode' => 'client',
        ]);
    }

    public function manage_appointments()
    {
        $this->_require_login();
        $rows = $this->Accomplishment_model->all_with_staff();
        $this->load->view('portal_appointments', [
            'appointments' => $rows,
            'heading' => 'Approve / Reject Appointments',
            'subheading' => 'Review and manage appointment requests.',
            'mode' => 'staff_manage',
        ]);
    }

    public function billing()
    {
        $this->_require_login();
        $this->load->view('portal_billing');
    }

    public function contact()
    {
        $this->_require_login();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('subject', 'Subject', 'required|trim');
            $this->form_validation->set_rules('message', 'Message', 'required|trim');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error', validation_errors('', ''));
                return redirect('portal/contact');
            }

            // Placeholder: store contact requests in session flash only.
            $this->session->set_flashdata('success', 'Message submitted. Our team will get back to you.');
            return redirect('portal/contact');
        }

        $this->load->view('portal_contact');
    }

    public function reschedule($id = null)
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        $id = (int) $id;
        if ($staffId <= 0 || $id <= 0) {
            return redirect('portal/appointments');
        }

        $existing = $this->Accomplishment_model->find($id, $staffId);
        if (!$existing) {
            $this->session->set_flashdata('error', 'Appointment not found.');
            return redirect('portal/appointments');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
            $this->form_validation->set_rules('start_time', 'Start Time', 'required|trim');
            $this->form_validation->set_rules('location', 'Location', 'trim');
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors('', ''));
                return redirect('portal/reschedule/' . $id);
            }

            $startDate = $this->input->post('start_date', TRUE);
            $startTime = $this->input->post('start_time', TRUE);
            $dateTime = $this->_combine_date_time($startDate, $startTime);

            $payload = [
                'start_date' => $dateTime,
                'location'   => $this->input->post('location', TRUE),
                'doctor'     => $this->input->post('doctor', TRUE),
            ];

            $this->Accomplishment_model->update($id, $staffId, $payload);
            $this->session->set_flashdata('success', 'Appointment rescheduled.');
            return redirect('portal/appointments');
        }

        $this->load->view('portal_reschedule', ['appointment' => $existing]);
    }

    public function cancel($id = null)
    {
        $this->_require_login();
        $staffId = (int) $this->session->userdata('staff_id');
        $id = (int) $id;
        if ($staffId <= 0 || $id <= 0) {
            return redirect('portal/appointments');
        }

        $existing = $this->Accomplishment_model->find($id, $staffId);
        if (!$existing) {
            $this->session->set_flashdata('error', 'Appointment not found.');
            return redirect('portal/appointments');
        }

        $this->Accomplishment_model->delete($id, $staffId);
        $this->session->set_flashdata('success', 'Appointment canceled.');
        return redirect('portal/appointments');
    }

    public function patient_list()
    {
        $this->_require_login();
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('first_name', 'First name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            if ($this->form_validation->run()) {
                $first = $this->input->post('first_name', TRUE);
                $last = $this->input->post('last_name', TRUE);
                $email = $this->input->post('email', TRUE);
                $password = (string)$this->input->post('password', TRUE);

                $this->db->insert('staff', [
                    'first_name' => $first,
                    'last_name'  => $last,
                    'position_title' => 'Client',
                    'is_active'  => 1,
                    'is_public'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $staffId = $this->db->insert_id();

                $this->db->insert('users', [
                    'staff_id'      => $staffId,
                    'username'      => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'role'          => 'client',
                    'status'        => 1,
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Patient created.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/patient-list');
        }

        $this->db->select('u.id, u.username, u.status, u.created_at, s.first_name, s.last_name, s.position_title');
        $this->db->from('users u');
        $this->db->join('staff s', 's.staff_id = u.staff_id', 'left');
        $this->db->where('u.role', 'client');
        $this->db->order_by('s.first_name', 'ASC');
        $patients = $this->db->get()->result();

        $this->load->view('portal_patient_list', ['patients' => $patients]);
    }

    public function manage_patients()
    {
        $this->_require_login();
        $this->_require_admin();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action', TRUE);
            if ($action === 'toggle') {
                $this->form_validation->set_rules('user_id', 'User', 'required|integer');
                $this->form_validation->set_rules('status', 'Status', 'required|integer');
                if ($this->form_validation->run()) {
                    $userId = (int)$this->input->post('user_id', TRUE);
                    $status = (int)$this->input->post('status', TRUE);
                    $this->db->where('id', $userId)->update('users', ['status' => $status]);
                    $this->session->set_flashdata('success', 'Patient status updated.');
                }
            } elseif ($action === 'create') {
                $this->form_validation->set_rules('first_name', 'First name', 'required|trim');
                $this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
                $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.username]');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
                if ($this->form_validation->run()) {
                    $first = $this->input->post('first_name', TRUE);
                    $last = $this->input->post('last_name', TRUE);
                    $email = $this->input->post('email', TRUE);
                    $password = (string)$this->input->post('password', TRUE);

                    $this->db->insert('staff', [
                        'first_name' => $first,
                        'last_name'  => $last,
                        'position_title' => 'Client',
                        'is_active'  => 1,
                        'is_public'  => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $staffId = $this->db->insert_id();

                    $this->db->insert('users', [
                        'staff_id'      => $staffId,
                        'username'      => $email,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'role'          => 'client',
                        'status'        => 1,
                        'created_at'    => date('Y-m-d H:i:s'),
                    ]);

                    $this->session->set_flashdata('success', 'Patient created.');
                } else {
                    $this->session->set_flashdata('error', validation_errors('', ''));
                }
            }
            return redirect('portal/manage-patients');
        }

        $this->db->select('u.id, u.username, u.status, u.created_at, s.first_name, s.last_name');
        $this->db->from('users u');
        $this->db->join('staff s', 's.staff_id = u.staff_id', 'left');
        $this->db->where('u.role', 'client');
        $patients = $this->db->get()->result();
        $this->load->view('portal_manage_patients', ['patients' => $patients]);
    }

    public function manage_staff()
    {
        $this->_require_login();
        $this->_require_admin();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('user_id', 'User', 'required|integer');
            $this->form_validation->set_rules('status', 'Status', 'required|integer');
            if ($this->form_validation->run()) {
                $userId = (int)$this->input->post('user_id', TRUE);
                $status = (int)$this->input->post('status', TRUE);
                $this->db->where('id', $userId)->update('users', ['status' => $status]);
                $this->session->set_flashdata('success', 'Staff status updated.');
            }
            return redirect('portal/manage-staff');
        }

        if ($this->input->post('action') === 'create') {
            $this->form_validation->set_rules('first_name', 'First name', 'required|trim');
            $this->form_validation->set_rules('last_name', 'Last name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('position_title', 'Position', 'required|trim');
            if ($this->form_validation->run()) {
                $first = $this->input->post('first_name', TRUE);
                $last = $this->input->post('last_name', TRUE);
                $email = $this->input->post('email', TRUE);
                $password = (string)$this->input->post('password', TRUE);
                $position = $this->input->post('position_title', TRUE);

                $this->db->insert('staff', [
                    'first_name' => $first,
                    'last_name'  => $last,
                    'position_title' => $position,
                    'is_active'  => 1,
                    'is_public'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $staffId = $this->db->insert_id();

                $this->db->insert('users', [
                    'staff_id'      => $staffId,
                    'username'      => $email,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'role'          => 'staff',
                    'status'        => 1,
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);

                $this->session->set_flashdata('success', 'Staff created.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/manage-staff');
        }

        $this->db->select('u.id, u.username, u.status, u.created_at, s.first_name, s.last_name, s.position_title');
        $this->db->from('users u');
        $this->db->join('staff s', 's.staff_id = u.staff_id', 'left');
        $this->db->where('u.role', 'staff');
        $staff = $this->db->get()->result();
        $this->load->view('portal_manage_staff', ['staff' => $staff]);
    }

    public function doctors()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_doctors_table();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action', TRUE);
            if ($action === 'add') {
                $this->form_validation->set_rules('name', 'Name', 'required|trim');
                $this->form_validation->set_rules('specialty', 'Specialty', 'trim');
                if ($this->form_validation->run()) {
                    $this->db->insert('doctors', [
                        'name'       => $this->input->post('name', TRUE),
                        'specialty'  => $this->input->post('specialty', TRUE),
                        'is_active'  => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $this->session->set_flashdata('success', 'Doctor added.');
                } else {
                    $this->session->set_flashdata('error', validation_errors('', ''));
                }
            } elseif ($action === 'toggle') {
                $id = (int)$this->input->post('id', TRUE);
                $status = (int)$this->input->post('status', TRUE);
                if ($id > 0) {
                    $this->db->where('id', $id)->update('doctors', ['is_active' => $status]);
                    $this->session->set_flashdata('success', 'Doctor status updated.');
                }
            }
            return redirect('portal/doctors');
        }

        $doctors = $this->db->get('doctors')->result();
        $this->load->view('portal_doctors', ['doctors' => $doctors]);
    }

    public function services()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_services_table();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action', TRUE);
            if ($action === 'add') {
                $this->form_validation->set_rules('name', 'Service', 'required|trim');
                $this->form_validation->set_rules('price', 'Price', 'required|numeric');
                if ($this->form_validation->run()) {
                    $this->db->insert('medical_services', [
                        'name'       => $this->input->post('name', TRUE),
                        'price'      => (float)$this->input->post('price', TRUE),
                        'is_active'  => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $this->session->set_flashdata('success', 'Service added.');
                } else {
                    $this->session->set_flashdata('error', validation_errors('', ''));
                }
            } elseif ($action === 'toggle') {
                $id = (int)$this->input->post('id', TRUE);
                $status = (int)$this->input->post('status', TRUE);
                if ($id > 0) {
                    $this->db->where('id', $id)->update('medical_services', ['is_active' => $status]);
                    $this->session->set_flashdata('success', 'Service status updated.');
                }
            }
            return redirect('portal/services');
        }

        $services = $this->db->get('medical_services')->result();
        $this->load->view('portal_services', ['services' => $services]);
    }

    public function hospital_info()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_hospital_settings();

        if ($this->input->method() === 'post') {
            $data = [
                'name'    => $this->input->post('name', TRUE),
                'address' => $this->input->post('address', TRUE),
                'phone'   => $this->input->post('phone', TRUE),
                'email'   => $this->input->post('email', TRUE),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $exists = $this->db->get('hospital_settings')->row();
            if ($exists) {
                $this->db->update('hospital_settings', $data, ['id' => $exists->id]);
            } else {
                $this->db->insert('hospital_settings', $data);
            }
            $this->session->set_flashdata('success', 'Hospital info saved.');
            return redirect('portal/hospital-info');
        }

        $info = $this->db->get('hospital_settings')->row();
        $this->load->view('portal_hospital_info', ['info' => $info]);
    }

    public function appointment_reports()
    {
        $this->_require_login();
        $stats = [
            'total'    => $this->Accomplishment_model->count_all(),
            'pending'  => $this->Accomplishment_model->count_by_status('pending'),
            'accepted' => $this->Accomplishment_model->count_by_status('accepted'),
            'declined' => $this->Accomplishment_model->count_by_status('declined'),
        ];
        $this->load->view('portal_reports', ['stats' => $stats, 'heading' => 'Appointment Reports']);
    }

    public function patient_statistics()
    {
        $this->_require_login();
        $totalClients = (int)$this->db->where('role', 'client')->count_all_results('users');
        $totalStaff   = (int)$this->db->where('role', 'staff')->count_all_results('users');
        $this->load->view('portal_reports', [
            'stats' => [
                'total_clients' => $totalClients,
                'total_staff'   => $totalStaff,
            ],
            'heading' => 'Patient Statistics',
        ]);
    }

    public function booking_rules()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_booking_rules_table();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name', 'Rule name', 'required|trim');
            $this->form_validation->set_rules('value', 'Value', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('booking_rules', [
                    'name'       => $this->input->post('name', TRUE),
                    'value'      => $this->input->post('value', TRUE),
                    'is_active'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Rule saved.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/booking-rules');
        }

        $rules = $this->db->order_by('created_at', 'DESC')->get('booking_rules')->result();
        $this->load->view('portal_booking_rules', ['rules' => $rules]);
    }

    public function roles()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_roles_tables();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name', 'Role', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('roles', [
                    'name'       => $this->input->post('name', TRUE),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Role added.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/roles');
        }

        $roles = $this->db->get('roles')->result();
        $this->load->view('portal_roles', ['roles' => $roles]);
    }

    public function permissions()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_roles_tables();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action', TRUE);
            if ($action === 'add-permission') {
                $this->form_validation->set_rules('name', 'Permission', 'required|trim');
                if ($this->form_validation->run()) {
                    $this->db->insert('permissions', [
                        'name'       => $this->input->post('name', TRUE),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $this->session->set_flashdata('success', 'Permission added.');
                } else {
                    $this->session->set_flashdata('error', validation_errors('', ''));
                }
            } elseif ($action === 'assign') {
                $roleId = (int)$this->input->post('role_id', TRUE);
                $permId = (int)$this->input->post('permission_id', TRUE);
                if ($roleId > 0 && $permId > 0) {
                    $this->db->insert('role_permissions', [
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                    ]);
                    $this->session->set_flashdata('success', 'Permission assigned.');
                }
            }
            return redirect('portal/permissions');
        }

        $roles = $this->db->get('roles')->result();
        $perms = $this->db->get('permissions')->result();
        $rolePerms = $this->db->get('role_permissions')->result();
        $this->load->view('portal_permissions', [
            'roles' => $roles,
            'permissions' => $perms,
            'role_permissions' => $rolePerms,
        ]);
    }

    public function departments()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_departments_table();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name', 'Department', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('departments', [
                    'name'       => $this->input->post('name', TRUE),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Department added.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/departments');
        }

        $departments = $this->db->get('departments')->result();
        $this->load->view('portal_departments', ['departments' => $departments]);
    }

    public function schedules()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_schedules_table();
        $this->_ensure_doctors_table();

        $doctors = $this->db->where('is_active', 1)->get('doctors')->result();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('doctor_id', 'Doctor', 'required|integer');
            $this->form_validation->set_rules('day_of_week', 'Day', 'required|trim');
            $this->form_validation->set_rules('start_time', 'Start', 'required|trim');
            $this->form_validation->set_rules('end_time', 'End', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('doctor_schedules', [
                    'doctor_id'  => (int)$this->input->post('doctor_id', TRUE),
                    'day_of_week'=> $this->input->post('day_of_week', TRUE),
                    'start_time' => $this->input->post('start_time', TRUE),
                    'end_time'   => $this->input->post('end_time', TRUE),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Schedule saved.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/schedules');
        }

        $this->db->select('ds.*, d.name as doctor_name');
        $this->db->from('doctor_schedules ds');
        $this->db->join('doctors d', 'd.id = ds.doctor_id', 'left');
        $schedules = $this->db->get()->result();

        $this->load->view('portal_schedules', ['doctors' => $doctors, 'schedules' => $schedules]);
    }

    public function medical_records()
    {
        $this->_require_login();
        $this->_ensure_medical_records_table();
        $clients = $this->_client_options();
        $patientId = (int)$this->input->get('patient_id', TRUE);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('user_id', 'Patient', 'required|integer');
            $this->form_validation->set_rules('title', 'Title', 'required|trim');
            $this->form_validation->set_rules('notes', 'Notes', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('medical_records', [
                    'user_id'    => (int)$this->input->post('user_id', TRUE),
                    'title'      => $this->input->post('title', TRUE),
                    'notes'      => $this->input->post('notes', TRUE),
                    'created_by' => (int)$this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Record saved.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/medical-records');
        }

        $this->db->select('mr.*, u.username, s.first_name, s.last_name');
        $this->db->from('medical_records mr');
        $this->db->join('users u', 'u.id = mr.user_id', 'left');
        $this->db->join('staff s', 's.staff_id = u.staff_id', 'left');
        if ($patientId > 0) {
            $this->db->where('mr.user_id', $patientId);
        }
        $records = $this->db->order_by('mr.created_at', 'DESC')->get()->result();

        $this->load->view('portal_medical_records', [
            'records' => $records,
            'clients' => $clients,
            'selected_patient_id' => $patientId,
        ]);
    }

    public function consultation_notes()
    {
        $this->_require_login();
        $this->_ensure_consultation_notes_table();
        $clients = $this->_client_options();
        $patientId = (int)$this->input->get('patient_id', TRUE);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('user_id', 'Patient', 'required|integer');
            $this->form_validation->set_rules('notes', 'Notes', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('consultation_notes', [
                    'user_id'    => (int)$this->input->post('user_id', TRUE),
                    'notes'      => $this->input->post('notes', TRUE),
                    'created_by' => (int)$this->session->userdata('user_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Consultation note saved.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/consultation-notes');
        }

        $this->db->select('cn.*, u.username, s.first_name, s.last_name');
        $this->db->from('consultation_notes cn');
        $this->db->join('users u', 'u.id = cn.user_id', 'left');
        $this->db->join('staff s', 's.staff_id = u.staff_id', 'left');
        if ($patientId > 0) {
            $this->db->where('cn.user_id', $patientId);
        }
        $notes = $this->db->order_by('cn.created_at', 'DESC')->get()->result();

        $this->load->view('portal_consultation_notes', [
            'notes' => $notes,
            'clients' => $clients,
            'selected_patient_id' => $patientId,
        ]);
    }

    public function client_medical_records()
    {
        $this->_require_login();
        $this->_ensure_medical_records_table();
        $userId = (int)$this->session->userdata('user_id');

        $this->db->select('mr.*, s.first_name, s.last_name');
        $this->db->from('medical_records mr');
        $this->db->join('staff s', 's.staff_id = mr.created_by', 'left');
        $this->db->where('mr.user_id', $userId);
        $records = $this->db->order_by('mr.created_at', 'DESC')->get()->result();

        $this->load->view('portal_patient_records', [
            'records' => $records,
            'patient_name' => $this->session->userdata('username'),
        ]);
    }

    public function invoices()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_invoices_table();

        $clients = $this->_client_options();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('user_id', 'Patient', 'required|integer');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            if ($this->form_validation->run()) {
                $this->db->insert('invoices', [
                    'user_id'    => (int)$this->input->post('user_id', TRUE),
                    'amount'     => (float)$this->input->post('amount', TRUE),
                    'status'     => 'unpaid',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Invoice created.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/invoices');
        }

        $this->db->select('i.*, u.username');
        $this->db->from('invoices i');
        $this->db->join('users u', 'u.id = i.user_id', 'left');
        $invoices = $this->db->order_by('i.created_at', 'DESC')->get()->result();
        $this->load->view('portal_invoices', ['invoices' => $invoices, 'clients' => $clients]);
    }

    public function payments()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_payments_table();
        $this->_ensure_invoices_table();

        $invoices = $this->db->get('invoices')->result();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('invoice_id', 'Invoice', 'required|integer');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            if ($this->form_validation->run()) {
                $invoiceId = (int)$this->input->post('invoice_id', TRUE);
                $amount = (float)$this->input->post('amount', TRUE);
                $this->db->insert('payments', [
                    'invoice_id' => $invoiceId,
                    'amount'     => $amount,
                    'method'     => $this->input->post('method', TRUE),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                // mark invoice paid if amounts meet/exceed
                $sum = $this->db->select_sum('amount')->where('invoice_id', $invoiceId)->get('payments')->row()->amount ?? 0;
                $inv = $this->db->where('id', $invoiceId)->get('invoices')->row();
                if ($inv && $sum >= (float)$inv->amount) {
                    $this->db->where('id', $invoiceId)->update('invoices', ['status' => 'paid']);
                }
                $this->session->set_flashdata('success', 'Payment recorded.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/payments');
        }

        $this->db->select('p.*, i.amount as invoice_amount');
        $this->db->from('payments p');
        $this->db->join('invoices i', 'i.id = p.invoice_id', 'left');
        $payments = $this->db->order_by('p.created_at', 'DESC')->get()->result();
        $this->load->view('portal_payments', ['payments' => $payments, 'invoices' => $invoices]);
    }

    public function audit_logs()
    {
        $this->_require_login();
        $this->_require_admin();
        $this->_ensure_audit_logs_table();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action_text', TRUE);
            if (trim($action) !== '') {
                $this->db->insert('audit_logs', [
                    'user_id' => (int)$this->session->userdata('user_id'),
                    'action'  => $action,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Log added.');
            }
            return redirect('portal/audit-logs');
        }

        $this->db->select('al.*, u.username');
        $this->db->from('audit_logs al');
        $this->db->join('users u', 'u.id = al.user_id', 'left');
        $logs = $this->db->order_by('al.created_at', 'DESC')->get()->result();
        $this->load->view('portal_audit_logs', ['logs' => $logs]);
    }

    public function walkins()
    {
        $this->_require_login();
        $this->_ensure_walkins_table();

        if ($this->input->method() === 'post') {
            $action = $this->input->post('action', TRUE);
            if ($action === 'add') {
                $this->form_validation->set_rules('name', 'Name', 'required|trim');
                if ($this->form_validation->run()) {
                    $this->db->insert('walk_in_patients', [
                        'name'       => $this->input->post('name', TRUE),
                        'reason'     => $this->input->post('reason', TRUE),
                        'status'     => 'queued',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $this->session->set_flashdata('success', 'Walk-in logged.');
                } else {
                    $this->session->set_flashdata('error', validation_errors('', ''));
                }
            } elseif ($action === 'update_status') {
                $id = (int)$this->input->post('id', TRUE);
                $status = $this->input->post('status', TRUE);
                if ($id > 0 && $status !== '') {
                    $this->db->where('id', $id)->update('walk_in_patients', ['status' => $status]);
                    $this->session->set_flashdata('success', 'Walk-in updated.');
                }
            }
            return redirect('portal/walkins');
        }

        $walkins = $this->db->order_by('created_at', 'DESC')->get('walk_in_patients')->result();
        $this->load->view('portal_walkins', ['walkins' => $walkins]);
    }

    public function prescriptions()
    {
        $this->_require_login();
        $this->_ensure_prescriptions_table();
        $clients = $this->_client_options();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('user_id', 'Patient', 'required|integer');
            $this->form_validation->set_rules('medication', 'Medication', 'required|trim');
            $this->form_validation->set_rules('dosage', 'Dosage', 'required|trim');
            if ($this->form_validation->run()) {
                $userId = (int)$this->input->post('user_id', TRUE);
                $patientLabel = '';
                foreach ($clients as $c) {
                    if ((int)$c['id'] === $userId) {
                        $patientLabel = $c['label'];
                        break;
                    }
                }
                $this->db->insert('prescriptions', [
                    'user_id'      => $userId,
                    'patient_name' => $patientLabel ?: $this->input->post('patient_name', TRUE),
                    'medication'   => $this->input->post('medication', TRUE),
                    'dosage'       => $this->input->post('dosage', TRUE),
                    'instructions' => $this->input->post('instructions', TRUE),
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Prescription saved.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/prescriptions');
        }

        $prescriptions = $this->db->order_by('created_at', 'DESC')->get('prescriptions')->result();
        $this->load->view('portal_prescriptions', [
            'prescriptions' => $prescriptions,
            'clients' => $clients,
            'client_view' => false,
        ]);
    }

    public function client_prescriptions()
    {
        $this->_require_login();
        $this->_ensure_prescriptions_table();
        $userId = (int)$this->session->userdata('user_id');

        $patientName = '';
        $staffRow = $this->db->where('staff_id', $this->session->userdata('staff_id'))->get('staff')->row();
        if ($staffRow) {
            $patientName = trim(($staffRow->first_name ?? '') . ' ' . ($staffRow->last_name ?? ''));
        }

        $this->db->from('prescriptions');
        $this->db->group_start()
            ->where('user_id', $userId);
        if ($patientName !== '') {
            $this->db->or_like('patient_name', $patientName, 'both');
        }
        $this->db->group_end();
        $prescriptions = $this->db->order_by('created_at', 'DESC')->get()->result();

        $this->load->view('portal_prescriptions', [
            'prescriptions' => $prescriptions,
            'clients' => [],
            'client_view' => true,
        ]);
    }

    public function lab_requests()
    {
        $this->_require_login();
        $this->_ensure_lab_requests_table();

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('patient_name', 'Patient', 'required|trim');
            $this->form_validation->set_rules('test_name', 'Test', 'required|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|trim');
            if ($this->form_validation->run()) {
                $this->db->insert('lab_requests', [
                    'patient_name' => $this->input->post('patient_name', TRUE),
                    'test_name'    => $this->input->post('test_name', TRUE),
                    'status'       => $this->input->post('status', TRUE),
                    'result'       => $this->input->post('result', TRUE),
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
                $this->session->set_flashdata('success', 'Lab request saved.');
            } else {
                $this->session->set_flashdata('error', validation_errors('', ''));
            }
            return redirect('portal/lab-requests');
        }

        $requests = $this->db->order_by('created_at', 'DESC')->get('lab_requests')->result();
        $this->load->view('portal_lab_requests', ['requests' => $requests]);
    }

    /**
     * Generic placeholder pages for routes we have not yet implemented.
     */
    public function page($slug = '')
    {
        $this->_require_login();
        $slug = trim((string) $slug);
        $titles = [
            // Admin
            'manage-patients'      => 'Manage Patients',
            'roles-permissions'    => 'Roles & Permissions',
            'booking-rules'        => 'Booking Rules & Limits',
            'departments'          => 'Departments',
            'doctors-schedules'    => 'Doctors & Schedules',
            'medical-services'     => 'Medical Services',
            'invoices'             => 'Invoices',
            'revenue-reports'      => 'Revenue Reports',
            'appointment-reports'  => 'Appointment Reports',
            'patient-statistics'   => 'Patient Statistics',
            'audit-logs'           => 'Audit Logs',
            'hospital-info'        => 'Hospital Information',
            'clinic-hours'         => 'Clinic Hours',
            'notifications'        => 'Notifications',
            'backup-restore'       => 'Backup & Restore',
            'access-logs'          => 'Access Logs',
            'account-status'       => 'Account Status',
            // Staff
            'walk-in-patients'         => 'Walk-in Patients',
            'patient-list'             => 'Patient List',
            'patient-medical-records'  => 'Patient Medical Records',
            'consultation-notes'       => 'Consultation Notes',
            'staff-prescriptions'      => 'Prescriptions',
            'lab-requests'             => 'Lab Requests / Results',
            'daily-monthly-reports'    => 'Daily / Monthly Reports',
            // Client
            'cancel-reschedule'    => 'Cancel / Reschedule',
            'my-medical-records'   => 'My Medical Records',
            'lab-results'          => 'Lab Results',
            'client-prescriptions' => 'Prescriptions',
            'payment-history'      => 'Payment History',
            'change-password'      => 'Change Password',
            'help-faq'             => 'Help / FAQs',
        ];

        $title = $titles[$slug] ?? 'Coming soon';
        $meta = $this->_page_meta($slug, $title);
        $this->load->view('portal_placeholder', $meta);
    }

    private function _require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    private function _require_admin()
    {
        if (strtolower((string)$this->session->userdata('role')) !== 'admin') {
            redirect('dashboard');
        }
    }

    private function _page_meta(string $slug, string $title): array
    {
        $descriptions = [
            // Admin
            'manage-patients'      => 'Create, edit, and deactivate patient records.',
            'roles-permissions'    => 'Define access levels and permissions per role.',
            'booking-rules'        => 'Configure appointment limits, buffers, and policies.',
            'departments'          => 'Manage hospital departments and services.',
            'doctors-schedules'    => 'Set and adjust provider schedules.',
            'medical-services'     => 'Configure medical services and pricing.',
            'invoices'             => 'Review and issue invoices.',
            'revenue-reports'      => 'Track revenue by period.',
            'appointment-reports'  => 'Analyze appointment volume and trends.',
            'patient-statistics'   => 'View patient demographics and usage.',
            'audit-logs'           => 'Monitor key system activities.',
            'hospital-info'        => 'Update hospital contact and branding.',
            'clinic-hours'         => 'Set operating hours and holidays.',
            'notifications'        => 'Configure notifications and channels.',
            'backup-restore'       => 'Manage system backups and restores.',
            'access-logs'          => 'Review sign-in and access attempts.',
            'account-status'       => 'Manage account lockouts or suspensions.',
            // Staff
            'walk-in-patients'         => 'Log and queue walk-in patients.',
            'patient-list'             => 'Browse and search patients.',
            'patient-medical-records'  => 'View and update patient records.',
            'consultation-notes'       => 'Record consultation notes.',
            'staff-prescriptions'      => 'Create and review prescriptions.',
            'lab-requests'             => 'Create and track lab requests/results.',
            'daily-monthly-reports'    => 'Operational reporting for staff.',
            // Client
            'cancel-reschedule'    => 'Manage your existing appointment bookings.',
            'my-medical-records'   => 'View your medical history.',
            'lab-results'          => 'Review lab results.',
            'client-prescriptions' => 'View prescribed medications.',
            'payment-history'      => 'View past payments.',
            'change-password'      => 'Update your account password.',
            'help-faq'             => 'Get answers to common questions.',
        ];

        $actions = [
            'manage-patients' => [
                ['label' => 'Add patient', 'href' => 'javascript:void(0);'],
                ['label' => 'Import CSV', 'href' => 'javascript:void(0);'],
            ],
            'roles-permissions' => [
                ['label' => 'Add role', 'href' => 'javascript:void(0);'],
                ['label' => 'Assign permissions', 'href' => 'javascript:void(0);'],
            ],
            'booking-rules' => [
                ['label' => 'Edit rules', 'href' => 'javascript:void(0);'],
            ],
            'walk-in-patients' => [
                ['label' => 'Log walk-in', 'href' => 'javascript:void(0);'],
            ],
            'cancel-reschedule' => [
                ['label' => 'Manage appointments', 'href' => site_url('portal/appointments')],
            ],
            'change-password' => [
                ['label' => 'Change password', 'href' => 'javascript:void(0);'],
            ],
        ];

        return [
            'title'       => $title,
            'description' => $descriptions[$slug] ?? 'This section is not yet available.',
            'actions'     => $actions[$slug] ?? [],
            'slug'        => $slug,
        ];
    }

    private function _combine_date_time(?string $date, ?string $time): ?string
    {
        $date = trim((string)$date);
        $time = trim((string)$time);
        if ($date === '') {
            return null;
        }
        if ($time === '') {
            return $date;
        }
        $timestamp = strtotime($date . ' ' . $time);
        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }
        return trim($date . ' ' . $time . ':00');
    }

    private function _ensure_doctors_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS doctors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            specialty VARCHAR(255) NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_services_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS medical_services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_hospital_settings()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS hospital_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            address TEXT,
            phone VARCHAR(100),
            email VARCHAR(255),
            updated_at DATETIME
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_walkins_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS walk_in_patients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            reason TEXT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'queued',
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_prescriptions_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS prescriptions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            patient_name VARCHAR(255) NOT NULL,
            medication VARCHAR(255) NOT NULL,
            dosage VARCHAR(255) NOT NULL,
            instructions TEXT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        // Backfill schema if the table existed before user_id was added
        $hasUser = $this->db->query("SHOW COLUMNS FROM prescriptions LIKE 'user_id'")->row();
        if (!$hasUser) {
            $this->db->query("ALTER TABLE prescriptions ADD COLUMN user_id INT NULL AFTER id, ADD KEY user_id (user_id)");
        }
    }

    private function _ensure_lab_requests_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS lab_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_name VARCHAR(255) NOT NULL,
            test_name VARCHAR(255) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'requested',
            result TEXT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_booking_rules_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS booking_rules (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value TEXT NOT NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_roles_tables()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->query("CREATE TABLE IF NOT EXISTS permissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->query("CREATE TABLE IF NOT EXISTS role_permissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT NOT NULL,
            permission_id INT NOT NULL,
            KEY role_id (role_id),
            KEY permission_id (permission_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_departments_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_schedules_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS doctor_schedules (
            id INT AUTO_INCREMENT PRIMARY KEY,
            doctor_id INT NOT NULL,
            day_of_week VARCHAR(20) NOT NULL,
            start_time VARCHAR(10) NOT NULL,
            end_time VARCHAR(10) NOT NULL,
            created_at DATETIME NOT NULL,
            KEY doctor_id (doctor_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_medical_records_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS medical_records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            notes TEXT NOT NULL,
            created_by INT NULL,
            created_at DATETIME NOT NULL,
            KEY user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_consultation_notes_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS consultation_notes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            notes TEXT NOT NULL,
            created_by INT NULL,
            created_at DATETIME NOT NULL,
            KEY user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_invoices_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS invoices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            status VARCHAR(20) NOT NULL DEFAULT 'unpaid',
            created_at DATETIME NOT NULL,
            KEY user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_payments_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            invoice_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            method VARCHAR(50) NULL,
            created_at DATETIME NOT NULL,
            KEY invoice_id (invoice_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _ensure_audit_logs_table()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            action TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            KEY user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function _client_options(): array
    {
        $rows = $this->db->where('role', 'client')->get('users')->result();
        $out = [];
        foreach ($rows as $row) {
            $nameRow = $this->db->where('staff_id', $row->staff_id)->get('staff')->row();
            $label = trim(($nameRow->first_name ?? '') . ' ' . ($nameRow->last_name ?? ''));
            $out[] = [
                'id' => (int)$row->id,
                'label' => $label !== '' ? $label : $row->username,
            ];
        }
        return $out;
    }
}
