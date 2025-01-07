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
	header('Content-Type: text/javascript;');
	include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

	echo "var NODE_CONTEXT = '".$CFG->NODE_CONTEXT."';";
	echo "var USER_CONTEXT = '".$CFG->USER_CONTEXT."';";
	echo "var GLOBAL_CONTEXT = '".$CFG->GLOBAL_CONTEXT."';";

    $evidenceArrayStr = "";
    $evidenceStr = "";
	$count = 0;
	if (is_countable($CFG->EVIDENCE_TYPES)) {
		$count = count($CFG->EVIDENCE_TYPES);
	}
    for($i=0;$i< $count; $i++){
        $evidenceArrayStr .= '"'.$CFG->EVIDENCE_TYPES[$i].'"';
        $evidenceStr .= $CFG->EVIDENCE_TYPES[$i];
        if ($i != ($count-1)){
            $evidenceArrayStr .= ',';
            $evidenceStr .= ',';
        }
    }
    echo "var EVIDENCE_TYPES = new Array(".$evidenceArrayStr.");";
	echo "var EVIDENCE_TYPES_STR = '".$evidenceStr."';";

    $baseArray = "";
    $baseStr = "";
	$count = 0;
	if (is_countable($CFG->BASE_TYPES)) {
		$count = count($CFG->BASE_TYPES);
	}
    for($j=0; $j<$count; $j++){
        $baseArray .= '"'.$CFG->BASE_TYPES[$j].'"';
        $baseStr .= $CFG->BASE_TYPES[$j];
        if ($j != ($count-1)) {
            $baseArray .= ',';
            $baseStr .= ',';
        }
    }
    echo "var BASE_TYPES = new Array(".$baseArray.");";
    echo "var BASE_TYPES_STR = '".$baseStr."';";

	// Colours for the applet node backgrounds
	echo "var challengebackpale = '".$CFG->challengebackpale."';";
	echo "var issuebackpale = '".$CFG->issuebackpale."';";
	echo "var solutionbackpale = '".$CFG->solutionbackpale."';";
	echo "var orgbackpale = '".$CFG->orgbackpale."';";
	echo "var projectbackpale = '".$CFG->projectbackpale."';";
	echo "var peoplebackpale = '".$CFG->peoplebackpale."';";
	echo "var probackpale = '".$CFG->probackpale."';";
	echo "var conbackpale = '".$CFG->conbackpale."';";
	echo "var evidencebackpale = '".$CFG->evidencebackpale."';";
	echo "var resourcebackpale = '".$CFG->resourcebackpale."';";
	echo "var themebackpale = '".$CFG->themebackpale."';";
	echo "var plainbackpale  = '".$CFG->plainbackpale."';";
?>

/**
 * Variables
 */
var URL_ROOT = "<?php print $CFG->homeAddress;?>";
var SERVICE_ROOT = URL_ROOT + "api/service.php?format=json";
var USER = "<?php print $USER->userid; ?>";
var IS_USER_ADMIN = "<?php print $USER->getIsAdmin(); ?>";
var DATE_FORMAT = 'd/m/yy';
var DATE_FORMAT_PROJECT = 'd mmm yyyy';
var TIME_FORMAT = 'd/m/yy - H:MM';
var DATE_FORMAT_PHASE = 'd mmm yyyy H:MM';
var SELECTED_LINKTYES = "";
var SELECTED_NODETYPES = "";
var SELECTED_USERS = "";
var ALERT_COUNT = 2;

var STATUS_SUSPENDED = <?php print $CFG->STATUS_SUSPENDED;?>; 
var STATUS_ARCHIVED = <?php print $CFG->STATUS_ARCHIVED;?>;

var USER_STATUS_SUSPENDED = <?php print $CFG->USER_STATUS_SUSPENDED;?>; 
var USER_STATUS_ARCHIVED = <?php print $CFG->USER_STATUS_ARCHIVED;?>;

var CLOSED_PHASE = 'closed';
var PENDING_PHASE = 'pending';

var DISCUSS_PHASE = 'discuss';
var REDUCE_PHASE = 'reduce';
var DECIDE_PHASE = 'decide';

var TIMED_PHASE = 'timed';
var TIMED_NOVOTE_PHASE = 'timednovote';
var TIMED_VOTEPENDING_PHASE = 'timedvotepending';
var TIMED_VOTEON_PHASE = 'timedvoteon';

var OPEN_PHASE = 'open'; // always voting
var OPEN_VOTEPENDING_PHASE = 'openvotepending';
var OPEN_VOTEON_PHASE = 'openvoteon';

var IE = 0;
var IE5 = 0;
var NS = 0;
var GECKO = 0;
var openpopups = new Array();

/** Store some variables about the browser being used.*/
if (document.all) {     // Internet Explorer Detected
	OS = navigator.platform;
	VER = new String(navigator.appVersion);
	VER = VER.substr(VER.indexOf("MSIE")+5, VER.indexOf(" "));
	if ((VER <= 5) && (OS == "Win32")) {
		IE5 = true;
	} else {
		IE = true;
	}
}
else if (document.layers) {   // Netscape Navigator Detected
	NS = true;
}
else if (document.getElementById) { // Netscape 6 Detected
	GECKO = true;
}

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}

/**
 * Check to see if the enter key was pressed then fire the onlcik of that item.
 */
function enterKeyPressed(evt) {
 	var event = evt || window.event;
	var thing = event.target || event.srcElement;

	var characterCode = document.all? window.event.keyCode:event.which;
	if(characterCode == 13) {
		thing.onclick();
	}
}


/**
 * Check to see if the enter key was pressed.
 */
function checkKeyPressed(evt) {
 	var event = evt || window.event;
	var thing = event.target || event.srcElement;

	var characterCode = document.all? window.event.keyCode:event.which;
	if(characterCode == 13) {
		return true;
	} else {
		return false;
	}
}

/**
 * get the anchor (#) value from the url
 */
function getAnchorVal(defVal){
    var url = document.location;
    var strippedUrl = url.toString().split("#");
    if(strippedUrl.length > 1 && strippedUrl[1] != ""){
        return strippedUrl[1];
    } else {
        return defVal;
    }
}

/**
 * Update the address parameters for a change in state.
 */
function updateAddressParameters(args) {
	if (typeof window.history.replaceState == 'function') {
		var newUrl = createNewURL(window.location.href, args);
		window.history.replaceState("string", "Title", newUrl);
	}
}

/**
 * create a new url based on the current one but with new arguments.
 */
function createNewURL(url, args, view){
	var newURL = "";

	// Strip empty parameters to declutter query string
	var newargs = {};
	for(var index in args) {
		var value = args[index];
		// check for an empty value or the title parameter - which does not need displaying in address
		if (value && value != "" && index != "title") {
			newargs[index] = value;
		}
	}

	// check for ? otherwise split on #
    var strippedUrl = url.toString().split("?");
    if (strippedUrl.length > 1) {
    	newURL = strippedUrl[0];
    } else {
    	newURL = (url.toString().split("#"))[0];
    }

	// if the view is not passed, reappend the original hash
	// we are just chaning the parameters
	if (view === undefined) {
		var bits = url.toString().split("#");
		if (bits.length > 1) {
			view = bits[1];
		}
	}

    newURL += "?"+Object.toQueryString(newargs);
    newURL += "#"+view;
    return newURL;
}

/**
 * Open a page in the dialog window
 */
function loadDialog(windowName, url, width, height){

    if (width == null){
        width = 570;
    }
    if (height == null){
        height = 510;
    }

    var left = parseInt((screen.availWidth/2) - (width/2));
    var top  = parseInt((screen.availHeight/2) - (height/2));
    var props = "width="+width+",height="+height+",left="+left+",top="+top+",menubar=no,toolbar=no,scrollbars=yes,location=no,status=no,resizable=yes";

    //var props = "width="+width+",height="+height+",left="+left+",top="+top+",menubar=no,toolbar=no,scrollbars=yes,location=no,status=yes,resizable=yes";
	var newWin = "";
    try {
    	newWin = window.open(url, windowName, props);
    	if(newWin == null){
    		alert("<?php echo $LNG->POPUPS_BLOCK; ?>");
    	} else {
    		newWin.focus();
    	}
    } catch(err) {
    	//IE error
    	alert(err.description);
    }

    return newWin;
}

/**
 * When closing a child window, reload the page or change the page as required.
 */
function closeDialog(gotopage){

	if(gotopage === undefined){
		gotopage="issue-list";
	}

	// try to refresh the parent page
	try {
		if (gotopage == "current") {
			window.opener.location.reload(true);
		} else if (gotopage == "conn-neighbour" || gotopage == "conn-net") {
			window.opener.location.reload(true);
		} else {
			var wohl = window.opener.location.href;
			if (wohl)
				var newurl = URL_ROOT + "user.php#" + gotopage;

			if(wohl == newurl){
				window.opener.location.reload(true);
			} else {
				window.opener.location.href = newurl;
			}
		}
	} catch(err) {
		//do nothing
	}

    window.close();
}

