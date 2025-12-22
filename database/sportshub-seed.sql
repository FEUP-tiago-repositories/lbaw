-- =========================
-- SCHEMA CONFIGURATION
-- =========================

--show search_path;

DO $do$
DECLARE
s text := COALESCE(current_setting('app.schema', true), 'lbaw25122');
BEGIN
EXECUTE format('DROP SCHEMA IF EXISTS %I CASCADE', s);
EXECUTE format('CREATE SCHEMA IF NOT EXISTS %I', s);
PERFORM set_config('search_path', format('%I, public', s), false);
END
$do$ LANGUAGE plpgsql;

-- =========================
-- TABLE CREATION
-- =========================

CREATE Table "user" (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    first_name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone_no VARCHAR(15) NOT NULL UNIQUE,
    is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
    is_banned BOOLEAN NOT NULL DEFAULT FALSE,
    password VARCHAR(100), --will be hashed and maybe is NULL for oauth
    birth_date DATE NOT NULL CHECK (
        birth_date <= NOW() - INTERVAL '18 years'
    ), --Must be 18+ years old
    profile_pic_url VARCHAR(255),
    google_id VARCHAR(255) DEFAULT NULL UNIQUE,
    facebook_id VARCHAR(255) DEFAULT NULL UNIQUE
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
    latitude FLOAT,
    longitude FLOAT,
    description VARCHAR(300) NOT NULL,
    is_closed BOOLEAN NOT NULL DEFAULT FALSE,
    phone_no VARCHAR(15) NOT NULL,
    email VARCHAR(150) NOT NULL,
    opening_time TIME NOT NULL DEFAULT '08:00:00',
    closing_time TIME NOT NULL DEFAULT '22:00:00',
    duration INT NOT NULL DEFAULT 30 CHECK (duration > 0),
    num_favorites INTEGER DEFAULT 0,
    num_reviews INTEGER DEFAULT 0,
    current_environment_rating INTEGER NOT NULL DEFAULT 0,
    current_equipment_rating INTEGER NOT NULL DEFAULT 0,
    current_service_rating INTEGER NOT NULL DEFAULT 0,
    current_total_rating INTEGER NOT NULL DEFAULT 0
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
    space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    start_time TIMESTAMP NOT NULL,
    max_capacity INT NOT NULL CHECK (max_capacity >= 0)
);

CREATE TABLE payment (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
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

CREATE TABLE booking (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    customer_id INT NOT NULL REFERENCES customer (id) ON DELETE CASCADE,
    schedule_id INT NOT NULL REFERENCES schedule (id) ON DELETE CASCADE,
    booking_created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    is_cancelled BOOLEAN NOT NULL DEFAULT FALSE,
    number_of_persons INT NOT NULL DEFAULT 1,
    total_duration INT NOT NULL DEFAULT 30,
    payment_id INT REFERENCES payment (id) ON DELETE SET NULL
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

CREATE TABLE discount (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    space_id INT NOT NULL REFERENCES space(id) ON DELETE CASCADE,
    code VARCHAR(50) UNIQUE DEFAULT NULL,
    percentage FLOAT NOT NULL CHECK (percentage BETWEEN 0 AND 100),
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL CHECK (end_date > start_date)
);

CREATE TABLE notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id) ON DELETE CASCADE,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW(),
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    content TEXT NOT NULL
);

CREATE TABLE response_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE,
    response_id INT NOT NULL REFERENCES response (id) ON DELETE CASCADE
);

CREATE TABLE review_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE,
    review_id INT NOT NULL REFERENCES review (id) ON DELETE CASCADE
);

CREATE TABLE booking_confirmation_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE,
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE
);

CREATE TABLE booking_cancellation_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE,
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE
);

CREATE TABLE booking_reminder_notification (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE,
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE
);

CREATE TABLE new_reservation_notifications (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    notification_id INT NOT NULL REFERENCES notification (id) ON DELETE CASCADE,
    booking_id INT NOT NULL REFERENCES booking (id) ON DELETE CASCADE
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
    PRIMARY KEY (space_id, customer_id)
);

CREATE TABLE password_resets (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id) ON DELETE CASCADE,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    expires_at TIMESTAMP NOT NULL
);

CREATE TABLE ban_appeal (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user" (id),
    ban_id INT NOT NULL REFERENCES ban (id) ON DELETE CASCADE,
    appeal VARCHAR(200) NOT NULL,
    time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
);

-- =======================================================================================
-- DATA POPULATION
-- =======================================================================================

INSERT INTO
    "user" (
        first_name,
        surname,
        user_name,
        email,
        phone_no,
        is_deleted,
        is_banned,
        password,
        birth_date,
        profile_pic_url
    )
VALUES (
        'Cade',
        'Le',
        'cade_le',
        'dolor.nulla@protonmail.couk',
        '954529117',
        FALSE,
        FALSE,
        'PDF81OMZ4CZ',
        '1960-01-23',
        'images/profile.jpg'
    ),
    (
        'Miriam',
        'Hoover',
        'miriam_hoover',
        'nibh.quisque.nonummy@hotmail.edu',
        '962922371',
        FALSE,
        FALSE,
        'NXV25EBT2MC',
        '1989-04-05',
        'images/profile.jpg'
    ),
    (
        'Lysandra',
        'Wise',
        'lysandra_wise',
        'ullamcorper@aol.org',
        '931042084',
        FALSE,
        TRUE,
        'HIC92UVB1RM',
        '1963-08-24',
        'images/profile.jpg'
    ),
    (
        'Alfreda',
        'Curtis',
        'alfreda_curtis',
        'sed@aol.net',
        '972456831',
        FALSE,
        TRUE,
        'QIR00JTE7MK',
        '2001-12-10',
        'images/profile.jpg'
    ),
    (
        'Constance',
        'Yates',
        'constance_yates',
        'interdum@protonmail.ca',
        '989628425',
        TRUE,
        TRUE,
        'YHP48KYD7QH',
        '1965-05-27',
        'images/profile.jpg'
    ),
    (
        'Nita',
        'Jennings',
        'nita_jennings',
        'auctor.ullamcorper.nisl@icloud.edu',
        '973066023',
        FALSE,
        FALSE,
        'XJC87JZZ1PJ',
        '1986-02-05',
        'images/profile.jpg'
    ),
    (
        'Lucy',
        'Schroeder',
        'lucy_schroeder',
        'quisque.tincidunt@hotmail.couk',
        '979822651',
        FALSE,
        FALSE,
        'URS83TKB1JK',
        '1990-12-28',
        'images/profile.jpg'
    ),
    (
        'Seth',
        'David',
        'seth_david',
        'mauris.magna@protonmail.ca',
        '963088105',
        TRUE,
        FALSE,
        'RBP99EEH9HY',
        '1971-09-09',
        'images/profile.jpg'
    ),
    (
        'Felicia',
        'Hubbard',
        'felicia_hubbard',
        'et.magnis@outlook.edu',
        '903518505',
        FALSE,
        FALSE,
        'YNY29HYN4EF',
        '1953-08-31',
        'images/profile.jpg'
    ),
    (
        'Kamal',
        'Burnett',
        'kamal_burnett',
        'convallis@yahoo.couk',
        '930572268',
        FALSE,
        FALSE,
        'JQD86QMT1PV',
        '1979-12-24',
        'images/profile.jpg'
    ),
    (
        'Murphy',
        'Cunningham',
        'murphy_cunningham',
        'mauris.sapien.cursus@hotmail.org',
        '975166436',
        FALSE,
        TRUE,
        'BDK84KFB0BM',
        '2006-10-12',
        'images/profile.jpg'
    ),
    (
        'Peter',
        'Haynes',
        'peter_haynes',
        'tortor.at.risus@protonmail.edu',
        '912031257',
        FALSE,
        FALSE,
        'TXL28IPE7HU',
        '1952-12-19',
        'images/profile.jpg'
    ),
    (
        'Xaviera',
        'Williams',
        'xaviera_williams',
        'eget@google.net',
        '986863406',
        TRUE,
        TRUE,
        'JGX59DHI7EX',
        '1975-02-26',
        'images/profile.jpg'
    ),
    (
        'Tatiana',
        'Trujillo',
        'tatiana_trujillo',
        'curabitur@aol.ca',
        '931704357',
        FALSE,
        FALSE,
        'PPX44LRY9TW',
        '1987-06-28',
        'images/profile.jpg'
    ),
    (
        'Gage',
        'Ramos',
        'gage_ramos',
        'congue@outlook.org',
        '995219633',
        TRUE,
        FALSE,
        'EKV03QXK6BT',
        '1961-03-18',
        'images/profile.jpg'
    ),
    (
        'Cynthia',
        'Barnett',
        'cynthia_barnett',
        'dictum.sapien.aenean@protonmail.edu',
        '998339655',
        TRUE,
        FALSE,
        'RBR82JEP2WS',
        '1998-06-24',
        'images/profile.jpg'
    ),
    (
        'Imani',
        'Wilkinson',
        'imani_wilkinson',
        'lorem.eu@yahoo.edu',
        '940636822',
        TRUE,
        TRUE,
        'CLM27NIQ8IO',
        '1959-07-29',
        'images/profile.jpg'
    ),
    (
        'Ebony',
        'Hill',
        'ebony_hill',
        'nulla@outlook.ca',
        '973648036',
        TRUE,
        FALSE,
        'AFA68UOX0FY',
        '1973-05-12',
        'images/profile.jpg'
    ),
    (
        'Jasper',
        'Briggs',
        'jasper_briggs',
        'sem.ut@protonmail.net',
        '917847326',
        TRUE,
        FALSE,
        'TUP96PDI4DM',
        '1966-08-28',
        'images/profile.jpg'
    ),
    (
        'Ulric',
        'Vasquez',
        'ulric_vasquez',
        'amet@aol.ca',
        '923223688',
        FALSE,
        TRUE,
        'TDN80GTU5DJ',
        '2002-02-09',
        'images/profile.jpg'
    );

INSERT INTO
    business_owner (user_id)
VALUES (1),
    (6),
    (7),
    (11),
    (14);

INSERT INTO
    customer (user_id)
VALUES (2),
    (3),
    (4),
    (5),
    (8),
    (9),
    (10),
    (12),
    (13),
    (15),
    (16),
    (17),
    (18),
    (19),
    (20);

INSERT INTO
    sport_type (name)
VALUES ('Badminton'),
    ('Basketball'),
    ('Biking'),
    ('Climbing'),
    ('Football'),
    ('Golf'),
    ('Gym'),
    ('Handball'),
    ('Hockey'),
    ('Martial Arts'),
    ('Padel'),
    ('Rugby'),
    ('Running'),
    ('Skating'),
    ('Swimming'),
    ('Tennis'),
    ('Volleyball'),
    ('Other');

INSERT INTO
    space(
        owner_id,
        sport_type_id,
        title,
        address,
        latitude,
        longitude,
        description,
        is_closed,
        phone_no,
        email,
        opening_time,
        closing_time,
        duration,
        num_favorites,
        num_reviews,
        current_environment_rating,
        current_equipment_rating,
        current_service_rating,
        current_total_rating
    )
VALUES (
        3,
        5,
        'Football field in Porto''s downtown',
        'Rua das Flores 123, Porto',
        41.1442807,
        -8.6130616,
        'Well-maintained grass field suitable for 5v5 and 7v7 matches.',
        FALSE,
        '912345678',
        'porto_field@mail.com',
        '08:00:00',
        '22:00:00',
        30,
        15,
        2,
        5,
        4,
        5,
        14
    ),
    (
        2,
        1,
        'Badminton Court Boavista',
        'Av. da Boavista 540, Porto',
        41.1579989,
        -8.633074,
        'Indoor badminton court with great lighting and wooden flooring.',
        FALSE,
        '911223344',
        'boavista_badminton@mail.com',
        '09:00:00',
        '22:00:00',
        60,
        9,
        1,
        5,
        5,
        5,
        15
    ),
    (
        1,
        7,
        'Iron Gym Central',
        'Rua de Cedofeita 88, Porto',
        41.149618,
        -8.6191305,
        'Fully equipped gym with modern machines and personal training available.',
        FALSE,
        '935678901',
        'iron_gym@mail.com',
        '06:00:00',
        '23:00:00',
        60,
        22,
        2,
        4,
        4,
        4,
        12
    ),
    (
        4,
        3,
        'Gaia Biking Park',
        'Rua da Praia, Vila Nova de Gaia',
        41.1380258,
        -8.6593563,
        'Large biking park with trails for beginners and pros.',
        FALSE,
        '937654321',
        'gaia_bike@mail.com',
        '08:00:00',
        '20:00:00',
        120,
        17,
        2,
        5,
        4,
        5,
        14
    ),
    (
        3,
        2,
        'Downtown Basketball Court',
        'Praça da República 12, Porto',
        41.1536453,
        -8.6149867,
        'Outdoor basketball court open to public, newly resurfaced.',
        FALSE,
        '934567890',
        'downtown_bball@mail.com',
        '10:00:00',
        '22:00:00',
        60,
        30,
        4,
        4,
        4,
        3,
        11
    ),
    (
        1,
        6,
        'Golf Club Foz',
        'Av. Brasil 999, Foz do Douro',
        41.1591669,
        -8.6861682,
        'Private golf course with ocean view.',
        FALSE,
        '938888777',
        'foz_golf@mail.com',
        '07:00:00',
        '19:00:00',
        120,
        12,
        1,
        5,
        5,
        5,
        15
    ),
    (
        5,
        11,
        'Padel Arena Porto',
        'Rua da Constituição 150, Porto',
        41.1614288,
        -8.6025899,
        'Two indoor padel courts with locker rooms and café.',
        FALSE,
        '936789123',
        'padel_arena@mail.com',
        '09:00:00',
        '23:00:00',
        60,
        25,
        0,
        0,
        0,
        0,
        0
    ),
    (
        3,
        15,
        'Indoor Swimming Complex',
        'Rua da Alegria 720, Porto',
        41.1574821,
        -8.6017456,
        'Olympic-sized pool and training lanes, lifeguards on duty.',
        FALSE,
        '932112233',
        'swim_complex@mail.com',
        '06:30:00',
        '21:30:00',
        60,
        19,
        0,
        0,
        0,
        0,
        0
    ),
    (
        5,
        4,
        'Climbing Zone Campanhã',
        'Rua do Heroísmo 200, Porto',
        41.1466845,
        -8.5946565,
        'Indoor climbing gym with bouldering and top rope walls.',
        FALSE,
        '931445566',
        'climb_zone@mail.com',
        '10:00:00',
        '22:00:00',
        90,
        14,
        0,
        0,
        0,
        0,
        0
    ),
    (
        3,
        9,
        'Hockey Arena Norte',
        'Av. de França 340, Porto',
        41.1470552,
        -8.6044052,
        'Indoor hockey arena hosting local matches and training.',
        FALSE,
        '939334455',
        'hockey_norte@mail.com',
        '08:00:00',
        '23:00:00',
        60,
        8,
        0,
        0,
        0,
        0,
        0
    ),
    (
        1,
        13,
        'Riverside Running Track',
        'Cais de Gaia',
        41.1368678,
        -8.6255192,
        'Beautiful riverside track perfect for daily runs.',
        FALSE,
        '937223344',
        'riverside_run@mail.com',
        '06:00:00',
        '21:00:00',
        30,
        20,
        0,
        0,
        0,
        0,
        0
    );

INSERT INTO
    admin (email, password)
VALUES (
        'admin1@example.com',
        'hashedpassword1'
    ),
    (
        'admin2@example.com',
        'hashedpassword2'
    );

INSERT INTO
    ban (
        user_id,
        admin_id,
        motive,
        time_stamp
    )
VALUES (
        4,
        1,
        'Spamming services',
        '2025-01-05 14:32:52'
    ),
    (
        2,
        1,
        'Harassment in messages',
        '2025-02-10 09:45:20'
    ),
    (
        3,
        2,
        'Repeated rule violations',
        '2025-10-01 18:50:01'
    );

INSERT INTO
    schedule (
        space_id,
        start_time,
        max_capacity
    )
VALUES
    -- Space 1: Football field
    (1, '2026-02-01 18:00:00', 14),
    (1, '2026-02-02 19:00:00', 14),
    (1, '2026-02-03 20:00:00', 14),
    (1, '2026-02-04 18:30:00', 14),
    (1, '2026-02-05 17:00:00', 14),
    (1, '2026-02-06 19:30:00', 14),
    (1, '2026-02-07 18:00:00', 14),
    (1, '2026-02-08 20:00:00', 14),
    (1, '2026-02-09 17:30:00', 14),
    (1, '2026-02-10 19:00:00', 14),

-- Space 2: Badminton Court
(2, '2026-02-01 17:30:00', 4),
(2, '2026-02-02 17:30:00', 4),
(2, '2026-02-02 18:30:00', 4),
(2, '2026-02-03 19:00:00', 4),
(2, '2026-02-04 17:00:00', 4),
(2, '2026-02-05 19:00:00', 4),
(2, '2026-02-06 18:00:00', 4),
(2, '2026-02-07 17:30:00', 4),
(2, '2026-02-08 19:30:00', 4),
(2, '2026-02-09 18:00:00', 4),
(2, '2026-02-10 17:00:00', 4),

