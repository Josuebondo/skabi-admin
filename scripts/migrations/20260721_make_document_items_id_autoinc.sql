-- Migration: make document_items.id AUTO_INCREMENT
-- Run this SQL against your database (e.g., via mysql CLI or phpMyAdmin)

ALTER TABLE document_items
  MODIFY COLUMN id INT
(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;
