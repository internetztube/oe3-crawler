SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `hitradio` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `hitradio`;


CREATE TABLE IF NOT EXISTS `tracks` (
	id int(11) NOT NULL,
  	artist varchar(255) CHARACTER SET utf8 NOT NULL,
  	title varchar(255) CHARACTER SET utf8 NOT NULL,
  	cover varchar(255) CHARACTER SET utf8 NOT NULL,
  	played_at datetime NOT NULL,
  	created_at datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

ALTER TABLE `tracks` ADD PRIMARY KEY (`id`);


ALTER TABLE `tracks` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;