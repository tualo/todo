CREATE TABLE IF NOT EXISTS `todo_list` (
  `id` VARCHAR(36) NOT NULL DEFAULT (UUID()),
  `tenant_id` VARCHAR(36) NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `color` VARCHAR(32) NULL,
  `is_archived` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` DATETIME(3) NULL,
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_todo_list_tenant_archived` (`tenant_id`, `is_archived`),
  KEY `idx_todo_list_deleted_at` (`deleted_at`)
);
