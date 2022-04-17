<html>
<head>
<style>
body {
  background-image: url('images/MacOS-Monterey-BG.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
  text-align: center;
  overflow: hidden;
  /*text-shadow:
    -1px -1px 0 #aaa,  
     1px -1px 0 #aaa,
    -1px  1px 0 #aaa,
     1px  1px 0 #aaa;*/
}
table {
  margin-left: auto;
  margin-right: auto;
  background: rgba(255, 255, 255, .5);
  border: 1px solid black;
  table-layout: fixed;
  border-radius: 2em;
}
.wrapper {
  width: 100vw;
  height: 100vh;
  display: table-cell;
  vertical-align: middle;
}
td {
	vertical-align: middle;
	text-align: center;
}
h1 {
	margin: 1em;
	text-align: center;
}
figure {
  display: inline-block;
  margin: 20px; /* adjust as needed */
  position: relative;
  top: 0;
  left: 0;
  transition: top ease 0.5s;
}
figure .imgtile {
  background-color: black;
  width: 10em;
  height: 10em;
  display: block;
  vertical-align: middle;
  padding: 1em;
  margin: .5em;
}
figure .imgtileglowing {
  background-color: black;
  width: 10em;
  height: 10em;
  display: block;
  vertical-align: middle;
  position: relative;
  left: .5em;
  top: .5em;
  margin: 0;
}
figure .imgtileborder {
  /*background:conic-gradient(from 45deg at 45% 55%,#fb4400,#fcbd02,#51df4d,#8fc48e,#2f8bff,#b749fc,#fe0d8a,#f90101,#fb4400);*/
  background:conic-gradient(from 45deg,dodgerblue, blueviolet, magenta, tomato, dodgerblue);
  width: 10em;
  height: 10em;
  padding: 1.5em;
  filter: blur(5px);
}
figure img {
  max-width: 10em;
  max-height: 10em;
}
figure figcaption {
  text-align: center;
  font-size: 1.6em;
}
a:link, a:visited {
  color: #222;
  text-decoration: none;
}
figure:hover {
  top: -.5em;
}
figure:active {
  left: -.2em;
}
.stack {
    display: grid;
}
.stack > * {
    grid-row: 1;
    grid-column: 1;
}
</style>
</head>

<body>
<div class="wrapper">
<table>
<tr>
<td colspan="3"><h1>Select the model of Apple Mac that this download is for:</h1></td>
</tr>
<tr>
<td style="width: 33%">
  <figure>
	<a href="https://software.kurnaicollege.vic.edu.au/files/AdobeCC-BYOD-5.6.0_MAC.zip" target="_blank" onclick="setTimeout(function() { window.open(window.location, '_self').close(); }, 3000);">
      <div class="imgtile">
        <img src="images/apple.svg" />
	  </div>
	  <figcaption>Intel Mac</figcaption>
	</a>
  </figure>
</td>
<td style="width: 33%">
  <figure>
    <a href="https://software.kurnaicollege.vic.edu.au/files/AdobeCC-BYOD-5.6.0_MACARM.zip" target="_blank" onclick="setTimeout(function() { window.open(window.location, '_self').close(); }, 3000);">
      <div class="stack">
        <div class="imgtileborder"></div>
        <div class="imgtile imgtileglowing">
          <img src="images/apple-m1.svg" />
	    </div>
	  </div>
	  <figcaption>M1 Mac</figcaption>
	</a>
  </figure>
</td>
<td style="width: 33%">
  <figure>
    <a href="https://apps.apple.com/app-bundle/id1549982153" target="_blank" onclick="setTimeout(function() { window.open(window.location, '_self').close(); }, 3000);">
      <div class="imgtile">
        <img src="images/ipad-pro.svg" />
	  </div>
	  <figcaption>iPad Pro</figcaption>
	</a>
  </figure>
</td>
</tr>
</table>
</div>
</body>
</html>