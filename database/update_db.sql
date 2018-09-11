-- BEFOR EXECUTE THIS SQL MAKE A BACKUP OF YOUR DATABASE!!!!!
-- BITTE VOR AUSFÜHRUNG UNBEDINGT EIN BACKUP DER DATENBANK DURCHFÜHREN!!!
-- Delete unused columns in database
-- Löschen ungenutzter Spalten in der Datenbank

ALTER TABLE `boinc_grundwerte`
  DROP `pending_credits`,
  DROP `results_ready`,
  DROP `expavg_credit`,
  DROP `expavg_time`,
  DROP `project_rank_total_credit`,
  DROP `project_rank_expavg_credit`,
  DROP `create_time`,
  DROP `country`,
  DROP `user_name`,
  DROP `user_url`,
  DROP `team_id`,
  DROP `team_name`,
  DROP `team_url`,
  DROP `team_rank_total_credit`,
  DROP `team_rank_expavg_credit`,
  DROP `team_member_count`,
  DROP `computer_count`,
  DROP `active_computer_count`;

ALTER TABLE `boinc_grundwerte` CHANGE `project_userid` `project_userid` INT;
ALTER TABLE `boinc_grundwerte` CHANGE `begin_credits` `begin_credits` BIGINT;
ALTER TABLE `boinc_grundwerte` CHANGE `project_status` `project_status` INT;
ALTER TABLE `boinc_grundwerte` CHANGE `total_credits` `total_credits` BIGINT;

ALTER TABLE `boinc_user` CHANGE `lastupdate_start` `lastupdate_start` INT;
ALTER TABLE `boinc_user` CHANGE `lastupdate` `lastupdate` INT;

ALTER TABLE `boinc_werte`
  DROP `pending_credits`;

ALTER TABLE `boinc_werte` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT;
ALTER TABLE `boinc_werte` CHANGE `credits` `credits` BIGINT;
ALTER TABLE `boinc_werte` CHANGE `time_stamp` `time_stamp` INT;

ALTER TABLE `boinc_werte_day`
  DROP `pending_credits`,
  DROP `rank`,
  DROP `rank_team`;

ALTER TABLE `boinc_werte_day` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT;
ALTER TABLE `boinc_werte_day` CHANGE `total_credits` `total_credits` BIGINT;
ALTER TABLE `boinc_werte_day` CHANGE `time_stamp` `time_stamp` INT;

OPTIMIZE TABLE `boinc_grundwerte`;
OPTIMIZE TABLE `boinc_werte`;
OPTIMIZE TABLE `boinc_werte_day`;
OPTIMIZE TABLE `boinc_user`;