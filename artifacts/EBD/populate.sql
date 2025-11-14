INSERT INTO
    "user" (
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
        'Cade Le',
        'dolor.nulla@protonmail.couk',
        '954529117',
        FALSE,
        FALSE,
        'PDF81OMZ4CZ',
        '1960-01-23',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Miriam Hoover',
        'nibh.quisque.nonummy@hotmail.edu',
        '962922371',
        FALSE,
        TRUE,
        'NXV25EBT2MC',
        '1989-04-05',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Lysandra Wise',
        'ullamcorper@aol.org',
        '931042084',
        FALSE,
        TRUE,
        'HIC92UVB1RM',
        '1963-08-24',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Alfreda Curtis',
        'sed@aol.net',
        '972456831',
        FALSE,
        TRUE,
        'QIR00JTE7MK',
        '2001-12-10',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Constance Yates',
        'interdum@protonmail.ca',
        '989628425',
        TRUE,
        TRUE,
        'YHP48KYD7QH',
        '1965-05-27',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Nita Jennings',
        'auctor.ullamcorper.nisl@icloud.edu',
        '973066023',
        FALSE,
        FALSE,
        'XJC87JZZ1PJ',
        '1986-02-05',
        'https://activehub/uploads/picture.jpeg'
    ),
    (
        'Lucy Schroeder',
        'quisque.tincidunt@hotmail.couk',
        '979822651',
        TRUE,
        FALSE,
        'URS83TKB1JK',
        '1990-12-28',
        'https://activehub/uploads/profile.png'
    ),
    (
        'Seth David',
        'mauris.magna@protonmail.ca',
        '963088105',
        TRUE,
        FALSE,
        'RBP99EEH9HY',
        '1971-09-09',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Felicia Hubbard',
        'et.magnis@outlook.edu',
        '903518505',
        FALSE,
        FALSE,
        'YNY29HYN4EF',
        '1953-08-31',
        'https://activehub/uploads/profile.png'
    ),
    (
        'Kamal Burnett',
        'convallis@yahoo.couk',
        '930572268',
        FALSE,
        FALSE,
        'JQD86QMT1PV',
        '1979-12-24',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Murphy Cunningham',
        'mauris.sapien.cursus@hotmail.org',
        '975166436',
        FALSE,
        TRUE,
        'BDK84KFB0BM',
        '2006-10-12',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Peter Haynes',
        'tortor.at.risus@protonmail.edu',
        '912031257',
        FALSE,
        FALSE,
        'TXL28IPE7HU',
        '1952-12-19',
        'https://activehub/uploads/profile.png'
    ),
    (
        'Xaviera Williams',
        'eget@google.net',
        '986863406',
        TRUE,
        TRUE,
        'JGX59DHI7EX',
        '1975-02-26',
        'https://activehub/uploads/picture.jpeg'
    ),
    (
        'Tatiana Trujillo',
        'curabitur@aol.ca',
        '931704357',
        FALSE,
        FALSE,
        'PPX44LRY9TW',
        '1987-06-28',
        'https://activehub/uploads/picture.jpeg'
    ),
    (
        'Gage Ramos',
        'congue@outlook.org',
        '995219633',
        TRUE,
        FALSE,
        'EKV03QXK6BT',
        '1961-03-18',
        'https://activehub/uploads/profile.png'
    ),
    (
        'Cynthia Barnett',
        'dictum.sapien.aenean@protonmail.edu',
        '998339655',
        TRUE,
        FALSE,
        'RBR82JEP2WS',
        '1998-06-24',
        'https://activehub/uploads/picture.jpeg'
    ),
    (
        'Imani Wilkinson',
        'lorem.eu@yahoo.edu',
        '940636822',
        TRUE,
        TRUE,
        'CLM27NIQ8IO',
        '1959-07-29',
        'https://activehub/uploads/picture.png'
    ),
    (
        'Ebony Hill',
        'nulla@outlook.ca',
        '973648036',
        TRUE,
        FALSE,
        'AFA68UOX0FY',
        '1973-05-12',
        'https://activehub/uploads/profile.png'
    ),
    (
        'Jasper Briggs',
        'sem.ut@protonmail.net',
        '917847326',
        TRUE,
        FALSE,
        'TUP96PDI4DM',
        '1966-08-28',
        'https://activehub/uploads/profile.png'
    ),
    (
        'Ulric Vasquez',
        'amet@aol.ca',
        '923223688',
        FALSE,
        TRUE,
        'TDN80GTU5DJ',
        '2002-02-09',
        'https://activehub/uploads/picture.jpeg'
    );

