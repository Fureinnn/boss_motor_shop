# Boss's Motor Shop

This is a web application for Boss's Motor Shop, offering motorcycle services and product sales.

## Setup Instructions

To set up and run this application, follow these steps:

**1. Prerequisites:**

*   **Web Server:** Apache or Nginx
*   **PHP:** Version 7.4 or higher (with `mysqli` and `pdo` extensions enabled)
*   **Database:** MySQL or MariaDB
*   **Composer** (Optional, not strictly required for this project as dependencies are not managed via Composer)

**2. Database Setup:**

*   Create a new MySQL database named `boss_motor_shop`.
*   Import the provided database schema and initial data from `boss_motor_shop.sql` into your newly created database. You can do this using a tool like phpMyAdmin or the MySQL command line:
    ```bash
    mysql -u your_username -p boss_motor_shop < boss_motor_shop.sql
    ```
    (Replace `your_username` with your MySQL username and enter your password when prompted.)

**3. Application Files:**

*   Place all the project files (the entire `BOSS_MOTOR_SHOP` directory) into your web server's document root (e.g., `htdocs` for Apache, `www` for Nginx).

**4. Configure Database Connection:**

*   Open the `includes/config.php` file.
*   Update the database connection details (`$host`, `$dbname`, `$username`, `$password`) to match your MySQL server configuration:

    ```php
    // Database configuration
    $host = 'localhost'; // Your database host
    $dbname = 'boss_motor_shop'; // The database name you created
    $username = 'root'; // Your database username
    $password = ''; // Your database password
    ```

**5. Access the Application:**

*   Open your web browser and navigate to the URL where you placed the project files (e.g., `http://localhost/BOSS_MOTOR_SHOP/` or `http://your_domain/BOSS_MOTOR_SHOP/`).

## Login Information

**Admin Account:(nothing yet)**

*   **Username:** ``
*   **Password:** `` 

**Regular User Account:**
*![Uploading image.png…]()


*   You can register a new user account via the `register.php` page.
*   Alternatively, if the `boss_motor_shop.sql` file contains sample user data, you might find existing user credentials there.

## File Descriptions

Here's a brief overview of what each file in the `BOSS_MOTOR_SHOP` directory does:

*   **`about.php`**:
    *   Displays information about Boss's Motor Shop.
    *   Includes a contact form for users to send messages.
    *   Handles form submission, sanitization, and validation for contact messages.

*   **`admin/`**:
    *   **`admin/dashboard.php`**: The main administrative dashboard. Provides an overview of total products, orders, and users, along with a list of recent orders. Accessible only by administrators.
    *   **`admin/order.php`**: Manages individual order details and allows administrators to update order statuses. Also displays a list of all orders if no specific order is being viewed.
    *   **`admin/products.php`**: Allows administrators to manage products (add, edit, delete/deactivate). Includes a modal form for product creation and modification.

*   **`assets/`**:
    *   **`assets/css/`**: Contains CSS stylesheets for the application.
        *   **`assets/css/profile.css`**: Specific styles for the user profile page.
        *   **`assets/css/style.css`**: General styles applied across the entire website.
    *   **`assets/images/`**: Stores image assets.
        *   **`assets/images/logo.png`**: The logo image for the motor shop.

*   **`boss_motor_shop.sql`**:
    *   The SQL dump file containing the database schema and initial data for the application. This file is crucial for setting up the database.

*   **`cart.php`**:
    *   Manages the user's shopping cart.
    *   Handles adding, removing, and updating product quantities in the cart.
    *   Displays the current items in the cart and the order summary.

*   **`checkout.php`**:
    *   Handles the checkout process for placing an order.
    *   Collects user details (autofilled if logged in) and shipping information.
    *   Allows selection of payment methods (Credit Card, PayPal, Cash on Delivery).
    *   Processes order creation and clears the cart upon successful placement.

*   **`includes/`**:
    *   **`includes/auth.php`**: Contains functions related to user authentication and authorization, such as `isLoggedIn()`, `isAdmin()`, `requireLogin()`, and `requireAdmin()`.
    *   **`includes/config.php`**: Central configuration file for database connection settings. Also includes helper functions for executing database queries.
    *   **`includes/footer.php`**: Contains the HTML structure for the website's footer, including quick links, contact information, business hours, and social media links.
    *   **`includes/functions.php`**: A collection of utility functions used throughout the application, including `sanitize()` for input cleaning, `formatPrice()` for currency formatting, `getCategoryName()`, and authentication helpers.
    *   **`includes/header.php`**: Contains the HTML structure for the website's header, including the navigation bar, logo, and dynamic links based on user login status (e.g., Cart, Profile, Admin Dashboard, Login/Register).

*   **`index.php`**:
    *   The main landing page of the website.
    *   Features a hero section, services overview, featured products, and customer testimonials.

*   **`login.php`**:
    *   Handles user login.
    *   Authenticates users against the database and sets session variables upon successful login.

*   **`logout.php`**:
    *   Destroys the user's session and redirects them to the login page, effectively logging them out.

*   **`productinfo.php`**:
    *   Displays detailed information for a specific product, including description, specifications, brand, model, and warranty details.

*   **`products.php`**:
    *   Displays a list of all available products.
    *   Allows users to filter products by category and search by name or SKU.
    *   Provides options to add products to the cart (if logged in).

*   **`profile.php`**:
    *   Displays the logged-in user's personal, address, and account information.
    *   Includes quick action buttons for common user tasks.
