# E-commerce Cart Application

A modern, responsive e-commerce shopping cart application built with Laravel, Vue.js, and Inertia.js. This project demonstrates a seamless Single Page Application (SPA) experience using server-side routing and state management provided by Inertia.

## Features

- **Product Management**: Browse products with images, descriptions, prices, and real-time stock availability.
- **Shopping Cart**: 
  - Add items to cart with instant feedback.
  - Update quantities and remove items.
  - Real-time cart count indicator in the navigation bar.
- **Checkout System**: Process orders and manage stock deduction.
- **User Authentication**: Secure login and registration using Laravel Breeze.
- **Notifications**: 
  - Flash messages for success/error feedback.
  - Low stock email notifications when product inventory dips below a threshold.
- **Responsive Design**: Fully responsive UI built with Tailwind CSS.

## Tech Stack

- **Backend**: [Laravel 10](https://laravel.com)
- **Frontend**: [Vue.js 3](https://vuejs.org)
- **Glue**: [Inertia.js](https://inertiajs.com) (Seamless server-side routing for SPAs)
- **Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Database**: MySQL

## Prerequisites

Ensure you have the following installed on your local machine:
- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ecommerce-cart
   ```

2. **Install Backend Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   Copy the example environment file and configure your database settings.
   ```bash
   cp .env.example .env
   ```
   Update the `DB_*` variables in `.env` to match your MySQL configuration.

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Database Migration & Seeding**
   Run the migrations to create the necessary tables and seed the database with sample products.
   ```bash
   php artisan migrate --seed
   ```

## Running the Application

1. **Start the Vite development server** (for hot module replacement)
   ```bash
   npm run dev
   ```

2. **Start the Laravel development server** (in a new terminal)
   ```bash
   php artisan serve
   ```

3. **Access the application**
   Open your browser and visit [http://localhost:8000](http://localhost:8000).

## Project Structure

- **`app/Http/Controllers`**: Contains `ProductController` and `CartController` handling business logic.
- **`resources/js/Pages`**: Vue components representing application pages (Products, Cart, Auth).
- **`resources/js/Layouts`**: Main application layout (`AuthenticatedLayout.vue`).
- **`routes/web.php`**: Application routes defined for Inertia.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
