24 Aug 2024
--------------------------
ALTER TABLE `dbl_users` ADD `admin` TINYINT(1) NOT NULL DEFAULT '0' AFTER `timezone`; 
ALTER TABLE `tbl_appointments_booked` ADD `CancelPatientOrDoctor` ENUM('1','2','') NULL AFTER `agree`; 


28 Aug 2024
------------------

ALTER TABLE `tbl_appointments_booked` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT; 

CREATE TABLE `tbl_report_reviews_replies` (
  `id` int NOT NULL,
  `appointment_id` int UNSIGNED DEFAULT NULL,
  `doctor_reply` text COLLATE utf8mb3_unicode_ci,
  `upload_file1` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `upload_file2` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `upload_file3` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_report_reviews_replies`
--
ALTER TABLE `tbl_report_reviews_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointments_booked` (`appointment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_report_reviews_replies`
--
ALTER TABLE `tbl_report_reviews_replies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_report_reviews_replies`
--
ALTER TABLE `tbl_report_reviews_replies`
  ADD CONSTRAINT `fk_appointments_booked` FOREIGN KEY (`appointment_id`) REFERENCES `tbl_appointments_booked` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


ALTER TABLE `tbl_report_reviews_replies` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP; 

ALTER TABLE `tbl_report_reviews_replies` CHANGE `upload_file3` `upload_file3` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NULL DEFAULT NULL; 

-- 2/09/2024

ALTER TABLE `tbl_appointments_booked` CHANGE `CancelPatientOrDoctor` `CancelPatientOrDoctor` ENUM('1','2') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1' COMMENT '1-cancel by patient, 2-cancel by doctor';


2/09/2024
CREATE TABLE `secondlookortho_db`.`blog_articles` (`id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(50) NULL DEFAULT NULL , `short_desc` TEXT NULL DEFAULT NULL , `long_desc` TEXT NULL DEFAULT NULL , `image` VARCHAR(255) NULL DEFAULT NULL , `status` ENUM('active','inactive','deleted') NOT NULL DEFAULT 'active' , `category_id` INT NULL DEFAULT NULL , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATE NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `secondlookortho_db`.`blog_categories` (`id` INT NOT NULL AUTO_INCREMENT , `category_name` VARCHAR(100) NULL DEFAULT NULL , `status` ENUM('active','inactive','deleted') NULL DEFAULT 'active' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `secondlookortho_db`.`blog_comments` (`id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NULL DEFAULT NULL , `article_id` INT NULL DEFAULT NULL , `comment` TEXT NULL DEFAULT NULL , `status` ENUM('active','inactive','deleted') NOT NULL DEFAULT 'active' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `blog_articles` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL; 
created by salman


03/09/2024
ALTER TABLE `tbl_appointments_booked` CHANGE `notes` `notes` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

05/09/24
ALTER TABLE `dbl_users` ADD `otp` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `state`; 

ALTER TABLE `dbl_users` ADD `otp` VARCHAR(6) NULL AFTER `gender`; 

6-09-24
ALTER TABLE `tbl_plans` CHANGE `status` `status` ENUM('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Active'; 
06/09/24
ALTER TABLE `tbl_appointments_booked` ADD `phone_meeting_link` VARCHAR(255) NULL AFTER `CancelPatientOrDoctor`; 
ALTER TABLE `tbl_appointments_booked` ADD `NotConfirmed` ENUM('1','0') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0' AFTER `agree`;
ALTER TABLE `dbl_users` CHANGE `status` `status` ENUM('Inactive','Active') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Active'; 

7-09-24
ALTER TABLE `dbl_users` CHANGE `status` `status` ENUM('Inactive','Active','Deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Active'; 
ALTER TABLE `tbl_appointments_booked` ADD `doctor_prescription` TEXT NULL DEFAULT NULL AFTER `NotConfirmed`; 

09/09/24
UPDATE `tbl_admins` SET `password` = '5ebe2294ecd0e0f08eab7690d2a6ee69' WHERE `tbl_admins`.`id` = 1; 
ALTER TABLE `tbl_payments`
  DROP `tax_rate`,
  DROP `tax_amount`; 
ALTER TABLE `tbl_payments` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT; 

10/09/24
ALTER TABLE `blog_comments` CHANGE `status` `status` ENUM('active','inactive','deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'inactive'; 

10/09/2024
ALTER TABLE `tbl_appointments_booked` ADD `upload_file1` TEXT NULL AFTER `phone_meeting_link`; 
ALTER TABLE `tbl_appointments_booked` ADD `completed_at` DATETIME NULL AFTER `updated_at`; 


11-09-24


ALTER TABLE `static_page_contents` ADD `section_heading3` TEXT NULL DEFAULT NULL AFTER `section_heading2`; 
ALTER TABLE `static_page_contents` ADD `section_short_desc3` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_short_desc2`; 

UPDATE `static_page_contents` SET `section_short_desc1` = ' Tracye J. Lawyer MD, PhD, FAAOS, ABOS is board certified and fellowship trained in orthopedic sports medicine with a focus on cartilage joint regeneration. She specializes in arthroscopic and open surgery of the shoulder, elbow, and knee.\r\n Earned a PhD focusing on cartilage regeneration\r\n Completed a sports medicine fellowship at the University of Pittsburgh\r\n \r\n Earned her bachelor’s degree at Stanford University\r\n Was a two-sport collegiate athlete + awarded PAC-10 Player of the Year Competed in the U.S. Olympic Trials in track and field\r\n Received induction into the Stanford Hall of Fame', `section_short_desc2` = ' SecondLook Ortho provides patients a second opinion related to their orthopedic injury, diagnosis and/or treatment options. We hope to offer clarity and guidance to help you make the best decision[...]

CREATE TABLE `tbl_admin_setting` (
  `id` int NOT NULL,
  `payment_mode` int NOT NULL COMMENT '0:sandbox,1:livemode',
  `status` int NOT NULL,
  `discount` float(10,2) DEFAULT NULL,
  `modified_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin_setting`
--

INSERT INTO `tbl_admin_setting` (`id`, `payment_mode`, `status`, `discount`, `modified_date`) VALUES
(1, 0, 1, 25.00, '2021-04-14 06:11:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin_setting`
--
ALTER TABLE `tbl_admin_setting`
  ADD PRIMARY KEY (`id`);
COMMIT;
ALTER TABLE `static_page_contents` ADD `section_heading3` TEXT NULL DEFAULT NULL AFTER `section_heading2`; 
ALTER TABLE `static_page_contents` ADD `section_short_desc3` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_short_desc2`; 


12-09-2024
ALTER TABLE `tbl_available_schedule_slots` ADD `lock_time` DATETIME NULL DEFAULT NULL AFTER `color`;

13-09-24
ALTER TABLE `static_page_contents` ADD `section_heading4` VARCHAR(100) NULL DEFAULT NULL AFTER `section_image3`, ADD `section_heading5` VARCHAR(100) NULL DEFAULT NULL AFTER `section_heading4`, ADD `section_heading6` VARCHAR(100) NULL DEFAULT NULL AFTER `section_heading5`, ADD `section_heading7` VARCHAR(100) NULL DEFAULT NULL AFTER `section_heading6`, ADD `section_heading8` VARCHAR(100) NULL DEFAULT NULL AFTER `section_heading7`;




ALTER TABLE `static_page_contents` ADD `section_heading10` VARCHAR(100) NULL DEFAULT NULL AFTER `section_heading8`, ADD `section_heading9` VARCHAR(100) NULL DEFAULT NULL AFTER `section_heading10`, ADD `section_short_desc4` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_heading9`, ADD `section_short_desc5` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_short_desc4`, ADD `section_short_desc6` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_short_desc5`, ADD `section_short_desc7` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_short_desc6`, ADD `step1` MEDIUMTEXT NULL DEFAULT NULL AFTER `section_short_desc7`, ADD `step2` MEDIUMTEXT NULL DEFAULT NULL AFTER `step1`, ADD `step3` MEDIUMTEXT NULL DEFAULT NULL AFTER `step2`, ADD `section_image4` VARCHAR(255) NULL DEFAULT NULL AFTER `step3`, ADD `section_image5` VARCHAR(255) NULL DEFAULT NULL AFTER `section_image4`, ADD `section_image6` VARCHAR(255) NULL DEFAULT NULL AFTER `section_image5`, ADD `section_image7` VARCHAR(255) NULL DEFAULT NULL AFTER `section_image6`, ADD `section_image8` VARCHAR(255) NULL DEFAULT NULL AFTER `section_image7`;

ALTER TABLE `static_page_contents` ADD `section_short_desc8` MEDIUMINT NULL DEFAULT NULL AFTER `section_short_desc7`; 
ALTER TABLE `static_page_contents` CHANGE `section_short_desc8` `section_short_desc8` MEDIUMINT NULL DEFAULT NULL; 

//15 sept 
CREATE TABLE `tbl_stripe_customers` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `patient_id` int(11) NOT NULL,
    `stripe_customer_id` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `tbl_stripe_customers` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `patient_id` int(11) NOT NULL,
    `stripe_customer_id` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tbl_stripe_cards` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `stripe_customer_id` varchar(255) NOT NULL,
    `stripe_card_id` varchar(255) NOT NULL,
    `last4` varchar(4) NOT NULL,
    `brand` varchar(50) NOT NULL,
    `exp_month` int(2) NOT NULL,
    `exp_year` int(4) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tbl_payments` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT; 


ALTER TABLE `tbl_appointments_booked` ADD `payment_id` INT(10) NULL AFTER `id`; 

ALTER TABLE testimonials 
ADD COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active';

ALTER TABLE `tbl_stripe_customers` ADD UNIQUE(`stripe_customer_id`);
ALTER TABLE `tbl_stripe_cards` ADD UNIQUE(`stripe_card_id`);

ALTER TABLE `dbl_users` CHANGE `status` `status` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Inactive';

ALTER TABLE `dbl_users` ADD `otp_validated` TINYINT(1) NOT NULL DEFAULT '0' AFTER `otp`; 

ALTER TABLE `tbl_appointments_booked` ADD `send_reminder` INT(10) NOT NULL DEFAULT '0' AFTER `doctor_prescription`; 

ALTER TABLE `tbl_appointments_booked` CHANGE `status` `status` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Un-Paid' COMMENT 'in-Process, Confirmed(Doctor acceptance) ,Cencelled'; 

ALTER TABLE `tbl_appointments_booked` CHANGE `phone_meeting_link` `phone_meeting_link` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 

23-09-2024
CREATE TABLE `secondlookortho_db`.`tbl_contact_us` ( `id` INT NOT NULL AUTO_INCREMENT , `first_name` VARCHAR(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `last_name` VARCHAR(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `email` VARCHAR(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `phone` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `message` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL , `status` VARCHAR(10) NOT NULL DEFAULT '1' , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
CREATE TABLE `secondlookortho_db`.`tbl_notifications` ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `status` VARCHAR(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , `updatesd_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
ALTER TABLE `tbl_notifications` ADD `user_id` INT(55) NULL DEFAULT NULL AFTER `id`; 
ALTER TABLE `tbl_notifications` CHANGE `status` `status` VARCHAR(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '1';

25-09-2024 added by darshan
ALTER TABLE `tbl_admins` ADD `phone_number` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `email`;

26-09-2024
ALTER TABLE `tbl_appointments_booked` ADD `msg_flag` INT(11) NOT NULL DEFAULT '0' COMMENT 'msg_seen_unseen' AFTER `send_reminder`;
ALTER TABLE `messages` ADD `msg_flag` INT(11) NOT NULL DEFAULT '0' COMMENT 'msg_seen_unseen' AFTER `message`
ALTER TABLE `messages` ADD `files` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0' AFTER `message`; 



27-09-24
ALTER TABLE `static_page_contents` ADD `section_short_desc9` MEDIUMINT NULL DEFAULT NULL AFTER `section_short_desc8`; ALTER TABLE `static_page_contents` CHANGE `section_short_desc9` `section_short_desc9` MEDIUMTEXT NULL DEFAULT NULL; 

01_10_2024
ALTER TABLE `messages` ADD `app_id` INT(100) NULL DEFAULT NULL AFTER `chat_id`;

10-10-2024
ALTER TABLE `tbl_appointments_booked` ADD `follow_up` TINYINT(1) NOT NULL DEFAULT '0' AFTER `active`; 

16-10-2024
ALTER TABLE `tbl_appointments_booked` CHANGE `symptoms` `symptoms` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;