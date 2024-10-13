# Gizzmos E-commerce

Gizzmos E-commerce is a web application designed to showcase and sell various products. This project not only provided an opportunity to build a functional e-commerce platform but also helped deepen my understanding of PHP, best data protection practices, and the implementation of CSRF tokens.

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage and Security Practices](#usage-and-security-practices)

## Features

- User registration and authentication
- Product catalog with search functionality
- Shopping cart management
- Secure payment processing
- Admin panel for product management

## Technologies Used

- PHP
- MySQL
- HTML/CSS
- JavaScript
- Apache

## Installation

To install and run the Gizzmos E-commerce application locally, follow these steps:

## Installation

To install and run the Gizzmos E-commerce application locally, follow these steps:

1. Clone the repository:
   `git clone https://github.com/Griffinov22/Gizzmos-E-commerce.git`

2. Navigate to the project directory:
   `cd Gizzmos-E-commerce`

3. Set up a local server using Apache on your Raspberry Pi.

4. Configure your environment settings (e.g., database connection) in the `config.php` file.

## Usage and Security Practices

After completing the installation, access the application through your web browser by visiting `http://localhost` or your Raspberry Pi's IP address. 

This project emphasizes data protection through various practices:

- **Input Validation**: All user inputs are validated to prevent SQL injection attacks.
- **Password Hashing**: User passwords are hashed before being stored in the database.
- **Secure Sessions**: Secure session management is implemented to protect user sessions.

Cross-Site Request Forgery (CSRF) tokens are implemented in the application to enhance security during form submissions. Each form includes a unique CSRF token that must match the token stored in the user's session, preventing unauthorized requests.


