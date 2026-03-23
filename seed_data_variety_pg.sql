-- One-time class seed data (PostgreSQL)
-- 10 students, admin data, plans, health history, and 3-4 payments per student.

BEGIN;

ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(100);

CREATE TABLE IF NOT EXISTS member_login (
    userid VARCHAR(20) PRIMARY KEY,
    pass_key VARCHAR(50) NOT NULL,
    CONSTRAINT member_login_user FOREIGN KEY (userid) REFERENCES users(userid) ON DELETE CASCADE
);

TRUNCATE TABLE
    sports_timetable,
    address,
    health_status,
    enrolls_to,
    member_login,
    log_users,
    users,
    plan,
    admin
RESTART IDENTITY CASCADE;

INSERT INTO admin (username, pass_key, securekey, "Full_name") VALUES
('admin1', 'admin1', 'admin1', 'Sports Club Manager'),
('admin2', 'admin2', 'admin2', 'Deputy Manager'),
('coach1', 'coach1', 'coach1', 'Assistant Coach');

INSERT INTO plan (pid, "planName", description, validity, amount, active) VALUES
('FOQKJF', 'Football Plan', 'Football sessions with tactical drills and fitness tracking.', '1', 1000, 'yes'),
('COQKJC', 'Cricket Plan', 'Cricket nets, bowling machine support, and match practice.', '1', 1500, 'yes'),
('BOQKJB', 'Badminton Plan', 'Badminton training for singles and doubles with coaching.', '1', 800, 'yes'),
('SWQKSA', 'Swimming Plan', 'Swimming endurance and technique sessions with coach support.', '2', 2200, 'yes'),
('YGQKSG', 'Yoga Plan', 'Mobility, strength, and breathing sessions for recovery.', '1', 900, 'yes'),
('TNQKST', 'Tennis Plan', 'Tennis footwork, rallies, and competitive practice.', '2', 1800, 'yes');

INSERT INTO users (userid, username, gender, mobile, email, dob, joining_date) VALUES
('2600001001', 'Aarav Mehta', 'Male', '9876543210', 'aarav@club.in', '1996-02-11', '2026-01-05'),
('2600001002', 'Nisha Roy', 'Female', '9811122233', 'nisha@club.in', '1999-08-23', '2026-01-20'),
('2600001003', 'Riley Das', 'Other', '9899001122', 'riley@club.in', '2001-11-14', '2026-02-03'),
('2600001004', 'Kabir Jain', 'Male', '9822211199', 'kabir@club.in', '1997-05-09', '2026-02-15'),
('2600001005', 'Sana Ali', 'Female', '9833300011', 'sana@club.in', '2000-07-01', '2026-03-01'),
('2600001006', 'Dev Verma', 'Male', '9844400022', 'dev@club.in', '1998-03-18', '2026-03-05'),
('2600001007', 'Mira Sen', 'Female', '9855500033', 'mira@club.in', '2002-04-22', '2026-03-09'),
('2600001008', 'Tara Kapoor', 'Female', '9866600044', 'tara@club.in', '1995-12-30', '2026-03-12'),
('2600001009', 'Zaid Khan', 'Male', '9877700055', 'zaid@club.in', '1994-10-16', '2026-03-15'),
('2600001010', 'Ira Bose', 'Female', '9888800066', 'ira@club.in', '2001-06-02', '2026-03-20');

INSERT INTO member_login (userid, pass_key) VALUES
('2600001001', 'aarav123'),
('2600001002', 'nisha123'),
('2600001003', 'riley123'),
('2600001004', 'kabir123'),
('2600001005', 'sana123'),
('2600001006', 'dev12345'),
('2600001007', 'mira1234'),
('2600001008', 'tara1234'),
('2600001009', 'zaid1234'),
('2600001010', 'ira12345');

INSERT INTO address (id, "streetName", state, city, zipcode) VALUES
('2600001001', '14 Lake View Road', 'MH', 'Pune', '411001'),
('2600001002', '22 Green Park', 'DL', 'Delhi', '110016'),
('2600001003', '7 River Side', 'WB', 'Kolkata', '700091'),
('2600001004', '44 Sunrise Lane', 'RJ', 'Jaipur', '302001'),
('2600001005', '9 Palm Street', 'TS', 'Hyderabad', '500081'),
('2600001006', '12 Orchid Avenue', 'KA', 'Bengaluru', '560034'),
('2600001007', '5 Lotus Colony', 'UP', 'Lucknow', '226010'),
('2600001008', '31 Skyline Road', 'TN', 'Chennai', '600042'),
('2600001009', '18 Pearl Enclave', 'GJ', 'Ahmedabad', '380015'),
('2600001010', '27 Maple Residency', 'KL', 'Kochi', '682020');

