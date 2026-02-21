-- PostgreSQL Schema for Sports Club Management System
-- Clean Final Version

-- Drop tables (order matters because of FK)
DROP TABLE IF EXISTS address CASCADE;
DROP TABLE IF EXISTS sports_timetable CASCADE;
DROP TABLE IF EXISTS enrolls_to CASCADE;
DROP TABLE IF EXISTS health_status CASCADE;
DROP TABLE IF EXISTS log_users CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS plan CASCADE;
DROP TABLE IF EXISTS admin CASCADE;

-- Drop functions
DROP FUNCTION IF EXISTS log_user_action() CASCADE;
DROP FUNCTION IF EXISTS countGender() CASCADE;

-----------------------------------------------------
-- admin
-----------------------------------------------------
CREATE TABLE admin (
    username VARCHAR(20) PRIMARY KEY,
    pass_key VARCHAR(20),
    securekey VARCHAR(20),
    "Full_name" VARCHAR(50)
);

INSERT INTO admin VALUES
('admin1', 'admin1', 'admin1', 'Sports Club Manager'),
('admin2', 'admin2', 'admin2', 'Deputy Manager');

-----------------------------------------------------
-- users
-----------------------------------------------------
CREATE TABLE users (
    userid VARCHAR(20) PRIMARY KEY,
    username VARCHAR(40),
    gender VARCHAR(8),
    mobile VARCHAR(20),
    email VARCHAR(20) UNIQUE,
    dob VARCHAR(10),
    joining_date VARCHAR(10)
);

CREATE INDEX email_idx ON users(email);

INSERT INTO users VALUES
('1529336794', 'Christiana Mayberry', 'Male', '3362013747', 'christiani@gmail.com', '1968-04-13', '2018-06-18'),
('1529336795', 'Shreyansh Gupta', 'Male', '3362013747', 'shreyansh@gmail.com', '1998-12-12', '2020-06-10');

-----------------------------------------------------
-- log_users
-----------------------------------------------------
CREATE TABLE log_users (
    id SERIAL PRIMARY KEY,
    users_userid VARCHAR(20) NOT NULL,
    action VARCHAR(20) NOT NULL,
    cdate TIMESTAMP NOT NULL
);

-----------------------------------------------------
-- health_status
-----------------------------------------------------
CREATE TABLE health_status (
    hid SERIAL PRIMARY KEY,
    calorie VARCHAR(8),
    height VARCHAR(8),
    weight VARCHAR(8),
    fat VARCHAR(8),
    remarks VARCHAR(200),
    uid VARCHAR(20),
    CONSTRAINT health_uid_fk
        FOREIGN KEY (uid)
        REFERENCES users(userid)
        ON DELETE CASCADE
);

CREATE INDEX health_uid_idx ON health_status(uid);

INSERT INTO health_status (calorie, height, weight, fat, remarks, uid) VALUES
(NULL, NULL, NULL, NULL, NULL, '1529336794'),
(NULL, NULL, NULL, NULL, NULL, '1529336795');

-----------------------------------------------------
-- plan
-----------------------------------------------------
CREATE TABLE plan (
    pid VARCHAR(8) PRIMARY KEY,
    "planName" VARCHAR(20),
    description VARCHAR(200),
    validity VARCHAR(20),
    amount INTEGER NOT NULL,
    active VARCHAR(255)
);

INSERT INTO plan VALUES
('FOQKJF', 'Football Plan', 'A monthly subscription that unlocks the members access to the football Plan and coach support on chat.', '1', 1000, 'yes'),
('COQKJC', 'Cricket Plan', 'A monthly subscription that unlocks the members access to the Cricket Plan and coach support on chat.', '1', 1500, 'yes'),
('BOQKJB', 'Badminton Plan', 'A monthly subscription that unlocks the members access to the Badminton Plan and coach support on chat.', '1', 800, 'yes');

-----------------------------------------------------
-- enrolls_to
-----------------------------------------------------
CREATE TABLE enrolls_to (
    et_id SERIAL PRIMARY KEY,
    pid VARCHAR(8),
    uid VARCHAR(20),
    paid_date VARCHAR(15),
    expire VARCHAR(15),
    renewal VARCHAR(15),
    CONSTRAINT enroll_plan_fk
        FOREIGN KEY (pid)
        REFERENCES plan(pid)
        ON DELETE NO ACTION,
    CONSTRAINT enroll_user_fk
        FOREIGN KEY (uid)
        REFERENCES users(userid)
        ON DELETE CASCADE
);

CREATE INDEX enroll_uid_idx ON enrolls_to(uid);
CREATE INDEX enroll_pid_idx ON enrolls_to(pid);

INSERT INTO enrolls_to (pid, uid, paid_date, expire, renewal) VALUES
('FOQKJF', '1529336794', '2018-06-18', '2018-07-18', 'yes');

-----------------------------------------------------
-- sports_timetable
-----------------------------------------------------
CREATE TABLE sports_timetable (
    tid SERIAL PRIMARY KEY,
    tname VARCHAR(45),
    day1 VARCHAR(200),
    day2 VARCHAR(200),
    day3 VARCHAR(200),
    day4 VARCHAR(200),
    day5 VARCHAR(200),
    day6 VARCHAR(200),
    pid VARCHAR(8),
    CONSTRAINT timetable_plan_fk
        FOREIGN KEY (pid)
        REFERENCES plan(pid)
        ON DELETE CASCADE
);

-----------------------------------------------------
-- address
-----------------------------------------------------
CREATE TABLE address (
    id VARCHAR(20),
    "streetName" VARCHAR(40),
    state VARCHAR(15),
    city VARCHAR(15),
    zipcode VARCHAR(20),
    CONSTRAINT address_user_fk
        FOREIGN KEY (id)
        REFERENCES users(userid)
        ON DELETE CASCADE
);

CREATE INDEX address_user_idx ON address(id);

INSERT INTO address VALUES
('1529336794', '2069  Quarry Drive', 'NC', 'Greensboro', '27409');

-----------------------------------------------------
-- Trigger Function
-----------------------------------------------------
CREATE OR REPLACE FUNCTION log_user_action()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        INSERT INTO log_users (users_userid, action, cdate)
        VALUES (OLD.userid, 'deleted', CURRENT_TIMESTAMP);
        RETURN OLD;
    ELSIF TG_OP = 'INSERT' THEN
        INSERT INTO log_users (users_userid, action, cdate)
        VALUES (NEW.userid, 'inserted', CURRENT_TIMESTAMP);
        RETURN NEW;
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO log_users (users_userid, action, cdate)
        VALUES (NEW.userid, 'updated', CURRENT_TIMESTAMP);
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER deletelog
    BEFORE DELETE ON users
    FOR EACH ROW
    EXECUTE FUNCTION log_user_action();

CREATE TRIGGER insertlog
    AFTER INSERT ON users
    FOR EACH ROW
    EXECUTE FUNCTION log_user_action();

CREATE TRIGGER updatelog
    AFTER UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION log_user_action();

-----------------------------------------------------
-- countGender function
-----------------------------------------------------
CREATE OR REPLACE FUNCTION countGender()
RETURNS TABLE(gender VARCHAR(8), count BIGINT) AS $$
BEGIN
    RETURN QUERY
    SELECT u.gender, COUNT(*)::BIGINT
    FROM users u
    GROUP BY u.gender;
END;
$$ LANGUAGE plpgsql;