INSERT INTO
    business_owner (user_id)
VALUES (1),
    (2),
    (3),
    (4),
    (5);

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
        description,
        is_closed,
        phone_no,
        email,
        num_favorites,
        num_reviews
    )
VALUES (
        3,
        5,
        'Football field near Porto''s downtown',
        'Rua das Flores 123, Porto',
        'Well-maintained grass field suitable for 5v5 and 7v7 matches.',
        FALSE,
        '912345678',
        'porto_field@mail.com',
        15,
        4
    ),
    (
        2,
        1,
        'Badminton Court Boavista',
        'Avenida da Boavista 540, Porto',
        'Indoor badminton court with great lighting and wooden flooring.',
        FALSE,
        '911223344',
        'boavista_badminton@mail.com',
        9,
        2
    ),
    (
        1,
        7,
        'Iron Gym Central',
        'Rua de Cedofeita 88, Porto',
        'Fully equipped gym with modern machines and personal training available.',
        FALSE,
        '935678901',
        'iron_gym@mail.com',
        22,
        8
    ),
    (
        4,
        3,
        'Gaia Biking Park',
        'Rua de Gaia 250, Vila Nova de Gaia',
        'Large biking park with trails for beginners and pros.',
        FALSE,
        '937654321',
        'gaia_bike@mail.com',
        17,
        6
    ),
    (
        3,
        2,
        'Downtown Basketball Court',
        'Praça da República 12, Porto',
        'Outdoor basketball court open to public, newly resurfaced.',
        FALSE,
        '934567890',
        'downtown_bball@mail.com',
        30,
        10
    ),
    (
        1,
        6,
        'Golf Club Foz',
        'Avenida Brasil 999, Foz do Douro',
        'Private golf course with ocean view.',
        FALSE,
        '938888777',
        'foz_golf@mail.com',
        12,
        3
    ),
    (
        5,
        11,
        'Padel Arena Porto',
        'Rua da Constituição 150, Porto',
        'Two indoor padel courts with locker rooms and café.',
        FALSE,
        '936789123',
        'padel_arena@mail.com',
        25,
        9
    ),
    (
        3,
        15,
        'Indoor Swimming Complex',
        'Rua da Alegria 720, Porto',
        'Olympic-sized pool and training lanes, lifeguards on duty.',
        FALSE,
        '932112233',
        'swim_complex@mail.com',
        19,
        7
    ),
    (
        5,
        4,
        'Climbing Zone Campanhã',
        'Rua do Heroísmo 200, Porto',
        'Indoor climbing gym with bouldering and top rope walls.',
        FALSE,
        '931445566',
        'climb_zone@mail.com',
        14,
        5
    ),
    (
        3,
        9,
        'Hockey Arena Norte',
        'Rua de Santo Ildefonso 340, Porto',
        'Indoor hockey arena hosting local matches and training.',
        FALSE,
        '939334455',
        'hockey_norte@mail.com',
        8,
        3
    ),
    (
        1,
        13,
        'Riverside Running Track',
        'Cais de Gaia, Vila Nova de Gaia',
        'Beautiful riverside track perfect for daily runs.',
        FALSE,
        '937223344',
        'riverside_run@mail.com',
        20,
        6
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
        '2025-12-01 18:00:00',
        90,
        14
    ),
    (
        1,
        '2025-12-02 19:00:00',
        90,
        14
    ),
    (
        1,
        '2025-12-03 20:00:00',
        120,
        14
    ),
    (
        1,
        '2025-12-04 18:30:00',
        90,
        14
    ),
    (
        1,
        '2025-12-05 17:00:00',
        90,
        14
    ),
    (
        1,
        '2025-12-06 19:30:00',
        120,
        14
    ),
    (
        1,
        '2025-12-07 18:00:00',
        90,
        14
    ),
    (
        1,
        '2025-12-08 20:00:00',
        90,
        14
    ),
    (
        1,
        '2025-12-09 17:30:00',
        90,
        14
    ),
    (
        1,
        '2025-12-10 19:00:00',
        120,
        14
    ),
    (
        2,
        '2025-12-02 17:30:00',
        60,
        4
    ),
    (
        2,
        '2025-12-05 19:00:00',
        60,
        4
    ),
    (
        3,
        '2025-12-01 08:00:00',
        90,
        20
    ),
    (
        3,
        '2025-12-02 18:00:00',
        120,
        25
    ),
    (
        4,
        '2025-12-07 10:00:00',
        180,
        30
    ),
    (
        4,
        '2025-12-10 14:00:00',
        120,
        25
    ),
    (
        5,
        '2025-12-06 16:00:00',
        90,
        10
    ),
    (
        5,
        '2025-12-09 18:30:00',
        90,
        12
    ),
    (
        6,
        '2025-12-08 09:00:00',
        240,
        20
    ),
    (
        6,
        '2025-12-15 13:30:00',
        240,
        18
    ),
    (
        7,
        '2025-12-03 19:00:00',
        60,
        8
    ),
    (
        7,
        '2025-12-04 20:30:00',
        60,
        8
    ),
    (
        8,
        '2025-12-02 07:30:00',
        90,
        30
    ),
    (
        8,
        '2025-12-06 15:00:00',
        90,
        25
    ),
    (
        9,
        '2025-12-05 18:00:00',
        120,
        15
    ),
    (
        9,
        '2025-12-08 10:00:00',
        150,
        12
    ),
    (
        10,
        '2025-12-09 19:30:00',
        90,
        22
    ),
    (
        10,
        '2025-12-11 21:00:00',
        90,
        20
    ),
    (
        11,
        '2025-12-10 07:00:00',
        60,
        40
    ),
    (
        11,
        '2025-12-12 18:00:00',
        60,
        35
    ),

