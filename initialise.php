<?php
#require "stopwatch.php";
require "lib/constants.php";
// start the timer
#StopWatch::start();

# Specify the full path of the database file.
# Make sure the web service (e.g. "IUSR" in IIS's case) has full permissions to both the database file AND the folder it's located in.
# If IUSR doesnt have write access, you'll be given a "500: Internal Server Error".

# Create the database if it doesn't already exist.
$db = new SQLite3(DATABASE_FILE);
$db->exec('BEGIN TRANSACTION;');

try {
	$query_string = "CREATE TABLE IF NOT EXISTS OperatingSystems (
		ID      INTEGER PRIMARY KEY,
		Name    TEXT,
		Version TEXT);";
	$db->exec($query_string);

	$query_string = "CREATE TABLE IF NOT EXISTS Browsers (
		ID      INTEGER PRIMARY KEY,
		Name    TEXT,
		Version TEXT);";
	$db->exec($query_string);

	$query_string = "CREATE TABLE IF NOT EXISTS MacAddresses (
		ID         INTEGER PRIMARY KEY,
		Address    TEXT,
		DeviceType TEXT);";
	$db->exec($query_string);

	$query_string = "CREATE TABLE IF NOT EXISTS Campuses (
		ID      INTEGER PRIMARY KEY,
		Name    TEXT);";
	$db->exec($query_string);

	$query_string = "CREATE TABLE IF NOT EXISTS Devices (
		ID                INTEGER PRIMARY KEY,
		OperatingSystemID INTEGER,
		BrowserID         INTEGER,
		MacAddressID      INTEGER,
		CampusID          INTEGER,
		Date              INTEGER DEFAULT (strftime('%s','now')),
		FOREIGN KEY(OperatingSystemID) REFERENCES OperatingSystems(ID),
		FOREIGN KEY(BrowserID)         REFERENCES Browsers(ID),
		FOREIGN KEY(MacAddressID)      REFERENCES MacAddresses(ID),
		FOREIGN KEY(CampusID)          REFERENCES Campuses(ID));";
	$db->exec($query_string);

	$query_string = "CREATE VIEW IF NOT EXISTS DeviceInformation AS
		SELECT *
		FROM (SELECT Campuses.Name AS Campus,
        MacAddresses.Address AS MacAddress,
        MacAddresses.DeviceType AS DeviceType,
				OperatingSystems.Name AS OSName,
				OperatingSystems.Version AS OSVersion,
				Browsers.Name AS BrowserName,
				Browsers.Version AS BrowserVersion,
				date(Devices.Date, 'unixepoch', 'localtime') AS Date
			FROM Devices
			INNER JOIN Campuses ON Campuses.ID = Devices.CampusID
			INNER JOIN MacAddresses ON MacAddresses.ID = Devices.MacAddressID
			INNER JOIN OperatingSystems ON OperatingSystems.ID = Devices.OperatingSystemID
			INNER JOIN Browsers ON Browsers.ID = Devices.BrowserID)
		GROUP BY Campus, MacAddress, OSName, OSVersion, BrowserName, date(Date);";
	$db->exec($query_string);
  
  foreach (CAMPUSES as $campusInfo) {
    $query_string = "INSERT INTO Campuses(id,name) 
      SELECT ".$campusInfo["ID"].", '".$campusInfo["Name"]."' 
      WHERE NOT EXISTS(SELECT 1 FROM Campuses WHERE id = ".$campusInfo["ID"]." AND name = '".$campusInfo["Name"]."');";
    $db->exec($query_string);
  }
  
	$db->exec('COMMIT;');
	
	#echo "Database initialised successfully.";
} catch (Exception $e) {
	$db->exec('ROLLBACK;');
	
	#echo "Database initialisation failed.";
} finally {
	$db->close();
}

#echo '<br>Elapsed time: ' . StopWatch::elapsed() . ' seconds';
?>