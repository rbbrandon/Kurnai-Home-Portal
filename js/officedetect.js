var OSName = "Unknown";
var O365Link = "";
if (window.navigator.userAgent.toLowerCase().indexOf("mac")       != -1) OSName="Mac OS X";
if (window.navigator.userAgent.toLowerCase().indexOf("windows")   != -1) OSName="Windows";
if (window.navigator.userAgent.toLowerCase().indexOf("ipod")      != -1) OSName="iOS";
if (window.navigator.userAgent.toLowerCase().indexOf("iphone")    != -1) OSName="iOS";
if (window.navigator.userAgent.toLowerCase().indexOf("ipad")      != -1) OSName="iOS";
if (window.navigator.userAgent.toLowerCase().indexOf("android")   != -1) OSName="Android";

switch (OSName) {
	case "Windows":
		O365Link = "http://software.kurnaicollege.vic.edu.au/files/Office365Install-Kurnai.exe";
		break;
	case "iOS":
		O365Link = "https://itunes.apple.com/au/developer/microsoft-corporation/id298856275";
		break;
	case "Android":
		O365Link = "https://play.google.com/store/apps/dev?id=6720847872553662727&hl=en";
		break;
	case "Mac OS X":
		O365Link = "http://software.kurnaicollege.vic.edu.au/files/Microsoft_Office_2016_Installer.pkg";
		break;
	default:
		O365Link = "https://portal.office.com/OLS/MySoftware.aspx"; 
		break;
}