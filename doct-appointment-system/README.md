# Doctor Appointment System

A web-based application for managing doctor appointments and medical records. Built with Flask and SQLAlchemy.

## Features

- User Authentication (Doctors and Patients)
- Appointment Scheduling and Management
- Medical Records Management
- Treatment and Diagnosis Tracking
- Doctor Search and Filtering
- Responsive Design with Bootstrap

## Requirements

- Python 3.8+
- Flask 3.0.2
- Flask-SQLAlchemy 3.1.1
- Flask-Login 0.6.3
- Flask-WTF 1.0.0
- Other dependencies in requirements.txt

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd doctor-appointment-system
```

2. Create a virtual environment and activate it:
```bash
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate
```

3. Install dependencies:
```bash
pip install -r requirements.txt
```

4. Create a `.env` file in the root directory with the following content:
```
SECRET_KEY=your-secret-key
DATABASE_URL=sqlite:///app.db
MAIL_SERVER=smtp.gmail.com
MAIL_PORT=587
MAIL_USE_TLS=true
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-email-password
```

5. Initialize the database:
```bash
flask db upgrade
```

6. Run the application:
```bash
flask run
```

The application will be available at `http://localhost:5000`.

## Project Structure

```
doctor-appointment-system/
├── app/
│   ├── __init__.py
│   ├── models.py
│   ├── auth/
│   │   ├── __init__.py
│   │   ├── forms.py
│   │   └── routes.py
│   ├── main/
│   │   ├── __init__.py
│   │   └── routes.py
│   ├── doctor/
│   │   ├── __init__.py
│   │   └── routes.py
│   ├── patient/
│   │   ├── __init__.py
│   │   └── routes.py
│   ├── static/
│   │   ├── css/
│   │   ├── js/
│   │   └── img/
│   └── templates/
│       ├── auth/
│       ├── main/
│       ├── doctor/
│       ├── patient/
│       └── base.html
├── migrations/
├── config.py
├── requirements.txt
└── README.md
```

## Usage

1. Register as a patient or doctor
2. Log in to your account
3. Patients can:
   - Search for doctors
   - Book appointments
   - View medical records
   - Track treatments and diagnoses
4. Doctors can:
   - Manage appointments
   - Create medical records
   - Add treatments and diagnoses
   - Update their profile

## Contributing

1. Fork the repository
2. Create a new branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 