-- Space 2: Badminton Court (10 schedules)
(
    2,
    '2025-12-01 17:30:00',
    60,
    4
),
(
    2,
    '2025-12-02 18:30:00',
    60,
    4
),
(
    2,
    '2025-12-03 19:00:00',
    60,
    4
),
(
    2,
    '2025-12-04 17:00:00',
    60,
    4
),
(
    2,
    '2025-12-05 19:00:00',
    60,
    4
),
(
    2,
    '2025-12-06 18:00:00',
    60,
    4
),
(
    2,
    '2025-12-07 17:30:00',
    60,
    4
),
(
    2,
    '2025-12-08 19:30:00',
    60,
    4
),
(
    2,
    '2025-12-09 18:00:00',
    60,
    4
),
(
    2,
    '2025-12-10 17:00:00',
    60,
    4
),

-- Space 3: Iron Gym (10 schedules)
(
    3,
    '2025-12-01 08:00:00',
    90,
    20
),
(
    3,
    '2025-12-02 09:00:00',
    120,
    25
),
(
    3,
    '2025-12-03 18:00:00',
    90,
    20
),
(
    3,
    '2025-12-04 07:30:00',
    90,
    20
),
(
    3,
    '2025-12-05 19:00:00',
    120,
    25
),
(
    3,
    '2025-12-06 08:00:00',
    90,
    20
),
(
    3,
    '2025-12-07 10:00:00',
    90,
    20
),
(
    3,
    '2025-12-08 18:30:00',
    120,
    25
),
(
    3,
    '2025-12-09 07:00:00',
    90,
    20
),
(
    3,
    '2025-12-10 19:30:00',
    90,
    20
),

-- Space 4: Gaia Biking Park (10 schedules)
(
    4,
    '2025-12-01 10:00:00',
    180,
    30
),
(
    4,
    '2025-12-02 14:00:00',
    120,
    25
),
(
    4,
    '2025-12-03 09:00:00',
    180,
    30
),
(
    4,
    '2025-12-04 15:00:00',
    120,
    25
),
(
    4,
    '2025-12-05 10:30:00',
    180,
    30
),
(
    4,
    '2025-12-06 13:00:00',
    180,
    30
),
(
    4,
    '2025-12-07 10:00:00',
    120,
    25
),
(
    4,
    '2025-12-08 14:30:00',
    180,
    30
),
(
    4,
    '2025-12-09 09:30:00',
    120,
    25
),
(
    4,
    '2025-12-10 14:00:00',
    180,
    30
),

