-- Cấu trúc cơ sở dữ liệu website ICANDOIT (MySQL)
-- Sinh tự động từ thư mục database/migrations
-- Nhập file này vào database trống bằng phpMyAdmin của hosting.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------
-- 0001_01_01_000000_create_users_table.php
-- ---------------------------------------------------------------
create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `email` varchar(255) not null, `email_verified_at` timestamp null, `password` varchar(255) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `users` add unique `users_email_unique`(`email`);
create table `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp null, primary key (`email`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `sessions` (`id` varchar(255) not null, `user_id` bigint unsigned null, `ip_address` varchar(45) null, `user_agent` text null, `payload` longtext not null, `last_activity` int not null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `sessions` add index `sessions_user_id_index`(`user_id`);
alter table `sessions` add index `sessions_last_activity_index`(`last_activity`);
-- ---------------------------------------------------------------
-- 0001_01_01_000001_create_cache_table.php
-- ---------------------------------------------------------------
create table `cache` (`key` varchar(255) not null, `value` mediumtext not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cache` add index `cache_expiration_index`(`expiration`);
create table `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` bigint not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `cache_locks` add index `cache_locks_expiration_index`(`expiration`);
-- ---------------------------------------------------------------
-- 0001_01_01_000002_create_jobs_table.php
-- ---------------------------------------------------------------
create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(255) not null, `payload` longtext not null, `attempts` smallint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `jobs` add index `jobs_queue_index`(`queue`);
create table `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` int not null, `pending_jobs` int not null, `failed_jobs` int not null, `failed_job_ids` longtext not null, `options` mediumtext null, `cancelled_at` int null, `created_at` int not null, `finished_at` int null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(255) not null, `connection` varchar(255) not null, `queue` varchar(255) not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp not null default CURRENT_TIMESTAMP) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `failed_jobs` add index `failed_jobs_connection_queue_failed_at_index`(`connection`, `queue`, `failed_at`);
alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`);
-- ---------------------------------------------------------------
-- 2026_01_01_000010_add_attendance_fields_to_users_table.php
-- ---------------------------------------------------------------
alter table `users` add `role` varchar(255) not null default 'employee' after `email`;
alter table `users` add `employee_code` varchar(255) null after `role`;
alter table `users` add `department` varchar(255) null after `employee_code`;
alter table `users` add `is_active` tinyint(1) not null default '1' after `department`;
alter table `users` add unique `users_employee_code_unique`(`employee_code`);
-- ---------------------------------------------------------------
-- 2026_01_01_000020_create_attendances_table.php
-- ---------------------------------------------------------------
create table `attendances` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `work_date` date not null, `check_in_at` timestamp null, `check_out_at` timestamp null, `check_in_ip` varchar(255) null, `check_out_ip` varchar(255) null, `status` varchar(255) null, `note` text null, `sync_status` varchar(255) not null default 'pending', `synced_at` timestamp null, `icandoit_ref` varchar(255) null, `sync_message` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `attendances` add constraint `attendances_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `attendances` add unique `attendances_user_id_work_date_unique`(`user_id`, `work_date`);
alter table `attendances` add index `attendances_work_date_index`(`work_date`);
alter table `attendances` add index `attendances_sync_status_index`(`sync_status`);
-- ---------------------------------------------------------------
-- 2026_02_01_000010_create_consultations_table.php
-- ---------------------------------------------------------------
create table `consultations` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `phone` varchar(30) not null, `email` varchar(255) null, `course` varchar(255) null, `note` text null, `status` varchar(255) not null default 'new', `staff_note` text null, `contacted_at` timestamp null, `source` varchar(255) null, `ip` varchar(45) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `consultations` add index `consultations_status_index`(`status`);
alter table `consultations` add index `consultations_created_at_index`(`created_at`);
-- ---------------------------------------------------------------
-- 2026_02_01_000020_create_site_contents_table.php
-- ---------------------------------------------------------------
create table `site_contents` (`id` bigint unsigned not null auto_increment primary key, `section` varchar(255) not null, `payload` json not null, `updated_by` bigint unsigned null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `site_contents` add constraint `site_contents_updated_by_foreign` foreign key (`updated_by`) references `users` (`id`) on delete set null;
alter table `site_contents` add unique `site_contents_section_unique`(`section`);
-- ---------------------------------------------------------------
-- 2026_03_01_000010_create_timetable_entries_table.php
-- ---------------------------------------------------------------
create table `timetable_entries` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `class_name` varchar(255) not null, `subject` varchar(255) null, `weekday` tinyint unsigned not null, `start_time` time not null, `end_time` time not null, `room` varchar(255) null, `effective_from` date not null, `effective_to` date null, `note` text null, `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `timetable_entries` add constraint `timetable_entries_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `timetable_entries` add index `timetable_entries_user_id_weekday_index`(`user_id`, `weekday`);
alter table `timetable_entries` add index `timetable_entries_weekday_index`(`weekday`);
-- ---------------------------------------------------------------
-- 2026_03_01_000020_create_work_tasks_table.php
-- ---------------------------------------------------------------
create table `work_tasks` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `assigned_by` bigint unsigned null, `title` varchar(255) not null, `description` text null, `work_date` date not null, `start_time` time null, `end_time` time null, `priority` varchar(255) not null default 'normal', `status` varchar(255) not null default 'todo', `completed_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `work_tasks` add constraint `work_tasks_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade;
alter table `work_tasks` add constraint `work_tasks_assigned_by_foreign` foreign key (`assigned_by`) references `users` (`id`) on delete set null;
alter table `work_tasks` add index `work_tasks_user_id_work_date_index`(`user_id`, `work_date`);
alter table `work_tasks` add index `work_tasks_work_date_index`(`work_date`);
alter table `work_tasks` add index `work_tasks_status_index`(`status`);

-- ---------------------------------------------------------------
-- Bảng theo dõi migration
-- ---------------------------------------------------------------
CREATE TABLE `migrations` (`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `migration` varchar(255) NOT NULL, `batch` int NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2026_01_01_000010_add_attendance_fields_to_users_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2026_01_01_000020_create_attendances_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2026_02_01_000010_create_consultations_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2026_02_01_000020_create_site_contents_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2026_03_01_000010_create_timetable_entries_table', 1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2026_03_01_000020_create_work_tasks_table', 1);

SET FOREIGN_KEY_CHECKS = 1;
