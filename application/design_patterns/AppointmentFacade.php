<?php
// Facade Pattern for Appointment Booking
require_once(APPPATH . 'design_patterns/AppointmentFactory.php');
require_once(APPPATH . 'design_patterns/ValidationStrategy.php');
require_once(APPPATH . 'design_patterns/AppointmentObserver.php');

class AppointmentFacade {
    public static function bookAppointment($staffId, $input) {
        // 1. Create appointment object (Factory)
        $type = 'patient'; // or determine from $input
        $appointment = \AppointmentFactory::create($type);
        $appointment->schedule();

        // 2. Validate appointment (Strategy)
        $validator = new \AppointmentValidator(new \PatientValidation());
        $data = [
            'patient_id' => $staffId // Example: using staffId as patient_id
        ];
        if (!$validator->validate($data)) {
            return ['success' => false, 'message' => 'Invalid patient data.'];
        }

        // 3. Save to DB (simulate, should be done in controller/model)
        // ...

        // 4. Notify (Observer)
        $subject = new \HospitalAppointmentSubject();
        $subject->attach(new \PatientNotifier());
        $subject->attach(new \DoctorNotifier());
        $subject->notify('A new appointment has been booked.');

        return ['success' => true, 'message' => 'Appointment booked successfully.'];
    }
}
