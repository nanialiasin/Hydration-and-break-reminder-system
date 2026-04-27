-- --------------------------------------------------------
-- Host:                         C:\Users\CHC PC\Herd\HydraPulse\database\database.sqlite
-- Server version:               3.51.0
-- Server OS:                    
-- HeidiSQL Version:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES  */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for database
CREATE DATABASE IF NOT EXISTS "database";
;

-- Dumping structure for table database.athlete_profiles
CREATE TABLE IF NOT EXISTS "athlete_profiles" ("id" integer primary key autoincrement not null, "athlete_id" integer not null, "weight" float, "height" float, "sport" varchar, "intensity" varchar, "created_at" datetime, "updated_at" datetime, foreign key("athlete_id") references "athletes"("id") on delete cascade);

-- Dumping data for table database.athlete_profiles: -1 rows
/*!40000 ALTER TABLE "athlete_profiles" DISABLE KEYS */;
/*!40000 ALTER TABLE "athlete_profiles" ENABLE KEYS */;

-- Dumping structure for table database.athletes
CREATE TABLE IF NOT EXISTS "athletes" ("id" integer primary key autoincrement not null, "athlete_id" varchar not null, "name" varchar not null, "email" varchar not null, "sport" varchar, "status" varchar not null, "intensity" varchar, "weight" float, "height" float, "bmi" float, "alert_volume" float, "reminder_volume" float, "created_at" datetime, "updated_at" datetime, "profile_pic" varchar default 'default.jpg', "stay_logged_in" tinyint(1) not null default '0', "created_by_coach" tinyint(1), "sip_prompt_seen" tinyint(1) not null default '0', "weekly_avg" integer, "weekly_total_ml" float not null default '0', "daily_total_ml" float not null default '0', "daily_total_date" date);
CREATE UNIQUE INDEX "athletes_athlete_id_unique" on "athletes" ("athlete_id");
CREATE UNIQUE INDEX "athletes_email_unique" on "athletes" ("email");

-- Dumping data for table database.athletes: -1 rows
/*!40000 ALTER TABLE "athletes" DISABLE KEYS */;
INSERT INTO "athletes" ("id", "athlete_id", "name", "email", "sport", "status", "intensity", "weight", "height", "bmi", "alert_volume", "reminder_volume", "created_at", "updated_at", "profile_pic", "stay_logged_in", "created_by_coach", "sip_prompt_seen", "weekly_avg", "weekly_total_ml", "daily_total_ml", "daily_total_date") VALUES
	(3, 'AFR396', 'popo', 'po@gmail.com', 'Badminton', 'active', 'Beginner', 80.0, 180.0, 24.69, NULL, NULL, '2026-03-24 18:57:42', '2026-03-24 19:01:38', 'default.jpg', 0, 4, 0, NULL, 0.0, 0.0, NULL),
	(4, 'AHS334', 'athlete', 'athlete@test.com', 'Badminton', 'active', 'Intermediate', 50.0, 170.0, 17.3, NULL, NULL, '2026-03-25 13:40:14', '2026-04-22 15:42:36', 'default.jpg', 0, 6, 0, NULL, 0.0, 0.0, '2026-04-22'),
	(5, 'AWJ599', 'athlete2', 'athlete2@gmail.com', 'Running', 'active', 'Beginner', 60.0, 150.0, 26.7, NULL, NULL, '2026-03-28 03:19:42', '2026-03-28 03:20:34', 'default.jpg', 0, 6, 0, NULL, 0.0, 0.0, NULL),
	(7, 'AEN436', 'athlete3', 'athlete3@gmail.com', 'Volleyball', 'active', 'Beginner', 50.0, 170.0, 17.3, NULL, NULL, '2026-03-30 10:46:41', '2026-03-31 06:02:48', 'default.jpg', 0, NULL, 0, NULL, 0.0, 0.0, NULL);
/*!40000 ALTER TABLE "athletes" ENABLE KEYS */;

-- Dumping structure for table database.cache
CREATE TABLE IF NOT EXISTS "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));
;
CREATE INDEX "cache_expiration_index" on "cache" ("expiration");

