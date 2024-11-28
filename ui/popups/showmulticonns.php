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
    include_once("../../config.php");

    $me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
    if ($HUB_FLM->hasCustomVersion($me)) {
    	$path = $HUB_FLM->getCodeDirPath($me);
    	include_once($path);
		die;
	}

    array_push($HEADER,"<link rel='stylesheet' href='".$HUB_FLM->getStylePath("style.css")."' type='text/css' media='screen' />");
    array_push($HEADER,"<link rel='stylesheet' href='".$HUB_FLM->getStylePath("stylecustom.css")."' type='text/css' media='screen' />");

    array_push($HEADER,"<script src='".$HUB_FLM->getCodeWebPath("ui/users.js.php")."' type='text/javascript'></script>");

    include_once($HUB_FLM->getCodeDirPath("ui/headerdialog.php"));

	$connectionids = optional_param("connectionids", "", PARAM_TEXT);
    $connectionids = parseToJSON($connectionids);
?>

<script type="text/javascript">
//<![CDATA[
	var LINKTYPELABEL_CUTOFF = 18;
	var connectionids = "<?php echo $connectionids; ?>";
	var connections = "";

	/**
	 * Display a list of connections with no numbers and checkboxes
	 */
	function displayMultipleConnections(objDiv,conns, start, direction, includemenu, type){

		var lOL = document.createElement("ol");
		lOL.start = start;
		lOL.className = 'conn-list-ol';
		for(var i=0; i< conns.length; i++){

			var iUL = document.createElement("li");
			iUL.id = conns[i].connection.connid;
			iUL.className = conn-list-li;
			lOL.appendChild(iUL);
			var cWrap = document.createElement("div");
			cWrap.className = 'conn-li-wrapper';
			var blobDiv = document.createElement("div");
			blobDiv.className = 'conn-blob-sm';
			var blobConn =  renderConnection(conns[i].connection,"conn-list"+i+start, includemenu, type);
			blobDiv.appendChild(blobConn);
			cWrap.appendChild(blobDiv);
			iUL.appendChild(cWrap);
		}
		objDiv.appendChild(lOL);
	}

	/**
	 * Display a list of connections with no numbers and checkboxes
	 */
	function displayMultipleConnectionsArray(objDiv,conns, start, direction, includemenu, type){

		var lOL = document.createElement("ol");
		lOL.start = start;
		lOL.className = 'conn-list-ol';
		for(var i=0; i< conns.length; i++){

			var iUL = document.createElement("li");
			iUL.id = conns[i].connid;
			iUL.class = 'conn-list-li';
			lOL.appendChild(iUL);
			var cWrap = document.createElement("div");
			cWrap.className = 'conn-li-wrapper';
			var blobDiv = document.createElement("div");
			blobDiv.className = 'conn-blob-sm';
			var blobConn =  renderConnection(conns[i], "conn-list"+i+start, includemenu, type);
			blobDiv.appendChild(blobConn);
			cWrap.appendChild(blobDiv);
			iUL.appendChild(cWrap);
		}
		objDiv.appendChild(lOL);
	}

	function renderConnection(connection,uniQ, includemenu, type){

		if (includemenu === undefined) {
			includemenu = false;
		}

		uniQ = connection.connid + uniQ;
		var connDiv = document.createElement("div");
		connDiv.className = 'connection';

		var fromDiv = document.createElement("div");
		fromDiv.className = 'fromidea-horiz';

		var from = connection.from[0].cnode;
		var fromrole = connection.fromrole[0].role;
		var to = connection.to[0].cnode;
		var torole = connection.torole[0].role;

		var fromNode = renderNodeFromLocalJSon(from,'conn-from-idea'+uniQ, fromrole, includemenu, type);
		fromDiv.appendChild(fromNode);
		fromDiv.innerHTML += '<div style="clear:both;"></div>';
		connDiv.appendChild(fromDiv);

		var linktypelabelfull = ""
		if (connection.linklabelname) {
			linktypelabelfull = connection.linklabelname;
		} else {
			linktypelabelfull = connection.linktype[0].linktype.label;
		}

		var linktypelabel = getLinkLabelName(fromrole.name, torole.name, linktypelabelfull)
		if (linktypelabelfull.length > LINKTYPELABEL_CUTOFF) {
			linktypelabel = linktypelabelfull.substring(0,LINKTYPELABEL_CUTOFF)+"...";
		}

		var group = 'neutral';
		if (linktypelabelfull == "<?php echo $CFG->LINK_PRO_SOLUTION; ?>") {
			group = 'positive'
		} else if (linktypelabelfull == "<?php echo $CFG->LINK_CON_SOLUTION; ?>") {
			group = 'negative'
		}

		var linkDiv = document.createElement("div");
		linkDiv.className = 'connlink-horiz-slim';
		linkDiv.id = 'connlink'+connection.connid;
		linkDiv.setStyle('background-image: url("'+URL_ROOT +'images/conn-'+group+'-slim3.png")');
		var ltDiv = document.createElement("div");
		ltDiv.className = 'conn-link-text';
		linkDiv.appendChild(ltDiv);

		var ltWrap = document.createElement("div");
		ltWrap.className = 'link-type-wrapper';
		ltDiv.appendChild(ltWrap);

		var ltText = document.createElement("div");
		ltText.className = 'link-type-text';
		ltText.innerHTML += linktypelabel;
		if (linktypelabelfull.length > LINKTYPELABEL_CUTOFF) {
			ltText.title = linktypelabelfull;
		}
		ltWrap.innerHTML += ltText;
		// set colour of ltText

		if (linktypelabelfull == "<?php echo $CFG->LINK_PRO_SOLUTION; ?>") {
			ltText.setStyle({"color":"#00BD53"});
		} else if (linktypelabelfull == "<?php echo $CFG->LINK_CON_SOLUTION; ?>") {
			ltText.setStyle({"color":"#C10031"});
		} else {
			ltText.setStyle({"color":"#B2B2B2"});
		}

		/*if (connection.linktype[0].linktype.grouplabel.toLowerCase() == "positive"){
			ltText.setStyle({"color":"#00BD53"});
		} else if (connection.linktype[0].linktype.grouplabel.toLowerCase() == "negative"){
			ltText.setStyle({"color":"#C10031"});
		} else if (connection.linktype[0].linktype.grouplabel.toLowerCase() == "neutral"){
			ltText.setStyle({"color":"#B2B2B2"});
		}*/

		ltText.style.width="154px";

		var iuDiv = document.createElement("div");
		iuDiv.style.marginLeft='90px';
		iuDiv.style.marginTop="3px";

		if (connectionids != "") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+connection.users[0].user.userid,
				'title':connection.users[0].user.name});
			imagelink.target = "_blank";
			var userimageThumb = document.createElement('img');
			userimageThumb.title = connection.users[0].user.name;
			userimageThumb.style = 'padding-right:5px;';
			userimageThumb.border = '0';
			userimageThumb.src = connection.users[0].user.thumb;
			imagelink.appendChild(userimageThumb);
			iuDiv.appendChild(imagelink);
		}

		linkDiv.appendChild(iuDiv);

		connDiv.appendChild(linkDiv);

		var toDiv = document.createElement("div");
		toDiv.className = 'toidea-horiz';
		var toNode = renderNodeFromLocalJSon(to,'conn-to-idea'+uniQ, torole, includemenu, type);
		toDiv.appendChild(toNode);
		toDiv.innerHTML += '<div style="clear:both;"></div>';
		connDiv.appendChild(toDiv);

		return connDiv;
	}

	async function getConnections(){
		if (connectionids != "") {

			const connectiondetails = document.getElementById("connectiondetails");
			connectiondetails.innerHTML = "";
			connectiondetails.appendChild(getLoading("(Loading connections...)"));

			var reqUrl = SERVICE_ROOT + "&method=getmulticonnections&connectionids="+connectionids;
			try {
				const json = await makeAPICall(reqUrl, 'GET');
				if (json.error) {
					alert(json.error[0].message);
					return;
				}
				if(json.connectionset[0].connections.length > 0){
					connectiondetails.innerHTML = "";
					displayMultipleConnections(connectiondetails, json.connectionset[0].connections, 1, 'right', false, 'active')
				} else {
					connectiondetails.innerHTML = "<p><h3>No Connections found</h3></p>";
				}
			} catch (err) {
				alert("There was an error: "+err.message);
				console.log(err)
			}
		} else if (window.opener.getLastConnections) {
			connections = window.opener.getLastConnections();
			if (connections != "" && connections.length > 0){
				connectiondetails.innerHTML = "";
				displayMultipleConnectionsArray(connectiondetails, connections, 1, 'right', false, 'inactive')
			}
		} else {
			connectiondetails.innerHTML = "<p><h3>Insufficient Data supplied to get Connections</h3></p>";
		}
   }

    /**
     *  set which tab to show and load first
     */
    window.addEventListener('load', function() {
        getConnections();

    });
//]]>
</script>
<div id="connectiondetails">
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>