<?php
session_start();

# https://packagist.org/packages/whichbrowser/parser
require_once 'vendor/autoload.php'; # Require "whichbrowser/parser" library.
require_once "./lib/constants.php";
require_once "./lib/userlookup.php";

# Test to see if the request came externally via the DMZ box, or internally via LAN.
# (Used for the cyberhound/deviceregister sections)
$localaccess = true;
if ($_SERVER['REMOTE_ADDR'] == DMZ_IP) {
  # Set $localaccess to false if the client's IP is the DMZ's (due to its NAT'ing)
  $localaccess = false;
}

$campus = CAMPUSES[($localaccess ? substr($_SERVER['REMOTE_ADDR'], 0, 6) : "00.000")]; 

$isFLO = ($campus["Name"] == "FLO");

# Get device info
$whichBrowser = new WhichBrowser\Parser(getallheaders());
$model        = $whichBrowser->device->getModel();
$deviceType   = $whichBrowser->device->type;
$osName       = $whichBrowser->os->getName();
$browser      = $whichBrowser->browser->name;

# Set the Office365 install URL based on user's OS
$O365Link = "https://portal.office.com/OLS/MySoftware.aspx";
switch (strtolower($osName)) {
  case "windows":
    $O365Link = "https://software.kurnaicollege.vic.edu.au/files/Office365Install-Kurnai.exe";
    break;
  case "ios":
    $O365Link = "https://itunes.apple.com/au/developer/microsoft-corporation/id298856275";
    break;
  case "android":
    $O365Link = "https://play.google.com/store/apps/dev?id=6720847872553662727&hl=en";
    break;
  case "chrome os":
    $O365Link = "https://chrome.google.com/webstore/detail/office-online/ndjpnladcallmjemlbaebfadecfhkepb";
    break;
  case "macos":
  case "os x":
  case "mac os x":
    if ($localaccess) {
      $O365Link = "http://software.kurnaicollege.vic.edu.au/files/Microsoft_Office_Installer.pkg";
    } else {
      $O365Link = "https://go.microsoft.com/fwlink/?LinkId=525133";
    }
    break;
}