-- Space 3: Iron Gym
(3, '2025-12-19 06:00:00', 20),
(3, '2025-12-19 07:00:00', 25),
(3, '2025-12-19 08:00:00', 20),
(3, '2025-12-19 09:00:00', 25),
(3, '2025-12-19 10:00:00', 20),
(3, '2025-12-19 11:00:00', 25),
(3, '2025-12-19 12:00:00', 20),
(3, '2025-12-19 13:00:00', 25),
(3, '2025-12-19 14:00:00', 20),
(3, '2025-12-19 15:00:00', 25),
(3, '2025-12-19 16:00:00', 20),
(3, '2025-12-19 17:00:00', 25),
(3, '2025-12-19 18:00:00', 20),
(3, '2025-12-19 19:00:00', 25),
(3, '2025-12-19 20:00:00', 20),
(3, '2025-12-19 21:00:00', 25),
(3, '2025-12-19 22:00:00', 20),
(3, '2025-12-20 06:00:00', 20),
(3, '2025-12-20 07:00:00', 25),
(3, '2025-12-20 08:00:00', 20),
(3, '2025-12-20 09:00:00', 25),
(3, '2025-12-20 10:00:00', 20),
(3, '2025-12-20 11:00:00', 25),
(3, '2025-12-20 12:00:00', 20),
(3, '2025-12-20 13:00:00', 25),
(3, '2025-12-20 14:00:00', 20),
(3, '2025-12-20 15:00:00', 25),
(3, '2025-12-20 16:00:00', 20),
(3, '2025-12-20 17:00:00', 25),
(3, '2025-12-20 18:00:00', 20),
(3, '2025-12-20 19:00:00', 25),
(3, '2025-12-20 20:00:00', 20),
(3, '2025-12-20 21:00:00', 25),
(3, '2025-12-20 22:00:00', 20),
(3, '2025-12-21 06:00:00', 25),
(3, '2025-12-21 07:00:00', 20),
(3, '2025-12-21 08:00:00', 25),
(3, '2025-12-21 09:00:00', 20),
(3, '2025-12-21 10:00:00', 25),
(3, '2025-12-21 11:00:00', 20),
(3, '2025-12-21 12:00:00', 25),
(3, '2025-12-21 13:00:00', 20),
(3, '2025-12-21 14:00:00', 25),
(3, '2025-12-21 15:00:00', 20),
(3, '2025-12-21 16:00:00', 25),
(3, '2025-12-21 17:00:00', 20),
(3, '2025-12-21 18:00:00', 25),
(3, '2025-12-21 19:00:00', 20),
(3, '2025-12-21 20:00:00', 25),
(3, '2025-12-21 21:00:00', 20),
(3, '2025-12-21 22:00:00', 25),
(3, '2025-12-22 06:00:00', 20),
(3, '2025-12-22 07:00:00', 25),
(3, '2025-12-22 08:00:00', 20),
(3, '2025-12-22 09:00:00', 25),
(3, '2025-12-22 10:00:00', 20),
(3, '2025-12-22 11:00:00', 25),
(3, '2025-12-22 12:00:00', 20),
(3, '2025-12-22 13:00:00', 25),
(3, '2025-12-22 14:00:00', 20),
(3, '2025-12-22 15:00:00', 25),
(3, '2025-12-22 16:00:00', 20),
(3, '2025-12-22 17:00:00', 25),
(3, '2025-12-22 18:00:00', 20),
(3, '2025-12-22 19:00:00', 25),
(3, '2025-12-22 20:00:00', 20),
(3, '2025-12-22 21:00:00', 25),
(3, '2025-12-22 22:00:00', 20),
(3, '2025-12-23 06:00:00', 25),
(3, '2025-12-23 07:00:00', 20),
(3, '2025-12-23 08:00:00', 25),
(3, '2025-12-23 09:00:00', 20),
(3, '2025-12-23 10:00:00', 25),
(3, '2025-12-23 11:00:00', 20),
(3, '2025-12-23 12:00:00', 25),
(3, '2025-12-23 13:00:00', 20),
(3, '2025-12-23 14:00:00', 25),
(3, '2025-12-23 15:00:00', 20),
(3, '2025-12-23 16:00:00', 25),
(3, '2025-12-23 17:00:00', 20),
(3, '2025-12-23 18:00:00', 25),
(3, '2025-12-23 19:00:00', 20),
(3, '2025-12-23 20:00:00', 25),
(3, '2025-12-23 21:00:00', 20),
(3, '2025-12-23 22:00:00', 25),
(3, '2025-12-24 06:00:00', 20),
(3, '2025-12-24 07:00:00', 25),
(3, '2025-12-24 08:00:00', 20),
(3, '2025-12-24 09:00:00', 25),
(3, '2025-12-24 10:00:00', 20),
(3, '2025-12-24 11:00:00', 25),
(3, '2025-12-24 12:00:00', 20),
(3, '2025-12-24 13:00:00', 25),
(3, '2025-12-24 14:00:00', 20),
(3, '2025-12-24 15:00:00', 25),
(3, '2025-12-24 16:00:00', 20),
(3, '2025-12-24 17:00:00', 25),
(3, '2025-12-24 18:00:00', 20),
(3, '2025-12-24 19:00:00', 25),
(3, '2025-12-24 20:00:00', 20),
(3, '2025-12-24 21:00:00', 25),
(3, '2025-12-24 22:00:00', 20),
(3, '2025-12-25 06:00:00', 25),
(3, '2025-12-25 07:00:00', 20),
(3, '2025-12-25 08:00:00', 25),
(3, '2025-12-25 09:00:00', 20),
(3, '2025-12-25 10:00:00', 25),
(3, '2025-12-25 11:00:00', 20),
(3, '2025-12-25 12:00:00', 25),
(3, '2025-12-25 13:00:00', 20),
(3, '2025-12-25 14:00:00', 25),
(3, '2025-12-25 15:00:00', 20),
(3, '2025-12-25 16:00:00', 25),
(3, '2025-12-25 17:00:00', 20),
(3, '2025-12-25 18:00:00', 25),
(3, '2025-12-25 19:00:00', 20),
(3, '2025-12-25 20:00:00', 25),
(3, '2025-12-25 21:00:00', 20),
(3, '2025-12-25 22:00:00', 25),
(3, '2025-12-26 06:00:00', 20),
(3, '2025-12-26 07:00:00', 25),
(3, '2025-12-26 08:00:00', 20),
(3, '2025-12-26 09:00:00', 25),
(3, '2025-12-26 10:00:00', 20),
(3, '2025-12-26 11:00:00', 25),
(3, '2025-12-26 12:00:00', 20),
(3, '2025-12-26 13:00:00', 25),
(3, '2025-12-26 14:00:00', 20),
(3, '2025-12-26 15:00:00', 25),
(3, '2025-12-26 16:00:00', 20),
(3, '2025-12-26 17:00:00', 25),
(3, '2025-12-26 18:00:00', 20),
(3, '2025-12-26 19:00:00', 25),
(3, '2025-12-26 20:00:00', 20),
(3, '2025-12-26 21:00:00', 25),
(3, '2025-12-26 22:00:00', 20),
(3, '2025-12-27 06:00:00', 25),
(3, '2025-12-27 07:00:00', 20),
(3, '2025-12-27 08:00:00', 25),
(3, '2025-12-27 09:00:00', 20),
(3, '2025-12-27 10:00:00', 25),
(3, '2025-12-27 11:00:00', 20),
(3, '2025-12-27 12:00:00', 25),
(3, '2025-12-27 13:00:00', 20),
(3, '2025-12-27 14:00:00', 25),
(3, '2025-12-27 15:00:00', 20),
(3, '2025-12-27 16:00:00', 25),
(3, '2025-12-27 17:00:00', 20),
(3, '2025-12-27 18:00:00', 25),
(3, '2025-12-27 19:00:00', 20),
(3, '2025-12-27 20:00:00', 25),
(3, '2025-12-27 21:00:00', 20),
(3, '2025-12-27 22:00:00', 25),
(3, '2025-12-28 06:00:00', 20),
(3, '2025-12-28 07:00:00', 25),
(3, '2025-12-28 08:00:00', 20),
(3, '2025-12-28 09:00:00', 25),
(3, '2025-12-28 10:00:00', 20),
(3, '2025-12-28 11:00:00', 25),
(3, '2025-12-28 12:00:00', 20),
(3, '2025-12-28 13:00:00', 25),
(3, '2025-12-28 14:00:00', 20),
(3, '2025-12-28 15:00:00', 25),
(3, '2025-12-28 16:00:00', 20),
(3, '2025-12-28 17:00:00', 25),
(3, '2025-12-28 18:00:00', 20),
(3, '2025-12-28 19:00:00', 25),
(3, '2025-12-28 20:00:00', 20),
(3, '2025-12-28 21:00:00', 25),
(3, '2025-12-28 22:00:00', 20),
(3, '2025-12-29 06:00:00', 25),
(3, '2025-12-29 07:00:00', 20),
(3, '2025-12-29 08:00:00', 25),
(3, '2025-12-29 09:00:00', 20),
(3, '2025-12-29 10:00:00', 25),
(3, '2025-12-29 11:00:00', 20),
(3, '2025-12-29 12:00:00', 25),
(3, '2025-12-29 13:00:00', 20),
(3, '2025-12-29 14:00:00', 25),
(3, '2025-12-29 15:00:00', 20),
(3, '2025-12-29 16:00:00', 25),
(3, '2025-12-29 17:00:00', 20),
(3, '2025-12-29 18:00:00', 25),
(3, '2025-12-29 19:00:00', 20),
(3, '2025-12-29 20:00:00', 25),
(3, '2025-12-29 21:00:00', 20),
(3, '2025-12-29 22:00:00', 25),
(3, '2025-12-30 06:00:00', 20),
(3, '2025-12-30 07:00:00', 25),
(3, '2025-12-30 08:00:00', 20),
(3, '2025-12-30 09:00:00', 25),
(3, '2025-12-30 10:00:00', 20),
(3, '2025-12-30 11:00:00', 25),
(3, '2025-12-30 12:00:00', 20),
(3, '2025-12-30 13:00:00', 25),
(3, '2025-12-30 14:00:00', 20),
(3, '2025-12-30 15:00:00', 25),
(3, '2025-12-30 16:00:00', 20),
(3, '2025-12-30 17:00:00', 25),
(3, '2025-12-30 18:00:00', 20),
(3, '2025-12-30 19:00:00', 25),
(3, '2025-12-30 20:00:00', 20),
(3, '2025-12-30 21:00:00', 25),
(3, '2025-12-30 22:00:00', 20),
(3, '2025-12-31 06:00:00', 25),
(3, '2025-12-31 07:00:00', 20),
(3, '2025-12-31 08:00:00', 25),
(3, '2025-12-31 09:00:00', 20),
(3, '2025-12-31 10:00:00', 25),
(3, '2025-12-31 11:00:00', 20),
(3, '2025-12-31 12:00:00', 25),
(3, '2025-12-31 13:00:00', 20),
(3, '2025-12-31 14:00:00', 25),
(3, '2025-12-31 15:00:00', 20),
(3, '2025-12-31 16:00:00', 25),
(3, '2025-12-31 17:00:00', 20),
(3, '2025-12-31 18:00:00', 25),
(3, '2025-12-31 19:00:00', 20),
(3, '2025-12-31 20:00:00', 25),
(3, '2025-12-31 21:00:00', 20),
(3, '2025-12-31 22:00:00', 25),
(3, '2026-01-01 06:00:00', 20),
(3, '2026-01-01 07:00:00', 25),
(3, '2026-01-01 08:00:00', 20),
(3, '2026-01-01 09:00:00', 25),
(3, '2026-01-01 10:00:00', 20),
(3, '2026-01-01 11:00:00', 25),
(3, '2026-01-01 12:00:00', 20),
(3, '2026-01-01 13:00:00', 25),
(3, '2026-01-01 14:00:00', 20),
(3, '2026-01-01 15:00:00', 25),
(3, '2026-01-01 16:00:00', 20),
(3, '2026-01-01 17:00:00', 25),
(3, '2026-01-01 18:00:00', 20),
(3, '2026-01-01 19:00:00', 25),
(3, '2026-01-01 20:00:00', 20),
(3, '2026-01-01 21:00:00', 25),
(3, '2026-01-01 22:00:00', 20),
(3, '2026-01-02 06:00:00', 25),
(3, '2026-01-02 07:00:00', 20),
(3, '2026-01-02 08:00:00', 25),
(3, '2026-01-02 09:00:00', 20),
(3, '2026-01-02 10:00:00', 25),
(3, '2026-01-02 11:00:00', 20),
(3, '2026-01-02 12:00:00', 25),
(3, '2026-01-02 13:00:00', 20),
(3, '2026-01-02 14:00:00', 25),
(3, '2026-01-02 15:00:00', 20),
(3, '2026-01-02 16:00:00', 25),
(3, '2026-01-02 17:00:00', 20),
(3, '2026-01-02 18:00:00', 25),
(3, '2026-01-02 19:00:00', 20),
(3, '2026-01-02 20:00:00', 25),
(3, '2026-01-02 21:00:00', 20),
(3, '2026-01-02 22:00:00', 25),
(3, '2026-01-03 06:00:00', 20),
(3, '2026-01-03 07:00:00', 25),
(3, '2026-01-03 08:00:00', 20),
(3, '2026-01-03 09:00:00', 25),
(3, '2026-01-03 10:00:00', 20),
(3, '2026-01-03 11:00:00', 25),
(3, '2026-01-03 12:00:00', 20),
(3, '2026-01-03 13:00:00', 25),
(3, '2026-01-03 14:00:00', 20),
(3, '2026-01-03 15:00:00', 25),
(3, '2026-01-03 16:00:00', 20),
(3, '2026-01-03 17:00:00', 25),
(3, '2026-01-03 18:00:00', 20),
(3, '2026-01-03 19:00:00', 25),
(3, '2026-01-03 20:00:00', 20),
(3, '2026-01-03 21:00:00', 25),
(3, '2026-01-03 22:00:00', 20),
(3, '2026-01-04 06:00:00', 25),
(3, '2026-01-04 07:00:00', 20),
(3, '2026-01-04 08:00:00', 25),
(3, '2026-01-04 09:00:00', 20),
(3, '2026-01-04 10:00:00', 25),
(3, '2026-01-04 11:00:00', 20),
(3, '2026-01-04 12:00:00', 25),
(3, '2026-01-04 13:00:00', 20),
(3, '2026-01-04 14:00:00', 25),
(3, '2026-01-04 15:00:00', 20),
(3, '2026-01-04 16:00:00', 25),
(3, '2026-01-04 17:00:00', 20),
(3, '2026-01-04 18:00:00', 25),
(3, '2026-01-04 19:00:00', 20),
(3, '2026-01-04 20:00:00', 25),
(3, '2026-01-04 21:00:00', 20),
(3, '2026-01-04 22:00:00', 25),
(3, '2026-01-05 06:00:00', 20),
(3, '2026-01-05 07:00:00', 25),
(3, '2026-01-05 08:00:00', 20),
(3, '2026-01-05 09:00:00', 25),
(3, '2026-01-05 10:00:00', 20),
(3, '2026-01-05 11:00:00', 25),
(3, '2026-01-05 12:00:00', 20),
(3, '2026-01-05 13:00:00', 25),
(3, '2026-01-05 14:00:00', 20),
(3, '2026-01-05 15:00:00', 25),
(3, '2026-01-05 16:00:00', 20),
(3, '2026-01-05 17:00:00', 25),
(3, '2026-01-05 18:00:00', 20),
(3, '2026-01-05 19:00:00', 25),
(3, '2026-01-05 20:00:00', 20),
(3, '2026-01-05 21:00:00', 25),
(3, '2026-01-05 22:00:00', 20),
(3, '2026-01-06 06:00:00', 25),
(3, '2026-01-06 07:00:00', 20),
(3, '2026-01-06 08:00:00', 25),
(3, '2026-01-06 09:00:00', 20),
(3, '2026-01-06 10:00:00', 25),
(3, '2026-01-06 11:00:00', 20),
(3, '2026-01-06 12:00:00', 25),
(3, '2026-01-06 13:00:00', 20),
(3, '2026-01-06 14:00:00', 25),
(3, '2026-01-06 15:00:00', 20),
(3, '2026-01-06 16:00:00', 25),
(3, '2026-01-06 17:00:00', 20),
(3, '2026-01-06 18:00:00', 25),
(3, '2026-01-06 19:00:00', 20),
(3, '2026-01-06 20:00:00', 25),
(3, '2026-01-06 21:00:00', 20),
(3, '2026-01-06 22:00:00', 25),
(3, '2026-01-07 06:00:00', 20),
(3, '2026-01-07 07:00:00', 25),
(3, '2026-01-07 08:00:00', 20),
(3, '2026-01-07 09:00:00', 25),
(3, '2026-01-07 10:00:00', 20),
(3, '2026-01-07 11:00:00', 25),
(3, '2026-01-07 12:00:00', 20),
(3, '2026-01-07 13:00:00', 25),
(3, '2026-01-07 14:00:00', 20),
(3, '2026-01-07 15:00:00', 25),
(3, '2026-01-07 16:00:00', 20),
(3, '2026-01-07 17:00:00', 25),
(3, '2026-01-07 18:00:00', 20),
(3, '2026-01-07 19:00:00', 25),
(3, '2026-01-07 20:00:00', 20),
(3, '2026-01-07 21:00:00', 25),
(3, '2026-01-07 22:00:00', 20),
(3, '2026-01-08 06:00:00', 25),
(3, '2026-01-08 07:00:00', 20),
(3, '2026-01-08 08:00:00', 25),
(3, '2026-01-08 09:00:00', 20),
(3, '2026-01-08 10:00:00', 25),
(3, '2026-01-08 11:00:00', 20),
(3, '2026-01-08 12:00:00', 25),
(3, '2026-01-08 13:00:00', 20),
(3, '2026-01-08 14:00:00', 25),
(3, '2026-01-08 15:00:00', 20),
(3, '2026-01-08 16:00:00', 25),
(3, '2026-01-08 17:00:00', 20),
(3, '2026-01-08 18:00:00', 25),
(3, '2026-01-08 19:00:00', 20),
(3, '2026-01-08 20:00:00', 25),
(3, '2026-01-08 21:00:00', 20),
(3, '2026-01-08 22:00:00', 25),
(3, '2026-01-09 06:00:00', 20),
(3, '2026-01-09 07:00:00', 25),
(3, '2026-01-09 08:00:00', 20),
(3, '2026-01-09 09:00:00', 25),
(3, '2026-01-09 10:00:00', 20),
(3, '2026-01-09 11:00:00', 25),
(3, '2026-01-09 12:00:00', 20),
(3, '2026-01-09 13:00:00', 25),
(3, '2026-01-09 14:00:00', 20),
(3, '2026-01-09 15:00:00', 25),
(3, '2026-01-09 16:00:00', 20),
(3, '2026-01-09 17:00:00', 25),
(3, '2026-01-09 18:00:00', 20),
(3, '2026-01-09 19:00:00', 25),
(3, '2026-01-09 20:00:00', 20),
(3, '2026-01-09 21:00:00', 25),
(3, '2026-01-09 22:00:00', 20),
(3, '2026-01-10 06:00:00', 25),
(3, '2026-01-10 07:00:00', 20),
(3, '2026-01-10 08:00:00', 25),
(3, '2026-01-10 09:00:00', 20),
(3, '2026-01-10 10:00:00', 25),
(3, '2026-01-10 11:00:00', 20),
(3, '2026-01-10 12:00:00', 25),
(3, '2026-01-10 13:00:00', 20),
(3, '2026-01-10 14:00:00', 25),
(3, '2026-01-10 15:00:00', 20),
(3, '2026-01-10 16:00:00', 25),
(3, '2026-01-10 17:00:00', 20),
(3, '2026-01-10 18:00:00', 25),
(3, '2026-01-10 19:00:00', 20),
(3, '2026-01-10 20:00:00', 25),
(3, '2026-01-10 21:00:00', 20),
(3, '2026-01-10 22:00:00', 25),
(3, '2026-01-11 06:00:00', 20),
(3, '2026-01-11 07:00:00', 25),
(3, '2026-01-11 08:00:00', 20),
(3, '2026-01-11 09:00:00', 25),
(3, '2026-01-11 10:00:00', 20),
(3, '2026-01-11 11:00:00', 25),
(3, '2026-01-11 12:00:00', 20),
(3, '2026-01-11 13:00:00', 25),
(3, '2026-01-11 14:00:00', 20),
(3, '2026-01-11 15:00:00', 25),
(3, '2026-01-11 16:00:00', 20),
(3, '2026-01-11 17:00:00', 25),
(3, '2026-01-11 18:00:00', 20),
(3, '2026-01-11 19:00:00', 25),
(3, '2026-01-11 20:00:00', 20),
(3, '2026-01-11 21:00:00', 25),
(3, '2026-01-11 22:00:00', 20),
(3, '2026-01-12 06:00:00', 25),
(3, '2026-01-12 07:00:00', 20),
(3, '2026-01-12 08:00:00', 25),
(3, '2026-01-12 09:00:00', 20),
(3, '2026-01-12 10:00:00', 25),
(3, '2026-01-12 11:00:00', 20),
(3, '2026-01-12 12:00:00', 25),
(3, '2026-01-12 13:00:00', 20),
(3, '2026-01-12 14:00:00', 25),
(3, '2026-01-12 15:00:00', 20),
(3, '2026-01-12 16:00:00', 25),
(3, '2026-01-12 17:00:00', 20),
(3, '2026-01-12 18:00:00', 25),
(3, '2026-01-12 19:00:00', 20),
(3, '2026-01-12 20:00:00', 25),
(3, '2026-01-12 21:00:00', 20),
(3, '2026-01-12 22:00:00', 25),
(3, '2026-01-13 06:00:00', 20),
(3, '2026-01-13 07:00:00', 25),
(3, '2026-01-13 08:00:00', 20),
(3, '2026-01-13 09:00:00', 25),
(3, '2026-01-13 10:00:00', 20),
(3, '2026-01-13 11:00:00', 25),
(3, '2026-01-13 12:00:00', 20),
(3, '2026-01-13 13:00:00', 25),
(3, '2026-01-13 14:00:00', 20),
(3, '2026-01-13 15:00:00', 25),
(3, '2026-01-13 16:00:00', 20),
(3, '2026-01-13 17:00:00', 25),
(3, '2026-01-13 18:00:00', 20),
(3, '2026-01-13 19:00:00', 25),
(3, '2026-01-13 20:00:00', 20),
(3, '2026-01-13 21:00:00', 25),
(3, '2026-01-13 22:00:00', 20),
(3, '2026-01-14 06:00:00', 25),
(3, '2026-01-14 07:00:00', 20),
(3, '2026-01-14 08:00:00', 25),
(3, '2026-01-14 09:00:00', 20),
(3, '2026-01-14 10:00:00', 25),
(3, '2026-01-14 11:00:00', 20),
(3, '2026-01-14 12:00:00', 25),
(3, '2026-01-14 13:00:00', 20),
(3, '2026-01-14 14:00:00', 25),
(3, '2026-01-14 15:00:00', 20),
(3, '2026-01-14 16:00:00', 25),
(3, '2026-01-14 17:00:00', 20),
(3, '2026-01-14 18:00:00', 25),
(3, '2026-01-14 19:00:00', 20),
(3, '2026-01-14 20:00:00', 25),
(3, '2026-01-14 21:00:00', 20),
(3, '2026-01-14 22:00:00', 25),
(3, '2026-01-15 06:00:00', 20),
(3, '2026-01-15 07:00:00', 25),
(3, '2026-01-15 08:00:00', 20),
(3, '2026-01-15 09:00:00', 25),
(3, '2026-01-15 10:00:00', 20),
(3, '2026-01-15 11:00:00', 25),
(3, '2026-01-15 12:00:00', 20),
(3, '2026-01-15 13:00:00', 25),
(3, '2026-01-15 14:00:00', 20),
(3, '2026-01-15 15:00:00', 25),
(3, '2026-01-15 16:00:00', 20),
(3, '2026-01-15 17:00:00', 25),
(3, '2026-01-15 18:00:00', 20),
(3, '2026-01-15 19:00:00', 25),
(3, '2026-01-15 20:00:00', 20),
(3, '2026-01-15 21:00:00', 25),
(3, '2026-01-15 22:00:00', 20),

-- Space 4: Gaia Biking Park
(4, '2026-02-01 10:00:00', 30),
(4, '2026-02-02 14:00:00', 25),
(4, '2026-02-03 09:00:00', 30),
(4, '2026-02-04 15:00:00', 25),
(4, '2026-02-05 10:30:00', 30),
(4, '2026-02-06 13:00:00', 30),
(4, '2026-02-07 10:00:00', 25),
(4, '2026-02-08 14:30:00', 30),
(4, '2026-02-09 09:30:00', 25),
(4, '2026-02-10 14:00:00', 30),

-- Space 5: Downtown Basketball Court
(5, '2026-02-01 16:00:00', 10),
(5, '2026-02-02 18:30:00', 12),
(5, '2026-02-03 17:00:00', 10),
(5, '2026-02-04 19:00:00', 12),
(5, '2026-02-05 16:30:00', 10),
(5, '2026-02-06 18:00:00', 12),
(5, '2026-02-07 17:30:00', 10),
(5, '2026-02-08 19:30:00', 12),
(5, '2026-02-09 16:00:00', 10),
(5, '2026-02-10 18:30:00', 12),

-- Space 6: Golf Club Foz
(6, '2025-12-19 07:00:00', 18),
(6, '2025-12-19 09:00:00', 20),
(6, '2025-12-19 11:00:00', 18),
(6, '2025-12-19 13:00:00', 20),
(6, '2025-12-19 15:00:00', 18),
(6, '2025-12-19 17:00:00', 20),
(6, '2025-12-20 07:00:00', 18),
(6, '2025-12-20 09:00:00', 20),
(6, '2025-12-20 11:00:00', 18),
(6, '2025-12-20 13:00:00', 20),
(6, '2025-12-20 15:00:00', 18),
(6, '2025-12-20 17:00:00', 20),
(6, '2025-12-21 07:00:00', 18),
(6, '2025-12-21 09:00:00', 20),
(6, '2025-12-21 11:00:00', 18),
(6, '2025-12-21 13:00:00', 20),
(6, '2025-12-21 15:00:00', 18),
(6, '2025-12-21 17:00:00', 20),
(6, '2025-12-22 07:00:00', 18),
(6, '2025-12-22 09:00:00', 20),
(6, '2025-12-22 11:00:00', 18),
(6, '2025-12-22 13:00:00', 20),
(6, '2025-12-22 15:00:00', 18),
(6, '2025-12-22 17:00:00', 20),
(6, '2025-12-23 07:00:00', 18),
(6, '2025-12-23 09:00:00', 20),
(6, '2025-12-23 11:00:00', 18),
(6, '2025-12-23 13:00:00', 20),
(6, '2025-12-23 15:00:00', 18),
(6, '2025-12-23 17:00:00', 20),
(6, '2025-12-24 07:00:00', 18),
(6, '2025-12-24 09:00:00', 20),
(6, '2025-12-24 11:00:00', 18),
(6, '2025-12-24 13:00:00', 20),
(6, '2025-12-24 15:00:00', 18),
(6, '2025-12-24 17:00:00', 20),
(6, '2025-12-25 07:00:00', 18),
(6, '2025-12-25 09:00:00', 20),
(6, '2025-12-25 11:00:00', 18),
(6, '2025-12-25 13:00:00', 20),
(6, '2025-12-25 15:00:00', 18),
(6, '2025-12-25 17:00:00', 20),
(6, '2025-12-26 07:00:00', 18),
(6, '2025-12-26 09:00:00', 20),
(6, '2025-12-26 11:00:00', 18),
(6, '2025-12-26 13:00:00', 20),
(6, '2025-12-26 15:00:00', 18),
(6, '2025-12-26 17:00:00', 20),
(6, '2025-12-27 07:00:00', 18),
(6, '2025-12-27 09:00:00', 20),
(6, '2025-12-27 11:00:00', 18),
(6, '2025-12-27 13:00:00', 20),
(6, '2025-12-27 15:00:00', 18),
(6, '2025-12-27 17:00:00', 20),
(6, '2025-12-28 07:00:00', 18),
(6, '2025-12-28 09:00:00', 20),
(6, '2025-12-28 11:00:00', 18),
(6, '2025-12-28 13:00:00', 20),
(6, '2025-12-28 15:00:00', 18),
(6, '2025-12-28 17:00:00', 20),
(6, '2025-12-29 07:00:00', 18),
(6, '2025-12-29 09:00:00', 20),
(6, '2025-12-29 11:00:00', 18),
(6, '2025-12-29 13:00:00', 20),
(6, '2025-12-29 15:00:00', 18),
(6, '2025-12-29 17:00:00', 20),
(6, '2025-12-30 07:00:00', 18),
(6, '2025-12-30 09:00:00', 20),
(6, '2025-12-30 11:00:00', 18),
(6, '2025-12-30 13:00:00', 20),
(6, '2025-12-30 15:00:00', 18),
(6, '2025-12-30 17:00:00', 20),
(6, '2025-12-31 07:00:00', 18),
(6, '2025-12-31 09:00:00', 20),
(6, '2025-12-31 11:00:00', 18),
(6, '2025-12-31 13:00:00', 20),
(6, '2025-12-31 15:00:00', 18),
(6, '2025-12-31 17:00:00', 20),
(6, '2026-01-01 07:00:00', 18),
(6, '2026-01-01 09:00:00', 20),
(6, '2026-01-01 11:00:00', 18),
(6, '2026-01-01 13:00:00', 20),
(6, '2026-01-01 15:00:00', 18),
(6, '2026-01-01 17:00:00', 20),
(6, '2026-01-02 07:00:00', 18),
(6, '2026-01-02 09:00:00', 20),
(6, '2026-01-02 11:00:00', 18),
(6, '2026-01-02 13:00:00', 20),
(6, '2026-01-02 15:00:00', 18),
(6, '2026-01-02 17:00:00', 20),
(6, '2026-01-03 07:00:00', 18),
(6, '2026-01-03 09:00:00', 20),
(6, '2026-01-03 11:00:00', 18),
(6, '2026-01-03 13:00:00', 20),
(6, '2026-01-03 15:00:00', 18),
(6, '2026-01-03 17:00:00', 20),
(6, '2026-01-04 07:00:00', 18),
(6, '2026-01-04 09:00:00', 20),
(6, '2026-01-04 11:00:00', 18),
(6, '2026-01-04 13:00:00', 20),
(6, '2026-01-04 15:00:00', 18),
(6, '2026-01-04 17:00:00', 20),
(6, '2026-01-05 07:00:00', 18),
(6, '2026-01-05 09:00:00', 20),
(6, '2026-01-05 11:00:00', 18),
(6, '2026-01-05 13:00:00', 20),
(6, '2026-01-05 15:00:00', 18),
(6, '2026-01-05 17:00:00', 20),
(6, '2026-01-06 07:00:00', 18),
(6, '2026-01-06 09:00:00', 20),
(6, '2026-01-06 11:00:00', 18),
(6, '2026-01-06 13:00:00', 20),
(6, '2026-01-06 15:00:00', 18),
(6, '2026-01-06 17:00:00', 20),
(6, '2026-01-07 07:00:00', 18),
(6, '2026-01-07 09:00:00', 20),
(6, '2026-01-07 11:00:00', 18),
(6, '2026-01-07 13:00:00', 20),
(6, '2026-01-07 15:00:00', 18),
(6, '2026-01-07 17:00:00', 20),
(6, '2026-01-08 07:00:00', 18),
(6, '2026-01-08 09:00:00', 20),
(6, '2026-01-08 11:00:00', 18),
(6, '2026-01-08 13:00:00', 20),
(6, '2026-01-08 15:00:00', 18),
(6, '2026-01-08 17:00:00', 20),
(6, '2026-01-09 07:00:00', 18),
(6, '2026-01-09 09:00:00', 20),
(6, '2026-01-09 11:00:00', 18),
(6, '2026-01-09 13:00:00', 20),
(6, '2026-01-09 15:00:00', 18),
(6, '2026-01-09 17:00:00', 20),
(6, '2026-01-10 07:00:00', 18),
(6, '2026-01-10 09:00:00', 20),
(6, '2026-01-10 11:00:00', 18),
(6, '2026-01-10 13:00:00', 20),
(6, '2026-01-10 15:00:00', 18),
(6, '2026-01-10 17:00:00', 20),
(6, '2026-01-11 07:00:00', 18),
(6, '2026-01-11 09:00:00', 20),
(6, '2026-01-11 11:00:00', 18),
(6, '2026-01-11 13:00:00', 20),
(6, '2026-01-11 15:00:00', 18),
(6, '2026-01-11 17:00:00', 20),
(6, '2026-01-12 07:00:00', 18),
(6, '2026-01-12 09:00:00', 20),
(6, '2026-01-12 11:00:00', 18),
(6, '2026-01-12 13:00:00', 20),
(6, '2026-01-12 15:00:00', 18),
(6, '2026-01-12 17:00:00', 20),
(6, '2026-01-13 07:00:00', 18),
(6, '2026-01-13 09:00:00', 20),
(6, '2026-01-13 11:00:00', 18),
(6, '2026-01-13 13:00:00', 20),
(6, '2026-01-13 15:00:00', 18),
(6, '2026-01-13 17:00:00', 20),
(6, '2026-01-14 07:00:00', 18),
(6, '2026-01-14 09:00:00', 20),
(6, '2026-01-14 11:00:00', 18),
(6, '2026-01-14 13:00:00', 20),
(6, '2026-01-14 15:00:00', 18),
(6, '2026-01-14 17:00:00', 20),
(6, '2026-01-15 07:00:00', 18),
(6, '2026-01-15 09:00:00', 20),
(6, '2026-01-15 11:00:00', 18),
(6, '2026-01-15 13:00:00', 20),
(6, '2026-01-15 15:00:00', 18),
(6, '2026-01-15 17:00:00', 20),

