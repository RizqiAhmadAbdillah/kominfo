/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 100427 (10.4.27-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : kominfo

 Target Server Type    : MySQL
 Target Server Version : 100427 (10.4.27-MariaDB)
 File Encoding         : 65001

 Date: 22/09/2024 14:25:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_product
-- ----------------------------
DROP TABLE IF EXISTS `m_product`;
CREATE TABLE `m_product`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `price` int NULL DEFAULT NULL,
  `stock` int NULL DEFAULT NULL,
  `sold` int NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deleted_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_product
-- ----------------------------
INSERT INTO `m_product` VALUES (1, 'Laptop X', 13000000, 13, 2, '2024-09-19 06:29:31', '2024-09-22 14:05:13', NULL);
INSERT INTO `m_product` VALUES (2, 'Smartphone Y', 7000000, 1, 14, '2024-09-19 06:38:21', '2024-09-22 14:19:15', NULL);
INSERT INTO `m_product` VALUES (3, 'Laptop Y', 12000000, 1, 14, '2024-09-22 10:22:59', '2024-09-22 14:19:15', NULL);
INSERT INTO `m_product` VALUES (4, 'Keyboard A', 12000000, 2, 13, '2024-09-22 10:24:18', '2024-09-22 14:12:34', NULL);
INSERT INTO `m_product` VALUES (7, 'Laptop XXX', 2100000, 2, 13, '2024-09-22 14:01:40', '2024-09-22 14:06:41', '2024-09-22 14:04:13');

-- ----------------------------
-- Table structure for t_order_details
-- ----------------------------
DROP TABLE IF EXISTS `t_order_details`;
CREATE TABLE `t_order_details`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_order` int NULL DEFAULT NULL,
  `id_product` int NULL DEFAULT NULL,
  `quantity` int NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deleted_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_order_details
-- ----------------------------
INSERT INTO `t_order_details` VALUES (3, 3, 2, 1, '2024-09-22 13:10:14', '2024-09-22 13:10:14', '2024-09-22 13:47:01');
INSERT INTO `t_order_details` VALUES (4, 3, 3, 2, '2024-09-22 13:10:14', '2024-09-22 13:10:14', '2024-09-22 13:47:01');
INSERT INTO `t_order_details` VALUES (5, 4, 1, 1, '2024-09-22 13:14:50', '2024-09-22 13:14:50', '2024-09-22 14:05:13');
INSERT INTO `t_order_details` VALUES (6, 4, 4, 2, '2024-09-22 13:14:50', '2024-09-22 13:14:50', '2024-09-22 14:05:13');
INSERT INTO `t_order_details` VALUES (7, 5, 1, 1, '2024-09-22 13:15:18', '2024-09-22 13:15:18', NULL);
INSERT INTO `t_order_details` VALUES (8, 5, 4, 2, '2024-09-22 13:15:18', '2024-09-22 13:15:18', NULL);
INSERT INTO `t_order_details` VALUES (9, 6, 1, 1, '2024-09-22 13:16:35', '2024-09-22 13:16:35', NULL);
INSERT INTO `t_order_details` VALUES (10, 6, 4, 2, '2024-09-22 13:16:35', '2024-09-22 13:16:35', NULL);
INSERT INTO `t_order_details` VALUES (11, 7, 2, 1, '2024-09-22 13:17:22', '2024-09-22 13:17:22', NULL);
INSERT INTO `t_order_details` VALUES (12, 7, 3, 2, '2024-09-22 13:17:22', '2024-09-22 13:17:22', NULL);
INSERT INTO `t_order_details` VALUES (13, 8, 2, 1, '2024-09-22 13:17:47', '2024-09-22 13:17:47', NULL);
INSERT INTO `t_order_details` VALUES (14, 8, 3, 2, '2024-09-22 13:17:47', '2024-09-22 13:17:47', NULL);
INSERT INTO `t_order_details` VALUES (15, 9, 2, 1, '2024-09-22 13:18:32', '2024-09-22 13:18:32', NULL);
INSERT INTO `t_order_details` VALUES (16, 10, 2, 1, '2024-09-22 13:19:38', '2024-09-22 13:19:38', NULL);
INSERT INTO `t_order_details` VALUES (17, 10, 3, 2, '2024-09-22 13:19:38', '2024-09-22 13:19:38', NULL);
INSERT INTO `t_order_details` VALUES (18, 11, 2, 1, '2024-09-22 13:20:10', '2024-09-22 13:20:10', NULL);
INSERT INTO `t_order_details` VALUES (19, 11, 3, 5, '2024-09-22 13:20:10', '2024-09-22 13:20:10', NULL);
INSERT INTO `t_order_details` VALUES (20, 12, 2, 1, '2024-09-22 13:24:35', '2024-09-22 13:24:35', NULL);
INSERT INTO `t_order_details` VALUES (21, 12, 3, 1, '2024-09-22 13:24:35', '2024-09-22 13:24:35', NULL);
INSERT INTO `t_order_details` VALUES (24, 14, 4, 1, '2024-09-22 14:11:39', '2024-09-22 14:11:39', '2024-09-22 14:12:34');
INSERT INTO `t_order_details` VALUES (25, 14, 3, 1, '2024-09-22 14:11:39', '2024-09-22 14:11:39', '2024-09-22 14:12:34');
INSERT INTO `t_order_details` VALUES (26, 15, 2, 1, '2024-09-22 14:19:15', '2024-09-22 14:19:15', NULL);
INSERT INTO `t_order_details` VALUES (27, 15, 3, 1, '2024-09-22 14:19:15', '2024-09-22 14:19:15', NULL);

-- ----------------------------
-- Table structure for t_orders
-- ----------------------------
DROP TABLE IF EXISTS `t_orders`;
CREATE TABLE `t_orders`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `total_spent` int NULL DEFAULT NULL,
  `deleted_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_orders
-- ----------------------------
INSERT INTO `t_orders` VALUES (3, NULL, '2024-09-22 13:10:14', '2024-09-22 13:10:14', NULL, '2024-09-22 13:47:01');
INSERT INTO `t_orders` VALUES (4, NULL, '2024-09-22 13:14:50', '2024-09-22 13:14:50', NULL, '2024-09-22 14:05:13');
INSERT INTO `t_orders` VALUES (5, NULL, '2024-09-22 13:15:18', '2024-09-22 13:15:18', NULL, NULL);
INSERT INTO `t_orders` VALUES (6, NULL, '2024-09-22 13:16:35', '2024-09-22 13:16:35', NULL, NULL);
INSERT INTO `t_orders` VALUES (7, NULL, '2024-09-22 13:17:22', '2024-09-22 13:17:22', NULL, NULL);
INSERT INTO `t_orders` VALUES (8, NULL, '2024-09-22 13:17:47', '2024-09-22 13:17:47', NULL, NULL);
INSERT INTO `t_orders` VALUES (9, NULL, '2024-09-22 13:18:32', '2024-09-22 13:18:32', NULL, NULL);
INSERT INTO `t_orders` VALUES (10, NULL, '2024-09-22 13:19:38', '2024-09-22 13:19:38', NULL, NULL);
INSERT INTO `t_orders` VALUES (11, NULL, '2024-09-22 13:20:10', '2024-09-22 13:20:10', NULL, NULL);
INSERT INTO `t_orders` VALUES (12, NULL, '2024-09-22 13:24:35', '2024-09-22 13:24:35', 19000000, NULL);
INSERT INTO `t_orders` VALUES (14, NULL, '2024-09-22 14:11:39', '2024-09-22 14:11:39', 24000000, '2024-09-22 14:12:34');
INSERT INTO `t_orders` VALUES (15, NULL, '2024-09-22 14:19:15', '2024-09-22 14:19:15', 19000000, NULL);

-- ----------------------------
-- Table structure for tes
-- ----------------------------
DROP TABLE IF EXISTS `tes`;
CREATE TABLE `tes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tes
-- ----------------------------
INSERT INTO `tes` VALUES (1, 'Rizqi', '2024-09-20');
INSERT INTO `tes` VALUES (2, 'Ahmad', '2024-09-21');
INSERT INTO `tes` VALUES (3, 'Abdillah', '2024-09-22');

SET FOREIGN_KEY_CHECKS = 1;
