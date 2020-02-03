-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2020 at 11:27 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medicalbrows_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `order`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Category 1', 'category-1', '2020-01-28 07:13:04', '2020-01-28 07:13:04'),
(2, NULL, 1, 'Category 2', 'category-2', '2020-01-28 07:13:04', '2020-01-28 07:13:04');

-- --------------------------------------------------------

--
-- Table structure for table `data_rows`
--

CREATE TABLE `data_rows` (
  `id` int(10) UNSIGNED NOT NULL,
  `data_type_id` int(10) UNSIGNED NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `browse` tinyint(1) NOT NULL DEFAULT 1,
  `read` tinyint(1) NOT NULL DEFAULT 1,
  `edit` tinyint(1) NOT NULL DEFAULT 1,
  `add` tinyint(1) NOT NULL DEFAULT 1,
  `delete` tinyint(1) NOT NULL DEFAULT 1,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_rows`
--

INSERT INTO `data_rows` (`id`, `data_type_id`, `field`, `type`, `display_name`, `required`, `browse`, `read`, `edit`, `add`, `delete`, `details`, `order`) VALUES
(1, 1, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(2, 1, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2),
(3, 1, 'email', 'text', 'Email', 1, 1, 1, 1, 1, 1, NULL, 3),
(4, 1, 'password', 'password', 'Password', 1, 0, 0, 1, 1, 0, NULL, 4),
(5, 1, 'remember_token', 'text', 'Remember Token', 0, 0, 0, 0, 0, 0, NULL, 5),
(6, 1, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, NULL, 6),
(7, 1, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 7),
(8, 1, 'avatar', 'image', 'Avatar', 0, 1, 1, 1, 1, 1, NULL, 8),
(9, 1, 'user_belongsto_role_relationship', 'relationship', 'Role', 0, 1, 1, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":0}', 10),
(10, 1, 'user_belongstomany_role_relationship', 'relationship', 'Roles', 0, 1, 1, 1, 1, 0, '{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}', 11),
(11, 1, 'settings', 'hidden', 'Settings', 0, 0, 0, 0, 0, 0, NULL, 12),
(12, 2, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(13, 2, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2),
(14, 2, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, NULL, 3),
(15, 2, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 4),
(16, 3, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(17, 3, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 2),
(18, 3, 'created_at', 'timestamp', 'Created At', 0, 0, 0, 0, 0, 0, NULL, 3),
(19, 3, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 4),
(20, 3, 'display_name', 'text', 'Display Name', 1, 1, 1, 1, 1, 1, NULL, 5),
(21, 1, 'role_id', 'text', 'Role', 1, 1, 1, 1, 1, 1, NULL, 9),
(22, 4, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(23, 4, 'parent_id', 'select_dropdown', 'Parent', 0, 0, 1, 1, 1, 1, '{\"default\":\"\",\"null\":\"\",\"options\":{\"\":\"-- None --\"},\"relationship\":{\"key\":\"id\",\"label\":\"name\"}}', 2),
(24, 4, 'order', 'text', 'Order', 1, 1, 1, 1, 1, 1, '{\"default\":1}', 3),
(25, 4, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, NULL, 4),
(26, 4, 'slug', 'text', 'Slug', 1, 1, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"name\"}}', 5),
(27, 4, 'created_at', 'timestamp', 'Created At', 0, 0, 1, 0, 0, 0, NULL, 6),
(28, 4, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 7),
(29, 5, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(30, 5, 'author_id', 'text', 'Author', 1, 0, 1, 1, 0, 1, NULL, 2),
(31, 5, 'category_id', 'text', 'Category', 1, 0, 1, 1, 1, 0, NULL, 3),
(32, 5, 'title', 'text', 'Title', 1, 1, 1, 1, 1, 1, NULL, 4),
(33, 5, 'excerpt', 'text_area', 'Excerpt', 1, 0, 1, 1, 1, 1, NULL, 5),
(34, 5, 'body', 'rich_text_box', 'Body', 1, 0, 1, 1, 1, 1, NULL, 6),
(35, 5, 'image', 'image', 'Post Image', 0, 1, 1, 1, 1, 1, '{\"resize\":{\"width\":\"1000\",\"height\":\"null\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}', 7),
(36, 5, 'slug', 'text', 'Slug', 1, 0, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"title\",\"forceUpdate\":true},\"validation\":{\"rule\":\"unique:posts,slug\"}}', 8),
(37, 5, 'meta_description', 'text_area', 'Meta Description', 1, 0, 1, 1, 1, 1, NULL, 9),
(38, 5, 'meta_keywords', 'text_area', 'Meta Keywords', 1, 0, 1, 1, 1, 1, NULL, 10),
(39, 5, 'status', 'select_dropdown', 'Status', 1, 1, 1, 1, 1, 1, '{\"default\":\"DRAFT\",\"options\":{\"PUBLISHED\":\"published\",\"DRAFT\":\"draft\",\"PENDING\":\"pending\"}}', 11),
(40, 5, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 0, 0, 0, NULL, 12),
(41, 5, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, NULL, 13),
(42, 5, 'seo_title', 'text', 'SEO Title', 0, 1, 1, 1, 1, 1, NULL, 14),
(43, 5, 'featured', 'checkbox', 'Featured', 1, 1, 1, 1, 1, 1, NULL, 15),
(44, 6, 'id', 'number', 'ID', 1, 0, 0, 0, 0, 0, NULL, 1),
(45, 6, 'author_id', 'text', 'Author', 1, 0, 0, 0, 0, 0, NULL, 2),
(46, 6, 'title', 'text', 'Title', 1, 1, 1, 1, 1, 1, NULL, 3),
(47, 6, 'excerpt', 'text_area', 'Excerpt', 1, 0, 1, 1, 1, 1, NULL, 4),
(48, 6, 'body', 'rich_text_box', 'Body', 1, 0, 1, 1, 1, 1, NULL, 5),
(49, 6, 'slug', 'text', 'Slug', 1, 0, 1, 1, 1, 1, '{\"slugify\":{\"origin\":\"title\"},\"validation\":{\"rule\":\"unique:pages,slug\"}}', 6),
(50, 6, 'meta_description', 'text', 'Meta Description', 1, 0, 1, 1, 1, 1, NULL, 7),
(51, 6, 'meta_keywords', 'text', 'Meta Keywords', 1, 0, 1, 1, 1, 1, NULL, 8),
(52, 6, 'status', 'select_dropdown', 'Status', 1, 1, 1, 1, 1, 1, '{\"default\":\"INACTIVE\",\"options\":{\"INACTIVE\":\"INACTIVE\",\"ACTIVE\":\"ACTIVE\"}}', 9),
(53, 6, 'created_at', 'timestamp', 'Created At', 1, 1, 1, 0, 0, 0, NULL, 10),
(54, 6, 'updated_at', 'timestamp', 'Updated At', 1, 0, 0, 0, 0, 0, NULL, 11),
(55, 6, 'image', 'image', 'Page Image', 0, 1, 1, 1, 1, 1, NULL, 12),
(72, 11, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(73, 11, 'name', 'text', 'Name', 1, 1, 1, 1, 1, 1, '{}', 2),
(74, 11, 'is_deleted', 'number', 'Is Deleted', 1, 1, 1, 1, 1, 1, '{}', 3),
(75, 11, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 4),
(76, 11, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 5),
(77, 12, 'id', 'text', 'Id', 1, 1, 1, 0, 0, 0, '{}', 1),
(78, 12, 'name', 'text', 'Name', 0, 1, 1, 1, 1, 1, '{}', 2),
(79, 12, 'is_deleted', 'text', 'Is Deleted', 0, 1, 1, 1, 1, 1, '{}', 3),
(80, 12, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 4),
(81, 12, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 5),
(82, 14, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(83, 14, 'name', 'text', 'Name', 0, 1, 1, 1, 1, 1, '{}', 2),
(84, 15, 'id', 'text', 'Id', 1, 0, 0, 0, 0, 0, '{}', 1),
(85, 15, 'rank_id', 'text', 'Rank Id', 0, 1, 1, 1, 1, 1, '{}', 2),
(86, 15, 'created_at', 'timestamp', 'Created At', 0, 1, 1, 1, 0, 1, '{}', 3),
(87, 15, 'updated_at', 'timestamp', 'Updated At', 0, 0, 0, 0, 0, 0, '{}', 4);

-- --------------------------------------------------------

--
-- Table structure for table `data_types`
--

CREATE TABLE `data_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT 0,
  `server_side` tinyint(4) NOT NULL DEFAULT 0,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_types`
--

INSERT INTO `data_types` (`id`, `name`, `slug`, `display_name_singular`, `display_name_plural`, `icon`, `model_name`, `policy_name`, `controller`, `description`, `generate_permissions`, `server_side`, `details`, `created_at`, `updated_at`) VALUES
(1, 'users', 'users', 'User', 'Users', 'voyager-person', 'TCG\\Voyager\\Models\\User', 'TCG\\Voyager\\Policies\\UserPolicy', 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController', '', 1, 0, NULL, '2020-01-28 07:12:55', '2020-01-28 07:12:55'),
(2, 'menus', 'menus', 'Menu', 'Menus', 'voyager-list', 'TCG\\Voyager\\Models\\Menu', NULL, '', '', 1, 0, NULL, '2020-01-28 07:12:55', '2020-01-28 07:12:55'),
(3, 'roles', 'roles', 'Role', 'Roles', 'voyager-lock', 'TCG\\Voyager\\Models\\Role', NULL, '', '', 1, 0, NULL, '2020-01-28 07:12:55', '2020-01-28 07:12:55'),
(4, 'categories', 'categories', 'Category', 'Categories', 'voyager-categories', 'TCG\\Voyager\\Models\\Category', NULL, '', '', 1, 0, NULL, '2020-01-28 07:13:03', '2020-01-28 07:13:03'),
(5, 'posts', 'posts', 'Post', 'Posts', 'voyager-news', 'TCG\\Voyager\\Models\\Post', 'TCG\\Voyager\\Policies\\PostPolicy', '', '', 1, 0, NULL, '2020-01-28 07:13:04', '2020-01-28 07:13:04'),
(6, 'pages', 'pages', 'Page', 'Pages', 'voyager-file-text', 'TCG\\Voyager\\Models\\Page', NULL, '', '', 1, 0, NULL, '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(11, 'tbl_ranks', 'tbl-ranks', 'Tbl Rank', 'Tbl Ranks', NULL, 'App\\TblRank', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}', '2020-01-28 13:07:11', '2020-01-28 13:07:11'),
(12, 'tbl_staff_types', 'tbl-staff-types', 'Tbl Staff Type', 'Tbl Staff Types', NULL, 'App\\TblStaffType', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}', '2020-02-01 06:01:19', '2020-02-02 23:05:01'),
(14, 'tbl_regions', 'tbl-regions', 'Tbl Region', 'Tbl Regions', NULL, 'App\\TblRegion', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}', '2020-02-01 18:41:52', '2020-02-01 18:41:52'),
(15, 'tbl_shift_histories', 'tbl-shift-histories', 'Tbl Shift History', 'Tbl Shift Histories', 'voyager-person', 'App\\TblShiftHistory', NULL, NULL, NULL, 1, 0, '{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}', '2020-02-02 00:18:53', '2020-02-02 00:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2020-01-28 07:12:56', '2020-01-28 07:12:56');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `title`, `url`, `target`, `icon_class`, `color`, `parent_id`, `order`, `created_at`, `updated_at`, `route`, `parameters`) VALUES
(1, 1, 'Dashboard', '', '_self', 'voyager-boat', NULL, NULL, 1, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.dashboard', NULL),
(2, 1, 'Media', '', '_self', 'voyager-images', NULL, NULL, 5, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.media.index', NULL),
(3, 1, 'Users', '', '_self', 'voyager-person', NULL, NULL, 3, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.users.index', NULL),
(4, 1, 'Roles', '', '_self', 'voyager-lock', NULL, NULL, 2, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.roles.index', NULL),
(5, 1, 'Tools', '', '_self', 'voyager-tools', NULL, NULL, 9, '2020-01-28 07:12:56', '2020-01-28 07:12:56', NULL, NULL),
(6, 1, 'Menu Builder', '', '_self', 'voyager-list', NULL, 5, 10, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.menus.index', NULL),
(7, 1, 'Database', '', '_self', 'voyager-data', NULL, 5, 11, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.database.index', NULL),
(8, 1, 'Compass', '', '_self', 'voyager-compass', NULL, 5, 12, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.compass.index', NULL),
(9, 1, 'BREAD', '', '_self', 'voyager-bread', NULL, 5, 13, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.bread.index', NULL),
(10, 1, 'Settings', '', '_self', 'voyager-settings', NULL, NULL, 14, '2020-01-28 07:12:56', '2020-01-28 07:12:56', 'voyager.settings.index', NULL),
(11, 1, 'Categories', '', '_self', 'voyager-categories', NULL, NULL, 8, '2020-01-28 07:13:03', '2020-01-28 07:13:03', 'voyager.categories.index', NULL),
(12, 1, 'Posts', '', '_self', 'voyager-news', NULL, NULL, 6, '2020-01-28 07:13:05', '2020-01-28 07:13:05', 'voyager.posts.index', NULL),
(13, 1, 'Pages', '', '_self', 'voyager-file-text', NULL, NULL, 7, '2020-01-28 07:13:05', '2020-01-28 07:13:05', 'voyager.pages.index', NULL),
(14, 1, 'Hooks', '', '_self', 'voyager-hook', NULL, 5, 13, '2020-01-28 07:13:08', '2020-01-28 07:13:08', 'voyager.hooks', NULL),
(19, 1, 'Tbl Ranks', '', '_self', NULL, NULL, NULL, 15, '2020-01-28 13:07:11', '2020-01-28 13:07:11', 'voyager.tbl-ranks.index', NULL),
(20, 1, 'Tbl Staff Types', '', '_self', NULL, NULL, NULL, 16, '2020-02-01 06:01:20', '2020-02-01 06:01:20', 'voyager.tbl-staff-types.index', NULL),
(21, 1, 'Tbl Regions', '', '_self', NULL, NULL, NULL, 17, '2020-02-01 18:41:53', '2020-02-01 18:41:53', 'voyager.tbl-regions.index', NULL),
(22, 1, 'Tbl Shift Histories', '', '_self', 'voyager-person', NULL, NULL, 18, '2020-02-02 00:18:54', '2020-02-02 00:18:54', 'voyager.tbl-shift-histories.index', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_01_01_000000_add_voyager_user_fields', 1),
(4, '2016_01_01_000000_create_data_types_table', 1),
(5, '2016_05_19_173453_create_menu_table', 1),
(6, '2016_10_21_190000_create_roles_table', 1),
(7, '2016_10_21_190000_create_settings_table', 1),
(8, '2016_11_30_135954_create_permission_table', 1),
(9, '2016_11_30_141208_create_permission_role_table', 1),
(10, '2016_12_26_201236_data_types__add__server_side', 1),
(11, '2017_01_13_000000_add_route_to_menu_items_table', 1),
(12, '2017_01_14_005015_create_translations_table', 1),
(13, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 1),
(14, '2017_03_06_000000_add_controller_to_data_types_table', 1),
(15, '2017_04_21_000000_add_order_to_data_rows_table', 1),
(16, '2017_07_05_210000_add_policyname_to_data_types_table', 1),
(17, '2017_08_05_000000_add_group_to_settings_table', 1),
(18, '2017_11_26_013050_add_user_role_relationship', 1),
(19, '2017_11_26_015000_create_user_roles_table', 1),
(20, '2018_03_11_000000_add_user_settings', 1),
(21, '2018_03_14_000000_add_details_to_data_types_table', 1),
(22, '2018_03_16_000000_make_settings_value_nullable', 1),
(23, '2019_08_19_000000_create_failed_jobs_table', 1),
(24, '2016_01_01_000000_create_pages_table', 2),
(25, '2016_01_01_000000_create_posts_table', 2),
(26, '2016_02_15_204651_create_categories_table', 2),
(27, '2017_04_11_000000_alter_post_nullable_fields_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `author_id`, `title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'Hello World', 'Hang the jib grog grog blossom grapple dance the hempen jig gangway pressgang bilge rat to go on account lugger. Nelsons folly gabion line draught scallywag fire ship gaff fluke fathom case shot. Sea Legs bilge rat sloop matey gabion long clothes run a shot across the bow Gold Road cog league.', '<p>Hello World. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>', 'pages/page1.jpg', 'hello-world', 'Yar Meta Description', 'Keyword1, Keyword2', 'ACTIVE', '2020-01-28 07:13:06', '2020-01-28 07:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `table_name`, `created_at`, `updated_at`) VALUES
(1, 'browse_admin', NULL, '2020-01-28 07:12:56', '2020-01-28 07:12:56'),
(2, 'browse_bread', NULL, '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(3, 'browse_database', NULL, '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(4, 'browse_media', NULL, '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(5, 'browse_compass', NULL, '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(6, 'browse_menus', 'menus', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(7, 'read_menus', 'menus', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(8, 'edit_menus', 'menus', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(9, 'add_menus', 'menus', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(10, 'delete_menus', 'menus', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(11, 'browse_roles', 'roles', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(12, 'read_roles', 'roles', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(13, 'edit_roles', 'roles', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(14, 'add_roles', 'roles', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(15, 'delete_roles', 'roles', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(16, 'browse_users', 'users', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(17, 'read_users', 'users', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(18, 'edit_users', 'users', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(19, 'add_users', 'users', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(20, 'delete_users', 'users', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(21, 'browse_settings', 'settings', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(22, 'read_settings', 'settings', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(23, 'edit_settings', 'settings', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(24, 'add_settings', 'settings', '2020-01-28 07:12:57', '2020-01-28 07:12:57'),
(25, 'delete_settings', 'settings', '2020-01-28 07:12:58', '2020-01-28 07:12:58'),
(26, 'browse_categories', 'categories', '2020-01-28 07:13:03', '2020-01-28 07:13:03'),
(27, 'read_categories', 'categories', '2020-01-28 07:13:04', '2020-01-28 07:13:04'),
(28, 'edit_categories', 'categories', '2020-01-28 07:13:04', '2020-01-28 07:13:04'),
(29, 'add_categories', 'categories', '2020-01-28 07:13:04', '2020-01-28 07:13:04'),
(30, 'delete_categories', 'categories', '2020-01-28 07:13:04', '2020-01-28 07:13:04'),
(31, 'browse_posts', 'posts', '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(32, 'read_posts', 'posts', '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(33, 'edit_posts', 'posts', '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(34, 'add_posts', 'posts', '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(35, 'delete_posts', 'posts', '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(36, 'browse_pages', 'pages', '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(37, 'read_pages', 'pages', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(38, 'edit_pages', 'pages', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(39, 'add_pages', 'pages', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(40, 'delete_pages', 'pages', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(41, 'browse_hooks', NULL, '2020-01-28 07:13:08', '2020-01-28 07:13:08'),
(62, 'browse_tbl_ranks', 'tbl_ranks', '2020-01-28 13:07:11', '2020-01-28 13:07:11'),
(63, 'read_tbl_ranks', 'tbl_ranks', '2020-01-28 13:07:11', '2020-01-28 13:07:11'),
(64, 'edit_tbl_ranks', 'tbl_ranks', '2020-01-28 13:07:11', '2020-01-28 13:07:11'),
(65, 'add_tbl_ranks', 'tbl_ranks', '2020-01-28 13:07:11', '2020-01-28 13:07:11'),
(66, 'delete_tbl_ranks', 'tbl_ranks', '2020-01-28 13:07:11', '2020-01-28 13:07:11'),
(67, 'browse_tbl_staff_types', 'tbl_staff_types', '2020-02-01 06:01:20', '2020-02-01 06:01:20'),
(68, 'read_tbl_staff_types', 'tbl_staff_types', '2020-02-01 06:01:20', '2020-02-01 06:01:20'),
(69, 'edit_tbl_staff_types', 'tbl_staff_types', '2020-02-01 06:01:20', '2020-02-01 06:01:20'),
(70, 'add_tbl_staff_types', 'tbl_staff_types', '2020-02-01 06:01:20', '2020-02-01 06:01:20'),
(71, 'delete_tbl_staff_types', 'tbl_staff_types', '2020-02-01 06:01:20', '2020-02-01 06:01:20'),
(72, 'browse_tbl_regions', 'tbl_regions', '2020-02-01 18:41:52', '2020-02-01 18:41:52'),
(73, 'read_tbl_regions', 'tbl_regions', '2020-02-01 18:41:52', '2020-02-01 18:41:52'),
(74, 'edit_tbl_regions', 'tbl_regions', '2020-02-01 18:41:53', '2020-02-01 18:41:53'),
(75, 'add_tbl_regions', 'tbl_regions', '2020-02-01 18:41:53', '2020-02-01 18:41:53'),
(76, 'delete_tbl_regions', 'tbl_regions', '2020-02-01 18:41:53', '2020-02-01 18:41:53'),
(77, 'browse_tbl_shift_histories', 'tbl_shift_histories', '2020-02-02 00:18:54', '2020-02-02 00:18:54'),
(78, 'read_tbl_shift_histories', 'tbl_shift_histories', '2020-02-02 00:18:54', '2020-02-02 00:18:54'),
(79, 'edit_tbl_shift_histories', 'tbl_shift_histories', '2020-02-02 00:18:54', '2020-02-02 00:18:54'),
(80, 'add_tbl_shift_histories', 'tbl_shift_histories', '2020-02-02 00:18:54', '2020-02-02 00:18:54'),
(81, 'delete_tbl_shift_histories', 'tbl_shift_histories', '2020-02-02 00:18:54', '2020-02-02 00:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('PUBLISHED','DRAFT','PENDING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author_id`, `category_id`, `title`, `seo_title`, `excerpt`, `body`, `image`, `slug`, `meta_description`, `meta_keywords`, `status`, `featured`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, 'Lorem Ipsum Post', NULL, 'This is the excerpt for the Lorem Ipsum Post', '<p>This is the body of the lorem ipsum post</p>', 'posts/post1.jpg', 'lorem-ipsum-post', 'This is the meta description', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(2, 0, NULL, 'My Sample Post', NULL, 'This is the excerpt for the sample Post', '<p>This is the body for the sample post, which includes the body.</p>\n                <h2>We can use all kinds of format!</h2>\n                <p>And include a bunch of other stuff.</p>', 'posts/post2.jpg', 'my-sample-post', 'Meta Description for sample post', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(3, 0, NULL, 'Latest Post', NULL, 'This is the excerpt for the latest post', '<p>This is the body for the latest post</p>', 'posts/post3.jpg', 'latest-post', 'This is the meta description', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2020-01-28 07:13:05', '2020-01-28 07:13:05'),
(4, 0, NULL, 'Yarr Post', NULL, 'Reef sails nipperkin bring a spring upon her cable coffer jury mast spike marooned Pieces of Eight poop deck pillage. Clipper driver coxswain galleon hempen halter come about pressgang gangplank boatswain swing the lead. Nipperkin yard skysail swab lanyard Blimey bilge water ho quarter Buccaneer.', '<p>Swab deadlights Buccaneer fire ship square-rigged dance the hempen jig weigh anchor cackle fruit grog furl. Crack Jennys tea cup chase guns pressgang hearties spirits hogshead Gold Road six pounders fathom measured fer yer chains. Main sheet provost come about trysail barkadeer crimp scuttle mizzenmast brig plunder.</p>\n<p>Mizzen league keelhaul galleon tender cog chase Barbary Coast doubloon crack Jennys tea cup. Blow the man down lugsail fire ship pinnace cackle fruit line warp Admiral of the Black strike colors doubloon. Tackle Jack Ketch come about crimp rum draft scuppers run a shot across the bow haul wind maroon.</p>\n<p>Interloper heave down list driver pressgang holystone scuppers tackle scallywag bilged on her anchor. Jack Tar interloper draught grapple mizzenmast hulk knave cable transom hogshead. Gaff pillage to go on account grog aft chase guns piracy yardarm knave clap of thunder.</p>', 'posts/post4.jpg', 'yarr-post', 'this be a meta descript', 'keyword1, keyword2, keyword3', 'PUBLISHED', 0, '2020-01-28 07:13:05', '2020-01-28 07:13:05');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', '2020-01-28 07:12:56', '2020-01-28 07:12:56'),
(2, 'clinic', 'Clinic Manager', '2020-01-28 07:12:56', '2020-01-28 10:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `details`, `type`, `order`, `group`) VALUES
(1, 'site.title', 'Site Title', 'Medical Brows', '', 'text', 1, 'Site'),
(2, 'site.description', 'Site Description', 'Site Description', '', 'text', 2, 'Site'),
(3, 'site.logo', 'Site Logo', '', '', 'image', 3, 'Site'),
(4, 'site.google_analytics_tracking_id', 'Google Analytics Tracking ID', NULL, '', 'text', 4, 'Site'),
(5, 'admin.bg_image', 'Admin Background Image', '', '', 'image', 5, 'Admin'),
(6, 'admin.title', 'Admin Title', 'Voyager', '', 'text', 1, 'Admin'),
(7, 'admin.description', 'Admin Description', 'Welcome to Voyager. The Missing Admin for Laravel', '', 'text', 2, 'Admin'),
(8, 'admin.loader', 'Admin Loader', '', '', 'image', 3, 'Admin'),
(9, 'admin.icon_image', 'Admin Icon Image', '', '', 'image', 4, 'Admin'),
(10, 'admin.google_analytics_client_id', 'Google Analytics Client ID (used for admin dashboard)', NULL, '', 'text', 1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clinics`
--

CREATE TABLE `tbl_clinics` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_vacation` tinyint(4) DEFAULT 0,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_clinics`
--

INSERT INTO `tbl_clinics` (`id`, `name`, `email`, `address`, `is_vacation`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Clinic2', 'clinic2@mail.com', 'Clinic 2 address', 0, 0, '2020-02-01 15:28:27', '2020-02-01 22:13:26'),
(2, 'sdas', 'afd@sdf', 'af', 0, 1, '2020-02-01 15:43:28', '2020-02-01 15:53:39'),
(3, 'tttttt', 'adfasdf@sdf', 'Room 3 ABC str ABC building', 0, 1, '2020-02-01 15:43:44', '2020-02-01 15:53:37'),
(4, 'atdfa', 'adfasdf@sdfadf', 'adfadfadf', 0, 1, '2020-02-01 15:52:16', '2020-02-01 15:53:34'),
(5, 'Clinic1', 'clinic1@mail.com', 'Address1111', 0, 0, '2020-02-01 15:53:27', '2020-02-01 22:13:06'),
(6, 'Clinic 3', 'clinc3@mail.com', '新宿', 0, 0, '2020-02-01 22:13:49', '2020-02-01 22:13:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menus`
--

CREATE TABLE `tbl_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `is_vacation` tinyint(4) DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_menus`
--

INSERT INTO `tbl_menus` (`id`, `name`, `rank_id`, `tax_id`, `amount`, `start_time`, `end_time`, `is_vacation`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'face change', 123, 123, 500, '2020-02-26 16:00:00', '2020-02-26 16:00:00', 0, 0, NULL, NULL),
(2, 'hair change', 123, 123, 500, '2020-02-26 16:00:00', '2020-02-26 16:00:00', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_operable_parts`
--

CREATE TABLE `tbl_operable_parts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `is_new` tinyint(4) DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `counselor_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`id`, `is_new`, `phone_number`, `order_id`, `staff_id`, `counselor_id`, `menu_id`, `clinic_id`, `description`, `is_deleted`, `created_at`, `updated_at`, `start_time`, `end_time`) VALUES
(4, 1, '12345678', 11, 2, 1, 1, 6, 'This is test order11111', 0, '2020-02-03 00:37:06', '2020-02-03 00:42:12', '2020-02-03 10:36:00', '2020-02-28 12:36:00'),
(5, 1, '53734563', 21, 2, 1, 2, 6, 'This is test order11111345345634563456', 0, '2020-02-03 00:37:52', '2020-02-03 00:42:02', '2020-02-04 14:36:00', '2020-02-29 16:36:00'),
(6, 0, 'sdfgert', 34, 3, 1, 1, 6, 'This is test order11111', 0, '2020-02-03 00:39:58', '2020-02-03 00:39:58', '2020-02-04 13:39:00', '2020-02-12 16:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ranks`
--

CREATE TABLE `tbl_ranks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_ranks`
--

INSERT INTO `tbl_ranks` (`id`, `name`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'グランドマスタートレイナー', 0, '2020-01-28 13:07:26', '2020-01-28 13:07:26'),
(2, 'グランドマスター', 0, '2020-01-28 13:08:58', '2020-01-28 13:08:58'),
(3, 'マスター', 0, '2020-01-28 13:09:44', '2020-01-28 13:09:44'),
(4, 'ノービスアーテイスト', 1, '2020-01-28 13:12:07', '2020-02-01 04:10:51'),
(5, 'カウンセラー', 0, '2020-01-28 13:12:17', '2020-01-28 13:12:17'),
(6, 'hnbnbn', 1, '2020-01-29 10:14:11', '2020-01-29 11:19:42'),
(7, 'bbbb', 1, '2020-01-29 10:14:17', '2020-01-29 11:23:41'),
(8, 'fdfd', 1, '2020-01-29 10:17:40', '2020-01-29 11:14:34'),
(9, 'RTRTRTR', 1, '2020-01-29 10:18:08', '2020-01-29 11:22:12'),
(10, '7878', 1, '2020-01-29 10:18:57', '2020-01-29 11:19:57'),
(11, 'ghh', 1, '2020-01-29 10:34:44', '2020-01-29 11:01:35'),
(12, 'bnb', 1, '2020-01-29 10:38:35', '2020-01-29 11:22:09'),
(13, 'hghf', 1, '2020-01-29 10:41:25', '2020-01-29 11:09:46'),
(14, 'weq', 1, '2020-01-29 11:23:50', '2020-01-29 11:39:56'),
(15, 'hesd', 1, '2020-01-29 11:25:18', '2020-01-31 02:31:21'),
(16, 'adfasdf', 1, '2020-01-29 11:27:24', '2020-01-29 11:37:35'),
(17, 'dfg', 1, '2020-01-29 11:27:34', '2020-01-29 11:27:42'),
(18, 'asdfasdf', 1, '2020-01-29 11:37:56', '2020-01-29 12:36:40'),
(19, 'ertretet', 1, '2020-01-29 11:38:01', '2020-01-31 02:31:16'),
(20, 'sdfgsdfg', 1, '2020-01-29 11:38:05', '2020-01-29 12:36:49'),
(21, 'testtest', 1, '2020-01-29 11:38:09', '2020-01-31 02:31:14'),
(22, 'wrewwer', 1, '2020-01-29 11:38:14', '2020-01-29 22:32:19'),
(23, 'mklkmolm', 1, '2020-01-29 21:59:59', '2020-01-29 22:00:07'),
(24, 'jklhhkjhkjhjk', 1, '2020-01-29 22:31:54', '2020-01-29 22:32:14'),
(25, 'sd', 1, '2020-01-30 22:50:24', '2020-01-31 00:52:04'),
(26, 'test', 1, '2020-01-30 23:43:25', '2020-01-31 00:26:50'),
(27, 'teste', 1, '2020-01-30 23:44:33', '2020-01-31 00:25:23'),
(28, '11111111', 1, '2020-01-30 23:49:51', '2020-01-31 00:24:21'),
(29, '2222222222', 1, '2020-01-30 23:50:07', '2020-01-31 00:22:29'),
(30, '333', 1, '2020-01-30 23:52:12', '2020-01-31 00:22:01'),
(31, 'sdfsdfsfsdfsdf', 1, '2020-01-30 23:52:29', '2020-01-31 00:21:37'),
(32, '33333333', 1, '2020-01-30 23:55:44', '2020-01-31 00:19:33'),
(38, 'testtesttestestetstwssfasdfas', 1, '2020-01-31 04:23:00', '2020-01-31 07:43:19'),
(39, '45645', 1, '2020-01-31 07:43:18', '2020-01-31 22:39:02'),
(45, '1234567', 0, '2020-01-31 07:43:36', '2020-01-31 22:38:38'),
(46, 'RedLine1', 0, '2020-01-31 22:39:10', '2020-01-31 22:39:10'),
(47, 'jkhkjh', 0, '2020-02-01 02:08:57', '2020-02-01 02:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rank_schedules`
--

CREATE TABLE `tbl_rank_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_regions`
--

CREATE TABLE `tbl_regions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_regions`
--

INSERT INTO `tbl_regions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '表参道', '2020-02-01 18:42:54', '2020-02-01 18:42:54'),
(2, '新宿', '2020-02-01 18:43:02', '2020-02-01 18:43:02'),
(3, '大阪', '2020-02-01 18:43:11', '2020-02-01 18:43:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shift_histories`
--

CREATE TABLE `tbl_shift_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staffs`
--

CREATE TABLE `tbl_staffs` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_type_id` int(11) DEFAULT NULL,
  `is_vacation` int(11) DEFAULT NULL,
  `is_deleted` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `clinic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_staffs`
--

INSERT INTO `tbl_staffs` (`id`, `full_name`, `staff_type_id`, `is_vacation`, `is_deleted`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'Gimura', 7, 0, 0, '2020-02-01 21:40:36', '2020-02-01 21:53:20', 5),
(2, 'Yamamoto', 2, 0, 0, '2020-02-01 21:53:40', '2020-02-01 21:53:40', 5),
(3, 'Hanako', 1, 0, 0, '2020-02-01 21:55:14', '2020-02-01 21:55:14', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staff_types`
--

CREATE TABLE `tbl_staff_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_staff_types`
--

INSERT INTO `tbl_staff_types` (`id`, `name`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'GMT', 0, '2020-02-01 14:39:09', '2020-02-01 14:39:09'),
(2, 'M', 0, '2020-02-01 14:39:18', '2020-02-01 14:39:18'),
(3, 'RA', 0, '2020-02-01 14:39:48', '2020-02-01 14:39:48'),
(4, 'A', 0, '2020-02-01 14:39:58', '2020-02-01 14:39:58'),
(5, 'NA', 0, '2020-02-01 14:40:08', '2020-02-01 14:40:08'),
(6, 'T', 0, '2020-02-01 14:40:16', '2020-02-01 14:40:16'),
(7, 'カウセ', 0, '2020-02-01 14:41:03', '2020-02-01 14:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tax_rates`
--

CREATE TABLE `tbl_tax_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` float NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_tax_rates`
--

INSERT INTO `tbl_tax_rates` (`id`, `name`, `amount`, `start_time`, `end_time`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'aasdf', 24, '2020-02-01 20:08:00', '2020-02-28 20:08:00', 0, '2020-02-01 04:25:03', '2020-02-01 04:25:03'),
(2, 'RedLine155', 7832, '2020-02-27 20:26:00', '2020-02-29 20:59:00', 1, '2020-02-01 04:26:40', '2020-02-01 04:30:54'),
(3, 'asdfasdfg', 42323, '2020-02-28 20:26:00', '2020-02-29 20:59:00', 0, '2020-02-01 04:29:16', '2020-02-01 06:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) UNSIGNED NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `table_name`, `column_name`, `foreign_key`, `locale`, `value`, `created_at`, `updated_at`) VALUES
(1, 'data_types', 'display_name_singular', 5, 'pt', 'Post', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(2, 'data_types', 'display_name_singular', 6, 'pt', 'Página', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(3, 'data_types', 'display_name_singular', 1, 'pt', 'Utilizador', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(4, 'data_types', 'display_name_singular', 4, 'pt', 'Categoria', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(5, 'data_types', 'display_name_singular', 2, 'pt', 'Menu', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(6, 'data_types', 'display_name_singular', 3, 'pt', 'Função', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(7, 'data_types', 'display_name_plural', 5, 'pt', 'Posts', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(8, 'data_types', 'display_name_plural', 6, 'pt', 'Páginas', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(9, 'data_types', 'display_name_plural', 1, 'pt', 'Utilizadores', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(10, 'data_types', 'display_name_plural', 4, 'pt', 'Categorias', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(11, 'data_types', 'display_name_plural', 2, 'pt', 'Menus', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(12, 'data_types', 'display_name_plural', 3, 'pt', 'Funções', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(13, 'categories', 'slug', 1, 'pt', 'categoria-1', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(14, 'categories', 'name', 1, 'pt', 'Categoria 1', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(15, 'categories', 'slug', 2, 'pt', 'categoria-2', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(16, 'categories', 'name', 2, 'pt', 'Categoria 2', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(17, 'pages', 'title', 1, 'pt', 'Olá Mundo', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(18, 'pages', 'slug', 1, 'pt', 'ola-mundo', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(19, 'pages', 'body', 1, 'pt', '<p>Olá Mundo. Scallywag grog swab Cat o\'nine tails scuttle rigging hardtack cable nipper Yellow Jack. Handsomely spirits knave lad killick landlubber or just lubber deadlights chantey pinnace crack Jennys tea cup. Provost long clothes black spot Yellow Jack bilged on her anchor league lateen sail case shot lee tackle.</p>\r\n<p>Ballast spirits fluke topmast me quarterdeck schooner landlubber or just lubber gabion belaying pin. Pinnace stern galleon starboard warp carouser to go on account dance the hempen jig jolly boat measured fer yer chains. Man-of-war fire in the hole nipperkin handsomely doubloon barkadeer Brethren of the Coast gibbet driver squiffy.</p>', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(20, 'menu_items', 'title', 1, 'pt', 'Painel de Controle', '2020-01-28 07:13:06', '2020-01-28 07:13:06'),
(21, 'menu_items', 'title', 2, 'pt', 'Media', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(22, 'menu_items', 'title', 12, 'pt', 'Publicações', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(23, 'menu_items', 'title', 3, 'pt', 'Utilizadores', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(24, 'menu_items', 'title', 11, 'pt', 'Categorias', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(25, 'menu_items', 'title', 13, 'pt', 'Páginas', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(26, 'menu_items', 'title', 4, 'pt', 'Funções', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(27, 'menu_items', 'title', 5, 'pt', 'Ferramentas', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(28, 'menu_items', 'title', 6, 'pt', 'Menus', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(29, 'menu_items', 'title', 7, 'pt', 'Base de dados', '2020-01-28 07:13:07', '2020-01-28 07:13:07'),
(30, 'menu_items', 'title', 10, 'pt', 'Configurações', '2020-01-28 07:13:07', '2020-01-28 07:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `remember_token`, `settings`, `created_at`, `updated_at`, `user_id`, `is_deleted`) VALUES
(1, 1, 'Admin', 'admin@admin.com', 'users/default.png', NULL, '$2y$10$swdnObuPvOaOdPE.giyUCOBpNO6Lzu7UU2VyTNLR4nXJP3w.ipHJC', 'd36TYhvgnaFSsYJcNbo2qi9oodHeIo4Ck0VrjufKEbK3AgG1aHdN1SyaQ0Hl', '{\"locale\":\"en\"}', '2020-01-28 07:13:04', '2020-01-28 10:38:14', 'administrator', 0),
(2, 2, 'Clinic1', 'clinic1@mail.com', 'users/default.png', NULL, '$2y$10$WFzJaFBy.Q5ukA4Fr4pyLOzPZcifBJiSFyfcEvTpZQhAKRrynnxby', NULL, '{\"locale\":\"ja\"}', '2020-01-28 10:32:46', '2020-01-28 10:32:46', 'clinic_1', 0),
(3, 1, 'admin', 'admin@mail.com', 'users/default.png', NULL, 'aldjflajdflkajsdfasdf', NULL, NULL, '2020-02-01 05:05:29', '2020-02-01 05:52:04', 'admin1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `data_rows`
--
ALTER TABLE `data_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_rows_data_type_id_foreign` (`data_type_id`);

--
-- Indexes for table `data_types`
--
ALTER TABLE `data_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_types_name_unique` (`name`),
  ADD UNIQUE KEY `data_types_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_name_unique` (`name`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_key_index` (`key`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `tbl_clinics`
--
ALTER TABLE `tbl_clinics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_menus`
--
ALTER TABLE `tbl_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_operable_parts`
--
ALTER TABLE `tbl_operable_parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ranks`
--
ALTER TABLE `tbl_ranks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tbl_ranks_name_unique` (`name`);

--
-- Indexes for table `tbl_rank_schedules`
--
ALTER TABLE `tbl_rank_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_regions`
--
ALTER TABLE `tbl_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shift_histories`
--
ALTER TABLE `tbl_shift_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_staffs`
--
ALTER TABLE `tbl_staffs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_staff_types`
--
ALTER TABLE `tbl_staff_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tax_rates`
--
ALTER TABLE `tbl_tax_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_roles_user_id_index` (`user_id`),
  ADD KEY `user_roles_role_id_index` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `data_rows`
--
ALTER TABLE `data_rows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `data_types`
--
ALTER TABLE `data_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_clinics`
--
ALTER TABLE `tbl_clinics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_menus`
--
ALTER TABLE `tbl_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_operable_parts`
--
ALTER TABLE `tbl_operable_parts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_ranks`
--
ALTER TABLE `tbl_ranks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tbl_rank_schedules`
--
ALTER TABLE `tbl_rank_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_regions`
--
ALTER TABLE `tbl_regions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_shift_histories`
--
ALTER TABLE `tbl_shift_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_staffs`
--
ALTER TABLE `tbl_staffs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_staff_types`
--
ALTER TABLE `tbl_staff_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_tax_rates`
--
ALTER TABLE `tbl_tax_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `data_rows`
--
ALTER TABLE `data_rows`
  ADD CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