-- Space 7: Padel Arena Porto
(7, '2026-02-01 19:00:00', 8),
(7, '2026-02-02 20:30:00', 8),
(7, '2026-02-03 18:30:00', 8),
(7, '2026-02-03 19:00:00', 8),
(7, '2026-02-04 19:30:00', 8),
(7, '2026-02-04 20:30:00', 8),
(7, '2026-02-05 20:00:00', 8),
(7, '2026-02-06 18:00:00', 8),
(7, '2026-02-07 19:00:00', 8),
(7, '2026-02-08 20:30:00', 8),
(7, '2026-02-09 18:30:00', 8),
(7, '2026-02-10 19:30:00', 8),

-- Space 8: Indoor Swimming Complex
(8, '2026-02-01 07:30:00', 30),
(8, '2026-02-02 07:30:00', 25),
(8, '2026-02-02 15:00:00', 25),
(8, '2026-02-03 08:00:00', 30),
(8, '2026-02-04 16:00:00', 25),
(8, '2026-02-05 07:00:00', 30),
(8, '2026-02-06 15:00:00', 25),
(8, '2026-02-06 15:30:00', 25),
(8, '2026-02-07 08:30:00', 30),
(8, '2026-02-08 14:30:00', 25),
(8, '2026-02-09 07:30:00', 30),
(8, '2026-02-10 15:00:00', 25),

-- Space 9: Climbing Zone Campanhã
(9, '2026-02-01 18:00:00', 15),
(9, '2026-02-02 10:00:00', 12),
(9, '2026-02-03 19:00:00', 15),
(9, '2026-02-04 11:00:00', 12),
(9, '2026-02-05 18:00:00', 15),
(9, '2026-02-05 18:30:00', 15),
(9, '2026-02-06 10:30:00', 12),
(9, '2026-02-07 19:30:00', 15),
(9, '2026-02-08 09:30:00', 12),
(9, '2026-02-08 10:00:00', 12),
(9, '2026-02-09 18:00:00', 15),
(9, '2026-02-10 10:00:00', 12),

-- Space 10: Hockey Arena Norte
(10, '2026-02-01 19:30:00', 22),
(10, '2026-02-02 21:00:00', 20),
(10, '2026-02-03 20:00:00', 22),
(10, '2026-02-04 19:00:00', 20),
(10, '2026-02-05 20:30:00', 22),
(10, '2026-02-06 21:30:00', 20),
(10, '2026-02-07 19:30:00', 22),
(10, '2026-02-08 20:00:00', 20),
(10, '2026-02-09 19:30:00', 22),
(10, '2026-02-09 21:00:00', 22),
(10, '2026-02-10 19:30:00', 20),
(10, '2026-02-11 21:00:00', 20),

