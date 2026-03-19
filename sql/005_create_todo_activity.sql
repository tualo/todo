CREATE TABLE IF NOT EXISTS `todo_activity` (
  `id` VARCHAR(36) NOT NULL DEFAULT (UUID()),
  `list_id` VARCHAR(36) NULL,
  `item_id` VARCHAR(36) NULL,
  `actor` VARCHAR(128) NULL,
  `event_type` VARCHAR(64) NOT NULL,
  `payload` JSON NULL,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  KEY `idx_todo_activity_list` (`list_id`),
  KEY `idx_todo_activity_item` (`item_id`),
  KEY `idx_todo_activity_event_type` (`event_type`),
  CONSTRAINT `fk_todo_activity_list`
    FOREIGN KEY (`list_id`) REFERENCES `todo_list` (`id`)
    ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_todo_activity_item`
    FOREIGN KEY (`item_id`) REFERENCES `todo_item` (`id`)
    ON DELETE SET NULL ON UPDATE RESTRICT
);
