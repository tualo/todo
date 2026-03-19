CREATE TABLE IF NOT EXISTS `todo_tag` (
  `id` VARCHAR(36) NOT NULL DEFAULT (UUID()),
  `name` VARCHAR(128) NOT NULL,
  `color` VARCHAR(32) NULL,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_todo_tag_name` (`name`)
);