-- Space 11: Riverside Running Track
(11, '2025-12-19 06:00:00', 35),
(11, '2025-12-19 06:30:00', 40),
(11, '2025-12-19 07:00:00', 35),
(11, '2025-12-19 07:30:00', 40),
(11, '2025-12-19 08:00:00', 35),
(11, '2025-12-19 08:30:00', 40),
(11, '2025-12-19 09:00:00', 35),
(11, '2025-12-19 09:30:00', 40),
(11, '2025-12-19 10:00:00', 35),
(11, '2025-12-19 10:30:00', 40),
(11, '2025-12-19 11:00:00', 35),
(11, '2025-12-19 11:30:00', 40),
(11, '2025-12-19 12:00:00', 35),
(11, '2025-12-19 12:30:00', 40),
(11, '2025-12-19 13:00:00', 35),
(11, '2025-12-19 13:30:00', 40),
(11, '2025-12-19 14:00:00', 35),
(11, '2025-12-19 14:30:00', 40),
(11, '2025-12-19 15:00:00', 35),
(11, '2025-12-19 15:30:00', 40),
(11, '2025-12-19 16:00:00', 35),
(11, '2025-12-19 16:30:00', 40),
(11, '2025-12-19 17:00:00', 35),
(11, '2025-12-19 17:30:00', 40),
(11, '2025-12-19 18:00:00', 35),
(11, '2025-12-19 18:30:00', 40),
(11, '2025-12-19 19:00:00', 35),
(11, '2025-12-19 19:30:00', 40),
(11, '2025-12-19 20:00:00', 35),
(11, '2025-12-19 20:30:00', 40),
(11, '2025-12-20 06:00:00', 35),
(11, '2025-12-20 06:30:00', 40),
(11, '2025-12-20 07:00:00', 35),
(11, '2025-12-20 07:30:00', 40),
(11, '2025-12-20 08:00:00', 35),
(11, '2025-12-20 08:30:00', 40),
(11, '2025-12-20 09:00:00', 35),
(11, '2025-12-20 09:30:00', 40),
(11, '2025-12-20 10:00:00', 35),
(11, '2025-12-20 10:30:00', 40),
(11, '2025-12-20 11:00:00', 35),
(11, '2025-12-20 11:30:00', 40),
(11, '2025-12-20 12:00:00', 35),
(11, '2025-12-20 12:30:00', 40),
(11, '2025-12-20 13:00:00', 35),
(11, '2025-12-20 13:30:00', 40),
(11, '2025-12-20 14:00:00', 35),
(11, '2025-12-20 14:30:00', 40),
(11, '2025-12-20 15:00:00', 35),
(11, '2025-12-20 15:30:00', 40),
(11, '2025-12-20 16:00:00', 35),
(11, '2025-12-20 16:30:00', 40),
(11, '2025-12-20 17:00:00', 35),
(11, '2025-12-20 17:30:00', 40),
(11, '2025-12-20 18:00:00', 35),
(11, '2025-12-20 18:30:00', 40),
(11, '2025-12-20 19:00:00', 35),
(11, '2025-12-20 19:30:00', 40),
(11, '2025-12-20 20:00:00', 35),
(11, '2025-12-20 20:30:00', 40),
(11, '2025-12-21 06:00:00', 35),
(11, '2025-12-21 06:30:00', 40),
(11, '2025-12-21 07:00:00', 35),
(11, '2025-12-21 07:30:00', 40),
(11, '2025-12-21 08:00:00', 35),
(11, '2025-12-21 08:30:00', 40),
(11, '2025-12-21 09:00:00', 35),
(11, '2025-12-21 09:30:00', 40),
(11, '2025-12-21 10:00:00', 35),
(11, '2025-12-21 10:30:00', 40),
(11, '2025-12-21 11:00:00', 35),
(11, '2025-12-21 11:30:00', 40),
(11, '2025-12-21 12:00:00', 35),
(11, '2025-12-21 12:30:00', 40),
(11, '2025-12-21 13:00:00', 35),
(11, '2025-12-21 13:30:00', 40),
(11, '2025-12-21 14:00:00', 35),
(11, '2025-12-21 14:30:00', 40),
(11, '2025-12-21 15:00:00', 35),
(11, '2025-12-21 15:30:00', 40),
(11, '2025-12-21 16:00:00', 35),
(11, '2025-12-21 16:30:00', 40),
(11, '2025-12-21 17:00:00', 35),
(11, '2025-12-21 17:30:00', 40),
(11, '2025-12-21 18:00:00', 35),
(11, '2025-12-21 18:30:00', 40),
(11, '2025-12-21 19:00:00', 35),
(11, '2025-12-21 19:30:00', 40),
(11, '2025-12-21 20:00:00', 35),
(11, '2025-12-21 20:30:00', 40),
(11, '2025-12-22 06:00:00', 35),
(11, '2025-12-22 06:30:00', 40),
(11, '2025-12-22 07:00:00', 35),
(11, '2025-12-22 07:30:00', 40),
(11, '2025-12-22 08:00:00', 35),
(11, '2025-12-22 08:30:00', 40),
(11, '2025-12-22 09:00:00', 35),
(11, '2025-12-22 09:30:00', 40),
(11, '2025-12-22 10:00:00', 35),
(11, '2025-12-22 10:30:00', 40),
(11, '2025-12-22 11:00:00', 35),
(11, '2025-12-22 11:30:00', 40),
(11, '2025-12-22 12:00:00', 35),
(11, '2025-12-22 12:30:00', 40),
(11, '2025-12-22 13:00:00', 35),
(11, '2025-12-22 13:30:00', 40),
(11, '2025-12-22 14:00:00', 35),
(11, '2025-12-22 14:30:00', 40),
(11, '2025-12-22 15:00:00', 35),
(11, '2025-12-22 15:30:00', 40),
(11, '2025-12-22 16:00:00', 35),
(11, '2025-12-22 16:30:00', 40),
(11, '2025-12-22 17:00:00', 35),
(11, '2025-12-22 17:30:00', 40),
(11, '2025-12-22 18:00:00', 35),
(11, '2025-12-22 18:30:00', 40),
(11, '2025-12-22 19:00:00', 35),
(11, '2025-12-22 19:30:00', 40),
(11, '2025-12-22 20:00:00', 35),
(11, '2025-12-22 20:30:00', 40),
(11, '2025-12-23 06:00:00', 35),
(11, '2025-12-23 06:30:00', 40),
(11, '2025-12-23 07:00:00', 35),
(11, '2025-12-23 07:30:00', 40),
(11, '2025-12-23 08:00:00', 35),
(11, '2025-12-23 08:30:00', 40),
(11, '2025-12-23 09:00:00', 35),
(11, '2025-12-23 09:30:00', 40),
(11, '2025-12-23 10:00:00', 35),
(11, '2025-12-23 10:30:00', 40),
(11, '2025-12-23 11:00:00', 35),
(11, '2025-12-23 11:30:00', 40),
(11, '2025-12-23 12:00:00', 35),
(11, '2025-12-23 12:30:00', 40),
(11, '2025-12-23 13:00:00', 35),
(11, '2025-12-23 13:30:00', 40),
(11, '2025-12-23 14:00:00', 35),
(11, '2025-12-23 14:30:00', 40),
(11, '2025-12-23 15:00:00', 35),
(11, '2025-12-23 15:30:00', 40),
(11, '2025-12-23 16:00:00', 35),
(11, '2025-12-23 16:30:00', 40),
(11, '2025-12-23 17:00:00', 35),
(11, '2025-12-23 17:30:00', 40),
(11, '2025-12-23 18:00:00', 35),
(11, '2025-12-23 18:30:00', 40),
(11, '2025-12-23 19:00:00', 35),
(11, '2025-12-23 19:30:00', 40),
(11, '2025-12-23 20:00:00', 35),
(11, '2025-12-23 20:30:00', 40),
(11, '2025-12-24 06:00:00', 35),
(11, '2025-12-24 06:30:00', 40),
(11, '2025-12-24 07:00:00', 35),
(11, '2025-12-24 07:30:00', 40),
(11, '2025-12-24 08:00:00', 35),
(11, '2025-12-24 08:30:00', 40),
(11, '2025-12-24 09:00:00', 35),
(11, '2025-12-24 09:30:00', 40),
(11, '2025-12-24 10:00:00', 35),
(11, '2025-12-24 10:30:00', 40),
(11, '2025-12-24 11:00:00', 35),
(11, '2025-12-24 11:30:00', 40),
(11, '2025-12-24 12:00:00', 35),
(11, '2025-12-24 12:30:00', 40),
(11, '2025-12-24 13:00:00', 35),
(11, '2025-12-24 13:30:00', 40),
(11, '2025-12-24 14:00:00', 35),
(11, '2025-12-24 14:30:00', 40),
(11, '2025-12-24 15:00:00', 35),
(11, '2025-12-24 15:30:00', 40),
(11, '2025-12-24 16:00:00', 35),
(11, '2025-12-24 16:30:00', 40),
(11, '2025-12-24 17:00:00', 35),
(11, '2025-12-24 17:30:00', 40),
(11, '2025-12-24 18:00:00', 35),
(11, '2025-12-24 18:30:00', 40),
(11, '2025-12-24 19:00:00', 35),
(11, '2025-12-24 19:30:00', 40),
(11, '2025-12-24 20:00:00', 35),
(11, '2025-12-24 20:30:00', 40),
(11, '2025-12-25 06:00:00', 35),
(11, '2025-12-25 06:30:00', 40),
(11, '2025-12-25 07:00:00', 35),
(11, '2025-12-25 07:30:00', 40),
(11, '2025-12-25 08:00:00', 35),
(11, '2025-12-25 08:30:00', 40),
(11, '2025-12-25 09:00:00', 35),
(11, '2025-12-25 09:30:00', 40),
(11, '2025-12-25 10:00:00', 35),
(11, '2025-12-25 10:30:00', 40),
(11, '2025-12-25 11:00:00', 35),
(11, '2025-12-25 11:30:00', 40),
(11, '2025-12-25 12:00:00', 35),
(11, '2025-12-25 12:30:00', 40),
(11, '2025-12-25 13:00:00', 35),
(11, '2025-12-25 13:30:00', 40),
(11, '2025-12-25 14:00:00', 35),
(11, '2025-12-25 14:30:00', 40),
(11, '2025-12-25 15:00:00', 35),
(11, '2025-12-25 15:30:00', 40),
(11, '2025-12-25 16:00:00', 35),
(11, '2025-12-25 16:30:00', 40),
(11, '2025-12-25 17:00:00', 35),
(11, '2025-12-25 17:30:00', 40),
(11, '2025-12-25 18:00:00', 35),
(11, '2025-12-25 18:30:00', 40),
(11, '2025-12-25 19:00:00', 35),
(11, '2025-12-25 19:30:00', 40),
(11, '2025-12-25 20:00:00', 35),
(11, '2025-12-25 20:30:00', 40),
(11, '2025-12-26 06:00:00', 35),
(11, '2025-12-26 06:30:00', 40),
(11, '2025-12-26 07:00:00', 35),
(11, '2025-12-26 07:30:00', 40),
(11, '2025-12-26 08:00:00', 35),
(11, '2025-12-26 08:30:00', 40),
(11, '2025-12-26 09:00:00', 35),
(11, '2025-12-26 09:30:00', 40),
(11, '2025-12-26 10:00:00', 35),
(11, '2025-12-26 10:30:00', 40),
(11, '2025-12-26 11:00:00', 35),
(11, '2025-12-26 11:30:00', 40),
(11, '2025-12-26 12:00:00', 35),
(11, '2025-12-26 12:30:00', 40),
(11, '2025-12-26 13:00:00', 35),
(11, '2025-12-26 13:30:00', 40),
(11, '2025-12-26 14:00:00', 35),
(11, '2025-12-26 14:30:00', 40),
(11, '2025-12-26 15:00:00', 35),
(11, '2025-12-26 15:30:00', 40),
(11, '2025-12-26 16:00:00', 35),
(11, '2025-12-26 16:30:00', 40),
(11, '2025-12-26 17:00:00', 35),
(11, '2025-12-26 17:30:00', 40),
(11, '2025-12-26 18:00:00', 35),
(11, '2025-12-26 18:30:00', 40),
(11, '2025-12-26 19:00:00', 35),
(11, '2025-12-26 19:30:00', 40),
(11, '2025-12-26 20:00:00', 35),
(11, '2025-12-26 20:30:00', 40),
(11, '2025-12-27 06:00:00', 35),
(11, '2025-12-27 06:30:00', 40),
(11, '2025-12-27 07:00:00', 35),
(11, '2025-12-27 07:30:00', 40),
(11, '2025-12-27 08:00:00', 35),
(11, '2025-12-27 08:30:00', 40),
(11, '2025-12-27 09:00:00', 35),
(11, '2025-12-27 09:30:00', 40),
(11, '2025-12-27 10:00:00', 35),
(11, '2025-12-27 10:30:00', 40),
(11, '2025-12-27 11:00:00', 35),
(11, '2025-12-27 11:30:00', 40),
(11, '2025-12-27 12:00:00', 35),
(11, '2025-12-27 12:30:00', 40),
(11, '2025-12-27 13:00:00', 35),
(11, '2025-12-27 13:30:00', 40),
(11, '2025-12-27 14:00:00', 35),
(11, '2025-12-27 14:30:00', 40),
(11, '2025-12-27 15:00:00', 35),
(11, '2025-12-27 15:30:00', 40),
(11, '2025-12-27 16:00:00', 35),
(11, '2025-12-27 16:30:00', 40),
(11, '2025-12-27 17:00:00', 35),
(11, '2025-12-27 17:30:00', 40),
(11, '2025-12-27 18:00:00', 35),
(11, '2025-12-27 18:30:00', 40),
(11, '2025-12-27 19:00:00', 35),
(11, '2025-12-27 19:30:00', 40),
(11, '2025-12-27 20:00:00', 35),
(11, '2025-12-27 20:30:00', 40),
(11, '2025-12-28 06:00:00', 35),
(11, '2025-12-28 06:30:00', 40),
(11, '2025-12-28 07:00:00', 35),
(11, '2025-12-28 07:30:00', 40),
(11, '2025-12-28 08:00:00', 35),
(11, '2025-12-28 08:30:00', 40),
(11, '2025-12-28 09:00:00', 35),
(11, '2025-12-28 09:30:00', 40),
(11, '2025-12-28 10:00:00', 35),
(11, '2025-12-28 10:30:00', 40),
(11, '2025-12-28 11:00:00', 35),
(11, '2025-12-28 11:30:00', 40),
(11, '2025-12-28 12:00:00', 35),
(11, '2025-12-28 12:30:00', 40),
(11, '2025-12-28 13:00:00', 35),
(11, '2025-12-28 13:30:00', 40),
(11, '2025-12-28 14:00:00', 35),
(11, '2025-12-28 14:30:00', 40),
(11, '2025-12-28 15:00:00', 35),
(11, '2025-12-28 15:30:00', 40),
(11, '2025-12-28 16:00:00', 35),
(11, '2025-12-28 16:30:00', 40),
(11, '2025-12-28 17:00:00', 35),
(11, '2025-12-28 17:30:00', 40),
(11, '2025-12-28 18:00:00', 35),
(11, '2025-12-28 18:30:00', 40),
(11, '2025-12-28 19:00:00', 35),
(11, '2025-12-28 19:30:00', 40),
(11, '2025-12-28 20:00:00', 35),
(11, '2025-12-28 20:30:00', 40),
(11, '2025-12-29 06:00:00', 35),
(11, '2025-12-29 06:30:00', 40),
(11, '2025-12-29 07:00:00', 35),
(11, '2025-12-29 07:30:00', 40),
(11, '2025-12-29 08:00:00', 35),
(11, '2025-12-29 08:30:00', 40),
(11, '2025-12-29 09:00:00', 35),
(11, '2025-12-29 09:30:00', 40),
(11, '2025-12-29 10:00:00', 35),
(11, '2025-12-29 10:30:00', 40),
(11, '2025-12-29 11:00:00', 35),
(11, '2025-12-29 11:30:00', 40),
(11, '2025-12-29 12:00:00', 35),
(11, '2025-12-29 12:30:00', 40),
(11, '2025-12-29 13:00:00', 35),
(11, '2025-12-29 13:30:00', 40),
(11, '2025-12-29 14:00:00', 35),
(11, '2025-12-29 14:30:00', 40),
(11, '2025-12-29 15:00:00', 35),
(11, '2025-12-29 15:30:00', 40),
(11, '2025-12-29 16:00:00', 35),
(11, '2025-12-29 16:30:00', 40),
(11, '2025-12-29 17:00:00', 35),
(11, '2025-12-29 17:30:00', 40),
(11, '2025-12-29 18:00:00', 35),
(11, '2025-12-29 18:30:00', 40),
(11, '2025-12-29 19:00:00', 35),
(11, '2025-12-29 19:30:00', 40),
(11, '2025-12-29 20:00:00', 35),
(11, '2025-12-29 20:30:00', 40),
(11, '2025-12-30 06:00:00', 35),
(11, '2025-12-30 06:30:00', 40),
(11, '2025-12-30 07:00:00', 35),
(11, '2025-12-30 07:30:00', 40),
(11, '2025-12-30 08:00:00', 35),
(11, '2025-12-30 08:30:00', 40),
(11, '2025-12-30 09:00:00', 35),
(11, '2025-12-30 09:30:00', 40),
(11, '2025-12-30 10:00:00', 35),
(11, '2025-12-30 10:30:00', 40),
(11, '2025-12-30 11:00:00', 35),
(11, '2025-12-30 11:30:00', 40),
(11, '2025-12-30 12:00:00', 35),
(11, '2025-12-30 12:30:00', 40),
(11, '2025-12-30 13:00:00', 35),
(11, '2025-12-30 13:30:00', 40),
(11, '2025-12-30 14:00:00', 35),
(11, '2025-12-30 14:30:00', 40),
(11, '2025-12-30 15:00:00', 35),
(11, '2025-12-30 15:30:00', 40),
(11, '2025-12-30 16:00:00', 35),
(11, '2025-12-30 16:30:00', 40),
(11, '2025-12-30 17:00:00', 35),
(11, '2025-12-30 17:30:00', 40),
(11, '2025-12-30 18:00:00', 35),
(11, '2025-12-30 18:30:00', 40),
(11, '2025-12-30 19:00:00', 35),
(11, '2025-12-30 19:30:00', 40),
(11, '2025-12-30 20:00:00', 35),
(11, '2025-12-30 20:30:00', 40),
(11, '2025-12-31 06:00:00', 35),
(11, '2025-12-31 06:30:00', 40),
(11, '2025-12-31 07:00:00', 35),
(11, '2025-12-31 07:30:00', 40),
(11, '2025-12-31 08:00:00', 35),
(11, '2025-12-31 08:30:00', 40),
(11, '2025-12-31 09:00:00', 35),
(11, '2025-12-31 09:30:00', 40),
(11, '2025-12-31 10:00:00', 35),
(11, '2025-12-31 10:30:00', 40),
(11, '2025-12-31 11:00:00', 35),
(11, '2025-12-31 11:30:00', 40),
(11, '2025-12-31 12:00:00', 35),
(11, '2025-12-31 12:30:00', 40),
(11, '2025-12-31 13:00:00', 35),
(11, '2025-12-31 13:30:00', 40),
(11, '2025-12-31 14:00:00', 35),
(11, '2025-12-31 14:30:00', 40),
(11, '2025-12-31 15:00:00', 35),
(11, '2025-12-31 15:30:00', 40),
(11, '2025-12-31 16:00:00', 35),
(11, '2025-12-31 16:30:00', 40),
(11, '2025-12-31 17:00:00', 35),
(11, '2025-12-31 17:30:00', 40),
(11, '2025-12-31 18:00:00', 35),
(11, '2025-12-31 18:30:00', 40),
(11, '2025-12-31 19:00:00', 35),
(11, '2025-12-31 19:30:00', 40),
(11, '2025-12-31 20:00:00', 35),
(11, '2025-12-31 20:30:00', 40),
(11, '2026-01-01 06:00:00', 35),
(11, '2026-01-01 06:30:00', 40),
(11, '2026-01-01 07:00:00', 35),
(11, '2026-01-01 07:30:00', 40),
(11, '2026-01-01 08:00:00', 35),
(11, '2026-01-01 08:30:00', 40),
(11, '2026-01-01 09:00:00', 35),
(11, '2026-01-01 09:30:00', 40),
(11, '2026-01-01 10:00:00', 35),
(11, '2026-01-01 10:30:00', 40),
(11, '2026-01-01 11:00:00', 35),
(11, '2026-01-01 11:30:00', 40),
(11, '2026-01-01 12:00:00', 35),
(11, '2026-01-01 12:30:00', 40),
(11, '2026-01-01 13:00:00', 35),
(11, '2026-01-01 13:30:00', 40),
(11, '2026-01-01 14:00:00', 35),
(11, '2026-01-01 14:30:00', 40),
(11, '2026-01-01 15:00:00', 35),
(11, '2026-01-01 15:30:00', 40),
(11, '2026-01-01 16:00:00', 35),
(11, '2026-01-01 16:30:00', 40),
(11, '2026-01-01 17:00:00', 35),
(11, '2026-01-01 17:30:00', 40),
(11, '2026-01-01 18:00:00', 35),
(11, '2026-01-01 18:30:00', 40),
(11, '2026-01-01 19:00:00', 35),
(11, '2026-01-01 19:30:00', 40),
(11, '2026-01-01 20:00:00', 35),
(11, '2026-01-01 20:30:00', 40),
(11, '2026-01-02 06:00:00', 35),
(11, '2026-01-02 06:30:00', 40),
(11, '2026-01-02 07:00:00', 35),
(11, '2026-01-02 07:30:00', 40),
(11, '2026-01-02 08:00:00', 35),
(11, '2026-01-02 08:30:00', 40),
(11, '2026-01-02 09:00:00', 35),
(11, '2026-01-02 09:30:00', 40),
(11, '2026-01-02 10:00:00', 35),
(11, '2026-01-02 10:30:00', 40),
(11, '2026-01-02 11:00:00', 35),
(11, '2026-01-02 11:30:00', 40),
(11, '2026-01-02 12:00:00', 35),
(11, '2026-01-02 12:30:00', 40),
(11, '2026-01-02 13:00:00', 35),
(11, '2026-01-02 13:30:00', 40),
(11, '2026-01-02 14:00:00', 35),
(11, '2026-01-02 14:30:00', 40),
(11, '2026-01-02 15:00:00', 35),
(11, '2026-01-02 15:30:00', 40),
(11, '2026-01-02 16:00:00', 35),
(11, '2026-01-02 16:30:00', 40),
(11, '2026-01-02 17:00:00', 35),
(11, '2026-01-02 17:30:00', 40),
(11, '2026-01-02 18:00:00', 35),
(11, '2026-01-02 18:30:00', 40),
(11, '2026-01-02 19:00:00', 35),
(11, '2026-01-02 19:30:00', 40),
(11, '2026-01-02 20:00:00', 35),
(11, '2026-01-02 20:30:00', 40),
(11, '2026-01-03 06:00:00', 35),
(11, '2026-01-03 06:30:00', 40),
(11, '2026-01-03 07:00:00', 35),
(11, '2026-01-03 07:30:00', 40),
(11, '2026-01-03 08:00:00', 35),
(11, '2026-01-03 08:30:00', 40),
(11, '2026-01-03 09:00:00', 35),
(11, '2026-01-03 09:30:00', 40),
(11, '2026-01-03 10:00:00', 35),
(11, '2026-01-03 10:30:00', 40),
(11, '2026-01-03 11:00:00', 35),
(11, '2026-01-03 11:30:00', 40),
(11, '2026-01-03 12:00:00', 35),
(11, '2026-01-03 12:30:00', 40),
(11, '2026-01-03 13:00:00', 35),
(11, '2026-01-03 13:30:00', 40),
(11, '2026-01-03 14:00:00', 35),
(11, '2026-01-03 14:30:00', 40),
(11, '2026-01-03 15:00:00', 35),
(11, '2026-01-03 15:30:00', 40),
(11, '2026-01-03 16:00:00', 35),
(11, '2026-01-03 16:30:00', 40),
(11, '2026-01-03 17:00:00', 35),
(11, '2026-01-03 17:30:00', 40),
(11, '2026-01-03 18:00:00', 35),
(11, '2026-01-03 18:30:00', 40),
(11, '2026-01-03 19:00:00', 35),
(11, '2026-01-03 19:30:00', 40),
(11, '2026-01-03 20:00:00', 35),
(11, '2026-01-03 20:30:00', 40),
(11, '2026-01-04 06:00:00', 35),
(11, '2026-01-04 06:30:00', 40),
(11, '2026-01-04 07:00:00', 35),
(11, '2026-01-04 07:30:00', 40),
(11, '2026-01-04 08:00:00', 35),
(11, '2026-01-04 08:30:00', 40),
(11, '2026-01-04 09:00:00', 35),
(11, '2026-01-04 09:30:00', 40),
(11, '2026-01-04 10:00:00', 35),
(11, '2026-01-04 10:30:00', 40),
(11, '2026-01-04 11:00:00', 35),
(11, '2026-01-04 11:30:00', 40),
(11, '2026-01-04 12:00:00', 35),
(11, '2026-01-04 12:30:00', 40),
(11, '2026-01-04 13:00:00', 35),
(11, '2026-01-04 13:30:00', 40),
(11, '2026-01-04 14:00:00', 35),
(11, '2026-01-04 14:30:00', 40),
(11, '2026-01-04 15:00:00', 35),
(11, '2026-01-04 15:30:00', 40),
(11, '2026-01-04 16:00:00', 35),
(11, '2026-01-04 16:30:00', 40),
(11, '2026-01-04 17:00:00', 35),
(11, '2026-01-04 17:30:00', 40),
(11, '2026-01-04 18:00:00', 35),
(11, '2026-01-04 18:30:00', 40),
(11, '2026-01-04 19:00:00', 35),
(11, '2026-01-04 19:30:00', 40),
(11, '2026-01-04 20:00:00', 35),
(11, '2026-01-04 20:30:00', 40),
(11, '2026-01-05 06:00:00', 35),
(11, '2026-01-05 06:30:00', 40),
(11, '2026-01-05 07:00:00', 35),
(11, '2026-01-05 07:30:00', 40),
(11, '2026-01-05 08:00:00', 35),
(11, '2026-01-05 08:30:00', 40),
(11, '2026-01-05 09:00:00', 35),
(11, '2026-01-05 09:30:00', 40),
(11, '2026-01-05 10:00:00', 35),
(11, '2026-01-05 10:30:00', 40),
(11, '2026-01-05 11:00:00', 35),
(11, '2026-01-05 11:30:00', 40),
(11, '2026-01-05 12:00:00', 35),
(11, '2026-01-05 12:30:00', 40),
(11, '2026-01-05 13:00:00', 35),
(11, '2026-01-05 13:30:00', 40),
(11, '2026-01-05 14:00:00', 35),
(11, '2026-01-05 14:30:00', 40),
(11, '2026-01-05 15:00:00', 35),
(11, '2026-01-05 15:30:00', 40),
(11, '2026-01-05 16:00:00', 35),
(11, '2026-01-05 16:30:00', 40),
(11, '2026-01-05 17:00:00', 35),
(11, '2026-01-05 17:30:00', 40),
(11, '2026-01-05 18:00:00', 35),
(11, '2026-01-05 18:30:00', 40),
(11, '2026-01-05 19:00:00', 35),
(11, '2026-01-05 19:30:00', 40),
(11, '2026-01-05 20:00:00', 35),
(11, '2026-01-05 20:30:00', 40),
(11, '2026-01-06 06:00:00', 35),
(11, '2026-01-06 06:30:00', 40),
(11, '2026-01-06 07:00:00', 35),
(11, '2026-01-06 07:30:00', 40),
(11, '2026-01-06 08:00:00', 35),
(11, '2026-01-06 08:30:00', 40),
(11, '2026-01-06 09:00:00', 35),
(11, '2026-01-06 09:30:00', 40),
(11, '2026-01-06 10:00:00', 35),
(11, '2026-01-06 10:30:00', 40),
(11, '2026-01-06 11:00:00', 35),
(11, '2026-01-06 11:30:00', 40),
(11, '2026-01-06 12:00:00', 35),
(11, '2026-01-06 12:30:00', 40),
(11, '2026-01-06 13:00:00', 35),
(11, '2026-01-06 13:30:00', 40),
(11, '2026-01-06 14:00:00', 35),
(11, '2026-01-06 14:30:00', 40),
(11, '2026-01-06 15:00:00', 35),
(11, '2026-01-06 15:30:00', 40),
(11, '2026-01-06 16:00:00', 35),
(11, '2026-01-06 16:30:00', 40),
(11, '2026-01-06 17:00:00', 35),
(11, '2026-01-06 17:30:00', 40),
(11, '2026-01-06 18:00:00', 35),
(11, '2026-01-06 18:30:00', 40),
(11, '2026-01-06 19:00:00', 35),
(11, '2026-01-06 19:30:00', 40),
(11, '2026-01-06 20:00:00', 35),
(11, '2026-01-06 20:30:00', 40),
(11, '2026-01-07 06:00:00', 35),
(11, '2026-01-07 06:30:00', 40),
(11, '2026-01-07 07:00:00', 35),
(11, '2026-01-07 07:30:00', 40),
(11, '2026-01-07 08:00:00', 35),
(11, '2026-01-07 08:30:00', 40),
(11, '2026-01-07 09:00:00', 35),
(11, '2026-01-07 09:30:00', 40),
(11, '2026-01-07 10:00:00', 35),
(11, '2026-01-07 10:30:00', 40),
(11, '2026-01-07 11:00:00', 35),
(11, '2026-01-07 11:30:00', 40),
(11, '2026-01-07 12:00:00', 35),
(11, '2026-01-07 12:30:00', 40),
(11, '2026-01-07 13:00:00', 35),
(11, '2026-01-07 13:30:00', 40),
(11, '2026-01-07 14:00:00', 35),
(11, '2026-01-07 14:30:00', 40),
(11, '2026-01-07 15:00:00', 35),
(11, '2026-01-07 15:30:00', 40),
(11, '2026-01-07 16:00:00', 35),
(11, '2026-01-07 16:30:00', 40),
(11, '2026-01-07 17:00:00', 35),
(11, '2026-01-07 17:30:00', 40),
(11, '2026-01-07 18:00:00', 35),
(11, '2026-01-07 18:30:00', 40),
(11, '2026-01-07 19:00:00', 35),
(11, '2026-01-07 19:30:00', 40),
(11, '2026-01-07 20:00:00', 35),
(11, '2026-01-07 20:30:00', 40),
(11, '2026-01-08 06:00:00', 35),
(11, '2026-01-08 06:30:00', 40),
(11, '2026-01-08 07:00:00', 35),
(11, '2026-01-08 07:30:00', 40),
(11, '2026-01-08 08:00:00', 35),
(11, '2026-01-08 08:30:00', 40),
(11, '2026-01-08 09:00:00', 35),
(11, '2026-01-08 09:30:00', 40),
(11, '2026-01-08 10:00:00', 35),
(11, '2026-01-08 10:30:00', 40),
(11, '2026-01-08 11:00:00', 35),
(11, '2026-01-08 11:30:00', 40),
(11, '2026-01-08 12:00:00', 35),
(11, '2026-01-08 12:30:00', 40),
(11, '2026-01-08 13:00:00', 35),
(11, '2026-01-08 13:30:00', 40),
(11, '2026-01-08 14:00:00', 35),
(11, '2026-01-08 14:30:00', 40),
(11, '2026-01-08 15:00:00', 35),
(11, '2026-01-08 15:30:00', 40),
(11, '2026-01-08 16:00:00', 35),
(11, '2026-01-08 16:30:00', 40),
(11, '2026-01-08 17:00:00', 35),
(11, '2026-01-08 17:30:00', 40),
(11, '2026-01-08 18:00:00', 35),
(11, '2026-01-08 18:30:00', 40),
(11, '2026-01-08 19:00:00', 35),
(11, '2026-01-08 19:30:00', 40),
(11, '2026-01-08 20:00:00', 35),
(11, '2026-01-08 20:30:00', 40),
(11, '2026-01-09 06:00:00', 35),
(11, '2026-01-09 06:30:00', 40),
(11, '2026-01-09 07:00:00', 35),
(11, '2026-01-09 07:30:00', 40),
(11, '2026-01-09 08:00:00', 35),
(11, '2026-01-09 08:30:00', 40),
(11, '2026-01-09 09:00:00', 35),
(11, '2026-01-09 09:30:00', 40),
(11, '2026-01-09 10:00:00', 35),
(11, '2026-01-09 10:30:00', 40),
(11, '2026-01-09 11:00:00', 35),
(11, '2026-01-09 11:30:00', 40),
(11, '2026-01-09 12:00:00', 35),
(11, '2026-01-09 12:30:00', 40),
(11, '2026-01-09 13:00:00', 35),
(11, '2026-01-09 13:30:00', 40),
(11, '2026-01-09 14:00:00', 35),
(11, '2026-01-09 14:30:00', 40),
(11, '2026-01-09 15:00:00', 35),
(11, '2026-01-09 15:30:00', 40),
(11, '2026-01-09 16:00:00', 35),
(11, '2026-01-09 16:30:00', 40),
(11, '2026-01-09 17:00:00', 35),
(11, '2026-01-09 17:30:00', 40),
(11, '2026-01-09 18:00:00', 35),
(11, '2026-01-09 18:30:00', 40),
(11, '2026-01-09 19:00:00', 35),
(11, '2026-01-09 19:30:00', 40),
(11, '2026-01-09 20:00:00', 35),
(11, '2026-01-09 20:30:00', 40),
(11, '2026-01-10 06:00:00', 35),
(11, '2026-01-10 06:30:00', 40),
(11, '2026-01-10 07:00:00', 35),
(11, '2026-01-10 07:30:00', 40),
(11, '2026-01-10 08:00:00', 35),
(11, '2026-01-10 08:30:00', 40),
(11, '2026-01-10 09:00:00', 35),
(11, '2026-01-10 09:30:00', 40),
(11, '2026-01-10 10:00:00', 35),
(11, '2026-01-10 10:30:00', 40),
(11, '2026-01-10 11:00:00', 35),
(11, '2026-01-10 11:30:00', 40),
(11, '2026-01-10 12:00:00', 35),
(11, '2026-01-10 12:30:00', 40),
(11, '2026-01-10 13:00:00', 35),
(11, '2026-01-10 13:30:00', 40),
(11, '2026-01-10 14:00:00', 35),
(11, '2026-01-10 14:30:00', 40),
(11, '2026-01-10 15:00:00', 35),
(11, '2026-01-10 15:30:00', 40),
(11, '2026-01-10 16:00:00', 35),
(11, '2026-01-10 16:30:00', 40),
(11, '2026-01-10 17:00:00', 35),
(11, '2026-01-10 17:30:00', 40),
(11, '2026-01-10 18:00:00', 35),
(11, '2026-01-10 18:30:00', 40),
(11, '2026-01-10 19:00:00', 35),
(11, '2026-01-10 19:30:00', 40),
(11, '2026-01-10 20:00:00', 35),
(11, '2026-01-10 20:30:00', 40),
(11, '2026-01-11 06:00:00', 35),
(11, '2026-01-11 06:30:00', 40),
(11, '2026-01-11 07:00:00', 35),
(11, '2026-01-11 07:30:00', 40),
(11, '2026-01-11 08:00:00', 35),
(11, '2026-01-11 08:30:00', 40),
(11, '2026-01-11 09:00:00', 35),
(11, '2026-01-11 09:30:00', 40),
(11, '2026-01-11 10:00:00', 35),
(11, '2026-01-11 10:30:00', 40),
(11, '2026-01-11 11:00:00', 35),
(11, '2026-01-11 11:30:00', 40),
(11, '2026-01-11 12:00:00', 35),
(11, '2026-01-11 12:30:00', 40),
(11, '2026-01-11 13:00:00', 35),
(11, '2026-01-11 13:30:00', 40),
(11, '2026-01-11 14:00:00', 35),
(11, '2026-01-11 14:30:00', 40),
(11, '2026-01-11 15:00:00', 35),
(11, '2026-01-11 15:30:00', 40),
(11, '2026-01-11 16:00:00', 35),
(11, '2026-01-11 16:30:00', 40),
(11, '2026-01-11 17:00:00', 35),
(11, '2026-01-11 17:30:00', 40),
(11, '2026-01-11 18:00:00', 35),
(11, '2026-01-11 18:30:00', 40),
(11, '2026-01-11 19:00:00', 35),
(11, '2026-01-11 19:30:00', 40),
(11, '2026-01-11 20:00:00', 35),
(11, '2026-01-11 20:30:00', 40),
(11, '2026-01-12 06:00:00', 35),
(11, '2026-01-12 06:30:00', 40),
(11, '2026-01-12 07:00:00', 35),
(11, '2026-01-12 07:30:00', 40),
(11, '2026-01-12 08:00:00', 35),
(11, '2026-01-12 08:30:00', 40),
(11, '2026-01-12 09:00:00', 35),
(11, '2026-01-12 09:30:00', 40),
(11, '2026-01-12 10:00:00', 35),
(11, '2026-01-12 10:30:00', 40),
(11, '2026-01-12 11:00:00', 35),
(11, '2026-01-12 11:30:00', 40),
(11, '2026-01-12 12:00:00', 35),
(11, '2026-01-12 12:30:00', 40),
(11, '2026-01-12 13:00:00', 35),
(11, '2026-01-12 13:30:00', 40),
(11, '2026-01-12 14:00:00', 35),
(11, '2026-01-12 14:30:00', 40),
(11, '2026-01-12 15:00:00', 35),
(11, '2026-01-12 15:30:00', 40),
(11, '2026-01-12 16:00:00', 35),
(11, '2026-01-12 16:30:00', 40),
(11, '2026-01-12 17:00:00', 35),
(11, '2026-01-12 17:30:00', 40),
(11, '2026-01-12 18:00:00', 35),
(11, '2026-01-12 18:30:00', 40),
(11, '2026-01-12 19:00:00', 35),
(11, '2026-01-12 19:30:00', 40),
(11, '2026-01-12 20:00:00', 35),
(11, '2026-01-12 20:30:00', 40),
(11, '2026-01-13 06:00:00', 35),
(11, '2026-01-13 06:30:00', 40),
(11, '2026-01-13 07:00:00', 35),
(11, '2026-01-13 07:30:00', 40),
(11, '2026-01-13 08:00:00', 35),
(11, '2026-01-13 08:30:00', 40),
(11, '2026-01-13 09:00:00', 35),
(11, '2026-01-13 09:30:00', 40),
(11, '2026-01-13 10:00:00', 35),
(11, '2026-01-13 10:30:00', 40),
(11, '2026-01-13 11:00:00', 35),
(11, '2026-01-13 11:30:00', 40),
(11, '2026-01-13 12:00:00', 35),
(11, '2026-01-13 12:30:00', 40),
(11, '2026-01-13 13:00:00', 35),
(11, '2026-01-13 13:30:00', 40),
(11, '2026-01-13 14:00:00', 35),
(11, '2026-01-13 14:30:00', 40),
(11, '2026-01-13 15:00:00', 35),
(11, '2026-01-13 15:30:00', 40),
(11, '2026-01-13 16:00:00', 35),
(11, '2026-01-13 16:30:00', 40),
(11, '2026-01-13 17:00:00', 35),
(11, '2026-01-13 17:30:00', 40),
(11, '2026-01-13 18:00:00', 35),
(11, '2026-01-13 18:30:00', 40),
(11, '2026-01-13 19:00:00', 35),
(11, '2026-01-13 19:30:00', 40),
(11, '2026-01-13 20:00:00', 35),
(11, '2026-01-13 20:30:00', 40),
(11, '2026-01-14 06:00:00', 35),
(11, '2026-01-14 06:30:00', 40),
(11, '2026-01-14 07:00:00', 35),
(11, '2026-01-14 07:30:00', 40),
(11, '2026-01-14 08:00:00', 35),
(11, '2026-01-14 08:30:00', 40),
(11, '2026-01-14 09:00:00', 35),
(11, '2026-01-14 09:30:00', 40),
(11, '2026-01-14 10:00:00', 35),
(11, '2026-01-14 10:30:00', 40),
(11, '2026-01-14 11:00:00', 35),
(11, '2026-01-14 11:30:00', 40),
(11, '2026-01-14 12:00:00', 35),
(11, '2026-01-14 12:30:00', 40),
(11, '2026-01-14 13:00:00', 35),
(11, '2026-01-14 13:30:00', 40),
(11, '2026-01-14 14:00:00', 35),
(11, '2026-01-14 14:30:00', 40),
(11, '2026-01-14 15:00:00', 35),
(11, '2026-01-14 15:30:00', 40),
(11, '2026-01-14 16:00:00', 35),
(11, '2026-01-14 16:30:00', 40),
(11, '2026-01-14 17:00:00', 35),
(11, '2026-01-14 17:30:00', 40),
(11, '2026-01-14 18:00:00', 35),
(11, '2026-01-14 18:30:00', 40),
(11, '2026-01-14 19:00:00', 35),
(11, '2026-01-14 19:30:00', 40),
(11, '2026-01-14 20:00:00', 35),
(11, '2026-01-14 20:30:00', 40),
(11, '2026-01-15 06:00:00', 35),
(11, '2026-01-15 06:30:00', 40),
(11, '2026-01-15 07:00:00', 35),
(11, '2026-01-15 07:30:00', 40),
(11, '2026-01-15 08:00:00', 35),
(11, '2026-01-15 08:30:00', 40),
(11, '2026-01-15 09:00:00', 35),
(11, '2026-01-15 09:30:00', 40),
(11, '2026-01-15 10:00:00', 35),
(11, '2026-01-15 10:30:00', 40),
(11, '2026-01-15 11:00:00', 35),
(11, '2026-01-15 11:30:00', 40),
(11, '2026-01-15 12:00:00', 35),
(11, '2026-01-15 12:30:00', 40),
(11, '2026-01-15 13:00:00', 35),
(11, '2026-01-15 13:30:00', 40),
(11, '2026-01-15 14:00:00', 35),
(11, '2026-01-15 14:30:00', 40),
(11, '2026-01-15 15:00:00', 35),
(11, '2026-01-15 15:30:00', 40),
(11, '2026-01-15 16:00:00', 35),
(11, '2026-01-15 16:30:00', 40),
(11, '2026-01-15 17:00:00', 35),
(11, '2026-01-15 17:30:00', 40),
(11, '2026-01-15 18:00:00', 35),
(11, '2026-01-15 18:30:00', 40),
(11, '2026-01-15 19:00:00', 35),
(11, '2026-01-15 19:30:00', 40),
(11, '2026-01-15 20:00:00', 35),
(11, '2026-01-15 20:30:00', 40),

