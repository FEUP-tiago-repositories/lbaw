-- =========================
-- SCHEMA CONFIGURATION
-- =========================

show search_path;

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
    latitude FLOAT,
    longitude FLOAT,
    description VARCHAR(300) NOT NULL,
    is_closed BOOLEAN NOT NULL DEFAULT FALSE,
    phone_no VARCHAR(15) NOT NULL,
    email VARCHAR(150) NOT NULL,
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
    start_time TIMESTAMP NOT NULL, --must be a future TIMESTAMP
    duration INT NOT NULL CHECK (duration > 0),
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

CREATE TABLE new_reservation_notifications (
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

CREATE TABLE password_resets (
    id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    user_id INT NOT NULL REFERENCES "user"(id) ON DELETE CASCADE,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    expires_at TIMESTAMP NOT NULL
);


-- =======================================================================================
-- DATA POPULATION (around 1950 lines of code, scroll down to see indexes and triggers)
-- =======================================================================================

    INSERT INTO "user" (
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
        TRUE,
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
        TRUE,
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
        'Football field near Porto''s downtown',
        'Rua das Flores 123, Porto',
        41.1442807,
        -8.6130616,
        'Well-maintained grass field suitable for 5v5 and 7v7 matches.',
        FALSE,
        '912345678',
        'porto_field@mail.com',
        15,
        4,
        5,
        4,
        5,
        14
    ),
    (
        2,
        1,
        'Badminton Court Boavista',
        'Avenida da Boavista 540, Porto',
        41.1579989,
        -8.633074,
        'Indoor badminton court with great lighting and wooden flooring.',
        FALSE,
        '911223344',
        'boavista_badminton@mail.com',
        9,
        2,
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
        22,
        8,
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
        17,
        6,
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
        30,
        10,
        4,
        4,
        3,
        11
    ),
    (
        1,
        6,
        'Golf Club Foz',
        'Avenida Brasil 999, Foz do Douro',
        41.1591669,
        -8.6861682,
        'Private golf course with ocean view.',
        FALSE,
        '938888777',
        'foz_golf@mail.com',
        12,
        3,
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
        25,
        9,
        4,
        5,
        3,
        12
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
        19,
        7,
        5,
        4,
        4,
        13
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
        14,
        5,
        5,
        4,
        5,
        14
    ),
    (
        3,
        9,
        'Hockey Arena Norte',
        'Rua de Santo Ildefonso 340, Porto',
        41.1470552,
        -8.6044052,
        'Indoor hockey arena hosting local matches and training.',
        FALSE,
        '939334455',
        'hockey_norte@mail.com',
        8,
        3,
        4,
        3,
        4,
        11
    ),
    (
        1,
        13,
        'Riverside Running Track',
        'Cais de Gaia, Vila Nova de Gaia',
        41.1368678,
        -8.6255192,
        'Beautiful riverside track perfect for daily runs.',
        FALSE,
        '937223344',
        'riverside_run@mail.com',
        20,
        6,
        5,
        4,
        5,
        14
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
        1,
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
        1,
        2,
        'Repeated rule violations',
        '2025-10-01 18:50:01'
    );

INSERT INTO
    schedule (
        space_id,
        start_time,
        duration,
        max_capacity
    )
VALUES
    -- Space 1: Football field (10 schedules)
    (
        1,
        '2026-02-01 18:00:00',
        90,
        14
    ),
    (
        1,
        '2026-02-02 19:00:00',
        90,
        14
    ),
    (
        1,
        '2026-02-03 20:00:00',
        120,
        14
    ),
    (
        1,
        '2026-02-04 18:30:00',
        90,
        14
    ),
    (
        1,
        '2026-02-05 17:00:00',
        90,
        14
    ),
    (
        1,
        '2026-02-06 19:30:00',
        120,
        14
    ),
    (
        1,
        '2026-02-07 18:00:00',
        90,
        14
    ),
    (
        1,
        '2026-02-08 20:00:00',
        90,
        14
    ),
    (
        1,
        '2026-02-09 17:30:00',
        90,
        14
    ),
    (
        1,
        '2026-02-10 19:00:00',
        120,
        14
    ),
    (
        2,
        '2026-02-02 17:30:00',
        60,
        4
    ),
    (
        2,
        '2026-02-05 19:00:00',
        60,
        4
    ),
    (
        3,
        '2026-02-01 08:00:00',
        90,
        20
    ),
    (
        3,
        '2026-02-02 18:00:00',
        120,
        25
    ),
    (
        4,
        '2026-02-07 10:00:00',
        180,
        30
    ),
    (
        4,
        '2026-02-10 14:00:00',
        120,
        25
    ),
    (
        5,
        '2026-02-06 16:00:00',
        90,
        10
    ),
    (
        5,
        '2026-02-09 18:30:00',
        90,
        12
    ),
    (
        6,
        '2026-02-08 09:00:00',
        240,
        20
    ),
    (
        6,
        '2026-02-15 13:30:00',
        240,
        18
    ),
    (
        7,
        '2026-02-03 19:00:00',
        60,
        8
    ),
    (
        7,
        '2026-02-04 20:30:00',
        60,
        8
    ),
    (
        8,
        '2026-02-02 07:30:00',
        90,
        30
    ),
    (
        8,
        '2026-02-06 15:00:00',
        90,
        25
    ),
    (
        9,
        '2026-02-05 18:00:00',
        120,
        15
    ),
    (
        9,
        '2026-02-08 10:00:00',
        150,
        12
    ),
    (
        10,
        '2026-02-09 19:30:00',
        90,
        22
    ),
    (
        10,
        '2026-02-11 21:00:00',
        90,
        20
    ),
    (
        11,
        '2026-02-10 07:00:00',
        60,
        40
    ),
    (
        11,
        '2026-02-12 18:00:00',
        60,
        35
    ),

-- Space 2: Badminton Court (10 schedules)
(
    2,
    '2026-02-01 17:30:00',
    60,
    4
),
(
    2,
    '2026-02-02 18:30:00',
    60,
    4
),
(
    2,
    '2026-02-03 19:00:00',
    60,
    4
),
(
    2,
    '2026-02-04 17:00:00',
    60,
    4
),
(
    2,
    '2026-02-05 19:00:00',
    60,
    4
),
(
    2,
    '2026-02-06 18:00:00',
    60,
    4
),
(
    2,
    '2026-02-07 17:30:00',
    60,
    4
),
(
    2,
    '2026-02-08 19:30:00',
    60,
    4
),
(
    2,
    '2026-02-09 18:00:00',
    60,
    4
),
(
    2,
    '2026-02-10 17:00:00',
    60,
    4
),

-- Space 3: Iron Gym (10 schedules)
(
    3,
    '2026-02-01 08:00:00',
    90,
    20
),
(
    3,
    '2026-02-02 09:00:00',
    120,
    25
),
(
    3,
    '2026-02-03 18:00:00',
    90,
    20
),
(
    3,
    '2026-02-04 07:30:00',
    90,
    20
),
(
    3,
    '2026-02-05 19:00:00',
    120,
    25
),
(
    3,
    '2026-02-06 08:00:00',
    90,
    20
),
(
    3,
    '2026-02-07 10:00:00',
    90,
    20
),
(
    3,
    '2026-02-08 18:30:00',
    120,
    25
),
(
    3,
    '2026-02-09 07:00:00',
    90,
    20
),
(
    3,
    '2026-02-10 19:30:00',
    90,
    20
),

-- Space 4: Gaia Biking Park (10 schedules)
(
    4,
    '2026-02-01 10:00:00',
    180,
    30
),
(
    4,
    '2026-02-02 14:00:00',
    120,
    25
),
(
    4,
    '2026-02-03 09:00:00',
    180,
    30
),
(
    4,
    '2026-02-04 15:00:00',
    120,
    25
),
(
    4,
    '2026-02-05 10:30:00',
    180,
    30
),
(
    4,
    '2026-02-06 13:00:00',
    180,
    30
),
(
    4,
    '2026-02-07 10:00:00',
    120,
    25
),
(
    4,
    '2026-02-08 14:30:00',
    180,
    30
),
(
    4,
    '2026-02-09 09:30:00',
    120,
    25
),
(
    4,
    '2026-02-10 14:00:00',
    180,
    30
),

-- Space 5: Downtown Basketball Court (10 schedules)
(
    5,
    '2026-02-01 16:00:00',
    90,
    10
),
(
    5,
    '2026-02-02 18:30:00',
    90,
    12
),
(
    5,
    '2026-02-03 17:00:00',
    90,
    10
),
(
    5,
    '2026-02-04 19:00:00',
    90,
    12
),
(
    5,
    '2026-02-05 16:30:00',
    90,
    10
),
(
    5,
    '2026-02-06 18:00:00',
    90,
    12
),
(
    5,
    '2026-02-07 17:30:00',
    90,
    10
),
(
    5,
    '2026-02-08 19:30:00',
    90,
    12
),
(
    5,
    '2026-02-09 16:00:00',
    90,
    10
),
(
    5,
    '2026-02-10 18:30:00',
    90,
    12
),

-- Space 6: Golf Club Foz (10 schedules)
(
    6,
    '2026-02-01 09:00:00',
    240,
    20
),
(
    6,
    '2026-02-02 13:30:00',
    240,
    18
),
(
    6,
    '2026-02-03 08:30:00',
    240,
    20
),
(
    6,
    '2026-02-04 14:00:00',
    240,
    18
),
(
    6,
    '2026-02-05 09:30:00',
    240,
    20
),
(
    6,
    '2026-02-06 13:00:00',
    240,
    18
),
(
    6,
    '2026-02-07 08:00:00',
    240,
    20
),
(
    6,
    '2026-02-08 14:30:00',
    240,
    18
),
(
    6,
    '2026-02-09 09:00:00',
    240,
    20
),
(
    6,
    '2026-02-10 13:30:00',
    240,
    18
),

-- Space 7: Padel Arena Porto (10 schedules)
(
    7,
    '2026-02-01 19:00:00',
    60,
    8
),
(
    7,
    '2026-02-02 20:30:00',
    60,
    8
),
(
    7,
    '2026-02-03 18:30:00',
    60,
    8
),
(
    7,
    '2026-02-04 19:30:00',
    60,
    8
),
(
    7,
    '2026-02-05 20:00:00',
    60,
    8
),
(
    7,
    '2026-02-06 18:00:00',
    60,
    8
),
(
    7,
    '2026-02-07 19:00:00',
    60,
    8
),
(
    7,
    '2026-02-08 20:30:00',
    60,
    8
),
(
    7,
    '2026-02-09 18:30:00',
    60,
    8
),
(
    7,
    '2026-02-10 19:30:00',
    60,
    8
),

-- Space 8: Indoor Swimming Complex (10 schedules)
(
    8,
    '2026-02-01 07:30:00',
    90,
    30
),
(
    8,
    '2026-02-02 15:00:00',
    90,
    25
),
(
    8,
    '2026-02-03 08:00:00',
    90,
    30
),
(
    8,
    '2026-02-04 16:00:00',
    90,
    25
),
(
    8,
    '2026-02-05 07:00:00',
    90,
    30
),
(
    8,
    '2026-02-06 15:30:00',
    90,
    25
),
(
    8,
    '2026-02-07 08:30:00',
    90,
    30
),
(
    8,
    '2026-02-08 14:30:00',
    90,
    25
),
(
    8,
    '2026-02-09 07:30:00',
    90,
    30
),
(
    8,
    '2026-02-10 15:00:00',
    90,
    25
),

-- Space 9: Climbing Zone Campanhã (10 schedules)
(
    9,
    '2026-02-01 18:00:00',
    120,
    15
),
(
    9,
    '2026-02-02 10:00:00',
    150,
    12
),
(
    9,
    '2026-02-03 19:00:00',
    120,
    15
),
(
    9,
    '2026-02-04 11:00:00',
    150,
    12
),
(
    9,
    '2026-02-05 18:30:00',
    120,
    15
),
(
    9,
    '2026-02-06 10:30:00',
    150,
    12
),
(
    9,
    '2026-02-07 19:30:00',
    120,
    15
),
(
    9,
    '2026-02-08 09:30:00',
    150,
    12
),
(
    9,
    '2026-02-09 18:00:00',
    120,
    15
),
(
    9,
    '2026-02-10 10:00:00',
    150,
    12
),

-- Space 10: Hockey Arena Norte (10 schedules)
(
    10,
    '2026-02-01 19:30:00',
    90,
    22
),
(
    10,
    '2026-02-02 21:00:00',
    90,
    20
),
(
    10,
    '2026-02-03 20:00:00',
    90,
    22
),
(
    10,
    '2026-02-04 19:00:00',
    90,
    20
),
(
    10,
    '2026-02-05 20:30:00',
    90,
    22
),
(
    10,
    '2026-02-06 21:30:00',
    90,
    20
),
(
    10,
    '2026-02-07 19:30:00',
    90,
    22
),
(
    10,
    '2026-02-08 20:00:00',
    90,
    20
),
(
    10,
    '2026-02-09 21:00:00',
    90,
    22
),
(
    10,
    '2026-02-10 19:30:00',
    90,
    20
),

-- Space 11: Riverside Running Track (10 schedules)
(
    11,
    '2026-02-01 07:00:00',
    60,
    40
),
(
    11,
    '2026-02-02 18:00:00',
    60,
    35
),
(
    11,
    '2026-02-03 06:30:00',
    60,
    40
),
(
    11,
    '2026-02-04 17:30:00',
    60,
    35
),
(
    11,
    '2026-02-05 07:30:00',
    60,
    40
),
(
    11,
    '2026-02-06 18:30:00',
    60,
    35
),
(
    11,
    '2026-02-07 07:00:00',
    60,
    40
),
(
    11,
    '2026-02-08 17:00:00',
    60,
    35
),
(
    11,
    '2026-02-09 06:30:00',
    60,
    40
),
(
    11,
    '2026-02-10 18:00:00',
    60,
    35
),
(
    3,
    '2026-01-10 17:30:00',
    90,
    20
),
(
    2,
    '2026-01-01 17:30:00',
    60,
    4
),
(
    1,
    '2026-02-03 15:00:00',
    30,
    14
),
(
    1,
    '2026-02-03 15:30:00',
    30,
    14
),
(
    1,
    '2026-02-03 16:00:00',
    30,
    14
),
(
    1,
    '2026-02-03 16:30:00',
    30,
    14
),
(
    1,
    '2026-02-03 17:00:00',
    30,
    14
),
(
    1,
    '2026-02-03 17:30:00',
    30,
    14
),
(
    1,
    '2026-02-03 18:00:00',
    30,
    1
),
(
    1,
    '2026-02-03 18:30:00',
    30,
    14
),
(
    1,
    '2026-02-03 19:00:00',
    30,
    14
),
(
    1,
    '2026-02-03 19:30:00',
    30,
    14
);

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
        percentage,
        start_date,
        end_date
    )
