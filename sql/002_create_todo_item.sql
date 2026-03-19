CREATE TABLE IF NOT EXISTS `todo_item` (
  `id` VARCHAR(36) NOT NULL DEFAULT (UUID()),
  `list_id` VARCHAR(36) NOT NULL,
  `parent_item_id` VARCHAR(36) NULL,
  `title` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL,
  `due_at` DATETIME(3) NULL,
  `reminder_at` DATETIME(3) NULL,
  `priority` TINYINT UNSIGNED NOT NULL DEFAULT 3,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('open', 'done', 'cancelled') NOT NULL DEFAULT 'open',
  `completed_at` DATETIME(3) NULL,
  `metadata` JSON NULL,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
  `deleted_at` DATETIME(3) NULL,
  `version` INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_todo_item_list_status` (`list_id`, `status`),
  KEY `idx_todo_item_due_at` (`due_at`),
  KEY `idx_todo_item_parent` (`parent_item_id`),
  KEY `idx_todo_item_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_todo_item_list`
    FOREIGN KEY (`list_id`) REFERENCES `todo_list` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_todo_item_parent`
    FOREIGN KEY (`parent_item_id`) REFERENCES `todo_item` (`id`)
    ON DELETE SET NULL ON UPDATE RESTRICT
);
