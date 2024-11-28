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
?>

/**
 * Javascript functions for nodes
 */

var timers = new Array();

/**
 * Render a list of nodes
 */
function displayIdeaList(objDiv,nodes,start,includeUser,uniqueid,type,status) {

	if (includeUser == undefined) {
		includeUser = true;
	}
	if (type == undefined) {
		type = 'active';
	}
	if (uniqueid == undefined) {
		uniqueid = 'idea-list';
	}
	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

	var myuniqueid = "";
	var lOL = document.createElement("ol");
	lOL.start = start;
	lOL.className = 'idea-list-ol';
	for(var i=0; i < nodes.length; i++){
		var node = nodes[i].cnode;
		if(node){
			myuniqueid = uniqueid+i+start;
			var connection = node.connection;
			if (connection) {
				myuniqueid = node.nodeid + connection.connid+myuniqueid;
			} else {
				myuniqueid = node.nodeid + myuniqueid;
			}

			var iUL = document.createElement("li");
			iUL.id = node.nodeid;
			iUL.className = 'idea-list-li';
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div");
			blobDiv.id = 'ideablobdiv'+myuniqueid;
			blobDiv.className = 'idea-blob-list d-flex flex-column';

			var blobNode = renderIdeaList(node, myuniqueid, node.role[0].role,includeUser,type,status, i);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
			if (NODE_ARGS['currentphase'] == CLOSED_PHASE && i == 2) {
				blobDiv.innerHTML += '<hr class="hrline-slim">';
			}
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of nodes for nodes that where removed in the REDUCE_PHASE
 */
function displayRemovedIdeaList(objDiv,nodes,start,includeUser,uniqueid){
	if (includeUser == undefined) {
		includeUser = true;
	}
	if (uniqueid == undefined) {
		uniqueid = 'idea-removed-list';
	}
	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

	var myuniqueid = "";
	var lOL = document.createElement("ol", {'start':start, 'class':'idea-list-ol'});
	for(var i=0; i < nodes.length; i++){
		var node = nodes[i].cnode;
		if(node){
			myuniqueid = uniqueid+i+start;
			var connection = node.connection;
			if (connection) {
				myuniqueid = node.nodeid + connection.connid+myuniqueid;
			} else {
				myuniqueid = node.nodeid + myuniqueid;
			}

			var iUL = document.createElement("li", {'id':node.nodeid, 'class':'idea-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'id':'ideablobdiv'+myuniqueid, 'class':'idea-blob-list'});
			var blobNode = renderIdeaRemovedList(node, myuniqueid, node.role[0].role,includeUser);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of Issue nodes
 * @param width the width of each node box
 * @param height the height of each node box
 * @param node the nodes the array of node objects to render
 * @param uniqueid is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node. Defaults to the node role.
 * @param includeUser whether to include the user image and link. Defaults to true.
 * @param isActive defaults to 'active', but can be 'inactive' so toolbar buttons not included.
 * @param includeconnectedness should the connectedness count be included - defaults to false.
 * @param includevoting should the voting buttons be included - defaults to true.
 */
function displayIssueNodes(width, height, objDiv,nodes,start,includeUser,uniqueid, isActive, includeconnectedness, includevoting, cropdesc){
	if (includeUser == undefined) {
		includeUser = true;
	}
	if (uniqueid == undefined) {
		uniqueid = 'idea-list';
	}
	if (isActive == undefined) {
		isActive = 'active';
	}
	if (includeconnectedness === undefined) {
		includeconnectedness = false;
	}
	if (includevoting === undefined) {
		includevoting = true;
	}

	// Clear timers from any previous calls
 	clearAllIssueTimers();

	var lOL = document.createElement("div", {'start':start, 'class':'issues-div' });
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var blobDiv = document.createElement("div", {'class':'d-inline-block m-2'});
			var blobNode = renderIssueNode(width, height, nodes[i].cnode, uniqueid+i+start,nodes[i].cnode.role[0].role,includeUser,isActive, includeconnectedness, includevoting, cropdesc);
			blobDiv.appendChild(blobNode);
			lOL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of Comment nodes
 */
function displayCommentNodes(objDiv,nodes,start,includeUser,uniqueid, type, status){
	if (includeUser == undefined) {
		includeUser = true;
	}
	if (type == undefined) {
		type = 'active';
	}
	if (uniqueid == undefined) {
		uniqueid = 'widget-list';
	}
	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

	var myuniqueid = "";
	var lOL = document.createElement("ol", {'start':start, 'class':'idea-list-ol'});
	for(var i=0; i < nodes.length; i++){
		var node = nodes[i].cnode;
		if(node){
			myuniqueid = uniqueid+i+start;
			var connection = node.connection;
			if (connection) {
				myuniqueid = node.nodeid + connection.connid+myuniqueid;
			} else {
				myuniqueid = node.nodeid + myuniqueid;
			}

			var iUL = document.createElement("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'id':'commentblobdiv'+myuniqueid, 'class':'idea-blob-list'});
			var blobNode = renderCommentNode(nodes[i].cnode, myuniqueid, nodes[i].cnode.role[0].role,includeUser, type, status);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of Pro and Con nodes
 */
function displayArgumentNodes(objDiv,nodes,start,includeUser,uniqueid, type, status){
	if (includeUser == undefined) {
		includeUser = true;
	}
	if (type == undefined) {
		type = 'active';
	}
	if (uniqueid == undefined) {
		uniqueid = 'widget-list';
	}
	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}
	var myuniqueid = "";
	var lOL = document.createElement("ol", {'start':start, 'class':'idea-list-ol'});
	for(var i=0; i < nodes.length; i++){
		var node = nodes[i].cnode;
		if(node){
			myuniqueid = uniqueid+i+start;
			var connection = node.connection;
			if (connection) {
				myuniqueid = node.nodeid + connection.connid+myuniqueid;
			} else {
				myuniqueid = node.nodeid + myuniqueid;
			}

			var iUL = document.createElement("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'id':'argumentblobdiv'+myuniqueid, 'class':'idea-blob-list'});
			var blobNode = renderArgumentNode(nodes[i].cnode, myuniqueid, nodes[i].cnode.role[0].role,includeUser, type, status);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of nodes in the user home data area
 */
function displayUsersNodes(objDiv,nodes,start,uniqueid){
	if (uniqueid == undefined) {
		uniqueid = 'widget-list';
	}
	var lOL = document.createElement("ul", {'class':'widget-list-ideas'});
	for(var i=0; i < nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = document.createElement("li", {'id':nodes[i].cnode.nodeid});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':' '});
			var blobNode = renderListNode(nodes[i].cnode, uniqueid+i+start, nodes[i].cnode.role[0].role, false);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of nodes
 */
function displaySearchNodes(objDiv,nodes,start,includeUser,uniqueid){

	if (uniqueid == undefined) {
		uniqueid = 'search-list';
	}

	var lOL = document.createElement("ul", {'start':start, 'style':''});
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = document.createElement("li", {'id':nodes[i].cnode.nodeid});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':'idea-blob-list'});
			var blobNode = renderListNode(nodes[i].cnode, uniqueid+i+start,nodes[i].cnode.role[0].role, includeUser);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}

/**
 * Render a list of nodes
 */
function displayReportNodes(objDiv,nodes,start){

	objDiv.innerHTML += ('<div></div>');

	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = document.createElement("span", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			objDiv.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':' '});
			var blobNode = renderReportNode(nodes[i].cnode,'idea-list'+i+start, nodes[i].cnode.role[0].role);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
}

/**
 * Render a list of nodes for the Connectedness stats boxes
 */
function displayConnectionStatNodes(objDiv,nodes,start,includeUser,uniqueid){
	if (uniqueid == undefined) {
		uniqueid = 'idea-list';
	}
	var lOL = document.createElement("ol", {'start':start, 'class':'idea-list-ol'});
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = document.createElement("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':'idea-blob-list'});
			var blobNode = renderWidgetListNode(nodes[i].cnode, uniqueid+i+start,nodes[i].cnode.role[0].role,includeUser,'active');
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}
	objDiv.appendChild(lOL);
}


/**
 * Render the given node from an associated connection.
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 * @param type defaults to 'active', but can be 'inactive' so nothing is clickable
 * 			or a specialized type for some of the popups
 */
function renderWidgetListNode(node, uniQ, role, includeUser, type){

	if (type === undefined) {
		type = "active";
	}

	if(role === undefined){
		role = node.role[0].role;
	}

	var nodeuser = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		nodeuser = node.users[0];
	} else {
		nodeuser = node.users[0].user;
	}
	var user = null;
	var connection = node.connection;
	if (connection) {
		user = connection.users[0].user;
	} else {
		user = nodeuser;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var focalrole = "";
	if (connection) {
		uniQ = connection.connid+uniQ;
		var fN = connection.from[0].cnode;
		var tN = connection.to[0].cnode;
		if (node.nodeid == fN.nodeid) {
			focalrole = tN.role[0].role;
		} else {
			focalrole = fN.role[0].role;
		}
	} else {
		uniQ = node.nodeid + uniQ;
	}

	var nodeTable = document.createElement('table', {'class':'table'});
	nodeTable.className = "toConnectionsTable";
	var row = nodeTable.insertRow(-1);

	var textCell = row.insertCell(-1);

	var alttext = getNodeTitleAntecedence(role.name, false);
	if (node.imagethumbnail != null && node.imagethumbnail != "") {
		var originalurl = "";
		if(node.urls && node.urls.length > 0){
			for (var i=0 ; i< node.urls.length; i++){
				var urlid = node.urls[i].url.urlid;
				if (urlid == node.imageurlid) {
					originalurl = node.urls[i].url.url;
					break;
				}
			}
		}
		if (originalurl == "") {
			originalurl = node.imagethumbnail;
		}
		var iconlink = document.createElement('a', {
			'href':originalurl,
			'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
 		var nodeicon = document.createElement('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'src': URL_ROOT + node.imagethumbnail});
 		iconlink.appendChild(nodeicon);
 		textCell.appendChild(iconlink);
 		textCell.innerHTML += alttext+": ";
	} else if (role.image != null && role.image != "") {
 		var nodeicon = document.createElement('img',{'alt':alttext, 'title':alttext, 'src': URL_ROOT + role.image});
		textCell.appendChild(nodeicon);
	} else {
 		textCell.innerHTML += alttext+": ";
	}

	var title = node.name;
	var exploreButton = document.createElement('a', {'target':'_blank', 'class':'itemtext', 'id':'desctoggle'+uniQ});
	if (role.name == "Map") {
		if (node.searchid && node.searchid != "") {
			exploreButton.href= "<?php echo $CFG->homeAddress; ?>map.php?id="+node.nodeid+"&sid="+node.searchid;
		} else {
			exploreButton.href= "<?php echo $CFG->homeAddress; ?>map.php?id="+node.nodeid;
		}
	} else {
		if (node.searchid && node.searchid != "") {
			exploreButton.href= "<?php echo $CFG->homeAddress; ?>explore.php?id="+node.nodeid+"&sid="+node.searchid;
		} else {
			exploreButton.href= "<?php echo $CFG->homeAddress; ?>explore.php?id="+node.nodeid;
		}
	}
	exploreButton.innerHTML = title;
	textCell.appendChild(exploreButton);

	return nodeTable;
}


/**
 * Render the given node.
 * Used for Activities, Multi connection Viewer, Stats pages etc. where the node is drawn as a Cohere style box.
 *
 * @param node the node object to render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includemenu whether to include the drop-down menu
 * @param type defaults to 'active', but can be 'inactive' so nothing is clickable
 * 			or a specialized type for some of the popups
 */
function renderNodeFromLocalJSon(node, uniQ, role, includemenu, type) {

	if (type === undefined) {
		type = "active";
	}
	if (includemenu === undefined) {
		includemenu = true;
	}
	if(role === undefined){
		role = node.role[0];
	}
	var user = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		user = node.users[0];
	} else {
		user = node.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	// used mostly for getting data from Audit history. So nodes repeated a lot.
	// creation date will be the same, but modification date will be different for each duplicated node in the Audit
	uniQ = node.modificationdate+node.nodeid + uniQ;

	var iDiv = document.createElement("div", {'class':'idea-container'});
	var ihDiv = document.createElement("div", {'class':'idea-header'});
	var itDiv = document.createElement("div", {'class':'idea-title'});

	var nodeTable = document.createElement( 'table' );
	nodeTable.className = "toConnectionsTable";
	if (type == "connselect") {
		nodeTable.style.cursor = 'pointer';
		nodeTable.addEventListener("click", function() {	
			loadConnectionNode(node, role);
		});
	}

	var row = nodeTable.insertRow(-1);
	var leftCell = row.insertCell(-1);
	leftCell.vAlign="top";
	leftCell.align="left";
	var rightCell = row.insertCell(-1);
	rightCell.vAlign="top";
	rightCell.align="right";

	//get url for any saved image.

	//add left side with icon image and node text.
	var alttext = getNodeTitleAntecedence(role.name, false);
	if (node.imagethumbnail != null && node.imagethumbnail != "") {
		var originalurl = "";
		if(node.urls && node.urls.length > 0){
			for (var i=0 ; i< node.urls.length; i++){
				var urlid = node.urls[i].url.urlid;
				if (urlid == node.imageurlid) {
					originalurl = node.urls[i].url.url;
					break;
				}
			}
		}
		if (originalurl == "") {
			originalurl = node.imagethumbnail;
		}
		var iconlink = document.createElement('a', {
			'href':originalurl,
			'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
 		var nodeicon = document.createElement('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'src': URL_ROOT + node.imagethumbnail});
 		iconlink.appendChild(nodeicon);
 		itDiv.appendChild(iconlink);
 		itDiv.innerHTML += alttext+": ";
	} else if (role.image != null && role.image != "") {
 		var nodeicon = document.createElement('img',{'alt':alttext, 'title':alttext, 'src': URL_ROOT + role.image});
		itDiv.appendChild(nodeicon);
	} else {
 		itDiv.innerHTML += alttext+": ";
	}
	itDiv.innerHTML += "<span>"+node.name+"</span>";

	leftCell.appendChild(itDiv);

	// Add right side with user image and date below
	var iuDiv = document.createElement("div", {'class':'idea-user'});

	var userimageThumb = document.createElement('img',{'alt':user.name, 'title': user.name, 'src': user.thumb});

	if (type == "active") {
		var imagelink = document.createElement('a', {
			'target':'_blank',
			'href':URL_ROOT+"user.php?userid="+user.userid,
			'title':user.name});
		if (breakout != "") {
			imagelink.target = "_blank";
		}
		imagelink.appendChild(userimageThumb);
		iuDiv.appendChild(imagelink);
	} else {
		iuDiv.appendChild(userimageThumb)
	}

	var modDate = new Date(node.creationdate*1000);
	if (modDate) {
		var fomatedDate = modDate.format(DATE_FORMAT);
		iuDiv.innerHTML += "<div>"+fomatedDate+"</span>";
	}

	rightCell.appendChild(iuDiv);
	ihDiv.appendChild(nodeTable);

	var iwDiv = document.createElement("div", {'class':'idea-wrapper'});
	var imDiv = document.createElement("div", {'class':'idea-main'});
	var idDiv = document.createElement("div", {'class':'idea-detail'});
	var headerDiv = document.createElement("div", {'class':'idea-menus'});
	idDiv.appendChild(headerDiv);

	if (type == 'active') {
		var exploreButton = document.createElement("a", {'title':'<?php echo $LNG->NODE_EXPLORE_BUTTON_HINT; ?>'} );
		exploreButton.innerHTML = "<?php echo $LNG->NODE_EXPLORE_BUTTON_TEXT;?>";
		exploreButton.href= URL_ROOT+"explore.php?id="+node.nodeid;
		exploreButton.target = 'coheremain';

		headerDiv.appendChild(exploreButton);
	}

	imDiv.appendChild(idDiv);
	iwDiv.appendChild(imDiv);

	iDiv.appendChild(ihDiv);
	iDiv.appendChild(iwDiv);

	return iDiv;
}

/**
 * Render the given node for drawing on the item Picker list.
 * @param node the node object to render
 * @param role the role object for this node
 * @param includeUser whether to include the user details
 */
function renderPickerNode(node, role, includeUser){

	if(role === undefined){
		role = node.role[0].role;
	}

	var user = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		user = node.users[0];
	} else {
		user = node.users[0].user;
	}

	var iDiv = document.createElement("div", {'class':' '});
	var ihDiv = document.createElement("div", {'class':' '});
	var itDiv = document.createElement("div", {'class':'idea-title'});

	var nodeTable = document.createElement( 'table' );
	nodeTable.className = "toConnectionsTable";
	nodeTable.style.cursor = 'pointer';

	var row = nodeTable.insertRow(-1);
	var leftCell = row.insertCell(-1);

	var rightCell = row.insertCell(-1);
	rightCell.vAlign="top";
	rightCell.align="right";

	var alttext = getNodeTitleAntecedence(role.name, false);
	if (role.image != null && role.image != "") {
		var nodeicon = document.createElement('img',{'alt':alttext, 'title':alttext, 'src': URL_ROOT + role.image});
		itDiv.appendChild(nodeicon);
	} else {
		itDiv.innerHTML += alttext+": ";
	}

	itDiv.addEventListener("click", function () {
		loadSelecteditem(node);
	});

	itDiv.innerHTML += "<span class='itemtext' title='Select this item'>"+node.name+"</span>";

	leftCell.appendChild(itDiv);

	if (includeUser) {
		var iuDiv = document.createElement("div", {'class':'idea-user2'});
		var userimageThumb = document.createElement('img',{'alt':user.name, 'title': user.name, 'src': user.thumb});
		iuDiv.appendChild(userimageThumb)
		rightCell.appendChild(iuDiv);
	}

	ihDiv.appendChild(nodeTable);

	iDiv.appendChild(ihDiv);

	var iwDiv = document.createElement("div", {'class':'idea-wrapper'});
	iDiv.appendChild(iwDiv);

	return iDiv;
}

/**
 * Render the given node.
 * @param width the width of the node box (e.g. 200px or 20%)
 * @param height the height of the node box (e.g. 200px or 20%)
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node. Defaults to the node role.
 * @param includeUser whether to include the user image and link. Defaults to true.
 * @param type defaults to 'active', but can be 'inactive' so nothing is clickable.
 * 			or a specialized type for some of the popups
 * @param includeconnectedness should the connectedness count be included - defaults to false.
 * @param includevoting should the voting buttons be included - defaults to true.
 * @param cropdesc whether to crop the description text.
 * @param mainheading whether or not the title is a main heading instead of a link.
 * @param includestats whether or not to include the stats list below this Debate.
 */
function renderIssueNode(width, height, node, uniQ, role, includeUser, type, includeconnectedness, includevoting, cropdesc, mainheading, includestats){

	if(role === undefined){
		role = node.role[0].role;
	}
	if(includeUser === undefined){
		includeUser = true;
	}
	if (type === undefined) {
		type = "active";
	}
	if (includeconnectedness === undefined) {
		includeconnectedness = false;
	}
	if (includevoting === undefined) {
		includevoting = true;
	}
	if (mainheading === undefined) {
		mainheading = false;
	}
	if (includestats === undefined){
		includestats = true;
	}

	var user = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		user = node.users[0];
	} else {
		user = node.users[0].user;
	}

	uniQ = node.nodeid + uniQ;

	var groupid = "";
	if (node.groupid) {
		groupid = node.groupid;
	}

	var issueClosed = false;
	var notStarted = false;
	if (node.startdatetime && node.enddatetime) {
		var start = new Date(node.startdatetime*1000);
		var end = new Date(node.enddatetime*1000);
		var now = Date.UTC();

		if (now < start.getTime()) {
			notStarted = true;
		} else if (now >= start.getTime() && now <= end.getTime()) {
			issueClosed = false;
			notStarted = false;
		} else if (now > end.getTime()) {
			issueClosed = true;
			notStarted = false;
		}
	}

	var breakout = "";
	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var iDiv = document.createElement("div", {'class':'card border-0 my-2'});

	var nodetableDiv = document.createElement("div", {'class':'card-body pb-0'});
	var nodeTable = document.createElement( 'div', {'class':'nodetableDebate border border-2'} );

	nodetableDiv.appendChild(nodeTable);

	var row = document.createElement( 'div', {'class':'d-flex flex-row'} );
	nodeTable.appendChild(row);

	var imageCell = document.createElement( 'div', {'class':'p-2 issue-img'} );
	row.appendChild(imageCell);

	if (notStarted) {
		var imageObj = document.createElement('img',{'alt':node.name, 'title': node.name, 'src': node.image});
		imageCell.appendChild(imageObj);
		imageCell.title = '<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>';
	} else {
		var imageObj = document.createElement('img',{'alt':node.name, 'title': node.name, 'src': node.image});
		var imagelink = document.createElement('a', {
			'href':URL_ROOT+"explore.php?id="+node.nodeid,
		});

		imagelink.appendChild(imageObj);
		imageCell.appendChild(imagelink);
		imageCell.title = '<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>';
	}

	var textCell = document.createElement( 'div', {'class':'p-2'} );
	row.appendChild(textCell);

	var textDiv = document.createElement('div', {'class':'issue-title'});
	textCell.appendChild(textDiv);

	var title = node.name;
	var description = node.description;

	if (mainheading) {
		var exploreButton = document.createElement('h1');
		textDiv.appendChild(exploreButton);
		exploreButton.innerHTML += title;
	} else {
		if (notStarted) {
			var exploreButton = document.createElement('span', {'class':' '});
			textDiv.appendChild(exploreButton);
			exploreButton.innerHTML += title;
		} else {
			var exploreButton = document.createElement('a', {'title':'<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>'});
			if (node.searchid && node.searchid != "") {
				exploreButton.href= "<?php echo $CFG->homeAddress; ?>explore.php?id="+node.nodeid+"&sid="+node.searchid;
			} else if (node.groupid && node.groupid != "") {
				exploreButton.href= "<?php echo $CFG->homeAddress; ?>explore.php?groupid="+node.groupid+"&id="+node.nodeid;
			} else {
				exploreButton.href= "<?php echo $CFG->homeAddress; ?>explore.php?id="+node.nodeid;
			}

			var croppedtitle = title;
			if (cropdesc && title.length > 100) {
				croppedtitle = title.substr(0,100)+"...";
			}

			exploreButton.innerHTML +=croppedtitle;
		}

		textDiv.appendChild(exploreButton);
	}

	if (mainheading) {
		var textDivinner = document.createElement('div', {'class':' '});
		textDivinner.innerHTML += (description);
		textDiv.appendChild(textDivinner);
	} else {
		if (description != "" && title.length <=80) {
			var plaindesc = removeHTMLTags(description);
			var hint = plaindesc;
			var croplength = 110-title.length;
			if (plaindesc && plaindesc.length > croplength) {
				hint = plaindesc;
				var plaincrop = plaindesc.substr(0,croplength)+"...";
				textDiv.innerHTML += '<p title="'+hint+'">'+plaincrop+'</p>';
			} else {
				textDiv.innerHTML +='<p>'+plaindesc+'</p>';
			}
		}
	}

	var rowToolbar = document.createElement( 'div', {'class':'d-flex justify-content-between'} );
	nodeTable.appendChild(rowToolbar);

	var toolbarCell = document.createElement( 'div', {'class':'d-flex align-items-end'} );
	rowToolbar.appendChild(toolbarCell);

	var userDiv = document.createElement("div", {'class':'m-1'} );
	toolbarCell.appendChild(userDiv);

	if (includeUser) {
		var userimageThumb = document.createElement('img',{'alt':user.name, 'title': user.name, 'src': user.thumb});
		if (type == "active") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+user.userid,
				'title':user.name});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.appendChild(userimageThumb);
			userDiv.appendChild(imagelink);
		} else {
			userDiv.appendChild(userimageThumb)
		}

		var userDateDiv = document.createElement("div", {'class':'m-1'} );
		toolbarCell.appendChild(userDateDiv);

		var cDate = new Date(node.creationdate*1000);
		var dateDiv = document.createElement('div',{'title':'<?php echo $LNG->NODE_ADDED_ON; ?>', 'class':'added_on'});
		dateDiv.innerHTML += cDate.format(DATE_FORMAT);

		userDateDiv.appendChild(dateDiv);
	}

	var toolbarDivOuter = document.createElement("div", {'class':'d-flex align-items-end'} );
	rowToolbar.appendChild(toolbarDivOuter);

	var toolbarDiv = document.createElement("div", {'class':'m-1 issue-tools'} );
	toolbarDivOuter.appendChild(toolbarDiv);

	// IF OWNER ADD EDIT / DEL ACTIONS
	if (type == "active") {
		if (USER == user.userid) {
			var edit = document.createElement('img',{'alt':'<?php echo $LNG->EDIT_BUTTON_TEXT;?>', 'title': '<?php echo $LNG->EDIT_BUTTON_HINT_ISSUE;?>', 'src': '<?php echo $HUB_FLM->getImagePath("edit.png"); ?>'});
			edit.onclick = function (){loadDialog('editissue',URL_ROOT+"ui/popups/issueedit.php?nodeid="+node.nodeid, 770,550)};
			toolbarDiv.appendChild(edit);
			var del = document.createElement('img',{'id':'deletebutton'+uniQ, 'alt':'<?php echo $LNG->NO_DELETE_BUTTON_ALT;?>', 'title': '<?php echo $LNG->NO_DELETE_BUTTON_HINT;?>', 'src': '<?php echo $HUB_FLM->getImagePath("delete-off.png"); ?>'});
			toolbarDiv.appendChild(del);
			if (node.connectedness == 0) {
				var deletename = node.name;
				del.src = '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>';
				del.alt = '<?php echo $LNG->DELETE_BUTTON_ALT;?>';
				del.title = '<?php echo $LNG->DELETE_BUTTON_HINT;?>';
				del.onclick = function () {	deleteNode(node.nodeid, deletename, role.name);	};
			}
		}
	}

	<?php if ($CFG->SPAM_ALERT_ON) { ?>
	if (mainheading) {
		if (type == "active" && USER != "" && USER != user.userid) { // IF LOGGED IN AND NOT YOU
			toolbarDiv.appendChild(createSpamButton(node, role));
		}
	}
	<?php } ?>

	if (type == "active" && !issueClosed) {
		if (USER != "") {
			var followbutton = document.createElement('img', {'class':' '});
			followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
			followbutton.setAttribute('alt', 'Follow');
			followbutton.setAttribute('id','follow'+node.nodeid);
			followbutton.nodeid = node.nodeid;
			followbutton.style.cursor = 'pointer';

			toolbarDiv.appendChild(followbutton);

			if (node.userfollow && node.userfollow == "Y") {
				followbutton.onclick = function (){ unfollowNode(node, this, "") };
				followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("following.png"); ?>');
				followbutton.setAttribute('title', '<?php echo $LNG->NODE_UNFOLLOW_ITEM_HINT; ?>');
			} else {
				followbutton.onclick = function (){ followNode(node, this, "") };
				followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
				followbutton.setAttribute('title', '<?php echo $LNG->NODE_FOLLOW_ITEM_HINT; ?>');
			}
		} else {
			toolbarDiv.innerHTML += "<img onclick='document.getElementById(\"loginsubmit\").click(); return true;' title='<?php echo $LNG->WIDGET_FOLLOW_SIGNIN_HINT; ?>' src='<?php echo $HUB_FLM->getImagePath("followgrey.png"); ?>' />";
		}
	}

	if (includevoting == true && !notStarted) {
		if (role.name == 'Issue'
			|| role.name == 'Solution'
			|| role.name == 'Pro'
			|| role.name == 'Con') {

			// vote for
			var voteforimg = document.createElement('img');
			voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-grey3.png"); ?>');
			voteforimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_FOR_ICON_ALT; ?>');
			voteforimg.setAttribute('id','nodefor'+node.nodeid);
			voteforimg.nodeid = node.nodeid;
			voteforimg.vote='Y';
			toolbarDiv.appendChild(voteforimg);
			if (!node.positivevotes) {
				node.positivevotes = 0;
			}

			if (issueClosed) {
				toolbarDiv.innerHTM += '<b><span id="nodevotefor'+node.nodeid+'">'+node.positivevotes+'</span></b>';
			} else {
				if(USER != ""){
					voteforimg.style.cursor = 'pointer';
					if (node.uservote && node.uservote == 'Y') {
						voteforimg.onclick = function (){ deleteNodeVote(this) };
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled3.png"); ?>');
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!node.uservote || node.uservote != 'Y') {
						voteforimg.onclick = function (){ nodeVote(this) };
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
					}
					toolbarDiv.innerHTML += '<b><span id="nodevotefor'+node.nodeid+'">'+node.positivevotes+'</span></b>';
				} else {
					voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_LOGIN_HINT; ?>');
					toolbarDiv.innerHTML += '<b><span id="nodevotefor'+node.nodeid+'">'+node.positivevotes+'</span></b>';
				}
			}

			// vote against
			var voteagainstimg = document.createElement('img');
			voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-grey3.png"); ?>');
			voteagainstimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_AGAINST_ICON_ALT; ?>');
			voteagainstimg.setAttribute('id', 'nodeagainst'+node.nodeid);
			voteagainstimg.nodeid = node.nodeid;
			voteagainstimg.vote='N';
			toolbarDiv.appendChild(voteagainstimg);
			if (!node.negativevotes) {
				node.negativevotes = 0;
			}
			if (issueClosed) {
				toolbarDiv.innerHTML += '<b><span id="nodevoteagainst'+node.nodeid+'">'+node.negativevotes+'</span></b>';
			} else {
				if(USER != ""){
					voteagainstimg.style.cursor = 'pointer';
					if (node.uservote && node.uservote == 'N') {
						voteagainstimg.onclick = function (){ deleteNodeVote(this) };
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled3.png"); ?>');
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!node.uservote || node.uservote != 'N') {
						voteagainstimg.onclick = function (){ nodeVote(this) };
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
					}
					toolbarDiv.innerHTML += '<b><span id="nodevoteagainst'+node.nodeid+'">'+node.negativevotes+'</span></b>';
				} else {
					voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_LOGIN_HINT; ?>');
					toolbarDiv.innerHTML += '<b><span id="nodevoteagainst'+node.nodeid+'">'+node.negativevotes+'</span></b>';
				}
			}
		}
	}

	if (mainheading) {
		var jsonldButton = document.createElement("span", {'title':'<?php echo $LNG->GRAPH_JSONLD_HINT;?>'});
		var jsonldButtonicon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('json-ld-data-24.png'); ?>", 'border':'0', 'alt':'<?php echo $LNG->GRAPH_JSONLD_HINT;?>'});
		jsonldButton.appendChild(jsonldButtonicon);
		var jsonldButtonhandler = function() {
			var code = URL_ROOT+'api/views/'+NODE_ARGS['nodeid'];
			textAreaPrompt('<?php echo $LNG->GRAPH_JSONLD_MESSAGE; ?>', code, "", "", "");
		};
		jsonldButton.onclick = jsonldButtonhandler;
		toolbarDiv.appendChild(jsonldButton);
	}

	iDiv.appendChild(nodetableDiv);

	var countdowntableDiv = "";

	var phase = "";
	if (NODE_ARGS['currentphase']) {
		phase = NODE_ARGS['currentphase'];
	} else {
		phase = calculateIssuePhase(node);
	}

	if (phase == PENDING_PHASE) {
		var start = convertUTCTimeToLocalDate(node.startdatetime);
		countdowntableDiv = document.createElement("div", {'class':'issue-status issuepending d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		var countdownbar = document.createElement("div", {'id':'timer'+node.nodeid} );
		countdowntableDiv.appendChild(countdownbar);
		iDiv.appendChild(countdowntableDiv);
		countDownIssueTimer(start.getTime(), countdownbar, '<?php echo $LNG->NODE_COUNTDOWN_START; ?>', false);
	} else if (phase == TIMED_PHASE || phase == TIMED_NOVOTE_PHASE
			|| phase == TIMED_VOTEPENDING_PHASE || phase == TIMED_VOTEON_PHASE
			|| phase == DISCUSS_PHASE || phase == REDUCE_PHASE || phase == DECIDE_PHASE ) {

		var end = convertUTCTimeToLocalDate(node.enddatetime);
		countdowntableDiv = document.createElement("div", {'class':'issue-status issueopen d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		let countdownbar = document.createElement("div", {'id':'timer'+node.nodeid} );
		countdowntableDiv.appendChild(countdownbar);
		iDiv.appendChild(countdowntableDiv);

		if (mainheading) {
			countDownIssueTimer(end.getTime(), countdownbar, '<?php echo $LNG->NODE_COUNTDOWN_END; ?>', false);
		} else {
			countdownbar.innerHTML += "<?php echo $LNG->NODE_COUNTDOWN_TIMED; ?>";
		}
	} else if (phase == CLOSED_PHASE) {
		countdowntableDiv = document.createElement("div", {'class':'issue-status issueclosed d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		var countdownbar = document.createElement("div", {'id':'timer'+node.nodeid} );
		countdownbar.innerHTML += "<?php echo $LNG->NODE_COUNTDOWN_CLOSED; ?>";
		countdowntableDiv.appendChild(countdownbar);
		iDiv.appendChild(countdowntableDiv);
	} else if (phase == OPEN_PHASE
			|| phase == OPEN_VOTEPENDING_PHASE || phase == OPEN_VOTEON_PHASE) {
		countdowntableDiv = document.createElement("div", {'class':'issue-status issueopen d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		var countdownbar = document.createElement("div", {'id':'timer'+node.nodeid} );
		countdownbar.innerHTML += "<?php echo $LNG->NODE_COUNTDOWN_OPEN; ?>";
		countdowntableDiv.appendChild(countdownbar);
		iDiv.appendChild(countdowntableDiv);
	}

	if (countdowntableDiv != "") {
		addIssuePhase(phase, node, countdowntableDiv, mainheading);
	}

	if (includestats) {
		var statstableDiv = document.createElement("div", {'class':'card-footer debates border-0 bg-white py-0 text-center'});
		var statsTable = document.createElement( 'div', {'class':'nodetable'} );
		statstableDiv.appendChild(statsTable);

		var innerRowStats = document.createElement( 'div', {'class':'d-flex justify-content-between'} );
		statsTable.appendChild(innerRowStats);

		var innerStatsCellViews = document.createElement( 'div', {'class':'col-auto'} );
		innerRowStats.appendChild(innerStatsCellViews);

		var viewslabelspan = document.createElement("strong");
		viewslabelspan.innerMHTL += '<?php echo $LNG->DEBATE_BLOCK_STATS_VIEWS; ?>';
		innerStatsCellViews.appendChild(viewslabelspan);

		var viewnumspan = document.createElement("span", {'id':'debateviewstats'+node.nodeid});
		viewnumspan.innerHTML = node.viewcount;
		innerStatsCellViews.appendChild(viewnumspan);

		if ((NODE_ARGS['issueHasLemoning'] && NODE_ARGS['currentphase'] == DECIDE_PHASE)
				|| (NODE_ARGS['issueHasLemoning'] && NODE_ARGS['currentphase'] == CLOSED_PHASE)) {
			var innerStatsCellDebates = document.createElement( 'div', {'class':'col-auto'} );
			innerRowStats.appendChild(innerStatsCellDebates);
			var idealabelspan = document.createElement("strong");
			idealabelspan.innerHTML +=' <?php echo $LNG->DEBATE_BLOCK_STATS_ISSUES_ALL; ?>';
			innerStatsCellDebates.appendChild(idealabelspan);
			var ideanumspan = document.createElement("span", {'id':'debatestatsideas'+node.nodeid});
			ideanumspan.innerHTML += '-';
			innerStatsCellDebates.appendChild(ideanumspan);

			var innerStatsCellDebatesNow = document.createElement( 'div', {'class':'col-auto'} );
			innerRowStats.appendChild(innerStatsCellDebatesNow);
			var idealabelspan = document.createElement("strong");
			idealabelspan.innerHTML += ' <?php echo $LNG->DEBATE_BLOCK_STATS_ISSUES_REMAINING; ?>';
			innerStatsCellDebatesNow.appendChild(idealabelspan);
			var ideanumspan = document.createElement("span", {'id':'debatestatsideasnow'+node.nodeid});
			ideanumspan.innerHTML += '-';
			innerStatsCellDebatesNow.appendChild(ideanumspan);
		} else {
			var innerStatsCellDebates = document.createElement( 'div', {'class':'col-auto'} );
			innerRowStats.appendChild(innerStatsCellDebates);
			var idealabelspan = document.createElement("strong");
			idealabelspan.innerHTML += ' <?php echo $LNG->DEBATE_BLOCK_STATS_ISSUES; ?>';
			innerStatsCellDebates.appendChild(idealabelspan);
			var ideanumspan = document.createElement("span", {'id':'debatestatsideas'+node.nodeid});
			ideanumspan.innerHTML += '-';
			innerStatsCellDebates.appendChild(ideanumspan);
		}

		var innerStatsCellPeople = document.createElement( 'div', {'class':'col-auto'} );
		innerRowStats.appendChild(innerStatsCellPeople);

 		var peoplelabelspan = document.createElement("strong");
		peoplelabelspan.innerHTML += ' <?php echo $LNG->DEBATE_BLOCK_STATS_PEOPLE; ?>';
		innerStatsCellPeople.appendChild(peoplelabelspan);

		var peoplenumspan = document.createElement("span", {'id':'debatestatspeople'+node.nodeid});
		peoplenumspan.innerHTML += '-';
		innerStatsCellPeople.appendChild(peoplenumspan);
		peoplenumspan.people = new Array();
		peoplenumspan.people.push(user.userid);

		var innerStatsCellVotes = document.createElement( 'div', {'class':'col-auto'} );
		innerRowStats.appendChild(innerStatsCellVotes);

		var votelabelspan = document.createElement("strong");
		votelabelspan.innerHTML += '<?php echo $LNG->DEBATE_BLOCK_STATS_VOTES; ?>';
		innerStatsCellVotes.appendChild(votelabelspan);

		var votenumspan = document.createElement("span", {'id':'debatestatsvotes'+node.nodeid});
		votenumspan.innerHTML += '-';
		innerStatsCellVotes.appendChild(votenumspan);
		votenumspan.votes = new Array();

		if (mainheading) {
			//loadStats(node.nodeid, peoplenumspan, ideanumspan, votenumspan);
		} else {
			loadStats(node.nodeid, peoplenumspan, ideanumspan, votenumspan);
		}
		iDiv.appendChild(statstableDiv);
	}

	return iDiv;
}

/**
 * Render the given node from an associated idea connection.
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 * @param type defaults to 'active', but can be 'inactive' so nothing is clickable
 * 			or a specialized type for some of the popups
 * @param status, active nodes or retired nodes. (active = 0, spam = 1, retired = 2.
 */
function renderIdeaList(node, uniQ, role, includeUser, type, status, i){

	if (i === undefined) {
	i = -1;
	}

	if (type === undefined) {
		type = "active";
	}

	if(role === undefined){
		role = node.role[0].role;
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

	var nodeuser = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		nodeuser = node.users[0];
	} else {
		nodeuser = node.users[0].user;
	}
	var user = null;
	var connection = node.connection;
	if (connection) {
		user = connection.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var focalrole = "";
	if (connection) {
		var fN = connection.from[0].cnode;
		var tN = connection.to[0].cnode;
		if (node.nodeid == fN.nodeid) {
			focalrole = tN.role[0].role;
		} else {
			focalrole = fN.role[0].role;
		}
	}

	var itDiv = document.createElement("div", {'class':'idea-title boxshadowsquaredarker'});

	if (USER != "" && NODE_ARGS['currentphase'] == REDUCE_PHASE) {
		itDiv.ondragover = function(e) {
			e.dataTransfer.dropEffect = 'copy';
			e.preventDefault();
			e.stopPropagation();
			return true;
		};

		itDiv.ondragenter = function(e){
			e.preventDefault();
			e.stopPropagation();
			return true;
		};

		itDiv.ondrop = function(e) {
			var name = e.dataTransfer.getData('text');
			e.preventDefault();
			e.stopPropagation();
			if (name == 'addlemon') {
				document.body.style.cursor = 'wait';
				var callback = function () {
					var lemoncount = parseInt(document.getElementById('lemoncount'+node.nodeid).innerHTML);
					if (lemoncount == 0) {
						document.getElementById('lemondiv'+node.nodeid).style.display = 'block';
					}
					document.getElementById('lemoncount'+node.nodeid).innerHTML = lemoncount + 1;
					document.getElementById('lemonbasketcount').innerHTML = parseInt(document.getElementById('lemonbasketcount').innerHTML) - 1;
					document.body.style.cursor = 'default';
				}
				lemonNode(node.nodeid, NODE_ARGS['nodeid'], callback);
				return true;
			} else {
				return false;
			}
		};
	}

	var nodeTable = document.createElement('table', {'class':'table'});
	nodeTable.className = "toConnectionsTable";
	itDiv.appendChild(nodeTable);

	var row = nodeTable.insertRow(-1);
	row.setAttribute('name','idearowitem');
	row.setAttribute('id','idearowitem'+uniQ);
	row.setAttribute('uniQ',uniQ);
	row.setAttribute('nodeid',node.nodeid);

	if (NODE_ARGS['currentphase'] == CLOSED_PHASE) {
		if (i == 0) {
			var winCell = row.insertCell(-1);
			winCell.setAttribute('vAlign','top');
			var img = document.createElement("img", {'id':node.nodeid});
			img.src = "<?php echo $HUB_FLM->getImagePath('first-place.png'); ?> ";
			img.alt = "1st place ";
			winCell.appendChild(img);
		} else if (i == 1) {
			var winCell = row.insertCell(-1);
			winCell.setAttribute('vAlign','top');
			var img = document.createElement("img", {'id':node.nodeid});
			img.src = "<?php echo $HUB_FLM->getImagePath('second-place.png'); ?> ";
			img.alt = "2nd place ";
			winCell.appendChild(img);
		} else if (i == 2) {
			var winCell = row.insertCell(-1);
			winCell.setAttribute('vAlign','top');
			var img = document.createElement("img", {'id':node.nodeid});
			img.src = "<?php echo $HUB_FLM->getImagePath('third-place.png'); ?> ";
			img.alt = "3rd place ";
			winCell.appendChild(img);
		}
	}

	if (node.nodeid == NODE_ARGS['selectednodeid']) {
		var options = new Array();
		options['startcolor'] = '#FAFB7D';
		options['endcolor'] = '#FDFDE3';
		options['restorecolor'] = 'transparent';
		options['duration'] = 5;
		highlightElement(row, options);
	} else {
		row.className = "transparent";
	}

	// FOR ORGANIZE MODE
	<?php if (isset($_SESSION['IS_MODERATOR']) && $_SESSION['IS_MODERATOR']){ ?>
	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var boxCell = row.insertCell(-1);
		boxCell.style.display='none';
		boxCell.setAttribute('class','nodecheckcell');
		boxCell.setAttribute('name','nodecheckcell');
		boxCell.setAttribute('id','nodecheckcell'+uniQ);
		boxCell.userid = nodeuser.userid;
		boxCell.nodeid = node.nodeid;
		var inChk = document.createElement("input",{'class':'nodecheck','type':'checkbox','id':'nodecheck'+node.nodeid, 'value':node.nodeid, 'aria-label':'test'});
		inChk.onclick = function () {
			var toAdd = getSelectedNodeIDs(document.getElementById('tab-content-idea-list'));
			if(toAdd.length < 2) {
				document.getElementById('mergeideadiv').style.display='none';
			}
		};
		boxCell.appendChild(inChk);
	}
	<?php } ?>

	//update stats
	if (node.parentid) {
		if (connection) {
			var votestats = document.getElementById('debatestatsvotes'+node.parentid);
			if (votestats) {
				votestats.votes[node.nodeid] = parseInt(parseInt(connection.positivevotes)+parseInt(connection.negativevotes));
			}
		}
	}

	// FOR VOTE MODE
	if (type == 'active') {
		if (connection) {
			var voteCell = row.insertCell(-1);
			voteCell.setAttribute('style','display:none;');
			voteCell.setAttribute('name','ideavotediv');
			voteCell.setAttribute('id','ideavotediv'+uniQ);
			voteCell.setAttribute('class','ideavotediv');
			
			if (NODE_ARGS['issueVoting'] || NODE_ARGS["currentphase"] == CLOSED_PHASE) {
				voteCell.style.display = "table-cell";
			} else {
				voteCell.style.display = "none";
			}

			var voteDiv = document.createElement("div", {
				'name':'editformvotedividea',
				'class':'editformvotedividea',
				'id':'editformvotedividea'+uniQ
			});
			voteCell.appendChild(voteDiv);

			var toRoleName = getNodeTitleAntecedence(connection.torole[0].role.name, false);

			// vote for
			var voteforimg = document.createElement('img');
			voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-grey.png"); ?>');
			voteforimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_FOR_ICON_ALT; ?>');
			voteforimg.setAttribute('id', connection.connid+'for');
			voteforimg.nodeid = node.nodeid;
			voteforimg.connid = connection.connid;

			voteforimg.actiontype = type;
			voteforimg.includeUser = includeUser;
			voteforimg.status = status;
			voteforimg.uniq = uniQ;

			voteforimg.vote='Y';
			voteDiv.appendChild(voteforimg);
			if (!connection.positivevotes) {
				connection.positivevotes = 0;
			}

			if (NODE_ARGS['issueVoting']) {
				if(USER != ""){
					if (nodeuser.userid == USER) {
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_OWN_HINT; ?>');
					} else {
						voteforimg.style.cursor = 'pointer';
						voteforimg.handler = function() {
							var votevar = document.getElementById('votebardiv'+uniQ);
							var other = document.getElementById(this.connid+'against');
							// switching vote from against to for
							if (other.src == '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>') {
								votevar.negativevotes = parseInt(votevar.negativevotes)-1;
								votevar.positivevotes = parseInt(votevar.positivevotes)+1;
							} else { // adding new for
								votevar.positivevotes = parseInt(votevar.positivevotes)+1;
							}
							drawVotesBar(votevar, uniQ, node.parentid);
							recalculatePeople();
							//promptForArgument(node, uniQ, 'pro', "Pro", type, includeUser, status);
						}
						voteforimg.handlerdelete = function() {
							var votevar = document.getElementById('votebardiv'+uniQ);
							votevar.positivevotes = parseInt(votevar.positivevotes)-1;
							drawVotesBar(votevar, uniQ, node.parentid);
							recalculatePeople();
						}
						voteforimg.oldtitle = '<?php echo $LNG->NODE_VOTE_FOR_SOLUTION_HINT; ?> '+toRoleName;
						if (connection.uservote && connection.uservote == 'Y') {
							voteforimg.onclick = function () {
								deleteConnectionVote(this)
							};
							voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>');
							voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
						} else if (!connection.uservote || connection.uservote != 'Y') {
							voteforimg.onclick = function () {
								connectionVote(this);
							};

							voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
							voteforimg.setAttribute('title', voteforimg.oldtitle);
						}
					}
				} else {
					voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_LOGIN_HINT; ?>');
				}
			}

			var voteforcount = document.createElement('span');
			voteforcount.setAttribute('id',connection.connid+'votefor');
			voteforcount.setAttribute('class',' ');
			voteforcount.innerHTML += connection.positivevotes;
			voteDiv.appendChild(voteforcount);


			// vote against
			var voteagainstimg = document.createElement('img');
			voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-grey.png"); ?>');
			voteagainstimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_AGAINST_ICON_ALT; ?>');
			voteagainstimg.setAttribute('id', connection.connid+'against');
			voteagainstimg.nodeid = node.nodeid;
			voteagainstimg.connid = connection.connid;
			voteagainstimg.vote='N';
			voteDiv.appendChild(voteagainstimg);
			if (!connection.negativevotes) {
				connection.negativevotes = 0;
			}

			if (NODE_ARGS['issueVoting']) {
				if(USER != ""){
					if (nodeuser.userid == USER) {
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_OWN_HINT; ?>');
					} else {
						voteagainstimg.style.cursor = 'pointer';
						voteagainstimg.handler = function() {
							var votebar = document.getElementById('votebardiv'+uniQ);
							var other = document.getElementById(his.connid+'for');
							//switching from for against vote to for vote
							if (other.src == '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>') {
								votebar.negativevotes = parseInt(votebar.negativevotes)+1;
								votebar.positivevotes = parseInt(votebar.positivevotes)-1;
							} else { // new for vote
								votebar.negativevotes = parseInt(votebar.negativevotes)+1;
							}
							drawVotesBar(votebar, uniQ, node.parentid);
							recalculatePeople();
						}
						voteagainstimg.handlerdelete = function() {
							var votebar = document.getElementById('votebardiv'+uniQ);
							votebar.negativevotes = parseInt(votebar.negativevotes)-1;
							drawVotesBar(votebar, uniQ, node.parentid);
							recalculatePeople();
						}

						voteagainstimg.oldtitle = '<?php echo $LNG->NODE_VOTE_AGAINST_SOLUTION_HINT; ?> '+toRoleName;
						if (connection.uservote && connection.uservote == 'N') {
							voteagainstimg.onclick = function () {
								deleteConnectionVote(this)
							};
							voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>');
							voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
						} else if (!connection.uservote || connection.uservote != 'N') {
							voteagainstimg.onclick = function () {
								connectionVote(this);
							};
							voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
							voteagainstimg.setAttribute('title', voteagainstimg.oldtitle);
						}
					}
				} else {
					voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_LOGIN_HINT; ?>');
				}
			}

			var voteagainstcount = document.createElement('span');
			voteagainstcount.setAttribute('id',connection.connid+'voteagainst');
			voteagainstcount.setAttribute('class',' ');
			voteagainstcount.innerHTML = connection.negativevotes;
			voteDiv.appendChild(voteagainstcount);
		}
	}

	if (includeUser == true) {
		var userCell = row.insertCell(-1);
		userCell.classList.add("idea-user-img");
		
		if (connection) {
			var cDate = new Date(connection.creationdate*1000);
			var dStr = "<?php echo $LNG->NODE_ADDED_BY; ?> "+nodeuser.name+ " on "+cDate.format(DATE_FORMAT)
			userCell.title = dStr;
		}

		// Add right side with user image and date below
		var iuDiv = document.createElement("div", {
			'id':'editformuserdividea'+uniQ,
			'class':'idea-user2'
		});

		var userimageThumb = document.createElement('img',{'alt':nodeuser.name, 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.appendChild(userimageThumb);
			iuDiv.appendChild(imagelink);
		} else {
			iuDiv.appendChild(userimageThumb)
		}

		userCell.appendChild(iuDiv);
	}

	var textCell = row.insertCell(-1);
	textCell.classList.add("idea-section");

	var textDiv = document.createElement("div", {
		'id':'textdividea'+uniQ,
		'class':'textdividea'
	});
	textCell.appendChild(textDiv);
	
	var title = node.name;

	var textspan = document.createElement("span", {
		'id':'desctoggle'+uniQ,
		'class':'desctoggle'
	});
	textspan.innerHTML += title;
	textspan.datadisabled = false;
	textDiv.appendChild(textspan);
	textspan.onclick = function () {
		if (textspan.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	};

	if (USER == nodeuser.userid && type == 'active' && NODE_ARGS['issueDiscussing']) {
		var editbutton = document.createElement("img", {			
			'class':'idea-edit',
			'src':'<?php echo $HUB_FLM->getImagePath("edit.png"); ?>',
			'title':'<?php echo $LNG->NODE_EDIT_SOLUTION_ICON_HINT; ?>',
		});
		textDiv.appendChild(editbutton);
		editbutton.onclick = function () {
			editInline(uniQ, 'idea');
		};

		if (!node.otheruserconnections || node.otheruserconnections == 0) {
			var deletename = node.name;
			var del = document.createElement('img',{		
				'class':'idea-delete',
				'alt':'<?php echo $LNG->DELETE_BUTTON_ALT;?>', 
				'title': '<?php echo $LNG->DELETE_BUTTON_HINT;?>', 
				'src': '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>'
			});
			del.onclick = function () {
				var callback = function () {
					refreshSolutions();
				}
				deleteNode(node.nodeid, deletename, role.name, callback);
			};
			textDiv.appendChild(del);
		} else {
			var del = document.createElement('img',{ 	
				'class':'idea-delete',
				'alt':'<?php echo $LNG->NO_DELETE_BUTTON_ALT;?>', 
				'title': '<?php echo $LNG->NO_DELETE_BUTTON_HINT;?>', 
				'src': '<?php echo $HUB_FLM->getImagePath("delete-off.png"); ?>'
			});
			textDiv.appendChild(del);
		}
	}

	<?php if ($CFG->SPAM_ALERT_ON) { ?>
	if (type == "active" && USER != "" && USER != nodeuser.userid) { // IF LOGGED IN AND NOT YOU
		textDiv.appendChild(createSpamButton(node, role));
	}
	<?php } ?>

	if (node.urls && node.urls.length > 0) {
		var menuButton = document.createElement('img',{
			'class':'idea-url',
			'alt':'>','width':'16','height':'16',
			'src': '<?php echo $HUB_FLM->getImagePath("nodetypes/Default/reference-32x32.png"); ?>'
		});
		textDiv.appendChild(menuButton);
		menuButton.onmouseout = function (event) {
			hideBox('toolbardiv'+uniQ);
		};
		menuButton.onmouseover = function (event) {
			var position = getPosition(this);
			var panel = document.getElementById('toolbardiv'+uniQ);
			var panelWidth = 200;

			var viewportHeight = getWindowHeight();
			var viewportWidth = getWindowWidth();

			var x = position.x;
			var y = position.y;

			if ( (x+panelWidth+30) > viewportWidth) {
				x = x-(panelWidth+30);
			} else {
				x = x+10;
			}

			x = x+30+getPageOffsetX();

			panel.style.left = x+"px";
			panel.style.top = y+"px";

			showBox('toolbardiv'+uniQ);
		};
		var toolbarDiv = document.createElement("div", {'id':'toolbardiv'+uniQ, 'style':'left:-1px;top:-1px;clear:both;position:absolute;display:none;z-index:60;padding:5px;width:200px;border:1px solid gray;background:white'} );
		toolbarDiv.onmouseout = function (event) {
			hideBox('toolbardiv'+uniQ);
		};
		toolbarDiv.onmouseover = function (event) { showBox('toolbardiv'+uniQ); };
		textDiv.appendChild(toolbarDiv);

		for(var i=0; i< node.urls.length; i++){
			if(node.urls[i].url){
				var next = node.urls[i].url;
				var url = next.url;
				var weblink = document.createElement("a", {'class':' ','target':'_blank'});
				weblink.href = url;
				weblink.innerHTML += url;
				toolbarDiv.appendChild(weblink);
			}
		}
	}

	if (type == 'active') {
		var more = document.createElement('img',{
			'class':'idea-built',
			'style':'display:none',
			'alt':'built from', 
			'title': 'built from', 'src': '<?php echo $HUB_FLM->getImagePath("desc.png"); ?>'
		});
		more.onclick = function () {
			loadDialog('builtfroms',URL_ROOT+"ui/popups/builtfroms.php?nodeid="+node.nodeid, 800,550);
		};
		textDiv.appendChild(more);
		loadBuiltFromsCount(more, node.nodeid);

		<?php if (isset($_SESSION['IS_MODERATOR']) && $_SESSION['IS_MODERATOR']){ ?>
		if (NODE_ARGS['issueDiscussing']) {
			var splitbutton = document.createElement("button", {
				'class':'idea-split',
				'id':'ideasplitbutton'+uniQ,
				'name':'ideasplitbutton',
				'style':'display:none',
				'title':'<?php echo $LNG->FORM_BUTTON_SPLIT_HINT; ?>'
			});
			splitbutton.uniQ = uniQ;
			if (node.haschildren) {
				splitbutton.hasChildren = node.haschildren;
			} else {
				splitbutton.hasChildren = true; // as it is unknown so cannot risk drawing split button
			}
			splitbutton.innerHTML += '<?php echo $LNG->FORM_BUTTON_SPLIT; ?>';
			textDiv.appendChild(splitbutton);
			splitbutton.onclick = function () {
				loadDialog('split',URL_ROOT+"ui/popups/split.php?nodeid="+node.nodeid+"&debateid="+node.parentid+"&groupid="+node.groupid, 770,550);
			};
		}
		<?php } ?>
	}

	// LEMONING
	if (USER != "" && NODE_ARGS['currentphase'] == REDUCE_PHASE) {
		var lemonigDiv = document.createElement('div', { 'style':'float:right;width:100px;', 'name':'lemondiv','id':'lemondiv'+node.nodeid});
		if (node.userlemonvote > 0) {
			lemonigDiv.style.display = 'block';
		} else {
			lemonigDiv.style.display = 'none';
		}

		var minuslemon = document.createElement('span', {'class':'lemoningbuttons'});
		minuslemon.innerHTML += '&#8211';
		minuslemon.onclick = function(e) {
			document.body.style.cursor = 'wait';
			var callback = function () {
				var lemoncount = parseInt(document.getElementById('lemoncount'+node.nodeid).innerHTML);
				if (lemoncount == 1) {
					document.getElementById('lemondiv'+node.nodeid).style.display = 'none';
				}
				document.getElementById('lemoncount'+node.nodeid).innerHTML = lemoncount - 1;
				document.getElementById('lemonbasketcount').innerHTML = parseInt(document.getElementById('lemonbasketcount').innerHTML) + 1;
				document.body.style.cursor = 'default';
			}
			unlemonNode(node.nodeid, NODE_ARGS['nodeid'], callback);
		};
		lemonigDiv.appendChild(minuslemon);

		var lemonimg = document.createElement('img', {'src':'<?php echo $HUB_FLM->getImagePath("lemon22.png"); ?>',  'class':'lemonimg' });
		lemonimg.setAttribute('draggable', 'true');
		lemonimg.ondragstart = function(e) {
			e.dataTransfer.setData("text", node.nodeid);
			e.dataTransfer.effectAllowed = "move";
		};
		lemonigDiv.appendChild(lemonimg);

		var lemoncountnum = document.createElement('span',{'id':'lemoncount'+node.nodeid, 'class':'lemoncount'});
		lemoncountnum.innerHTML += node.userlemonvote;
		lemonigDiv.appendChild(lemoncountnum);

		var pluslemon = document.createElement('span', {'class':'lemoningbuttons'});
		pluslemon.innerHTML +='+';
		pluslemon.onclick = function(e) {
			var remaininglemons = parseInt(document.getElementById('lemonbasketcount').innerHTML);
			if (remaininglemons > 0) {
				document.body.style.cursor = 'wait';
				var callback = function () {
					var lemoncount = parseInt(document.getElementById('lemoncount'+node.nodeid).innerHTML);
					var lemoncount = lemoncount + 1;
					if (lemoncount > 0) {
						document.getElementById('lemondiv'+node.nodeid).style.display = 'block';
					}
					document.getElementById('lemoncount'+node.nodeid).innerHTML = lemoncount;
					document.getElementById('lemonbasketcount').innerHTML = parseInt(document.getElementById('lemonbasketcount').innerHTML) - 1;
					document.body.style.cursor = 'default';
				}
				lemonNode(node.nodeid, NODE_ARGS['nodeid'], callback);
			} else {
				alert('<?php echo $LNG->LEMONING_COUNT_FINISHED; ?>');
			}
		};
		lemonigDiv.appendChild(pluslemon);

		textDiv.appendChild(lemonigDiv);
	}

	//if (status != 2 &&
	if (NODE_ARGS['currentphase'] == DISCUSS_PHASE
			|| NODE_ARGS['currentphase'] == OPEN_PHASE
			|| NODE_ARGS['currentphase'] == OPEN_VOTEON_PHASE
			|| NODE_ARGS['currentphase'] == OPEN_VOTEPENDING_PHASE
			|| NODE_ARGS['currentphase'] == TIMED_PHASE
			|| NODE_ARGS['currentphase'] == TIMED_VOTEON_PHASE
			|| NODE_ARGS['currentphase'] == TIMED_VOTEPENDING_PHASE
			|| NODE_ARGS['currentphase'] == DISCUSS_PHASE
			|| NODE_ARGS['currentphase'] == REDUCE_PHASE
		) {
		var votebarDiv = document.createElement('div',{'name':'votebardiv','id':'votebardiv'+uniQ,'class':'votebar'});
		votebarDiv.positivevotes = parseInt(connection.positivevotes);
		votebarDiv.negativevotes = parseInt(connection.negativevotes);
		votebarDiv.conpositivevotes = 0;
		votebarDiv.connegativevotes = 0;
		votebarDiv.propositivevotes = 0;
		votebarDiv.pronegativevotes = 0;
		votebarDiv.procount = 0;
		votebarDiv.concount = 0;
		textDiv.appendChild(votebarDiv);
		drawVotesBar(votebarDiv, uniQ, node.parentid);
	}

	if(node.description || node.hasdesc){
		var dStr = '<div class="idea-desc" id="desc'+uniQ+'div">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.innerHTML += dStr;
	}

	var argumentLink = document.createElement("span", {
		'name':'ideaargumentlink',
		'id':'ideaargumentlink'+uniQ,
		'class':'active ideaargumentlink',
		'title':'<?php echo $LNG->IDEA_ARGUMENTS_HINT; ?>',
	});
	argumentLink.nodeid = node.nodeid;
	argumentLink.datadisabled = false;
	argumentLink.onclick = function () {
		if (argumentLink.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	};

	<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']) { ?>
	if (USER == '' && NODE_ARGS['issueDiscussing']) {
		argumentLink.innerHTML +='<img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="width:16px;height:16px;vertical-align:bottom;padding-right:3px;" />';
	}
	<?php } ?>

	argumentLink.innerHTML += '<?php echo $LNG->IDEA_ARGUMENTS_LINK; ?> (';
	var argumentCount = document.createElement("span", {
		'id':'ideaargumentcount'+node.nodeid,
	});

	//alert(node.childrencount);

	argumentCount.innerHTML += node.childrencount;
	argumentLink.appendChild(argumentCount);
	argumentLink.innerHTML += ')';

	textDiv.appendChild(argumentLink);

	var commentLink = document.createElement("span", {
		'name':'ideacommentlink',
		'id':'ideacommentlink'+uniQ,
		'class':'active ideacommentlink',
		'title':'<?php echo $LNG->IDEA_COMMENTS_HINT; ?>',
	});
	commentLink.nodeid = node.nodeid;
	commentLink.datadisabled = false;
	commentLink.onclick = function () {
		if (commentLink.datadisabled == false) {
			ideatoggle('comments'+uniQ, uniQ, node.nodeid, 'comments', role.name);
		}
	};

	commentLink.innerHTML += '<?php echo $LNG->IDEA_COMMENTS_LINK; ?>';
	var commentCount = document.createElement("span", {'id':'ideacommentscount'+node.nodeid});
	commentCount.innerHTML += '0';
	commentLink.innerHTML += ' (';
	commentLink.appendChild(commentCount);
	commentLink.innerHTML += ')';

	textDiv.appendChild(commentLink);

	// IF NOT LOGGED IN AND IN DISCUSSION PHASE, ADD CONTRIBUTE BUTTON TO GO TO LOGIN
	<?php if (!isset($USER->userid)) {
		?>
	if (USER == '' && NODE_ARGS['issueDiscussing']) {
			var signinlink = document.createElement("a", {
				'href':'<?php echo $CFG->homeAddress."ui/pages/login.php?ref="; ?>'+NODE_ARGS["ref"],
				'title':'<?php echo $LNG->DEBATE_CONTRIBUTE_LINK_HINT; ?>',
				'class':'lightgreenbutton',
				'style':'margin-right: 15px;',
			});
			signinlink.innerHTML += '<?php echo $LNG->DEBATE_CONTRIBUTE_LINK_TEXT; ?>';
			textDiv.appendChild(signinlink);
		}
	<?php } ?>

	/** ADD THE EDIT FORM FOR THE IDEA **/
	if (USER == user.userid && type == 'active' && NODE_ARGS['issueDiscussing']) {
		var editDiv = document.createElement("fieldset", {
			'class':'editformdividea',
			'name':'editformdividea',
			'id':'editformdividea'+uniQ,
			'style':'display:none;'
		});

		var legend = document.createElement("legend", {});
		var legendtitle = document.createElement("h2", {'class':'editing-header',});
		legendtitle.innerHTML += '<?php echo $LNG->EXPLORE_EDITING_ARGUMENT_TITLE; ?>';
		legend.appendChild(legendtitle);
		editDiv.appendChild(legend);

		var editideaid = document.createElement("input", {
			'name':'editideaid',
			'id':'editideaid'+uniQ,
			'type':'hidden',
			'value':node.nodeid,
		});
		editDiv.appendChild(editideaid);
		var editnodetypeid = document.createElement("input", {
			'name':'editideanodetypeid',
			'id':'editideanodetypeid'+uniQ,
			'type':'hidden',
			'value':role.roleid,
		});
		editDiv.appendChild(editnodetypeid);

		var rowDiv1 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		editDiv.appendChild(rowDiv1);
		var editideaname = document.createElement("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
			'id':'editideaname'+uniQ,
			'name':'editideaname',
			'value':node.name,
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
		});
		rowDiv1.appendChild(editideaname);

		var rowDiv2 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		editDiv.appendChild(rowDiv2);
		var editideadesc = document.createElement("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
			'id':'editideadesc'+uniQ,
			'name':'editideadesc',
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
		});
		editideadesc.innerHTML+=node.description;
		rowDiv2.appendChild(editideadesc);

		var rowDiv4 = document.createElement("div", {
			'class':'mb-2',
			'id':'linksdivedit'+uniQ,
		});
		rowDiv4.linkcount = 0;
		editDiv.appendChild(rowDiv4);

		if (node.urls && node.urls.length > 0) {
			rowDiv4.linkcount = node.urls.length-1;
			for(var i=0; i< node.urls.length; i++){
				if(node.urls[i].url){
					var next = node.urls[i].url;
					var urlid = next.urlid;
					var url = next.url;
					var weblink = document.createElement("input", {
						'class':'form-control',
						'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
						'id':'argumentlinkedit'+uniQ+i,
						'name':'argumentlinkedit'+uniQ+'[]',
						'value':url,
						'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
					});
					weblink.urlid = urlid;
					rowDiv4.appendChild(weblink);
				}
			}
		} else {
			rowDiv4.linkcount = 0;
			var weblink = document.createElement("input", {
				'class':'form-control',
				'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
				'id':'argumentlinkedit'+uniQ+0,
				'name':'argumentlinkedit'+uniQ+'[]',
				'value':'',
				'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			});
			rowDiv4.appendChild(weblink);
		}

		var rowDiv5 = document.createElement("div", {'class':'my-3'});
		editDiv.insappendChildert(rowDiv5);
		var addURL = document.createElement("a", {
			'href':'javascript:void(0)',
			'class':'hgrinput',
		});
		addURL.innerHTML += '<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>';
		addURL.onclick = function () {
			insertIdeaLink(uniQ, 'edit');
		};
		rowDiv5.appendChild(addURL);
		var rowDiv3 = document.createElement("div", {
			'class':'formrowsm',
		});
		editDiv.appendChild(rowDiv3);
		var editideasave = document.createElement("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'editidea',
			'name':'editidea',
			'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>',
		});
		editideasave.onclick = function () {
			editIdeaNode(node, uniQ, 'idea', type, includeUser, status);
		};

		rowDiv3.appendChild(editideasave);
		var editideacancel = document.createElement("input", {
			'type':'button',
			'class':'btn btn-secondary ms-2',
			'id':'cancelidea',
			'name':'editidea',
			'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>',
		});
		editideacancel.onclick = function () {
			cancelEditAction(uniQ, 'idea');
		};

		rowDiv3.appendChild(editideacancel);

		textCell.appendChild(editDiv);
	}

	/** COMMENTS LIST **/
	var expandDiv = document.createElement("div", {
		'name':'commentsdiv',
		'id':'commentsdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'comments'+uniQ,
		'class':'ideadata',
		'style':'display:none;'
	});
	itDiv.appendChild(expandDiv);

	var kidscommentTable = document.createElement('table', {'style':'width:100%'});
	expandDiv.appendChild(kidscommentTable);
	var rowcomment = kidscommentTable.insertRow(-1);
	var commentCell = rowcomment.insertCell(-1);

	var commentHeading = document.createElement('h3');
	commentHeading.style.marginBottom = "2px";
	commentHeading.style.marginTop = "5px";
	commentHeading.style.color = "black";
	commentHeading.style.fontWeight = "normal";
	commentHeading.innerHTML += '<?php echo $LNG->IDEA_COMMENTS_CHILDREN_TITLE; ?> (';
	var commentCount = document.createElement('span', {'id':'count-comment'+uniQ});
	commentCount.innerHTML += '0';
	commentHeading.appendChild(commentCount);
	commentHeading.innerHTML += ')';
	commentCell.appendChild(commentHeading);

	var commentKidsDiv = document.createElement('div', {
		'id':'commentslist'+uniQ,
		'style':'width:100%;',
	});
	commentKidsDiv.style.borderTop = "1px solid #D8D8D8";
	commentCell.appendChild(commentKidsDiv);

	<?php if (isset($_SESSION['IS_MODERATOR']) && $_SESSION['IS_MODERATOR']){ ?>

	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var addCommentDiv = document.createElement("div", {
			'name':'addformdivcomment',
			'id':'addformdivcomment'+uniQ,
		});

		var rowDiv1 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		addCommentDiv.appendChild(rowDiv1);

		var addcommentname = document.createElement("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
			'id':'addcommentname'+uniQ,
			'name':'addcommentname',
			'value':'',
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
		});
		rowDiv1.appendChild(addcommentname);

		var rowDiv2 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		addCommentDiv.appendChild(rowDiv2);
		var addcommentdesc = document.createElement("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
			'id':'addcommentdesc'+uniQ,
			'name':'addcommentdesc',
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
		});
		rowDiv2.appendChild(addcommentdesc);

		var rowDiv3 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		addCommentDiv.appendChild(rowDiv3);
		var addcommentsave = document.createElement("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'addcomment',
			'name':'addcomment',
			'value':'<?php echo $LNG->FORM_BUTTON_SUBMIT; ?>',
		});
		addcommentsave.onclick = function () {
			addCommentNode(node, uniQ, 'comment', type, includeUser, status);
		};
		rowDiv3.appendChild(addcommentsave);

		commentCell.appendChild(addCommentDiv);
	}
	<?php } ?>

	/** PRO AND CON LISTS **/
	var kidsTable = document.createElement('table', {
		'name':'ideaforagainstdiv',
		'id':'ideaforagainstdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'arguments'+uniQ,
		'class':'ideaforagainsttable table',
		'style':'display:none;'
	});
	itDiv.appendChild(kidsTable);

	var row = kidsTable.insertRow(-1);
	row.width="100%";

	var forCell = row.insertCell(-1);
	forCell.vAlign = "top";
	forCell.align = "left";
	forCell.className = "for-against";
	
	var forHeading = document.createElement('h3');
	forHeading.classList.add("forHeading");
	forHeading.inneTRML += '<?php echo $LNG->NODE_CHILDREN_EVIDENCE_PRO; ?> (';
	var forCount = document.createElement('span', {'id':'count-support'+uniQ});
	forCount.innerHTML += '0';
	forHeading.appendChild(forCount);
	forHeading.innerHTML += ')';
	forCell.appendChild(forHeading);

	var forKidsDiv = document.createElement('div', {'id':'supportkidsdiv'+uniQ});
	forCell.appendChild(forKidsDiv);

	<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']){ ?>
	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var addProDiv = document.createElement("div", {
			'name':'addformdivpro',
			'id':'addformdivpro'+uniQ,
		});

		var rowDiv1 = document.createElement("div", {
			'class':'formrowsm mt-2 mb-2',
		});
		addProDiv.appendChild(rowDiv1);

		var addproname = document.createElement("input", {
			'aria-label':'<?php echo $LNG->FORM_PRO_LABEL_TITLE; ?>',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_PRO_LABEL_TITLE; ?>',
			'id':'addproname'+uniQ,
			'name':'addproname',
			'value':'',
		});
		rowDiv1.appendChild(addproname);

		var rowDiv2 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		addProDiv.appendChild(rowDiv2);
		var addprodesc = document.createElement("textarea", {
			'aria-label':'<?php echo $LNG->FORM_PRO_LABEL_DESC; ?>',
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_PRO_LABEL_DESC; ?>',
			'id':'addprodesc'+uniQ,
			'name':'addprodesc',
		});
		rowDiv2.appendChild(addprodesc);

		var rowDiv3 = document.createElement("div", {
			'class':'formrowsm',
			'id':'linksdivpro'+uniQ,
		});
		rowDiv3.linkcount = 0;
		addProDiv.appendChild(rowDiv3);

		var weblink = document.createElement("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			'id':'argumentlinkpro'+uniQ+0,
			'name':'argumentlinkpro'+uniQ+'[]',
			'value':'',
			'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
		});
		rowDiv3.appendChild(weblink);

		var rowDiv4 = document.createElement("div", {
			'class':'my-3',
		});
		addProDiv.appendChild(rowDiv4);
		var addURL = document.createElement("a", {
			'class':'hgrinput',
			'href':'javascript:void(0)',
		});
		addURL.innerHTML += '<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>';
		addURL.onclick = function () {
			insertArgumentLink(uniQ, 'pro');
		};
		rowDiv4.appendChild(addURL);

		var rowDiv5 = document.createElement("div", {
			'class':'formrowsm',
		});
		addProDiv.appendChild(rowDiv5);
		var addprosave = document.createElement("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'addprosave',
			'name':'addprosave',
			'value':'<?php echo $LNG->FORM_BUTTON_SUBMIT; ?>',
		});
		addprosave.onclick = function () {
			addArgumentNode(node, uniQ, 'pro', 'Pro', type, includeUser, status);
		};
		rowDiv5.appendChild(addprosave);

		forCell.appendChild(addProDiv);
	}
	<?php } ?>

	var conCell = row.insertCell(-1);
	conCell.vAlign = "top";
	conCell.align = "left";
	conCell.className = "for-against";

	var conHeading = document.createElement('h3', {'class':'conHeading'});
	conHeading.innerHTML += '<?php echo $LNG->NODE_CHILDREN_EVIDENCE_CON; ?> (';
	var conCount = document.createElement('span', {'id':'count-counter'+uniQ, 'class':'count-counter'});
	conCount.innerHTML += '0';
	conHeading.appendChild(conCount);
	conHeading.innerHTML += ')';
	conCell.appendChild(conHeading);

	var conKidsDiv = document.createElement('div', {'id':'counterkidsdiv'+uniQ });
	conCell.appendChild(conKidsDiv);

	<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']){ ?>
	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var addConDiv = document.createElement("div", {
			'name':'addformdivcon',
			'id':'addformdivcon'+uniQ,
		});

		var rowDiv1 = document.createElement("div", {
			'class':'formrowsm mt-2 mb-2',
		});
		addConDiv.appendChild(rowDiv1);

		var addconname = document.createElement("input", {
			'aria-label':'<?php echo $LNG->FORM_CON_LABEL_TITLE; ?>',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_CON_LABEL_TITLE; ?>',
			'id':'addconname'+uniQ,
			'name':'addconname',
			'value':'',
		});
		rowDiv1.appendChild(addconname);

		var rowDiv2 = document.createElement("div", {
			'class':'formrowsm mb-2',
		});
		addConDiv.appendChild(rowDiv2);
		var addcondesc = document.createElement("textarea", {
			'aria-label':'<?php echo $LNG->FORM_CON_LABEL_DESC; ?>',
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_CON_LABEL_DESC; ?>',
			'id':'addcondesc'+uniQ,
			'name':'addcondesc',
		});
		rowDiv2.appendChild(addcondesc);

		var rowDiv3 = document.createElement("div", {
			'class':'formrowsm',
			'id':'linksdivcon'+uniQ,
		});
		rowDiv3.linkcount = 0;
		addConDiv.appendChild(rowDiv3);

		var weblink = document.createElement("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			'id':'argumentlinkcon'+uniQ+0,
			'name':'argumentlinkcon'+uniQ+'[]',
			'value':'',
			'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
		});
		rowDiv3.appendChild(weblink);

		var rowDiv4 = document.createElement("div", {
			'class':'my-3',
		});
		addConDiv.appendChild(rowDiv4);
		var addURL = document.createElement("a", {
			'class':'hgrinput',
			'href':'javascript:void(0)',
		});
		addURL.innerHTML += '<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>';
		addURL.onclick = function () {
			insertArgumentLink(uniQ, 'con');
		};
		rowDiv4.appendChild(addURL);

		var rowDiv5 = document.createElement("div", {
			'class':'formrowsm',
		});
		addConDiv.appendChild(rowDiv5);
		var addconsave = document.createElement("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'addconsave',
			'name':'addconsave',
			'value':'<?php echo $LNG->FORM_BUTTON_SUBMIT; ?>',
		});
		addconsave.onclick = function () {
			addArgumentNode(node, uniQ, 'con', 'Con', type, includeUser, status);
		};
		rowDiv5.appendChild(addconsave);

		conCell.appendChild(addConDiv);
	}
	<?php } ?>

	loadChildComments(commentKidsDiv, node.nodeid, '<?php echo $LNG->COMMENTS_NAME; ?>', '<?php echo $CFG->LINK_COMMENT_NODE; ?>', 'Comment', node.parentid, node.groupid, uniQ, commentCount, type, status);
	if (!votebarDiv) {
		votebarDiv = ""
		loadChildArguments(forKidsDiv, node.nodeid, '<?php echo $LNG->PROS_NAME; ?>', '<?php echo $CFG->LINK_PRO_SOLUTION; ?>', 'Pro', node.parentid, node.groupid, uniQ, forCount, type, status, votebarDiv);
		loadChildArguments(conKidsDiv, node.nodeid, '<?php echo $LNG->CONS_NAME; ?>', '<?php echo $CFG->LINK_CON_SOLUTION; ?>', 'Con', node.parentid, node.groupid, uniQ, conCount, type, status, votebarDiv);
	} else {
		loadChildArguments(forKidsDiv, node.nodeid, '<?php echo $LNG->PROS_NAME; ?>', '<?php echo $CFG->LINK_PRO_SOLUTION; ?>', 'Pro', node.parentid, node.groupid, uniQ, forCount, type, status, votebarDiv);
		loadChildArguments(conKidsDiv, node.nodeid, '<?php echo $LNG->CONS_NAME; ?>', '<?php echo $CFG->LINK_CON_SOLUTION; ?>', 'Con', node.parentid, node.groupid, uniQ, conCount, type, status, votebarDiv);
	}

	return itDiv;
}

/**
 * Render the given node from an associated idea connection for removed ideas.
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 * @param status, active nodes or retired nodes. (active = 0, spam = 1, retired = 2.
 */
function renderIdeaRemovedList(node, uniQ, role, includeUser){

	var type = "inactive";
	var status = <?php echo $CFG->STATUS_ACTIVE; ?>;

	if(role === undefined){
		role = node.role[0].role;
	}

	var nodeuser = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		nodeuser = node.users[0];
	} else {
		nodeuser = node.users[0].user;
	}
	var user = null;
	var connection = node.connection;
	if (connection) {
		user = connection.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var focalrole = "";
	if (connection) {
		var fN = connection.from[0].cnode;
		var tN = connection.to[0].cnode;
		if (node.nodeid == fN.nodeid) {
			focalrole = tN.role[0].role;
		} else {
			focalrole = fN.role[0].role;
		}
	}

	var itDiv = document.createElement("div", {'class':'idea-title boxshadowsquaredarker'});

	var nodeTable = document.createElement('table', {'style':'width:100%;margin:3px;'});
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";
	itDiv.appendChild(nodeTable);

	var row = nodeTable.insertRow(-1);
	row.setAttribute('name','idearowitem');
	row.setAttribute('id','idearowitem'+uniQ);
	row.setAttribute('uniQ',uniQ);
	row.setAttribute('nodeid',node.nodeid);

	if (node.nodeid == NODE_ARGS['selectednodeid']) {
		var options = new Array();
		options['startcolor'] = '#FAFB7D';
		options['endcolor'] = '#FDFDE3';
		options['restorecolor'] = 'transparent';
		options['duration'] = 5;
		highlightElement(row, options);
	} else {
		row.className = "transparent";
	}

	if (includeUser == true) {
		var userCell = row.insertCell(-1);
		userCell.setAttribute('style','max-width:40px;');
		userCell.vAlign="top";
		userCell.align="left";
		userCell.width="40";
		if (connection) {
			var cDate = new Date(connection.creationdate*1000);
			var dStr = "<?php echo $LNG->NODE_ADDED_BY; ?> "+nodeuser.name+ " on "+cDate.format(DATE_FORMAT)
			userCell.title = dStr;
		}

		// Add right side with user image and date below
		var iuDiv = document.createElement("div", {
			'id':'editformuserdividea'+uniQ,
			'class':'idea-user2',
		});

		var userimageThumb = document.createElement('img',{'alt':nodeuser.name, 'style':'padding-left:5px;padding-top:5px;', 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.appendChild(userimageThumb);
			iuDiv.appendChild(imagelink);
		} else {
			iuDiv.appendChild(userimageThumb)
		}

		userCell.appendChild(iuDiv);
	}

	var textCell = row.insertCell(-1);
	textCell.vAlign="top";
	textCell.align="left";
	textCell.setAttribute('width','95%');

	var textDiv = document.createElement("div", {
		'id':'textdividea'+uniQ,
		'style':'clear:both;float:left;width:98%;display:block;padding-left:5px;padding-bottom:3px;'
	});
	textCell.appendChild(textDiv);

	var title = node.name;

	var textspan = document.createElement("span", {
		'id':'desctoggle'+uniQ,
		'style':'cursor:pointer;float:left;font-weight:bold;font-size:14pt'
	});
	textspan.innerHTML += title;
	textspan.datadisabled = false;
	textDiv.appendChild(textspan);
	textspan.onclick = function () {
		if (textspan.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	};

	// LEMONING
	var lemonigDiv = document.createElement('div', {'name':'lemondiv','id':'lemondiv'+node.nodeid,'style':'float:right;padding:0px;margin:0px;'});
	var lemonimg = document.createElement('img', {'src':'<?php echo $HUB_FLM->getImagePath("lemon22.png"); ?>',  'class':'lemonimgoff' });
	lemonigDiv.appendChild(lemonimg);
	var lemoncountnum = document.createElement('span',{'class':'lemoncount'});
	lemoncountnum.innerHTML += node.lemonvotes;
	lemonigDiv.appendChild(lemoncountnum);
	textDiv.appendChild(lemonigDiv);

	if(node.description || node.hasdesc){
		var dStr = '<div style="float:left;clear:both;margin:0px;padding:0px;margin-top:5px;font-size:10pt" class="idea-desc" id="desc'+uniQ+'div">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.innerHTML += dStr;
	}

	var argumentLink = document.createElement("span", {
		'name':'ideaargumentlink',
		'id':'ideaargumentlink'+uniQ,
		'class':'active',
		'style':'clear:both;float:left;display:block;font-size:10pt;margin-top:7px;font-weight:bold;',
		'title':'<?php echo $LNG->IDEA_ARGUMENTS_HINT; ?>',
	});
	argumentLink.nodeid = node.nodeid;
	argumentLink.datadisabled = false;
	argumentLink.onclick = function () {
		if (argumentLink.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	};

	argumentLink.innerHTML += '<?php echo $LNG->IDEA_ARGUMENTS_LINK; ?> (';
	var argumentCount = document.createElement("span", {
		'id':'ideaargumentcount'+node.nodeid,
	});

	//argumentCount.appendChild(node.childrencount);
	argumentLink.appendChild(argumentCount);
	argumentLink.innerHTML += ')';

	textDiv.appendChild(argumentLink);

	var commentLink = document.createElement("div", {
		'name':'ideacommentlink',
		'id':'ideacommentlink'+uniQ,
		'class':'active',
		'style':'float:left;display:block;font-size:10pt;margin-top:7px;font-weight:bold;margin-left:30px;',
		'title':'<?php echo $LNG->IDEA_COMMENTS_HINT; ?>',
	});
	commentLink.nodeid = node.nodeid;
	commentLink.datadisabled = false;
	commentLink.onclick = function () {
		if (commentLink.datadisabled == false) {
			ideatoggle('comments'+uniQ, uniQ, node.nodeid, 'comments', role.name);
		}
	};

	commentLink.innerHTML += '<?php echo $LNG->IDEA_COMMENTS_LINK; ?>';
	var commentCount = document.createElement("span", {'id':'ideacommentscount'+node.nodeid});
	commentCount.innerHTML += '0';
	commentLink.innerHTML += ' (';
	commentLink.appendChild(commentCount);
	commentLink.innerHTML +=')';

	textDiv.appendChild(commentLink);

	/** COMMENTS LIST **/
	var expandDiv = document.createElement("div", {
		'name':'commentsdiv',
		'id':'commentsdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'comments'+uniQ,
		'class':'ideadata',
		'style':'display:none;'
	});
	itDiv.appendChild(expandDiv);

	var kidscommentTable = document.createElement('table', {'style':'clear:both;margin-top:0px;width:100%'});
	kidscommentTable.width="100%";
	kidscommentTable.style.paddingLeft = '20px';
	expandDiv.appendChild(kidscommentTable);
	//kidscommentTable.border = "1";
	var rowcomment = kidscommentTable.insertRow(-1);
	var commentCell = rowcomment.insertCell(-1);

	var commentHeading = document.createElement('h3');
	commentHeading.style.marginBottom = "2px";
	commentHeading.style.marginTop = "5px";
	commentHeading.style.color = "black";
	commentHeading.style.fontWeight = "normal";
	commentHeading.innerHTML += '<?php echo $LNG->IDEA_COMMENTS_CHILDREN_TITLE; ?> (';
	var commentCount = document.createElement('span', {'id':'count-comment'+uniQ});
	commentCount.innerHTML += '0';
	commentHeading.appendChild(commentCount);
	commentHeading.innerHTML += ')';
	commentCell.appendChild(commentHeading);

	var commentKidsDiv = document.createElement('div', {
		'id':'commentslist'+uniQ,
		'style':'width:100%;clear:both;float:left;padding-top:5px;margin-top:5px;',
	});
	commentKidsDiv.style.borderTop = "1px solid #D8D8D8";
	commentCell.appendChild(commentKidsDiv);

	/** PRO AND CON LISTS **/
	var kidsTable = document.createElement('table', {
		'name':'ideaforagainstdiv',
		'id':'ideaforagainstdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'arguments'+uniQ,
		'class':'ideaforagainsttable',
		'style':'display:none;'
	});
	kidsTable.width="100%";
	kidsTable.style.paddingLeft = '3px';
	itDiv.appendChild(kidsTable);
	//kidsTable.border = "1";

	var row = kidsTable.insertRow(-1);
	row.width="100%";

	var forCell = row.insertCell(-1);
	forCell.vAlign="top";
	forCell.align="left";
	forCell.valign="top";
	forCell.style.paddingRight = "10px";
	forCell.style.width = '360px';
	forCell.style.minWidth = '360px';
	forCell.width = "360px";

	var forHeading = document.createElement('h3');
	forHeading.style.marginBottom = "2px";
	forHeading.style.marginTop = "5px";
	forHeading.style.color = "green";
	forHeading.style.fontWeight = "normal";
	forHeading.innerHTML += '<?php echo $LNG->NODE_CHILDREN_EVIDENCE_PRO; ?> (';
	var forCount = document.createElement('span', {'id':'count-support'+uniQ});
	forCount.innerHTML += '0';
	forHeading.appendChild(forCount);
	forHeading.innerTHML += ')';
	forCell.appendChild(forHeading);

	var forKidsDiv = document.createElement('div', {'id':'supportkidsdiv'+uniQ, 'style':'width:100%;clear:both;float:left;'});
	forKidsDiv.style.paddingTop = "5px";
	forKidsDiv.style.borderTop = "1px solid #D8D8D8";
	forCell.appendChild(forKidsDiv);

	var conCell = row.insertCell(-1);
	conCell.vAlign="top";
	conCell.align="left";
	conCell.valign="top";
	conCell.style.paddingLeft = "10px";
	conCell.style.width = '360px';
	conCell.style.minWidth = '360px';
	conCell.width = "360px";

	var conHeading = document.createElement('h3');
	conHeading.style.marginBottom = "2px";
	conHeading.style.marginTop = "5px";
	conHeading.style.fontWeight = "normal";
	conHeading.innerHTML += '<?php echo $LNG->NODE_CHILDREN_EVIDENCE_CON; ?> (';
	var conCount = document.createElement('span', {'id':'count-counter'+uniQ, 'class':'count-counter'});
	conCount.innerHTML += '0';
	conHeading.appendChild(conCount);
	conHeading.innerHTML+= ')';
	conCell.appendChild(conHeading);

	var conKidsDiv = document.createElement('div', {'id':'counterkidsdiv'+uniQ, 'style':'width:100%;clear:both;float:left;'});
	conKidsDiv.style.paddingTop = "5px";
	conKidsDiv.style.borderTop = "1px solid #D8D8D8";
	conCell.appendChild(conKidsDiv);

	loadChildComments(commentKidsDiv, node.nodeid, '<?php echo $LNG->COMMENTS_NAME; ?>', '<?php echo $CFG->LINK_COMMENT_NODE; ?>', 'Comment', node.parentid, node.groupid, uniQ, commentCount, type, status);
	loadChildArguments(forKidsDiv, node.nodeid, '<?php echo $LNG->PROS_NAME; ?>', '<?php echo $CFG->LINK_PRO_SOLUTION; ?>', 'Pro', node.parentid, node.groupid, uniQ, forCount, type, status, "");
	loadChildArguments(conKidsDiv, node.nodeid, '<?php echo $LNG->CONS_NAME; ?>', '<?php echo $CFG->LINK_CON_SOLUTION; ?>', 'Con', node.parentid, node.groupid, uniQ, conCount, type, status, "");

	return itDiv;
}


/**
 * Render the given node from an associated connection.
 * @param node the node object to render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 * @param type defaults to 'active', but can be 'inactive' so nothing is clickable
 * 			or a specialized type for some of the popups
 */
function renderArgumentNode(node, uniQ, role, includeUser, type, status){

	if (type === undefined) {
		type = "active";
	}

	if(role === undefined){
		role = node.role[0].role;
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

	var nodeuser = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		nodeuser = node.users[0];
	} else {
		nodeuser = node.users[0].user;
	}
	var user = null;
	var connection = node.connection;
	if (connection) {
		user = connection.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var focalrole = "";
	var otherend = "";
	if (connection) {
		var fN = connection.from[0].cnode;
		var tN = connection.to[0].cnode;
		if (node.nodeid == fN.nodeid) {
			focalrole = tN.role[0].role;
			otherend = tN;
		} else {
			focalrole = fN.role[0].role;
			otherend = fN;
		}
	}

	var nodeTable = document.createElement('table', {'style':'width:100%'});
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";

	var row = nodeTable.insertRow(-1);
	row.setAttribute('name','argumentrowitem');
	row.setAttribute('class','argumentrowitem');
	row.setAttribute('id','argumentrowitem'+uniQ);
	row.setAttribute('uniQ',uniQ);
	row.setAttribute('nodeid',node.nodeid);
	row.setAttribute('parentid',node.parentid);
	if (node.nodeid == NODE_ARGS['selectednodeid']) {
		//row.className = "selectedback";
		var options = new Array();
		options['startcolor'] = '#FAFB7D';
		options['endcolor'] = '#FDFDE3';
		options['restorecolor'] = 'transparent';
		options['duration'] = 5;
		highlightElement(row, options);
	}

	var textCell = row.insertCell(-1);
	textCell.classList.add("idea-section");

	var textDiv = document.createElement("div", {
		'id':'textdivargument'+uniQ,
		'class':'textdividea'
	});
	textCell.appendChild(textDiv);

	var title = node.name;
	var textspan = document.createElement("span", {
		'id':'desctoggle'+uniQ,
		'class': 'idea-title'
	});
	textspan.innerHTML+= title;
	textDiv.appendChild(textspan);

	if (USER == nodeuser.userid && type == "active" && NODE_ARGS['issueDiscussing']) {
		var editbutton = document.createElement("img", {			
			'class':'idea-edit',
			'alt':'<?php echo $LNG->EDIT_BUTTON_TEXT;?>', 
			'src':'<?php echo $HUB_FLM->getImagePath("edit.png"); ?>',
			'title':'<?php echo $LNG->NODE_EDIT_SOLUTION_ICON_HINT; ?>',
		});
		textDiv.appendChild(editbutton);
		editbutton.onclick = function () {
			var innertype = "pro";
			if (role.name == "Con") {
				innertype = "con";
			}
			hideAddForm(node.parentuniq, innertype);
			editInline(uniQ, 'argument');
		};

		var deletename = node.name;
		var del = document.createElement('img',{		
			'class':'idea-delete',
			'alt':'<?php echo $LNG->DELETE_BUTTON_ALT;?>', 
			'title': '<?php echo $LNG->DELETE_BUTTON_HINT;?>', 
			'src': '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>'
		});
		
		del.onclick = function () {
			var callback = function () {
				if (role.name == "Con") {
					document.getElementById('counterkidsdiv'+node.parentuniq).loaded = 'false';
					loadChildArguments('counterkidsdiv'+node.parentuniq, 
										node.parentid, 
										'<?php echo $LNG->CONS_NAME; ?>', 
										'<?php echo $CFG->LINK_CON_SOLUTION; ?>', 
										'Con', 
										node.parentid, 
										node.groupid, 
										node.parentuniq, 
										document.getElementById('count-counter'+node.parentuniq), 
										type, 
										status, 
										document.getElementById('votebardiv'+node.parentuniq));
					refreshStats();
				} else if (role.name == 'Pro') {
					document.getElementById('supportkidsdiv'+node.parentuniq).loaded = 'false';
					loadChildArguments('supportkidsdiv'+node.parentuniq, 
										node.parentid, 
										'<?php echo $LNG->PROS_NAME; ?>', 
										'<?php echo $CFG->LINK_PRO_SOLUTION; ?>', 
										'Pro', 
										node.parentid, 
										node.groupid, 
										node.parentuniq, 
										document.getElementById('count-support'+node.parentuniq), 
										type, 
										status, 
										document.getElementById('votebardiv'+node.parentuniq));
					refreshStats();
				}
			}
			deleteNode(node.nodeid, deletename, role.name, callback);
		};
		textDiv.appendChild(del);
	}

	<?php if ($CFG->SPAM_ALERT_ON) { ?>
	if (type == "active" && USER != "" && USER != nodeuser.userid) { // IF LOGGED IN AND NOT YOU
		textDiv.appendChild(createSpamButton(node, role));
	}
	<?php } ?>

	if (node.urls && node.urls.length > 0) {
		var menuButton = document.createElement('img',{
			'class':'idea-url',
			'alt':'>','width':'16','height':'16',
			'src': '<?php echo $HUB_FLM->getImagePath("nodetypes/Default/reference-32x32.png"); ?>'
		});
		textDiv.appendChild(menuButton);		
		menuButton.onmouseout = function (event) {
			hideBox('toolbardiv'+uniQ);
		};
		menuButton.onmouseover = function (event) {
			var position = getPosition(this);
			var panel = document.getElementById('toolbardiv'+uniQ);
			var panelWidth = 200;

			var viewportHeight = getWindowHeight();
			var viewportWidth = getWindowWidth();

			var x = position.x;
			var y = position.y;

			if ( (x+panelWidth+30) > viewportWidth) {
				x = x-(panelWidth+30);
			} else {
				x = x+10;
			}

			x = x+30+getPageOffsetX();

			panel.style.left = x+"px";
			panel.style.top = y+"px";

			showBox('toolbardiv'+uniQ);
		};
		var toolbarDiv = document.createElement("div", {'id':'toolbardiv'+uniQ, 'style':'left:-1px;top:-1px;clear:both;position:absolute;display:none;z-index:60;padding:5px;width:200px;border:1px solid gray;background:white'} );
		toolbarDiv.onmouseout = function (event) {
			hideBox('toolbardiv'+uniQ);
		};
		toolbarDiv.onmouseover = function (event){ showBox('toolbardiv'+uniQ); };
		textDiv.appendChild(toolbarDiv);

		for(var i=0; i< node.urls.length; i++){
			if(node.urls[i].url){
				var next = node.urls[i].url;
				var url = next.url;
				var weblink = document.createElement("a", {'style':'clear:both;float:left;margin-bottom:6px;font-size:10pt','target':'_blank'});
				weblink.href = url;
				weblink.innerHTML += url;
				toolbarDiv.appendChild(weblink);
			}
		}
	}

	if(node.description || node.hasdesc){
		var dStr = '<div style="clear:both;margin:0px;padding:0px;margin-top:3px;font-size:10pt;" class="idea-desc" id="desc'+uniQ+'div"><span style="margin-top: 5px;">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.innerHTML += dStr;
	}

	// VOTING
	if (type == 'active') {
		var voteCell = row.insertCell(-1);
		voteCell.setAttribute('name','argumentvotediv');
		voteCell.setAttribute('class','argumentvotediv');
		voteCell.setAttribute('id','argumentvotediv'+uniQ);
		voteCell.setAttribute('style','display:none;');
		if (NODE_ARGS['issueVoting'] && NODE_ARGS['currentphase'] != DECIDE_PHASE) {
			voteCell.style.display = "table-cell";
		}

		var voteDiv = document.createElement("div", {
			'id':'editformvotedivargument'+uniQ,
			'class':'editformvotedivargument',
		});
		//voteDiv.innerHTML += '<span style="margin-right:5px;"><?php echo $LNG->NODE_VOTE_MENU_TEXT; ?></span>';
		voteCell.appendChild(voteDiv);

		var toRoleName = getNodeTitleAntecedence(connection.torole[0].role.name, false);

		// vote for
		var voteforimg = document.createElement('img');
		voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-grey.png"); ?>');
		voteforimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_FOR_ICON_ALT; ?>');
		voteforimg.setAttribute('id', connection.connid+'for');
		voteforimg.nodeid = node.nodeid;
		voteforimg.connid = connection.connid;
		voteforimg.vote='Y';
		voteDiv.appendChild(voteforimg);
		if (!connection.positivevotes) {
			connection.positivevotes = 0;
		}
		voteDiv.innerHTML += '<span class="vote-count" id="'+connection.connid+'votefor">'+connection.positivevotes+'</span>';

		if (NODE_ARGS['issueVoting']) {
			if(USER != ""){
				if (nodeuser.userid == USER) {
					voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_OWN_HINT; ?>');
				} else {
					voteforimg.handler = function() {
						var votebar = document.getElementById('votebardiv'+node.parentuniq);
						var other = document.getElementById(this.connid+'against');
						// switching vote from against to for
						if (other.src == '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>') {
							if (role.name == "Con") {
								votebar.connegativevotes = parseInt(votebar.connegativevotes)-1;
								votebar.conpositivevotes = parseInt(votebar.conpositivevotes)+1;
							} else if (role.name == "Pro") {
								votebar.pronegativevotes = parseInt(votebar.pronegativevotes)-1;
								votebar.propositivevotes = parseInt(votebar.propositivevotes)+1;
							}
						} else { // adding new for
							if (role.name == "Con") {
								votebar.conpositivevotes = parseInt(votebar.conpositivevotes)+1;
							} else if (role.name == "Pro") {
								votebar.propositivevotes = parseInt(votebar.propositivevotes)+1;
							}
						}

						if (votebar.propositivevotes < 0) {
							votebar.propositivevotes = 0;
						}
						if (votebar.pronegativevotes < 0) {
							votebar.pronegativevotes = 0;
						}
						if (votebar.connegativevotes < 0) {
							votebar.connegativevotes = 0;
						}
						if (votebar.conpositivevotes < 0) {
							votebar.conpositivevotes = 0;
						}

						drawVotesBar(votebar, node.parentuniq, node.focalnodeid);
						recalculatePeople();
					}
					voteforimg.handlerdelete = function() {
						var votebar = document.getElementById('votebardiv'+node.parentuniq);
						if (role.name == "Con") {
							votebar.conpositivevotes = parseInt(votebar.conpositivevotes)-1;
						} else if (role.name == "Pro") {
							votebar.propositivevotes = parseInt(votebar.propositivevotes)-1;
						}
						drawVotesBar(votebar, node.parentuniq, node.focalnodeid);
						recalculatePeople();
					}

					voteforimg.style.cursor = 'pointer';
					voteforimg.oldtitle = '<?php echo $LNG->NODE_VOTE_FOR_EVIDENCE_SOLUTION_HINT; ?> '+toRoleName;
					if (connection.uservote && connection.uservote == 'Y') {
						voteforimg.onclick = function (){ deleteConnectionVote(this) };
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>');
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!connection.uservote || connection.uservote != 'Y') {
						voteforimg.onclick = function (){ connectionVote(this) };
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
						voteforimg.setAttribute('title', voteforimg.oldtitle);
					}
				}
			} else {
				voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_LOGIN_HINT; ?>');
			}
		}

		// vote against
		var voteagainstimg = document.createElement('img');
		voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-grey.png"); ?>');
		voteagainstimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_AGAINST_ICON_ALT; ?>');
		voteagainstimg.setAttribute('id', connection.connid+'against');
		voteagainstimg.nodeid = node.nodeid;
		voteagainstimg.connid = connection.connid;
		voteagainstimg.vote='N';
		voteDiv.appendChild(voteagainstimg);
		if (!connection.negativevotes) {
			connection.negativevotes = 0;
		}
		voteDiv.innerHTML += '<span class="vote-count" id="'+connection.connid+'voteagainst">'+connection.negativevotes+'</span>';

		if (NODE_ARGS['issueVoting']) {
			if(USER != ""){
				if (nodeuser.userid == USER) {
					voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_OWN_HINT; ?>');
				} else {
					voteagainstimg.handler = function() {
						var votebar = document.getElementById('votebardiv'+node.parentuniq);
						var other = document.getElementById(this.connid+'for');
						//switching from for against vote to for vote
						if (other.src == '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>') {
							if (role.name == "Con") {
								votebar.connegativevotes = parseInt(votebar.connegativevotes)+1;
								votebar.conpositivevotes = parseInt(votebar.conpositivevotes)-1;
							} else if (role.name == "Pro") {
								votebar.pronegativevotes = parseInt(votebar.pronegativevotes)+1;
								votebar.propositivevotes = parseInt(votebar.propositivevotes)-1;
							}
						} else { // new for vote
							if (role.name == "Con") {
								votebar.connegativevotes = parseInt(votebar.connegativevotes)+1;
							} else if (role.name == "Pro") {
								votebar.pronegativevotes = parseInt(votebar.pronegativevotes)+1;
							}
						}
						drawVotesBar(votebar, node.parentuniq, node.focalnodeid);
						recalculatePeople();
					}
					voteagainstimg.handlerdelete = function() {
						var votebar = document.getElementById('votebardiv'+node.parentuniq);
						if (role.name == "Con") {
							votebar.connegativevotes = parseInt(votebar.connegativevotes)-1;
						} else if (role.name == "Pro") {
							votebar.pronegativevotes = parseInt(votebar.pronegativevotes)-1;
						}
						drawVotesBar(votebar, node.parentuniq, node.focalnodeid);
						recalculatePeople();
					}

					voteagainstimg.style.cursor = 'pointer';
					voteagainstimg.oldtitle = '<?php echo $LNG->NODE_VOTE_AGAINST_EVIDENCE_SOLUTION_HINT; ?> '+toRoleName;
					if (connection.uservote && connection.uservote == 'N') {
						voteagainstimg.addEventListener('click', function () { deleteConnectionVote(this) } );
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>');
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!connection.uservote || connection.uservote != 'N') {
						voteagainstimg.addEventListener('click', function () { connectionVote(this) } );
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
						voteagainstimg.setAttribute('title', voteagainstimg.oldtitle);
					}
				}
			} else {
				voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_LOGIN_HINT; ?>');
			}
		}
	}

	if (includeUser == true) {
		var userCell = row.insertCell(-1);

		if (connection) {
			var cDate = new Date(connection.creationdate*1000);
			var dStr = "<?php echo $LNG->NODE_ADDED_BY; ?> "+user.name+ " on "+cDate.format(DATE_FORMAT)
			userCell.title = dStr;
		}


		// Add right side with user image and date below
		var iuDiv = document.createElement("div", {
			'id':'editformuserdivargument'+uniQ,
			'class':'idea-user2',
		});

		var userimageThumb = document.createElement('img',{'alt':nodeuser.name, 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.appendChild(userimageThumb);
			iuDiv.appendChild(imagelink);
		} else {
			iuDiv.appendChild(userimageThumb)
		}

		userCell.appendChild(iuDiv);
	}

	var row2 = nodeTable.insertRow(-1);
	var editCell = row2.insertCell(-1);
	editCell.className = "edit-column";

	/** ADD THE EDIT FORM FOR THE ARGUMENT **/
	if (USER == user.userid && type == 'active') {
		var editouterDiv = document.createElement("fieldset", {
			'id':'editformdivargument'+uniQ,
			'class':'editformdivargument',
			'style':'display:none;'
		});

		var legend = document.createElement("legend", { 'class':'edit-argument'});
		var legendtitle = document.createElement("h2");
		legendtitle.innerHTML += '<?php echo $LNG->EXPLORE_EDITING_ARGUMENT_TITLE; ?>';
		legend.appendChild(legendtitle);
		editouterDiv.appendChild(legend);

		editCell.appendChild(editouterDiv);

		var editDiv = document.createElement("div", {
			'class':'edit-argument-form'
		});
		editouterDiv.appendChild(editDiv);

		var editargumentid = document.createElement("input", {
			'name':'editargumentid',
			'id':'editargumentid'+uniQ,
			'type':'hidden',
			'value':node.nodeid,
		});
		editDiv.appendChild(editargumentid);
		var editargumentroleid = document.createElement("input", {
			'name':'editargumentnodetypeid',
			'id':'editargumentnodetypeid'+uniQ,
			'type':'hidden',
			'value':role.roleid,
		});
		editDiv.appendChild(editargumentroleid);

		var rowDiv1 = document.createElement("div", {
			'class':'my-2',
		});
		editDiv.appendChild(rowDiv1);
		var editargumentname = document.createElement("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
			'id':'editargumentname'+uniQ,
			'name':'editargumentname',
			'value':node.name,
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
		});
		rowDiv1.appendChild(editargumentname);

		var rowDiv2 = document.createElement("div", {
			'class':'my-2',
		});
		editDiv.appendChild(rowDiv2);
		var editargumentdesc = document.createElement("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
			'id':'editargumentdesc'+uniQ,
			'name':'editargumentdesc',
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
		});
		editargumentdesc.appendChild(node.description);
		rowDiv2.appendChild(editargumentdesc);

		var rowDiv = document.createElement("div", {
			'class':'my-2',
			'id':'linksdivedit'+uniQ,
		});
		rowDiv.linkcount = 0;
		editDiv.appendChild(rowDiv);

		if (node.urls && node.urls.length > 0) {
			rowDiv.linkcount = node.urls.length-1;
			for(var i=0; i< node.urls.length; i++){
				if(node.urls[i].url){
					var next = node.urls[i].url;
					var urlid = next.urlid;
					var url = next.url;

					editDiv.appendChild(rowDiv);

					var weblink = document.createElement("input", {
						'class':'form-control',
						'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
						'id':'argumentlinkedit'+uniQ+i,
						'name':'argumentlinkedit'+uniQ+'[]',
						'value':url,
						'style':'margin-bottom:3px;',
						'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
					});
					weblink.urlid = urlid;
					rowDiv.appendChild(weblink);
				}
			}
		} else {
			rowDiv.linkcount = 0;
			var weblink = document.createElement("input", {
				'class':'form-control',
				'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
				'id':'argumentlinkedit'+uniQ+0,
				'name':'argumentlinkedit'+uniQ+'[]',
				'value':'',
				'style':'margin-bottom:2px;',
				'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			});
			rowDiv.appendChild(weblink);
		}

		var rowDiv5 = document.createElement("div", {'class':'my-3'});
		editDiv.appendChild(rowDiv5);
		var addURL = document.createElement("a", {
			'class':'hgrinput',
			'href':'javascript:void(0)',
		});
		addURL.innerHTML += '<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>';
		addURL.addEventListener('click', function () {
			insertArgumentLink(uniQ, 'edit');
		});
		rowDiv5.appendChild(addURL);

		var rowDiv6 = document.createElement("div", {
			'class':'formrowsm mb-3',
		});
		editDiv.appendChild(rowDiv6);
		var editargumentsave = document.createElement("input", {
			'type':'button',
			'class':'btn btn-primary me-3',
			'id':'editargument',
			'name':'editargument',
			'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>',
		});
		editargumentsave.addEventListener('click', function () {
			editArgumentNode(node, uniQ, 'argument', role.name, type, includeUser, status);
			var innertype = "pro";
			if (role.name == "Con") {
				innertype = "con";
			}
			showAddForm(node.parentuniq, innertype);
		});
		rowDiv6.appendChild(editargumentsave);
		var editargumentcancel = document.createElement("input", {
			'type':'button',
			'class':'btn btn-secondary',
			'id':'cancelargument',
			'name':'editargument',
			'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>',
		});
		editargumentcancel.addEventListener('click', function () {
			var innertype = "pro";
			if (role.name == "Con") {
				innertype = "con";
			}
			showAddForm(node.parentuniq, innertype);
			cancelEditAction(uniQ, 'argument');
		});

		rowDiv6.appendChild(editargumentcancel);
	}

	return nodeTable;
}

/**
 * Render the given node from an associated connection.
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 * @param type defaults to 'active', but can be 'inactive' so nothing is clickable
 * 			or a specialized type for some of the popups
 */
function renderCommentNode(node, uniQ, role, includeUser, type, status){

	if (type === undefined) {
		type = "active";
	}

	if(role === undefined){
		role = node.role[0].role;
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

	var nodeuser = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		nodeuser = node.users[0];
	} else {
		nodeuser = node.users[0].user;
	}
	var user = null;
	var connection = node.connection;
	if (connection) {
		user = connection.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var focalrole = "";
	var otherend = "";
	if (connection) {
		var fN = connection.from[0].cnode;
		var tN = connection.to[0].cnode;
		if (node.nodeid == fN.nodeid) {
			focalrole = tN.role[0].role;
			otherend = tN;
		} else {
			focalrole = fN.role[0].role;
			otherend = fN;
		}
	}

	var nodeTable = document.createElement('table', {'style':'width:100%'});
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";
	//nodeTable.border = "1";

	var row = nodeTable.insertRow(-1);
	row.setAttribute('name','commentrowitem');
	row.setAttribute('id','commentrowitem'+uniQ);
	if (node.nodeid == NODE_ARGS['selectednodeid']) {
		//row.className = "selectedback";
		var options = new Array();
		options['startcolor'] = '#FAFB7D';
		options['endcolor'] = '#FDFDE3';
		options['restorecolor'] = 'transparent';
		options['duration'] = 5;
		highlightElement(row, options);
	}

	var textCell = row.insertCell(-1);
	textCell.vAlign="top";
	textCell.align="left";

	var textDiv = document.createElement("div", {
		'id':'textdivcomment'+uniQ,
	});
	textCell.appendChild(textDiv);

	var title = node.name;

	var textspan = document.createElement("span", {
		'id':'desctoggle'+uniQ,
		'style':'font-weight:normal;font-size:11pt'
	});
	textspan.innerHTML += title;
	textDiv.appendChild(textspan);

	if (USER == nodeuser.userid && type == "active") {
		var editbutton = document.createElement("img", {
			
			'class':'imagebuttonfaded',
			'style':'padding-left:10px',
			'src':'<?php echo $HUB_FLM->getImagePath("edit.png"); ?>',
			'title':'<?php echo $LNG->NODE_EDIT_SOLUTION_ICON_HINT; ?>',
		});
		textDiv.appendChild(editbutton);
		editbutton.addEventListener('click', function () {
			editInline(uniQ, 'comment');
		});

		var deletename = node.name;
		var del = document.createElement('img',{'style':'cursor: pointer;padding-left:5px;margin-top:5px;','alt':'<?php echo $LNG->DELETE_BUTTON_ALT;?>', 'title': '<?php echo $LNG->DELETE_BUTTON_HINT;?>', 'src': '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>'});
		del.addEventListener('click', function () {
			var callback = function () {
				document.getElementById('commentslist'+node.parentuniq).loaded = 'false';
				loadChildComments('commentslist'+node.parentuniq, node.parentid, '<?php echo $LNG->COMMENTS_NAME; ?>', '<?php echo $CFG->LINK_COMMENT_NODE; ?>', 'Comment', node.parentid, node.groupid, node.parentuniq, document.getElementById('count-comment'+node.parentuniq), type, status);
				refreshStats();
			}
			deleteNode(node.nodeid, deletename, role.name, callback);
		});
		textDiv.appendChild(del);
	}

	if(node.description || node.hasdesc){
		var dStr = '<div style="clear:both;margin:0px;padding:0px;margin-top:3px;font-size:10pt;" class="idea-desc" id="desc'+uniQ+'div"><span style="margin-top: 5px;">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.innerHTML += dStr;
	}

	if (includeUser == true) {
		var userCell = row.insertCell(-1);
		userCell.vAlign="top";
		userCell.align="left";
		userCell.width="40px;";

		if (connection) {
			var cDate = new Date(connection.creationdate*1000);
			var dStr = "<?php echo $LNG->NODE_ADDED_BY; ?> "+user.name+ " on "+cDate.format(DATE_FORMAT)
			userCell.title = dStr;
		}


		// Add right side with user image and date below
		var iuDiv = document.createElement("div", {
			'id':'editformuserdivcomment'+uniQ,
			'class':'idea-user2',
			'style':'float:left;display:block'
		});

		var userimageThumb = document.createElement('img',{'alt':nodeuser.name, 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.appendChild(userimageThumb);
			iuDiv.appendChild(imagelink);
		} else {
			iuDiv.appendChild(userimageThumb)
		}

		userCell.appendChild(iuDiv);
	}

	var row2 = nodeTable.insertRow(-1);
	var editCell = row2.insertCell(-1);
	editCell.colSpan = "3";

	/** ADD THE EDIT FORM FOR THE IDEA **/
	if (USER == user.userid && type == 'active') {
		var editouterDiv = document.createElement("div", {
			'id':'editformdivcomment'+uniQ,
			'style':'clear:both;float:left;width:100%;display:none;border:1px solid #E8E8E8;'
		});
		editCell.appendChild(editouterDiv);

		var editDiv = document.createElement("div", {
			'style':'clear:both;float:left;margin:5px;'
		});
		editouterDiv.appendChild(editDiv);

		/*
		var editForm = document.createElement("form", {
			'name':'editformcomment'+uniQ,
			'id':'editformcomment'+uniQ,
			'action':'',
			'method':'post',
			'enctype':'multipart/form-data',
			'onsubmit': "return checkCommentEditForm('comment','"+uniQ+"');",
		});
		editDiv.appendChild(editForm);
		*/

		var editideaid = document.createElement("input", {
			'name':'editcommentid',
			'id':'editcommentid'+uniQ,
			'type':'hidden',
			'value':node.nodeid,
		});
		editDiv.appendChild(editideaid);
		var editidearole = document.createElement("input", {
			'name':'editcommentnodetypeid',
			'id':'editcommentnodetypeid'+uniQ,
			'type':'hidden',
			'value':role.roleid,
		});
		editDiv.appendChild(editidearole);

		var rowDiv1 = document.createElement("div", {
			'class':'formrowsm',
			'style':'padding-top:0px;',
		});
		editDiv.appendChild(rowDiv1);
		var editideaname = document.createElement("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
			'id':'editcommentname'+uniQ,
			'name':'editcommentname',
			'value':node.name,
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
		});
		rowDiv1.appendChild(editideaname);

		var rowDiv2 = document.createElement("div", {
			'class':'formrowsm',
		});
		editDiv.appendChild(rowDiv2);
		var editideadesc = document.createElement("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
			'id':'editcommentdesc'+uniQ,
			'name':'editcommentdesc',
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
		});
		editideadesc.innerHTML += node.description;
		rowDiv2.appendChild(editideadesc);

		var rowDiv3 = document.createElement("div", {
			'class':'formrowsm',
		});
		editDiv.appendChild(rowDiv3);
		var editideasave = document.createElement("input", {
			'type':'button',
			'class':'submitright',
			'id':'editcomment',
			'name':'editcomment',
			'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>',
		});
		editideasave.addEventListener('click', function () {
			editCommentNode(node, uniQ, 'comment', type, includeUser, status);
		});
		rowDiv3.appendChild(editideasave);
		var editideacancel = document.createElement("input", {
			'type':'button',
			'class':'submitright',
			'id':'cancelcomment',
			'name':'cancelcomment',
			'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>',
			'style':'margin-right:10px;',
		});
		editideacancel.addEventListener('click', function () {
			cancelEditAction(uniQ, 'comment');
		});

		rowDiv3.appendChild(editideacancel);
	}

	return nodeTable;
}

/**
 * Render the given node.
 * @param node the node object to render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 */
function renderListNode(node, uniQ, role, includeUser){

	var type = "active";

	if(role === undefined){
		role = node.role[0].role;
	}

	if(includeUser === undefined){
		includeUser = false;
	}

	var user = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		user = node.users[0];
	} else {
		user = node.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	uniQ = node.nodeid + uniQ;

	var nodeTable = document.createElement('table', {'class':'ideas-table'});
	nodeTable.className = "toConnectionsTable";

	var row = nodeTable.insertRow(-1);

	if (includeUser) {
		var userCell = row.insertCell(-1);

		var cDate = new Date(node.creationdate*1000);
		var dStr = "<?php echo $LNG->NODE_ADDED_BY; ?> "+user.name+ " on "+cDate.format(DATE_FORMAT)
		userCell.title = dStr;

		// Add right side with user image and date below
		var iuDiv = document.createElement("div", {
			'id':'editformuserdivcomment'+uniQ,
			'class':'idea-user2',
			'style':'display:block'
		});

		var userimageThumb = document.createElement('img',{'alt':user.name, 'src': user.thumb});
		if (type == "active") {
			var imagelink = document.createElement('a', {
				'href':URL_ROOT+"user.php?userid="+user.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.appendChild(userimageThumb);
			iuDiv.appendChild(imagelink);
		} else {
			iuDiv.appendChild(userimageThumb)
		}

		userCell.appendChild(iuDiv);
	}

	var textCell = row.insertCell(-1);

	var textDiv = document.createElement("div", {
		'id':'textdivcomment'+uniQ,
		'class':'textdivcomment',
	});
	textCell.appendChild(textDiv);

	var title = node.name;

	var textspan = document.createElement("a", {
		'class':'textdivcomment-title'
	});
	textspan.innerHTML = title;
	textspan.href = '<?php echo $CFG->homeAddress; ?>explore.php?id='+node.nodeid;
	textDiv.appendChild(textspan);

	if(node.description || node.hasdesc){
		var dStr = '<div class="idea-desc" id="desc'+uniQ+'div"><span>';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.innerHTML += dStr;
	}

	return nodeTable;
}

/**
 * Render the given node for a report.
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 */
function renderReportNode(node, uniQ, role){

	if(role === undefined){
		role = node.role[0].role;
	}

	var user = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		user = node.users[0];
	} else {
		user = node.users[0].user;
	}

	var breakout = "";

	//needs to check if embedded as a snippet
	if(top.location != self.location){
		breakout = " target='_blank'";
	}
	uniQ = node.nodeid + uniQ;
	var iDiv = document.createElement("div", {'style':'clear:both;float:left; margin-bottom:10px'});
	var itDiv = document.createElement("div", {'style':'float:left;'});

	//get url for any saved image.
	//add left side with icon image and node text.
	var alttext = getNodeTitleAntecedence(role.name, false);
	if (EVIDENCE_TYPES_STR.indexOf(role.name) != -1
		|| role.name=="Project" || role.name=="Organization") {
		if (node.imagethumbnail != null && node.imagethumbnail != "") {
			var originalurl = "";
			if(node.urls && node.urls.length > 0){
				for (var i=0 ; i< node.urls.length; i++){
					var urlid = node.urls[i].url.urlid;
					if (urlid == node.imageurlid) {
						originalurl = node.urls[i].url.url;
						break;
					}
				}
			}
			if (originalurl == "") {
				originalurl = node.imagethumbnail;
			}
			var iconlink = document.createElement('a', {
				'href':originalurl,
				'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
			var nodeicon = document.createElement('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'style':'width:16px;height:16px;margin-right:5px;','width':'16','height':'16','align':'left', 'src': URL_ROOT + node.imagethumbnail});
			iconlink.appendChild(nodeicon);
			itDiv.appendChild(iconlink);
			itDiv.innerHTML += alttext+": ";
		} else if (role.image != null && role.image != "") {
			var nodeicon = document.createElement('img',{'alt':alttext, 'title':alttext, 'style':'width:16px;height:16px;margin-right:5px;','width':'16','height':'16','align':'left', 'src': URL_ROOT + role.image});
			itDiv.appendChild(nodeicon);
		} else {
			itDiv.innerHTML += alttext+": ";
		}
	}

	if (node.name != "") {
		iDiv.appendChild(itDiv);
		var str = "<div style='float:left;width:600px;'>"+node.name;
		str += "</div>";
		iDiv.innerHTML += str;
	}

	return iDiv;
}


/*** HELPER FUNCTIONS ***/

var DEBATE_TREE_OPEN_ARRAY = {};
var TREE_OPEN_ARRAY = {};

/**
 * Open and close the knowledge tree
 */
function toggleDebate(section, uniQ) {
    var sectionobj = document.getElementById(section); // Select the section element

	if (sectionobj.style.display === 'none' || section.style.display === '') {
    	sectionobj.style.display = 'block'; // Show the element
	} else {
    	sectionobj.style.display = 'none'; // Hide the element
	}

    if(isVisible(document.getElementById(section))){
    	DEBATE_TREE_OPEN_ARRAY[section] = true;
    	document.getElementById('explorearrow'+uniQ).src='<?php echo $HUB_FLM->getImagePath("arrow-down-blue.png"); ?>';
	} else {
    	DEBATE_TREE_OPEN_ARRAY[section] = false;
		document.getElementById('explorearrow'+uniQ).src='<?php echo $HUB_FLM->getImagePath("arrow-right-blue.png"); ?>';
	}
}

function ideaArgumentToggle(section, uniQ, id, sect, rolename, focalnodeid, groupid) {
   	const sectionobj = document.getElementById(section);
   	if (sectionobj.style.display === 'none' || section.style.display === '') {
    	sectionobj.style.display = 'block'; // Show the element
	} else {
    	sectionobj.style.display = 'none'; // Hide the element
	}

   	if(document.getElementById('arrow'+section)){
        if(isVisible(document.getElementById(section))){
            document.getElementById('arrow'+section).src = "<?php echo $HUB_FLM->getImagePath("arrow-up2.png"); ?>";
        } else {
            document.getElementById('arrow'+section).src = "<?php echo $HUB_FLM->getImagePath("arrow-down2.png"); ?>";
        }
	}
}

async function ideatoggle(section, uniQ, id, sect, rolename, focalnodeid, groupid) {
	const sectionobj = document.getElementById(section);
    if (sectionobj.style.display == 'block') {
		sectionobj.style.display = 'none';
    } else if (sectionobj.style.display == 'none') {
		sectionobj.style.display = 'block';
    }

	//Audit viewing of child lists only if opening area
	if (sectionobj.style.display == 'block') {
		if (sect == "comments") {
			if (sectionobj.commentnodes) {
				var nodes = sectionobj.commentnodes;
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i < count;i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids += node.cnode.nodeid;
					} else {
						nodeids += ","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				fetch(reqUrl, {
					method: 'POST'
				})
				.then(response => {
					if (!response.ok) {
						throw new Error('Network response was not ok');
					}
					return response.json();
				})
				.then(json => {
					jsonData = json; // Store the JSON data in a variable
					if (json.error) {
						alert(json.error[0].message);
					}					
				})
				.catch(err => {
					console.error('There was a problem with the post operation:', err);
				});
			}
		} else if (sect == "arguments") {
			if (sectionobj.pronodes) {
				var nodes = sectionobj.pronodes;
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids = nodeids + node.cnode.nodeid;
					} else {
						nodeids = nodeids+","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				try {
					const json = await makeAPICall(reqUrl, 'POST');
					if (json.error) {
						alert(json.error[0].message);
						return;
					}
				} catch (err) {
					alert("There was an error: "+err.message);
					console.log(err)
				}										
			}

			if (sectionobj.connodes) {
				var nodes = sectionobj.connodes;
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i < count;i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids = nodeids + node.cnode.nodeid;
					} else {
						nodeids = nodeids + ","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				try {
					const json = await makeAPICall(reqUrl, 'POST');
					if (json.error) {
						alert(json.error[0].message);
						return;
					}
				} catch (err) {
					alert("There was an error: "+err.message);
					console.log(err)
				}					
			}
		}
	}

	if (document.getElementById('idearowitem'+uniQ)) {
		if( (document.getElementById('comments'+uniQ) 
				&& document.getElementById('comments'+uniQ).style.display == 'none' 
				&& document.getElementById('arguments'+uniQ).style.display == 'none') || (!document.getElementById('comments'+uniQ) 
				&& document.getElementById('arguments'+uniQ).style.display == 'none') ){
			
			document.getElementById('idearowitem'+uniQ).style.background = "transparent";
		} else {
			document.getElementById('idearowitem'+uniQ).style.background = "#E8E8E8";
   		}
   	}
}

async function loadStats(nodeid, peoplearea, ideaarea, votearea) {

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = nodeid;
    args['style'] = "long";
	var reqUrl = SERVICE_ROOT + "&method=getdebateministats&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		var stats = json.debateministats[0];
		var totalvotes = parseInt(stats.totalvotes);
		var ideacount = parseInt(stats.ideacount);
		var peoplecount = parseInt(stats.peoplecount);
		if (peoplearea) {
			peoplearea.iinerHTML = peoplecount;
		}
		if (ideaarea) {
			ideaarea.innerHTML = ideacount;
		}
		if (votearea) {
			votearea.innerHTML = totalvotes;
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 * load child list, if required as per parameters.
 */
async function loadChildComments(section, nodeid, title, linktype, nodetype, focalnodeid, groupid, uniQ, countArea, type, status){

	if (typeof section === "string") {
		section = document.getElementById(section);
	}

	if (section.loaded == undefined) {
		section.loaded = 'false';
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

    if(section.visible() && (!section.loaded || section.loaded == 'false')){

   	section.innerHTML = getLoading("<?php echo $LNG->LOADING_ITEMS; ?>");

		var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=ASC&orderby=date&status="+status;
		reqUrl += "&filterlist="+linktype+"&filternodetypes="+nodetype+"&scope=all&start=0&max=-1&nodeid="+nodeid;
		try {
			const json = await makeAPICall(reqUrl, 'POST');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}

			var conns = json.connectionset[0].connections;
			section.innerHTML = "";

			const ideacommentscount = document.getElementById('ideacommentscount'+nodeid);
			ideacommentscount.innerHTML = "0";
			if (countArea) {
				countArea.innerHTML = "0";
			}

			var otherend = "";
			var nodes = new Array();
			//alert(conns.length);

			if (conns.length >  0) {
				for(var i=0; i< conns.length; i++){
					var c = conns[i].connection;
					var fN = c.from[0].cnode;
					var tN = c.to[0].cnode;

					var fnRole = c.fromrole[0].role;
					var tnRole = c.torole[0].role;

					if (fN.nodeid == NODE_ARGS['selectednodeid']) {
						otherend = tN.nodeid;
					}
					if (tN.nodeid == NODE_ARGS['selectednodeid']) {
						otherend = fN.nodeid;
					}

					if ((fnRole.name == nodetype || nodetype.indexOf(fnRole.name) != -1) && fN.nodeid != nodeid) {
						if (fN.name != "") {
							var next = c.from[0];
							next.cnode['parentid'] = nodeid;
							next.cnode['parentuniq'] = uniQ;
							next.cnode['connection'] = c;
							if (groupid) {
								next.cnode['groupid'] = groupid;
							}
							if (focalnodeid) {
								next.cnode['focalnodeid'] = focalnodeid;
							}
							nodes.push(next);
						}
					} else if ((tnRole.name == nodetype || nodetype.indexOf(tnRole.name) != -1) && tN.nodeid != nodeid) {
						if (tN.name != "") {
							var next = c.to[0];
							next.cnode['parentid'] = nodeid;
							next.cnode['parentuniq'] = uniQ;
							next.cnode['connection'] = c;
							if (groupid) {
								next.cnode['groupid'] = groupid;
							}
							if (focalnodeid) {
								next.cnode['focalnodeid'] = focalnodeid;
							}
							nodes.push(next);
						}
					}
				}
				section.loaded = 'true';
			}

			if (countArea) {
				countArea.innerHTML = nodes.length;
			}
			if (document.getElementById('ideacommentscount'+nodeid)) {
				document.getElementById('ideacommentscount'+nodeid).innerHTML = nodes.length;
			}

			if (nodes.length > 0){
				displayCommentNodes(section, nodes, parseInt(0), true, uniQ, type, status);
				// for View auditign on toggle
				section.commentnodes = nodes;
			}

			if (otherend !="") {
				openSelectedItem(otherend, 'comments');
			}
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	}
}

/**
 * load child list, if required as per parameters.
 */
async function loadChildArguments(section, nodeid, title, linktype, nodetype, focalnodeid, groupid, uniQ, countArea, type, status, votebar){

	if (typeof section === "string") {
		section = document.getElementById(section);
	}

	if (section.loaded == undefined) {
		section.loaded = 'false';
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

    if(section.visible() && (!section.loaded || section.loaded == 'false')){

    	section.innerHTML = getLoading("<?php echo $LNG->LOADING_ITEMS; ?>");

		var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=ASC&orderby=date&status="+status;
		reqUrl += "&filterlist="+linktype+"&filternodetypes="+nodetype+"&scope=all&start=0&max=-1&nodeid="+nodeid;
		try {
			const json = await makeAPICall(reqUrl, 'POST');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}

			var conns = json.connectionset[0].connections;
			section.innerHTML ="";
			if (document.getElementById('ideaargumentcount'+nodeid)) {
				document.getElementById('ideaargumentcount'+nodeid).innerHTML = "0";
			}
			if (countArea) {
				countArea.innerHTML = "0";
			}

			//alert(conns.length);

			var nodes = new Array();
			var otherend = "";
			var positivevotes = 0;
			var negativevotes = 0;

			if (conns.length > 0) {
				for(var i=0; i< conns.length; i++){
					var c = conns[i].connection;

					var fN = c.from[0].cnode;
					var tN = c.to[0].cnode;

					var fnRole = c.fromrole[0].role;
					var tnRole = c.torole[0].role;

					if (fN.nodeid == NODE_ARGS['selectednodeid']) {
						otherend = tN.nodeid;
					}
					if (tN.nodeid == NODE_ARGS['selectednodeid']) {
						otherend = fN.nodeid;
					}

					if ((fnRole.name == nodetype || nodetype.indexOf(fnRole.name) != -1) && fN.nodeid != nodeid) {
						if (fN.name != "") {
							var next = c.from[0];
							next.cnode['parentid'] = nodeid;
							next.cnode['parentuniq'] = uniQ;
							next.cnode['connection'] = c;

							if (nodetype == "Con") {
								positivevotes += parseInt(c.positivevotes);
								negativevotes += parseInt(c.negativevotes);
							} else {
								positivevotes += parseInt(c.positivevotes);
								negativevotes += parseInt(c.negativevotes);
							}

							if (groupid) {
								next.cnode['groupid'] = groupid;
							}
							if (focalnodeid) {
								next.cnode['focalnodeid'] = focalnodeid;
							}
							nodes.push(next);
						}
					} else if ((tnRole.name == nodetype || nodetype.indexOf(tnRole.name) != -1) && tN.nodeid != nodeid) {
						if (tN.name != "") {
							var next = c.to[0];
							next.cnode['parentid'] = nodeid;
							next.cnode['parentuniq'] = uniQ;
							next.cnode['connection'] = c;

							if (nodetype == "Con") {
								positivevotes += parseInt(c.positivevotes);
								negativevotes += parseInt(c.negativevotes);
							} else {
								positivevotes += parseInt(c.positivevotes);
								negativevotes += parseInt(c.negativevotes);
							}

							if (groupid) {
								next.cnode['groupid'] = groupid;
							}
							if (focalnodeid) {
								next.cnode['focalnodeid'] = focalnodeid;
							}
							nodes.push(next);
						}
					}
				}
				section.loaded = 'true';
			}

			if (countArea) {
				countArea.innerHTML = nodes.length;
			}
			var otherAmount = 0;
			if (nodetype == "Pro") {
				otherAmount = parseInt(document.getElementById('count-counter'+uniQ).innerHTML);
			} else {
				otherAmount = parseInt(document.getElementById('count-support'+uniQ).innerHTML);
			}
			if (document.getElementById('ideaargumentcount'+nodeid)) {
				document.getElementById('ideaargumentcount'+nodeid).innerHTML = otherAmount+nodes.length;
			}

			if (nodes.length > 0){
				displayArgumentNodes(section, nodes, parseInt(0), true, uniQ, type, status);

				// for View auditing on toggle
				if (document.getElementById('arguments'+uniQ)) {
					if (nodetype == "Con") {
						document.getElementById('arguments'+uniQ).connodes = nodes;
					} else {
						document.getElementById('arguments'+uniQ).pronodes = nodes;
					}
				}
			}

			if (votebar != "") {
				if (nodetype == "Con") {
					votebar.conpositivevotes = parseInt(positivevotes);
					votebar.connegativevotes = parseInt(negativevotes);
					votebar.concount = nodes.length;
				} else {
					votebar.propositivevotes = parseInt(positivevotes);
					votebar.pronegativevotes = parseInt(negativevotes);
					votebar.procount = nodes.length;
				}
				drawVotesBar(votebar, uniQ, focalnodeid);
			}

			// if in the voting phase, expand arguments and audit views.
			if (NODE_ARGS['currentphase'] == DECIDE_PHASE && nodes.length > 0) {
				document.getElementById('arguments'+uniQ).style.display = "block";

				//audit views of this child list now it is opened
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids = nodeids + node.cnode.nodeid;
					} else {
						nodeids = nodeids+","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				try {
					const json = await makeAPICall(reqUrl, 'POST');
					if (json.error) {
						alert(json.error[0].message);
						return;
					}
				} catch (err) {
					alert("There was an error: "+err.message);
					console.log(err)
				}					
			}

			if (otherend != "") {
				openSelectedItem(otherend, 'arguments');
			}
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	}
}

/**
 * load child list on solutionas for built froms.
 */
async function loadBuiltFromsCount(section, nodeid){

	var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=DESC&orderby=date&status=<?php echo $CFG->STATUS_ACTIVE; ?>";
	reqUrl += "&filterlist=<?php echo $CFG->LINK_BUILT_FROM; ?>&filternodetypes=Solution&scope=all&start=0&max=0&nodeid="+nodeid;
	try {
		const json = await makeAPICall(reqUrl, 'POST');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		var count = json.connectionset[0].totalno;
		if (count > 0) {
			section.style.display = "block";
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}


/**
 * Delete the selected node
 */
async function deleteNode(nodeid, name, type, handler, handlerparams){
	var typename = getNodeTitleAntecedence(type, false);
	if (type == "") {
		typename = '<?php echo $LNG->NODE_DELETE_CHECK_MESSAGE_ITEM; ?>';
	}

	var ans = confirm("<?php echo $LNG->NODE_DELETE_CHECK_MESSAGE; ?> "+typename+": '"+htmlspecialchars_decode(name)+"'?");
	if(ans){
		var reqUrl = SERVICE_ROOT + "&method=deletenode&nodeid=" + encodeURIComponent(nodeid);
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			if (handler && (typeof handler === "string" || typeof handler === "function")) {
				if (typeof handler === "string") {
					var pos = handler.indexOf(")");
					if (pos != -1) {
						eval ( handler );
					} else {
						if (handlerparams) {
							eval( handler + "('"+handlerparams+"')" );
						} else {
							eval( handler + "()" );
						}
					}
				} else if (typeof handler === "function") {
					handler();
				}
			} else {
				try {
					// If you are deleting the currently viewed item
					if (nodeid == NODE_ARGS['nodeid']) {
						if (NODE_ARGS['groupid'] != "") {
							window.location.href = "<?php echo $CFG->homeAddress;?>group.php?groupid="+NODE_ARGS['groupid'];
						} else {
							window.location.href = "<?php echo $CFG->homeAddress;?>";
						}
					} else {
						window.location.reload(true);
					}
				} catch(err) {
					//do nothing
				}
			}
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}  
	}
}

async function nodeVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=nodevote&vote="+obj.vote+"&nodeid="+obj.nodeid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}		
		if (obj.vote == 'Y') {

			// if they vote on an idea, make sure they follow the main debate issue
			if (nodeObj && nodeObj.role.name == 'Issue' && !nodeObj.userfollow || nodeObj.userfollow == "N") {
				followNode(nodeObj, null, 'refreshMainIssue');
			}

			var nodevoteforelements = document.querySelectorAll('#nodevotefor' + obj.nodeid);
			nodevoteforelements.forEach(function(elmt) {
				elmt.innerHTML = json.cnode[0].positivevotes;
			});

			var nodeforelements = document.querySelectorAll('#nodefor' + obj.nodeid);
			nodeforelements.forEach(function(elmt) {
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');

				// Remove existing click event listeners (if needed)
				var newClickHandler = function() { deleteNodeVote(this); };
				elmt.removeEventListener('click', newClickHandler); // Ensure to remove the previous listener
				elmt.addEventListener('click', newClickHandler); // Add the new click event listener
			});					

			var nodeforelements = document.querySelectorAll('#nodefor' + obj.nodeid);
			elements.forEach(function(elmt) {
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');

				// Remove existing click event listeners (if needed)
				elmt.removeEventListener('click', deleteNodeVote); // Ensure to remove the previous listener
				elmt.addEventListener('click', function() { deleteNodeVote(this); }); // Add the new click event listener
			});

			var nodevoteagainstelements = document.querySelectorAll('#nodevoteagainst' + obj.nodeid);
			nodevoteagainstelements.forEach(function(elmt) {
				elmt.innerHTML = json.cnode[0].negativevotes;
			});

			document.querySelectorAll("#nodeagainst" + obj.nodeid).forEach(function(elmt) {			
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
				elmt.onclick = function () { nodeVote(this) };
			});
		} else if (obj.vote == 'N') {
			document.querySelectorAll("#nodevoteagainst" + obj.nodeid).forEach(function(elmt) {	elmt.innerHTML = json.cnode[0].negativevotes; });

			document.querySelectorAll("#nodeagainst" + obj.nodeid).forEach(function(elmt) {						
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
				elmt.onclick = function (){ deleteNodeVote(this) };
			});

			document.querySelectorAll("#nodevotefor" + obj.nodeid).forEach(function(elmt) {	
				elmt.innerHTML = json.cnode[0].positivevotes; 
			});

			document.querySelectorAll("#nodefor" + obj.nodeid).forEach(function(elmt) {
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
				elmt.onclick = function() { nodeVote(this); };
			});
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

async function deleteNodeVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=deletenodevote&vote="+obj.vote+"&nodeid="+obj.nodeid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		if (obj.vote == 'Y') {

			document.querySelectorAll("#nodevotefor" + obj.nodeid).forEach(function(elmt) { elmt.innerHTML = json.cnode[0].positivevotes; });

			document.querySelectorAll("#nodefor" + obj.nodeid).forEach(function(elmt) {						
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
				elmt.onclick = function () { nodeVote(this); };
			});

			document.querySelectorAll("#nodevoteagainst" + obj.nodeid).forEach(function(elmt) { elmt.innerHTML = json.cnode[0].negativevotes; });

			document.querySelectorAll("#nodeagainst" + obj.nodeid).forEach(function(elmt) {						
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
				elmt.onclick = function () { nodeVote(this) };
			});

			document.getElementById(obj.nodeid+obj.uniqueid+'nodeagainst').setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
			document.getElementById(obj.nodeid+obj.uniqueid+'nodeagainst').setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');

			document.getElementById(obj.nodeid+obj.uniqueid+'nodeagainst').onclick = function (){ connectionVote(this) };

		} if (obj.vote == 'N') {
			document.querySelectorAll("#nodevoteagainst" + obj.nodeid).forEach(function(elmt) {	elmt.innerHTML = json.cnode[0].negativevotes; });				

			document.querySelectorAll("#nodeagainst" + obj.nodeid).forEach(function(elmt) {						
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
				elmt.onclick = function (){ nodeVote(this) };
			});

			document.querySelectorAll("#nodevotefor" + obj.nodeid).forEach(function(elmt) { elmt.innerHTML = json.cnode[0].positivevotes; });

			document.querySelectorAll("#nodefor" + obj.nodeid).forEach(function(elmt) {						
				elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
				elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
				elmt.onclick = function (){ nodeVote(this) };
			});
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

async function followNode(node, obj, handler) {
	var reqUrl = SERVICE_ROOT + "&method=addfollowing&itemid="+node.nodeid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		node.userfollow = "Y";
		if (handler) {
			var pos = handler.indexOf(")");
			if (pos != -1) {
				eval ( handler );
			} else {
				eval( handler + "()" );
			}
		}

		if (obj) {
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("following.png"); ?>');
			obj.setAttribute('title', '<?php echo $LNG->NODE_UNFOLLOW_ITEM_HINT; ?>');
			obj.onclick = function (){ unfollowNode(node, this, handler) };
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

async function unfollowNode(node, obj, handler) {
	var reqUrl = SERVICE_ROOT + "&method=deletefollowing&itemid="+node.nodeid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		node.userfollow = "N";
		if (handler) {
			var pos = handler.indexOf(")");
			if (pos != -1) {
				eval ( handler );
			} else {
				eval( handler + "()" );
			}
		}

		if (obj) {
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
			obj.setAttribute('title', '<?php echo $LNG->NODE_FOLLOW_ITEM_HINT; ?>');
			obj.onclick = function (){ followNode(node, this, handler) };
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 * Called from user home page follow list.
 */
async function unfollowMyNode(nodeid) {
	var reqUrl = SERVICE_ROOT + "&method=deletefollowing&itemid="+nodeid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}		
		try {
			window.location.reload(true);
		} catch(err) {
			//do nothing
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

async function lemonNode(nodeid, issueid, handler) {
	var reqUrl = SERVICE_ROOT + "&method=addlemon&nodeid="+nodeid+"&issueid="+issueid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		if (handler && (typeof handler === "string" || typeof handler === "function")) {
			if (typeof handler === "string") {
				var pos = handler.indexOf(")");
				if (pos != -1) {
					eval ( handler );
				} else {
					eval( handler + "()" );
				}
			} else if (typeof handler === "function") {
				handler();
			}
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

async function unlemonNode(nodeid, issueid, handler) {
	var reqUrl = SERVICE_ROOT + "&method=deletelemon&nodeid="+nodeid+"&issueid="+issueid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	

		if (handler && (typeof handler === "string" || typeof handler === "function")) {
			if (typeof handler === "string") {
				var pos = handler.indexOf(")");
				if (pos != -1) {
					eval ( handler );
				} else {
					eval( handler + "()" );
				}
			} else if (typeof handler === "function") {
				handler();
			}
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
 *	show an RSS feed of the nodes for the given arguments
 */
function getNodesFeed(nodeargs) {
	var url = SERVICE_ROOT.replace('format=json','format=rss');
	var args = { ...nodeargs};
	args["start"] = 0;
	args["style"] = 'long';
	var fcontext = 'global';
	if (CONTEXT) {
		fcontext = CONTEXT;
	}
	var reqUrl = url+"&method=getnodesby"+fcontext+"&"+Object.toQueryString(args);
	window.location = reqUrl;
}

/**
 *	show an RSS feed of the nodes for the given arguments
 */
function getCommentNodesFeed(nodeargs) {
	var url = SERVICE_ROOT.replace('format=json','format=rss');
	var args = { ...nodeargs };
	args["start"] = 0;
	args["style"] = 'long';
	var reqUrl = url+"&method=getconnectednodesby"+CONTEXT+"&"+Object.toQueryString(args);
	window.location.href = reqUrl;
}

/**
 * Print current node list in new popup window
 */
function printNodes(nodeargs, title) {
	var url = SERVICE_ROOT;

	var args = { ...nodeargs };
	args["start"] = 0;
	args["max"] = -1;
	args["style"] = 'long';

	var reqUrl = url+"&method=getnodesby"+CONTEXT+"&"+Object.toQueryString(args);
	var urlcall =  URL_ROOT+"ui/popups/printnodes.php?context="+CONTEXT+"&title="+title+"&filternodetypes="+args['filternodetypes']+"&url="+encodeURIComponent(reqUrl);

	loadDialog('printnodes', urlcall, 800, 700);
}


/**
 * Print current node list in new popup window
 */
function printCommentNodes(nodeargs, title) {
	var url = SERVICE_ROOT;

	var args = { ...nodeargs };
	args["start"] = 0;
	args["max"] = -1;
	args["style"] = 'long';

	var reqUrl = url+"&method=getconnectednodesby"+CONTEXT+"&"+Object.toQueryString(args);
	var urlcall =  URL_ROOT+"ui/popups/printnodes.php?context="+CONTEXT+"&title="+title+"&filternodetypes="+args['filternodetypes']+"&url="+encodeURIComponent(reqUrl);

	loadDialog('printnodes', urlcall, 800, 700);
}

// NODE CONNECTION FUNCTIONS
async function connectionVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=connectionvote&vote="+obj.vote+"&connid="+obj.connid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}		

		// if they vote on an idea, make sure they follow the main debate issue
		if (nodeObj && nodeObj.role.name == 'Issue' && !nodeObj.userfollow || nodeObj.userfollow == "N") {
			followNode(nodeObj, null, 'refreshMainIssue');
		}

		if (obj.vote == 'Y') {
			const nodevoteforelements = document.querySelectorAll('#'+obj.connid+'votefor');
			nodevoteforelements.innerHTML = json.connection[0].positivevotes;
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>');
			obj.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
			obj.onclick = function () { deleteConnectionVote(this) };

			const nodevoteagainstelements = document.querySelectorAll('#'+obj.connid+'voteagainst');
			nodevoteagainstelements.innerHTML = json.connection[0].negativevotes;

			const nodeagainstelements = document.querySelectorAll('#'+obj.connid+'against');
			nodeagainstelements.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
			nodeagainstelements.setAttribute('title', nodeagainstelements.oldtitle);
			nodeagainstelements.onclick = function (){ connectionVote(this) };
		} else if (obj.vote == 'N') {
			const voteagainstelements = document.querySelectorAll('#'+obj.connid+'voteagainst');
			voteagainstelements.innerHTML = json.connection[0].negativevotes;
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>');
			obj.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
			obj.onclick = function (){ deleteConnectionVote(this) };

			const voteforelements = document.querySelectorAll('#'+obj.connid+'votefor');
			voteforelements.innerHTML = json.connection[0].positivevotes;

			const forelements = document.querySelectorAll('#'+obj.connid+'for');
			forelements.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
			forelements.setAttribute('title', forelements.oldtitle);
			forelements.onclick = function (){ connectionVote(this) };
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	if (obj.handler != undefined) {
  		obj.handler();
  	}
}

async function deleteConnectionVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=deleteconnectionvote&vote="+obj.vote+"&connid="+obj.connid;
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	
		if (obj.vote == 'Y') {
			const voteforelements = document.querySelectorAll('#'+obj.connid+'votefor');
			voteforelements.innerHTML = json.connection[0].positivevotes;
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
			obj.setAttribute('title', obj.oldtitle);
			obj.onclick = function () { connectionVote(this) };

			const voteagainstelements = document.querySelectorAll('#'+obj.connid+'voteagainst');
			voteagainstelements.innerHTML = json.connection[0].negativevotes;

			const againstelements = document.querySelectorAll('#'+obj.connid+'against');
			againstelements.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
			againstelements.setAttribute('title', againstelements.oldtitle);
			againstelements.onclick = function () { connectionVote(this) };
		} if (obj.vote == 'N') {
			const voteagainstelements = document.querySelectorAll('#'+obj.connid+'voteagainst');
			voteagainstelements.innerHTML = json.connection[0].negativevotes;
			obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
			obj.setAttribute('title', obj.oldtitle);
			obj.onclick = function (){ connectionVote(this) };

			const voteforelements = document.querySelectorAll('#'+obj.connid+'votefor');
			voteforelements.innerHTML = json.connection[0].positivevotes;

			const forelements = document.querySelectorAll('#'+obj.connid+'for');
			forelements.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
			forelements.setAttribute('title', forelements.oldtitle);
			forelements.onclick = function (){ connectionVote(this) };
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	if (obj.handlerdelete != undefined) {
  		obj.handlerdelete();
  	}
}

/**
 * Delete the connection for the given connection id.
 */
async function deleteNodeConnection(connid, childname, parentname, handler) {
	var ans = confirm("<?php echo $LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART1; ?> \n\r\n\r'"+htmlspecialchars_decode(childname)+"'\n\r\n\r <?php echo $LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART2; ?> \n\r\n\r'"+htmlspecialchars_decode(parentname)+"' <?php echo $LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART3; ?>");
	if(ans){
		var reqUrl = SERVICE_ROOT + "&method=deleteconnection&connid=" + encodeURIComponent(connid);
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			if (handler) {
				var pos = handler.indexOf(")");
				if (pos != -1) {
					eval ( handler );
				} else {
					eval( handler + "()" );
				}
			} else {
				try {
					window.location.reload(true);
				} catch(err) {
					//do nothing
				}
			}
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	}
}

/**
 * Send a spam alert to the server.
 */
async function reportNodeSpamAlert(obj, nodetype, node) {

	var name = node.name;

	var ans = confirm("<?php echo $LNG->SPAM_CONFIRM_MESSAGE_PART1; ?>\n\n"+name+"\n\n<?php echo $LNG->SPAM_CONFIRM_MESSAGE_PART2; ?>\n\n");
	if (ans){
		var reqUrl = URL_ROOT + "ui/admin/spamalert.php?type=idea&id="+node.nodeid;
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			node.status = 1;
			obj.title = '<?php echo $LNG->SPAM_REPORTED_HINT; ?>';
			if (obj.alt) {
				obj.alt = '<?php echo $LNG->SPAM_REPORTED_TEXT; ?>';
				obj.src= '<?php echo $HUB_FLM->getImagePath('flag-grey.png'); ?>';
				obj.style.cursor = 'auto';
				obj.onclick = null;
			} else {
				obj.innerHTML = '<?php echo $LNG->SPAM_REPORTED_TEXT; ?>';
			}
			obj.className = "";
			fadeMessage(name+"<br /><br /><?php echo $LNG->SPAM_SUCCESS_MESSAGE; ?>");
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	}
}

/**
 * Create a span menu option to report spam / show spam reported / or say login to report.
 *
 * @param node the node to report
 * @param nodetype the nodetype of the node to report
 */
function createSpamMenuOption(node, nodetype) {

	var spaming = document.createElement("span", {'class':'active','style':'margin-bottom:5px;clear:both;font-size:10pt'} );

	if (node.status == <?php echo $CFG->STATUS_REPORTED; ?>) {
		spaming.innerHTML = "<?php echo $LNG->SPAM_REPORTED_TEXT; ?>";
		spaming.title = '<?php echo $LNG->SPAM_REPORTED_HINT; ?>';
		spaming.className = "";
	} else if (node.status == <?php echo $CFG->STATUS_ACTIVE; ?>) {
		if (USER != "") {
			spaming.innerHTML = "<?php echo $LNG->SPAM_REPORT_TEXT; ?>";
			spaming.title = '<?php echo $LNG->SPAM_REPORT_HINT; ?>';
			spaming.onclick = function (){ reportNodeSpamAlert(this, nodetype, node); };
		} else {
			spaming.innerHTML = "<?php echo $LNG->SPAM_LOGIN_REPORT_TEXT; ?>";
			spaming.title = '<?php echo $LNG->SPAM_LOGIN_REPORT_TEXT; ?>';			
			spaming.onclick = function (){ 
				const loginsubmit = document.getElementById('loginsubmit');
				loginsubmit.click(); return true;
			};
		}
	}

	return spaming;
}

/**
 * Create a span button to report spam / show spam reported / or say login to report.
 *
 * @param node the node to report
 * @param nodetype the nodetype of the node to report
 */
function createSpamButton(node, nodetype) {
	// Add spam icon
	var spamimg = document.createElement('img', {'style':'padding-top:0px;padding-right:10px;padding-left:10px;'});
	spamimg.classList.add('spamicon');
	if (node.status == <?php echo $CFG->STATUS_REPORTED; ?>) {
		spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_REPORTED_TEXT; ?>');
		spamimg.setAttribute('title', '<?php echo $LNG->SPAM_REPORTED_HINT; ?>');
		spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath('flag-grey.png'); ?>');
	} else if (node.status == <?php echo $CFG->STATUS_ACTIVE; ?>) {
		if(USER != ""){
			spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_REPORT_TEXT; ?>');
			spamimg.setAttribute('title', '<?php echo $LNG->SPAM_REPORT_HINT; ?>');
			spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath('flag.png'); ?>');
			spamimg.classList.add('idea-report');
			spamimg.style.cursor = 'pointer';
			spamimg.onclick - function (){ reportNodeSpamAlert(this, nodetype, node); };
		} else {
			spamimg.setAttribute('alt', '<?php echo $LNG->SPAM_LOGIN_REPORT_TEXT; ?>');
			spamimg.setAttribute('title', '<?php echo $LNG->SPAM_LOGIN_REPORT_HINT; ?>');
			spamimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath('falg-grey.png'); ?>');
			spamimg.classList.add('idea-report');
			spamimg.style.cursor = 'pointer';
			spamimg.onclick = function (){ 
				const loginsubmit = document.getElementById('loginsubmit');
				loginsubmit.click(); 
				return true; 
			};
		}
	}
	return spamimg;
}

/**
 * If the idea trees contain the argument or comment with the given id, open comment, or argument area.
 * The id of the argument of comment to focus on.
 */
function openSelectedItem(parentid, type) {

	if (parentid === undefined) {
		parentid = "";
	}

	if (parentid != "") {
		var cellsArray = document.getElementsByName('idearowitem');
		var count = cellsArray.length;
		var next = null;

		for (var i=0; i < count; i++) {
			next = cellsArray[i];
			var nodeid = next.getAttribute('nodeid');
			var uniQ = next.getAttribute('uniQ');
			if (nodeid == parentid) {
				if (type == 'arguments') {
					const arguments = document.querySelectorAll('#arguments'+uniQ);
					arguments.style.display = "block";
					var pos = getPosition(arguments);
					window.scroll(0,pos.y-100);
				} else if (type == 'comments') {
					const comments = document.querySelectorAll('#comments'+uniQ);
					comments.style.display = "block";
					var pos = getPosition(comments);
					window.scroll(0,pos.y-100);
				}
			}
		}
	}
}

/**
 *	get all the selected node ids
 */
function getSelectedNodeIDs(container){
	var retArr = new Array();
	var nodes = container.select('[class="nodecheck"]');
	nodes.each(function(name, index) {
		if(nodes[index].checked){
			retArr.push(nodes[index].id.replace(/nodecheck/,''));
			//retArr.push(nodes[index].nodeid);
		}
	});
	return retArr;
}


function drawVotesBar(container, uniQ, focalnodeid) {

	if (container == "") {
		return;
	}

	if (typeof container === "string") {		
		container = document.getElementById(container);
	}

	if (container.positivevotes < 0) {
		container.positivevotes = 0;
	}
	if (container.negativevotes < 0) {
		container.negativevotes = 0;
	}
	if (container.propositivevotes < 0) {
		container.propositivevotes = 0;
	}
	if (container.pronegativevotes < 0) {
		container.pronegativevotes = 0;
	}
	if (container.connegativevotes < 0) {
		container.connegativevotes = 0;
	}
	if (container.conpositivevotes < 0) {
		container.conpositivevotes = 0;
	}
	if (container.concount < 0) {
		container.concount = 0;
	}
	if (container.procount < 0) {
		container.procount = 0;
	}

	var positivetotal = parseInt(container.positivevotes)+parseInt(container.propositivevotes)+parseInt(container.connegativevotes)+container.procount;
	var negativetotal = parseInt(container.negativevotes)+parseInt(container.pronegativevotes)+parseInt(container.conpositivevotes)+container.concount;
	var total = positivetotal + negativetotal;

	const debatestatsvotes = document.getElementById('debatestatsvotes'+focalnodeid);
	if (focalnodeid && debatestatsvotes) {
		debatestatsvotes.votes[uniQ] = container.propositivevotes+container.pronegativevotes+container.connegativevotes+container.conpositivevotes;
		recalculateVotes(focalnodeid);
	}

	if (total == 0) {
		container.innerHTML = "";
	} else {
		const positivePercent = (100/total)*positivetotal;
		const negativePercent = (100/total)*negativetotal;

		let positiveWidth = 0;
		let negativeWidth = 0;
		let	positiveHint = 0;
		let	negativeHint = 0;

		// If the percentages end a .5 round both overcounts the number
		// So check and then always round up the larger percentage and round down the smaller.
		if (positivePercent % 1 != 0.5) {
			positiveHint = Math.round(positivePercent);
			negativeHint = Math.round(negativePercent);
			positiveWidth = Math.round(positivePercent);
			negativeWidth = Math.round(negativePercent);
		} else {
			if (positivePercent > negativePercent) {
				positiveHint = Math.round(positivePercent);
				negativeHint = Math.floor(negativePercent);
				positiveWidth = Math.round(positivePercent);
				negativeWidth = Math.floor(negativePercent);
			} else {
				positiveHint = Math.floor(positivePercent);
				negativeHint = Math.round(negativePercent);
				positiveWidth = Math.floor(positivePercent);
				negativeWidth = Math.round(negativePercent);
			}
		}

		var votebar = document.createElement("div", {'class':'progress'} );

		if (positiveHint == 100) {
			var bar = document.createElement("div", {'class':'barall', 'title':positiveHint+'% <?php echo $LNG->STATS_PRO_HINT_TEXT;?>', 'style':'width:'+positiveWidth+'%'} );
			votebar.appendChild(bar);
			bar.innerHTML += positiveHint+"%";
		} else if (negativeHint == 100) {
			var remainder = document.createElement("div", {'class':'remainderall','title':negativeHint+'%  <?php echo $LNG->STATS_CON_HINT_TEXT;?>', 'style':'width:'+negativeWidth+'%'} );
			votebar.appendChild(remainder);
			remainder.innerHTML += negativeHint+"%";
		} else {
			// otherwise it wraps.
			positiveWidth = positiveWidth-2;
			negativeWidth = negativeWidth-2;

			var bar = document.createElement("div", {'class':'bar', 'title':positiveHint+'%  <?php echo $LNG->STATS_PRO_HINT_TEXT;?>', 'style':'width:'+positiveWidth+'%'} );
			var remainder = document.createElement("div", {'class':'remainder','title':negativeHint+'%  <?php echo $LNG->STATS_CON_HINT_TEXT;?>', 'style':'width:'+negativeWidth+'%'} );
			votebar.appendChild(bar);
			votebar.appendChild(remainder);

			if (positiveWidth > negativeWidth) {
				bar.innerHTML += positiveHint+"%";
			} else if (negativeWidth > positiveWidth) {
				remainder.innerHTML += negativeHint+"%";
			} else {
				bar.innerHTML += positiveHint+"%";
				remainder.innerHTML += negativeHint+"%";
			}
		}

		container.appendChild(votebar);
	}
}

function countDownIssueTimer(dt, container, message, withSeconds) {
	var end = new Date(dt);

	var _second = 1000;
	var _minute = _second * 60;
	var _hour = _minute * 60;
	var _day = _hour * 24;
	var timer;

	var inow = new Date();
	var idistance = end.getTime() - inow.getTime();

	var idays = Math.floor(idistance / _day);
	var daylabel = '<?php echo $LNG->NODE_COUNTDOWN_DAYS; ?>';
	if (idays == 1) {
		daylabel = '<?php echo $LNG->NODE_COUNTDOWN_DAY; ?>';
	}
	var ihours = Math.floor((idistance % _day) / _hour);
	var hourlabel = '<?php echo $LNG->NODE_COUNTDOWN_HOURS; ?>';
	if (ihours == 1) {
		hourlabel = '<?php echo $LNG->NODE_COUNTDOWN_HOUR; ?>';
	}
	var iminutes = Math.floor((idistance % _hour) / _minute);
	var minlabel = '<?php echo $LNG->NODE_COUNTDOWN_MINUTES; ?>';
	if (iminutes == 1) {
		minlabel = '<?php echo $LNG->NODE_COUNTDOWN_MINUTE; ?>';
	}
	var iseconds = Math.floor((idistance % _minute) / _second);
	var seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECONDS; ?>';
	if (iseconds == 1) {
		seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECOND; ?>';
	}

	if (withSeconds) {
		container.innerHTML = message+' <span style="padding-left:10px;">'+idays+' '+daylabel+'</span><span style="padding-left:10px;">'+ihours+hourlabel+'</span><span style="padding-left:10px;">'+iminutes+minlabel+'</span><span style="padding-left:10px;">'+iseconds+seclabel+'</span>';
	} else {
		container.innerHTML = message+' <span style="padding-left:10px;">'+idays+' '+daylabel+'</span><span style="padding-left:10px;">'+ihours+hourlabel+'</span><span style="padding-left:10px;">'+iminutes+minlabel+'</span>';
	}

	function showRemaining() {
		var now = new Date();
		var distance = end.getTime() - now.getTime();
		if (distance < 0) {
			clearInterval(timer);
			if (containerid) {
				const divcontainer = document.getElementById('div-'+containerid);
				divcontainer.style.className = "remainderall";
			}
			container.update = '<?php echo $LNG->NODE_COUNTDOWN_CLOSED; ?>';
			return;
		}
		var days = Math.floor(distance / _day);
		var hours = Math.floor((distance % _day) / _hour);
		var minutes = Math.floor((distance % _hour) / _minute);
		var seconds = Math.floor((distance % _minute) / _second);

		daylabel = '<?php echo $LNG->NODE_COUNTDOWN_DAYS; ?>';
		if (days == 1) {
			daylabel = '<?php echo $LNG->NODE_COUNTDOWN_DAY; ?>';
		}
		hourlabel = '<?php echo $LNG->NODE_COUNTDOWN_HOURS; ?>';
		if (hours == 1) {
			hourlabel = '<?php echo $LNG->NODE_COUNTDOWN_HOUR; ?>';
		}
		minlabel = '<?php echo $LNG->NODE_COUNTDOWN_MINUTES; ?>';
		if (minutes == 1) {
			minlabel = '<?php echo $LNG->NODE_COUNTDOWN_MINUTE; ?>';
		}

		if (withSeconds) {
			seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECONDS; ?>';
			if (seconds == 1) {
				seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECOND; ?>';
			}
			container.innerHTML = message+' <span style="padding-left:10px;">'+days+' '+daylabel+'</span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span><span style="padding-left:10px;">'+seconds+seclabel+'</span>';
		} else {
			container.innerHTML = message+' <span style="padding-left:10px;">'+days+' '+daylabel+'</span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span>';
		}
	}

	timer = setInterval(showRemaining, 1000);
	timers.push(timer);
}

function countDownIssueVoteTimer(dt, container, message) {
	var end = new Date(dt);

	var _second = 1000;
	var _minute = _second * 60;
	var _hour = _minute * 60;
	var _day = _hour * 24;
	var timer;

	var inow = new Date();
	var idistance = end.getTime() - inow.getTime();
	var idays = Math.floor(idistance / _day);
	var ihours = Math.floor((idistance % _day) / _hour);
	var iminutes = Math.floor((idistance % _hour) / _minute);
	var iseconds = Math.floor((idistance % _minute) / _second);
	container.innerHTML = message+' <span style="padding-left:10px;">'+idays + ' days</span><span style="padding-left:10px;">' + ihours + 'hrs</span><span style="padding-left:10px;">' + iminutes + 'mins</span>';

	function showRemainingVotes() {
		var now = new Date();
		var distance = end.getTime() - now.getTime();
		if (distance < 0) {
			clearInterval(timer);
			//document.getElementById('div-'+containerid).style.className = "remainderall";
			container.update = '<?php echo $LNG->NODE_COUNTDOWN_CLOSED; ?>';
			return;
		}
		var days = Math.floor(distance / _day);
		var hours = Math.floor((distance % _day) / _hour);
		var minutes = Math.floor((distance % _hour) / _minute);
		var seconds = Math.floor((distance % _minute) / _second);

		var daylabel = '<?php echo $LNG->NODE_COUNTDOWN_DAYS; ?>';
		if (days == 1) {
			daylabel = '<?php echo $LNG->NODE_COUNTDOWN_DAY; ?>';
		}
		var hourlabel = '<?php echo $LNG->NODE_COUNTDOWN_HOURS; ?>';
		if (hours == 1) {
			hourlabel = '<?php echo $LNG->NODE_COUNTDOWN_HOUR; ?>';
		}
		var minlabel = '<?php echo $LNG->NODE_COUNTDOWN_MINUTES; ?>';
		if (minutes == 1) {
			minlabel = '<?php echo $LNG->NODE_COUNTDOWN_MINUTE; ?>';
		}

		//container.innerHTML = message+' <span style="padding-left:10px;">'+days+' '+daylabel+'</span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span>';

		var seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECONDS; ?>';
		if (seconds == 1) {
			seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECOND; ?>';
		}
		container.innerHTML = message+' <span style="padding-left:10px;">'+days+' '+daylabel+'<?php echo $LNG->NODE_COUNTDOWN_DAYS; ?></span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span><span style="padding-left:10px;">'+seconds+seclabel+'</span>';
	}

	timer = setInterval(showRemainingVotes, 1000);
	timers.push(timer);
}

function recalculateVotes(focalnodeid) {
	const debatestatsvotes = document.getElementById('debatestatsvotes'+focalnodeid);
	if (focalnodeid && debatestatsvotes) {
		var votes = debatestatsvotes.votes;
		var total = 0;
		for (var key in votes) {
		    if (votes.hasOwnProperty(key)) {
		        total += parseInt(votes[key]);
		    }
		}
		debatestatsvotes.innerHTML = total;
	}
}

function recalculatePeople() {
	loadParticipationStats();
}

function clearAllIssueTimers() {
	for (var i = 0; i < timers.length; i++) {
	    clearInterval(timers[i]);
	}
}

/**
 * Add the current phase to the right of the Issue bar.
 */
function addIssuePhase(phase, node, countdowntableDiv, mainheading) {

	if (countdowntableDiv) {
		if (phase == DISCUSS_PHASE) {
			if (mainheading) {
				var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.appendChild(bar);
				var discussionend = 0;
				if (node.properties[0] && node.properties[0].discussionend) {
					discussionend = convertUTCTimeToLocalDate(node.properties[0].discussionend);
				} else if (node.properties.discussionend) {
					discussionend = convertUTCTimeToLocalDate(node.properties.discussionend);
				}
				countDownIssueTimer(discussionend.getTime(), bar, '<?php echo $LNG->NODE_COUNTDOWN_DISCUSSION_END; ?>', true);
			} else {
				var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.appendChild(bar);
				bar.innerHTML += "<?php echo $LNG->ISSUE_PHASE_CURRENT.': '.$LNG->ISSUE_PHASE_DISCUSS; ?>";
			}
		} else if (phase == REDUCE_PHASE) {
			if (mainheading) {
				var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.appendChild(bar);
				var lemonend = 0;
				if (node.properties[0] && node.properties[0].lemoningend) {
					lemonend = convertUTCTimeToLocalDate(node.properties[0].lemoningend);
				} else if (node.properties.lemoningend) {
					lemonend = convertUTCTimeToLocalDate(node.properties.lemoningend);
				}
				countDownIssueTimer(lemonend.getTime(), bar, '<?php echo $LNG->NODE_COUNTDOWN_REDUCING_END; ?>', true);
			} else {
				var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.appendChild(bar);
				bar.innerHTML = "<?php echo $LNG->ISSUE_PHASE_CURRENT.': '.$LNG->ISSUE_PHASE_REDUCE; ?>";
			}
		} else if (phase == DECIDE_PHASE) {
			if (mainheading) {
				var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.appendChild(bar);
				var voteend = 0;
				if (node.properties[0] && node.properties[0].votingend) {
					voteend = convertUTCTimeToLocalDate(node.properties[0].votingend);
				} else if (node.properties.votingend) {
					voteend = convertUTCTimeToLocalDate(node.properties.votingend);
				}
				countDownIssueTimer(voteend.getTime(), bar, '<?php echo $LNG->NODE_COUNTDOWN_DECIDING_END; ?>', true);
			} else {
				var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.appendChild(bar);
				bar.innerHTML += "<?php echo $LNG->ISSUE_PHASE_CURRENT.': '.$LNG->ISSUE_PHASE_DECIDE; ?>";
			}
		} else if (phase == TIMED_VOTEON_PHASE) {
			var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.appendChild(bar);
			bar.innerHTML+="<?php echo $LNG->NODE_VOTE_COUNTDOWN_OPEN; ?>";
		} else if (phase == TIMED_VOTEPENDING_PHASE) {
			var votestart = 0;
			if (node.properties[0] && node.properties[0].votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties[0].votingstart);
			} else if (node.properties.votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties.votingstart);
			}
			var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.appendChild(bar);
			countDownIssueVoteTimer(votestart.getTime(), bar, "<?php echo $LNG->NODE_VOTE_COUNTDOWN_START; ?>");
		} else if (phase == OPEN_VOTEON_PHASE || phase == OPEN_PHASE) {
			var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.appendChild(bar);
			bar.innerHTML = "<?php echo $LNG->NODE_VOTE_COUNTDOWN_OPEN; ?>";
		} else if (phase == OPEN_VOTEPENDING_PHASE) {
			var votestart = 0;
			if (node.properties[0] && node.properties[0].votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties[0].votingstart);
			} else if (node.properties.votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties.votingstart);
			}
			var bar = document.createElement("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.appendChild(bar);
			countDownIssueVoteTimer(votestart.getTime(), bar, "<?php echo $LNG->NODE_VOTE_COUNTDOWN_START; ?>");
		}
	}
}



var TREE_OPEN_ARRAY = {};

/**
 * Open and close the knowledge tree
 */
function toggleItem(section, uniQ) {
	const sectionobj = document.getElementById(section);
    sectionobj.toggle();

    if(sectionobj.visible()){
    	TREE_OPEN_ARRAY[section] = true;
    	document.getElementById('explorearrow'+uniQ).src='<?php echo $HUB_FLM->getImagePath("arrow-down-blue.png"); ?>';
	} else {
    	TREE_OPEN_ARRAY[section] = false;
		document.getElementById('explorearrow'+uniQ).src='<?php echo $HUB_FLM->getImagePath("arrow-right-blue.png"); ?>';
	}
}

/**
 * Render a list of connection nodes
 */
function displayConnectionNodes(objDiv, nodes,start,includeUser,uniqueid, childCountSpan, parentrefreshhandler){
	if (uniqueid == undefined) {
		uniqueid = 'idea-list';
	}

	var lOL = document.createElement("ol", {'start':start, 'class':'idea-list-ol'});
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = document.createElement("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.appendChild(iUL);
			var blobDiv = document.createElement("div", {'class':'idea-blob-list'});
			var blobNode = renderConnectionNode(nodes[i].cnode, uniqueid,nodes[i].cnode.role[0].role,includeUser, childCountSpan, parentrefreshhandler);
			blobDiv.appendChild(blobNode);
			iUL.appendChild(blobDiv);
		}
	}

	objDiv.appendChild(lOL);
}

/**
 * Render the given node from an associated connection in the knowledge tree.
 * @param node the node object do render
 * @param uniQ is a unique id element prepended to the nodeid to form an overall unique id within the currently visible site elements
 * @param role the role object for this node
 * @param includeUser whether to include the user image and link
 * @param childCountSpan The element into which to put the running total of children in this conneciotn tree..
 * @param parentrefreshhandler a statment to eval after actions have occurred to refresh this list. - NOT USED?
 */
function renderConnectionNode(node, uniQ, role, includeUser, childCountSpan, parentrefreshhandler){

	if (childCountSpan === undefined) {
		childCountSpan = null;
	}

	var originaluniQ = uniQ;

	if(role === undefined){
		role = node.role[0].role;
	}

	var nodeuser = null;
	// JSON structure different if coming from popup where json_encode used.
	if (node.users[0].userid) {
		nodeuser = node.users[0];
	} else {
		nodeuser = node.users[0].user;
	}
	var connection = node.connection;
	var user = null;
	if (connection && connection.users) {
		user = connection.users[0].user;
	}

	//needs to check if embedded as a snippet
	var breakout = "";
	if(top.location != self.location){
		breakout = " target='_blank'";
	}

	var focalnodeid = "";
	if (node.focalnodeid) {
		focalnodeid = node.focalnodeid;
	}
	var focalrole = "";
	var connrole = role;
	var otherend = "";
	if (connection) {
		uniQ = connection.connid+uniQ;
		var fN = connection.from[0].cnode;
		var tN = connection.to[0].cnode;
		if (node.nodeid == fN.nodeid) {
			connrole = connection.fromrole[0].role;
			focalrole = tN.role[0].role;
			otherend = tN;
		} else {
			connrole = connection.torole[0].role;
			focalrole = fN.role[0].role;
			otherend = fN;
		}
	} else {
		uniQ = node.nodeid + uniQ;
	}

	var iDiv = document.createElement("div", {'style':'padding:0px;margin:0px;'});
	var ihDiv = document.createElement("div", {'style':'padding:0px;margin:0px;'});
	var itDiv = document.createElement("div", {'class':'idea-title','style':'padding:0px;'});

	var nodeTable = document.createElement( 'table' );
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";
	//nodeTable.border = "1";

	itDiv.appendChild(nodeTable);

	var row = nodeTable.insertRow(-1);

	// ADD THE ARROW IF REQUIRED
	if (node.istop) {
		var expandArrow = null;
		if (EVIDENCE_TYPES_STR.indexOf(role.name) != -1 || role.name == "Challenge"
			|| role.name == "Issue" || role.name == "Solution" ) {

			var arrowCell = row.insertCell(-1);
			arrowCell.vAlign="middle";
			arrowCell.align="left";

			if (TREE_OPEN_ARRAY["desc"+uniQ] && TREE_OPEN_ARRAY["desc"+uniQ] == true) {
				expandArrow = document.createElement('img',{'id':'explorearrow'+uniQ, 'name':'explorearrow', 'alt':'>', 'title':'<?php echo $LNG->NODE_DEBATE_TOGGLE; ?>', 'style':'visibility:visible;margin-top:3px;','align':'left','border':'0','src': '<?php echo $HUB_FLM->getImagePath("arrow-down-blue.png"); ?>'});
				expandArrow.uniqueid = uniQ;
			} else {
				expandArrow = document.createElement('img',{'id':'explorearrow'+uniQ, 'name':'explorearrow', 'alt':'>', 'title':'<?php echo $LNG->NODE_DEBATE_TOGGLE; ?>', 'style':'visibility:visible;margin-top:3px;','align':'left','border':'0','src': '<?php echo $HUB_FLM->getImagePath("arrow-right-blue.png"); ?>'});
				expandArrow.uniqueid = uniQ;
			}
			expandArrow.onclick = function (){ toggleItem("treedesc"+uniQ,uniQ); };
			arrowCell.appendChild(expandArrow);
		}
	} else {
		var lineCell = row.insertCell(-1);
		//lineCell.style.borderLeft = "1px solid white"; // needed for IE to draw the background image
		lineCell.width="15px;"
		lineCell.vAlign="middle";
		var lineDiv = document.createElement('div',{'class':'graylinewide', 'style':'width:100%;'});
		lineCell.appendChild(lineDiv);
	}

	var textCell = row.insertCell(-1);
	textCell.vAlign="middle";
	textCell.align="left";
	var textCellDiv = document.createElement("div", { 'id':'textDivCell'+uniQ, 'name':'textDivCell', 'class':'whiteborder', 'style':'padding:3px;'});
	textCellDiv.nodeid = node.nodeid;
	textCellDiv.focalnodeid = node.focalnodeid;
	textCellDiv.nodetype = role.name;
	textCellDiv.parentuniQ = originaluniQ;
	if (connection) {
		textCellDiv.connection = connection;
	}

	if (node.nodeid == node.focalnodeid) {
		var bordercolor = 'plainborder';
		var backcolor = 'whiteback';
		var nodetype = role.name;
		if (nodetype == 'Issue') {
			bordercolor = 'issueborder';
			backcolor = 'issueback';
		} else if (nodetype == 'Idea') {
			bordercolor = 'ideaborder';
			backcolor = 'ideaback';
		} else if (nodetype == 'Solution') {
			bordercolor = 'solutionborder';
			backcolor = 'solutionback';
		} else if (nodetype == 'Pro') {
			bordercolor = 'proborder';
			backcolor = 'proback';
		} else if (nodetype == 'Con') {
			bordercolor = 'conborder';
			backcolor = 'conback';
		} else if (nodetype == 'Comment') {
			bordercolor = 'plainborder';
			backcolor = 'plainback';
		} 

		textCellDiv = document.createElement("div", { 'id':'textDivCell'+uniQ, 'name':'textDivCell','class':bordercolor+' '+backcolor, 'style':'padding:3px;'});
		textCellDiv.nodeid = node.nodeid;
		textCellDiv.nodetype = role.name;
		textCellDiv.focalnodeid = node.focalnodeid;
		textCellDiv.parentuniQ = originaluniQ;
		if (connection) {
			textCellDiv.connection = connection;
		}
	}

	var toolbarCell = row.insertCell(-1);
	toolbarCell.vAlign="middle";
	toolbarCell.align="left";
	toolbarCell.width="80";

	textCell.appendChild(textCellDiv);

	var dStr = "";
	if (user != null) {
		var cDate = new Date(connection.creationdate*1000);
		var dStr = "<?php echo $LNG->NODE_CONNECTED_BY; ?> "+user.name+ " on "+cDate.format(DATE_FORMAT)+' - <?php echo $LNG->NODE_TOGGLE_HINT;?>'
	}

	// ADD THE NODE ICON
	var nodeArea = document.createElement("a", {'class':'itemtext', 'name':'nodeArea', 'style':'padding-top:2px;','title':dStr} );
	nodeArea.nodeid = node.nodeid;
	if (typeof node.focalnodeid != 'undefined') {
		nodeArea.focalnodeid = node.focalnodeid;
	}

	var alttext = getNodeTitleAntecedence(role.name, false);
	if (connrole.image != null && connrole.image != "") {
		var nodeicon = document.createElement('img',{'alt':alttext, 'title':alttext, 'style':'height:24px;padding-right:5px;','align':'left','border':'0','src': URL_ROOT + connrole.image});
		nodeArea.appendChild(nodeicon);
	} else {
		nodeArea.innerHTML += alttext+": ";
	}

	// ADD THE NODE LABEL
	textCellDiv.appendChild(nodeArea);
	if (node.nodeid == node.focalnodeid) {
		nodeArea.className = "itemtextwhite";
	} else {
		nodeArea.className = "itemtext unselectedlabel";
	}

	var nodeextra = ""; //getNodeTitleAntecedence(role.name, true);
	nodeArea.innerHTML += "<span style='font-style:italic'>"+nodeextra+"</span>"+node.name;

	nodeArea.href= "#";
	nodeArea.onclick = function (){
		ideatoggle3("desc"+uniQ, uniQ, node.nodeid,"desc",role.name);
	};

	if (node.istop) {
		if (EVIDENCE_TYPES_STR.indexOf(role.name) != -1 || role.name == "Challenge"
			|| role.name == "Issue" || role.name == "Solution" || role.name == "Idea"
			|| role.name == "Pro"  || role.name == "Con" || role.name == "Comment") {

			var childCount = document.createElement('div',{'style':' margin-left:5px;margin-right:5px;margin-top:2px;', 'title':'<?php echo $LNG->NODE_DEBATE_TREE_COUNT_HINT; ?>'});
			childCount.innerHTML += "(";
			childCountSpan = document.createElement('span',{'name':'toptreecount'});
			childCountSpan.id = 'toptreecount'+uniQ;
			childCountSpan.innerHTML += '1';
			childCountSpan.uniqueid = uniQ;
			childCount.appendChild(childCountSpan);
			childCount.innerHTML += ")";
			toolbarCell.appendChild(childCount);
		}
	}
	
	ihDiv.appendChild(itDiv);

	var iwDiv = document.createElement("div", {'class':'idea-wrapper'});
	var imDiv = document.createElement("div", {'class':'idea-main'});
	var idDiv = document.createElement("div", {'class':'idea-detail'});

	var expandDiv = document.createElement("div", {'id':'treedesc'+uniQ,'class':'ideadata', 'style':'padding:0px;margin-left:0px;color:Gray;'} );

	if (node.children && node.children.length > 0) {
		if (expandArrow && expandArrow != null) {
			expandArrow.src='<?php echo $HUB_FLM->getImagePath("arrow-down-blue.png"); ?>';
		}
		expandDiv.style.display = 'block';
	} else {
		expandDiv.style.display = 'none';
	}

	var hint = alttext+": "+node.name;
	hint += " <?php echo $LNG->NODE_GOTO_PARENT_HINT; ?>"

	/**
	 * This is for the rollover hint around the vertical line - background image 21px wide 1px line in middle
	 * This was the only way to get it to work in all four main browsers!!!!!
   	 **/
	var expandTable = document.createElement( 'table', {'style':'empty-cells:show;border-collapse:collapse;'} );
	expandTable.height="100%";
	var expandrow = expandTable.insertRow(-1);
	expandrow.style.height="100%";
	if (node.istop) {
		expandTable.style.marginLeft = "9px";
	} else {
		expandTable.style.marginLeft = "26px";
	}

	var lineCell = expandrow.insertCell(-1);
	lineCell.style.borderLeft = "1px solid white"; // needed for IE to draw the background image
	lineCell.width="5px;";
	lineCell.style.marginLeft="3px";
	lineCell.title=hint;
	lineCell.className="grayline";
	lineCell.onclick = function () {
		var pos = getPosition(textCellDiv);
		window.scroll(0,pos.y-3);
	};

	var childCell = expandrow.insertCell(-1);
	childCell.vAlign="top";
	childCell.align="left";
	childCell.style.padding="0px";
	childCell.style.margin="0px";

	expandDiv.appendChild(expandTable);

	if (node.istop) {
		expandDiv.style.marginLeft = "22px";
	} else {
		expandDiv.style.marginLeft = "4px";
	}

	/** EXPAND DIV **/
	var innerexpandDiv = document.createElement("div", {'id':'desc'+uniQ,'class':'ideadata', 'style':'padding-left:20px;color:Gray;display:none;'} );

	var nodeTable = document.createElement( 'table' );
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";

	innerexpandDiv.appendChild(nodeTable);

	var row = nodeTable.insertRow(-1);
	var nextCell = row.insertCell(-1);
	nextCell.vAlign="middle";
	nextCell.align="left";

	// USER ICON NAME AND CREATIONS DATES
	var userbar = document.createElement("div", {'style':'clear:both;margin-bottom:5px;'} );
	if (includeUser == true) {
		// Add right side with user image and date below
		var iuDiv = document.createElement("div", {'class':'idea-user2', 'style':'clear:both;'});
		var userimageThumb = document.createElement('img',{'alt':nodeuser.name, 'title': nodeuser.name, 'style':'padding-right:5px;', 'border':'0','src': nodeuser.thumb});
		iuDiv.appendChild(userimageThumb)
		userbar.appendChild(iuDiv);
	}

	var iuDiv = document.createElement("div", {'style':''});

	var dStr = "";
	var cDate = new Date(node.creationdate*1000);
	dStr += "<b><?php echo $LNG->NODE_ADDED_ON; ?> </b>"+ cDate.format(DATE_FORMAT) + "<br/>";
	dStr += "<b><?php echo $LNG->NODE_ADDED_BY; ?> </b>"+ nodeuser.name + "";
	iuDiv.innerHTML += "dStr";

	userbar.appendChild(iuDiv);

	nextCell.appendChild(userbar);

	// image
	if (node.imagethumbnail != null && node.imagethumbnail != "") {
		var imageDiv = document.createElement("div");

		var originalurl = "";
		if(node.urls && node.urls.length > 0){
			for (var i=0 ; i< node.urls.length; i++){
				var urlid = node.urls[i].url.urlid;
				if (urlid == node.imageurlid) {
					originalurl = node.urls[i].url.url;
					break;
				}
			}
		}
		if (originalurl == "") {
			originalurl = node.imagethumbnail;
		}
		var iconlink = document.createElement('a', {
			'href':originalurl,
			'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
		var nodeicon = document.createElement('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'style':'clear:both;padding-right:5px;','align':'left', 'border':'0','src': URL_ROOT + node.imagethumbnail});
		iconlink.appendChild(nodeicon);
		imageDiv.appendChild(iconlink);
		const row = nodeTable.insertRow(-1);
		const nextCell = row.insertCell(-1);
		nextCell.vAlign="middle";
		nextCell.align="left";		
		nextCell.appendChild(imageDiv);
		//nodeArea.innerHTML += alttext+": ";
	} else if (node.image != null && node.image != "") {
		var imageDiv = document.createElement("div");
		var nodeicon = document.createElement('img',{'alt':alttext, 'title':alttext, 'style':'clear:both;padding-right:5px;','align':'left','border':'0','src': node.image});
		imageDiv.appendChild(nodeicon);
		const row = nodeTable.insertRow(-1);
		const nextCell = row.insertCell(-1);
		nextCell.vAlign="middle";
		nextCell.align="left";		
		nextCell.appendChild(imageDiv);
		nextCell.appendChild(imageDiv);
	} 

	// META DATA - DESCRIPTION, URLS ETC

 	// add urls

	if (node.urls && node.urls.length > 0) {
		var iUL = document.createElement("ul", {});
		for (var i=0 ; i< node.urls.length; i++){

			innerexpandDiv.innerHTML += '<span style="margin-right:5px;"><b><?php echo $LNG->NODE_URL_HEADING; ?></b></span>';
			var link = document.createElement("a", {'href':node.urls[i].url.url,'target':'_blank','title':'<?php echo $LNG->NODE_RESOURCE_LINK_HINT; ?>'} );
			link.innerHTML = node.urls[i].url.title;
			innerexpandDiv.appendChild(link);
		}
		innerexpandDiv.appendChild(iUL);
	}

	var dStr = "";

	if(node.description || node.hasdesc){
		dStr += '<div style="margin:0px;padding:0px;" class="idea-desc" id="desc'+uniQ+'div"><span style="margin-top: 5px;"><b><?php echo $LNG->NODE_DESC_HEADING; ?> </b></span><br>';
		if (node.description && node.description != "") {
			innerexpandDiv.description = true;
			dStr += node.description;
		}
		dStr += '</div>';
		innerexpandDiv.innerHTML += dStr;
	}

	// CHILD LISTS
	var nodes = node.children;
	if (nodes != undefined && nodes.length > 0) {

		childCell.innerHTML = '<div style="clear:both;"></div>';
		var childrenDiv = document.createElement("div", {'id':'children'+uniQ, 'style':'clear:both;margin-left:0px;padding-left:0px;margin-bottom:5px;color:Gray;display:block;'} );
		childCell.appendChild(childrenDiv);
		childCell.innerHTML += '<div style="clear:both;"></div>';
		if (expandArrow) {
			expandArrow.style.visibility = 'visible';
		}
		var parentrefreshhanlder = "";
		//"refreshchildren(\'children"+uniQ+"\', \'"+nodeid+"\', \'"+title+"\', \'"+linktype+"\', \'"+role.name+"\')";

		if (node.istop) {
			childCountSpan.innerHTML = nodes.length+1;
		} else {
			if (childCountSpan != null) {
				var countnow = parseInt(childCountSpan.innerHTML);
				var finalcount = countnow+nodes.length;
				childCountSpan.innerHTML = finalcount;
			}
		}
		displayConnectionNodes(childrenDiv, nodes, parseInt(0), true, uniQ, childCountSpan, parentrefreshhanlder);
	}

	idDiv.appendChild(innerexpandDiv);
	idDiv.appendChild(expandDiv);
	imDiv.appendChild(idDiv);
	iwDiv.appendChild(imDiv);
	iDiv.appendChild(ihDiv);
	iDiv.appendChild(iwDiv);

	return iDiv;
}

/**
 * Open and close the meta data sections - get additional stuff if required.
 */
function ideatoggle3(section, uniQ, id, sect, rolename) {
	const sectionobj = document.getElementById(section);
    sectionobj.toggle();

	const opensection = document.getElementById('open'+section);
    if(opensection){
        if(sectionobj.visible()){
			opensection.innerHTML = "&laquo;";
       } else {
            opensection.innerHTML = "&raquo;";
        }
	}

    if(sect == "desc" && sectionobj.visible() && !sectionobj.description){
		var reqUrl = SERVICE_ROOT + "&method=getnode&nodeid=" + encodeURIComponent(id);
		fetch(reqUrl, {
			method: 'POST'
		})
		.then(response => {
			if (!response.ok) {
				throw new Error('Network response was not ok');
			}
			return response.json();
		})
		.then(json => {
			jsonData = json; // Store the JSON data in a variable
			if (json.error) {
				alert(json.error[0].message);
			}
			const sectiondiv = document.getElementById(section + 'div');	
			sectiondiv.insertAdjacentHTML('beforeend', json.cnode[0].description);
			sectionobj.description = 'true';
		})
		.catch(err => {
			console.error('There was a problem with the post operation:', err);
		});		
	}
}
