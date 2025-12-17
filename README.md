# Roadmechs (Cardano MVP)

A **web app** that helps drivers **quickly find nearby mechanics based on their current location** when their vehicle breaks down, with **Cardano (ADA) payment integration** for transparent and digital transactions.

This project is built as an **MVP**, focused on solving real roadside problems while demonstrating Cardano payment integration.

---

## 🚗 What Problem This App Solves

When a vehicle breaks down unexpectedly:

- Drivers don’t know which mechanic is closest
- Time is wasted searching for help
- Prices are often unclear
- Payments are mostly cash-based with no records

This app solves these issues by:

- Finding mechanics **based on the driver’s real-time location**
- Displaying service pricing clearly
- Showing **live ADA ↔ Naira exchange rates**
- Processing payments using **Cardano (ADA)**
- Keeping transaction records

---

## 🧠 How the App Works (Driver Flow)

1. Vehicle breaks down
2. Driver opens the app
3. App detects the **driver’s current location**
4. Nearby mechanics are displayed
5. Service price is shown in **Naira**
6. Driver calls a mechanic
7. During paymant app converts price to **ADA using real-time rates**
8. Driver completes payment
9. Transaction is logged

---

## 📍 Location-Based Mechanic Discovery

- Mechanics are fetched **based on the driver’s current location at that time**
- Ensures faster response and reduced waiting time
- Designed for real-world roadside scenarios
- Location is used **only for matching** during the session (MVP)

---

## 💰 Cardano (ADA) Payment Integration

The app integrates **Cardano (ADA) payments** to provide:

- Transparent digital transactions
- Real-time ADA conversion from Naira
- Logged and traceable payments

### 🔐 Driver Escrow Wallet (MVP)

- A **pre-funded Cardano test wallet** represents the driver
- Used for **dummy ADA transactions**
- Runs on the **Cardano pre-production network**

## ⚙️ Tech Stack

### Frontend
- HTML5  
- CSS3 
- JavaScript (Vanilla JS)  
- Responsive UI  

### Backend
- PHP  
- MySQL  
- Session-based authentication

### Location & Maps
- Browser Geolocation API  
- Leaflet for maps
- Geoapify for distance calculation

### Blockchain & Payments
- Cardano (ADA)  
- Pre-production network  
- Mock transaction logic  
- Test ADA from Cardano Faucet  

### Tools & Utilities
- AJAX  
- SweetAlert  
- MySQLi  

## 🧪 Current Status

- ADA payments are simulated  
- No smart contracts yet  
- No mainnet transactions  

## 🚀 Future Enhancements

- Real on-chain ADA payments  
- Smart contract escrow  
- Ratings & reviews  
- Push notifications  
- Admin dashboard  
- Mainnet deployment  

---

## 🛠️ Local Setup

1. Clone the repository  
2. Move the project to your server directory  
3. Import the database SQL file  
4. Update database credentials  
5. Start local server  
6. Open in browser  

---

## 📝 Prerequisites

Before running the app locally, make sure you have the following installed:

- **PHP** (version 7.4 or higher recommended)  
- **MySQL** (or MariaDB)  
- **Web Server** (XAMPP, MAMP, WAMP, or Apache/Nginx)  
- **Browser** with JavaScript enabled (for geolocation)  
---

## 🤝 Final Note

This app focuses on **speed, clarity, and trust**.

When drivers need help on the road, the system ensures they can **find help fast and pay transparently using Cardano**.