-- Space 1: Extra schedules for Feb 3rd (30 min intervals)
(1, '2026-02-03 15:00:00', 14),
(1, '2026-02-03 15:30:00', 14),
(1, '2026-02-03 16:00:00', 14),
(1, '2026-02-03 16:30:00', 14),
(1, '2026-02-03 17:00:00', 14),
(1, '2026-02-03 17:30:00', 14),
(1, '2026-02-03 18:00:00', 1),
(1, '2026-02-03 18:30:00', 14),
(1, '2026-02-03 19:00:00', 14),
(1, '2026-02-03 19:30:00', 14),

-- Space 2: Extra schedule
(2, '2026-01-01 17:30:00', 4);

INSERT INTO
    payment (
        value,
        is_discounted,
        is_accepted,
        payment_provider_ref,
        time_stamp
    )
VALUES (
        25.50,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-01 14:30:00'
    ),
    (
        28.00,
        FALSE,
        TRUE,
        'MB Way',
        '2026-01-02 10:05:00'
    ),
    (
        12.00,
        TRUE,
        TRUE,
        'MB Way',
        '2026-01-02 11:05:00'
    ),
    (
        18.90,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-04 09:25:00'
    ),
    (
        21.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-04 12:20:00'
    ),
    (
        30.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-05 08:10:00'
    ),
    (
        10.00,
        TRUE,
        TRUE,
        'Paypal',
        '2026-01-06 13:50:00'
    ),
    (
        10.00,
        TRUE,
        FALSE,
        'MB Way',
        '2026-01-07 14:40:00'
    ),
    (
        45.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-07 18:10:00'
    ),
    (
        20.00,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-08 10:15:00'
    ),
    (
        15.00,
        TRUE,
        FALSE,
        'Paypal',
        '2026-01-08 15:35:00'
    ),
    (
        18.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-09 17:10:00'
    ),
    (
        25.00,
        FALSE,
        TRUE,
        'MB Way',
        '2026-01-09 18:20:00'
    ),
    (
        12.00,
        FALSE,
        FALSE,
        'Paypal',
        '2026-01-03 15:35:00'
    ),
    (
        20.00,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2026-01-05 16:15:00'
    ),
    (
        15.00,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-08 10:05:00'
    ),
    (
        22.50,
        FALSE,
        TRUE,
        'MB Way',
        '2026-01-09 17:05:00'
    ),
    (
        18.00,
        TRUE,
        TRUE,
        'Paypal',
        '2026-01-09 18:20:00'
    ),
    (
        20.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2026-01-01 14:40:00'
    ),
    (
        17.50,
        TRUE,
        TRUE,
        'MB Way',
        '2026-01-01 14:20:00'
    ),
    (
        17.50,
        TRUE,
        TRUE,
        'MB Way',
        '2026-01-01 14:20:00'
    ),
    (
        16.34,
        False,
        True,
        'Paypal',
        '2025-11-27 03:08:56'
    ),
    (
        16.34,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-27 03:24:06'
    ),
    (
        47.89,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        12.1,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        14.89,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-10 03:24:06'
    ),
    (
        19.46,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        30.23,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-11 03:24:06'
    ),
    (
        20.48,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-04 03:24:06'
    ),
    (
        18.43,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-10 03:24:06'
    ),
    (
        49.26,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-19 03:24:06'
    ),
    (
        14.93,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        26.45,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        19.38,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        40.96,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        16.22,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-02 03:24:06'
    ),
    (
        37.64,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        18.12,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-15 03:24:06'
    ),
    (
        30.58,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        36.59,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-06 03:24:06'
    ),
    (
        36.76,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        30.05,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        22.25,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        19.51,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        47.79,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-14 03:24:06'
    ),
    (
        17.33,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        22.69,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-28 03:24:06'
    ),
    (
        18.35,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-08 03:24:06'
    ),
    (
        36.66,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-11 03:24:06'
    ),
    (
        33.89,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        30.4,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        44.79,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-27 03:24:06'
    ),
    (
        28.98,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-18 03:24:06'
    ),
    (
        46.54,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-18 03:24:06'
    ),
    (
        21.82,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-15 03:24:06'
    ),
    (
        15.17,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-15 03:24:06'
    ),
    (
        24.31,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-02 03:24:06'
    ),
    (
        33.63,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-07 03:24:06'
    ),
    (
        21.51,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        47.91,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        35.84,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-28 03:24:06'
    ),
    (
        17.05,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-15 03:24:06'
    ),
    (
        11.3,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-30 03:24:06'
    ),
    (
        48.56,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        40.02,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-26 03:24:06'
    ),
    (
        33.95,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        23.17,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        46.48,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        46.48,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-10 03:24:06'
    ),
    (
        32.86,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-25 03:24:06'
    ),
    (
        39.78,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        30.6,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-04 03:24:06'
    ),
    (
        47.86,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        33.33,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-19 03:24:06'
    ),
    (
        36.2,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        40.22,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-25 03:24:06'
    ),
    (
        34.11,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        42.75,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-14 03:24:06'
    ),
    (
        34.0,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-05 03:24:06'
    ),
    (
        17.56,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-24 03:24:06'
    ),
    (
        49.11,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        20.09,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        16.19,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-12 03:24:06'
    ),
    (
        11.54,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        49.05,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-30 03:24:06'
    ),
    (
        17.81,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        13.61,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        10.81,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-03 03:24:06'
    ),
    (
        34.66,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-14 03:24:06'
    ),
    (
        22.44,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        46.17,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-16 03:24:06'
    ),
    (
        32.97,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-05 03:24:06'
    ),
    (
        11.16,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        32.75,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-18 03:24:06'
    ),
    (
        27.07,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-11 03:24:06'
    ),
    (
        35.81,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        25.69,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-18 03:24:06'
    ),
    (
        30.03,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        47.49,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-07 03:24:06'
    ),
    (
        23.02,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        13.54,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        28.09,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        23.46,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        42.58,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-29 03:24:06'
    ),
    (
        23.81,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-15 03:24:06'
    ),
    (
        47.2,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-17 03:24:06'
    ),
    (
        19.99,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-05 03:24:06'
    ),
    (
        18.13,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        32.17,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        38.93,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        18.34,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        29.89,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        38.15,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        38.89,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        30.71,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        49.78,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-01 03:24:06'
    ),
    (
        27.4,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-01 03:24:06'
    ),
    (
        25.09,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        28.75,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-01 03:24:06'
    ),
    (
        46.39,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-07 03:24:06'
    ),
    (
        23.9,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-11 03:24:06'
    ),
    (
        42.68,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        48.29,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        22.97,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-19 03:24:06'
    ),
    (
        25.86,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-29 03:24:06'
    ),
    (
        23.92,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        29.9,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        34.16,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-21 03:24:06'
    ),
    (
        19.98,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-04 03:24:06'
    ),
    (
        48.64,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        12.08,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-02 03:24:06'
    ),
    (
        14.63,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        31.68,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-21 03:24:06'
    ),
    (
        26.03,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        40.08,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-23 03:24:06'
    ),
    (
        15.32,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        16.14,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-04 03:24:06'
    ),
    (
        30.91,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-26 03:24:06'
    ),
    (
        20.22,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        15.65,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-15 03:24:06'
    ),
    (
        13.15,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        23.74,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        22.29,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-10 03:24:06'
    ),
    (
        20.38,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        33.85,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        35.83,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        26.41,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-06 03:24:06'
    ),
    (
        16.47,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        34.21,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-25 03:24:06'
    ),
    (
        32.74,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        46.6,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-11 03:24:06'
    ),
    (
        29.44,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        29.77,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-18 03:24:06'
    ),
    (
        27.6,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        17.82,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        31.15,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-16 03:24:06'
    ),
    (
        35.19,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        36.31,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        27.27,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        37.62,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        23.25,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        12.86,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        11.1,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        10.2,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-04 03:24:06'
    ),
    (
        30.42,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-10 03:24:06'
    ),
    (
        11.49,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        41.16,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        17.22,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-04 03:24:06'
    ),
    (
        16.36,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-27 03:24:06'
    ),
    (
        32.01,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-11 03:24:06'
    ),
    (
        12.71,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        42.53,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        28.29,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-02 03:24:06'
    ),
    (
        49.51,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        32.87,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        15.46,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-11 03:24:06'
    ),
    (
        46.57,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-11 03:24:06'
    ),
    (
        46.3,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-15 03:24:06'
    ),
    (
        23.93,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-18 03:24:06'
    ),
    (
        47.27,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-27 03:24:06'
    ),
    (
        32.74,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-08 03:24:06'
    ),
    (
        38.8,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-21 03:24:06'
    ),
    (
        41.79,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        34.84,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-06 03:24:06'
    ),
    (
        48.16,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        31.33,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        37.65,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-23 03:24:06'
    ),
    (
        28.12,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-19 03:24:06'
    ),
    (
        25.32,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-27 03:24:06'
    ),
    (
        29.23,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-17 03:24:06'
    ),
    (
        10.13,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        15.36,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        17.41,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-27 03:24:06'
    ),
    (
        12.98,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        26.26,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-16 03:24:06'
    ),
    (
        11.56,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-10 03:24:06'
    ),
    (
        10.08,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-16 03:24:06'
    ),
    (
        48.64,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        37.59,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-24 03:24:06'
    ),
    (
        27.68,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-09 03:24:06'
    ),
    (
        40.22,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        29.28,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-26 03:24:06'
    ),
    (
        37.12,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        26.06,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-26 03:24:06'
    ),
    (
        39.18,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        12.27,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-23 03:24:06'
    ),
    (
        28.37,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-22 03:24:06'
    ),
    (
        27.74,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        43.71,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-27 03:24:06'
    ),
    (
        37.42,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        38.25,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        35.66,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        42.06,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-04 03:24:06'
    ),
    (
        19.42,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-02 03:24:06'
    ),
    (
        18.14,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        40.67,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        30.51,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        48.95,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        30.44,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        46.64,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        25.87,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        22.65,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-26 03:24:06'
    ),
    (
        27.97,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        23.53,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        21.79,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-07 03:24:06'
    ),
    (
        42.99,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-20 03:24:06'
    ),
    (
        47.23,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        13.87,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        31.43,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-11 03:24:06'
    ),
    (
        26.84,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        22.75,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-30 03:24:06'
    ),
    (
        35.24,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        31.04,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        20.65,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-04 03:24:06'
    ),
    (
        29.56,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-23 03:24:06'
    ),
    (
        46.0,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        39.09,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-10 03:24:06'
    ),
    (
        38.59,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-21 03:24:06'
    ),
    (
        11.8,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        30.61,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-18 03:24:06'
    ),
    (
        22.3,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-24 03:24:06'
    ),
    (
        44.31,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-11 03:24:06'
    ),
    (
        27.63,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        16.58,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        33.46,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-16 03:24:06'
    ),
    (
        10.85,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-10 03:24:06'
    ),
    (
        18.84,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-11 03:24:06'
    ),
    (
        30.22,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        24.32,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        21.7,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        41.58,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        27.58,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-26 03:24:06'
    ),
    (
        11.18,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        30.95,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-09 03:24:06'
    ),
    (
        39.52,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-24 03:24:06'
    ),
    (
        43.76,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        31.85,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-19 03:24:06'
    ),
    (
        17.39,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-18 03:24:06'
    ),
    (
        35.4,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        26.77,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-02 03:24:06'
    ),
    (
        28.72,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        34.52,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-23 03:24:06'
    ),
    (
        37.63,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        31.96,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-21 03:24:06'
    ),
    (
        32.73,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-23 03:24:06'
    ),
    (
        20.89,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        27.15,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-19 03:24:06'
    ),
    (
        25.9,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        15.67,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        41.85,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-29 03:24:06'
    ),
    (
        46.14,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        25.73,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-26 03:24:06'
    ),
    (
        27.43,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        20.55,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        11.38,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        40.91,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-25 03:24:06'
    ),
    (
        28.32,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        49.07,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        19.31,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        17.3,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        40.8,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        26.64,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-23 03:24:06'
    ),
    (
        30.67,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        32.52,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        33.93,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        12.26,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        27.38,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-15 03:24:06'
    ),
    (
        21.73,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-18 03:24:06'
    ),
    (
        18.31,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-19 03:24:06'
    ),
    (
        28.13,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-11 03:24:06'
    ),
    (
        37.01,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        17.11,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        38.16,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-19 03:24:06'
    ),
    (
        35.71,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-03 03:24:06'
    ),
    (
        33.78,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-01 03:24:06'
    ),
    (
        19.79,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        30.81,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-09 03:24:06'
    ),
    (
        38.39,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        15.03,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-30 03:24:06'
    ),
    (
        16.11,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        27.07,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        30.84,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        47.79,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-09 03:24:06'
    ),
    (
        26.2,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-06 03:24:06'
    ),
    (
        42.02,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-29 03:24:06'
    ),
    (
        23.57,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-23 03:24:06'
    ),
    (
        30.52,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-18 03:24:06'
    ),
    (
        30.82,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        17.5,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-23 03:24:06'
    ),
    (
        36.37,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-21 03:24:06'
    ),
    (
        49.37,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-06 03:24:06'
    ),
    (
        39.43,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-28 03:24:06'
    ),
    (
        32.12,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        34.58,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-03 03:24:06'
    ),
    (
        33.12,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        40.89,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        35.08,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-12 03:24:06'
    ),
    (
        31.93,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        34.51,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-01 03:24:06'
    ),
    (
        32.85,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-27 03:24:06'
    ),
    (
        16.49,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-10 03:24:06'
    ),
    (
        43.4,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        35.71,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        46.22,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-10 03:24:06'
    ),
    (
        11.27,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        44.95,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        42.32,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        29.15,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        17.15,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-19 03:24:06'
    ),
    (
        12.19,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        33.25,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-03 03:24:06'
    ),
    (
        28.65,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        22.54,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-16 03:24:06'
    ),
    (
        25.55,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-21 03:24:06'
    ),
    (
        13.7,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        31.05,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        30.13,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        16.66,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-19 03:24:06'
    ),
    (
        28.11,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-09 03:24:06'
    ),
    (
        24.94,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        15.2,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        18.49,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-12 03:24:06'
    ),
    (
        22.88,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        27.36,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        36.83,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        38.34,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        18.81,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-09 03:24:06'
    ),
    (
        42.78,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-24 03:24:06'
    ),
    (
        10.39,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        32.45,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        13.26,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-06 03:24:06'
    ),
    (
        17.74,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-15 03:24:06'
    ),
    (
        44.23,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-26 03:24:06'
    ),
    (
        20.63,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        25.92,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-21 03:24:06'
    ),
    (
        48.76,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        11.09,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-10 03:24:06'
    ),
    (
        10.22,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-16 03:24:06'
    ),
    (
        17.64,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        35.3,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-26 03:24:06'
    ),
    (
        13.67,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-24 03:24:06'
    ),
    (
        31.88,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        18.35,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-11 03:24:06'
    ),
    (
        39.36,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        15.12,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        46.02,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        14.62,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-07 03:24:06'
    ),
    (
        27.11,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-23 03:24:06'
    ),
    (
        44.27,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-22 03:24:06'
    ),
    (
        23.63,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        33.05,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-10 03:24:06'
    ),
    (
        22.88,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-18 03:24:06'
    ),
    (
        37.8,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        30.46,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        14.57,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-15 03:24:06'
    ),
    (
        36.07,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-19 03:24:06'
    ),
    (
        13.88,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-14 03:24:06'
    ),
    (
        37.31,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        35.51,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        41.53,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-30 03:24:06'
    ),
    (
        28.86,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-08 03:24:06'
    ),
    (
        31.03,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-30 03:24:06'
    ),
    (
        29.57,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-14 03:24:06'
    ),
    (
        27.75,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        13.22,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-08 03:24:06'
    ),
    (
        31.27,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-07 03:24:06'
    ),
    (
        28.21,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        48.01,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-19 03:24:06'
    ),
    (
        46.87,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-23 03:24:06'
    ),
    (
        26.47,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        32.19,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        27.47,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-14 03:24:06'
    ),
    (
        25.07,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-03 03:24:06'
    ),
    (
        19.32,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-19 03:24:06'
    ),
    (
        48.15,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        36.25,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-24 03:24:06'
    ),
    (
        32.55,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-20 03:24:06'
    ),
    (
        42.38,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-18 03:24:06'
    ),
    (
        37.6,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        38.19,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-09 03:24:06'
    ),
    (
        19.37,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-16 03:24:06'
    ),
    (
        24.83,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-12 03:24:06'
    ),
    (
        33.52,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-02 03:24:06'
    ),
    (
        42.21,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-28 03:24:06'
    ),
    (
        17.3,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-20 03:24:06'
    ),
    (
        22.3,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        49.33,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-27 03:24:06'
    ),
    (
        39.61,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-17 03:24:06'
    ),
    (
        37.64,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-19 03:24:06'
    ),
    (
        25.47,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-30 03:24:06'
    ),
    (
        12.19,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        32.16,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        15.35,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        39.07,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        37.22,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-07 03:24:06'
    ),
    (
        33.54,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        28.34,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-19 03:24:06'
    ),
    (
        24.99,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-28 03:24:06'
    ),
    (
        30.15,
        TRUE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-10 03:24:06'
    ),
    (
        26.94,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-16 03:24:06'
    ),
    (
        44.64,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        24.09,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        31.26,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-09 03:24:06'
    ),
    (
        34.97,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-01 03:24:06'
    ),
    (
        16.06,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        10.84,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-23 03:24:06'
    ),
    (
        35.17,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        21.35,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-20 03:24:06'
    ),
    (
        44.22,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-14 03:24:06'
    ),
    (
        35.42,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        16.19,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-11 03:24:06'
    ),
    (
        15.06,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-04 03:24:06'
    ),
    (
        45.73,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-16 03:24:06'
    ),
    (
        46.62,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-08 03:24:06'
    ),
    (
        37.48,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-12 03:24:06'
    ),
    (
        10.79,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        41.69,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        30.67,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        40.25,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-03 03:24:06'
    ),
    (
        21.94,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-10 03:24:06'
    ),
    (
        25.85,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-22 03:24:06'
    ),
    (
        28.55,
        FALSE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        42.57,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-28 03:24:06'
    ),
    (
        27.52,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-30 03:24:06'
    ),
    (
        15.02,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        42.47,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-25 03:24:06'
    ),
    (
        42.53,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-27 03:24:06'
    ),
    (
        26.05,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-11 03:24:06'
    ),
    (
        15.64,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-13 03:24:06'
    ),
    (
        35.03,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-03 03:24:06'
    ),
    (
        40.8,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-16 03:24:06'
    ),
    (
        42.89,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-06 03:24:06'
    ),
    (
        41.51,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-24 03:24:06'
    ),
    (
        14.85,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        49.81,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        11.16,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        16.22,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-06 03:24:06'
    ),
    (
        18.15,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        41.64,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        21.84,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-17 03:24:06'
    ),
    (
        30.31,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-26 03:24:06'
    ),
    (
        20.08,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-08 03:24:06'
    ),
    (
        27.85,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-25 03:24:06'
    ),
    (
        11.51,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-30 03:24:06'
    ),
    (
        40.81,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-23 03:24:06'
    ),
    (
        41.0,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        34.54,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        26.67,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-02 03:24:06'
    ),
    (
        20.55,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        43.94,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-07 03:24:06'
    ),
    (
        41.86,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-18 03:24:06'
    ),
    (
        28.74,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        49.54,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-29 03:24:06'
    ),
    (
        19.17,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-26 03:24:06'
    ),
    (
        26.22,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-15 03:24:06'
    ),
    (
        25.58,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        45.19,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-17 03:24:06'
    ),
    (
        40.88,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        21.68,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        35.21,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-25 03:24:06'
    ),
    (
        18.37,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        45.99,
        TRUE,
        FALSE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        48.79,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-30 03:24:06'
    ),
    (
        12.41,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-06 03:24:06'
    ),
    (
        49.14,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-19 03:24:06'
    ),
    (
        30.46,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-26 03:24:06'
    ),
    (
        17.06,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-05 03:24:06'
    ),
    (
        42.43,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-30 03:24:06'
    ),
    (
        19.65,
        FALSE,
        FALSE,
        'MB Way',
        '2025-12-12 03:24:06'
    ),
    (
        41.8,
        FALSE,
        TRUE,
        'Paypal',
        '2025-12-16 03:24:06'
    ),
    (
        26.01,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-28 03:24:06'
    ),
    (
        28.85,
        FALSE,
        FALSE,
        'Paypal',
        '2025-12-16 03:24:06'
    ),
    (
        15.18,
        TRUE,
        FALSE,
        'Paypal',
        '2025-12-09 03:24:06'
    ),
    (
        25.72,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-10 03:24:06'
    ),
    (
        39.36,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-23 03:24:06'
    ),
    (
        11.97,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-20 03:24:06'
    ),
    (
        14.91,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-27 03:24:06'
    ),
    (
        17.11,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-22 03:24:06'
    ),
    (
        43.27,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-21 03:24:06'
    ),
    (
        23.68,
        FALSE,
        FALSE,
        'MB Way',
        '2025-11-29 03:24:06'
    ),
    (
        15.48,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-24 03:24:06'
    ),
    (
        28.97,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-07 03:24:06'
    ),
    (
        42.37,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-20 03:24:06'
    ),
    (
        43.64,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-08 03:24:06'
    ),
    (
        31.25,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-24 03:24:06'
    ),
    (
        45.09,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-15 03:24:06'
    ),
    (
        30.13,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-21 03:24:06'
    ),
    (
        47.67,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-01 03:24:06'
    ),
    (
        29.73,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-13 03:24:06'
    ),
    (
        17.03,
        TRUE,
        TRUE,
        'MB Way',
        '2025-12-04 03:24:06'
    ),
    (
        26.71,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        34.52,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-27 03:24:06'
    ),
    (
        38.39,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-01 03:24:06'
    ),
    (
        48.27,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-09 03:24:06'
    ),
    (
        48.04,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-13 03:24:06'
    ),
    (
        30.27,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-12-06 03:24:06'
    ),
    (
        27.04,
        TRUE,
        TRUE,
        'Paypal',
        '2025-12-05 03:24:06'
    ),
    (
        26.5,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-23 03:24:06'
    ),
    (
        13.27,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-03 03:24:06'
    ),
    (
        20.52,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-05 03:24:06'
    ),
    (
        44.46,
        FALSE,
        TRUE,
        'MB Way',
        '2025-12-01 03:24:06'
    );

INSERT INTO
    booking (
        space_id,
        customer_id,
        schedule_id,
        booking_created_at,
        is_cancelled,
        number_of_persons,
        total_duration,
        payment_id
    )