-- Space 5: Downtown Basketball Court (10 schedules)
(
    5,
    '2025-12-01 16:00:00',
    90,
    10
),
(
    5,
    '2025-12-02 18:30:00',
    90,
    12
),
(
    5,
    '2025-12-03 17:00:00',
    90,
    10
),
(
    5,
    '2025-12-04 19:00:00',
    90,
    12
),
(
    5,
    '2025-12-05 16:30:00',
    90,
    10
),
(
    5,
    '2025-12-06 18:00:00',
    90,
    12
),
(
    5,
    '2025-12-07 17:30:00',
    90,
    10
),
(
    5,
    '2025-12-08 19:30:00',
    90,
    12
),
(
    5,
    '2025-12-09 16:00:00',
    90,
    10
),
(
    5,
    '2025-12-10 18:30:00',
    90,
    12
),

-- Space 6: Golf Club Foz (10 schedules)
(
    6,
    '2025-12-01 09:00:00',
    240,
    20
),
(
    6,
    '2025-12-02 13:30:00',
    240,
    18
),
(
    6,
    '2025-12-03 08:30:00',
    240,
    20
),
(
    6,
    '2025-12-04 14:00:00',
    240,
    18
),
(
    6,
    '2025-12-05 09:30:00',
    240,
    20
),
(
    6,
    '2025-12-06 13:00:00',
    240,
    18
),
(
    6,
    '2025-12-07 08:00:00',
    240,
    20
),
(
    6,
    '2025-12-08 14:30:00',
    240,
    18
),
(
    6,
    '2025-12-09 09:00:00',
    240,
    20
),
(
    6,
    '2025-12-10 13:30:00',
    240,
    18
),

-- Space 7: Padel Arena Porto (10 schedules)
(
    7,
    '2025-12-01 19:00:00',
    60,
    8
),
(
    7,
    '2025-12-02 20:30:00',
    60,
    8
),
(
    7,
    '2025-12-03 18:30:00',
    60,
    8
),
(
    7,
    '2025-12-04 19:30:00',
    60,
    8
),
(
    7,
    '2025-12-05 20:00:00',
    60,
    8
),
(
    7,
    '2025-12-06 18:00:00',
    60,
    8
),
(
    7,
    '2025-12-07 19:00:00',
    60,
    8
),
(
    7,
    '2025-12-08 20:30:00',
    60,
    8
),
(
    7,
    '2025-12-09 18:30:00',
    60,
    8
),
(
    7,
    '2025-12-10 19:30:00',
    60,
    8
),

-- Space 8: Indoor Swimming Complex (10 schedules)
(
    8,
    '2025-12-01 07:30:00',
    90,
    30
),
(
    8,
    '2025-12-02 15:00:00',
    90,
    25
),
(
    8,
    '2025-12-03 08:00:00',
    90,
    30
),
(
    8,
    '2025-12-04 16:00:00',
    90,
    25
),
(
    8,
    '2025-12-05 07:00:00',
    90,
    30
),
(
    8,
    '2025-12-06 15:30:00',
    90,
    25
),
(
    8,
    '2025-12-07 08:30:00',
    90,
    30
),
(
    8,
    '2025-12-08 14:30:00',
    90,
    25
),
(
    8,
    '2025-12-09 07:30:00',
    90,
    30
),
(
    8,
    '2025-12-10 15:00:00',
    90,
    25
),

-- Space 9: Climbing Zone Campanhã (10 schedules)
(
    9,
    '2025-12-01 18:00:00',
    120,
    15
),
(
    9,
    '2025-12-02 10:00:00',
    150,
    12
),
(
    9,
    '2025-12-03 19:00:00',
    120,
    15
),
(
    9,
    '2025-12-04 11:00:00',
    150,
    12
),
(
    9,
    '2025-12-05 18:30:00',
    120,
    15
),
(
    9,
    '2025-12-06 10:30:00',
    150,
    12
),
(
    9,
    '2025-12-07 19:30:00',
    120,
    15
),
(
    9,
    '2025-12-08 09:30:00',
    150,
    12
),
(
    9,
    '2025-12-09 18:00:00',
    120,
    15
),
(
    9,
    '2025-12-10 10:00:00',
    150,
    12
),

