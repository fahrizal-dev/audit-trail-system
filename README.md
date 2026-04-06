# Audit Trail System

## Overview
This is a web-based audit trail system designed to record and monitor user activities within an application.

It tracks important actions such as login events, data changes, and API access, helping maintain system transparency and data integrity.

## Features
- User authentication (login system)
- Activity logging (create, update, delete)
- API request logging
- Dashboard for monitoring user activities

## Tech Stack
- PHP
- MySQL
- JavaScript

## How It Works
Each user action is recorded into a log table with details such as:
- user_id
- action performed
- timestamp

This enables administrators to trace system activity and detect unusual behavior.

## Setup
1. Clone this repository  
2. Import the database (`database.sql`)  
3. Configure database connection  
4. Run on localhost (XAMPP / Laragon)

## Preview
<img width="690" height="612" alt="image" src="https://github.com/user-attachments/assets/9daf67d0-fbec-429b-b49b-a454ec65fb06" />
<img width="1181" height="602" alt="image" src="https://github.com/user-attachments/assets/bc9e7c1d-6767-481e-b199-b816287ba2cc" />
<img width="1361" height="1285" alt="image" src="https://github.com/user-attachments/assets/56057c1e-61ae-46d1-b045-dac92b9165eb" />
<img width="1365" height="1885" alt="image" src="https://github.com/user-attachments/assets/841a40f5-ede7-4f70-ab1e-0868b6af64dc" />

## Demo Account
- Username: admin  
- Password: Admin123.

## Notes
This project focuses on implementing audit logging and understanding how activity tracking works in real-world applications.

## Author
Fahrizal Adji
