<?php
if (!function_exists('run_seed_if_needed')) {
function run_seed_if_needed($con)
{
    // Create marker table used for one-time seed execution tracking.
    pg_query($con, "CREATE TABLE IF NOT EXISTS setup_meta (
        key_name VARCHAR(50) PRIMARY KEY,
        key_value VARCHAR(100),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $res = pg_query($con, "SELECT key_value FROM setup_meta WHERE key_name='seed_version'");
    $current_version = null;
    if ($res && pg_num_rows($res) == 1) {
        $row = pg_fetch_assoc($res);
        $current_version = $row['key_value'];
    }

    // If v2 is already seeded, do nothing.
    if ($current_version === 'v2') {
        return;
    }

    // Full reset + schema recreation script (drops and recreates tables/functions).
    $schema_file = __DIR__ . '/../sports_club_db_pg.sql';
    if (!file_exists($schema_file)) {
        return;
    }
    $schema_sql = file_get_contents($schema_file);
    if ($schema_sql === false || trim($schema_sql) === '') {
        return;
    }

    // Rich class seed data script.
    $seed_file = __DIR__ . '/../seed_data_variety_pg.sql';
    if (!file_exists($seed_file)) {
        return;
    }
    $seed_sql = file_get_contents($seed_file);
    if ($seed_sql === false || trim($seed_sql) === '') {
        return;
    }

    // 1) Drop/recreate everything from schema file.
    $ok_schema = pg_query($con, $schema_sql);
    if (!$ok_schema) {
        return;
    }

    // 2) Insert variety seed data.
    $ok_seed = pg_query($con, $seed_sql);
    if (!$ok_seed) {
        return;
    }

    // 3) Mark as seeded version v2 (bump when schema/seed changes require re-run).
    pg_query($con, "INSERT INTO setup_meta(key_name, key_value, updated_at)
                    VALUES('seed_version','v2',CURRENT_TIMESTAMP)
                    ON CONFLICT (key_name)
                    DO UPDATE SET key_value='v2', updated_at=CURRENT_TIMESTAMP");
}
}
?>
