# Online Appointment Booking

This CodeIgniter template now ships with a barebones online appointment booking flow (staff login, dashboard, appointment logging).

## Quick start
1) Create the database and seed an admin user:
   - Import `resources/appointment_booking_schema.sql` into MySQL.
   - Default DB name: `appointment_booking` (update `application/config/database.php` if you change it).

2) Run the app at `http://localhost/AppointmentBooking/`.
   - Default admin login: `admin` / `admin123`.

## Notes
- Staff members can register themselves (or an admin can create them) via `/register`.
- Appointments are logged from `/dashboard/log`.
