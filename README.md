# Eduxamido

### College Examination & Academic Management System

**Eduxamido** is a PHP-based web application developed to simplify and automate examination and academic administration activities in colleges.

The project focuses on reducing the manual effort involved in examination seating arrangements, classroom allocation, invigilator assignments, duty management, student management, and report generation.

---

## 📌 About the Project

Managing college examinations manually can become difficult when there are large numbers of students, multiple subjects, classrooms, and invigilators.

Traditional approaches often depend on spreadsheets, paper records, and manually prepared documents. This can result in:

* Time-consuming seating arrangements
* Errors in student allocation
* Inefficient classroom utilization
* Difficulty managing invigilator assignments
* Scheduling conflicts
* Manual duty calculations
* Repetitive administrative work
* Inconsistent reports

**Eduxamido** was developed to provide a centralized web-based system for managing these activities.

---

## 🎯 Objectives

The main objectives of Eduxamido are:

* Automate examination seating arrangements
* Efficiently utilize available classrooms
* Simplify student examination management
* Manage departments and academic information
* Manage examination subjects and schedules
* Assign invigilators
* Manage classroom allocation
* Generate examination-related PDF reports
* Reduce manual errors
* Reduce administrative workload

---

## 🪑 Examination Seating Arrangement

One of the important features of Eduxamido is automated examination seating arrangement.

The system is designed around a classroom capacity of:

```text
7 Rows × 5 Columns = 35 Students
```

Students are identified using their subject code and roll number.

Examples:

```text
BCA01
BCA02
BBA19
BCO45
```

The seating algorithm attempts to distribute students from different subjects across adjacent columns.

Example:

```text
BCA01   BBA20   BCO40   BCA08   BBA27
BCA02   BBA21   BCO41   BCA09   BBA28
BCA03   BBA22   BCO42   BCA10   BBA29
BCA04   BBA23   BCO43   BCA11   BBA30
BCA05   BBA24   BCO44   BCA12   BBA31
BCA06   BBA25   BCO45   BCA13   BBA32
BCA07   BBA26   BCO46   BCA14   BBA33
```

This approach helps reduce the possibility of students from the same subject being seated in adjacent columns.

---

## ✨ Features

### Examination Management

* Examination management
* Subject management
* Student examination allocation
* Automated seating arrangements
* Classroom allocation
* Seating arrangement display
* Examination-related reports

### Classroom Management

* Classroom management
* Classroom capacity management
* Student seating allocation
* Seating arrangement visualization

### Invigilator Management

* Invigilator management
* Invigilator assignment
* Invigilator swapping
* Duty allocation
* Assignment tracking

### Department Management

* Department management
* Academic information management
* Student information management

### Scheme Management

* Scheme management
* Academic scheme information
* Subject-related management

### PDF & Reports

* Seating arrangement PDF generation
* Examination reports
* Class/student details
* Administrative documentation

---

## 🏗️ Technology

Eduxamido is currently implemented as a PHP-based web application.

### Technologies

* **PHP**
* **HTML**
* **CSS**
* **JavaScript**
* **MySQL**
* **Apache / WAMP**
* **Git**

The project can be developed and tested using a local PHP development environment such as **WAMP**.

---

## 📂 Project Structure

The project is organized into modules based on different areas of the application.

```text
Eduxamido/
│
├── Common/
├── DepartmentManagement/
├── ExamManagement/
├── InvigilatorManagement/
├── Invigilators_assignment/
├── SchemeManagement/
├── classroomManagement/
├── student_details/
│
├── adminlogin/
├── api/
├── css/
├── js/
├── images/
├── fonts/
├── homescreen/
│
├── pdf_works/
├── demo/
│
├── index.html
└── README.md
```

The project structure may change as development continues.

---

## 🚀 Getting Started

### Requirements

Before running Eduxamido locally, install:

* PHP
* Apache
* MySQL
* WAMP/XAMPP or another PHP development environment
* Git
* Web browser

### Clone the repository