VALUES (
        1,
        1,
        1,
        '2026-01-01 14:25:00',
        FALSE,
        2,
        30,
        1
    ),
    (
        1,
        2,
        2,
        '2026-01-02 10:00:00',
        FALSE,
        2,
        30,
        2
    ),
    (
        2,
        3,
        3,
        '2026-01-02 11:00:00',
        FALSE,
        2,
        30,
        3
    ),
    (
        2,
        4,
        4,
        '2026-01-03 15:30:00',
        TRUE,
        2,
        30,
        14
    ),
    (
        3,
        5,
        5,
        '2026-01-04 09:20:00',
        FALSE,
        2,
        30,
        4
    ),
    (
        3,
        6,
        6,
        '2026-01-04 12:10:00',
        FALSE,
        2,
        30,
        5
    ),
    (
        4,
        7,
        7,
        '2026-01-05 08:00:00',
        FALSE,
        2,
        30,
        10
    ),
    (
        4,
        8,
        8,
        '2026-01-05 16:10:00',
        TRUE,
        2,
        30,
        11
    ),
    (
        5,
        9,
        9,
        '2026-01-06 13:45:00',
        FALSE,
        2,
        30,
        7
    ),
    (
        5,
        10,
        10,
        '2026-01-07 14:30:00',
        FALSE,
        2,
        8,
        16
    ),
    (
        6,
        11,
        11,
        '2026-01-07 18:00:00',
        FALSE,
        2,
        30,
        16
    ),
    (
        7,
        12,
        12,
        '2026-01-08 10:00:00',
        FALSE,
        2,
        30,
        16
    ),
    (
        8,
        13,
        13,
        '2026-01-08 15:30:00',
        TRUE,
        2,
        30,
        16
    ),
    (
        9,
        14,
        14,
        '2026-01-09 17:00:00',
        FALSE,
        2,
        30,
        16
    ),
    (
        10,
        15,
        15,
        '2026-01-09 18:15:00',
        FALSE,
        2,
        30,
        18
    ),
    (
        2,
        1,
        132,
        '2026-01-01 14:35:00',
        FALSE,
        2,
        30,
        19
    ),
    (
        3,
        1,
        131,
        '2026-01-01 14:15:00',
        FALSE,
        2,
        30,
        20
    ),
    (
        4,
        1,
        56,
        '2026-01-01 14:15:00',
        TRUE,
        2,
        29,
        21
    ),
    (
        3,
        11,
        201,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        60,
        22
    ),
    (
        3,
        12,
        202,
        '2025-12-17 00:00:00',
        FALSE,
        3,
        60,
        23
    ),
    (
        3,
        14,
        210,
        '2025-12-14 00:00:00',
        TRUE,
        1,
        60,
        24
    ),
    (
        3,
        5,
        203,
        '2025-12-16 00:00:00',
        FALSE,
        1,
        60,
        25
    ),
    (
        3,
        14,
        209,
        '2025-12-14 00:00:00',
        FALSE,
        1,
        60,
        26
    ),
    (
        3,
        15,
        208,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        60,
        27
    ),
    (
        3,
        5,
        212,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        60,
        28
    ),
    (
        3,
        1,
        200,
        '2025-12-17 00:00:00',
        FALSE,
        2,
        60,
        29
    ),
    (
        3,
        7,
        205,
        '2025-12-14 00:00:00',
        FALSE,
        1,
        60,
        30
    ),
    (
        3,
        12,
        215,
        '2025-12-15 00:00:00',
        FALSE,
        2,
        60,
        31
    ),
    (
        3,
        3,
        206,
        '2025-12-14 00:00:00',
        FALSE,
        1,
        60,
        32
    ),
    (
        6,
        6,
        217,
        '2025-12-19 00:00:00',
        FALSE,
        4,
        120,
        33
    ),
    (
        6,
        1,
        219,
        '2025-12-17 00:00:00',
        FALSE,
        3,
        120,
        34
    ),
    (
        6,
        13,
        218,
        '2025-12-19 00:00:00',
        FALSE,
        3,
        120,
        35
    ),
    (
        6,
        5,
        221,
        '2025-12-18 00:00:00',
        TRUE,
        5,
        120,
        36
    ),
    (
        11,
        11,
        235,
        '2025-12-16 00:00:00',
        FALSE,
        1,
        30,
        37
    ),
    (
        11,
        3,
        244,
        '2025-12-19 00:00:00',
        FALSE,
        2,
        30,
        38
    ),
    (
        11,
        14,
        241,
        '2025-12-19 00:00:00',
        FALSE,
        2,
        30,
        39
    ),
    (
        11,
        8,
        245,
        '2025-12-16 00:00:00',
        FALSE,
        1,
        30,
        40
    ),
    (
        11,
        15,
        243,
        '2025-12-14 00:00:00',
        FALSE,
        1,
        30,
        41
    ),
    (
        11,
        9,
        224,
        '2025-12-14 00:00:00',
        FALSE,
        2,
        30,
        42
    ),
    (
        11,
        7,
        237,
        '2025-12-17 00:00:00',
        FALSE,
        1,
        30,
        43
    ),
    (
        11,
        4,
        248,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        30,
        44
    ),
    (
        11,
        7,
        239,
        '2025-12-17 00:00:00',
        FALSE,
        3,
        30,
        45
    ),
    (
        11,
        3,
        230,
        '2025-12-14 00:00:00',
        FALSE,
        2,
        30,
        46
    ),
    (
        11,
        4,
        223,
        '2025-12-19 00:00:00',
        FALSE,
        2,
        30,
        47
    ),
    (
        11,
        1,
        234,
        '2025-12-15 00:00:00',
        FALSE,
        1,
        30,
        48
    ),
    (
        11,
        12,
        240,
        '2025-12-14 00:00:00',
        FALSE,
        2,
        30,
        49
    ),
    (
        11,
        3,
        225,
        '2025-12-14 00:00:00',
        FALSE,
        1,
        30,
        50
    ),
    (
        11,
        5,
        238,
        '2025-12-18 00:00:00',
        FALSE,
        2,
        30,
        51
    ),
    (
        11,
        8,
        236,
        '2025-12-18 00:00:00',
        FALSE,
        2,
        30,
        52
    ),
    (
        11,
        5,
        231,
        '2025-12-17 00:00:00',
        FALSE,
        1,
        30,
        53
    ),
    (
        11,
        2,
        249,
        '2025-12-14 00:00:00',
        FALSE,
        2,
        30,
        54
    ),
    (
        11,
        11,
        228,
        '2025-12-17 00:00:00',
        FALSE,
        3,
        30,
        55
    ),
    (
        11,
        12,
        232,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        30,
        56
    ),
    (
        11,
        7,
        242,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        30,
        57
    ),
    (
        11,
        2,
        250,
        '2025-12-15 00:00:00',
        FALSE,
        2,
        30,
        58
    ),
    (
        11,
        12,
        227,
        '2025-12-19 00:00:00',
        FALSE,
        1,
        30,
        59
    ),
    (
        3,
        4,
        257,
        '2025-12-16 00:00:00',
        TRUE,
        3,
        60,
        60
    ),
    (
        3,
        7,
        266,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        60,
        61
    ),
    (
        3,
        14,
        260,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        60,
        62
    ),
    (
        3,
        15,
        267,
        '2025-12-19 00:00:00',
        FALSE,
        2,
        60,
        63
    ),
    (
        3,
        13,
        261,
        '2025-12-17 00:00:00',
        TRUE,
        3,
        60,
        64
    ),
    (
        3,
        9,
        256,
        '2025-12-15 00:00:00',
        FALSE,
        1,
        60,
        65
    ),
    (
        3,
        9,
        264,
        '2025-12-16 00:00:00',
        FALSE,
        3,
        60,
        66
    ),
    (
        3,
        10,
        259,
        '2025-12-17 00:00:00',
        FALSE,
        1,
        60,
        67
    ),
    (
        3,
        4,
        269,
        '2025-12-18 00:00:00',
        FALSE,
        1,
        60,
        68
    ),
    (
        3,
        5,
        254,
        '2025-12-15 00:00:00',
        FALSE,
        1,
        60,
        69
    ),
    (
        6,
        7,
        274,
        '2025-12-20 00:00:00',
        FALSE,
        3,
        120,
        70
    ),
    (
        6,
        9,
        272,
        '2025-12-17 00:00:00',
        FALSE,
        5,
        120,
        71
    ),
    (
        6,
        8,
        270,
        '2025-12-19 00:00:00',
        FALSE,
        2,
        120,
        72
    ),
    (
        6,
        10,
        275,
        '2025-12-15 00:00:00',
        FALSE,
        6,
        120,
        73
    ),
    (
        11,
        8,
        297,
        '2025-12-15 00:00:00',
        FALSE,
        1,
        30,
        74
    ),
    (
        11,
        11,
        281,
        '2025-12-19 00:00:00',
        FALSE,
        1,
        30,
        75
    ),
    (
        11,
        14,
        296,
        '2025-12-19 00:00:00',
        FALSE,
        2,
        30,
        76
    ),
    (
        11,
        7,
        283,
        '2025-12-20 00:00:00',
        FALSE,
        3,
        30,
        77
    ),
    (
        11,
        9,
        290,
        '2025-12-16 00:00:00',
        FALSE,
        1,
        30,
        78
    ),
    (
        11,
        7,
        279,
        '2025-12-20 00:00:00',
        FALSE,
        3,
        30,
        79
    ),
    (
        11,
        7,
        298,
        '2025-12-16 00:00:00',
        FALSE,
        3,
        30,
        80
    ),
    (
        11,
        11,
        282,
        '2025-12-19 00:00:00',
        FALSE,
        3,
        30,
        81
    ),
    (
        11,
        2,
        278,
        '2025-12-15 00:00:00',
        TRUE,
        1,
        30,
        82
    ),
    (
        11,
        10,
        302,
        '2025-12-18 00:00:00',
        FALSE,
        2,
        30,
        83
    ),
    (
        11,
        1,
        287,
        '2025-12-17 00:00:00',
        FALSE,
        1,
        30,
        84
    ),
    (
        11,
        10,
        293,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        30,
        85
    ),
    (
        11,
        6,
        289,
        '2025-12-16 00:00:00',
        FALSE,
        3,
        30,
        86
    ),
    (
        11,
        3,
        294,
        '2025-12-17 00:00:00',
        FALSE,
        2,
        30,
        87
    ),
    (
        11,
        1,
        285,
        '2025-12-15 00:00:00',
        FALSE,
        1,
        30,
        88
    ),
    (
        11,
        7,
        291,
        '2025-12-20 00:00:00',
        FALSE,
        1,
        30,
        89
    ),
    (
        11,
        12,
        284,
        '2025-12-20 00:00:00',
        TRUE,
        2,
        30,
        90
    ),
    (
        11,
        1,
        292,
        '2025-12-17 00:00:00',
        FALSE,
        1,
        30,
        91
    ),
    (
        11,
        13,
        277,
        '2025-12-17 00:00:00',
        FALSE,
        1,
        30,
        92
    ),
    (
        3,
        10,
        322,
        '2025-12-19 00:00:00',
        FALSE,
        4,
        60,
        93
    ),
    (
        3,
        5,
        310,
        '2025-12-19 00:00:00',
        FALSE,
        3,
        60,
        94
    ),
    (
        3,
        9,
        306,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        60,
        95
    ),
    (
        3,
        2,
        313,
        '2025-12-20 00:00:00',
        FALSE,
        3,
        60,
        96
    ),
    (
        3,
        1,
        312,
        '2025-12-19 00:00:00',
        FALSE,
        4,
        60,
        97
    ),
    (
        3,
        5,
        315,
        '2025-12-21 00:00:00',
        FALSE,
        1,
        60,
        98
    ),
    (
        3,
        14,
        311,
        '2025-12-16 00:00:00',
        FALSE,
        3,
        60,
        99
    ),
    (
        3,
        4,
        318,
        '2025-12-19 00:00:00',
        FALSE,
        4,
        60,
        100
    ),
    (
        3,
        15,
        319,
        '2025-12-16 00:00:00',
        FALSE,
        4,
        60,
        101
    ),
    (
        3,
        2,
        307,
        '2025-12-18 00:00:00',
        FALSE,
        4,
        60,
        102
    ),
    (
        3,
        13,
        321,
        '2025-12-21 00:00:00',
        FALSE,
        3,
        60,
        103
    ),
    (
        3,
        14,
        314,
        '2025-12-20 00:00:00',
        FALSE,
        4,
        60,
        104
    ),
    (
        6,
        11,
        327,
        '2025-12-18 00:00:00',
        FALSE,
        6,
        120,
        105
    ),
    (
        6,
        3,
        326,
        '2025-12-21 00:00:00',
        FALSE,
        4,
        120,
        106
    ),
    (
        6,
        1,
        323,
        '2025-12-21 00:00:00',
        FALSE,
        4,
        120,
        107
    ),
    (
        6,
        6,
        324,
        '2025-12-21 00:00:00',
        FALSE,
        4,
        120,
        108
    ),
    (
        11,
        11,
        350,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        30,
        109
    ),
    (
        11,
        8,
        352,
        '2025-12-21 00:00:00',
        FALSE,
        2,
        30,
        110
    ),
    (
        11,
        3,
        339,
        '2025-12-17 00:00:00',
        FALSE,
        2,
        30,
        111
    ),
    (
        11,
        14,
        337,
        '2025-12-19 00:00:00',
        FALSE,
        1,
        30,
        112
    ),
    (
        11,
        15,
        330,
        '2025-12-20 00:00:00',
        FALSE,
        3,
        30,
        113
    ),
    (
        11,
        15,
        347,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        30,
        114
    ),
    (
        11,
        7,
        357,
        '2025-12-18 00:00:00',
        FALSE,
        2,
        30,
        115
    ),
    (
        11,
        12,
        356,
        '2025-12-21 00:00:00',
        FALSE,
        3,
        30,
        116
    ),
    (
        11,
        9,
        358,
        '2025-12-20 00:00:00',
        FALSE,
        3,
        30,
        117
    ),
    (
        11,
        7,
        332,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        30,
        118
    ),
    (
        11,
        3,
        331,
        '2025-12-16 00:00:00',
        FALSE,
        3,
        30,
        119
    ),
    (
        11,
        2,
        353,
        '2025-12-17 00:00:00',
        FALSE,
        3,
        30,
        120
    ),
    (
        11,
        11,
        344,
        '2025-12-19 00:00:00',
        FALSE,
        1,
        30,
        121
    ),
    (
        11,
        15,
        333,
        '2025-12-20 00:00:00',
        FALSE,
        2,
        30,
        122
    ),
    (
        11,
        5,
        341,
        '2025-12-20 00:00:00',
        FALSE,
        2,
        30,
        123
    ),
    (
        11,
        1,
        335,
        '2025-12-20 00:00:00',
        FALSE,
        1,
        30,
        124
    ),
    (
        11,
        13,
        351,
        '2025-12-21 00:00:00',
        FALSE,
        2,
        30,
        125
    ),
    (
        11,
        10,
        343,
        '2025-12-18 00:00:00',
        FALSE,
        3,
        30,
        126
    ),
    (
        11,
        13,
        338,
        '2025-12-16 00:00:00',
        FALSE,
        1,
        30,
        127
    ),
    (
        11,
        9,
        354,
        '2025-12-16 00:00:00',
        FALSE,
        2,
        30,
        128
    ),
    (
        3,
        10,
        365,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        60,
        129
    ),
    (
        3,
        5,
        361,
        '2025-12-28 00:00:00',
        FALSE,
        1,
        60,
        130
    ),
    (
        3,
        9,
        366,
        '2025-12-31 00:00:00',
        TRUE,
        3,
        60,
        131
    ),
    (
        3,
        11,
        371,
        '2026-01-01 00:00:00',
        FALSE,
        4,
        60,
        132
    ),
    (
        3,
        2,
        374,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        60,
        133
    ),
    (
        3,
        3,
        363,
        '2025-12-30 00:00:00',
        FALSE,
        3,
        60,
        134
    ),
    (
        3,
        10,
        362,
        '2025-12-27 00:00:00',
        FALSE,
        2,
        60,
        135
    ),
    (
        3,
        10,
        364,
        '2025-12-27 00:00:00',
        FALSE,
        2,
        60,
        136
    ),
    (
        6,
        5,
        377,
        '2026-01-01 00:00:00',
        FALSE,
        5,
        120,
        137
    ),
    (
        6,
        11,
        376,
        '2026-01-01 00:00:00',
        FALSE,
        6,
        120,
        138
    ),
    (
        6,
        3,
        380,
        '2025-12-28 00:00:00',
        FALSE,
        2,
        120,
        139
    ),
    (
        11,
        5,
        401,
        '2025-12-31 00:00:00',
        TRUE,
        2,
        30,
        140
    ),
    (
        11,
        3,
        406,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        30,
        141
    ),
    (
        11,
        5,
        387,
        '2025-12-27 00:00:00',
        FALSE,
        3,
        30,
        142
    ),
    (
        11,
        10,
        393,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        30,
        143
    ),
    (
        11,
        6,
        398,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        144
    ),
    (
        11,
        12,
        405,
        '2025-12-30 00:00:00',
        TRUE,
        2,
        30,
        145
    ),
    (
        11,
        2,
        384,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        30,
        146
    ),
    (
        11,
        11,
        389,
        '2025-12-30 00:00:00',
        FALSE,
        1,
        30,
        147
    ),
    (
        11,
        7,
        388,
        '2025-12-27 00:00:00',
        FALSE,
        1,
        30,
        148
    ),
    (
        11,
        6,
        408,
        '2025-12-28 00:00:00',
        FALSE,
        1,
        30,
        149
    ),
    (
        11,
        7,
        404,
        '2025-12-28 00:00:00',
        FALSE,
        1,
        30,
        150
    ),
    (
        11,
        8,
        394,
        '2025-12-30 00:00:00',
        FALSE,
        3,
        30,
        151
    ),
    (
        11,
        9,
        396,
        '2025-12-29 00:00:00',
        FALSE,
        1,
        30,
        152
    ),
    (
        11,
        4,
        390,
        '2025-12-31 00:00:00',
        FALSE,
        1,
        30,
        153
    ),
    (
        11,
        5,
        383,
        '2025-12-30 00:00:00',
        TRUE,
        1,
        30,
        154
    ),
    (
        11,
        7,
        409,
        '2025-12-29 00:00:00',
        FALSE,
        1,
        30,
        155
    ),
    (
        11,
        12,
        402,
        '2025-12-28 00:00:00',
        FALSE,
        1,
        30,
        156
    ),
    (
        3,
        6,
        423,
        '2025-12-29 00:00:00',
        FALSE,
        1,
        60,
        157
    ),
    (
        3,
        2,
        422,
        '2025-12-28 00:00:00',
        TRUE,
        1,
        60,
        158
    ),
    (
        3,
        13,
        421,
        '2025-12-28 00:00:00',
        FALSE,
        1,
        60,
        159
    ),
    (
        3,
        12,
        425,
        '2025-12-29 00:00:00',
        FALSE,
        3,
        60,
        160
    ),
    (
        3,
        9,
        416,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        60,
        161
    ),
    (
        3,
        6,
        420,
        '2025-12-31 00:00:00',
        FALSE,
        1,
        60,
        162
    ),
    (
        6,
        9,
        432,
        '2025-12-31 00:00:00',
        FALSE,
        5,
        120,
        163
    ),
    (
        6,
        1,
        431,
        '2026-01-02 00:00:00',
        TRUE,
        5,
        120,
        164
    ),
    (
        6,
        3,
        433,
        '2025-12-31 00:00:00',
        FALSE,
        6,
        120,
        165
    ),
    (
        11,
        8,
        461,
        '2026-01-01 00:00:00',
        TRUE,
        1,
        30,
        166
    ),
    (
        11,
        12,
        455,
        '2025-12-30 00:00:00',
        FALSE,
        3,
        30,
        167
    ),
    (
        11,
        9,
        439,
        '2025-12-29 00:00:00',
        FALSE,
        3,
        30,
        168
    ),
    (
        11,
        15,
        447,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        30,
        169
    ),
    (
        11,
        14,
        441,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        30,
        170
    ),
    (
        11,
        4,
        457,
        '2025-12-31 00:00:00',
        FALSE,
        3,
        30,
        171
    ),
    (
        11,
        1,
        459,
        '2026-01-02 00:00:00',
        TRUE,
        2,
        30,
        172
    ),
    (
        11,
        15,
        452,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        173
    ),
    (
        11,
        1,
        462,
        '2025-12-31 00:00:00',
        FALSE,
        2,
        30,
        174
    ),
    (
        11,
        5,
        456,
        '2025-12-31 00:00:00',
        FALSE,
        1,
        30,
        175
    ),
    (
        11,
        8,
        448,
        '2025-12-31 00:00:00',
        FALSE,
        1,
        30,
        176
    ),
    (
        11,
        13,
        451,
        '2025-12-30 00:00:00',
        FALSE,
        1,
        30,
        177
    ),
    (
        11,
        14,
        454,
        '2026-01-01 00:00:00',
        TRUE,
        2,
        30,
        178
    ),
    (
        11,
        14,
        445,
        '2025-12-31 00:00:00',
        FALSE,
        3,
        30,
        179
    ),
    (
        3,
        10,
        469,
        '2026-01-02 00:00:00',
        TRUE,
        4,
        60,
        180
    ),
    (
        3,
        3,
        481,
        '2025-12-30 00:00:00',
        TRUE,
        2,
        60,
        181
    ),
    (
        3,
        9,
        471,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        60,
        182
    ),
    (
        3,
        10,
        478,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        60,
        183
    ),
    (
        3,
        1,
        472,
        '2026-01-02 00:00:00',
        FALSE,
        4,
        60,
        184
    ),
    (
        3,
        13,
        477,
        '2025-12-31 00:00:00',
        FALSE,
        1,
        60,
        185
    ),
    (
        3,
        8,
        473,
        '2025-12-30 00:00:00',
        TRUE,
        3,
        60,
        186
    ),
    (
        3,
        13,
        470,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        60,
        187
    ),
    (
        6,
        3,
        487,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        120,
        188
    ),
    (
        6,
        6,
        482,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        120,
        189
    ),
    (
        6,
        12,
        483,
        '2025-12-29 00:00:00',
        FALSE,
        5,
        120,
        190
    ),
    (
        11,
        15,
        508,
        '2025-12-30 00:00:00',
        FALSE,
        1,
        30,
        191
    ),
    (
        11,
        2,
        505,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        192
    ),
    (
        11,
        14,
        495,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        30,
        193
    ),
    (
        11,
        6,
        494,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        194
    ),
    (
        11,
        6,
        516,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        30,
        195
    ),
    (
        11,
        9,
        503,
        '2025-12-30 00:00:00',
        TRUE,
        1,
        30,
        196
    ),
    (
        11,
        5,
        489,
        '2025-12-29 00:00:00',
        TRUE,
        2,
        30,
        197
    ),
    (
        11,
        15,
        493,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        198
    ),
    (
        11,
        6,
        499,
        '2025-12-30 00:00:00',
        FALSE,
        3,
        30,
        199
    ),
    (
        11,
        6,
        501,
        '2025-12-29 00:00:00',
        FALSE,
        1,
        30,
        200
    ),
    (
        11,
        14,
        507,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        201
    ),
    (
        11,
        13,
        490,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        30,
        202
    ),
    (
        11,
        12,
        496,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        30,
        203
    ),
    (
        3,
        1,
        522,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        60,
        204
    ),
    (
        3,
        5,
        519,
        '2026-01-02 00:00:00',
        FALSE,
        4,
        60,
        205
    ),
    (
        3,
        8,
        521,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        60,
        206
    ),
    (
        3,
        3,
        530,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        60,
        207
    ),
    (
        3,
        2,
        525,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        60,
        208
    ),
    (
        3,
        2,
        524,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        60,
        209
    ),
    (
        3,
        7,
        533,
        '2025-12-31 00:00:00',
        FALSE,
        1,
        60,
        210
    ),
    (
        3,
        12,
        526,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        60,
        211
    ),
    (
        3,
        15,
        520,
        '2026-01-03 00:00:00',
        FALSE,
        4,
        60,
        212
    ),
    (
        6,
        13,
        539,
        '2026-01-01 00:00:00',
        FALSE,
        6,
        120,
        213
    ),
    (
        6,
        14,
        538,
        '2026-01-04 00:00:00',
        FALSE,
        6,
        120,
        214
    ),
    (
        6,
        3,
        540,
        '2026-01-01 00:00:00',
        FALSE,
        4,
        120,
        215
    ),
    (
        11,
        5,
        570,
        '2025-12-31 00:00:00',
        FALSE,
        2,
        30,
        216
    ),
    (
        11,
        8,
        543,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        30,
        217
    ),
    (
        11,
        4,
        542,
        '2025-12-30 00:00:00',
        FALSE,
        1,
        30,
        218
    ),
    (
        11,
        4,
        544,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        30,
        219
    ),
    (
        11,
        11,
        548,
        '2025-12-31 00:00:00',
        TRUE,
        1,
        30,
        220
    ),
    (
        11,
        2,
        553,
        '2026-01-04 00:00:00',
        TRUE,
        2,
        30,
        221
    ),
    (
        11,
        11,
        546,
        '2025-12-30 00:00:00',
        FALSE,
        3,
        30,
        222
    ),
    (
        11,
        4,
        547,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        223
    ),
    (
        11,
        1,
        565,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        30,
        224
    ),
    (
        11,
        12,
        559,
        '2026-01-01 00:00:00',
        TRUE,
        1,
        30,
        225
    ),
    (
        11,
        10,
        554,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        30,
        226
    ),
    (
        11,
        3,
        549,
        '2025-12-30 00:00:00',
        FALSE,
        1,
        30,
        227
    ),
    (
        11,
        10,
        550,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        30,
        228
    ),
    (
        11,
        15,
        563,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        229
    ),
    (
        11,
        14,
        568,
        '2026-01-02 00:00:00',
        FALSE,
        1,
        30,
        230
    ),
    (
        11,
        5,
        555,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        231
    ),
    (
        11,
        13,
        551,
        '2025-12-30 00:00:00',
        FALSE,
        2,
        30,
        232
    ),
    (
        3,
        11,
        571,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        60,
        233
    ),
    (
        3,
        3,
        577,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        60,
        234
    ),
    (
        3,
        9,
        582,
        '2026-01-01 00:00:00',
        TRUE,
        3,
        60,
        235
    ),
    (
        3,
        9,
        573,
        '2026-01-02 00:00:00',
        FALSE,
        4,
        60,
        236
    ),
    (
        3,
        3,
        585,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        60,
        237
    ),
    (
        3,
        9,
        583,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        60,
        238
    ),
    (
        3,
        2,
        576,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        60,
        239
    ),
    (
        3,
        2,
        581,
        '2026-01-05 00:00:00',
        FALSE,
        4,
        60,
        240
    ),
    (
        3,
        3,
        579,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        60,
        241
    ),
    (
        6,
        15,
        590,
        '2026-01-04 00:00:00',
        FALSE,
        6,
        120,
        242
    ),
    (
        6,
        9,
        588,
        '2026-01-03 00:00:00',
        TRUE,
        4,
        120,
        243
    ),
    (
        6,
        15,
        593,
        '2026-01-01 00:00:00',
        TRUE,
        6,
        120,
        244
    ),
    (
        11,
        13,
        607,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        30,
        245
    ),
    (
        11,
        8,
        622,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        246
    ),
    (
        11,
        3,
        613,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        247
    ),
    (
        11,
        10,
        601,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        248
    ),
    (
        11,
        6,
        610,
        '2026-01-02 00:00:00',
        FALSE,
        1,
        30,
        249
    ),
    (
        11,
        12,
        612,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        250
    ),
    (
        11,
        15,
        615,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        251
    ),
    (
        11,
        6,
        616,
        '2025-12-31 00:00:00',
        FALSE,
        3,
        30,
        252
    ),
    (
        11,
        1,
        605,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        253
    ),
    (
        11,
        6,
        594,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        254
    ),
    (
        11,
        15,
        597,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        30,
        255
    ),
    (
        11,
        8,
        614,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        256
    ),
    (
        11,
        15,
        608,
        '2026-01-04 00:00:00',
        FALSE,
        1,
        30,
        257
    ),
    (
        11,
        11,
        617,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        30,
        258
    ),
    (
        11,
        12,
        595,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        259
    ),
    (
        11,
        9,
        596,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        30,
        260
    ),
    (
        11,
        6,
        604,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        261
    ),
    (
        3,
        8,
        630,
        '2026-01-02 00:00:00',
        FALSE,
        4,
        60,
        262
    ),
    (
        3,
        15,
        633,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        60,
        263
    ),
    (
        3,
        7,
        632,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        60,
        264
    ),
    (
        3,
        7,
        637,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        60,
        265
    ),
    (
        3,
        11,
        634,
        '2026-01-01 00:00:00',
        FALSE,
        3,
        60,
        266
    ),
    (
        3,
        11,
        638,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        60,
        267
    ),
    (
        3,
        15,
        624,
        '2026-01-06 00:00:00',
        TRUE,
        4,
        60,
        268
    ),
    (
        6,
        5,
        646,
        '2026-01-05 00:00:00',
        FALSE,
        6,
        120,
        269
    ),
    (
        6,
        12,
        642,
        '2026-01-02 00:00:00',
        FALSE,
        5,
        120,
        270
    ),
    (
        6,
        7,
        644,
        '2026-01-01 00:00:00',
        FALSE,
        5,
        120,
        271
    ),
    (
        11,
        11,
        667,
        '2026-01-03 00:00:00',
        TRUE,
        2,
        30,
        272
    ),
    (
        11,
        9,
        663,
        '2026-01-01 00:00:00',
        FALSE,
        2,
        30,
        273
    ),
    (
        11,
        7,
        649,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        30,
        274
    ),
    (
        11,
        4,
        657,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        275
    ),
    (
        11,
        14,
        653,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        276
    ),
    (
        11,
        4,
        650,
        '2026-01-01 00:00:00',
        FALSE,
        1,
        30,
        277
    ),
    (
        11,
        4,
        671,
        '2026-01-04 00:00:00',
        TRUE,
        2,
        30,
        278
    ),
    (
        11,
        2,
        674,
        '2026-01-02 00:00:00',
        FALSE,
        1,
        30,
        279
    ),
    (
        11,
        2,
        660,
        '2026-01-06 00:00:00',
        FALSE,
        2,
        30,
        280
    ),
    (
        11,
        3,
        668,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        281
    ),
    (
        11,
        6,
        673,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        282
    ),
    (
        11,
        9,
        664,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        30,
        283
    ),
    (
        11,
        7,
        672,
        '2026-01-06 00:00:00',
        FALSE,
        2,
        30,
        284
    ),
    (
        11,
        8,
        655,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        30,
        285
    ),
    (
        11,
        5,
        662,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        30,
        286
    ),
    (
        11,
        8,
        661,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        30,
        287
    ),
    (
        11,
        1,
        648,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        30,
        288
    ),
    (
        3,
        10,
        689,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        60,
        289
    ),
    (
        3,
        8,
        680,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        60,
        290
    ),
    (
        3,
        14,
        685,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        60,
        291
    ),
    (
        3,
        7,
        692,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        60,
        292
    ),
    (
        3,
        1,
        681,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        60,
        293
    ),
    (
        3,
        11,
        682,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        60,
        294
    ),
    (
        3,
        6,
        690,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        60,
        295
    ),
    (
        6,
        5,
        695,
        '2026-01-04 00:00:00',
        FALSE,
        4,
        120,
        296
    ),
    (
        6,
        3,
        698,
        '2026-01-05 00:00:00',
        FALSE,
        4,
        120,
        297
    ),
    (
        6,
        9,
        694,
        '2026-01-06 00:00:00',
        FALSE,
        2,
        120,
        298
    ),
    (
        11,
        6,
        718,
        '2026-01-03 00:00:00',
        TRUE,
        1,
        30,
        299
    ),
    (
        11,
        10,
        722,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        30,
        300
    ),
    (
        11,
        2,
        717,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        301
    ),
    (
        11,
        13,
        704,
        '2026-01-06 00:00:00',
        FALSE,
        1,
        30,
        302
    ),
    (
        11,
        12,
        706,
        '2026-01-03 00:00:00',
        FALSE,
        2,
        30,
        303
    ),
    (
        11,
        4,
        705,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        304
    ),
    (
        11,
        7,
        701,
        '2026-01-02 00:00:00',
        FALSE,
        3,
        30,
        305
    ),
    (
        11,
        1,
        726,
        '2026-01-05 00:00:00',
        FALSE,
        3,
        30,
        306
    ),
    (
        11,
        3,
        702,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        307
    ),
    (
        11,
        3,
        700,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        30,
        308
    ),
    (
        11,
        10,
        708,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        309
    ),
    (
        11,
        10,
        710,
        '2026-01-05 00:00:00',
        FALSE,
        3,
        30,
        310
    ),
    (
        11,
        3,
        712,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        30,
        311
    ),
    (
        11,
        1,
        724,
        '2026-01-02 00:00:00',
        FALSE,
        1,
        30,
        312
    ),
    (
        11,
        15,
        727,
        '2026-01-02 00:00:00',
        FALSE,
        2,
        30,
        313
    ),
    (
        11,
        10,
        703,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        314
    ),
    (
        3,
        4,
        734,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        60,
        315
    ),
    (
        3,
        13,
        741,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        60,
        316
    ),
    (
        3,
        10,
        733,
        '2026-01-06 00:00:00',
        TRUE,
        4,
        60,
        317
    ),
    (
        3,
        7,
        731,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        60,
        318
    ),
    (
        3,
        11,
        743,
        '2026-01-07 00:00:00',
        FALSE,
        1,
        60,
        319
    ),
    (
        3,
        15,
        735,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        60,
        320
    ),
    (
        3,
        7,
        740,
        '2026-01-04 00:00:00',
        FALSE,
        1,
        60,
        321
    ),
    (
        3,
        8,
        745,
        '2026-01-07 00:00:00',
        FALSE,
        1,
        60,
        322
    ),
    (
        3,
        10,
        736,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        60,
        323
    ),
    (
        6,
        7,
        749,
        '2026-01-08 00:00:00',
        FALSE,
        6,
        120,
        324
    ),
    (
        6,
        4,
        747,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        120,
        325
    ),
    (
        11,
        12,
        780,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        326
    ),
    (
        11,
        3,
        764,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        327
    ),
    (
        11,
        11,
        762,
        '2026-01-03 00:00:00',
        FALSE,
        1,
        30,
        328
    ),
    (
        11,
        11,
        774,
        '2026-01-07 00:00:00',
        FALSE,
        1,
        30,
        329
    ),
    (
        11,
        14,
        772,
        '2026-01-03 00:00:00',
        FALSE,
        3,
        30,
        330
    ),
    (
        11,
        3,
        763,
        '2026-01-07 00:00:00',
        FALSE,
        1,
        30,
        331
    ),
    (
        11,
        4,
        757,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        30,
        332
    ),
    (
        11,
        5,
        768,
        '2026-01-04 00:00:00',
        FALSE,
        3,
        30,
        333
    ),
    (
        11,
        2,
        779,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        30,
        334
    ),
    (
        11,
        5,
        760,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        30,
        335
    ),
    (
        11,
        14,
        781,
        '2026-01-04 00:00:00',
        TRUE,
        3,
        30,
        336
    ),
    (
        11,
        4,
        756,
        '2026-01-04 00:00:00',
        FALSE,
        2,
        30,
        337
    ),
    (
        11,
        13,
        758,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        338
    ),
    (
        11,
        11,
        755,
        '2026-01-06 00:00:00',
        FALSE,
        1,
        30,
        339
    ),
    (
        3,
        7,
        784,
        '2026-01-07 00:00:00',
        FALSE,
        2,
        60,
        340
    ),
    (
        3,
        15,
        785,
        '2026-01-09 00:00:00',
        FALSE,
        2,
        60,
        341
    ),
    (
        3,
        11,
        799,
        '2026-01-09 00:00:00',
        FALSE,
        1,
        60,
        342
    ),
    (
        3,
        6,
        793,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        60,
        343
    ),
    (
        3,
        6,
        791,
        '2026-01-05 00:00:00',
        FALSE,
        4,
        60,
        344
    ),
    (
        3,
        15,
        794,
        '2026-01-07 00:00:00',
        FALSE,
        4,
        60,
        345
    ),
    (
        3,
        10,
        798,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        60,
        346
    ),
    (
        3,
        12,
        792,
        '2026-01-09 00:00:00',
        FALSE,
        1,
        60,
        347
    ),
    (
        3,
        14,
        786,
        '2026-01-05 00:00:00',
        FALSE,
        3,
        60,
        348
    ),
    (
        6,
        3,
        801,
        '2026-01-07 00:00:00',
        FALSE,
        2,
        120,
        349
    ),
    (
        6,
        4,
        804,
        '2026-01-07 00:00:00',
        FALSE,
        6,
        120,
        350
    ),
    (
        11,
        7,
        823,
        '2026-01-06 00:00:00',
        FALSE,
        1,
        30,
        351
    ),
    (
        11,
        7,
        826,
        '2026-01-07 00:00:00',
        FALSE,
        1,
        30,
        352
    ),
    (
        11,
        2,
        828,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        30,
        353
    ),
    (
        11,
        12,
        810,
        '2026-01-09 00:00:00',
        FALSE,
        2,
        30,
        354
    ),
    (
        11,
        5,
        808,
        '2026-01-05 00:00:00',
        FALSE,
        1,
        30,
        355
    ),
    (
        11,
        5,
        814,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        356
    ),
    (
        11,
        8,
        833,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        30,
        357
    ),
    (
        11,
        7,
        827,
        '2026-01-05 00:00:00',
        FALSE,
        3,
        30,
        358
    ),
    (
        11,
        3,
        835,
        '2026-01-06 00:00:00',
        FALSE,
        3,
        30,
        359
    ),
    (
        11,
        10,
        824,
        '2026-01-05 00:00:00',
        FALSE,
        2,
        30,
        360
    ),
    (
        11,
        11,
        830,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        30,
        361
    ),
    (
        11,
        6,
        820,
        '2026-01-06 00:00:00',
        FALSE,
        2,
        30,
        362
    ),
    (
        11,
        13,
        817,
        '2026-01-04 00:00:00',
        FALSE,
        1,
        30,
        363
    ),
    (
        11,
        2,
        811,
        '2026-01-09 00:00:00',
        TRUE,
        1,
        30,
        364
    ),
    (
        11,
        8,
        834,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        30,
        365
    ),
    (
        11,
        2,
        815,
        '2026-01-06 00:00:00',
        FALSE,
        2,
        30,
        366
    ),
    (
        3,
        10,
        845,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        60,
        367
    ),
    (
        3,
        13,
        844,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        60,
        368
    ),
    (
        3,
        14,
        842,
        '2026-01-08 00:00:00',
        FALSE,
        2,
        60,
        369
    ),
    (
        3,
        15,
        848,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        60,
        370
    ),
    (
        3,
        2,
        852,
        '2026-01-09 00:00:00',
        FALSE,
        2,
        60,
        371
    ),
    (
        3,
        8,
        843,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        60,
        372
    ),
    (
        3,
        2,
        851,
        '2026-01-10 00:00:00',
        FALSE,
        4,
        60,
        373
    ),
    (
        3,
        10,
        850,
        '2026-01-05 00:00:00',
        FALSE,
        3,
        60,
        374
    ),
    (
        6,
        4,
        856,
        '2026-01-10 00:00:00',
        FALSE,
        2,
        120,
        375
    ),
    (
        6,
        5,
        855,
        '2026-01-09 00:00:00',
        FALSE,
        5,
        120,
        376
    ),
    (
        11,
        10,
        860,
        '2026-01-08 00:00:00',
        TRUE,
        3,
        30,
        377
    ),
    (
        11,
        7,
        874,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        378
    ),
    (
        11,
        6,
        875,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        30,
        379
    ),
    (
        11,
        7,
        866,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        30,
        380
    ),
    (
        11,
        3,
        880,
        '2026-01-05 00:00:00',
        FALSE,
        3,
        30,
        381
    ),
    (
        11,
        9,
        881,
        '2026-01-10 00:00:00',
        FALSE,
        1,
        30,
        382
    ),
    (
        11,
        10,
        864,
        '2026-01-07 00:00:00',
        FALSE,
        2,
        30,
        383
    ),
    (
        11,
        12,
        863,
        '2026-01-08 00:00:00',
        TRUE,
        1,
        30,
        384
    ),
    (
        11,
        10,
        886,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        385
    ),
    (
        11,
        12,
        873,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        30,
        386
    ),
    (
        11,
        10,
        872,
        '2026-01-06 00:00:00',
        FALSE,
        3,
        30,
        387
    ),
    (
        11,
        2,
        888,
        '2026-01-07 00:00:00',
        FALSE,
        1,
        30,
        388
    ),
    (
        11,
        9,
        879,
        '2026-01-08 00:00:00',
        FALSE,
        2,
        30,
        389
    ),
    (
        11,
        11,
        871,
        '2026-01-09 00:00:00',
        FALSE,
        1,
        30,
        390
    ),
    (
        11,
        7,
        878,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        30,
        391
    ),
    (
        3,
        4,
        898,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        392
    ),
    (
        3,
        15,
        891,
        '2026-01-07 00:00:00',
        FALSE,
        4,
        60,
        393
    ),
    (
        3,
        1,
        899,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        60,
        394
    ),
    (
        3,
        13,
        889,
        '2026-01-06 00:00:00',
        FALSE,
        4,
        60,
        395
    ),
    (
        3,
        1,
        890,
        '2026-01-07 00:00:00',
        TRUE,
        2,
        60,
        396
    ),
    (
        3,
        10,
        903,
        '2026-01-06 00:00:00',
        FALSE,
        2,
        60,
        397
    ),
    (
        3,
        9,
        901,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        60,
        398
    ),
    (
        3,
        2,
        893,
        '2026-01-07 00:00:00',
        FALSE,
        4,
        60,
        399
    ),
    (
        3,
        9,
        892,
        '2026-01-09 00:00:00',
        FALSE,
        4,
        60,
        400
    ),
    (
        6,
        7,
        906,
        '2026-01-06 00:00:00',
        FALSE,
        5,
        120,
        401
    ),
    (
        6,
        8,
        910,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        120,
        402
    ),
    (
        11,
        4,
        928,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        30,
        403
    ),
    (
        11,
        13,
        921,
        '2026-01-10 00:00:00',
        TRUE,
        1,
        30,
        404
    ),
    (
        11,
        15,
        939,
        '2026-01-10 00:00:00',
        FALSE,
        2,
        30,
        405
    ),
    (
        11,
        6,
        916,
        '2026-01-11 00:00:00',
        FALSE,
        3,
        30,
        406
    ),
    (
        11,
        10,
        927,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        30,
        407
    ),
    (
        11,
        11,
        925,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        30,
        408
    ),
    (
        11,
        4,
        920,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        30,
        409
    ),
    (
        11,
        11,
        923,
        '2026-01-06 00:00:00',
        FALSE,
        3,
        30,
        410
    ),
    (
        11,
        12,
        931,
        '2026-01-06 00:00:00',
        FALSE,
        1,
        30,
        411
    ),
    (
        11,
        4,
        938,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        412
    ),
    (
        11,
        3,
        933,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        30,
        413
    ),
    (
        11,
        15,
        919,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        30,
        414
    ),
    (
        11,
        11,
        941,
        '2026-01-11 00:00:00',
        FALSE,
        3,
        30,
        415
    ),
    (
        11,
        2,
        936,
        '2026-01-08 00:00:00',
        FALSE,
        2,
        30,
        416
    ),
    (
        3,
        3,
        958,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        60,
        417
    ),
    (
        3,
        3,
        945,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        418
    ),
    (
        3,
        14,
        956,
        '2026-01-09 00:00:00',
        FALSE,
        2,
        60,
        419
    ),
    (
        3,
        9,
        954,
        '2026-01-12 00:00:00',
        FALSE,
        3,
        60,
        420
    ),
    (
        3,
        2,
        955,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        421
    ),
    (
        3,
        6,
        953,
        '2026-01-12 00:00:00',
        FALSE,
        3,
        60,
        422
    ),
    (
        3,
        11,
        947,
        '2026-01-07 00:00:00',
        FALSE,
        2,
        60,
        423
    ),
    (
        6,
        2,
        962,
        '2026-01-09 00:00:00',
        TRUE,
        2,
        120,
        424
    ),
    (
        6,
        15,
        963,
        '2026-01-09 00:00:00',
        FALSE,
        2,
        120,
        425
    ),
    (
        6,
        15,
        961,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        120,
        426
    ),
    (
        11,
        1,
        988,
        '2026-01-10 00:00:00',
        FALSE,
        2,
        30,
        427
    ),
    (
        11,
        13,
        984,
        '2026-01-07 00:00:00',
        FALSE,
        3,
        30,
        428
    ),
    (
        11,
        5,
        978,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        30,
        429
    ),
    (
        11,
        10,
        992,
        '2026-01-10 00:00:00',
        FALSE,
        1,
        30,
        430
    ),
    (
        11,
        5,
        970,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        30,
        431
    ),
    (
        11,
        9,
        975,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        432
    ),
    (
        11,
        14,
        985,
        '2026-01-12 00:00:00',
        FALSE,
        2,
        30,
        433
    ),
    (
        11,
        3,
        983,
        '2026-01-12 00:00:00',
        FALSE,
        3,
        30,
        434
    ),
    (
        11,
        11,
        967,
        '2026-01-08 00:00:00',
        FALSE,
        2,
        30,
        435
    ),
    (
        11,
        13,
        972,
        '2026-01-10 00:00:00',
        TRUE,
        3,
        30,
        436
    ),
    (
        11,
        2,
        994,
        '2026-01-07 00:00:00',
        FALSE,
        2,
        30,
        437
    ),
    (
        11,
        6,
        979,
        '2026-01-10 00:00:00',
        FALSE,
        2,
        30,
        438
    ),
    (
        11,
        10,
        987,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        439
    ),
    (
        3,
        2,
        995,
        '2026-01-09 00:00:00',
        TRUE,
        4,
        60,
        440
    ),
    (
        3,
        3,
        1011,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        441
    ),
    (
        3,
        8,
        1007,
        '2026-01-13 00:00:00',
        FALSE,
        4,
        60,
        442
    ),
    (
        3,
        14,
        1006,
        '2026-01-10 00:00:00',
        FALSE,
        2,
        60,
        443
    ),
    (
        3,
        11,
        999,
        '2026-01-13 00:00:00',
        FALSE,
        3,
        60,
        444
    ),
    (
        3,
        15,
        1003,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        445
    ),
    (
        3,
        11,
        998,
        '2026-01-08 00:00:00',
        TRUE,
        4,
        60,
        446
    ),
    (
        3,
        5,
        997,
        '2026-01-13 00:00:00',
        TRUE,
        1,
        60,
        447
    ),
    (
        3,
        13,
        1004,
        '2026-01-09 00:00:00',
        FALSE,
        1,
        60,
        448
    ),
    (
        6,
        6,
        1017,
        '2026-01-09 00:00:00',
        FALSE,
        4,
        120,
        449
    ),
    (
        6,
        6,
        1016,
        '2026-01-11 00:00:00',
        FALSE,
        6,
        120,
        450
    ),
    (
        6,
        2,
        1014,
        '2026-01-09 00:00:00',
        FALSE,
        5,
        120,
        451
    ),
    (
        11,
        15,
        1029,
        '2026-01-08 00:00:00',
        FALSE,
        1,
        30,
        452
    ),
    (
        11,
        10,
        1026,
        '2026-01-13 00:00:00',
        FALSE,
        3,
        30,
        453
    ),
    (
        11,
        2,
        1022,
        '2026-01-13 00:00:00',
        FALSE,
        1,
        30,
        454
    ),
    (
        11,
        4,
        1019,
        '2026-01-12 00:00:00',
        FALSE,
        2,
        30,
        455
    ),
    (
        11,
        11,
        1044,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        30,
        456
    ),
    (
        11,
        6,
        1031,
        '2026-01-08 00:00:00',
        FALSE,
        2,
        30,
        457
    ),
    (
        11,
        13,
        1021,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        30,
        458
    ),
    (
        11,
        6,
        1039,
        '2026-01-13 00:00:00',
        FALSE,
        1,
        30,
        459
    ),
    (
        11,
        4,
        1028,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        30,
        460
    ),
    (
        11,
        11,
        1045,
        '2026-01-08 00:00:00',
        FALSE,
        3,
        30,
        461
    ),
    (
        11,
        10,
        1041,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        30,
        462
    ),
    (
        11,
        12,
        1038,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        30,
        463
    ),
    (
        11,
        9,
        1027,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        30,
        464
    ),
    (
        11,
        10,
        1024,
        '2026-01-10 00:00:00',
        FALSE,
        1,
        30,
        465
    ),
    (
        11,
        2,
        1018,
        '2026-01-10 00:00:00',
        FALSE,
        1,
        30,
        466
    ),
    (
        11,
        10,
        1042,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        30,
        467
    ),
    (
        3,
        14,
        1060,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        60,
        468
    ),
    (
        3,
        1,
        1058,
        '2026-01-13 00:00:00',
        FALSE,
        1,
        60,
        469
    ),
    (
        3,
        1,
        1057,
        '2026-01-09 00:00:00',
        FALSE,
        2,
        60,
        470
    ),
    (
        3,
        1,
        1054,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        471
    ),
    (
        3,
        11,
        1063,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        60,
        472
    ),
    (
        3,
        4,
        1064,
        '2026-01-09 00:00:00',
        FALSE,
        4,
        60,
        473
    ),
    (
        3,
        9,
        1061,
        '2026-01-12 00:00:00',
        FALSE,
        1,
        60,
        474
    ),
    (
        3,
        9,
        1048,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        60,
        475
    ),
    (
        3,
        7,
        1059,
        '2026-01-12 00:00:00',
        FALSE,
        1,
        60,
        476
    ),
    (
        6,
        14,
        1068,
        '2026-01-13 00:00:00',
        FALSE,
        5,
        120,
        477
    ),
    (
        6,
        15,
        1066,
        '2026-01-12 00:00:00',
        FALSE,
        2,
        120,
        478
    ),
    (
        11,
        10,
        1088,
        '2026-01-09 00:00:00',
        FALSE,
        3,
        30,
        479
    ),
    (
        11,
        6,
        1091,
        '2026-01-12 00:00:00',
        FALSE,
        2,
        30,
        480
    ),
    (
        11,
        14,
        1075,
        '2026-01-14 00:00:00',
        FALSE,
        2,
        30,
        481
    ),
    (
        11,
        9,
        1100,
        '2026-01-10 00:00:00',
        FALSE,
        1,
        30,
        482
    ),
    (
        11,
        1,
        1085,
        '2026-01-12 00:00:00',
        FALSE,
        3,
        30,
        483
    ),
    (
        11,
        4,
        1099,
        '2026-01-14 00:00:00',
        FALSE,
        3,
        30,
        484
    ),
    (
        11,
        9,
        1094,
        '2026-01-13 00:00:00',
        FALSE,
        1,
        30,
        485
    ),
    (
        11,
        4,
        1072,
        '2026-01-10 00:00:00',
        FALSE,
        2,
        30,
        486
    ),
    (
        11,
        12,
        1090,
        '2026-01-14 00:00:00',
        FALSE,
        2,
        30,
        487
    ),
    (
        11,
        2,
        1077,
        '2026-01-14 00:00:00',
        FALSE,
        1,
        30,
        488
    ),
    (
        11,
        10,
        1080,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        30,
        489
    ),
    (
        11,
        3,
        1079,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        30,
        490
    ),
    (
        11,
        1,
        1095,
        '2026-01-14 00:00:00',
        FALSE,
        3,
        30,
        491
    ),
    (
        11,
        15,
        1093,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        30,
        492
    ),
    (
        11,
        1,
        1086,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        30,
        493
    ),
    (
        11,
        9,
        1074,
        '2026-01-12 00:00:00',
        FALSE,
        1,
        30,
        494
    ),
    (
        11,
        2,
        1096,
        '2026-01-14 00:00:00',
        FALSE,
        2,
        30,
        495
    ),
    (
        3,
        5,
        1110,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        60,
        496
    ),
    (
        3,
        5,
        1109,
        '2026-01-13 00:00:00',
        FALSE,
        4,
        60,
        497
    ),
    (
        3,
        15,
        1105,
        '2026-01-10 00:00:00',
        FALSE,
        1,
        60,
        498
    ),
    (
        3,
        15,
        1115,
        '2026-01-13 00:00:00',
        FALSE,
        1,
        60,
        499
    ),
    (
        3,
        14,
        1111,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        60,
        500
    ),
    (
        3,
        3,
        1113,
        '2026-01-14 00:00:00',
        FALSE,
        1,
        60,
        501
    ),
    (
        3,
        11,
        1117,
        '2026-01-12 00:00:00',
        FALSE,
        2,
        60,
        502
    ),
    (
        3,
        14,
        1101,
        '2026-01-14 00:00:00',
        FALSE,
        4,
        60,
        503
    ),
    (
        6,
        15,
        1123,
        '2026-01-12 00:00:00',
        FALSE,
        3,
        120,
        504
    ),
    (
        6,
        15,
        1118,
        '2026-01-15 00:00:00',
        TRUE,
        4,
        120,
        505
    ),
    (
        11,
        7,
        1143,
        '2026-01-13 00:00:00',
        FALSE,
        3,
        30,
        506
    ),
    (
        11,
        11,
        1138,
        '2026-01-15 00:00:00',
        FALSE,
        1,
        30,
        507
    ),
    (
        11,
        6,
        1128,
        '2026-01-13 00:00:00',
        FALSE,
        1,
        30,
        508
    ),
    (
        11,
        14,
        1148,
        '2026-01-13 00:00:00',
        TRUE,
        2,
        30,
        509
    ),
    (
        11,
        6,
        1129,
        '2026-01-11 00:00:00',
        FALSE,
        1,
        30,
        510
    ),
    (
        11,
        4,
        1130,
        '2026-01-13 00:00:00',
        FALSE,
        3,
        30,
        511
    ),
    (
        11,
        4,
        1136,
        '2026-01-13 00:00:00',
        FALSE,
        2,
        30,
        512
    ),
    (
        11,
        11,
        1124,
        '2026-01-15 00:00:00',
        FALSE,
        1,
        30,
        513
    ),
    (
        11,
        13,
        1141,
        '2026-01-11 00:00:00',
        FALSE,
        3,
        30,
        514
    ),
    (
        11,
        12,
        1133,
        '2026-01-15 00:00:00',
        FALSE,
        2,
        30,
        515
    ),
    (
        11,
        5,
        1137,
        '2026-01-11 00:00:00',
        FALSE,
        2,
        30,
        516
    ),
    (
        11,
        14,
        1152,
        '2026-01-11 00:00:00',
        FALSE,
        3,
        30,
        517
    ),
    (
        11,
        3,
        1151,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        30,
        518
    ),
    (
        11,
        10,
        1147,
        '2026-01-10 00:00:00',
        FALSE,
        3,
        30,
        519
    ),
    (
        11,
        6,
        1144,
        '2026-01-15 00:00:00',
        FALSE,
        3,
        30,
        520
    ),
    (
        7,
        4,
        686,
        '2026-01-11 10:00:00',
        FALSE,
        4,
        30,
        1
    ),
    (
        8,
        8,
        698,
        '2026-01-11 12:00:00',
        FALSE,
        2,
        30,
        2
    ),
    (
        9,
        10,
        710,
        '2026-01-11 15:00:00',
        FALSE,
        2,
        30,
        3
    ),
    (
        10,
        13,
        722,
        '2026-01-11 18:00:00',
        FALSE,
        10,
        30,
        4
    );

