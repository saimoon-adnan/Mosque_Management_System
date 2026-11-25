-- ============================================================
--  Masjid Management System (simple version) - PostgreSQL
--  3 ta table: member, event, donation
--
--  Kivabe chalaben:
--    1. pgAdmin e mosque_simple naam e ekta database banan
--    2. mosque_simple er upor right click > Query Tool
--    3. Niche STEP 1, STEP 2, STEP 3 alada alada select kore
--       Run (F5) korte paren (sir ke ekta ekta kore dekhanor jonno)
--       ba puro file ek sathe Run korte paren
-- ============================================================

-- (Optional) purono table muche abar notun kore banate chaile:
DROP TABLE IF EXISTS donation, event, member CASCADE;


-- ============================================================
-- STEP 1: member table (CRUD)
-- ============================================================
CREATE TABLE member (
    member_id  SERIAL PRIMARY KEY,           -- protita member er unique number (auto)
    name       VARCHAR(100) NOT NULL,        -- naam, faka rakha jabe na
    phone      VARCHAR(20),
    address    VARCHAR(150),
    role       VARCHAR(20) NOT NULL
               CHECK (role IN ('Imam', 'Muazzin', 'Committee', 'Regular'))
);

INSERT INTO member (name, phone, address, role) VALUES
('Abdul Karim',     '01711-000001', 'Tungi',  'Imam'),
('Md. Rahim Uddin', '01811-000002', 'Uttara', 'Muazzin'),
('Jamal Hossain',   '01911-000003', 'Tungi',  'Committee'),
('Shafiqul Islam',  '01511-000005', 'Tungi',  'Regular');

-- dekhar jonno:
-- SELECT * FROM member;


-- ============================================================
-- STEP 2: event table (CRUD)
-- ============================================================
CREATE TABLE event (
    event_id    SERIAL PRIMARY KEY,
    event_name  VARCHAR(50) NOT NULL,
    event_date  DATE NOT NULL,
    location    VARCHAR(100)
);

INSERT INTO event (event_name, event_date, location) VALUES
('Iftar',      '2026-03-05', 'Mosque courtyard'),
('Eid Jamaat', '2026-03-20', 'Eidgah field'),
('Milad',      '2026-09-25', 'Main hall');

-- dekhar jonno:
-- SELECT * FROM event;


-- ============================================================
-- STEP 3: donation table (JOIN)
--   member_id  -> member table er sathe link (kon member dilo)
--   event_id   -> event table er sathe link  (kon event er jonno)
-- ============================================================
CREATE TABLE donation (
    donation_id    SERIAL PRIMARY KEY,
    member_id      INT NOT NULL REFERENCES member(member_id),
    event_id       INT NOT NULL REFERENCES event(event_id),
    amount         NUMERIC(10,2) NOT NULL CHECK (amount > 0),
    donation_date  DATE NOT NULL DEFAULT CURRENT_DATE
);

INSERT INTO donation (member_id, event_id, amount, donation_date) VALUES
(3, 1, 5000, '2026-03-01'),
(4, 1, 2000, '2026-03-02'),
(4, 2,  500, '2026-03-20'),
(3, 3, 1500, '2026-09-20'),
(1, 3, 1000, '2026-09-21');


-- ============================================================
-- JOIN query: donation er sathe member er naam, phone ar event er naam
-- (donations.php page e ei query-i chole)
-- ============================================================
SELECT d.donation_id, m.name AS member_name, m.phone AS member_phone, e.event_name, d.amount, d.donation_date
FROM donation d
JOIN member m ON d.member_id = m.member_id
JOIN event  e ON d.event_id  = e.event_id
ORDER BY d.donation_id;