# 🎵 Concert Booking API

This project is a RESTful API designed for a multi-platform Concert Booking System. 
It supports functionality for users to browse concerts, book tickets, manage favourites, 
and get artist information through Spotify integration. The system is designed to support 
a web frontend, a mobile app, and an admin backend.

---

## 🚀 Features

- User registration and login
- Concert listing and search
- Ticket booking system
- Favourites management
- Spotify API integration (artist data and top tracks)
- Structured JSON responses and appropriate HTTP status codes
- Postman collections for testing
- MkDocs-powered documentation

---

## 🛠️ Technologies Used

- PHP
- MySQL
- Spotify Web API
- Postman
- MkDocs

---

## 📁 Project Structure

Concert-Booking/
├── API/
│   ├── concerts/
│   ├── bookings/
│   ├── users/
│   ├── favourites/
│   ├── Spotify
│   ├── Core/
│   ├── includes/
│   └── Exports/
├── my-project/
│   ├── mkdocs.yml
│   └── docs/

---

## 🔐 Authentication

Spotify's OAuth 2.0 is used to authorize API requests.
- API uses access tokens in the headers.
- Requires `client_id` and `client_secret`.