INSERT INTO
    review (
        customer_id,
        booking_id,
        time_stamp,
        text,
        environment_rating,
        equipment_rating,
        service_rating
    )
VALUES (
        1,
        1,
        '2026-02-02 20:00:00',
        'Great football pitch and lighting, perfect for 7v7 games!',
        5,
        4,
        5
    ),
    (
        2,
        2,
        '2026-02-03 21:10:00',
        'Nice field but locker rooms could be cleaner.',
        4,
        4,
        3
    ),
    (
        3,
        3,
        '2026-02-03 19:30:00',
        'Loved the indoor court. Great surface and friendly staff.',
        5,
        5,
        5
    ),
    (
        5,
        5,
        '2026-02-04 10:45:00',
        'Good gym atmosphere, though some machines need maintenance.',
        4,
        3,
        4
    ),
    (
        6,
        6,
        '2026-02-04 20:00:00',
        'Top-notch trainers and well-equipped gym!',
        5,
        5,
        5
    ),
    (
        7,
        7,
        '2026-02-05 13:15:00',
        'Beautiful biking trails, great for weekend rides.',
        5,
        4,
        5
    ),
    (
        9,
        9,
        '2026-02-06 18:30:00',
        'Court was clean and the hoops were in great condition.',
        5,
        4,
        4
    ),
    (
        10,
        10,
        '2026-02-07 20:10:00',
        'Good basketball space, but too crowded at times.',
        4,
        4,
        3
    ),
    (
        11,
        11,
        '2026-02-08 14:00:00',
        'Peaceful golf course with an amazing sea view.',
        5,
        5,
        5
    ),
    (
        12,
        7,
        '2026-02-09 11:20:00',
        'Padel courts were excellent, café service a bit slow.',
        4,
        5,
        3
    ),
    (
        14,
        9,
        '2026-02-09 19:45:00',
        'Fun climbing walls and friendly instructors.',
        5,
        4,
        5
    ),
    (
        15,
        10,
        '2026-02-10 22:00:00',
        'Nice hockey arena, though changing rooms need upgrades.',
        4,
        3,
        4
    ),
    (
        12,
        12,
        '2026-02-11 20:10:00',
        'Padel courts were excellent and well maintained. Booking was smooth.',
        5,
        5,
        4
    ),
    (
        4,
        518,
        '2026-02-12 21:30:00',
        'Amazing padel courts! Very well maintained and the equipment is top quality.',
        5,
        5,
        5
    ),
    (
        13,
        13,
        '2026-02-11 18:00:00',
        'Great swimming complex: clean facilities and plenty of space in the lanes.',
        5,
        4,
        5
    ),
    (
        8,
        519,
        '2026-02-12 16:45:00',
        'Good pool overall, though the water temperature could be a bit warmer.',
        4,
        4,
        4
    ),
    (
        14,
        14,
        '2026-02-12 19:10:00',
        'Fun climbing walls and great variety of routes. Staff was helpful.',
        5,
        5,
        5
    ),
    (
        10,
        520,
        '2026-02-13 22:15:00',
        'Fantastic climbing walls for all skill levels. Highly recommend!',
        5,
        5,
        5
    ),
    (
        15,
        15,
        '2026-02-13 23:15:00',
        'Solid hockey facility, good quality and well organized.',
        5,
        5,
        4
    ),
    (
        13,
        521,
        '2026-02-14 22:45:00',
        'Good arena for practice, though it could use better seating for spectators.',
        4,
        4,
        4
    );