INSERT INTO health_status (calorie, height, weight, fat, remarks, uid) VALUES
('2400', '178', '76', '18', 'Good stamina, continue cardio.', '2600001001'),
('2350', '178', '75', '17', 'Improved sprint timing.', '2600001001'),
('1900', '163', '59', '25', 'Beginner phase, stay consistent.', '2600001002'),
('1850', '163', '57', '23', 'Flexibility improving.', '2600001002'),
('2100', '170', '68', '21', 'Hydration needs focus.', '2600001003'),
('2050', '170', '67', '20', 'Stable progress.', '2600001003'),
('2500', '182', '84', '22', 'Strength phase started.', '2600001004'),
('2450', '182', '82', '21', 'Fat % moving down.', '2600001004'),
('1750', '160', '54', '24', 'Mobility and posture work needed.', '2600001005'),
('1700', '160', '53', '23', 'Core strength better.', '2600001005'),
('2200', '176', '72', '19', 'Conditioning week complete.', '2600001006'),
('2150', '176', '71', '18', 'Endurance improving.', '2600001006'),
('1800', '165', '58', '26', 'Sleep cycle improvement advised.', '2600001007'),
('1780', '165', '57', '24', 'Recovery better this month.', '2600001007'),
('2000', '168', '62', '22', 'Balanced plan, continue.', '2600001008'),
('1980', '168', '61', '21', 'Steady progress maintained.', '2600001008'),
('2600', '180', '88', '27', 'High muscle gain cycle.', '2600001009'),
('2500', '180', '85', '24', 'Visible fat reduction.', '2600001009'),
('1850', '162', '56', '23', 'Great attendance consistency.', '2600001010'),
('1820', '162', '55', '22', 'Performance improving weekly.', '2600001010');

INSERT INTO enrolls_to (pid, uid, paid_date, expire, renewal) VALUES
-- 2600001001 (3 payments, 2 plans)
('FOQKJF', '2600001001', '2025-12-05', '2026-01-05', 'no'),
('SWQKSA', '2600001001', '2026-01-05', '2026-03-05', 'no'),
('SWQKSA', '2600001001', '2026-03-05', '2026-05-05', 'yes'),

-- 2600001002 (4 payments, 1 plan)
('YGQKSG', '2600001002', '2025-12-20', '2026-01-20', 'no'),
('YGQKSG', '2600001002', '2026-01-20', '2026-02-20', 'no'),
('YGQKSG', '2600001002', '2026-02-20', '2026-03-20', 'no'),
('YGQKSG', '2600001002', '2026-03-20', '2026-04-20', 'yes'),

-- 2600001003 (3 payments, 2 plans)
('TNQKST', '2600001003', '2025-12-03', '2026-02-03', 'no'),
('BOQKJB', '2600001003', '2026-02-03', '2026-03-03', 'no'),
('BOQKJB', '2600001003', '2026-03-03', '2026-04-03', 'yes'),

-- 2600001004 (4 payments, 2 plans)
('COQKJC', '2600001004', '2025-10-15', '2025-11-15', 'no'),
('COQKJC', '2600001004', '2025-11-15', '2025-12-15', 'no'),
('FOQKJF', '2600001004', '2025-12-15', '2026-01-15', 'no'),
('FOQKJF', '2600001004', '2026-01-15', '2026-02-15', 'yes'),

-- 2600001005 (3 payments, 1 plan)
('YGQKSG', '2600001005', '2026-01-01', '2026-02-01', 'no'),
('YGQKSG', '2600001005', '2026-02-01', '2026-03-01', 'no'),
('YGQKSG', '2600001005', '2026-03-01', '2026-04-01', 'yes'),

-- 2600001006 (4 payments, 2 plans)
('FOQKJF', '2600001006', '2025-12-05', '2026-01-05', 'no'),
('FOQKJF', '2600001006', '2026-01-05', '2026-02-05', 'no'),
('TNQKST', '2600001006', '2026-02-05', '2026-04-05', 'no'),
('TNQKST', '2600001006', '2026-04-05', '2026-06-05', 'yes'),

-- 2600001007 (3 payments, 1 plan)
('BOQKJB', '2600001007', '2026-01-09', '2026-02-09', 'no'),
('BOQKJB', '2600001007', '2026-02-09', '2026-03-09', 'no'),
('BOQKJB', '2600001007', '2026-03-09', '2026-04-09', 'yes'),

-- 2600001008 (4 payments, 2 plans)
('SWQKSA', '2600001008', '2025-11-12', '2026-01-12', 'no'),
('SWQKSA', '2600001008', '2026-01-12', '2026-03-12', 'no'),
('YGQKSG', '2600001008', '2026-03-12', '2026-04-12', 'no'),
('YGQKSG', '2600001008', '2026-04-12', '2026-05-12', 'yes'),

-- 2600001009 (3 payments, 2 plans)
('COQKJC', '2600001009', '2026-01-15', '2026-02-15', 'no'),
('COQKJC', '2600001009', '2026-02-15', '2026-03-15', 'no'),
('SWQKSA', '2600001009', '2026-03-15', '2026-05-15', 'yes'),

-- 2600001010 (4 payments, 1 plan)
('YGQKSG', '2600001010', '2025-12-20', '2026-01-20', 'no'),
('YGQKSG', '2600001010', '2026-01-20', '2026-02-20', 'no'),
('YGQKSG', '2600001010', '2026-02-20', '2026-03-20', 'no'),
('YGQKSG', '2600001010', '2026-03-20', '2026-04-20', 'yes');

COMMIT;
