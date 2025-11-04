show search_path;

create schema lbaw25122;

set search_path to lbaw25122;

DROP Table IF EXISTS "users" CASCADE;

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

DROP TABLE IF EXISTS "review_notification" CASCADE;

DROP TABLE IF EXISTS "booking_confirmation_notification" CASCADE;

DROP TABLE IF EXISTS "booking_cancelation_notification" CASCADE;

DROP TABLE IF EXISTS "booking_reminder_notification" CASCADE;

DROP TABLE IF EXISTS "media" CASCADE;

DROP TABLE IF EXISTS "favorited" CASCADE;
--not to reapeat any table

CREATE Table users (
    user_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_no VARCHAR(255) NOT NULL UNIQUE,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    is_banned BOOLEAN NOT NULL DEFAULT FALSE,
    password VARCHAR(255) NOT NULL, --will be hashed
    birth_date TIMESTAMP NOT NULL CHECK (
        birth_date <= NOW() - INTERVAL '18 years'
    ), --Must be 18+ years old
    profile_pic_url VARCHAR(512)
);

CREATE TABLE business_owner (
    business_owner_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES users (user_id) ON DELETE CASCADE
);

CREATE TABLE customer (
    customer_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES users (user_id) ON DELETE CASCADE
);

CREATE TABLE sport_type (
    sport_type_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(255) NOT NULL UNIQUE CHECK (
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
    space_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    owner_id INT NOT NULL REFERENCES business_owner (business_owner_id) ON DELETE CASCADE,
    sport_type_id INT NOT NULL REFERENCES sport_type (sport_type_id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    is_closed BOOLEAN NOT NULL DEFAULT FALSE,
    phone_no VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    num_favorites INTEGER DEFAULT 0,
    num_reviews INTEGER DEFAULT 0
);

CREATE TABLE admin (
    admin_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE ban (
    ban_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES users (user_id),
    admin_id INT NOT NULL REFERENCES admin (admin_id) ON DELETE CASCADE,
    motive VARCHAR(150) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE schedule (
    schedule_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(space_id),
    schedule_date DATE NOT NULL CHECK (schedule_date > NOW()), --must be a future DATE
    start_time TIMESTAMP NOT NULL CHECK (start_time > NOW()), --must be a future TIMESTAMP
    duration INT NOT NULL CHECK (duration > 0),
    max_capacity INT NOT NULL CHECK (max_capacity > 0)
);

CREATE TABLE booking (
    booking_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(space_id),
    customer_id INT NOT NULL REFERENCES customer (customer_id) ON DELETE CASCADE,
    schedule_id INT NOT NULL REFERENCES schedule (schedule_id) ON DELETE CASCADE,
    booking_created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    is_cancelled BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE review (
    review_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    customer_id INT REFERENCES customer (customer_id) ON DELETE SET NULL,
    booking_id INT REFERENCES booking (booking_id) ON DELETE SET NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW(),
    text VARCHAR(255) NOT NULL,
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
    response_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    owner_id INT REFERENCES business_owner (business_owner_id) ON DELETE SET NULL,
    review_id INT NOT NULL REFERENCES review (review_id) ON DELETE CASCADE,
    text VARCHAR(255) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE payment (
    payment_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    booking_id INT REFERENCES booking (booking_id) ON DELETE SET NULL,
    value FLOAT NOT NULL CHECK (value > 0),
    is_discounted BOOLEAN NOT NULL DEFAULT FALSE,
    is_accepted BOOLEAN NOT NULL DEFAULT FALSE,
    payment_provider_ref VARCHAR(150) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE discount (
    discount_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(space_id),
    percentage FLOAT NOT NULL CHECK (percentage BETWEEN 0 AND 100),
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL CHECK (end_date > start_date)
);

CREATE TABLE notification (
    notification_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES users (user_id) ON DELETE CASCADE,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW(),
    is_read BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE review_notification (
    review_notification_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (notification_id), -- Links to notification table
    review_id INT NOT NULL REFERENCES review (review_id) ON DELETE CASCADE -- Links to review table
);

CREATE TABLE booking_confirmation_notification (
    booking_confirmation_notification_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (notification_id), -- Links to notification table
    booking_id INT NOT NULL REFERENCES booking (booking_id) -- Links to booking table
);

CREATE TABLE booking_cancelation_notification (
    booking_cancelation_notification_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (notification_id), -- Links to notification table
    booking_id INT NOT NULL REFERENCES booking (booking_id) -- Links to booking table
);

CREATE TABLE booking_reminder_notification (
    booking_reminder_notification_id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (notification_id), -- Links to notification table
    booking_id INT NOT NULL REFERENCES booking (booking_id) -- Links to booking table
);

CREATE TABLE media (
    media_id INT GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(space_id),
    media_url VARCHAR(255) NOT NULL,
    is_cover BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (media_id, space_id)
);

CREATE TABLE favorited (
    space_id INT NOT NULL REFERENCES space(space_id),
    customer_id INT NOT NULL REFERENCES customer (customer_id),
    is_favorite BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (space_id, customer_id)
);