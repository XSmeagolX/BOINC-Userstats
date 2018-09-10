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

ALTER TABLE `boinc_werte`
  DROP `pending_credits`;

ALTER TABLE `boinc_werte_day`
  DROP `pending_credits`;

OPTIMIZE TABLE `boinc_grundwerte`;
OPTIMIZE TABLE `boinc_werte`;
OPTIMIZE TABLE `boinc_werte_day`;
OPTIMIZE TABLE `boinc_user`;