-- Space 10: Hockey Arena Norte (10 schedules)
(
    10,
    '2025-12-01 19:30:00',
    90,
    22
),
(
    10,
    '2025-12-02 21:00:00',
    90,
    20
),
(
    10,
    '2025-12-03 20:00:00',
    90,
    22
),
(
    10,
    '2025-12-04 19:00:00',
    90,
    20
),
(
    10,
    '2025-12-05 20:30:00',
    90,
    22
),
(
    10,
    '2025-12-06 21:30:00',
    90,
    20
),
(
    10,
    '2025-12-07 19:30:00',
    90,
    22
),
(
    10,
    '2025-12-08 20:00:00',
    90,
    20
),
(
    10,
    '2025-12-09 21:00:00',
    90,
    22
),
(
    10,
    '2025-12-10 19:30:00',
    90,
    20
),

-- Space 11: Riverside Running Track (10 schedules)
(
    11,
    '2025-12-01 07:00:00',
    60,
    40
),
(
    11,
    '2025-12-02 18:00:00',
    60,
    35
),
(
    11,
    '2025-12-03 06:30:00',
    60,
    40
),
(
    11,
    '2025-12-04 17:30:00',
    60,
    35
),
(
    11,
    '2025-12-05 07:30:00',
    60,
    40
),
(
    11,
    '2025-12-06 18:30:00',
    60,
    35
),
(
    11,
    '2025-12-07 07:00:00',
    60,
    40
),
(
    11,
    '2025-12-08 17:00:00',
    60,
    35
),
(
    11,
    '2025-12-09 06:30:00',
    60,
    40
),
(
    11,
    '2025-12-10 18:00:00',
    60,
    35
);

INSERT INTO
    booking (
        space_id,
        customer_id,
        schedule_id,
        booking_created_at,
        is_cancelled
    )