$napLINK = "https://portal.office.com/OLS/MySoftware.aspx";
switch (strtolower($osName)) {
  case "windows":
    $napLINK = "https://pages.assessform.edu.au/uploads/files/Release/NAP%20Locked%20down%20browser.msi";
    break;
  case "ios":
    $napLINK = "https://apps.apple.com/us/app/nap-locked-down-browser/id1086807255";
    break;
  case "android":
    $napLINK = "https://play.google.com/store/apps/dev?id=6720847872553662727&hl=en";
    break;
  case "chrome os":
    $napLINK = "https://chrome.google.com/webstore/detail/nap-locked-down-browser-0/aifnolhhhkhdngmmdpclhiimkpojdafk";
    break;
  case "macos":
    $napLINK = "https://pages.assessform.edu.au/uploads/files/Release/NAP%20Locked%20down%20browser.dmg";
    break;
  case "os x":
  case "mac os x":
    $napLINK = "https://pages.assessform.edu.au/uploads/files/Release/NAP%20Locked%20down%20browser.pkg";
    break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- <?php echo strtolower($osName); ?>Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#1c75bc">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
<!-- /Favicon -->

<link rel="stylesheet" href="css/style.css?time=<?php echo round(microtime(true), 2); ?>" type="text/css" >
<link rel="stylesheet" href="css/flexboxgrid.min.css" type="text/css" >
<link rel="stylesheet" href="css/font-awesome.min.css">
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>


<?php 
# If the client is local, include the cyberhound SSL cert checking javascript.
# Essentially, it tries to load a CSS from the cyberhound box using SSL. If the cert isn't installed, the CSS won't load.
if ($localaccess) { ?>
<link rel="stylesheet" href="" class="check">
<script type="text/javascript">
$(function() {
  var cyberhoundheader = $("#cyberhoundheader");
  var installstatus = $("#install-status");
  var installstatustooltip = $("#install-status-tooltip");
  (function certificateCheck(nested=0) {
    if ($("#check").height() !== 100) {
      var stylesheet = $("link.check");
      var newStylesheet = stylesheet.clone();
      var uri = encodeURIComponent(window.location.href);
      cyberhoundheader.attr('href','http://cert.localnetwork.zone/install/?url='+uri);
	  if (nested > 5) {
        installstatus.html('<span class="fa-stack fa-lg"><i class="fa fa-square fa-lg" style="color: dimgray"></i><i class="fa fa-times-circle-o fa-stack-1x flashit" style="color: red"></i></span> Not Installed (Pages may fail to load!)');
        installstatustooltip.html('<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: tomato"></i> Browsing session not secure.<br />Click this link to fix it.');
	  }
      newStylesheet.attr("href", "https://cert.localnetwork.zone:60209/sslverify.css?time=<?php echo round(microtime(true), 2); ?>" + "?" + Math.round(new Date().getTime() / 1000));
      stylesheet.after(newStylesheet);
      stylesheet.remove();
      window.setTimeout(certificateCheck, 2000, nested+1);
    } else {
      installstatus.html('<span class="fa-stack fa-lg"><i class="fa fa-square fa-lg" style="color: dimgray"></i><i class="fa fa-check-circle-o fa-stack-1x" style="color: limegreen"></i></span> Installed.');
      installstatustooltip.html('<i class="fa fa-shield" aria-hidden="true" style="color: limegreen"></i> Your browsing session is protected.');
    }
  })();
});

function onPageLoad() {
  //var myFrame = document.getElementById('registerFrame');
  //myFrame.src = "registerdevice.php";
}

</script>
<?php } ?>

<title>Home@Kurnai</title>
</head>
<body onload="onPageLoad()">
<!-- Tag used for SSL cert detection. -->
<div id="check"></div>

<!-- Rest of site -->
<div class="content">
  <div id="container">
    <a href="https://www.kurnai.co" target="_blank"><img src="images/<?php if ($isFLO) { echo "flologo_wide.svg?v2"; } else { echo "kurnailogo_wide-2022-filled.svg?v2"; } ?>" class="logo"></a>
    <br />
    
    <?php if ($localaccess) { ?>
    <!-- Cyberhound header to show certificate install status. -->
    <div class="tooltip">
      <span class="tooltiptext" id="install-status-tooltip">Currently checking cyberhound certificate.</span>
      <a id="cyberhoundheader" class="cyberhoundheader">
        <img src="images/cyberhoundlogo.svg?v2" alt="Cyberhound" class="chlogo"> :
		<span id="install-status">
		  <span class="fa-stack fa-lg">
		    <i class="fa fa-square fa-lg" style="color: dimgray"></i>
			<i class="fa fa-spinner fa-stack-1x fa-spin" style="color: orange"></i>
		  </span> Checking...
		</span>
      </a>
    </div>
    <?php } else { ?>
	<div class="logospacer"></div>
	<?php }
    if (isset($_POST['url']) && !str_contains($_POST['url'], 'www.msftconnecttest.com') && !str_contains($_POST['url'], 'captive.apple.com')) { ?>
    <br />
    <a href="<?php echo $_POST['url'] ?>" target="_blank" class="urlforward">
      Click here to continue to &quot;<?php echo $_POST['url'] ?>&quot;
    </a>
    
    <?php } ?>
    
    <form action="https://www.google.com.au/search" class="searchform" method="get" name="searchform" target="_blank">
      <a href="https://www.google.com.au/" target="_blank"><img src="images/google.svg?v2" class="searchlogo" target="_blank"></a>
      <input autocomplete="on" class="form-control search" name="q" placeholder="Search Google (Australia)" required="required" type="text" <?php if ($deviceType != "mobile" && $deviceType != "tablet") {echo "autofocus";} ?>>
      <button class="button" type="submit">Search</button>
    </form>
    <br />
    <ul class="flex-container">
      <li class="flex-item">
        <figure>
          <a href="https://www.kurnai.co" target="_blank">
            <img src="images/kurnai-2022.svg?v2" alt="Kurnai College" class="tile">
            <figcaption>Kurnai&apos;s<br />Website</figcaption>
          </a>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <a href="https://kurnaicollege-vic.compass.education" target="_blank">
            <img src="images/compass.svg" alt="Compass" class="tile" style="background-color: rgb(18, 135, 250)">
            <figcaption>Compass</figcaption>
          </a>
        </figure>
      </li>
      
	  <?php if ($localaccess) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://myaccount.kurnaicollege.vic.edu.au<?php if (isset($_COOKIE['username'])) {echo '?user='.$_COOKIE['username']; } ?>" target="_blank">
            <img src="images/account.svg" alt="Account" class="tile" style="background-color: rgb(214, 138, 58)">
            <figcaption>Account<br />Management</figcaption>
          </a>
        </figure>
      </li>
	  <?php } ?>
      
      <?php if ($_COOKIE['staff']) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://outlook.office365.com/owa/education.vic.gov.au<?php if(isset($_COOKIE["firstName"])){echo "?login_hint=".strtolower($_COOKIE["firstName"]).".".strtolower($_COOKIE["lastName"])."@education.vic.gov.au";}?>" target="_blank">
            <img src="images/eduMail.svg" alt="eduMail" class="tile" style="background-color: rgb(32, 22, 72)">
            <figcaption>Department<br />Email</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <li class="flex-item">
        <figure>
          <a href="http://outlook.com/kurnaicollege.vic.edu.au<?php if(isset($_COOKIE["username"])){echo "?login_hint=".$_COOKIE["username"]."@kurnaicollege.vic.edu.au";}?>" target="_blank"><!-- ?login_hint=%username%@kurnaicollege.vic.edu.au -->
            <img src="images/outlook.svg" alt="Outlook" class="tile">
            <figcaption>School<br />Email</figcaption>
          </a>
        </figure>
      </li>
      
      <?php if ($_COOKIE['staff']) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://kurnai.on.spiceworks.com/portal" target="_blank">
            <img src="images/spiceworks.svg" alt="Helpdesk" class="tile" style="background-color: #FF7F32">
            <figcaption>Kurnai<br />Helpdesk</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <?php if ($campus["Name"] == "FLO" || $campus["Name"] == "Morwell" || $_COOKIE['staff'] || $_COOKIE['morwellStudent'] || $_COOKIE['floStudent'] || !$localaccess) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://kurnai-morwell.functionalsolutions.com.au/" target="_blank">
            <img src="images/library.svg" alt="Morwell Library" class="tile" style="background-color: #19892b">
            <figcaption>Morwell<br />Library</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <?php if ($campus["Name"] == "Churchill" || $_COOKIE['staff'] || $_COOKIE['churchillStudent'] || !$localaccess) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://kurnai-churchill.functionalsolutions.com.au/" target="_blank">
            <img src="images/library.svg" alt="Churchill Library" class="tile" style="background-color: #19892b">
            <figcaption>Churchill<br />Library</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <?php if ($campus["Name"] == "Uni" || $_COOKIE['staff'] || $_COOKIE['uniStudent'] || !$localaccess) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://kurnai-university.functionalsolutions.com.au/" target="_blank">
            <img src="images/library.svg" alt="University Library" class="tile" style="background-color: #19892b">
            <figcaption>University<br />Library</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <?php if ($model == "iPad") { ?>
      <li class="flex-item">
        <figure>
          <a href="https://enroll.mosyle.com/J0IUZ" target="_blank">
            <img src="images/ipad.svg" alt="Enrol iPad" class="tile" style="background-color: #2c2c2c">
            <figcaption>Enrol iPad</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
       <?php if (strtolower($osName) == "chrome os") { ?>
	   <!--
      <li class="flex-item">
        <figure>
          <a href="https://www.google.com/a/kurnaicollege.vic.edu.au" target="_blank">
            <img src="images/gsuite.svg" alt="Kurnai G Suite" class="tile">
            <figcaption>G-Suite</figcaption>
          </a>
        </figure>
      </li>
	  -->
      <?php } ?>

      <li class="flex-item">
        <figure>
          <div class="tooltip">
            <span class="tooltiptext">&bull; Access<br />&bull; Excel<br />&bull; Outlook<br />&bull; PowerPoint<br />&bull; Publisher<br />&bull; Word</span>
            <a href="<?php echo $O365Link; ?>" target="<?php if (substr($O365Link, -4) == ".exe" || substr($O365Link, -4) == ".pkg") { echo "_self"; } else { echo "_blank"; } ?>">
              <img src="images/office.svg" alt="Office 365" class="tile">
              <figcaption>Download<br />Office 365</figcaption>
            </a>
          </div>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <a href="https://www.microsoft.com/en-au/microsoft-365/microsoft-teams/download-app" target="_blank">
            <img src="images/Teams.svg" alt="Teams" class="tile">
            <figcaption>Download<br>Teams</figcaption>
          </a>
        </figure>
      </li>
      
	  <?php if (strtolower($osName) == "windows" || 
		strtolower($osName) == "macos" ||
		strtolower($osName) == "mac os x" ||
		strtolower($osName) == "os x") {?>
      <li class="flex-item">
        <figure>
		  <?php if (strtolower($osName) == "windows") { ?>
          <a href="https://software.kurnaicollege.vic.edu.au/files/AdobeCC_BYOD-5.6.0.exe" target="_blank">
		  <?php } elseif (strtolower($osName) == "macos" || strtolower($osName) == "mac os x" || strtolower($osName) == "os x") { ?>
		  <a href="macos-choice.php" target="_blank">
		  <?php } ?>
            <img src="images/AdobeCC.svg" alt="Adobe CC Logo" class="tile" style="background-color: red; background:conic-gradient(from 45deg at 45% 55%,#fb4400,#fcbd02,#51df4d,#8fc48e,#2f8bff,#b749fc,#fe0d8a,#f90101,#fb4400);">
            <figcaption>Download<br>Adobe CC</figcaption>
          </a>
        </figure>
      </li>
	  <?php } ?>
      
      <li class="flex-item">
        <figure>
          <div class="tooltip">
          <span class="tooltiptext">&bull; Minecraft<br />&bull; Adobe CC Suite<br />&bull; SketchUp Pro<br />&bull; etc.</span>
            <a href="https://www.edustar.vic.edu.au/software" target="_blank">
              <img src="images/eduSTAR.svg" alt="Software" class="tile" style="background-color: rgb(19, 19, 19)">
              <figcaption>eduSTAR<br />Software</figcaption>
            </a>
          </div>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <div class="tooltip">
          <span class="tooltiptext">&bull; Visual Studio<br />&bull; Visio Pro<br />&bull; Project Pro<br />&bull; Windows 10<br />&bull; Windows Server</span>
            <a href="https://aka.ms/devtoolsforteaching" target="_blank">
              <img src="images/visualstudio.svg" alt="Visual Studio" class="tile" style="background-color: rgb(57, 59, 65)">
              <figcaption>Azure<br />DevTools</figcaption>
            </a>
          </div>
        </figure>
      </li>
      
      <?php # Show "Install Printers" if the visitor is accessing the site locally, but isn't at the FLO.
      if ($localaccess && !$isFLO) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://print.kurnaicollege.vic.edu.au" target="_blank">
            <img src="images/papercut.svg" alt="Printers" class="tile" style="background-color: #19892b">
            <figcaption>Install<br />Printers</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <li class="flex-item">
        <figure>
          <a href="https://saml-in3.clickview.com.au/Shibboleth.sso/KURN624" target="_blank">
            <img src="images/clickview.svg" alt="Clickview" class="tile" style="background-color: rgb(248, 152, 29)">
            <figcaption>Clickview<br />Online</figcaption>
          </a>
        </figure>
      </li>
      
      <?php if (!$isFLO) { ?>
      <li class="flex-item">
        <figure>
          <a href="https://portal.kurnaicollege.vic.edu.au" target="_blank">
            <img src="images/hap.svg" alt="Kurnai Portal" class="tile" style="background-color: rgb(0, 96, 166)">
            <figcaption>Kurnai<br />Portal</figcaption>
          </a>
        </figure>
      </li>
      <?php } ?>
      
      <li class="flex-item">
        <figure>
          <a href="http://oars.acer.edu.au/kurnai-college" target="_blank">
            <img src="images/acer.svg" alt="ACER Testing" class="tile" style="background-color: #663366">
            <figcaption>ACER<br />Testing</figcaption>
          </a>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <a href="https://onguardv3.com.au/?schoolKey=kurnai.vic" target="_blank">
            <img src="images/onguard.svg" alt="OnGuard Training" class="tile" style="background-color: black">
            <figcaption>OnGuard<br />Safety</figcaption>
          </a>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <a href="https://kurnaicollegecareers.com/" target="_blank">
            <img src="images/kurnaicareers.svg?v3" alt="Careers Portal" class="tile">
            <figcaption>Kurnai<br />Careers</figcaption>
          </a>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <a href="https://sso.educationperfect.com/sso/start?schoolid=7197" target="_blank">
            <img src="images/ep-logo.svg?v2" alt="Education Perfect Login" class="tile" style="background-color: #1a2753">
            <figcaption>Education<br>Perfect</figcaption>
          </a>
        </figure>
      </li>

      <li class="flex-item">
        <figure>
          <a href="https://www.stymie.com.au/" target="_blank">
            <img src="images/stymie-logo.svg" alt="stymie" class="tile">
            <figcaption>Stymie<br>"Say something"</figcaption>
          </a>
        </figure>
      </li>
      
      <li class="flex-item">
        <figure>
          <a href="https://www.orima.com.au/AtoSS" target="_blank">
            <img src="images/atss.svg" alt="Orima Research Logo" class="tile" style="background-color: #008dd6">
            <figcaption>Attitude to<br>Schools<br>Survey</figcaption>
          </a>
        </figure>
      </li>

  <?php if ($_COOKIE["naplan"]) { ?>
<!-- Naplan BYOD code (currently Morwell yr 7&9 ONLY) -->
          <li class="flex-item">
        <figure>
            <a href="<?php echo $napLINK; ?>" target="<?php if (substr($napLINK, -4) == ".msi" || substr($napLINK, -4) == ".pkg") { echo "_self"; } else { echo "_blank"; } ?>">
            <img src="images/Lockdown-Browser.svg" alt="NAP" class="tile">
            <figcaption>NAPLAN Locked<br />Download Browser</figcaption>
          </a>
        </figure>
      </li>

      <figure>
		<a href="https://administration.assessform.edu.au/trt/device/index" target="_blank">
            <img src="images/NAP-Check.svg" alt="NAP" class="tile">
            <figcaption>NAPLAN<br />Device Check</figcaption>
          </a>
        </figure>
      </li>
<div id="note">
    Have you installed the NAPLAN Locked-Down Browser yet?? Use your own device for the test, download it now...
            <li class="flex-item">
        <figure>
            <a href="<?php echo $napLINK; ?>" target="<?php if (substr($napLINK, -4) == ".msi" || substr($napLINK, -4) == ".pkg") { echo "_self"; } else { echo "_blank"; } ?>">
            <img src="images/Lockdown-Browser.svg" alt="NAP" class="tile">
            <figcaption>NAPLAN Locked<br />Download Browser</figcaption>
          </a>
        </figure>
      </li>
     <a id="close">[close]</a>
</div>
<script>
 close = document.getElementById("close");
 close.addEventListener('click', function() {
   note = document.getElementById("note");
   note.style.display = 'none';
 }, false);
</script>
<!-- End Naplan BYOD Code -->
     <?php } ?>

    </ul>
    <div id="copyright">
      Copyright &copy; 2019-2022 Kurnai College
    </div>
  </div>
</div>

<?php if ($localaccess) { ?>
<!-- Hidden iframe to register device's anonymous data in the BYOD register.
<iframe id="registerFrame" src="" class="hidden"></iframe> -->

<!-- Hidden iframe to register device's certificate check status.
<iframe src="http://cert.localnetwork.zone/" class="hidden"></iframe> -->

<?php } ?>
</body>
</html>