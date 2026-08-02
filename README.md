# Vivium AI Assistant

An AI-powered virtual assistant prototype built as a technical assessment for Vivium Clinics. The assistant answers questions using a predefined knowledge base, supports multilingual conversations, guides users through appointment booking, and stores appointment requests for administrative review.

---

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Bootstrap 5
- JavaScript
- OpenRouter AI API

---

## Features

- AI-powered chatbot
- Knowledge-based responses
- English & Arabic support
- Conversation history
- AI-guided appointment booking
- Appointment storage in MySQL
- Admin dashboard
- Prompt engineering
- Error handling and API fallback

---

## Installation

```bash
git clone <repository-url>

cd vivium-ai-assistant

composer install

cp .env.example .env

php artisan key:generate

npm install

npm run build

php artisan migrate

php artisan serve
```

---

## Configuration

Configure the following environment variables in your `.env` file:

```env
APP_NAME=Vivium
APP_ENV=local
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

OPENROUTER_API_KEY=
```

---

## Project Highlights

- Uses a custom system prompt to control AI behavior.
- Uses a structured knowledge base (`config/knowledge.php`) to provide grounded responses.
- Maintains conversation history for contextual conversations.
- Supports structured JSON responses for completed appointment bookings.
- Prevents hallucinations by restricting responses to the provided knowledge base.

---

## Disclaimer

This project is a prototype created for a technical assessment and is intended to demonstrate AI integration, prompt engineering, conversation management, and appointment booking workflows. It is not intended for production use without additional security, validation, CRM integration, and healthcare compliance enhancements.