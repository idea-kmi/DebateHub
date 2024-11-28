<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2024 The Open University UK                            *
 *                                                                              *
 *  This software is freely distributed in accordance with                      *
 *  the GNU Lesser General Public (LGPL) license, version 3 or later            *
 *  as published by the Free Software Foundation.                               *
 *  For details see LGPL: http://www.fsf.org/licensing/licenses/lgpl.html       *
 *               and GPL: http://www.fsf.org/licensing/licenses/gpl-3.0.html    *
 *                                                                              *
 *  This software is provided by the copyright holders and contributors "as is" *
 *  and any express or implied warranties, including, but not limited to, the   *
 *  implied warranties of merchantability and fitness for a particular purpose  *
 *  are disclaimed. In no event shall the copyright owner or contributors be    *
 *  liable for any direct, indirect, incidental, special, exemplary, or         *
 *  consequential damages (including, but not limited to, procurement of        *
 *  substitute goods or services; loss of use, data, or profits; or business    *
 *  interruption) however caused and on any theory of liability, whether in     *
 *  contract, strict liability, or tort (including negligence or otherwise)     *
 *  arising in any way out of the use of this software, even if advised of the  *
 *  possibility of such damage.                                                 *
 *                                                                              *
 ********************************************************************************/
    include_once("config.php");
    include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
?>

<script>

function init() {

	const date = new Date();
	const datefield = document.getElementById("date");
	datefield.innerHTML = "";
	datefield.appendChild(date);

	const time = date.getTime();
	const timefield = document.getElementById("time");
	timefield.innerHTML = "";
	timefield.appendChild(time);

	const offset = date.getTimezoneOffset();
	const offsetfield = document.getElementById("offset");
	offsetfield.innerHTML = "";
	offsetfield.appendChild(offset);

	const newtime = parseInt((time + (offset*60000))/1000);
	const newtimefield = document.getElementById("newtime");
	newtimefield.innerHTML = parseInt(newtime);

	const localutc = date.toUTCString();
	const localutcfield = document.getElementById("localutc");
	localutcfield.innerHTML = "";
	localutcfield.appendChild(localutc);

	const final = new Date(date.toUTCString());
	const finaltime = final.getTime()/1000;
	const finalfield = document.getElementById("final");
	finalfield.innerHTML = "";
	finalfield.appendChild(finaltime);

	var newDate = new Date();
	newDate.setTime(finaltime*1000)
	const reversefield = document.getElementById("reverse");
	reversefield.innerHTML = "";
	reversefield.appendChild(newDate);
}

window.onload = init;

</script>

<div style="clear:both;"></div>
<div>
<p id="date"></p>
<p id="time"></p>
<p id="offset"></p>
<p id="newtime"></p>
<p id="localutc"></p>
<p id="final"></p>
<p id="reverse"></p>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>