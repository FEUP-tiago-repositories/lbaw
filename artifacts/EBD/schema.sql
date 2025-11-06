show search_path;

DROP SCHEMA IF EXISTS lbaw25122 CASCADE;

create schema lbaw25122;

set search_path to lbaw25122;

DROP Table IF EXISTS "user" CASCADE;

DROP TABLE IF EXISTS "business_owner" CASCADE;

DROP TABLE IF EXISTS "customer" CASCADE;

DROP TABLE IF EXISTS "sport_type" CASCADE;

DROP TABLE IF EXISTS "space" CASCADE;

DROP TABLE IF EXISTS "admin" CASCADE;

DROP TABLE IF EXISTS "ban" CASCADE;

DROP TABLE IF EXISTS "schedule" CASCADE;

DROP TABLE IF EXISTS "booking" CASCADE;

DROP TABLE IF EXISTS "review" CASCADE;

DROP TABLE IF EXISTS "response" CASCADE;

DROP TABLE IF EXISTS "payment" CASCADE;

DROP TABLE IF EXISTS "discount" CASCADE;

DROP TABLE IF EXISTS "notification" CASCADE;

DROP TABLE IF EXISTS "response_notification" CASCADE;

DROP TABLE IF EXISTS "review_notification" CASCADE;

DROP TABLE IF EXISTS "booking_confirmation_notification" CASCADE;

DROP TABLE IF EXISTS "booking_cancellation_notification" CASCADE;

DROP TABLE IF EXISTS "booking_reminder_notification" CASCADE;

DROP TABLE IF EXISTS "media" CASCADE;

DROP TABLE IF EXISTS "favorited" CASCADE;
--not to repeat any table

CREATE Table "user" (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone_no VARCHAR(15) NOT NULL UNIQUE,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    is_banned BOOLEAN NOT NULL DEFAULT FALSE,
    password VARCHAR(100) NOT NULL, --will be hashed
    birth_date DATE NOT NULL CHECK (
        birth_date <= NOW() - INTERVAL '18 years'
    ), --Must be 18+ years old
    profile_pic_url VARCHAR(255)
);

CREATE TABLE business_owner (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id) ON DELETE CASCADE
);

CREATE TABLE customer (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id) ON DELETE CASCADE
);

CREATE TABLE sport_type (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(50) NOT NULL UNIQUE CHECK (
        name IN (
            'Badminton',
            'Basketball',
            'Biking',
            'Climbing',
            'Football',
            'Golf',
            'Gym',
            'Handball',
            'Hockey',
            'Martial Arts',
            'Padel',
            'Rugby',
            'Running',
            'Skating',
            'Swimming',
            'Tennis',
            'Volleyball',
            'Other'
        )
    )
);

CREATE TABLE space(
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    owner_id INT NOT NULL REFERENCES business_owner (id) ON DELETE RESTRICT,
    sport_type_id INT NOT NULL REFERENCES sport_type (id) ON DELETE RESTRICT,
    title VARCHAR(100) NOT NULL,
    address VARCHAR(150) NOT NULL,
    description VARCHAR(300) NOT NULL,
    is_closed BOOLEAN NOT NULL DEFAULT FALSE,
    phone_no VARCHAR(15) NOT NULL,
    email VARCHAR(150) NOT NULL,
    num_favorites INTEGER DEFAULT 0,
    num_reviews INTEGER DEFAULT 0
);

CREATE TABLE admin (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE ban (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id),
    admin_id INT NOT NULL REFERENCES admin (id) ON DELETE CASCADE,
    motive VARCHAR(200) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE schedule (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(id),
    start_time TIMESTAMP NOT NULL CHECK (start_time > NOW()), --must be a future TIMESTAMP
    duration INT NOT NULL CHECK (duration > 0),
    max_capacity INT NOT NULL CHECK (max_capacity > 0)
);

CREATE TABLE booking (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    customer_id INT NOT NULL REFERENCES customer (id) ON DELETE CASCADE,
    schedule_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE,
    booking_created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    is_cancelled BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE review (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    customer_id INT REFERENCES customer (id) ON DELETE SET NULL,
    booking_id INT REFERENCES booking (id) ON DELETE SET NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW(),
    text VARCHAR(500) NOT NULL,
    environment_rating SMALLINT NOT NULL CHECK (
        environment_rating BETWEEN 1 AND 5
    ),
    equipment_rating SMALLINT NOT NULL CHECK (
        equipment_rating BETWEEN 1 AND 5
    ),
    service_rating SMALLINT NOT NULL CHECK (
        service_rating BETWEEN 1 AND 5
    )
);

CREATE TABLE response (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    owner_id INT REFERENCES business_owner (id) ON DELETE SET NULL,
    review_id INT NOT NULL REFERENCES review (id) ON DELETE CASCADE,
    text VARCHAR(300) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE payment (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    booking_id INT REFERENCES booking (id) ON DELETE SET NULL,
    value FLOAT NOT NULL CHECK (value > 0),
    is_discounted BOOLEAN NOT NULL DEFAULT FALSE,
    is_accepted BOOLEAN NOT NULL DEFAULT FALSE,
    payment_provider_ref VARCHAR(100) NOT NULL CHECK (
        payment_provider_ref IN (
            'Credit/Debit Card',
            'MB Way',
            'Paypal'
        )
    ),
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE discount (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(id),
    percentage FLOAT NOT NULL CHECK (percentage BETWEEN 0 AND 100),
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL CHECK (end_date > start_date)
);

CREATE TABLE notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id) ON DELETE CASCADE,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW(),
    is_read BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE response_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE, -- Links to notification table
    response_id INT NOT NULL REFERENCES response (id) ON DELETE CASCADE -- Links to response table
);

CREATE TABLE review_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE, -- Links to notification table
    review_id INT NOT NULL REFERENCES review (id) ON DELETE CASCADE -- Links to review table
);

CREATE TABLE booking_confirmation_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE, -- Links to notification table
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE -- Links to booking table
);

CREATE TABLE booking_cancellation_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE, -- Links to notification table
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE -- Links to booking table
);

CREATE TABLE booking_reminder_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE, -- Links to notification table
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE -- Links to booking table
);

CREATE TABLE media (
    id INT GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    media_url VARCHAR(255) NOT NULL,
    is_cover BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id, space_id)
);

CREATE TABLE favorited (
    space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    customer_id INT NOT NULL REFERENCES customer (id) ON DELETE CASCADE,
    is_favorite BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (space_id, customer_id)
);