VALUES (
        1,
        1,
        1,
        '2025-11-01 14:25:00',
        FALSE
    ),
    (
        1,
        2,
        2,
        '2025-11-02 10:00:00',
        FALSE
    ),
    (
        2,
        3,
        3,
        '2025-11-02 11:00:00',
        FALSE
    ),
    (
        2,
        4,
        4,
        '2025-11-03 15:30:00',
        TRUE
    ),
    (
        3,
        5,
        5,
        '2025-11-04 09:20:00',
        FALSE
    ),
    (
        3,
        6,
        6,
        '2025-11-04 12:10:00',
        FALSE
    ),
    (
        4,
        7,
        7,
        '2025-11-05 08:00:00',
        FALSE
    ),
    (
        4,
        8,
        8,
        '2025-11-05 16:10:00',
        TRUE
    ),
    (
        5,
        9,
        9,
        '2025-11-06 13:45:00',
        FALSE
    ),
    (
        5,
        10,
        10,
        '2025-11-07 14:30:00',
        FALSE
    ),
    (
        6,
        11,
        11,
        '2025-11-07 18:00:00',
        FALSE
    ),
    (
        7,
        12,
        12,
        '2025-11-08 10:00:00',
        FALSE
    ),
    (
        8,
        13,
        13,
        '2025-11-08 15:30:00',
        TRUE
    ),
    (
        9,
        14,
        14,
        '2025-11-09 17:00:00',
        FALSE
    ),
    (
        10,
        15,
        15,
        '2025-11-09 18:15:00',
        FALSE
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
        '2025-12-02 20:00:00',
        'Great football pitch and lighting, perfect for 7v7 games!',
        5,
        4,
        5
    ),
    (
        2,
        2,
        '2025-12-03 21:10:00',
        'Nice field but locker rooms could be cleaner.',
        4,
        4,
        3
    ),
    (
        3,
        3,
        '2025-12-03 19:30:00',
        'Loved the indoor court. Great surface and friendly staff.',
        5,
        5,
        5
    ),
    (
        5,
        5,
        '2025-12-04 10:45:00',
        'Good gym atmosphere, though some machines need maintenance.',
        4,
        3,
        4
    ),
    (
        6,
        6,
        '2025-12-04 20:00:00',
        'Top-notch trainers and well-equipped gym!',
        5,
        5,
        5
    ),
    (
        7,
        7,
        '2025-12-05 13:15:00',
        'Beautiful biking trails, great for weekend rides.',
        5,
        4,
        5
    ),
    (
        9,
        9,
        '2025-12-06 18:30:00',
        'Court was clean and the hoops were in great condition.',
        5,
        4,
        4
    ),
    (
        10,
        10,
        '2025-12-07 20:10:00',
        'Good basketball space, but too crowded at times.',
        4,
        4,
        3
    ),
    (
        11,
        11,
        '2025-12-08 14:00:00',
        'Peaceful golf course with an amazing sea view.',
        5,
        5,
        5
    ),
    (
        12,
        7,
        '2025-12-09 11:20:00',
        'Padel courts were excellent, café service a bit slow.',
        4,
        5,
        3
    ),
    (
        14,
        9,
        '2025-12-09 19:45:00',
        'Fun climbing walls and friendly instructors.',
        5,
        4,
        5
    ),
    (
        15,
        10,
        '2025-12-10 22:00:00',
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
        '2025-12-03 10:15:00'
    ),
    (
        1,
        4,
        'Appreciate your comment. We''re scheduling maintenance on those machines next week.',
        '2025-12-04 12:00:00'
    ),
    (
        5,
        6,
        'Happy to hear you liked the trails! We''re adding new routes next season.',
        '2025-12-05 16:45:00'
    ),
    (
        3,
        7,
        'Thanks for visiting! We''ll work on improving the lighting for evening games.',
        '2025-12-06 20:30:00'
    ),
    (
        3,
        10,
        'We''re happy you liked the courts! Sorry for the café wait — we''re hiring more staff.',
        '2025-12-09 13:10:00'
    ),
    (
        3,
        12,
        'Thank you for your feedback! We''ll renovate the locker rooms early next year.',
        '2025-12-11 09:40:00'
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
        '2025-11-25 00:00:00',
        '2025-12-10 23:59:00'
    ),
    (
        3,
        10.0,
        '2025-11-20 00:00:00',
        '2025-12-05 23:59:00'
    ),
    (
        5,
        20.0,
        '2025-12-01 00:00:00',
        '2025-12-07 23:59:00'
    ),
    (
        7,
        25.0,
        '2025-12-03 00:00:00',
        '2025-12-20 23:59:00'
    ),
    (
        8,
        30.0,
        '2025-11-29 00:00:00',
        '2025-12-15 23:59:00'
    );

INSERT INTO
    payment (
        booking_id,
        value,
        is_discounted,
        is_accepted,
        payment_provider_ref,
        time_stamp
    )
VALUES (
        1,
        25.50,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-01 14:30:00'
    ),
    (
        2,
        28.00,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-02 10:05:00'
    ),
    (
        3,
        12.00,
        TRUE,
        TRUE,
        'MB Way',
        '2025-11-02 11:05:00'
    ),
    (
        5,
        18.90,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-04 09:25:00'
    ),
    (
        6,
        21.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-04 12:20:00'
    ),
    (
        7,
        30.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-05 08:10:00'
    ),
    (
        9,
        10.00,
        TRUE,
        TRUE,
        'Paypal',
        '2025-11-06 13:50:00'
    ),
    (
        10,
        10.00,
        TRUE,
        FALSE,
        'MB Way',
        '2025-11-07 14:40:00'
    ),
    (
        11,
        45.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-07 18:10:00'
    ),
    (
        7,
        20.00,
        TRUE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-08 10:15:00'
    ),
    (
        8,
        15.00,
        TRUE,
        FALSE,
        'Paypal',
        '2025-11-08 15:35:00'
    ),
    (
        9,
        18.00,
        FALSE,
        TRUE,
        'Credit/Debit Card',
        '2025-11-09 17:10:00'
    ),
    (
        10,
        25.00,
        FALSE,
        TRUE,
        'MB Way',
        '2025-11-09 18:20:00'
    ),
    (
        4,
        12.00,
        FALSE,
        FALSE,
        'Paypal',
        '2025-11-03 15:35:00'
    ),
    (
        8,
        20.00,
        FALSE,
        FALSE,
        'Credit/Debit Card',
        '2025-11-05 16:15:00'
    );