/**
 * Set display to 'block' for the item with the given pid
 */
function showPopup(pid){
    $(pid).setStyle({'display':'block'});
}

/**
 * Set display to 'none' for the item with the given pid
 */
function hidePopup(pid){
    $(pid).setStyle({'display':'none'});
}

/**
 * Toggle the given div between display 'block' and 'none'
 */
function toggleDiv(div) {
	var div = document.getElementById(div);
	if (div.style.display == "none") {
		div.style.display = "block";
	} else {
		div.style.display = "none";
	}
}

function toggleArrowDiv(div, arrow) {
	if ( $(div).style.display == "block") {
		$(div).style.display = "none";
		$(arrow).src='<?php echo $HUB_FLM->getImagePath("arrow-down-blue.png"); ?>';
	} else {
		$(div).style.display = "block";
		$(arrow).src='<?php echo $HUB_FLM->getImagePath("arrow-up-blue.png"); ?>';
	}
}

/**
 * Return the height of the current browser page.
 * Defaults to 500.
 */
function getWindowHeight(){
  	var viewportHeight = 500;
	if (self.innerHeight) {
		// all except Explorer
		viewportHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
	 	// Explorer 6 Strict Mode
		viewportHeight = document.documentElement.clientHeight;
	} else if (document.body)  {
		// other Explorers
		viewportHeight = document.body.clientHeight;
	}
	return viewportHeight;
}

/**
 * Return the width of the current browser page.
 * Defaults to 500.
 */
function getWindowWidth(){
  	var viewportWidth = 500;
	if (self.innerHeight) {
		// all except Explorer
		viewportWidth = self.innerWidth;
	} else if (document.documentElement && document.documentElement.clientHeight) {
	 	// Explorer 6 Strict Mode
		viewportWidth = document.documentElement.clientWidth;
	} else if (document.body)  {
		// other Explorers
		viewportWidth = document.body.clientWidth;
	}
	return viewportWidth;
}

function getPageOffsetX() {
	var x = 0;

    if (typeof(window.pageXOffset) == 'number') {
		x = window.pageXOffset;
	} else {
        if (document.body && document.body.scrollLeft) {
			x = document.body.scrollLeft;
        } else if (document.documentElement && document.documentElement.scrollLeft) {
			x = document.documentElement.scrollLeft;
		}
	}

	return x;
}

function getPageOffsetY() {
	var y = 0;

    if (typeof(window.pageYOffset) == 'number') {
		y = window.pageYOffset;
	} else {
        if (document.body && document.body.scrollTop) {
			y = document.body.scrollTop;
        } else if (document.documentElement && document.documentElement.scrollTop) {
			y = document.documentElement.scrollTop;
		}
	}

	return y;
}

/**
 * Return the position of the given element in an x/y array.
 */
function getPosition(element) {
	var xPosition = 0;
	var yPosition = 0;

	while(element && element != null) {

		xPosition += element.offsetLeft;
		xPosition -= element.scrollLeft;
		xPosition += element.clientLeft;

		yPosition += element.offsetTop;
		yPosition += element.clientTop;

		// Messes up menu positions in Chrome if this is included.
		// Works fine on all main browsers and Chrome if it is not.
		// yPosition -= element.scrollTop;

		//alert(element.id+" :"+"element.offsetTop: "+element.offsetTop+" element.scrollTop :"+element.scrollTop+" element.clientTop :"+element.clientTop);
		//alert(element.id+" :"+xPosition+":"+yPosition);

		// if the element is a table, get the parentElement as offsetParent is wrong
		if (element.nodeName == 'TABLE') {
			var prevelement = element;
			var nextelement = element.parentNode;
			//find a div with any scroll set.
			while(nextelement != prevelement.offsetParent) {
				yPosition -= nextelement.scrollTop;
				xPosition -= nextelement.scrollLeft;
				nextelement = nextelement.parentNode;
			}
		}

		element = element.offsetParent;
	}

	return { x: xPosition, y: yPosition };
}

/**
 * Display the index page hint for the given type.
 */
function showGlobalHint(type,evt,panelName) {

	$(panelName).style.width="400px";

 	var event = evt || window.event;

	$('globalMessage').innerHTML="";

	if (type == "MainSearch") {
		var text = '<?php echo addslashes($LNG->HEADER_SEARCH_INFO_HINT); ?>';
		$('globalMessage').insert(text);
	} else if (type == "StatsOverviewParticipation") {
		$("globalMessage").insert('<?php echo $LNG->STATS_OVERVIEW_HEALTH_PARTICIPATION_HINT; ?>');
	} else if (type == "StatsOverviewViewing") {
		$("globalMessage").insert('<?php echo $LNG->STATS_OVERVIEW_HEALTH_VIEWING_HINT; ?>');
	} else if (type == "StatsOverviewDebate") {
		$("globalMessage").insert('<?php echo $LNG->STATS_OVERVIEW_HEALTH_CONTRIBUTION_HINT; ?>');
	} else if (type == "StatsDebateContribution") {
		$("globalMessage").insert('<?php echo $LNG->STATS_DEBATE_CONTRIBUTION_HELP; ?>');
	} else if (type == "StatsDebateViewing") {
		$("globalMessage").insert('<?php echo $LNG->STATS_DEBATE_VIEWING_HELP; ?>');
	} else if (type == 'PendingMember') {
		$("globalMessage").insert('<?php echo $LNG->GROUP_JOIN_REQUEST_MESSAGE; ?>');
	}

	showHint(event, panelName, 10, -10);
}

function showHintText(evt, text) {
 	var event = evt || window.event;
	$('globalMessage').innerHTML="";
	$('globalMessage').insert(text);
	$('hgrhint').style.width="220px";
	showHint(event, 'hgrhint', 10, -10);
}

/**
 * Show a rollover hint popup div (when multiple lines needed).
 */
function showHint(evt, popupName, extraX, extraY) {
	hideHints();

 	var event = evt || window.event;
	var thing = event.target || event.srcElement;

	var viewportHeight = getWindowHeight();
	var viewportWidth = getWindowWidth();
	var panel = document.getElementById(popupName);

	if (GECKO) {

		//adjust for it going off the screen right or bottom.
		var x = event.clientX;
		var y = event.clientY;
		if ( (x+panel.offsetWidth+30) > viewportWidth) {
			x = x-(panel.offsetWidth+30);
		} else {
			x = x+10;
		}
		if ( (y+panel.offsetHeight) > viewportHeight) {
			y = y-50;
		} else {
			y = y-5;
		}

		if (panel) {
			panel.style.left = x+extraX+window.pageXOffset+"px";
			panel.style.top = y+extraY+window.pageYOffset+"px";
			panel.style.background = "#FFFED9";
			panel.style.visibility = "visible";
			openpopups.push(popupName);
		}
	}
	else if (NS) {
		//adjust for it going off the screen right or bottom.
		var x = event.pageX;
		var y = event.pageY;
		if ( (x+panel.offsetWidth+30) > viewportWidth) {
			x = x-(panel.offsetWidth+30);
		} else {
			x = x+10;
		}
		if ( (y+panel.offsetHeight) > viewportHeight) {
			y = y-50;
		} else {
			y = y-5;
		}
		document.layers[popupName].moveTo(x+extraX+window.pageXOffset+"px", y+extraY+window.pageYOffset+"px");
		document.layers[popupName].bgColor = "#FFFED9";
		document.layers[popupName].visibility = "show";
		openpopups.push(popupName);
	}
	else if (IE || IE5) {
		//adjust for it going off the screen right or bottom.
		var x = event.x;
		var y = event.clientY;
		if ( (x+panel.offsetWidth+30) > viewportWidth) {
			x = x-(panel.offsetWidth+30);
		} else {
			x = x+10;
		}
		if ( (y+panel.offsetHeight) > viewportHeight) {
			y = y-50;
		} else {
			y = y-5;
		}

		window.event.cancelBubble = true;
		document.all[popupName].style.left = x+extraX+ document.documentElement.scrollLeft+"px";
		document.all[popupName].style.top = y+extraY+ document.documentElement.scrollTop+"px";
		document.all[popupName].style.visibility = "visible";
		openpopups[openpopups.length] = popupName;
	}
	return false;
}

function hideHints() {
	var popupname;
	for (var i = 0; i < openpopups.length; i++) {
		popupname = new String (openpopups[i]);
		if (popupname) {
			var popup = document.getElementById(popupname);
			if (popup) {
				popup.style.visibility = "hidden";
			}
		}
	}
	openpopups = new Array();
	return;
}

var popupTimerHandleArray = new Array();
var popupArray = new Array();

function showBox(div) {
	hideBoxes();

    if (popupTimerHandleArray[div] != null) {
        clearTimeout(popupTimerHandleArray[div]);
        popupTimerHandleArray[div] = null;
    }

    var divObj = document.getElementById(div);
    divObj.style.display = 'block';
    popupArray.push(div);
}

