<?php
// Factory Pattern for Appointment Creation
interface AppointmentInterface {
    public function schedule();
}

class PatientAppointment implements AppointmentInterface {
    public function schedule() {
        return "Patient appointment scheduled.";
    }
}

class DoctorAppointment implements AppointmentInterface {
    public function schedule() {
        return "Doctor appointment scheduled.";
    }
}

class AppointmentFactory {
    public static function create($type) {
        switch ($type) {
            case 'patient':
                return new PatientAppointment();
            case 'doctor':
                return new DoctorAppointment();
            default:
                throw new Exception("Invalid appointment type");
        }
    }
}