INSERT INTO
    response (
        owner_id,
        review_id,
        text,
        time_stamp
    )
VALUES (
        3,
        1,
        'Thanks for the feedback! We''re glad you enjoyed your match and hope to see you again soon.',
        '2026-02-03 10:15:00'
    ),
    (
        1,
        4,
        'Appreciate your comment. We''re scheduling maintenance on those machines next week.',
        '2026-02-04 12:00:00'
    ),
    (
        5,
        6,
        'Happy to hear you liked the trails! We''re adding new routes next season.',
        '2026-02-05 16:45:00'
    ),
    (
        3,
        7,
        'Thanks for visiting! We''ll work on improving the lighting for evening games.',
        '2026-02-06 20:30:00'
    ),
    (
        3,
        10,
        'We''re happy you liked the courts! Sorry for the café wait — we''re hiring more staff.',
        '2026-02-09 13:10:00'
    ),
    (
        3,
        12,
        'Thank you for your feedback! We''ll renovate the locker rooms early next year.',
        '2026-02-11 09:40:00'
    );

INSERT INTO
    discount (
        space_id,
        code,
        percentage,
        start_date,
        end_date
    )
VALUES (
        1,
        'PROMO15',
        15.0,
        '2025-01-25 00:00:00',
        '2026-02-10 23:59:00'
    ),
    (
        3,
        'DISC10',
        10.0,
        '2026-01-20 00:00:00',
        '2026-02-05 23:59:00'
    ),
    (
        5,
        'FLASH20',
        20.0,
        '2026-02-01 00:00:00',
        '2026-02-07 23:59:00'
    ),
    (
        7,
        'SPECIAL25',
        25.0,
        '2026-02-03 00:00:00',
        '2026-02-20 23:59:00'
    ),
    (
        8,
        'SUPER30',
        30.0,
        '2026-01-29 00:00:00',
        '2026-02-15 23:59:00'
    );

INSERT INTO
    notification (
        user_id,
        time_stamp,
        is_read,
        content
    )
VALUES (
        2,
        '2025-11-01 14:35:00',
        TRUE,
        'Your reservation has been confirmed.'
    ),
    (
        3,
        '2025-11-02 10:10:00',
        TRUE,
        'The reservation was cancelled by the host.'
    ),
    (
        4,
        '2025-11-02 19:45:00',
        TRUE,
        'You have a reservation tomorrow at 8 PM.'
    ),
    (
        5,
        '2025-11-03 15:40:00',
        TRUE,
        'Payment received successfully.'
    ),
    (
        8,
        '2025-11-04 09:30:00',
        TRUE,
        'Welcome to our platform!'
    ),
    (
        9,
        '2025-11-04 12:25:00',
        TRUE,
        'New reservation awaiting approval.'
    ),
    (
        10,
        '2025-11-05 08:15:00',
        TRUE,
        'Don''t forget to leave a review.'
    ),
    (
        12,
        '2025-11-05 16:20:00',
        TRUE,
        'Your reservation has been confirmed.'
    ),
    (
        13,
        '2025-11-06 13:55:00',
        TRUE,
        'Your cancellation request has been accepted.'
    ),
    (
        15,
        '2025-11-07 14:45:00',
        TRUE,
        'We have updated our terms of service.'
    ),
    (
        16,
        '2025-11-07 18:10:00',
        TRUE,
        'Reservation confirmed for 2 people.'
    ),
    (
        17,
        '2025-11-08 10:20:00',
        TRUE,
        'Your reservation starts in 1 hour.'
    ),
    (
        18,
        '2025-11-08 15:40:00',
        TRUE,
        'The host has accepted your request.'
    ),
    (
        19,
        '2025-11-09 17:15:00',
        TRUE,
        'You have received a new discount coupon!'
    ),
    (
        20,
        '2025-11-09 18:25:00',
        TRUE,
        'Reservation completed. Thank you!'
    ),
    (
        3,
        '2025-12-03 21:30:00',
        FALSE,
        'Your subscription will expire soon.'
    ),
    (
        9,
        '2025-12-04 20:10:00',
        FALSE,
        'You have received a new message from the host.'
    ),
    (
        10,
        '2025-12-05 13:20:00',
        FALSE,
        'Please confirm your attendance for reservation.'
    ),
    (
        19,
        '2025-12-09 20:00:00',
        FALSE,
        'A schedule change has been requested.'
    ),
    (
        20,
        '2025-12-10 22:10:00',
        FALSE,
        'System maintenance is scheduled.'
    );

INSERT INTO
    review_notification (notification_id, review_id)
VALUES (3, 1),
    (9, 7),
    (16, 2),
    (17, 5),
    (19, 11),
    (20, 12);

INSERT INTO
    booking_confirmation_notification (notification_id, booking_id)
VALUES (1, 1),
    (2, 2),
    (5, 5),
    (7, 7),
    (10, 10),
    (11, 11),
    (12, 7),
    (14, 9),
    (15, 10);

INSERT INTO
    booking_cancellation_notification (notification_id, booking_id)
VALUES (4, 4),
    (8, 8),
    (13, 8);

INSERT INTO
    booking_reminder_notification (notification_id, booking_id)
VALUES (6, 6),
    (13, 9),
    (18, 7);

INSERT INTO
    media (space_id, media_url, is_cover)
VALUES (
        1,
        '/images/uploads/spaces/1/football_field_cover.jpg',
        TRUE
    ),
    (
        1,
        '/images/uploads/spaces/1/football_field_inside.jpg',
        FALSE
    ),
    (
        2,
        '/images/uploads/spaces/2/badminton_boavista_cover.jpg',
        TRUE
    ),
    (
        3,
        '/images/uploads/spaces/3/iron_gym.webp',
        TRUE
    ),
    (
        4,
        '/images/uploads/spaces/4/biking_park.jpg',
        TRUE
    ),
    (
        5,
        '/images/uploads/spaces/5/downtown_basketball_cover.jpg',
        TRUE
    ),
    (
        5,
        '/images/uploads/spaces/5/downtown_basketball_court.jpg',
        FALSE
    ),
    (
        6,
        '/images/uploads/spaces/6/golf_club_foz_cover.jpg',
        TRUE
    ),
    (
        6,
        '/images/uploads/spaces/6/golf_club_foz_course.jpg',
        FALSE
    ),
    (
        7,
        '/images/uploads/spaces/7/padel_arena_cover.jpg',
        TRUE
    ),
    (
        7,
        '/images/uploads/spaces/7/padel_arena_inside.jpg',
        FALSE
    ),
    (
        8,
        '/images/uploads/spaces/8/swimming_complex_cover.jpg',
        TRUE
    ),
    (
        8,
        '/images/uploads/spaces/8/swimming_complex_pool.jpg',
        FALSE
    ),
    (
        9,
        '/images/uploads/spaces/9/climbing_zone_cover.jpg',
        TRUE
    ),
    (
        9,
        '/images/uploads/spaces/9/climbing_zone_wall.jpg',
        FALSE
    ),
    (
        10,
        '/images/uploads/spaces/10/hockey_arena_cover.jpg',
        TRUE
    ),
    (
        10,
        '/images/uploads/spaces/10/hockey_arena_inside.jpg',
        FALSE
    ),
    (
        11,
        '/images/uploads/spaces/11/riverside_running_track_cover.jpg',
        TRUE
    ),
    (
        11,
        '/images/uploads/spaces/11/riverside_running_track_inside.jpg',
        FALSE
    );

INSERT INTO
    favorited (space_id, customer_id)
VALUES (1, 1),
    (2, 1),
    (3, 2),
    (4, 2),
    (5, 3),
    (6, 3),
    (7, 4),
    (8, 4),
    (9, 5),
    (10, 5),
    (1, 6),
    (5, 6),
    (3, 7),
    (6, 7),
    (8, 8),
    (10, 8);

-- =========================
-- PERFORMANCE INDICES
-- =========================

CREATE INDEX notification_user ON notification USING btree (user_id);

CREATE INDEX schedule_space ON schedule USING btree (space_id);

CREATE INDEX booking_history ON booking USING btree (customer_id);

CREATE INDEX space_sport_type ON space USING btree (sport_type_id);

CREATE INDEX user_google_id ON "user" USING btree(google_id) WHERE google_id IS NOT NULL;
CREATE INDEX user_facebook_id ON "user" USING btree(facebook_id) WHERE facebook_id IS NOT NULL;

-- =========================
-- FULL-TEXT SEARCH INDEX
-- =========================

ALTER TABLE space ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION space_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.title), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B')
        );
    END IF;

    IF TG_OP = 'UPDATE' THEN
        IF (NEW.title <> OLD.title OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B')
            );
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER space_search_update
    BEFORE INSERT OR UPDATE ON space
    FOR EACH ROW
EXECUTE FUNCTION space_search_update();

CREATE INDEX search_space_idx ON space USING GIN (tsvectors);

-- =========================
-- BUSINESS LOGIC TRIGGERS
-- =========================

DROP TRIGGER IF EXISTS num_favorites_update ON favorited;

DROP FUNCTION IF EXISTS update_num_favorites ();

DROP TRIGGER IF EXISTS review_num_update ON review;

DROP FUNCTION IF EXISTS update_num_reviews ();

CREATE FUNCTION update_num_reviews() RETURNS TRIGGER AS $$
DECLARE
    v_space_id INT;
BEGIN
    IF (TG_OP = 'INSERT') THEN
        SELECT b.space_id INTO v_space_id
        FROM booking b
        WHERE b.id = NEW.booking_id;

        UPDATE space
        SET num_reviews = num_reviews + 1
        WHERE id = v_space_id;
    END IF;

    IF (TG_OP = 'DELETE') THEN
        SELECT b.space_id INTO v_space_id
        FROM booking b
        WHERE b.id = OLD.booking_id;

        UPDATE space
        SET num_reviews = num_reviews - 1
        WHERE id = v_space_id;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER review_num_update
    AFTER INSERT OR DELETE ON review
    FOR EACH ROW
EXECUTE FUNCTION update_num_reviews();

CREATE FUNCTION update_num_favorites() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        UPDATE space
        SET num_favorites = num_favorites + 1
        WHERE id = NEW.space_id;
    END IF;

    IF (TG_OP = 'DELETE') THEN
        UPDATE space
        SET num_favorites = num_favorites - 1
        WHERE id = OLD.space_id;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER num_favorites_update
    AFTER INSERT OR DELETE ON favorited
    FOR EACH ROW
EXECUTE FUNCTION update_num_favorites();

CREATE FUNCTION update_is_deleted() RETURNS TRIGGER AS $$
BEGIN
   UPDATE "user"
   SET is_deleted = TRUE,
       first_name = 'Deleted',
       surname = 'User',
       user_name = 'Deleted_user',
       email = 'deleted_user_' || OLD.id || '@example.com',
       phone_no = 'deleted_user_' || OLD.id,
       password = 'N/A',
       birth_date = '0001-01-01',
       profile_pic_url = 'N/A',
       google_id = NULL,
       facebook_id = NULL
   WHERE id = OLD.id;

   RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- Update space ratings when reviews are added, updated, or deleted
CREATE FUNCTION update_space_ratings() RETURNS TRIGGER AS $$
DECLARE
    v_space_id INT;
    v_avg_env NUMERIC;
    v_avg_equip NUMERIC;
    v_avg_serv NUMERIC;
    v_avg_total NUMERIC;
BEGIN
    -- Get space_id from the booking
    IF (TG_OP = 'DELETE') THEN
        SELECT b.space_id INTO v_space_id
        FROM booking b
        WHERE b.id = OLD.booking_id;
    ELSE
        SELECT b.space_id INTO v_space_id
        FROM booking b
        WHERE b.id = NEW.booking_id;
    END IF;

    -- Calculate new averages
    SELECT 
        COALESCE(AVG(r.environment_rating), 0),
        COALESCE(AVG(r.equipment_rating), 0),
        COALESCE(AVG(r.service_rating), 0),
        COALESCE(AVG((r.environment_rating + r.equipment_rating + r.service_rating) / 3.0), 0)
    INTO v_avg_env, v_avg_equip, v_avg_serv, v_avg_total
    FROM review r
    JOIN booking b ON r.booking_id = b.id
    WHERE b.space_id = v_space_id;

    -- Update space ratings
    UPDATE space
    SET current_environment_rating = ROUND(v_avg_env),
        current_equipment_rating = ROUND(v_avg_equip),
        current_service_rating = ROUND(v_avg_serv),
        current_total_rating = ROUND(v_avg_total)
    WHERE id = v_space_id;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER space_ratings_update
    AFTER INSERT OR UPDATE OR DELETE ON review
    FOR EACH ROW
EXECUTE FUNCTION update_space_ratings();

CREATE TRIGGER is_deleted_update
    BEFORE DELETE ON "user"
    FOR EACH ROW
EXECUTE FUNCTION update_is_deleted();

CREATE FUNCTION anonymize_closed_space() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.is_closed = TRUE AND OLD.is_closed = FALSE THEN
        NEW.address := 'N/A';
        NEW.description := 'N/A';
        NEW.phone_no := 'N/A';
        NEW.email := 'N/A';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER space_closure_anonymize
    BEFORE UPDATE ON space
    FOR EACH ROW
    WHEN (NEW.is_closed = TRUE AND OLD.is_closed = FALSE)
EXECUTE FUNCTION anonymize_closed_space();

-- Validar que utilizador tem password OU OAuth ID
CREATE FUNCTION validate_user_auth() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.password IS NULL AND NEW.google_id IS NULL AND NEW.facebook_id IS NULL THEN
        RAISE EXCEPTION 'Utilizador deve ter password ou OAuth ID (Google/Facebook)';
END IF;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER validate_user_auth_trigger
    BEFORE INSERT OR UPDATE ON "user"
                         FOR EACH ROW
                         EXECUTE FUNCTION validate_user_auth();


-- =========================
-- POST-SEED BACKFILL
-- (Triggers are defined at the end of this file, so earlier INSERTs won't
--  update denormalized counters/ratings. This recomputes them once.)
-- =========================

UPDATE space
SET
    num_reviews = 0,
    current_environment_rating = 0,
    current_equipment_rating = 0,
    current_service_rating = 0,
    current_total_rating = 0;

UPDATE space s
SET num_reviews = x.num_reviews,
    current_environment_rating = x.env,
    current_equipment_rating = x.equip,
    current_service_rating = x.serv,
    current_total_rating = x.total
FROM (
    SELECT
        b.space_id,
        COUNT(r.id) AS num_reviews,
        ROUND(COALESCE(AVG(r.environment_rating), 0))::INT AS env,
        ROUND(COALESCE(AVG(r.equipment_rating), 0))::INT AS equip,
        ROUND(COALESCE(AVG(r.service_rating), 0))::INT AS serv,
        ROUND(
            COALESCE(
                AVG((r.environment_rating + r.equipment_rating + r.service_rating) / 3.0),
                0
            )
        )::INT AS total
    FROM booking b
    LEFT JOIN review r ON r.booking_id = b.id
    GROUP BY b.space_id
) x
WHERE s.id = x.space_id;