```bash
git clone https://github.com/NoBugtechSolution/eduxamido.git
```

Move the project into your web server directory.

For WAMP:

```text
C:\wamp64\www\
```

Example:

```text
C:\wamp64\www\eduxamido
```

Start:

* Apache
* MySQL

Then open the project through your local server.

Example:

```text
http://localhost/eduxamido/
```

---

## 🔐 Database

The open-source repository should not contain production databases or private institutional data.

For local development, configure your own database and import the required database structure.

Do not commit:

```text
.env
*.sql
*.sqlite
*.sqlite3
/db/
```

if they contain private or environment-specific information.

---

## 🏆 Project Journey

Eduxamido was developed as a student innovation project by **NoBugTech Solution**.

The project was presented through innovation and idea competitions as the team worked toward developing a practical solution for examination and academic administration.

### Dreamvestor 2.0

Eduxamido was also presented at **Dreamvestor 2.0** at the state-level stage.

The team participated as a **finalist at the state-level stage**.

---

## 👥 Team

### NoBugTech Solution

**Eduxamido** was developed by the NoBugTech Solution team.

### Team Lead

* **[VISHAL V NAIR](https://github.com/vishalvnair124)** - Team Lead


### Team Members

* **[SETHU S NAIR](https://github.com/Sethusnair)**
* **[ABHIN M](https://github.com/mrabhin03)**
* **[ABI BINU](https://github.com/abibinu)**
* **[Harijith](https://github.com/Hari2020codes)**
* **[Anandhu V Nair](https://github.com/ananduvnair)**
* **[Robin P Danel](https://github.com/R0BIN-H00D)**
* **[Adarsh](https://github.com/AdarshAnil-04)**

---

## 🌱 Open Source

The new version of Eduxamido is being prepared as an **open-source project**.

The goal is to make the project available to developers, students, educational institutions, and contributors who are interested in improving examination and academic administration software.

Contributions can include:

* Bug fixes
* Seating algorithms
* Optimization
* UI improvements
* PHP development
* JavaScript development
* Database improvements
* PDF generation
* Testing
* Documentation
* Security improvements
* New examination-management features

---

## 🤝 Contributing

Contributions are welcome.

### 1. Fork the repository

Create your own fork of the project.

### 2. Create a feature branch

```bash
git checkout -b feature/your-feature
```

### 3. Make your changes

Implement and test your changes locally.

### 4. Commit your changes

```bash
git add .
git commit -m "Add your feature"
```

### 5. Push your branch

```bash
git push origin feature/your-feature
```

### 6. Create a Pull Request

Open a Pull Request describing:

* What was changed
* Why it was changed
* How it was tested

---

## 🔒 Privacy & Security

Eduxamido is intended to manage educational information, so privacy and security are important.

Never commit:

* Student personal information
* Student databases
* Passwords
* API keys
* Authentication tokens
* Production credentials
* Institution-specific confidential information

Always use test data when developing locally.

---

## 🛣️ Future Development

Future development may include:

* Improved seating optimization algorithms
* Advanced examination scheduling
* Automated invigilator scheduling
* Role-based access control
* Better student management
* REST APIs
* Modernized frontend
* Improved PDF generation
* Better reporting
* Multiple institution support
* Cloud deployment
* Improved database architecture
* Automated examination workflows

---

## 📜 License

This project is intended to be released as open source.

The applicable license will be provided in the repository through the `LICENSE` file.

---

## 📞 Project

**Project:** Eduxamido
**Team:** NoBugTech Solution
**Type:** College Examination & Academic Management System
**Technology:** PHP, MySQL, HTML, CSS, JavaScript

---

## ⭐ Support the Project

If you find Eduxamido useful or interesting:

* ⭐ Star the repository
* 🐛 Report bugs
* 💡 Suggest features
* 🔧 Submit improvements
* 🔀 Create pull requests

Every contribution helps improve the project.

---

### Eduxamido

**Automating examination management.
Simplifying academic administration.**
