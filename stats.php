<?php
require('vendor/autoload.php');
require "lib/constants.php";

$lava = new Khill\Lavacharts\Lavacharts;

if (file_exists(DATABASE_FILE)) {
	$db = new SQLite3(DATABASE_FILE);
  
  $query = 'SELECT * FROM DeviceInformation';
  if (isset($_GET["campus"])) {
    $query .= " where lower(CAMPUS) = lower(:campus)";
	
	if (isset($_GET["year"])) {
		$query .= " and ";
	}
  } else {
    if (isset($_GET["year"])) {
	 $query .= " where ";
	}
  }
  
  if (isset($_GET["year"])) {
    $query .= "strftime('%Y', DeviceInformation.DATE) = :year";
  }
  
  if ($stmt = $db->prepare($query)) {
    if (isset($_GET["campus"])) {
	  $stmt->bindValue(':campus', $_GET["campus"], SQLITE3_TEXT);
    }
	if (isset($_GET["year"])) {
	  $stmt->bindValue(':year', $_GET["year"], SQLITE3_TEXT);
    }
			
    $result = $stmt->execute();
    $results = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $results[] = $row;
    }
    
    if (count($results) != 0) {
      # Operating Systems
      $devices = $lava->DataTable();
      
      $devices->addStringColumn('Devices')
              ->addNumberColumn('Percent');
      
      $oSes = array();
      foreach ($results as $r) {
		  if ($r['OSName'] == null) {
			  $r['OSName'] = "Unknown";
		  }
        $oSes[] = array("Name"=>$r['OSName'], "ClientID"=>$r["MacAddress"]);
      }
      $uniqueOSes = array_unique($oSes, SORT_REGULAR);
      
      $osData = array();
      foreach ($uniqueOSes as $os) {
        $osData[$os["Name"]] = 0;
      }
      foreach ($uniqueOSes as $os) {
        $osData[$os["Name"]] += 1;
      }
      
      foreach($osData as $osName => $osCount) {
        $devices->addRow([$osName, $osCount]);
      }

      $lava->DonutChart('OSes', $devices, [
          'title' => 'Operating Systems'
      ]);
      
      # Browsers
      $browsers = $lava->DataTable();
      
      $browsers->addStringColumn('Browsers')
               ->addNumberColumn('Percent');
      
      $browserNames = array();
      foreach ($results as $r) {
		  if ($r['BrowserName'] == null) {
			  $r['BrowserName'] = "Unknown";
		  }
		  
        $browserNames[] = array("Name"=>$r['BrowserName'], "ClientID"=>$r["MacAddress"]);
      }
      $uniqueBrowsers = array_unique($browserNames, SORT_REGULAR);
      
      $browserData = array();
      foreach ($uniqueBrowsers as $browser) {
        $browserData[$browser["Name"]] = 0;
      }
      foreach ($uniqueBrowsers as $browser) {
        $browserData[$browser["Name"]] += 1;
      }
      
      foreach($browserData as $browserName => $browserCount) {
        $browsers->addRow([$browserName, $browserCount]);
      }

      $lava->DonutChart('Browsers', $browsers, [
          'title' => 'Browsers in Use'
      ]);
      
      # Campuses
      $campuses = $lava->DataTable();
      
      $campuses->addStringColumn('Campuses')
               ->addNumberColumn('Percent');
      
      $campusNames = array();
      foreach ($results as $r) {
        $campusNames[] = array("Name"=>$r['Campus'], "ClientID"=>$r["MacAddress"]);
      }
      $uniqueCampuses = array_unique($campusNames, SORT_REGULAR);
      
      $campusData = array();
      foreach ($uniqueCampuses as $campus) {
        $campusData[$campus["Name"]] = 0;
      }
      foreach ($uniqueCampuses as $campus) {
        $campusData[$campus["Name"]] += 1;
      }
        
      
      foreach($campusData as $campusName => $campusCount) {
        $campuses->addRow([$campusName, $campusCount]);
      }

      $lava->DonutChart('Campuses', $campuses, [
          'title' => 'Devices at Campus'
      ]);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home@Kurnai Device Stats</title>
  <link rel="shortcut icon" href="images/favicon.ico" type="image/vnd.microsoft.icon" />
  <link rel="stylesheet" href="css/style.css" type="text/css" >
  <script type="text/javascript">
  var deviceInformation = <?php echo json_encode($results); ?>;
  </script>
</head>

<body>

<div id="os-chart-div" class="chart"></div>
<div id="browser-chart-div" class="chart"></div>
<div id="campus-chart-div" class="chart"></div>

<?= $lava->render('DonutChart', 'OSes', 'os-chart-div') ?>
<?= $lava->render('DonutChart', 'Browsers', 'browser-chart-div') ?>
<?= $lava->render('DonutChart', 'Campuses', 'campus-chart-div') ?>
</body>