-- Dumping data for table database.cache: -1 rows
/*!40000 ALTER TABLE "cache" DISABLE KEYS */;
/*!40000 ALTER TABLE "cache" ENABLE KEYS */;

-- Dumping structure for table database.cache_locks
CREATE TABLE IF NOT EXISTS "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));
;
CREATE INDEX "cache_locks_expiration_index" on "cache_locks" ("expiration");

-- Dumping data for table database.cache_locks: -1 rows
/*!40000 ALTER TABLE "cache_locks" DISABLE KEYS */;
/*!40000 ALTER TABLE "cache_locks" ENABLE KEYS */;

-- Dumping structure for table database.coaches
CREATE TABLE IF NOT EXISTS "coaches" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "sport" varchar, "phone_number" varchar, "team_name" varchar, "profile_picture" varchar, "coach_id" varchar not null, "created_at" datetime, "updated_at" datetime, "stay_logged_in" tinyint(1) not null default '0', "profile_pic" varchar);
CREATE UNIQUE INDEX "coaches_email_unique" on "coaches" ("email");
CREATE UNIQUE INDEX "coaches_coach_id_unique" on "coaches" ("coach_id");

-- Dumping data for table database.coaches: -1 rows
/*!40000 ALTER TABLE "coaches" DISABLE KEYS */;
INSERT INTO "coaches" ("id", "name", "email", "sport", "phone_number", "team_name", "profile_picture", "coach_id", "created_at", "updated_at", "stay_logged_in", "profile_pic") VALUES
	(1, 'abu', 'abu@gmail.com', 'Badminton', '12345678', 'test', NULL, 'COT197', '2026-03-24 18:37:53', '2026-03-24 18:37:53', 0, NULL),
	(2, 'pipi', 'pi@gmail.com', 'Badminton', '12345678', 'test', NULL, 'CNK710', '2026-03-24 18:51:28', '2026-03-24 18:51:28', 0, NULL),
	(3, 'coach', 'coach@test.com', 'Badminton', '12345678', 'test', NULL, 'CIB397', '2026-03-25 13:39:36', '2026-03-25 13:39:36', 0, NULL),
	(4, 'coach2', 'coach2@test.com', 'Running', '12345678', 'test2', NULL, 'CLB962', '2026-03-30 17:04:14', '2026-03-30 17:04:14', 0, NULL);
/*!40000 ALTER TABLE "coaches" ENABLE KEYS */;

-- Dumping structure for table database.failed_jobs
CREATE TABLE IF NOT EXISTS "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");

-- Dumping data for table database.failed_jobs: -1 rows
/*!40000 ALTER TABLE "failed_jobs" DISABLE KEYS */;
/*!40000 ALTER TABLE "failed_jobs" ENABLE KEYS */;

-- Dumping structure for table database.hydration_sessions
CREATE TABLE IF NOT EXISTS "hydration_sessions" ("id" integer primary key autoincrement not null, "sport" varchar, "intensity" varchar, "planned_duration_minutes" integer not null default '0', "actual_duration_seconds" integer not null default '0', "temperature" integer not null default '32', "humidity" integer not null default '74', "reminder_interval_minutes" integer not null default '20', "alerts" integer not null default '0', "followed" integer not null default '0', "ignored" integer not null default '0', "hydration_score" integer not null default '0', "completed_at" datetime, "created_at" datetime, "updated_at" datetime, "athlete_id" varchar, "coach_id" varchar, "assigned_by_coach" tinyint(1) not null default '0', "started_at" datetime);
CREATE INDEX "hydration_sessions_athlete_id_index" on "hydration_sessions" ("athlete_id");
CREATE INDEX "hydration_sessions_coach_id_index" on "hydration_sessions" ("coach_id");

