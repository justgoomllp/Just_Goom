-- =============================================================
-- JustGoom Project - New Migrations SQL
-- Database: justgoom_db
-- Date: 2026-07-04
-- =============================================================

-- ---------------------------------------------------------
-- 1. Create login_histories table
-- ---------------------------------------------------------
CREATE TABLE `login_histories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `login_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `logout_at` TIMESTAMP NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `login_histories_user_id_login_at_index` (`user_id`, `login_at`),
    CONSTRAINT `login_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
-- 2. Add type & price columns to services table
-- ---------------------------------------------------------
ALTER TABLE `services`
    ADD COLUMN `type` VARCHAR(20) NOT NULL DEFAULT 'service' AFTER `user_id`,
    ADD COLUMN `price` DECIMAL(12, 2) NULL DEFAULT NULL AFTER `product_desc`,
    ADD INDEX `services_user_id_type_deleted_at_index` (`user_id`, `type`, `deleted_at`);


-- ---------------------------------------------------------
-- 3. Create projects table
-- ---------------------------------------------------------
CREATE TABLE `projects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `type` VARCHAR(20) NOT NULL DEFAULT 'document' COMMENT 'document, video, link',
    `file_path` VARCHAR(500) NULL DEFAULT NULL,
    `external_url` VARCHAR(500) NULL DEFAULT NULL,
    `thumbnail` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `projects_user_id_type_deleted_at_index` (`user_id`, `type`, `deleted_at`),
    CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
-- 4. Create articles table
-- ---------------------------------------------------------
CREATE TABLE `articles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(300) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `body` LONGTEXT NOT NULL,
    `featured_image` VARCHAR(255) NULL DEFAULT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft, published',
    `published_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `articles_slug_unique` (`slug`),
    INDEX `articles_user_id_status_deleted_at_index` (`user_id`, `status`, `deleted_at`),
    INDEX `articles_status_published_at_index` (`status`, `published_at`),
    CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
-- 5. Create offers table
-- ---------------------------------------------------------
CREATE TABLE `offers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `banner_image` VARCHAR(255) NULL DEFAULT NULL,
    `link_url` VARCHAR(500) NULL DEFAULT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `offers_status_start_date_end_date_deleted_at_index` (`status`, `start_date`, `end_date`, `deleted_at`),
    INDEX `offers_user_id_deleted_at_index` (`user_id`, `deleted_at`),
    CONSTRAINT `offers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
-- 6. Create advertisements table
-- ---------------------------------------------------------
CREATE TABLE `advertisements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(200) NOT NULL,
    `banner_image` VARCHAR(255) NOT NULL,
    `link_url` VARCHAR(500) NULL DEFAULT NULL,
    `position` VARCHAR(50) NOT NULL DEFAULT 'homepage' COMMENT 'homepage, sidebar, etc.',
    `priority` INT UNSIGNED NOT NULL DEFAULT 0,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `advertisements_is_active_position_start_date_end_date_index` (`is_active`, `position`, `start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
-- 7. Create countries, states, cities tables
-- ---------------------------------------------------------
CREATE TABLE `countries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(5) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `countries_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `states` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `country_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `states_country_id_name_index` (`country_id`, `name`),
    CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `state_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `cities_state_id_name_index` (`state_id`, `name`),
    CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ---------------------------------------------------------
-- 8. Add video/project/article limits to plans table
-- ---------------------------------------------------------
ALTER TABLE `plans`
    ADD COLUMN `max_video_size_mb` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `duration_days`,
    ADD COLUMN `max_video_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_video_size_mb`,
    ADD COLUMN `max_project_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_video_count`,
    ADD COLUMN `max_article_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_project_count`;
