<?php
define('LDAP_SERVER', 'M-Server1.kurnai.lan');
define('LDAP_SEARCH_ROOT', 'OU=Kurnai,DC=kurnai,DC=lan');
define('LDAP_USERNAME', 'user');
define('LDAP_PASSWORD', 'password');

function setUserCookie($name, $value) {
	$arr_cookie_options = array (
                'expires' => time() + (365 * 24 * 60 * 60),
                'path' => '/',
                'domain' => '.kurnaicollege.vic.edu.au', // leading dot for compatibility or use subdomain
                'secure' => true,     // or false
                'httponly' => false,    // or false
                'samesite' => 'Strict' // None || Lax  || Strict
                );
				
	setcookie($name, $value, $arr_cookie_options);
	$_COOKIE[$name] = $value;
}

$staffGroups = array(
	'CN=GG_KStaff,OU=Kurnai,DC=kurnai,DC=lan',
	'CN=GG_CStaff,OU=Office365,OU=Groups,OU=Churchill,OU=Kurnai,DC=kurnai,DC=lan',
	'CN=GG_GStaff,OU=Office365,OU=Groups,OU=GEP,OU=Kurnai,DC=kurnai,DC=lan',
	'CN=GG_LStaff,OU=Office365,OU=Groups,OU=LV FLO,OU=Kurnai,DC=kurnai,DC=lan',
	'CN=GG_MStaff,OU=Office365,OU=Groups,OU=Morwell,OU=Kurnai,DC=kurnai,DC=lan'
);
$byodNaplanGroups = array(
	'CN=GG_MYear07,OU=Office365,OU=Groups,OU=Morwell,OU=Kurnai,DC=kurnai,DC=lan',
	'CN=GG_MYear09,OU=Office365,OU=Groups,OU=Morwell,OU=Kurnai,DC=kurnai,DC=lan'
);
$year10StudentGroups = array(
	'CN=GG_MYear10,OU=Office365,OU=Groups,OU=Morwell,OU=Kurnai,DC=kurnai,DC=lan',
	'CN=GG_CYear10,OU=Office365,OU=Groups,OU=Churchill,OU=Kurnai,DC=kurnai,DC=lan'
);
$morwellStudentGroup   = 'CN=GG_MAllStudents,OU=Office365,OU=Groups,OU=Morwell,OU=Kurnai,DC=kurnai,DC=lan';
$churchillStudentGroup = 'CN=GG_CAllStudents,OU=Office365,OU=Groups,OU=Churchill,OU=Kurnai,DC=kurnai,DC=lan';
$uniStudentGroup       = 'CN=GG_GStudentGEP,OU=Office365,OU=Groups,OU=GEP,OU=Kurnai,DC=kurnai,DC=lan';
$floStudentGroup       = 'CN=GG_LStudents,OU=Office365,OU=Groups,OU=LV FLO,OU=Kurnai,DC=kurnai,DC=lan';

if (isset($_POST['username'])) {
  setUserCookie('username', $_POST['username']);
  $ldapconn = ldap_connect('ldaps://'.LDAP_SERVER.'/') or die("Could not connect to LDAP server.");

  if ($ldapconn) {
    $ldapbind = ldap_bind($ldapconn, LDAP_USERNAME, LDAP_PASSWORD);
    
    if ($ldapbind) {
		
      $dn     = LDAP_SEARCH_ROOT;
      $filter = '(samAccountName='.$_POST['username'].')';
      $attr   = array('displayname', 'givenname', 'sn', 'dn', 'memberof');
      $sr     = ldap_search($ldapconn, $dn, $filter, $attr);
      $info   = ldap_get_entries($ldapconn, $sr);
	  
	  setUserCookie('displayName', $info[0]['displayname'][0]);
	  setUserCookie('firstName', $info[0]['givenname'][0]);
	  setUserCookie('lastName', $info[0]['sn'][0]);
      
	  foreach ($info[0]['memberof'] as $group) {
		  if (in_array($group, $staffGroups))
			setUserCookie('staff', 1);
		  
		  if (in_array($group, $byodNaplanGroups))
			setUserCookie('naplan', 1);
		  
		  if (in_array($group, $year10StudentGroups) || $group == $uniStudentGroup)
			setUserCookie('uniStudent', 1);
		  
		  if ($group == $morwellStudentGroup)
			setUserCookie('morwellStudent', 1);
		  
		  if ($group == $churchillStudentGroup)
			setUserCookie('churchillStudent', 1);
		  
		  if ($group == $floStudentGroup)
			setUserCookie('floStudent', 1);
	  }
    }
    
    ldap_close($ldapconn);
  }
}
?>