-- Dumping data for table database.hydration_sessions: -1 rows
/*!40000 ALTER TABLE "hydration_sessions" DISABLE KEYS */;
INSERT INTO "hydration_sessions" ("id", "sport", "intensity", "planned_duration_minutes", "actual_duration_seconds", "temperature", "humidity", "reminder_interval_minutes", "alerts", "followed", "ignored", "hydration_score", "completed_at", "created_at", "updated_at", "athlete_id", "coach_id", "assigned_by_coach", "started_at") VALUES
	(21, 'badminton', 'beginner', 60, 0, 0, 0, 15, 0, 0, 0, 0, '2026-03-30 16:44:01', '2026-03-30 16:43:56', '2026-03-30 16:44:01', 'AHS334', '6', 1, '2026-03-30 16:43:59'),
	(22, 'badminton', 'beginner', 60, 0, 32, 74, 15, 0, 0, 0, 0, NULL, '2026-03-30 16:43:56', '2026-03-30 16:43:56', 'AWJ599', '6', 1, NULL),
	(23, 'badminton', 'beginner', 60, 0, 32, 74, 15, 0, 0, 0, 0, NULL, '2026-03-30 16:43:56', '2026-03-30 16:43:56', 'AEN436', '6', 1, NULL),
	(24, 'jogging', 'beginner', 60, 36, 29, 82, 7, 2, 1, 1, 50, '2026-03-31 06:06:55', '2026-03-31 06:03:21', '2026-03-31 06:06:55', 'AHS334', '6', 1, '2026-03-31 06:06:18'),
	(25, 'jogging', 'beginner', 60, 8, 0, 0, 30, 0, 0, 0, 0, '2026-04-14 14:32:18', '2026-03-31 06:03:21', '2026-04-14 14:32:18', 'AWJ599', '6', 1, '2026-04-14 14:32:09');
/*!40000 ALTER TABLE "hydration_sessions" ENABLE KEYS */;

-- Dumping structure for table database.hydration_settings
CREATE TABLE IF NOT EXISTS "hydration_settings" ("id" integer primary key autoincrement not null, "intensity" varchar not null, "hydration_reminder" integer not null, "break_duration" integer not null, "break_reminder" integer not null, "created_at" datetime, "updated_at" datetime, "athlete_id" varchar);

-- Dumping data for table database.hydration_settings: -1 rows
/*!40000 ALTER TABLE "hydration_settings" DISABLE KEYS */;
INSERT INTO "hydration_settings" ("id", "intensity", "hydration_reminder", "break_duration", "break_reminder", "created_at", "updated_at", "athlete_id") VALUES
	(1, 'Beginner', 15, 5, 10, '2026-03-24 18:37:27', '2026-03-24 18:37:27', NULL),
	(2, 'Intermediate', 10, 5, 8, '2026-03-24 18:37:27', '2026-03-24 18:37:27', NULL),
	(3, 'Advanced', 5, 5, 5, '2026-03-24 18:37:27', '2026-03-30 16:10:12', NULL);
/*!40000 ALTER TABLE "hydration_settings" ENABLE KEYS */;

-- Dumping structure for table database.job_batches
CREATE TABLE IF NOT EXISTS "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));
;

-- Dumping data for table database.job_batches: -1 rows
/*!40000 ALTER TABLE "job_batches" DISABLE KEYS */;
/*!40000 ALTER TABLE "job_batches" ENABLE KEYS */;

-- Dumping structure for table database.jobs
CREATE TABLE IF NOT EXISTS "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);
CREATE INDEX "jobs_queue_index" on "jobs" ("queue");

-- Dumping data for table database.jobs: -1 rows
/*!40000 ALTER TABLE "jobs" DISABLE KEYS */;
/*!40000 ALTER TABLE "jobs" ENABLE KEYS */;

-- Dumping structure for table database.migrations
CREATE TABLE IF NOT EXISTS "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

