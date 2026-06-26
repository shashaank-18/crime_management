# 👮 Crime Record Management System (CRMS)

A web-based **Crime Record Management System (CRMS)** designed to streamline the logging, tracking, and management of police records, officer assignments, FIR files, person roles (victims/suspects), and evidence storage. Built using **PHP** and **MySQL**, it provides an intuitive administrative dashboard with real-time statistical breakdowns.

---

## 🚀 Features

### 🔐 Authentication & Security
- Secure user signup and login system.
- Session-based access control (automatically restricts unauthorized users from viewing the dashboard).

### 📊 Real-Time Dashboard Statistics
- Displays total case count.
- Tracks active case status breakdown (`Open`, `Investigating`, `Closed`).
- Real-time statistics counters for Officers, Citizens/Persons, FIRs, and Evidence files.

### 📁 Case & Investigation Management
- **Insert Case**: Easily add new crime files with details such as crime type, date, location, status, and assigned police station.
- **View / Update / Delete**: Tabular interface to inspect all cases, search case records, edit case progress, and delete obsolete logs.
- **First Information Report (FIR)**: File and link FIR reports to specific cases.
- **Evidence Management**: Register evidence items (digital, physical, biological) and associate them directly with cases.

### 👥 People & Station Administration
- **Officer Directory**: Manage officer rosters, assign ranks, and link officers to specific police stations.
- **Person Profiles**: Register individuals involved in investigations (assigning roles like `Victim`, `Suspect`, or `Witness`).
- **Station Directory**: Record police stations, addresses, and contact numbers.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (using Object-Oriented MySQLi API)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, Vanilla CSS3 (Custom gradients, responsive card layouts, clean styling)
- **Server**: Apache (compatible with XAMPP, WAMP, or LAMP stacks)

---

## 🗄️ Database Design (Relational Schema)

The database `crime_db` operates on a structured relational model consisting of the following key tables:

*   **`users`**: Manages dashboard operators (operators/administrators).
*   **`PoliceStation`**: Stores branch station details.
*   **`PoliceOfficer`**: Profile information for all law enforcement personnel.
*   **`CrimeCase`**: Records specific crime events, dates, locations, and current status.
*   **`CaseOfficer`**: A junction table linking cases to the officers assigned, defining their roles in the investigation.
*   **`Person`**: Registry for civilians, storing metadata and their default system role.
*   **`CasePerson`**: Links civilians to cases under specific categories (e.g., Victim, Suspect, Witness).
*   **`FIR`**: Documents First Information Reports linked to cases.
*   **`Evidence`**: Tracks all evidence items and maps them back to active cases.

---

## 📁 File Structure

```text
├── db.php                 # Database connection & automated schema setup
├── index.php              # Login and landing portal
├── signup.php             # User registration
├── logout.php             # Session teardown
├── dashboard.php          # Main administrative hub & stats
├── insert_case.php        # Form for logging new crimes
├── view_cases.php         # Central case logs table (read/update/delete trigger)
├── update_case.php        # Case status modification form
├── delete_case.php        # Case removal handler
├── search_case.php        # Case search engine
├── manage_fir.php         # FIR creation & lookup interface
├── manage_evidence.php    # Evidence tracker module
├── manage_officer.php     # Police Officer portal
├── manage_person.php      # Victim / Suspect directory portal
├── manage_station.php     # Police Station directory portal
└── style.css              # Main stylesheets (Gradients, layout rules)
```

---

## 💻 Installation & Setup Guide

Follow these steps to set up the project locally on your machine:

### Prerequisites
Make sure you have an Apache server stack running (like [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/en/)).

### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/crime_management.git
   ```

2. **Move to Server Root**
   Move the project folder into your server's public directory (e.g., `C:/xampp/htdocs/` for XAMPP).

3. **Configure Database Connection**
   - Ensure your MySQL database server is running.
   - Open `db.php` and configure the connection details if needed:
     ```php
     $conn = new mysqli("localhost", "root", "YOUR_PASSWORD", "crime_db");
     ```
   - *Note: The application will automatically create the database `crime_db` (if configured) and all required tables upon first launch!*

4. **Run the Application**
   - Open your browser and navigate to:
     ```text
     http://localhost/crime_project/
     ```
   - Register a new account on the Signup page, log in, and begin managing cases!

---

## 🤝 Contributing
Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/YOUR_USERNAME/crime_management/issues).
