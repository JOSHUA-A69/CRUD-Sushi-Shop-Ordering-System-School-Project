CREATE DATABASE Sushi_Shop;
USE Sushi_Shop;

CREATE TABLE Customers (
    CustomerID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(50) NOT NULL,
    MiddleInitial CHAR(1),
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    PhoneNumber VARCHAR(15) UNIQUE NOT NULL,
    CityTown VARCHAR(50),
    Street VARCHAR(100),
    HouseNumber INT,
    Password VARCHAR(255) NOT NULL  -- Use hashed passwords
);

CREATE TABLE Administrators (
    AdminID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,  -- Use hashed passwords
    Role VARCHAR(50) NOT NULL,
    ContactNumber VARCHAR(15) UNIQUE
);

CREATE TABLE Sushi_Item (
    ItemID INT PRIMARY KEY AUTO_INCREMENT,
    ItemName VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    AvailabilityStatus ENUM('Available', 'Unavailable') DEFAULT 'Available',
    Category VARCHAR(50),
    Ingredients TEXT
);

CREATE TABLE Orders (
    OrderID INT PRIMARY KEY AUTO_INCREMENT,
    CustomerID INT NOT NULL,
    ItemID INT NOT NULL,
    OrderStatus ENUM('Pending', 'Preparing', 'Completed') DEFAULT 'Pending',
    Quantity INT NOT NULL,
    TotalPrice DECIMAL(10, 2) NOT NULL,
    OrderTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    PaymentStatus ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
    Feedback TEXT,
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID) ON DELETE CASCADE,
    FOREIGN KEY (ItemID) REFERENCES Sushi_Item(ItemID) ON DELETE CASCADE
);



INSERT INTO sushi_item(ItemName, price, description, AvailabilityStatus, category, ingredients) VALUES 
    ('24pcs Premium Maki', 450.0, '24 pieces of assorted premium maki rolls', 1, 'Maki', 'Rice, Seaweed, Fish, Vegetables'),
    ('32pcs Premium Maki', 600.0, '32 pieces of assorted premium maki rolls', 1, 'Maki', 'Rice, Seaweed, Fish, Vegetables'),
    ('40pcs Premium Maki', 750.0, '40 pieces of assorted premium maki rolls', 1, 'Maki', 'Rice, Seaweed, Fish, Vegetables'),
    ('56pcs Premium Maki', 1050.0, '56 pieces of assorted premium maki rolls', 1, 'Maki', 'Rice, Seaweed, Fish, Vegetables'),
    ('64pcs Premium Maki', 1200.0, '64 pieces of assorted premium maki rolls', 1, 'Maki', 'Rice, Seaweed, Fish, Vegetables'),
    ('24pcs CM Platter', 350.0, '24 pieces of classic maki in a platter', 1, 'Platter', 'Rice, Seaweed, Cucumber, Crabstick'),
    ('32pcs CM Platter', 450.0, '32 pieces of classic maki in a platter', 1, 'Platter', 'Rice, Seaweed, Cucumber, Crabstick'),
    ('40pcs CM Platter', 550.0, '40 pieces of classic maki in a platter', 1, 'Platter', 'Rice, Seaweed, Cucumber, Crabstick'),
    ('60pcs CM Platter', 770.0, '60 pieces of classic maki in a platter', 1, 'Platter', 'Rice, Seaweed, Cucumber, Crabstick'),
    ('80pcs CM Platter', 1120.0, '80 pieces of classic maki in a platter', 1, 'Platter', 'Rice, Seaweed, Cucumber, Crabstick'),
    ('40pcs DM Platter', 600.0, '40 pieces of deluxe maki in a platter', 1, 'Platter', 'Rice, Seaweed, Fish, Avocado'),
    ('60pcs DM Platter', 820.0, '60 pieces of deluxe maki in a platter', 1, 'Platter', 'Rice, Seaweed, Fish, Avocado'),
    ('80pcs DM Platter', 1170.0, '80 pieces of deluxe maki in a platter', 1, 'Platter', 'Rice, Seaweed, Fish, Avocado'),
    ('100pcs DM Platter', 1450.0, '100 pieces of deluxe maki in a platter', 1, 'Platter', 'Rice, Seaweed, Fish, Avocado'),
    ('Solo pan', 130.0, 'Individual serving pan', 1, 'Pan', 'Rice, Mixed Toppings'),
    ('Small pan (2pax)', 350.0, 'Small pan suitable for 2 people', 1, 'Pan', 'Rice, Mixed Toppings'),
    ('Medium pan (4-6pax)', 750.0, 'Medium pan suitable for 4-6 people', 1, 'Pan', 'Rice, Mixed Toppings'),
    ('Large pan (8-10pax)', 1100.0, 'Large pan suitable for 8-10 people', 1, 'Pan', 'Rice, Mixed Toppings');


-- Administrators table
ALTER TABLE administrators
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD UNIQUE INDEX idx_email (email),
ADD UNIQUE INDEX idx_username (username);

-- Customers table
ALTER TABLE customers
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD UNIQUE INDEX idx_email (email);

/*Error
-- Orders table
ALTER TABLE orders
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD INDEX idx_customer (customerID),
ADD INDEX idx_status (status);

-- Sushi_Item table
ALTER TABLE sushi_item
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD INDEX idx_category (category),
ADD INDEX idx_status (availabilityStatus);*/

--Proper Naming
-- Orders table
ALTER TABLE orders
ADD COLUMN date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD INDEX idx_orders_customerID (customerID),
ADD INDEX idx_orders_status (OrderStatus);

-- Sushi_Item table
ALTER TABLE sushi_item
ADD COLUMN date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD INDEX idx_sushi_item_category (category),
ADD INDEX idx_sushi_item_availability (availabilityStatus);