-- Dumping data for table database.migrations: -1 rows
/*!40000 ALTER TABLE "migrations" DISABLE KEYS */;
INSERT INTO "migrations" ("id", "migration", "batch") VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_02_22_181925_create_athletes_table', 1),
	(5, '2026_02_22_181943_create_sessions_table', 1),
	(6, '2026_02_23_152816_create_hydration_settings_table', 1),
	(7, '2026_02_24_000003_create_hydration_sessions_table', 1),
	(8, '2026_02_24_130258_create_athlete_profiles_table', 1),
	(9, '2026_02_24_153921_add_profile_fields_to_users_table', 1),
	(10, '2026_02_25_000001_add_profile_pic_to_users_table', 1),
	(11, '2026_02_25_225752_create_coaches_table', 1),
	(12, '2026_02_26_000001_add_profile_pic_to_athletes_table', 1),
	(13, '2026_02_26_000002_remove_profile_pic_from_users_table', 1),
	(14, '2026_03_01_161744_add_email_to_athletes_table', 1),
	(15, '2026_03_06_000001_add_stay_logged_in_to_athletes_table', 1),
	(16, '2026_03_06_000001_add_stay_logged_in_to_coaches_table', 1),
	(17, '2026_03_06_000001_add_weight_height_to_athletes_table', 1),
	(18, '2026_03_06_000002_add_bmi_to_athletes_table', 1),
	(19, '2026_03_06_000002_add_role_to_users_table', 1),
	(20, '2026_03_06_000003_add_created_by_coach_to_athletes_table', 1),
	(21, '2026_03_06_000003_add_profile_pic_to_coaches_table', 1),
	(22, '2026_03_08_000001_add_profile_pic_to_athletes_table', 1),
	(23, '2026_03_08_000002_add_created_by_coach_to_athletes_table', 1),
	(24, '2026_03_08_000003_add_athlete_id_to_hydration_settings_table', 1),
	(25, '2026_03_25_000004_add_assignment_fields_to_hydration_sessions_table', 2),
	(26, '2026_03_16_000001_add_coach_id_to_training_sessions_table', 3),
	(27, '2026_03_30_151749_add_assigned_by_coach_to_hydration_sessions_table', 4),
	(28, '2026_04_18_162356_add_sip_prompt_seen_to_athletes_table', 4),
	(29, '2026_04_19_153407_add_weekly_avg_to_athletes_table', 4),
	(30, '2026_04_20_170043_add_weekly_total_ml_to_athletes_table', 4),
	(31, '2026_04_22_120000_add_daily_hydration_tracking_to_athletes_table', 4);
/*!40000 ALTER TABLE "migrations" ENABLE KEYS */;

-- Dumping structure for table database.password_reset_tokens
CREATE TABLE IF NOT EXISTS "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));
;

-- Dumping data for table database.password_reset_tokens: -1 rows
/*!40000 ALTER TABLE "password_reset_tokens" DISABLE KEYS */;
/*!40000 ALTER TABLE "password_reset_tokens" ENABLE KEYS */;