function hideBox(div) {
    var popupTimerHandle = setTimeout("reallyHideBox('" + div + "');", 250);
    popupTimerHandleArray[div] = popupTimerHandle;
}

function reallyHideBox(div) {
    var divObj = document.getElementById(div);
    divObj.style.display = 'none';
}

function hideBoxes() {
	var popupname;
	for (var i = 0; i < popupArray.length; i++) {
		popupname = new String (popupArray[i]);
		var popup = document.getElementById(popupname);
		if (popup) {
			popup.style.display = "none";
		}
	}
	popupArray = new Array();
	return;
}

function radioEvidencePrompt(focalnodeid, filternodetypes, focalnodeend, handler, key, nodetofocusid, promptlabel, selectedOption, refresher) {

	$('prompttext').innerHTML="";
	$('prompttext').style.width = "380px";
	$('prompttext').style.height = "140px";

	var viewportHeight = getWindowHeight();
	var viewportWidth = getWindowWidth();
	var x = (viewportWidth-380)/2;
	var y = (viewportHeight-140)/2;

	$('prompttext').style.left = x+getPageOffsetX()+"px";
	$('prompttext').style.top = y+getPageOffsetY()+"px";

	var choicehidden = new Element('input', {'name':'radiopromptchoice','id':'radiopromptchoice','type':'hidden', 'value':'supports'});
	$('prompttext').insert(choicehidden);

	var labelobj = new Element('label', {'style':'padding-bottom:5px;font-weight:bold;font-size:12pt; color:black;'});
	labelobj.insert(promptlabel);
	$('prompttext').insert(labelobj);

	$('prompttext').insert("<br />");
	$('prompttext').insert("<br />");

	var radio = new Element('input', {'style':'vertical-align:bottom','type':'radio','name':'radioPrompt','value':'supports'});
	radio.checked = "checked";
	Event.observe(radio,'click', function() {
		if (this.checked) {
			$('radiopromptchoice').value = this.value;
		}
	});
	$('prompttext').insert(radio);
	$('prompttext').insert('<img border="0" alt="+" style="vertical-align:bottom" src="<?php echo $HUB_FLM->getImagePath("plus-16x16.png"); ?>" /><span style="color:black"> Supporting</span>');
	$('prompttext').insert("<br />");

	var radio2 = new Element('input', {'style':'vertical-align:bottom','type':'radio','name':'radioPrompt','value':'challenges'});
	Event.observe(radio2,'click', function() {
		if (this.checked) {
			$('radiopromptchoice').value = this.value;
		}
	});
	$('prompttext').insert(radio2);
	$('prompttext').insert('<img border="0" alt="-" style="vertical-align:bottom" src="<?php echo $HUB_FLM->getImagePath("minus-16x16.png"); ?>" /><span style="color:black"> Countering</span>');
	$('prompttext').insert("<br />");

	$('prompttext').insert("<br />");

    var buttonOK = new Element('input', { 'class':'btn btn-secondary text-dark fw-bold mx-3 mt-2 float-end', 'type':'button', 'value':'<?php echo $LNG->FORM_BUTTON_CONTINUE; ?>'});
	Event.observe(buttonOK,'click', function() {
		var valuechosen = $('radiopromptchoice').value;
		eval( refresher + '("'+focalnodeid+'","'+filternodetypes+'","'+focalnodeend+'","'+handler+'","'+key+'","'+nodetofocusid+'","'+valuechosen+'")' );
		textAreaCancel();
	});

    var buttonCancel = new Element('input', { 'class':'btn btn-secondary text-dark fw-bold mx-3 mt-2 float-end', 'type':'button', 'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>'});
	Event.observe(buttonCancel,'click', textAreaCancel);

	var buttonDiv = new Element('div', { 'class':'col-auto'});
	buttonDiv.insert(buttonOK);
	buttonDiv.insert(buttonCancel);

	$('prompttext').insert(buttonDiv);
	$('prompttext').style.display = "block";
}

function textAreaCancel() {
	$('prompttext').style.display = "none";
	$('prompttext').update("");
}

function textAreaPrompt(messageStr, text, connid, handler, refresher, width, height) {

	$('prompttext').innerHTML="";
	if (width == undefined) {
		width = 400;
	}
	if (height == undefined) {
		height = 250;
	}
	$('prompttext').style.width = width+"px";
	$('prompttext').style.height = height+"px";

	var viewportHeight = getWindowHeight();
	var viewportWidth = getWindowWidth();
	var x = (viewportWidth-width)/2;
	var y = (viewportHeight-height)/2;
	$('prompttext').style.left = x+getPageOffsetX()+"px";
	$('prompttext').style.top = y+getPageOffsetY()+"px";

	var textarea1 = new Element('textarea', {'id':'messagetextarea','rows':'5','class':'messagetextarea'});
	textarea1.value=text;

	if (connid != "") {
		var buttonOK = new Element('input', {  'class':'btn btn-secondary text-dark fw-bold mx-3 mt-2 float-end', 'type':'button', 'value':'<?php echo $LNG->FORM_BUTTON_PUBLISH; ?>'});
		Event.observe(buttonOK,'click', function() {
			eval( refresher + '("'+connid+'","'+textarea1.value+'","'+handler+'")' );
			textAreaCancel();
		});
	}

    var buttonCancel = new Element('input', {  'class':'btn btn-secondary text-dark fw-bold mx-3 mt-2 float-end', 'type':'button', 'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>'});
	Event.observe(buttonCancel,'click', textAreaCancel);

	var buttonDiv = new Element('div', { 'class':'col-auto'});
	if (connid != "") {
		$('buttonDiv').insert(buttonOK);
	}
	buttonDiv.insert(buttonCancel);

	$('prompttext').insert('<div class="fw-bold p-2" style="color: #4E725F">'+messageStr+'</div>');
	$('prompttext').insert(textarea1);
	$('prompttext').insert(buttonDiv);
	$('prompttext').style.display = "block";
}

function fadeMessage(messageStr) {
    var viewportHeight = getWindowHeight();
    var viewportWidth = getWindowWidth();
    var x = (viewportWidth - 300) / 2;
    var y = (viewportHeight - 200) / 2;

	$('message').update("");
	$('message').update(messageStr);
	
	$('message').style.top = y + 'px';
	$('message').style.display = "block";

	setTimeout(() => { $('message').style.opacity = 1; }, 10);
    setTimeout(() => { $('message').style.opacity = 0; }, 3500);
	setTimeout(() => { $('message').style.display = 'none'; }, 4500);
}

function fadein(){
	var element = document.getElementById("message");
	element.style.opacity = 0.0;
	fadeinloop();
}

function fadeinloop(){
	var element = document.getElementById("message");

	element.style.opacity += 0.1;
	if(element.style.opacity > 1.0) {
		element.style.opacity = 1.0;
	} else {
		setTimeout("fadeinloop()", 100);
	}
}

function fadeout(){
	var element = document.getElementById("message");
	element.style.opacity = 1.0;
	fadeoutloop();
}

function fadeoutloop(){
	var element = document.getElementById("message");

	element.style.opacity -= 0.1;
	if(element.style.opacity < 0.0) {
		element.style.opacity = 0.0;
	} else {
		setTimeout("fadeoutloop()", 100);
	}
}


function getLoading(infoText){
    var loadDiv = new Element("div",{'class':'loading'});
    loadDiv.insert("<img src='<?php echo $HUB_FLM->getImagePath('ajax-loader.gif'); ?>'/>");
    loadDiv.insert("<br/>"+infoText);
    return loadDiv;
}

function getLoadingLine(infoText){
    var loadDiv = new Element("div",{'class':'loading'});
    loadDiv.insert("<img src='<?php echo $HUB_FLM->getImagePath('ajax-loader.gif'); ?>' />");
    loadDiv.insert("&nbsp;"+infoText);
    return loadDiv;
}

function nl2br (dataStr) {
	return dataStr.replace(/(\r\n|\r|\n)/g, "<br />");
}

/**
 * http://www.456bereastreet.com/archive/201105/validate_url_syntax_with_javascript/
 * MB: I modified the original as I could not get it to work as it was.
 */
