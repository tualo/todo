CREATE TABLE IF NOT EXISTS `todo_item_tag` (
  `item_id` VARCHAR(36) NOT NULL,
  `tag_id` VARCHAR(36) NOT NULL,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  PRIMARY KEY (`item_id`, `tag_id`),
  KEY `idx_todo_item_tag_tag_id` (`tag_id`),
  CONSTRAINT `fk_todo_item_tag_item`
    FOREIGN KEY (`item_id`) REFERENCES `todo_item` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_todo_item_tag_tag`
    FOREIGN KEY (`tag_id`) REFERENCES `todo_tag` (`id`)
    ON DELETE CASCADE ON UPDATE RESTRICT
);