-- Dumping structure for table database.sessions
CREATE TABLE IF NOT EXISTS "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));
;
CREATE INDEX "sessions_user_id_index" on "sessions" ("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions" ("last_activity");

-- Dumping data for table database.sessions: -1 rows
/*!40000 ALTER TABLE "sessions" DISABLE KEYS */;
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES
	('GjcbXFUyU0id6ulVex4a36wI7xw99dZIsNFYenTp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.25.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3NKb1RpaHc0Y0k4dWtIOHh4SHp1ak5TUkw2RVZ6dGVORHdpTzM2byI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8yNXMyLWcxNS50ZXN0Lz9oZXJkPXByZXZpZXciO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776871772),
	('NDyP6GhJKIyxS83lAOpBVt6BJR40Cgy5AX3jHTRn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidnpqN3lleUhuaXhuYnNuOGxLdGZaNTVoclN5bFBlMzJtVHBvZkVSaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8yNXMyLWcxNS50ZXN0L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776873545),
	('RNkBd8DSY0rv3jEjvkKACTGo8jD8OAVne7QR0MZD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieHZDdG5hcVczOVNnaFcxaDJYYjhJOHYzVVZLdVlhdTdqamQ3aEpIcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776883075),
	('RnMERraa6ZbAaaXcfiVBVgMPgGOHE6ujoSroHKcz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVU41MGZWaTR3eWQ0NGJZT1NxZXcxUmRDamxDMlBYQVduTUhWd2xFUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776882947),
	('a1iqSJaBSPKzRrXrO2oPntpQdP3eVpJWr6ojFV1f', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibER0Sm9PM2dwTzRBZ25QZ3d1TEFJa05OV3BRak90ODFuUXZiODVwSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8yNXMyLWcxNS50ZXN0L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776882017),
	('z23SPgH4WMnzqkwTVHXNJ4PyaJ3PZTLq7yfsorwK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.25.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicFpnVTF2UXUwbUMySWxHUkJ4dFAxMHFQc2NZSlU0SHJWUHQ1ME1LZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8yNXMyLWcxNS50ZXN0L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776871794);
/*!40000 ALTER TABLE "sessions" ENABLE KEYS */;

-- Dumping structure for table database.training_sessions
CREATE TABLE IF NOT EXISTS "training_sessions" ("sport" varchar not null, "beginner_duration" integer, "intermediate_duration" integer, "advanced_duration" integer, "created_at" datetime, "updated_at" datetime, "coach_id" integer);

-- Dumping data for table database.training_sessions: -1 rows
/*!40000 ALTER TABLE "training_sessions" DISABLE KEYS */;
/*!40000 ALTER TABLE "training_sessions" ENABLE KEYS */;

-- Dumping structure for table database.users
CREATE TABLE IF NOT EXISTS "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "weight" float, "height" float, "sport" varchar, "intensity" varchar, "stay_logged_in" tinyint(1) not null default '0', "alert_volume" integer not null default '50', "reminder_volume" integer not null default '50', "role" varchar not null default 'athlete');
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");

-- Dumping data for table database.users: -1 rows
/*!40000 ALTER TABLE "users" DISABLE KEYS */;
INSERT INTO "users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at", "weight", "height", "sport", "intensity", "stay_logged_in", "alert_volume", "reminder_volume", "role") VALUES
	(4, 'pipi', 'pi@gmail.com', NULL, '$2y$12$ZTQV8P8F3r6LOC7ZWaGUJ.ylxz0oSHuxAHPZ2955uKtmELOMjh1bi', NULL, '2026-03-24 18:51:23', '2026-03-24 18:51:23', NULL, NULL, NULL, NULL, 0, 50, 50, 'coach'),
	(5, 'popo', 'po@gmail.com', NULL, '$2y$12$hvd29E2MHZ0oYPrAx4iLIuG3AiO4AXrOemkhQL0/SIZLjwuUNUFj2', NULL, '2026-03-24 18:57:42', '2026-03-24 18:57:42', NULL, NULL, NULL, NULL, 0, 50, 50, 'athlete'),
	(6, 'coach', 'coach@test.com', NULL, '$2y$12$Z7g881h6b9N3cb2VhxgOa.In4.or0xpjZyOV91p1S08C26zGN/X.y', NULL, '2026-03-25 13:39:25', '2026-03-25 13:39:25', NULL, NULL, NULL, NULL, 0, 50, 50, 'coach'),
	(7, 'athlete', 'athlete@test.com', NULL, '$2y$12$I6EhGNssxN3ViP517MhT3.8LXVDBfnIYDEBMHszsmP9kAI1mECpK6', NULL, '2026-03-25 13:40:14', '2026-03-25 13:40:14', NULL, NULL, NULL, NULL, 0, 50, 50, 'athlete'),
	(8, 'athlete2', 'athlete2@gmail.com', NULL, '$2y$12$hu5ZsqPuRBnuh/TXOWIrT.iAewQegrlvFLwKotopNPgxx/W2ljNF2', NULL, '2026-03-28 03:19:42', '2026-03-28 03:19:42', NULL, NULL, NULL, NULL, 0, 50, 50, 'athlete'),
	(10, 'athlete3', 'athlete3@gmail.com', NULL, '$2y$12$drBN0vh065HmVYipKeN9Ae46PRoHoZyYlNY6DhcLCQw36/ltW44uK', NULL, '2026-03-30 10:46:41', '2026-03-30 10:46:41', NULL, NULL, NULL, NULL, 0, 50, 50, 'athlete');
/*!40000 ALTER TABLE "users" ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