function isValidURI(uri) {
    if (!uri) uri = "";

	//SERVER SIDE URL VALIDATION
	//at some point the two should match!
	//'protocol' => '((http|https|ftp|mailto)://)',
	//'access' => '(([a-z0-9_]+):([a-z0-9-_]*)@)?',
	//'sub_domain' => '(([a-z0-9_-]+\.)*)',
	//'domain' => '(([a-z0-9-]{2,})\.)',
	//'tld' =>'([a-z0-9_]+)',
	//'port'=>'(:(\d+))?',
	//'path'=>'((/[a-z0-9-_.%~]*)*)?',
	//'query'=>'(\?[^? ]*)?'

   	var schemeRE = /^([-a-z0-9]|%[0-9a-f]{2})*$/i;

   	var authorityRE = /^([-a-z0-9.]|%[0-9a-f]{2})*$/i;

   	var pathRE = /^([-a-z0-9._~:@!$&'()*+,;=\//#]|%[0-9a-f]{2})*$/i;

    var qqRE = /^([-a-z0-9._~:@!$&'\[\]()*+,;=?\/]|%[0-9a-f]{2})*$/i;
    var qfRE = /^([-a-z0-9._~:@!$&#'\[\]()*+,;=?\/]|%[0-9a-f]{2})*$/i;

    var parser = /^(?:([^:\/?]+):)?(?:\/\/([^\/?]*))?([^?]*)(?:\?([^\#]*))?(?:(.*))?/;

    var result = uri.match(parser);

    var scheme    = result[1] || null;
    var authority = result[2] || null;
    var path      = result[3] || null;
    var query     = result[4] || null;
    var fragment  = result[5] || null;

    //alert("scheme="+scheme);
    //alert("authority="+authority);
    //alert("path="+path);
    //alert("query="+query);
    //alert("fragment="+fragment);

    if (!scheme || !scheme.match(schemeRE)) {
    	//alert('scheme failed');
        return false;
    }

    if (!authority || !authority.match(authorityRE)) {
    	//alert('authority failed');
        return false;
    }
    if (path != null && !path.match(pathRE)) {
    	//alert('path failed');
        return false;
    }
    if (query && !query.match(qqRE)) {
    	//alert('query failed');
        return false;
    }
    if (fragment && !fragment.match(qfRE)) {
    	//alert('fragment failed');
        return false;
    }

    return true;
}

/**
 * http://www.wohill.com/javascript-regular-expression-for-url-check/
 */
function urlCheck(str) {
	var v = new RegExp();
	v.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
	if (!v.test(str)) {
		return false;
	}
	return true;
}

/**
 * Display explore page in a popup (called by applets).
 */
function viewNodeDetails(nodeid, nodetype, width, height) {
	loadDialog('details', URL_ROOT+"explore.php?id="+nodeid, width,height);
}

/**
 * Called by the Applet to go to the home page of the given userid
 */
function viewUserHome(userid) {
	var width = getWindowWidth();
	var height = getWindowHeight()-20;

	loadDialog('userdetails', URL_ROOT+"user.php?userid="+userid, width,height);
}

/**
 * Add the given connection object to the given map.
 * @param c the connection to add (json of connection returned from server).
 * @param map the name of the map applet to add the data to
 */
function addConnectionToNetworkMap(c, map) {

	var fN = c.from[0].cnode;
	var tN = c.to[0].cnode;

	var fnRole = c.fromrole[0].role;
	var fNNodeImage = "";
	if (fN.imagethumbnail != null && fN.imagethumbnail != "") {
		fNNodeImage = URL_ROOT + fN.imagethumbnail;
	} else if (fN.role[0].role.image != null && fN.role[0].role.image != "") {
		fNNodeImage = URL_ROOT + fN.role[0].role.image;
	}

	var tnRole = c.torole[0].role;
	var tNNodeImage = "";
	if (tN.imagethumbnail != null && tN.imagethumbnail != "") {
		tNNodeImage = URL_ROOT + tN.imagethumbnail;
	} else if (tN.role[0].role.image != null && tN.role[0].role.image != "") {
		tNNodeImage = URL_ROOT + tN.role[0].role.image;
	}
	var fromRole = fN.role[0].role.name;
	var toRole = tN.role[0].role.name;

	var fromDesc = "";
	if (fN.description) {
		fromDesc = fN.description;
	}
	var toDesc = "";
	if (tN.description) {
		toDesc = tN.description;
	}
	var fromName = fN.name;
	var toName = tN.name;

	// Get HEX for From Role
	var fromHEX = "";
	if (fromRole == 'Challenge') {
		fromHEX = challengebackpale;
	} else if (fromRole == 'Issue') {
		fromHEX = issuebackpale;
	} else if (fromRole == 'Solution') {
		fromHEX = solutionbackpale;
	} else if (EVIDENCE_TYPES_STR.indexOf(fromRole) != -1) {
		fromHEX = evidencebackpale;
	} else {
		fromHEX = plainbackpale;
	}

	// Get HEX for To Role
	var toHEX = "";
	if (toRole == 'Challenge') {
		toHEX = challengebackpale;
	} else if (toRole == 'Issue') {
		toHEX = issuebackpale;
	} else if (toRole == 'Solution') {
		toHEX = solutionbackpale;
	} else if (EVIDENCE_TYPES_STR.indexOf(toRole) != -1) {
		toHEX = evidencebackpale;
	} else {
		toHEX = plainbackpale;
	}

	fromRole = getNodeTitleAntecedence(fromRole, false);
	toRole = getNodeTitleAntecedence(toRole, false);

	//create from & to nodes
	$(map).addNode(fN.nodeid, fromRole+": "+fromName, fromDesc, fN.users[0].user.userid, fN.creationdate, fN.otheruserconnections, fNNodeImage, fN.users[0].user.thumb, fN.users[0].user.name, fromRole, fromHEX);
	$(map).addNode(tN.nodeid, toRole+": "+toName, toDesc, tN.users[0].user.userid, tN.creationdate, tN.otheruserconnections, tNNodeImage, tN.users[0].user.thumb, tN.users[0].user.name, toRole, toHEX);

	// add edge/conn
	var fromRoleName = fromRole;
	if (c.fromrole[0].role) {
		fromRoleName = c.fromrole[0].role.name;
	}

	var toRoleName = toRole;
	if (c.torole[0].role) {
		toRoleName = c.torole[0].role.name;
	}

	var linklabelname = c.linktype[0].linktype.label;
	linklabelname = getLinkLabelName(fN.role[0].role.name, tN.role[0].role.name, linklabelname);

	$(map).addEdge(c.connid, fN.nodeid, tN.nodeid, c.linktype[0].linktype.grouplabel, linklabelname, c.creationdate, c.userid, c.users[0].user.name, fromRoleName, toRoleName);
}

/**
 * Get the language version of the link label that should be displayed to the users.
 * Allows for local varients and internationalization.
 */
function getLinkLabelName(fromNodeTypeName, toNodeTypeName, linkName) {

	if (fromNodeTypeName == 'Solution' && toNodeTypeName == 'Issue') {
		return '<?php echo $LNG->LINK_SOLUTION_ISSUE; ?>';
	} else if (EVIDENCE_TYPES.indexOf(fromNodeTypeName) != -1 &&
				(toNodeTypeName == 'Solution')) {
		if (linkName == '<?php echo $CFG->LINK_PRO_SOLUTION; ?>') {
			return '<?php echo $LNG->LINK_PRO_SOLUTION; ?>';
		} else if (linkName == '<?php echo $CFG->LINK_CON_SOLUTION; ?>') {
			return '<?php echo $LNG->LINK_CON_SOLUTION; ?>';
		}
	}

	return linkName;
}

/**
 * Return the node type text to be placed before the node title
 * @param nodetype the node type for node to return the text for
 * @param withColon true if you want a colon adding after the node type name, else false.
 */
function getNodeTitleAntecedence(nodetype, withColon) {
	if (withColon == undefined) {
		withColon = true;
	}

	var title=nodetype;

	if (nodetype == 'Issue') {
		title = "<?php echo $LNG->ISSUE_NAME; ?>";
	} else if (nodetype == 'Solution') {
		title = "<?php echo $LNG->SOLUTION_NAME; ?>";
	} else if (nodetype == 'Comment') {
		title = "<?php echo $LNG->COMMENT_NAME; ?>";
	} else if (nodetype == 'Pro') {
		title = "<?php echo $LNG->PRO_NAME; ?>";
	} else if (nodetype == 'Con') {
		title = "<?php echo $LNG->CON_NAME; ?>";
	} else if (nodetype == 'News') {
		title = "<?php echo $LNG->NEWS_NAME; ?>";
	}

	if (withColon) {
		title += ": ";
	}

	return title;
}

function gotoHomeList(type) {
	var reqUrl = '<?php print($CFG->homeAddress);?>index.php';
	window.location.href = reqUrl;
	if (CONTEXT == 'global') {
		window.location.reload(true);
	}
}

function alphanodesort(a, b) {
	var nameA=a.cnode.name.toLowerCase();
	var nameB=b.cnode.name.toLowerCase();
	if (nameA < nameB) {
		return -1;
	}
	if (nameA > nameB) {
		return 1;
	}
	return 0 ;
}

function alphanodesortfront(a, b) {
	var nameA=a.name.toLowerCase();
	var nameB=b.name.toLowerCase();
	if (nameA < nameB) {
		return -1;
	}
	if (nameA > nameB) {
		return 1;
	}
	return 0 ;
}

function creationdatenodesortasc(a, b) {
	var nameA=a.cnode.creationdate;
	var nameB=b.cnode.creationdate;
	if (nameA < nameB) {
		return -1;
	}
	if (nameA > nameB) {
		return 1;
	}
	return 0 ;
}

function creationdatenodesortdesc(a, b) {
	var nameA=a.cnode.creationdate;
	var nameB=b.cnode.creationdate;
	if (nameA > nameB) {
		return -1;
	}
	if (nameA < nameB) {
		return 1;
	}
	return 0 ;
}

function modedatenodesortasc(a, b) {
	var nameA=a.cnode.modificationdate;
	var nameB=b.cnode.modificationdate;
	if (nameA < nameB) {
		return -1;
	}
	if (nameA > nameB) {
		return 1;
	}
	return 0 ;
}

function modedatenodesortdesc(a, b) {
	var nameA=a.cnode.modificationdate;
	var nameB=b.cnode.modificationdate;
	if (nameA > nameB) {
		return -1;
	}
	if (nameA < nameB) {
		return 1;
	}
	return 0 ;
}

function removeHTMLTags(htmlString) {
	var cleanString = "";
	if(htmlString){
		var mydiv = document.createElement("div");
		mydiv.innerHTML = htmlString;
		if (document.all) {
			cleanString = mydiv.innerText;
		} else {
			cleanString = mydiv.textContent;
		}
  	}

  	return cleanString.trim();
}

/**
 * Used to switch a textarea between plain text and full HTML editor box.
 */
function switchCKEditorMode(link, divname, editorname) {
	if ($(divname).style.clear == 'none') {
		CKEDITOR.replace(editorname, {
			on : { instanceReady : function( ev ) { this.focus(); } }
		} );

		$(divname).style.clear = 'both';
		link.innerHTML = '<?php echo $LNG->FORM_DESC_PLAIN_TEXT_LINK; ?>'
		link.title = '<?php echo $LNG->FORM_DESC_PLAIN_TEXT_HINT; ?>';
	} else {
		var ans = confirm("<?php echo $LNG->FORM_DESC_HTML_SWITCH_WARNING; ?>");
		if (ans == true) {
			if (CKEDITOR.instances[editorname]) {
				CKEDITOR.instances[editorname].destroy();
			}
			$(divname).style.clear = 'none';
			link.innerHTML = '<?php echo $LNG->FORM_DESC_HTML_TEXT_LINK; ?>';
			link.title = '<?php echo $LNG->FORM_DESC_HTML_TEXT_HINT; ?>';
			$(editorname).value = removeHTMLTags($(editorname).value);
		}
	}
}

function htmlspecialchars_decode (string, quote_style) {
  // http://kevin.vanzonneveld.net
  // +   original by: Mirek Slugen
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Mateusz "loonquawl" Zalega
  // +      input by: ReverseSyntax
  // +      input by: Slawomir Kaniecki
  // +      input by: Scott Cariss
  // +      input by: Francois
  // +   bugfixed by: Onno Marsman
  // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Ratheous
  // +      input by: Mailfaker (http://www.weedem.fr/)
  // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
  // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
  // *     returns 1: '<p>this -> &quot;</p>'
  // *     example 2: htmlspecialchars_decode("&amp;quot;");
  // *     returns 2: '&quot;'
  var optTemp = 0,
    i = 0,
    noquotes = false;
  if (typeof quote_style === 'undefined') {
    quote_style = 2;
  }
  string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/&#0*39;/g, "'"); // PHP does not currently escape if more than one 0, but it should
    // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
  }
  if (!noquotes) {
    string = string.replace(/&quot;/g, '"');
  }
  // Put this in last place to avoid escape being double-decoded
  string = string.replace(/&amp;/g, '&');

  return string;
}

/**
 * Replace reserved chars with their XML entity equivalents
 *
 * @param string xmlStr
 * @return string
 */
function parseToXML(xmlStr) {

    xmlStr = xmlStr.replace(/&/g,'&amp;');
    xmlStr = xmlStr.replace(/</g,'&lt;');
    xmlStr = xmlStr.replace(/>/g,'&gt;');
    xmlStr = xmlStr.replace(/"/g,'&quot;');
    xmlStr = xmlStr.replace(/'/g,'&#39;');
    return xmlStr;
}

/**
 * Audit a testing action
 * @param itemid the id of any associated item being tested
 * @param testelementid the id of the test element being audited
 * @param testevent the event that triggered the audit
 * @param state and meta data wishing to be stored as part of the audit,
 * @param handler a function object to run once the auditing has returned (optional).
 * such as the state of the audited element.
 */
function auditTesting(itemid, testelementid, testevent, state, handler) {
	var args = {};
	args["trialname"] = '<?php echo $CFG->TEST_TRIAL_NAME; ?>';
    args['itemid'] = itemid;
    args['testelementid'] = testelementid;
    args['event'] = testevent;
    args['state'] = parseToXML(state);

	var reqUrl = SERVICE_ROOT + "&method=audittesting&" + Object.toQueryString(args);
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			} else {
				if (handler) {
					handler();
				}
				//alert("OK");
			}
		}
	});

	return;
}

function auditDashboardButton(itemid, name, link, levelname, elementid) {

	//alert(itemid+":"+name+":"+link+":"+levelname+":"+elementid);
	var handler = function() {
		 location.href=link;
	}

	var state = '<parent></parent>';
	state += '<meta1>'+name+'</meta1>';
	state += '<meta2>'+levelname+'</meta2>';
	state += '<meta3><![CDATA['+link+']]></meta3>';

	auditTesting(itemid, elementid, 'click', state, handler);
	return true;
}

/** FUNCTIONS FOR ALERTS **/
/*
// DATA BASED
$CFG->ALERT_LURKING_USER = 'lurking_user';
$CFG->ALERT_IGNORED_POST = 'ignored_post';
$CFG->ALERT_MATURE_ISSUE = 'mature_issue';
$CFG->ALERT_IMMATURE_ISSUE = 'immature_issue';
$CFG->ALERT_INACTIVE_USER = 'inactive_user';
$CFG->ALERT_HOT_POST = "hot_post";
$CFG->ALERT_ORPHANED_IDEA = "orphaned_idea";
$CFG->ALERT_EMERGING_WINNER = "emerging_winner";
$CFG->ALERT_CONTROVERSIAL_IDEA = "controversial_idea";

// not done yet
$CFG->ALERT_CONTENTIOUS_ISSUE = "contentious_issue";
$CFG->ALERT_WELL_EVALUATED_IDEA = "well_evaluated_idea";
$CFG->ALERT_POORLY_EVALUATED_IDEA = "poorly_evaluated_idea";
$CFG->ALERT_USER_GONE_INACTIVE = "user_gone_inactive";

// USER BASED - LOGGED IN
$CFG->ALERT_UNSEEN_BY_ME = "unseen_by_me";
$CFG->ALERT_RESPONSE_TO_ME = "response_to_me";
$CFG->ALERT_UNRATED_BY_ME = "unrated_by_me";
$CFG->ALERT_INTERESTING_TO_ME = "interesting_to_me";

// not done yet
$CFG->ALERT_INCONSISTENT_SUPPORT = "inconsistent_support";
$CFG->ALERT_PEOPLE_WITH_INTERESTS_LIKE_MINE = "people_with_interests_like_mine";
$CFG->ALERT_PEOPLE_WHO_AGREE_WITH_ME = "people_who_agree_with_me";
$CFG->ALERT_INTERESTING_TO_PEOPLE_LIKE_ME = "interesting_to_people_like_me";
$CFG->ALERT_SUPPORTED_BY_PEOPLE_LIKE_ME = "supported_by_people_like_me";
*/

function loadUserAlertsData(nodealertDiv, useralertDiv, alertmessagearea, issuenodeid) {
	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["issueid"] = issuenodeid;
	args["url"] = '<?php echo $CFG->homeAddress; ?>api/views/'+issuenodeid;
	args["timeout"] = 60;
	args["userids"] = USER;

	var alerts = "";
	alerts += '<?php echo $CFG->ALERT_UNSEEN_BY_ME; ?>';
	alerts += ',<?php echo $CFG->ALERT_RESPONSE_TO_ME; ?>';
	alerts += ',<?php echo $CFG->ALERT_UNRATED_BY_ME; ?>';
	alerts += ',<?php echo $CFG->ALERT_INTERESTING_TO_ME; ?>';
	alerts += ',<?php echo $CFG->ALERT_UNSEEN_RESPONSE; ?>';
	alerts += ',<?php echo $CFG->ALERT_UNSEEN_COMPETITOR; ?>';
	args["alerts"] = alerts;

	loadAlertsData(args, nodealertDiv, useralertDiv, alertmessagearea, issuenodeid);
}

function loadModeratorAlertsData(nodealertDiv, useralertDiv, alertmessagearea, issuenodeid) {
	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["issueid"] = issuenodeid;
	args["url"] = '<?php echo $CFG->homeAddress; ?>api/views/'+issuenodeid;
	args["timeout"] = 60;

	var alerts = "";
	alerts += '<?php echo $CFG->ALERT_LURKING_USER; ?>';
	alerts += ',<?php echo $CFG->ALERT_HOT_POST; ?>';
	alerts += '<?php echo $CFG->ALERT_IGNORED_POST; ?>';
	alerts += ',<?php echo $CFG->ALERT_MATURE_ISSUE; ?>';
	alerts += ',<?php echo $CFG->ALERT_IMMATURE_ISSUE; ?>';
	alerts += ',<?php echo $CFG->ALERT_ORPHANED_IDEA; ?>';
	alerts += ',<?php echo $CFG->ALERT_EMERGING_WINNER; ?>';
	alerts += ',<?php echo $CFG->ALERT_CONTROVERSIAL_IDEA; ?>';
	alerts += ',<?php echo $CFG->ALERT_CONTROVERSIAL_IDEA; ?>';
	alerts += ',<?php echo $CFG->ALERT_RATING_IGNORED_ARGUMENT; ?>';
	alerts += ',<?php echo $CFG->ALERT_USER_IGNORED_COMPETITORS; ?>';
	alerts += ',<?php echo $CFG->ALERT_USER_IGNORED_ARGUMENTS; ?>';
	alerts += ',<?php echo $CFG->ALERT_USER_IGNORED_RESPONSES; ?>';

	args["alerts"] = alerts;

	loadAlertsData(args, nodealertDiv, useralertDiv, alertmessagearea, issuenodeid);
}

function loadAlertsData(args, nodealertDiv, useralertDiv, alertmessagearea, issuenodeid) {

	alertmessagearea.update(getLoading("<?php echo $LNG->LOADING_MESSAGE; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getalerts&" + Object.toQueryString(args);
	//alert(reqUrl);

	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){
			//alert(transport.responseText);

			var json = null;
			try {
				json = transport.responseText.evalJSON();
			} catch(e) {
				alertmessagearea.innerHTML="<?php echo $LNG->ALERT_NO_RESULTS; ?>";
				return;
			}
			if(json.error){
				alertmessagearea.innerHTML="<?php echo $LNG->ALERT_NO_RESULTS; ?>";
				return;
			}

			var data = json.alertdata[0];
			//alert(data.toSource());
			if (data && (data.alertarray.length > 0 || data.userarray.length > 0)) {
				var alertarray = data.alertarray[0];
				var userarray = data.userarray[0];
				//alert(alertarray.toSource());

				// Process Users
				var usersDataArray = new Array();
				if (data.users) {
					var userObj = data.users[0];
					var userSet = userObj.userset;
					var users = userSet.users;
					for (user in users) {
						if (users[user].user) {
							var cuser = users[user].user;
							if (cuser.profileid) {
								usersDataArray[cuser.profileid] = cuser;
							} else {
								usersDataArray[cuser.userid] = cuser;
							}
						}
					}
				}

				// Process Nodes
				var nodesArray = new Array();
				if (data.nodes) {
					var nodesObj = data.nodes[0];
					var nodeSet = nodesObj.nodeset;
					var nodes = nodeSet.nodes;
					for (node in nodes) {
						if (nodes[node].cnode) {
							var cnode = nodes[node].cnode;
							nodesArray[cnode.nodeid] = cnode;
						}
					}
				}


				// process user specific alerts
				var i=0;
				for (userid in userarray) {
					if (userarray.hasOwnProperty(userid)) {
						if (USER && USER === userid) {
							var user = usersDataArray[userid];
							/*if (user.homepage && user.homepage != "") {
								useralertDiv.insert('<br><h2 style="font-size:10pt"><a href="'+user.homepage+'" target="_blank">'+user.name+'</a></h2>');
							} else {
								useralertDiv.insert('<br><h2 style="font-size:10pt">'+user.name+'</h2>');
							}*/

							var alertTypes = userarray[userid][0];
							for (alerttype in alertTypes) {
								if (alertTypes.hasOwnProperty(alerttype)) {
									//alert(alertype);
									var alertName = getAlertName(alerttype);
									var	title = new Element('div', {'title':getAlertHint(alerttype), 'style':'font-weight:bold;border-top:1px solid #E8E8E8;font-size:12pt'});
									title.insert(alertName);
									var countspan = new Element('span', {'id':'titlecount'+i, 'style':'font-size:10pt; font-weight:normal; padding-left:5px;'});
									title.insert(countspan);
									if (i > 0) {
										title.style.marginTop = '10px';
									} else {
										title.style.marginTop = '0px';
									}
									useralertDiv.insert(title);
									i++;
									var posts = alertTypes[alerttype][0];
									var k=0;
									for (post in posts) {
										if (posts.hasOwnProperty(post)) {
											k++;
											var display = 'block';
											if (k>ALERT_COUNT) {
												display = 'none';
											}
											var postid = posts[post];
											if (nodesArray[postid]) {
												var node = nodesArray[postid];
												createAlertNodeLink(alerttype, postid, node, useralertDiv, display);
											} else if (usersDataArray[postid]) {
												var inneruser = usersDataArray[postid];
												createAlertUserLink(alerttype, postid, inneruser, useralertDiv, display);
											}
										}
									}
									countspan.insert("("+k+")");
									if (k>ALERT_COUNT) {
										var morebutton = new Element('span', {'class':'active','style':'color:#A6156C;margin-bottom:10px;'});
										morebutton.insert('<?php echo $LNG->ALERT_SHOW_ALL; ?>');
										morebutton.alerttype = alerttype;
										Event.observe(morebutton,"click", function(){
											toggleAlertPosts(this, this.alerttype);
										});
										useralertDiv.insert(morebutton);
									}
								}
							}
						}
					}
				}

				var hasData = false;
				if (i > 0) {
					hasData = true;
					nodealertDiv.style.marginTop = "20px";
				}

				// process map specific alerts
				i=0;
				for (alerttype in alertarray) {
					if (alertarray.hasOwnProperty(alerttype)) {
						i++;
						var alertName = getAlertName(alerttype);
						var	title = new Element('div', {'title':getAlertHint(alerttype), 'style':'font-weight:bold;border-top:1px solid #E8E8E8;font-size:12pt'});
						title.insert(alertName);
						var countspan = new Element('span', {'id':'titlecount'+i, 'style':'font-size:10pt; font-weight:normal; padding-left:5px;'});
						title.insert(countspan);
						if (i > 0) {
							title.style.marginTop = '10px';
						} else {
							title.style.marginTop = '0px';
						}
						nodealertDiv.insert(title);
						var posts = alertarray[alerttype][0];
						var k=0;
						for (post in posts) {
							if (posts.hasOwnProperty(post)) {
								k++;
								var display = 'block';
								if (k>ALERT_COUNT) {
									display = 'none';
								}
								var postid = posts[post];
								if (nodesArray[postid]) {
									var node = nodesArray[postid];
									createAlertNodeLink(alerttype, postid, node, nodealertDiv, display);
								} else if (usersDataArray[postid]) {
									var inneruser = usersDataArray[postid];
									createAlertUserLink(alerttype, postid, inneruser, nodealertDiv, display);
								}
							}
						}
						countspan.insert("("+k+")");
						if (k>ALERT_COUNT) {
							var morebutton = new Element('div', {'class':'active','style':'color:#A6156C;margin-top:5px;margin-bottom:10px;'});
							morebutton.insert('<?php echo $LNG->ALERT_SHOW_ALL; ?>');
							morebutton.alerttype = alerttype;
							Event.observe(morebutton,"click", function(){
								toggleAlertPosts(this, this.alerttype);
							});
							nodealertDiv.insert(morebutton);
						}
					}
				}
				if (i > 0) {
					hasData = true;
				}
				if (hasData) {
					alertmessagearea.innerHTML="";
				} else {
					alertmessagearea.innerHTML="<?php echo $LNG->ALERT_NO_RESULTS; ?>";
				}
			} else {
				alertmessagearea.innerHTML="<?php echo $LNG->ALERT_NO_RESULTS; ?>";
			}
		}
	});
}

function createAlertNodeLink(alerttype, postid, node, container, display) {
	var name = node.name;
	var type = alerttype.replace(/ /g,'');
	var id = 'post';
	if (display == 'none') {
		id = type;
	}
	var nodespan = new Element('div', {'name':id, 'style':'display:'+display+';padding-top:10px;'});

	var role = node.role[0].role;
	var alttext = getNodeTitleAntecedence(role.name, false);
	if (role.image != null && role.image != "") {
		var nodeicon = new Element('img',{'alt':alttext, 'title':alttext, 'style':'width:16px;height:16px;padding-top:6px;padding-right:5px;','border':'0','src': URL_ROOT + role.image});
		nodespan.insert(nodeicon);
	}

	if (nodeObj && nodeObj.nodeid == postid) {
		var nodespantext = new Element('span', {'style':'display:inline'});
		nodespantext.insert(name);
		nodespan.insert(nodespantext);
	} else {
		var desc = "<?php echo $LNG->ALERT_CLICK_HIGHLIGHT; ?>";
		var nodespantext = new Element('span', {'class':'active', 'title':desc, 'style':'display:inline'});
		nodespantext.postid = postid;
		nodespantext.alerttype = alerttype;
		nodespantext.insert(name);
		Event.observe(nodespantext,"click", function(){
			//NODE_ARGS['selectednodeid'] = postid;
			//refreshSolutions();

			var found = false;
			var items = document.getElementsByName('idearowitem');
			for (var i=0; i<items.length; i++) {
				var item = items[i];
				var nodeid = item.getAttribute('nodeid');
				if (nodeid == postid) {
					found = true;
					var options = new Array();
					options['startcolor'] = '#FAFB7D';
					options['endcolor'] = '#FDFDE3';
					options['restorecolor'] = 'transparent';
					options['duration'] = 5;
					highlightElement(item, options);
					break;
				}
			}
			if (!found) {
				var items = document.getElementsByName('argumentrowitem');
				for (var i=0; i<items.length; i++) {
					var item = items[i];
					var nodeid = item.getAttribute('nodeid');
					if (nodeid == postid) {
						if (item.getAttribute('parentid')) {
							openSelectedItem(item.getAttribute('parentid'), 'arguments');
						}
						var options = new Array();
						options['startcolor'] = '#FAFB7D';
						options['endcolor'] = '#FDFDE3';
						options['restorecolor'] = 'transparent';
						options['duration'] = 5;
						highlightElement(item, options);
					}
				}
			}

		});
		nodespan.insert(nodespantext);
	}
	container.insert(nodespan);
}

function createAlertUserLink(alerttype, postid, inneruser, container, display) {
	var type = alerttype.replace(/ /g,'');
	var id = 'post';
	if (display == 'none') {
		id = type;
	}
	var nodespan = new Element('div', {'name':id, 'class':'active', 'style':'display:'+display+';padding-top:10px;'});

	var nodespantext = new Element('span', {'title':'<?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_HINT; ?>: '+inneruser.name, });
	nodespantext.userid = inneruser.userid;
	nodespantext.alerttype = alerttype;
	nodespantext.insert('<span>'+inneruser.name+'</span>');
	Event.observe(nodespantext,"click", function(){
		//auditAlertClicked(this.userid, this.alerttype);
		viewUserHome(this.userid)
	});
	nodespan.insert(nodespantext);
	container.insert(nodespan);
}

function toggleAlertPosts(obj, alerttype) {
	var type = alerttype.replace(/ /g,'');
	var items = document.getElementsByName(type);
	for (var i=0; i<items.length; i++) {
		var item = items[i];
		if (item.style.display == 'none') {
			item.style.display = 'block';
			obj.update('<?php echo $LNG->ALERT_SHOW_LESS; ?>');
		} else {
			item.style.display = 'none';
			obj.update('<?php echo $LNG->ALERT_SHOW_ALL; ?>');
		}
	}
}

function getAlertName(alerttype) {
	var alertName = "";
	switch(alerttype) {
		case '<?php echo $CFG->ALERT_UNSEEN_BY_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_UNSEEN_BY_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_RESPONSE_TO_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_RESPONSE_TO_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_UNRATED_BY_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_UNRATED_BY_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_LURKING_USER; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_LURKING_USER); ?>';
			break;
		case '<?php echo $CFG->ALERT_INACTIVE_USER; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_INACTIVE_USER); ?>';
			break;
		case '<?php echo $CFG->ALERT_MATURE_ISSUE; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_MATURE_ISSUE); ?>';
			break;
		case '<?php echo $CFG->ALERT_IMMATURE_ISSUE; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_IMMATURE_ISSUE); ?>';
			break;
		case '<?php echo $CFG->ALERT_IGNORED_POST; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_IGNORED_POST); ?>';
			break;
		case '<?php echo $CFG->ALERT_INTERESTING_TO_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_INTERESTING_TO_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_INTERESTING_TO_PEOPLE_LIKE_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_INTERESTING_TO_PEOPLE_LIKE_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_SUPPORTED_BY_PEOPLE_LIKE_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_SUPPORTED_BY_PEOPLE_LIKE_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_HOT_POST; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_HOT_POST); ?>';
			break;
		case '<?php echo $CFG->ALERT_ORPHANED_IDEA; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_ORPHANED_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_EMERGING_WINNER; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_EMERGING_WINNER); ?>';
			break;
		case '<?php echo $CFG->ALERT_CONTENTIOUS_ISSUE; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_CONTENTIOUS_ISSUE); ?>';
			break;
		case '<?php echo $CFG->ALERT_INCONSISTENT_SUPPORT; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_INCONSISTENT_SUPPORT); ?>';
			break;
		case '<?php echo $CFG->ALERT_PEOPLE_WITH_INTERESTS_LIKE_MINE; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_PEOPLE_WITH_INTERESTS_LIKE_MINE); ?>';
			break;
		case '<?php echo $CFG->ALERT_PEOPLE_WHO_AGREE_WITH_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_PEOPLE_WHO_AGREE_WITH_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_INTERESTING_TO_ME; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_INTERESTING_TO_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_CONTROVERSIAL_IDEA; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_CONTROVERSIAL_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_GONE_INACTIVE; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_USER_GONE_INACTIVE); ?>';
			break;
		case '<?php echo $CFG->ALERT_WELL_EVALUATED_IDEA; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_WELL_EVALUATED_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_POORLY_EVALUATED_IDEA; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_POORLY_EVALUATED_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_RATING_IGNORED_ARGUMENT; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_RATING_IGNORED_ARGUMENT); ?>';
			break;
		case '<?php echo $CFG->ALERT_UNSEEN_RESPONSE; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_UNSEEN_RESPONSE); ?>';
			break;
		case '<?php echo $CFG->ALERT_UNSEEN_COMPETITOR; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_UNSEEN_COMPETITOR); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_IGNORED_COMPETITORS; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_USER_IGNORED_COMPETITORS); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_IGNORED_ARGUMENTS; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_USER_IGNORED_ARGUMENTS); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_IGNORED_RESPONSES; ?>':
			alertName = '<?php echo addslashes($LNG->ALERT_USER_IGNORED_RESPONSES); ?>';
			break;
	}

	return alertName;
}

function getAlertHint(alerttype) {
	var alertHint = "";
	switch(alerttype) {
		case '<?php echo $CFG->ALERT_UNSEEN_BY_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_UNSEEN_BY_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_RESPONSE_TO_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_RESPONSE_TO_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_UNRATED_BY_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_UNRATED_BY_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_LURKING_USER; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_LURKING_USER); ?>';
			break;
		case '<?php echo $CFG->ALERT_INACTIVE_USER; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_INACTIVE_USER); ?>';
			break;
		case '<?php echo $CFG->ALERT_MATURE_ISSUE; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_MATURE_ISSUE); ?>';
			break;
		case '<?php echo $CFG->ALERT_IMMATURE_ISSUE; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_IMMATURE_ISSUE); ?>';
			break;
		case '<?php echo $CFG->ALERT_IGNORED_POST; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_IGNORED_POST); ?>';
			break;
		case '<?php echo $CFG->ALERT_INTERESTING_TO_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_INTERESTING_TO_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_INTERESTING_TO_PEOPLE_LIKE_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_INTERESTING_TO_PEOPLE_LIKE_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_SUPPORTED_BY_PEOPLE_LIKE_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_SUPPORTED_BY_PEOPLE_LIKE_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_HOT_POST; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_HOT_POST); ?>';
			break;
		case '<?php echo $CFG->ALERT_ORPHANED_IDEA; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_ORPHANED_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_EMERGING_WINNER; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_EMERGING_WINNER); ?>';
			break;
		case '<?php echo $CFG->ALERT_CONTENTIOUS_ISSUE; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_CONTENTIOUS_ISSUE); ?>';
			break;
		case '<?php echo $CFG->ALERT_INCONSISTENT_SUPPORT; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_INCONSISTENT_SUPPORT); ?>';
			break;
		case '<?php echo $CFG->ALERT_PEOPLE_WITH_INTERESTS_LIKE_MINE; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_PEOPLE_WITH_INTERESTS_LIKE_MINE); ?>';
			break;
		case '<?php echo $CFG->ALERT_PEOPLE_WHO_AGREE_WITH_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_PEOPLE_WHO_AGREE_WITH_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_INTERESTING_TO_ME; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_INTERESTING_TO_ME); ?>';
			break;
		case '<?php echo $CFG->ALERT_CONTROVERSIAL_IDEA; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_CONTROVERSIAL_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_GONE_INACTIVE; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_USER_GONE_INACTIVE); ?>';
			break;
		case '<?php echo $CFG->ALERT_WELL_EVALUATED_IDEA; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_WELL_EVALUATED_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_POORLY_EVALUATED_IDEA; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_POORLY_EVALUATED_IDEA); ?>';
			break;
		case '<?php echo $CFG->ALERT_RATING_IGNORED_ARGUMENT; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_RATING_IGNORED_ARGUMENT); ?>';
			break;
		case '<?php echo $CFG->ALERT_UNSEEN_RESPONSE; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_UNSEEN_RESPONSE); ?>';
			break;
		case '<?php echo $CFG->ALERT_UNSEEN_COMPETITOR; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_UNSEEN_COMPETITOR); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_IGNORED_COMPETITORS; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_USER_IGNORED_COMPETITORS); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_IGNORED_ARGUMENTS; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_USER_IGNORED_ARGUMENTS); ?>';
			break;
		case '<?php echo $CFG->ALERT_USER_IGNORED_RESPONSES; ?>':
			alertHint = '<?php echo addslashes($LNG->ALERT_HINT_USER_IGNORED_RESPONSES); ?>';
			break;
	}
	return alertHint;
}

/**
 * Convert the given UTC timestamp (in seconds) to a local Date object.
 */
function convertUTCTimeToLocalDate(time) {
	if (time == 0) {
		return 0;
	}

	var newDate = new Date();
	newDate.setTime(time*1000);
    return newDate;
}

/**
 * Convert the given local Date object to a UTC timestamp (in seconds).
 */
function convertLocalDateToUTCTime(date) {
	var final = new Date(date.toUTCString());
	var finaltime = final.getTime();
	var utctime = finaltime/1000;
	return utctime;
}

/**
 * For the given Issue Node work out the current phase of the debate.
 */
function calculateIssuePhase(node) {

	// Currently this time comparison is all in UTC

	var now = Date.now();
	var thisphase = '';

	var startdate = 0;
	if (node.startdatetime && node.startdatetime > 0) {
		startdate = convertUTCTimeToLocalDate(node.startdatetime).getTime();
	}

	var enddate = 0;
	if (node.enddatetime && node.enddatetime > 0) {
		enddate = convertUTCTimeToLocalDate(node.enddatetime).getTime();
	}

	var discussionstart = 0;
	if (node.properties[0] && node.properties[0].discussionstart && node.properties[0].discussionstart > 0) {
		discussionstart = convertUTCTimeToLocalDate(parseInt(node.properties[0].discussionstart)).getTime();
	} else if (node.properties.discussionstart && node.properties.discussionstart > 0) {
		discussionstart = convertUTCTimeToLocalDate(parseInt(node.properties.discussionstart)).getTime();
	}

	var discussionend = 0;
	if (node.properties[0] && node.properties[0].discussionend && node.properties[0].discussionend > 0) {
		discussionend = convertUTCTimeToLocalDate(parseInt(node.properties[0].discussionend)).getTime();
	} else if (node.properties.discussionend && node.properties.discussionend > 0) {
		discussionend = convertUTCTimeToLocalDate(parseInt(node.properties.discussionend)).getTime();
	}

	var lemonstart = 0;
	if (node.properties[0] && node.properties[0].lemoningstart && node.properties[0].lemoningstart > 0) {
		lemonstart = convertUTCTimeToLocalDate(parseInt(node.properties[0].lemoningstart)).getTime();
	} else if (node.properties.lemoningstart && node.properties.lemoningstart > 0) {
		lemonstart = convertUTCTimeToLocalDate(parseInt(node.properties.lemoningstart)).getTime();
	}

	var lemonend = 0;
	if (node.properties[0] && node.properties[0].lemoningend && node.properties[0].lemoningend > 0) {
		lemonend = convertUTCTimeToLocalDate(parseInt(node.properties[0].lemoningend)).getTime();
	} else if (node.properties.lemoningend && node.properties.lemoningend > 0) {
		lemonend = convertUTCTimeToLocalDate(parseInt(node.properties.lemoningend)).getTime();
	}

	var votestart = 0;
	if (node.properties[0] && node.properties[0].votingstart && node.properties[0].votingstart > 0) {
		votestart = convertUTCTimeToLocalDate(parseInt(node.properties[0].votingstart)).getTime();
	} else if (node.properties.votingstart && node.properties.votingstart > 0) {
		votestart = convertUTCTimeToLocalDate(parseInt(node.properties.votingstart)).getTime();
	}

	var voteend = 0;
	if (node.properties[0] && node.properties[0].votingend && node.properties[0].votingend > 0) {
		voteend = convertUTCTimeToLocalDate(parseInt(node.properties[0].votingend)).getTime();
	} else if (node.properties.votingstart && node.properties.votingstart > 0) {
		voteend = convertUTCTimeToLocalDate(parseInt(node.properties.votingend)).getTime();
	}

	if (discussionstart > 0  && discussionend > 0
			&& (now >= discussionstart && now < discussionend) && votestart > 0) {
		thisphase = DISCUSS_PHASE;
	} else if (lemonstart > 0  && lemonend > 0
			&& (now >= lemonstart && now < lemonend)) {
		thisphase = REDUCE_PHASE;
	} else if (votestart > 0  && voteend > 0
			&& (now >= votestart && now < voteend)) {
		thisphase = DECIDE_PHASE;
	} else if (enddate > 0 && now >= enddate) {
		thisphase = CLOSED_PHASE;
	} else if (startdate > 0 && now < startdate) {
		thisphase = PENDING_PHASE;
	} else if (votestart > 0) {
		if (startdate > 0 && enddate > 0 && now < enddate && now >= votestart) {
			thisphase = TIMED_VOTEON_PHASE;
		} else if (startdate > 0 && enddate > 0 && now < enddate && now < votestart) {
			thisphase = TIMED_VOTEPENDING_PHASE;
		} else if (startdate == 0 && enddate == 0 && now < votestart) {
			thisphase = OPEN_VOTEPENDING_PHASE;
		} else if (startdate == 0 && enddate == 0 && now >= votestart) {
			thisphase = OPEN_VOTEON_PHASE;
		}
	}

	if (thisphase == "") {

		// check if voting set to off / or VOTING EMPTY.
		var votingon = "";
		if (node.properties[0] && node.properties[0].votingon) {
			votingon = node.properties[0].votingon;
		} else if (node.properties.votingon) {
			votingon = node.properties.votingon;
		}

		if (startdate > 0 && enddate > 0) {
			if (votingon == 'N' && discussionend > 0) {
				thisphase = TIMED_NOVOTE_PHASE;
			} else {
				thisphase = TIMED_PHASE;
			}
		} else {
			thisphase = OPEN_PHASE;
		}
	}

	return thisphase;
}

/**
 * Replacing function from Scriptaculous. Can now be done with css.
 * Hightlight an element transitioning between two highlight colours and finally restore it to a given colour.
 * @param item, the item to tranition the background colour on.
 * @param options, the options to use - object containing 'startcolor', 'endcolor', 'restorecolor', 'duration'
 */
function highlightElement(item, options) {

	// Prevent executing on elements not in the layout flow
	if (item.style.display == 'none') { return; }

	if (!options.restorecolor)
		options.restorecolor = item.style.backgroundColor;

    item.style.backgroundColor = options['startcolor'];

	item.style.webkitTransform = 'background-color '+options.duration+'s linear';
	item.style.MozTransform = 'background-color '+options.duration+'s linear';
	item.style.msTransform = 'background-color '+options.duration+'s linear';
	item.style.OTransform = 'background-color '+options.duration+'s linear';
	item.style.transition = 'background-color '+options.duration+'s linear';

	// Needed so that initial color and transition are applied before the transition is later triggered.
    setTimeout(function() {
		 highlightElementComplete(item, options);
    }, 100);

}

function highlightElementComplete(item, options) {

	// trigger transition
	item.style.backgroundColor = options['endcolor'];

	// Put it all back to normal about when transition should end plus a bit.
	var totalwait = parseInt((options.duration+1) * 1000); //convert seconds to milliseconds
    setTimeout(function() {
		item.style.transition = 'none';
		item.style.webkitTransform = 'none';
		item.style.MozTransform = 'none';
		item.style.msTransform = 'none';
		item.style.OTransform = 'none';
   		item.style.backgroundColor = options['restorecolor'];
    },totalwait);
}

/**
 * Add new new Script tag to the current HTML page dynamically to load a local javascript file on demand.
 *
 * @param url The url to add as the src on the new script tag
 * @param id If given set as the id of the new script tag
 */
function addScriptDynamically(url, id) {

	// only allow the import of local code;
	if (url.indexOf(URL_ROOT) == 0) {
		var headarea = document.getElementsByTagName("head").item(0);
		var scriptobj = document.createElement("script");
		scriptobj.setAttribute("type", "text/javascript");
		scriptobj.setAttribute("src", url);
		if (id) {
			scriptobj.setAttribute("id", id);
		}
		headarea.appendChild(scriptobj);
	}
}