VALUES (
        1,
        15.0,
        '2026-01-25 00:00:00',
        '2026-02-10 23:59:00'
    ),
    (
        3,
        10.0,
        '2026-01-20 00:00:00',
        '2026-02-05 23:59:00'
    ),
    (
        5,
        20.0,
        '2026-02-01 00:00:00',
        '2026-02-07 23:59:00'
    ),
    (
        7,
        25.0,
        '2026-02-03 00:00:00',
        '2026-02-20 23:59:00'
    ),
    (
        8,
        30.0,
        '2026-01-29 00:00:00',
        '2026-02-15 23:59:00'
    );

INSERT INTO notification (user_id, time_stamp, is_read, content)
VALUES
    (2, '2025-11-01 14:35:00', TRUE, 'Your reservation has been confirmed.'),
    (3, '2025-11-02 10:10:00', TRUE, 'The reservation was cancelled by the host.'),
    (4, '2025-11-02 19:45:00', TRUE, 'You have a reservation tomorrow at 8 PM.'),
    (5, '2025-11-03 15:40:00', TRUE, 'Payment received successfully.'),
    (8, '2025-11-04 09:30:00', TRUE, 'Welcome to our platform!'),
    (9, '2025-11-04 12:25:00', TRUE, 'New reservation awaiting approval.'),
    (10, '2025-11-05 08:15:00', TRUE, 'Don’ t forget to leave a review.'),
    (12, '2025-11-05 16:20:00', TRUE, 'Your reservation has been confirmed.'),
    (13, '2025-11-06 13:55:00', TRUE, 'Your cancellation request has been accepted.'),
    (15, '2025-11-07 14:45:00', TRUE, 'We have updated our terms of service.'),
    (16, '2025-11-07 18:10:00', TRUE, 'Reservation confirmed for 2 people.'),
    (17, '2025-11-08 10:20:00', TRUE, 'Your reservation starts in 1 hour.'),
    (18, '2025-11-08 15:40:00', TRUE, 'The host has accepted your request.'),
    (19, '2025-11-09 17:15:00', TRUE, 'You have received a new discount coupon!'),
    (20, '2025-11-09 18:25:00', TRUE, 'Reservation completed. Thank you!'),

    (3, '2025-12-03 21:30:00', FALSE, 'Your subscription will expire soon.'),
    (9, '2025-12-04 20:10:00', FALSE, 'You have received a new message from the host.'),
    (10, '2025-12-05 13:20:00', FALSE, 'Please confirm your attendance for reservation .'),
    (19, '2025-12-09 20:00:00', FALSE, 'A schedule change has been requested.'),
    (20, '2025-12-10 22:10:00', FALSE, 'System maintenance is scheduled.');


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
        2,
        '/images/uploads/spaces/2/badminton_boavista_inside.jpg',
        FALSE
    ),
    (
        3,
        '/images/uploads/spaces/3/iron_gym.webp',
        TRUE
    ),
    (
        3,
        '/images/uploads/spaces/3/iron_gym_equipment.jpg',
        FALSE
    ),
    (
        4,
        '/images/uploads/spaces/4/biking_park.jpg',
        TRUE
    ),
    (
        4,
        '/images/uploads/spaces/4/gaia_biking_trail.jpg',
        FALSE
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
    favorited (
        space_id,
        customer_id,
        is_favorite
    )
VALUES (1, 1, TRUE),
    (2, 1, TRUE),
    (3, 2, TRUE),
    (4, 2, FALSE),
    (5, 3, TRUE),
    (6, 3, TRUE),
    (7, 4, FALSE),
    (8, 4, TRUE),
    (9, 5, TRUE),
    (10, 5, TRUE),
    (1, 6, TRUE),
    (5, 6, TRUE),
    (3, 7, TRUE),
    (6, 7, FALSE),
    (8, 8, TRUE),
    (10, 8, TRUE);

-- =========================
-- PERFORMANCE INDICES
-- =========================

-- IDX01: Notifications by user
CREATE INDEX notification_user ON notification USING btree (user_id);

-- IDX02: Schedules by space
CREATE INDEX schedule_space ON schedule USING btree (space_id);

-- IDX03: Booking history by customer
CREATE INDEX booking_history ON booking USING btree (customer_id);

-- IDX04: Spaces by sport type
CREATE INDEX space_sport_type ON space USING btree (sport_type_id);

-- =========================
-- FULL-TEXT SEARCH INDEX
-- =========================

--add a column to 'space' to store computed ts_vectors
ALTER TABLE space ADD COLUMN tsvectors TSVECTOR;

--create a function to automatically update ts_vectors
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

--create a trigger to automatically update the ts_vectors before insert or update
CREATE TRIGGER space_search_update
    BEFORE INSERT OR UPDATE ON space
    FOR EACH ROW
EXECUTE FUNCTION space_search_update();

--create the GIN index for the ts_vectors column
CREATE INDEX search_space_idx ON space USING GIN (tsvectors);

-- =========================
-- BUSINESS LOGIC TRIGGERS
-- =========================
DROP TRIGGER IF EXISTS num_favorites_update ON favorited;

DROP FUNCTION IF EXISTS update_num_favorites ();

DROP TRIGGER IF EXISTS review_num_update ON review;

DROP FUNCTION IF EXISTS update_num_reviews ();

-- TRIGGER01: Update num_reviews when review is inserted/deleted
CREATE FUNCTION update_num_reviews() RETURNS TRIGGER AS $$
DECLARE
    v_space_id INT;
BEGIN
    --when a new review is inserted
    IF (TG_OP = 'INSERT') THEN
        -- get the space_id from the booking association
        SELECT b.space_id INTO v_space_id
        FROM booking b
        WHERE b.id = NEW.booking_id;
    END IF;

    --when a review is deleted
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
$$
    LANGUAGE plpgsql;

--trigger on the review table
CREATE TRIGGER review_num_update
    AFTER INSERT OR DELETE ON review
    FOR EACH ROW
EXECUTE FUNCTION update_num_reviews();

-- TRIGGER02: Update num_favorites when space is favorited/unfavorited
--function to update favorite num
CREATE FUNCTION update_num_favorites() RETURNS TRIGGER AS $$
BEGIN
    --when a space is favorited
    IF (TG_OP = 'INSERT') THEN
        UPDATE space
        SET num_favorites = num_favorites + 1
        WHERE id = NEW.space_id;
    END IF;

    --when a favorite is removed
    IF (TG_OP = 'DELETE') THEN
        UPDATE space
        SET num_favorites = num_favorites - 1
        WHERE id = OLD.space_id;
    END IF;

    RETURN NULL;
END;
$$
    LANGUAGE plpgsql;

--trigger on the favorite table
CREATE TRIGGER num_favorites_update
    AFTER INSERT OR DELETE ON favorited
    FOR EACH ROW
EXECUTE FUNCTION update_num_favorites();

-- TRIGGER03: Anonymize user data when deleted
-- Function to set the attribute "is_deleted" TRUE
CREATE FUNCTION update_is_deleted() RETURNS TRIGGER AS
$$
BEGIN

   UPDATE "user"
   SET is_deleted = TRUE,
       user_name = 'Deleted user',
       email = 'deleted_user_' || OLD.id || '@example.com',
       phone_no = 'deleted_user_' || OLD.id,
       password = 'N/A',
       birth_date = '0001-01-01',
       profile_pic_url = 'N/A'
   WHERE id = OLD.id;

   RETURN NULL;
END;
$$
LANGUAGE plpgsql;

-- Trigger on the user table
CREATE TRIGGER is_deleted_update
    BEFORE DELETE ON "user"
    FOR EACH ROW
EXECUTE FUNCTION update_is_deleted();

-- TRIGGER04: Anonymize space data when permanently closed
CREATE FUNCTION anonymize_closed_space()
    RETURNS TRIGGER AS $$
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

-- Trigger on the space table
CREATE TRIGGER space_closure_anonymize
    BEFORE UPDATE ON space
    FOR EACH ROW
    WHEN (NEW.is_closed = TRUE AND OLD.is_closed = FALSE)
EXECUTE FUNCTION anonymize_closed_space();