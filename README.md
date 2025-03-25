# Authentication System API

## Overview

The **Authentication System API** is a Laravel-based web application that provides a robust authentication system for managing user registration, login, and cache management. This API serves as a foundation for securing applications by implementing best practices in user authentication.

## Purpose

The main purposes of this project include:

- **User Authentication**: Implementing a secure system for user registration and login, ensuring safe access to resources.
- **Cache Management**: Enhancing performance by caching user sessions and authentication states.
- **Role-Based Access Control**: Allowing different levels of access based on user roles to enhance security.
- **Learning Resource**: Serving as a practical example for developers to understand and implement authentication in Laravel applications.

## Key Features

- **User Registration**: Allows new users to create accounts with secure password storage.
- **User Login**: Authenticates users and provides access tokens for subsequent requests.
- **Password Reset**: Enables users to reset their passwords securely.
- **Role Management**: Supports different user roles with varying access permissions.
- **RESTful API**: Built as a RESTful API, making it easy to integrate with front-end applications.

## Tech Stack

- **Framework**: Laravel
- **Language**: PHP
- **Database**: MySQL
- **API Format**: JSON

## URL

The application can be accessed at: [https://authentication-system-api-production.up.railway.app/](https://authentication-system-api-production.up.railway.app/)

## API Endpoints
- **User Registration: [POST]** /api/auth/register
- **User Login: [POST]** /api/auth/login
- **User Logout: [GET]**  /api/auth/logout
- **Verify Email: [POST]** /api/auth/verify-email
- **Resend Code: [POST]** /api/auth/resend-code
- **Forget Password: [POST]** /api/auth/forget-password
- **Reset Password: [POST]** /api/auth/reset-password
- **Enable Two-Factory Authenticated: [POST]** /api/auth/verify-2FA
- **Refresh Token: [POST]: /api/auth/refresh-token**
