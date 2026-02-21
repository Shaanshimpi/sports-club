-- SQL Script to Increase Field Sizes in PostgreSQL Database
-- Run this script to fix VARCHAR length constraints that are too restrictive
-- Execute this in pgAdmin Query Tool or using psql

-- Increase email field size from VARCHAR(20) to VARCHAR(100)
-- This allows for longer email addresses
ALTER TABLE users ALTER COLUMN email TYPE VARCHAR(100);

-- Optional: Increase mobile field size if needed (currently VARCHAR(20) should be fine)
-- But if you need international numbers with country codes, you might want:
-- ALTER TABLE users ALTER COLUMN mobile TYPE VARCHAR(30);

-- Verify the changes
SELECT 
    column_name, 
    data_type, 
    character_maximum_length 
FROM information_schema.columns 
WHERE table_name = 'users' 
AND column_name IN ('email', 'mobile', 'userid', 'username')
ORDER BY column_name;