INSERT INTO
    notification (user_id, time_stamp, is_read)
VALUES (
        2,
        '2025-11-01 14:35:00',
        TRUE
    ),
    (
        3,
        '2025-11-02 10:10:00',
        TRUE
    ),
    (
        4,
        '2025-11-02 19:45:00',
        TRUE
    ),
    (
        5,
        '2025-11-03 15:40:00',
        TRUE
    ),
    (
        8,
        '2025-11-04 09:30:00',
        TRUE
    ),
    (
        9,
        '2025-11-04 12:25:00',
        TRUE
    ),
    (
        10,
        '2025-11-05 08:15:00',
        TRUE
    ),
    (
        12,
        '2025-11-05 16:20:00',
        TRUE
    ),
    (
        13,
        '2025-11-06 13:55:00',
        TRUE
    ),
    (
        15,
        '2025-11-07 14:45:00',
        TRUE
    ),
    (
        16,
        '2025-11-07 18:10:00',
        TRUE
    ),
    (
        17,
        '2025-11-08 10:20:00',
        TRUE
    ),
    (
        18,
        '2025-11-08 15:40:00',
        TRUE
    ),
    (
        19,
        '2025-11-09 17:15:00',
        TRUE
    ),
    (
        20,
        '2025-11-09 18:25:00',
        TRUE
    ),
    (
        3,
        '2025-12-03 21:30:00',
        FALSE
    ),
    (
        9,
        '2025-12-04 20:10:00',
        FALSE
    ),
    (
        10,
        '2025-12-05 13:20:00',
        FALSE
    ),
    (
        19,
        '2025-12-09 20:00:00',
        FALSE
    ),
    (
        20,
        '2025-12-10 22:10:00',
        FALSE
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
        'https://activehub/uploads/spaces/football_field_cover.jpg',
        TRUE
    ),
    (
        1,
        'https://activehub/uploads/spaces/football_field_inside.jpg',
        FALSE
    ),
    (
        2,
        'https://activehub/uploads/spaces/badminton_boavista_cover.jpg',
        TRUE
    ),
    (
        2,
        'https://activehub/uploads/spaces/badminton_boavista_inside.jpg',
        FALSE
    ),
    (
        3,
        'https://activehub/uploads/spaces/iron_gym_cover.jpg',
        TRUE
    ),
    (
        3,
        'https://activehub/uploads/spaces/iron_gym_equipment.jpg',
        FALSE
    ),
    (
        4,
        'https://activehub/uploads/spaces/gaia_biking_cover.jpg',
        TRUE
    ),
    (
        4,
        'https://activehub/uploads/spaces/gaia_biking_trail.jpg',
        FALSE
    ),
    (
        5,
        'https://activehub/uploads/spaces/downtown_basketball_cover.jpg',
        TRUE
    ),
    (
        5,
        'https://activehub/uploads/spaces/downtown_basketball_court.jpg',
        FALSE
    ),
    (
        6,
        'https://activehub/uploads/spaces/golf_club_foz_cover.jpg',
        TRUE
    ),
    (
        6,
        'https://activehub/uploads/spaces/golf_club_foz_course.jpg',
        FALSE
    ),
    (
        7,
        'https://activehub/uploads/spaces/padel_arena_cover.jpg',
        TRUE
    ),
    (
        7,
        'https://activehub/uploads/spaces/padel_arena_inside.jpg',
        FALSE
    ),
    (
        8,
        'https://activehub/uploads/spaces/swimming_complex_cover.jpg',
        TRUE
    ),
    (
        8,
        'https://activehub/uploads/spaces/swimming_complex_pool.jpg',
        FALSE
    ),
    (
        9,
        'https://activehub/uploads/spaces/climbing_zone_cover.jpg',
        TRUE
    ),
    (
        9,
        'https://activehub/uploads/spaces/climbing_zone_wall.jpg',
        FALSE
    ),
    (
        10,
        'https://activehub/uploads/spaces/hockey_arena_cover.jpg',
        TRUE
    ),
    (
        10,
        'https://activehub/uploads/spaces/hockey_arena_inside.jpg',
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