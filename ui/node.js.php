<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 The Open University UK                                   *
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
function displayIdeaList(objDiv,nodes,start,includeUser,uniqueid,type,status){
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
	var lOL = new Element("ol", {'start':start, 'class':'idea-list-ol'});
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

			var iUL = new Element("li", {'id':node.nodeid, 'class':'idea-list-li'});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'id':'ideablobdiv'+myuniqueid, 'class':'idea-blob-list d-flex flex-column'});
			var blobNode = renderIdeaList(node, myuniqueid, node.role[0].role,includeUser,type,status, i);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
			if (NODE_ARGS['currentphase'] == CLOSED_PHASE && i == 2) {
				blobDiv.insert('<hr class="hrline-slim">');
			}
		}
	}
	objDiv.insert(lOL);
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
	var lOL = new Element("ol", {'start':start, 'class':'idea-list-ol'});
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

			var iUL = new Element("li", {'id':node.nodeid, 'class':'idea-list-li'});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'id':'ideablobdiv'+myuniqueid, 'class':'idea-blob-list'});
			var blobNode = renderIdeaRemovedList(node, myuniqueid, node.role[0].role,includeUser);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
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

	var lOL = new Element("div", {'start':start, 'class':'issues-div' });
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var blobDiv = new Element("div", {'class':'d-inline-block m-2'});
			var blobNode = renderIssueNode(width, height, nodes[i].cnode, uniqueid+i+start,nodes[i].cnode.role[0].role,includeUser,isActive, includeconnectedness, includevoting, cropdesc);
			blobDiv.insert(blobNode);
			lOL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
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
	var lOL = new Element("ol", {'start':start, 'class':'idea-list-ol'});
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

			var iUL = new Element("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'id':'commentblobdiv'+myuniqueid, 'class':'idea-blob-list'});
			var blobNode = renderCommentNode(nodes[i].cnode, myuniqueid, nodes[i].cnode.role[0].role,includeUser, type, status);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
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
	var lOL = new Element("ol", {'start':start, 'class':'idea-list-ol'});
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

			var iUL = new Element("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'id':'argumentblobdiv'+myuniqueid, 'class':'idea-blob-list'});
			var blobNode = renderArgumentNode(nodes[i].cnode, myuniqueid, nodes[i].cnode.role[0].role,includeUser, type, status);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
}

/**
 * Render a list of nodes in the user home data area
 */
function displayUsersNodes(objDiv,nodes,start,uniqueid){
	if (uniqueid == undefined) {
		uniqueid = 'widget-list';
	}
	var lOL = new Element("ul", {'class':'widget-list-ideas'});
	for(var i=0; i < nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = new Element("li", {'id':nodes[i].cnode.nodeid});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'class':' '});
			var blobNode = renderListNode(nodes[i].cnode, uniqueid+i+start, nodes[i].cnode.role[0].role, false);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
}

/**
 * Render a list of nodes
 */
function displaySearchNodes(objDiv,nodes,start,includeUser,uniqueid){

	if (uniqueid == undefined) {
		uniqueid = 'search-list';
	}

	var lOL = new Element("ul", {'start':start, 'style':''});
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = new Element("li", {'id':nodes[i].cnode.nodeid});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'class':'idea-blob-list'});
			var blobNode = renderListNode(nodes[i].cnode, uniqueid+i+start,nodes[i].cnode.role[0].role, includeUser);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
}

/**
 * Render a list of nodes
 */
function displayReportNodes(objDiv,nodes,start){

	objDiv.insert('<div></div>');

	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = new Element("span", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			objDiv.insert(iUL);
			var blobDiv = new Element("div", {'class':' '});
			var blobNode = renderReportNode(nodes[i].cnode,'idea-list'+i+start, nodes[i].cnode.role[0].role);
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
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
	var lOL = new Element("ol", {'start':start, 'class':'idea-list-ol'});
	for(var i=0; i< nodes.length; i++){
		if(nodes[i].cnode){
			var iUL = new Element("li", {'id':nodes[i].cnode.nodeid, 'class':'idea-list-li'});
			lOL.insert(iUL);
			var blobDiv = new Element("div", {'class':'idea-blob-list'});
			var blobNode = renderWidgetListNode(nodes[i].cnode, uniqueid+i+start,nodes[i].cnode.role[0].role,includeUser,'active');
			blobDiv.insert(blobNode);
			iUL.insert(blobDiv);
		}
	}
	objDiv.insert(lOL);
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
		var iconlink = new Element('a', {
			'href':originalurl,
			'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
 		var nodeicon = new Element('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'src': URL_ROOT + node.imagethumbnail});
 		iconlink.insert(nodeicon);
 		textCell.insert(iconlink);
 		textCell.insert(alttext+": ");
	} else if (role.image != null && role.image != "") {
 		var nodeicon = new Element('img',{'alt':alttext, 'title':alttext, 'src': URL_ROOT + role.image});
		textCell.insert(nodeicon);
	} else {
 		textCell.insert(alttext+": ");
	}

	var title = node.name;
	var exploreButton = new Element('a', {'target':'_blank', 'class':'itemtext', 'id':'desctoggle'+uniQ});
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
	exploreButton.insert(title);
	textCell.insert(exploreButton);

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

	var iDiv = new Element("div", {'class':'idea-container'});
	var ihDiv = new Element("div", {'class':'idea-header'});
	var itDiv = new Element("div", {'class':'idea-title'});

	var nodeTable = new Element( 'table' );
	nodeTable.className = "toConnectionsTable";
	if (type == "connselect") {
		nodeTable.style.cursor = 'pointer';
		Event.observe(nodeTable,'click',function (){
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
		var iconlink = new Element('a', {
			'href':originalurl,
			'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
 		var nodeicon = new Element('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'src': URL_ROOT + node.imagethumbnail});
 		iconlink.insert(nodeicon);
 		itDiv.insert(iconlink);
 		itDiv.insert(alttext+": ");
	} else if (role.image != null && role.image != "") {
 		var nodeicon = new Element('img',{'alt':alttext, 'title':alttext, 'src': URL_ROOT + role.image});
		itDiv.insert(nodeicon);
	} else {
 		itDiv.insert(alttext+": ");
	}
	itDiv.insert("<span>"+node.name+"</span>");

	leftCell.insert(itDiv);

	// Add right side with user image and date below
	var iuDiv = new Element("div", {'class':'idea-user'});

	var userimageThumb = new Element('img',{'alt':user.name, 'title': user.name, 'src': user.thumb});

	if (type == "active") {
		var imagelink = new Element('a', {
			'target':'_blank',
			'href':URL_ROOT+"user.php?userid="+user.userid,
			'title':user.name});
		if (breakout != "") {
			imagelink.target = "_blank";
		}
		imagelink.insert(userimageThumb);
		iuDiv.update(imagelink);
	} else {
		iuDiv.insert(userimageThumb)
	}

	var modDate = new Date(node.creationdate*1000);
	if (modDate) {
		var fomatedDate = modDate.format(DATE_FORMAT);
		iuDiv.insert("<div>"+fomatedDate+"</span>");
	}

	rightCell.insert(iuDiv);
	ihDiv.insert(nodeTable);

	var iwDiv = new Element("div", {'class':'idea-wrapper'});
	var imDiv = new Element("div", {'class':'idea-main'});
	var idDiv = new Element("div", {'class':'idea-detail'});
	var headerDiv = new Element("div", {'class':'idea-menus'});
	idDiv.insert(headerDiv);

	if (type == 'active') {
		var exploreButton = new Element("a", {'title':'<?php echo $LNG->NODE_EXPLORE_BUTTON_HINT; ?>'} );
		exploreButton.insert("<?php echo $LNG->NODE_EXPLORE_BUTTON_TEXT;?>");
		exploreButton.href= URL_ROOT+"explore.php?id="+node.nodeid;
		exploreButton.target = 'coheremain';

		headerDiv.insert(exploreButton);
	}

	imDiv.insert(idDiv);
	iwDiv.insert(imDiv);

	iDiv.insert(ihDiv);
	iDiv.insert(iwDiv);

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

	var iDiv = new Element("div", {'class':' '});
	var ihDiv = new Element("div", {'class':' '});
	var itDiv = new Element("div", {'class':'idea-title'});

	var nodeTable = new Element( 'table' );
	nodeTable.className = "toConnectionsTable";
	nodeTable.style.cursor = 'pointer';

	var row = nodeTable.insertRow(-1);
	var leftCell = row.insertCell(-1);

	var rightCell = row.insertCell(-1);
	rightCell.vAlign="top";
	rightCell.align="right";

	var alttext = getNodeTitleAntecedence(role.name, false);
	if (role.image != null && role.image != "") {
		var nodeicon = new Element('img',{'alt':alttext, 'title':alttext, 'src': URL_ROOT + role.image});
		itDiv.insert(nodeicon);
	} else {
		itDiv.insert(alttext+": ");
	}

	Event.observe(itDiv,'click',function (){
		loadSelecteditem(node);
	});

	itDiv.insert("<span class='itemtext' title='Select this item'>"+node.name+"</span>");

	leftCell.insert(itDiv);

	if (includeUser) {
		var iuDiv = new Element("div", {'class':'idea-user2'});
		var userimageThumb = new Element('img',{'alt':user.name, 'title': user.name, 'src': user.thumb});
		iuDiv.insert(userimageThumb)
		rightCell.insert(iuDiv);
	}

	ihDiv.insert(nodeTable);

	iDiv.insert(ihDiv);

	var iwDiv = new Element("div", {'class':'idea-wrapper'});
	iDiv.insert(iwDiv);

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

	var iDiv = new Element("div", {'class':'card border-0 my-2'});

	var nodetableDiv = new Element("div", {'class':'card-body pb-0'});
	var nodeTable = new Element( 'div', {'class':'nodetableDebate border border-2'} );

	nodetableDiv.insert(nodeTable);

	var row = new Element( 'div', {'class':'d-flex flex-row'} );
	nodeTable.insert(row);

	var imageCell = new Element( 'div', {'class':'p-2 issue-img'} );
	row.insert(imageCell);

	if (notStarted) {
		var imageObj = new Element('img',{'alt':node.name, 'title': node.name, 'src': node.image});
		imageCell.insert(imageObj);
		imageCell.title = '<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>';
	} else {
		var imageObj = new Element('img',{'alt':node.name, 'title': node.name, 'src': node.image});
		var imagelink = new Element('a', {
			'href':URL_ROOT+"explore.php?id="+node.nodeid,
		});

		imagelink.insert(imageObj);
		imageCell.insert(imagelink);
		imageCell.title = '<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>';
	}

	var textCell = new Element( 'div', {'class':'p-2'} );
	row.insert(textCell);

	var textDiv = new Element('div', {'class':'issue-title'});
	textCell.insert(textDiv);

	var title = node.name;
	var description = node.description;

	if (mainheading) {
		var exploreButton = new Element('h1');
		textDiv.insert(exploreButton);
		exploreButton.insert(title);
	} else {
		if (notStarted) {
			var exploreButton = new Element('span', {'class':' '});
			textDiv.insert(exploreButton);
			exploreButton.insert(title);
		} else {
			var exploreButton = new Element('a', {'title':'<?php echo $LNG->NODE_DETAIL_BUTTON_HINT; ?>'});
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

			exploreButton.insert(croppedtitle);
		}

		textDiv.insert(exploreButton);
	}

	if (mainheading) {
		var textDivinner = new Element('div', {'class':' '});
		textDivinner.insert(description);
		textDiv.insert(textDivinner);
	} else {
		if (description != "" && title.length <=80) {
			var plaindesc = removeHTMLTags(description);
			var hint = plaindesc;
			var croplength = 110-title.length;
			if (plaindesc && plaindesc.length > croplength) {
				hint = plaindesc;
				var plaincrop = plaindesc.substr(0,croplength)+"...";
				textDiv.insert('<p title="'+hint+'">'+plaincrop+'</p>');
			} else {
				textDiv.insert('<p>'+plaindesc+'</p>');
			}
		}
	}

	var rowToolbar = new Element( 'div', {'class':'d-flex justify-content-between'} );
	nodeTable.insert(rowToolbar);

	var toolbarCell = new Element( 'div', {'class':'d-flex align-items-end'} );
	rowToolbar.insert(toolbarCell);

	var userDiv = new Element("div", {'class':'m-1'} );
	toolbarCell.insert(userDiv);

	if (includeUser) {
		var userimageThumb = new Element('img',{'alt':user.name, 'title': user.name, 'src': user.thumb});
		if (type == "active") {
			var imagelink = new Element('a', {
				'href':URL_ROOT+"user.php?userid="+user.userid,
				'title':user.name});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.insert(userimageThumb);
			userDiv.insert(imagelink);
		} else {
			userDiv.insert(userimageThumb)
		}

		var userDateDiv = new Element("div", {'class':'m-1'} );
		toolbarCell.insert(userDateDiv);

		var cDate = new Date(node.creationdate*1000);
		var dateDiv = new Element('div',{'title':'<?php echo $LNG->NODE_ADDED_ON; ?>', 'class':'added_on'});
		dateDiv.insert(cDate.format(DATE_FORMAT));

		userDateDiv.insert(dateDiv);
	}

	var toolbarDivOuter = new Element("div", {'class':'d-flex align-items-end'} );
	rowToolbar.insert(toolbarDivOuter);

	var toolbarDiv = new Element("div", {'class':'m-1 issue-tools'} );
	toolbarDivOuter.insert(toolbarDiv);

	// IF OWNER ADD EDIT / DEL ACTIONS
	if (type == "active") {
		if (USER == user.userid) {
			var edit = new Element('img',{'alt':'<?php echo $LNG->EDIT_BUTTON_TEXT;?>', 'title': '<?php echo $LNG->EDIT_BUTTON_HINT_ISSUE;?>', 'src': '<?php echo $HUB_FLM->getImagePath("edit.png"); ?>'});
			Event.observe(edit,'click',function (){loadDialog('editissue',URL_ROOT+"ui/popups/issueedit.php?nodeid="+node.nodeid, 770,550)});
			toolbarDiv.insert(edit);
			var del = new Element('img',{'id':'deletebutton'+uniQ, 'alt':'<?php echo $LNG->NO_DELETE_BUTTON_ALT;?>', 'title': '<?php echo $LNG->NO_DELETE_BUTTON_HINT;?>', 'src': '<?php echo $HUB_FLM->getImagePath("delete-off.png"); ?>'});
			toolbarDiv.insert(del);
			if (node.connectedness == 0) {
				var deletename = node.name;
				del.src = '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>';
				del.alt = '<?php echo $LNG->DELETE_BUTTON_ALT;?>';
				del.title = '<?php echo $LNG->DELETE_BUTTON_HINT;?>';
				Event.observe(del,'click',function (){
					deleteNode(node.nodeid, deletename, role.name);
				});
			}
		}
	}

	if (type == "active" && !issueClosed) {
		if (USER != "") {
			var followbutton = new Element('img', {'class':' '});
			followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
			followbutton.setAttribute('alt', 'Follow');
			followbutton.setAttribute('id','follow'+node.nodeid);
			followbutton.nodeid = node.nodeid;
			followbutton.style.cursor = 'pointer';

			toolbarDiv.insert(followbutton);

			if (node.userfollow && node.userfollow == "Y") {
				Event.observe(followbutton,'click',function (){ unfollowNode(node, this, "") } );
				followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("following.png"); ?>');
				followbutton.setAttribute('title', '<?php echo $LNG->NODE_UNFOLLOW_ITEM_HINT; ?>');
			} else {
				Event.observe(followbutton,'click',function (){ followNode(node, this, "") } );
				followbutton.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("follow.png"); ?>');
				followbutton.setAttribute('title', '<?php echo $LNG->NODE_FOLLOW_ITEM_HINT; ?>');
			}
		} else {
			toolbarDiv.insert("<img onclick='$(\"loginsubmit\").click(); return true;' title='<?php echo $LNG->WIDGET_FOLLOW_SIGNIN_HINT; ?>' src='<?php echo $HUB_FLM->getImagePath("followgrey.png"); ?>' />");
		}
	}

	if (includevoting == true && !notStarted) {
		if (role.name == 'Issue'
			|| role.name == 'Solution'
			|| role.name == 'Pro'
			|| role.name == 'Con') {

			// vote for
			var voteforimg = new Element('img');
			voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-grey3.png"); ?>');
			voteforimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_FOR_ICON_ALT; ?>');
			voteforimg.setAttribute('id','nodefor'+node.nodeid);
			voteforimg.nodeid = node.nodeid;
			voteforimg.vote='Y';
			toolbarDiv.insert(voteforimg);
			if (!node.positivevotes) {
				node.positivevotes = 0;
			}

			if (issueClosed) {
				toolbarDiv.insert('<b><span id="nodevotefor'+node.nodeid+'">'+node.positivevotes+'</span></b>');
			} else {
				if(USER != ""){
					voteforimg.style.cursor = 'pointer';
					if (node.uservote && node.uservote == 'Y') {
						Event.observe(voteforimg,'click',function (){ deleteNodeVote(this) } );
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled3.png"); ?>');
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!node.uservote || node.uservote != 'Y') {
						Event.observe(voteforimg,'click',function (){ nodeVote(this) } );
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
					}
					toolbarDiv.insert('<b><span id="nodevotefor'+node.nodeid+'">'+node.positivevotes+'</span></b>');
				} else {
					voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_LOGIN_HINT; ?>');
					toolbarDiv.insert('<b><span id="nodevotefor'+node.nodeid+'">'+node.positivevotes+'</span></b>');
				}
			}

			// vote against
			var voteagainstimg = new Element('img');
			voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-grey3.png"); ?>');
			voteagainstimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_AGAINST_ICON_ALT; ?>');
			voteagainstimg.setAttribute('id', 'nodeagainst'+node.nodeid);
			voteagainstimg.nodeid = node.nodeid;
			voteagainstimg.vote='N';
			toolbarDiv.insert(voteagainstimg);
			if (!node.negativevotes) {
				node.negativevotes = 0;
			}
			if (issueClosed) {
				toolbarDiv.insert('<b><span id="nodevoteagainst'+node.nodeid+'">'+node.negativevotes+'</span></b>');
			} else {
				if(USER != ""){
					voteagainstimg.style.cursor = 'pointer';
					if (node.uservote && node.uservote == 'N') {
						Event.observe(voteagainstimg,'click',function (){ deleteNodeVote(this) } );
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled3.png"); ?>');
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!node.uservote || node.uservote != 'N') {
						Event.observe(voteagainstimg,'click',function (){ nodeVote(this) } );
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
					}
					toolbarDiv.insert('<b><span id="nodevoteagainst'+node.nodeid+'">'+node.negativevotes+'</span></b>');
				} else {
					voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_LOGIN_HINT; ?>');
					toolbarDiv.insert('<b><span id="nodevoteagainst'+node.nodeid+'">'+node.negativevotes+'</span></b>');
				}
			}
		}
	}

	if (mainheading) {
		var jsonldButton = new Element("span", {'title':'<?php echo $LNG->GRAPH_JSONLD_HINT;?>'});
		var jsonldButtonicon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('json-ld-data-24.png'); ?>", 'border':'0', 'alt':'<?php echo $LNG->GRAPH_JSONLD_HINT;?>'});
		jsonldButton.insert(jsonldButtonicon);
		var jsonldButtonhandler = function() {
			var code = URL_ROOT+'api/views/'+NODE_ARGS['nodeid'];
			textAreaPrompt('<?php echo $LNG->GRAPH_JSONLD_MESSAGE; ?>', code, "", "", "");
		};
		Event.observe(jsonldButton,"click", jsonldButtonhandler);
		toolbarDiv.insert(jsonldButton);
	}

	iDiv.insert(nodetableDiv);

	var countdowntableDiv = "";

	var phase = "";
	if (NODE_ARGS['currentphase']) {
		phase = NODE_ARGS['currentphase'];
	} else {
		phase = calculateIssuePhase(node);
	}

	if (phase == PENDING_PHASE) {
		var start = convertUTCTimeToLocalDate(node.startdatetime);
		countdowntableDiv = new Element("div", {'class':'issue-status issuepending d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		var countdownbar = new Element("div", {'id':'timer'+node.nodeid} );
		countdowntableDiv.insert(countdownbar);
		iDiv.insert(countdowntableDiv);
		countDownIssueTimer(start.getTime(), countdownbar, '<?php echo $LNG->NODE_COUNTDOWN_START; ?>', false);
	} else if (phase == TIMED_PHASE || phase == TIMED_NOVOTE_PHASE
			|| phase == TIMED_VOTEPENDING_PHASE || phase == TIMED_VOTEON_PHASE
			|| phase == DISCUSS_PHASE || phase == REDUCE_PHASE || phase == DECIDE_PHASE ) {

		var end = convertUTCTimeToLocalDate(node.enddatetime);
		countdowntableDiv = new Element("div", {'class':'issue-status issueopen d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		let countdownbar = new Element("div", {'id':'timer'+node.nodeid} );
		countdowntableDiv.insert(countdownbar);
		iDiv.insert(countdowntableDiv);

		if (mainheading) {
			countDownIssueTimer(end.getTime(), countdownbar, '<?php echo $LNG->NODE_COUNTDOWN_END; ?>', false);
		} else {
			countdownbar.insert("<?php echo $LNG->NODE_COUNTDOWN_TIMED; ?>");
		}
	} else if (phase == CLOSED_PHASE) {
		countdowntableDiv = new Element("div", {'class':'issue-status issueclosed d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		var countdownbar = new Element("div", {'id':'timer'+node.nodeid} );
		countdownbar.insert("<?php echo $LNG->NODE_COUNTDOWN_CLOSED; ?>");
		countdowntableDiv.insert(countdownbar);
		iDiv.insert(countdowntableDiv);
	} else if (phase == OPEN_PHASE
			|| phase == OPEN_VOTEPENDING_PHASE || phase == OPEN_VOTEON_PHASE) {
		countdowntableDiv = new Element("div", {'class':'issue-status issueopen d-flex justify-content-between', 'id':'div-timer'+node.nodeid});
		var countdownbar = new Element("div", {'id':'timer'+node.nodeid} );
		countdownbar.insert("<?php echo $LNG->NODE_COUNTDOWN_OPEN; ?>");
		countdowntableDiv.insert(countdownbar);
		iDiv.insert(countdowntableDiv);
	}

	if (countdowntableDiv != "") {
		addIssuePhase(phase, node, countdowntableDiv, mainheading);
	}

	if (includestats) {
		var statstableDiv = new Element("div", {'class':'card-footer debates border-0 bg-white py-0 text-center'});
		var statsTable = new Element( 'div', {'class':'nodetable'} );
		statstableDiv.insert(statsTable);

		var innerRowStats = new Element( 'div', {'class':'d-flex justify-content-between'} );
		statsTable.insert(innerRowStats);

		var innerStatsCellViews = new Element( 'div', {'class':'col-auto'} );
		innerRowStats.insert(innerStatsCellViews);

		var viewslabelspan = new Element("strong");
		viewslabelspan.insert('<?php echo $LNG->DEBATE_BLOCK_STATS_VIEWS; ?>');
		innerStatsCellViews.insert(viewslabelspan);

		var viewnumspan = new Element("span", {'id':'debateviewstats'+node.nodeid});
		viewnumspan.insert(node.viewcount);
		innerStatsCellViews.insert(viewnumspan);

		if ((NODE_ARGS['issueHasLemoning'] && NODE_ARGS['currentphase'] == DECIDE_PHASE)
				|| (NODE_ARGS['issueHasLemoning'] && NODE_ARGS['currentphase'] == CLOSED_PHASE)) {
			var innerStatsCellDebates = new Element( 'div', {'class':'col-auto'} );
			innerRowStats.insert(innerStatsCellDebates);
			var idealabelspan = new Element("strong");
			idealabelspan.insert(' <?php echo $LNG->DEBATE_BLOCK_STATS_ISSUES_ALL; ?>');
			innerStatsCellDebates.insert(idealabelspan);
			var ideanumspan = new Element("span", {'id':'debatestatsideas'+node.nodeid});
			ideanumspan.insert('-');
			innerStatsCellDebates.insert(ideanumspan);

			var innerStatsCellDebatesNow = new Element( 'div', {'class':'col-auto'} );
			innerRowStats.insert(innerStatsCellDebatesNow);
			var idealabelspan = new Element("strong");
			idealabelspan.insert(' <?php echo $LNG->DEBATE_BLOCK_STATS_ISSUES_REMAINING; ?>');
			innerStatsCellDebatesNow.insert(idealabelspan);
			var ideanumspan = new Element("span", {'id':'debatestatsideasnow'+node.nodeid});
			ideanumspan.insert('-');
			innerStatsCellDebatesNow.insert(ideanumspan);
		} else {
			var innerStatsCellDebates = new Element( 'div', {'class':'col-auto'} );
			innerRowStats.insert(innerStatsCellDebates);
			var idealabelspan = new Element("strong");
			idealabelspan.insert(' <?php echo $LNG->DEBATE_BLOCK_STATS_ISSUES; ?>');
			innerStatsCellDebates.insert(idealabelspan);
			var ideanumspan = new Element("span", {'id':'debatestatsideas'+node.nodeid});
			ideanumspan.insert('-');
			innerStatsCellDebates.insert(ideanumspan);
		}

		var innerStatsCellPeople = new Element( 'div', {'class':'col-auto'} );
		innerRowStats.insert(innerStatsCellPeople);

 		var peoplelabelspan = new Element("strong");
		peoplelabelspan.insert(' <?php echo $LNG->DEBATE_BLOCK_STATS_PEOPLE; ?>');
		innerStatsCellPeople.insert(peoplelabelspan);

		var peoplenumspan = new Element("span", {'id':'debatestatspeople'+node.nodeid});
		peoplenumspan.insert('-');
		innerStatsCellPeople.insert(peoplenumspan);
		peoplenumspan.people = new Array();
		peoplenumspan.people.push(user.userid);

		var innerStatsCellVotes = new Element( 'div', {'class':'col-auto'} );
		innerRowStats.insert(innerStatsCellVotes);

		var votelabelspan = new Element("strong");
		votelabelspan.insert('<?php echo $LNG->DEBATE_BLOCK_STATS_VOTES; ?>');
		innerStatsCellVotes.insert(votelabelspan);

		var votenumspan = new Element("span", {'id':'debatestatsvotes'+node.nodeid});
		votenumspan.insert('-');
		innerStatsCellVotes.insert(votenumspan);
		votenumspan.votes = new Array();

		if (mainheading) {
			//loadStats(node.nodeid, peoplenumspan, ideanumspan, votenumspan);
		} else {
			loadStats(node.nodeid, peoplenumspan, ideanumspan, votenumspan);
		}
		iDiv.insert(statstableDiv);
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

	var itDiv = new Element("div", {'class':'idea-title boxshadowsquaredarker'});

	if (USER != "" && NODE_ARGS['currentphase'] == REDUCE_PHASE) {
		Event.observe(itDiv,"dragover", function(e){
			e.dataTransfer.dropEffect = 'copy';
			e.preventDefault();
			e.stopPropagation();
			return true;
		});

		Event.observe(itDiv,"dragenter", function(e){
			e.preventDefault();
			e.stopPropagation();
			return true;
		});

		Event.observe(itDiv,"drop", function(e){
			var name = e.dataTransfer.getData('text');
			e.preventDefault();
			e.stopPropagation();
			if (name == 'addlemon') {
				document.body.style.cursor = 'wait';
				var callback = function () {
					var lemoncount = parseInt($('lemoncount'+node.nodeid).innerHTML);
					if (lemoncount == 0) {
						$('lemondiv'+node.nodeid).style.display = 'block';
					}
					$('lemoncount'+node.nodeid).innerHTML = lemoncount + 1;
					$('lemonbasketcount').innerHTML = parseInt($('lemonbasketcount').innerHTML) - 1;
					document.body.style.cursor = 'default';
				}
				lemonNode(node.nodeid, NODE_ARGS['nodeid'], callback);
				return true;
			} else {
				return false;
			}
		});
	}

	var nodeTable = new Element('table', {'class':'table'});
	nodeTable.className = "toConnectionsTable";
	itDiv.insert(nodeTable);

	var row = nodeTable.insertRow(-1);
	row.setAttribute('name','idearowitem');
	row.setAttribute('id','idearowitem'+uniQ);
	row.setAttribute('uniQ',uniQ);
	row.setAttribute('nodeid',node.nodeid);

	if (NODE_ARGS['currentphase'] == CLOSED_PHASE) {
		if (i == 0) {
			var winCell = row.insertCell(-1);
			winCell.setAttribute('vAlign','top');
			var img = new Element("img", {'id':node.nodeid});
			img.src = "<?php echo $HUB_FLM->getImagePath('first-place.png'); ?> ";
			img.alt = "1st place ";
			winCell.insert(img);
		} else if (i == 1) {
			var winCell = row.insertCell(-1);
			winCell.setAttribute('vAlign','top');
			var img = new Element("img", {'id':node.nodeid});
			img.src = "<?php echo $HUB_FLM->getImagePath('second-place.png'); ?> ";
			img.alt = "2nd place ";
			winCell.insert(img);
		} else if (i == 2) {
			var winCell = row.insertCell(-1);
			winCell.setAttribute('vAlign','top');
			var img = new Element("img", {'id':node.nodeid});
			img.src = "<?php echo $HUB_FLM->getImagePath('third-place.png'); ?> ";
			img.alt = "3rd place ";
			winCell.insert(img);
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
		var inChk = new Element("input",{'class':'nodecheck','type':'checkbox','id':'nodecheck'+node.nodeid, 'value':node.nodeid, 'aria-label':'test'});
		Event.observe(inChk,'click',function (){
			var toAdd = getSelectedNodeIDs($('tab-content-idea-list'));
			if(toAdd.length < 2) {
				$('mergeideadiv').style.display='none';
			}
		} );
		boxCell.insert(inChk);
	}
	<?php } ?>

	//update stats
	if (node.parentid) {
		if (connection) {
			var votestats = $('debatestatsvotes'+node.parentid);
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

			var voteDiv = new Element("div", {
				'name':'editformvotedividea',
				'class':'editformvotedividea',
				'id':'editformvotedividea'+uniQ
			});
			voteCell.insert(voteDiv);

			var toRoleName = getNodeTitleAntecedence(connection.torole[0].role.name, false);

			// vote for
			var voteforimg = new Element('img');
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
			voteDiv.insert(voteforimg);
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
							var votevar = $('votebardiv'+uniQ);
							var other = $(this.connid+'against');
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
							var votevar = $('votebardiv'+uniQ);
							votevar.positivevotes = parseInt(votevar.positivevotes)-1;
							drawVotesBar(votevar, uniQ, node.parentid);
							recalculatePeople();
						}
						voteforimg.oldtitle = '<?php echo $LNG->NODE_VOTE_FOR_SOLUTION_HINT; ?> '+toRoleName;
						if (connection.uservote && connection.uservote == 'Y') {
							Event.observe(voteforimg,'click',function () {
								deleteConnectionVote(this)
							} );
							voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>');
							voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
						} else if (!connection.uservote || connection.uservote != 'Y') {
							Event.observe(voteforimg,'click',function () {
								connectionVote(this);
							});

							voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
							voteforimg.setAttribute('title', voteforimg.oldtitle);
						}
					}
				} else {
					voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_LOGIN_HINT; ?>');
				}
			}

			var voteforcount = new Element('span');
			voteforcount.setAttribute('id',connection.connid+'votefor');
			voteforcount.setAttribute('class',' ');
			voteforcount.insert(connection.positivevotes);
			voteDiv.insert(voteforcount);


			// vote against
			var voteagainstimg = new Element('img');
			voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-grey.png"); ?>');
			voteagainstimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_AGAINST_ICON_ALT; ?>');
			voteagainstimg.setAttribute('id', connection.connid+'against');
			voteagainstimg.nodeid = node.nodeid;
			voteagainstimg.connid = connection.connid;
			voteagainstimg.vote='N';
			voteDiv.insert(voteagainstimg);
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
							var votebar = $('votebardiv'+uniQ);
							var other = $(this.connid+'for');
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
							var votebar = $('votebardiv'+uniQ);
							votebar.negativevotes = parseInt(votebar.negativevotes)-1;
							drawVotesBar(votebar, uniQ, node.parentid);
							recalculatePeople();
						}

						voteagainstimg.oldtitle = '<?php echo $LNG->NODE_VOTE_AGAINST_SOLUTION_HINT; ?> '+toRoleName;
						if (connection.uservote && connection.uservote == 'N') {
							Event.observe(voteagainstimg,'click',function (){
								deleteConnectionVote(this)
							} );
							voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>');
							voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
						} else if (!connection.uservote || connection.uservote != 'N') {
							Event.observe(voteagainstimg,'click',function (){
								connectionVote(this);
							});
							voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
							voteagainstimg.setAttribute('title', voteagainstimg.oldtitle);
						}
					}
				} else {
					voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_LOGIN_HINT; ?>');
				}
			}

			var voteagainstcount = new Element('span');
			voteagainstcount.setAttribute('id',connection.connid+'voteagainst');
			voteagainstcount.setAttribute('class',' ');
			voteagainstcount.insert(connection.negativevotes);
			voteDiv.insert(voteagainstcount);
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
		var iuDiv = new Element("div", {
			'id':'editformuserdividea'+uniQ,
			'class':'idea-user2'
		});

		var userimageThumb = new Element('img',{'alt':nodeuser.name, 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = new Element('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.insert(userimageThumb);
			iuDiv.update(imagelink);
		} else {
			iuDiv.insert(userimageThumb)
		}

		userCell.insert(iuDiv);
	}

	var textCell = row.insertCell(-1);
	textCell.classList.add("idea-section");

	var textDiv = new Element("div", {
		'id':'textdividea'+uniQ,
		'class':'textdividea'
	});
	textCell.insert(textDiv);
	

	var title = node.name;

	var textspan = new Element("span", {
		'id':'desctoggle'+uniQ,
		'class':'desctoggle'
	});
	textspan.insert(title);
	textspan.datadisabled = false;
	textDiv.insert(textspan);
	Event.observe(textspan,'click',function (){
		if (textspan.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	});

	if (USER == nodeuser.userid && type == 'active' && NODE_ARGS['issueDiscussing']) {
		var editbutton = new Element("img", {			
			'class':'idea-edit',
			'src':'<?php echo $HUB_FLM->getImagePath("edit.png"); ?>',
			'title':'<?php echo $LNG->NODE_EDIT_SOLUTION_ICON_HINT; ?>',
		});
		textDiv.insert(editbutton);
		Event.observe(editbutton,'click',function (){
			editInline(uniQ, 'idea');
		});

		if (!node.otheruserconnections || node.otheruserconnections == 0) {
			var deletename = node.name;
			var del = new Element('img',{		
				'class':'idea-delete',
				'alt':'<?php echo $LNG->DELETE_BUTTON_ALT;?>', 
				'title': '<?php echo $LNG->DELETE_BUTTON_HINT;?>', 
				'src': '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>'
			});
			Event.observe(del,'click',function (){
				var callback = function () {
					refreshSolutions();
				}
				deleteNode(node.nodeid, deletename, role.name, callback);
			});
			textDiv.insert(del);
		} else {
			var del = new Element('img',{ 	
				'class':'idea-delete',
				'alt':'<?php echo $LNG->NO_DELETE_BUTTON_ALT;?>', 
				'title': '<?php echo $LNG->NO_DELETE_BUTTON_HINT;?>', 
				'src': '<?php echo $HUB_FLM->getImagePath("delete-off.png"); ?>'
			});
			textDiv.insert(del);
		}
	}

	if (node.urls && node.urls.length > 0) {
		var menuButton = new Element('img',{
			'class':'idea-url',
			'alt':'>','width':'16','height':'16',
			'src': '<?php echo $HUB_FLM->getImagePath("nodetypes/Default/reference-32x32.png"); ?>'
		});
		textDiv.appendChild(menuButton);
		Event.observe(menuButton,'mouseout',function (event){
			hideBox('toolbardiv'+uniQ);
		});
		Event.observe(menuButton,'mouseover',function (event) {
			var position = getPosition(this);
			var panel = $('toolbardiv'+uniQ);
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
		});
		var toolbarDiv = new Element("div", {'id':'toolbardiv'+uniQ, 'style':'left:-1px;top:-1px;clear:both;position:absolute;display:none;z-index:60;padding:5px;width:200px;border:1px solid gray;background:white'} );
		Event.observe(toolbarDiv,'mouseout',function (event){
			hideBox('toolbardiv'+uniQ);
		});
		Event.observe(toolbarDiv,'mouseover',function (event){ showBox('toolbardiv'+uniQ); });
		textDiv.appendChild(toolbarDiv);

		for(var i=0; i< node.urls.length; i++){
			if(node.urls[i].url){
				var next = node.urls[i].url;
				var url = next.url;
				var weblink = new Element("a", {'class':' ','target':'_blank'});
				weblink.href = url;
				weblink.insert(url);
				toolbarDiv.insert(weblink);
			}
		}
	}

	if (type == 'active') {
		var more = new Element('img',{
			'class':'idea-built',
			'style':'display:none',
			'alt':'built from', 
			'title': 'built from', 'src': '<?php echo $HUB_FLM->getImagePath("desc.png"); ?>'
		});
		Event.observe(more,'click',function (){
			loadDialog('builtfroms',URL_ROOT+"ui/popups/builtfroms.php?nodeid="+node.nodeid, 800,550);
		});
		textDiv.insert(more);
		loadBuiltFromsCount(more, node.nodeid);

		<?php if (isset($_SESSION['IS_MODERATOR']) && $_SESSION['IS_MODERATOR']){ ?>
		if (NODE_ARGS['issueDiscussing']) {
			var splitbutton = new Element("button", {
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
			splitbutton.insert('<?php echo $LNG->FORM_BUTTON_SPLIT; ?>');
			textDiv.insert(splitbutton);
			Event.observe(splitbutton,'click',function (){
				loadDialog('split',URL_ROOT+"ui/popups/split.php?nodeid="+node.nodeid+"&debateid="+node.parentid+"&groupid="+node.groupid, 770,550);
			});
		}
		<?php } ?>
	}

	// LEMONING
	if (USER != "" && NODE_ARGS['currentphase'] == REDUCE_PHASE) {
		var lemonigDiv = new Element('div', { 'style':'float:right;width:100px;', 'name':'lemondiv','id':'lemondiv'+node.nodeid});
		if (node.userlemonvote > 0) {
			lemonigDiv.style.display = 'block';
		} else {
			lemonigDiv.style.display = 'none';
		}

		var minuslemon = new Element('span', {'class':'lemoningbuttons'});
		minuslemon.insert('&#8211');
		Event.observe(minuslemon,"click", function(e){
			document.body.style.cursor = 'wait';
			var callback = function () {
				var lemoncount = parseInt($('lemoncount'+node.nodeid).innerHTML);
				if (lemoncount == 1) {
					$('lemondiv'+node.nodeid).style.display = 'none';
				}
				$('lemoncount'+node.nodeid).innerHTML = lemoncount - 1;
				$('lemonbasketcount').innerHTML = parseInt($('lemonbasketcount').innerHTML) + 1;
				document.body.style.cursor = 'default';
			}
			unlemonNode(node.nodeid, NODE_ARGS['nodeid'], callback);
		});
		lemonigDiv.insert(minuslemon);

		var lemonimg = new Element('img', {'src':'<?php echo $HUB_FLM->getImagePath("lemon22.png"); ?>',  'class':'lemonimg' });
		lemonimg.setAttribute('draggable', 'true');
		Event.observe(lemonimg,"dragstart", function(e){
			e.dataTransfer.setData("text", node.nodeid);
			e.dataTransfer.effectAllowed = "move";
		});
		lemonigDiv.insert(lemonimg);

		var lemoncountnum = new Element('span',{'id':'lemoncount'+node.nodeid, 'class':'lemoncount'});
		lemoncountnum.insert(node.userlemonvote);
		lemonigDiv.insert(lemoncountnum);

		var pluslemon = new Element('span', {'class':'lemoningbuttons'});
		pluslemon.insert('+');
		Event.observe(pluslemon,"click", function(e){
			var remaininglemons = parseInt($('lemonbasketcount').innerHTML);
			if (remaininglemons > 0) {
				document.body.style.cursor = 'wait';
				var callback = function () {
					var lemoncount = parseInt($('lemoncount'+node.nodeid).innerHTML);
					var lemoncount = lemoncount + 1;
					if (lemoncount > 0) {
						$('lemondiv'+node.nodeid).style.display = 'block';
					}
					$('lemoncount'+node.nodeid).innerHTML = lemoncount;
					$('lemonbasketcount').innerHTML = parseInt($('lemonbasketcount').innerHTML) - 1;
					document.body.style.cursor = 'default';
				}
				lemonNode(node.nodeid, NODE_ARGS['nodeid'], callback);
			} else {
				alert('<?php echo $LNG->LEMONING_COUNT_FINISHED; ?>');
			}
		});
		lemonigDiv.insert(pluslemon);

		textDiv.insert(lemonigDiv);
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
		var votebarDiv = new Element('div',{'name':'votebardiv','id':'votebardiv'+uniQ,'class':'votebar'});
		votebarDiv.positivevotes = parseInt(connection.positivevotes);
		votebarDiv.negativevotes = parseInt(connection.negativevotes);
		votebarDiv.conpositivevotes = 0;
		votebarDiv.connegativevotes = 0;
		votebarDiv.propositivevotes = 0;
		votebarDiv.pronegativevotes = 0;
		votebarDiv.procount = 0;
		votebarDiv.concount = 0;
		textDiv.insert(votebarDiv);
		drawVotesBar(votebarDiv, uniQ, node.parentid);
	}

	if(node.description || node.hasdesc){
		var dStr = '<div class="idea-desc" id="desc'+uniQ+'div">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.insert(dStr);
	}

	var argumentLink = new Element("span", {
		'name':'ideaargumentlink',
		'id':'ideaargumentlink'+uniQ,
		'class':'active ideaargumentlink',
		'title':'<?php echo $LNG->IDEA_ARGUMENTS_HINT; ?>',
	});
	argumentLink.nodeid = node.nodeid;
	argumentLink.datadisabled = false;
	Event.observe(argumentLink,'click',function (){
		if (argumentLink.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	});

	<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']) { ?>
	if (USER == '' && NODE_ARGS['issueDiscussing']) {
		argumentLink.insert('<img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="width:16px;height:16px;vertical-align:bottom;padding-right:3px;" />');
	}
	<?php } ?>

	argumentLink.insert('<?php echo $LNG->IDEA_ARGUMENTS_LINK; ?> (');
	var argumentCount = new Element("span", {
		'id':'ideaargumentcount'+node.nodeid,
	});

	//alert(node.childrencount);

	argumentCount.insert(node.childrencount);
	argumentLink.insert(argumentCount);
	argumentLink.insert(')');

	textDiv.insert(argumentLink);

	var commentLink = new Element("span", {
		'name':'ideacommentlink',
		'id':'ideacommentlink'+uniQ,
		'class':'active ideacommentlink',
		'title':'<?php echo $LNG->IDEA_COMMENTS_HINT; ?>',
	});
	commentLink.nodeid = node.nodeid;
	commentLink.datadisabled = false;
	Event.observe(commentLink,'click',function (){
		if (commentLink.datadisabled == false) {
			ideatoggle('comments'+uniQ, uniQ, node.nodeid, 'comments', role.name);
		}
	});

	commentLink.insert('<?php echo $LNG->IDEA_COMMENTS_LINK; ?>');
	var commentCount = new Element("span", {'id':'ideacommentscount'+node.nodeid});
	commentCount.insert('0');
	commentLink.insert(' (');
	commentLink.insert(commentCount);
	commentLink.insert(')');

	textDiv.insert(commentLink);

	// IF NOT LOGGED IN AND IN DISCUSSION PHASE, ADD CONTRIBUTE BUTTON TO GO TO LOGIN
	<?php if (!isset($USER->userid)) {
		?>
	if (USER == '' && NODE_ARGS['issueDiscussing']) {
			var signinlink = new Element("a", {
				'href':'<?php echo $CFG->homeAddress."ui/pages/login.php?ref="; ?>'+NODE_ARGS["ref"],
				'title':'<?php echo $LNG->DEBATE_CONTRIBUTE_LINK_HINT; ?>',
				'class':'lightgreenbutton',
				'style':'float:left;margin-left:30px;font-size:10pt;margin-top:2px;',
			});
			signinlink.insert('<?php echo $LNG->DEBATE_CONTRIBUTE_LINK_TEXT; ?>');
			textDiv.insert(signinlink);
		}
	<?php } ?>

	/** ADD THE EDIT FORM FOR THE IDEA **/
	if (USER == user.userid && type == 'active' && NODE_ARGS['issueDiscussing']) {
		var editDiv = new Element("fieldset", {
			'class':'editformdividea',
			'name':'editformdividea',
			'id':'editformdividea'+uniQ,
			'style':'display:none;'
		});

		var legend = new Element("legend", {});
		var legendtitle = new Element("h2", {'class':'editing-header',});
		legendtitle.insert('<?php echo $LNG->EXPLORE_EDITING_ARGUMENT_TITLE; ?>');
		legend.insert(legendtitle);
		editDiv.insert(legend);

		var editideaid = new Element("input", {
			'name':'editideaid',
			'id':'editideaid'+uniQ,
			'type':'hidden',
			'value':node.nodeid,
		});
		editDiv.insert(editideaid);
		var editnodetypeid = new Element("input", {
			'name':'editideanodetypeid',
			'id':'editideanodetypeid'+uniQ,
			'type':'hidden',
			'value':role.roleid,
		});
		editDiv.insert(editnodetypeid);

		var rowDiv1 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		editDiv.insert(rowDiv1);
		var editideaname = new Element("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
			'id':'editideaname'+uniQ,
			'name':'editideaname',
			'value':node.name,
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
		});
		rowDiv1.insert(editideaname);

		var rowDiv2 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		editDiv.insert(rowDiv2);
		var editideadesc = new Element("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
			'id':'editideadesc'+uniQ,
			'name':'editideadesc',
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
		});
		editideadesc.insert(node.description);
		rowDiv2.insert(editideadesc);

		var rowDiv4 = new Element("div", {
			'class':'mb-2',
			'id':'linksdivedit'+uniQ,
		});
		rowDiv4.linkcount = 0;
		editDiv.insert(rowDiv4);

		if (node.urls && node.urls.length > 0) {
			rowDiv4.linkcount = node.urls.length-1;
			for(var i=0; i< node.urls.length; i++){
				if(node.urls[i].url){
					var next = node.urls[i].url;
					var urlid = next.urlid;
					var url = next.url;
					var weblink = new Element("input", {
						'class':'form-control',
						'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
						'id':'argumentlinkedit'+uniQ+i,
						'name':'argumentlinkedit'+uniQ+'[]',
						'value':url,
						'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
					});
					weblink.urlid = urlid;
					rowDiv4.insert(weblink);
				}
			}
		} else {
			rowDiv4.linkcount = 0;
			var weblink = new Element("input", {
				'class':'form-control',
				'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
				'id':'argumentlinkedit'+uniQ+0,
				'name':'argumentlinkedit'+uniQ+'[]',
				'value':'',
				'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			});
			rowDiv4.insert(weblink);
		}

		var rowDiv5 = new Element("div", {'class':'my-3'});
		editDiv.insert(rowDiv5);
		var addURL = new Element("a", {
			'href':'javascript:void(0)',
			'class':'hgrinput',
		});
		addURL.insert('<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>');
		Event.observe(addURL,'click',function (){
			insertIdeaLink(uniQ, 'edit');
		});
		rowDiv5.insert(addURL);
		var rowDiv3 = new Element("div", {
			'class':'formrowsm',
		});
		editDiv.insert(rowDiv3);
		var editideasave = new Element("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'editidea',
			'name':'editidea',
			'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>',
		});
		Event.observe(editideasave,'click',function (){
			editIdeaNode(node, uniQ, 'idea', type, includeUser, status);
		});

		rowDiv3.insert(editideasave);
		var editideacancel = new Element("input", {
			'type':'button',
			'class':'btn btn-secondary ms-2',
			'id':'cancelidea',
			'name':'editidea',
			'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>',
		});
		Event.observe(editideacancel,'click',function (){
			cancelEditAction(uniQ, 'idea');
		});

		rowDiv3.insert(editideacancel);

		textCell.insert(editDiv);
	}

	/** COMMENTS LIST **/
	var expandDiv = new Element("div", {
		'name':'commentsdiv',
		'id':'commentsdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'comments'+uniQ,
		'class':'ideadata',
		'style':'display:none;'
	});
	itDiv.insert(expandDiv);

	var kidscommentTable = new Element('table', {'style':'width:100%'});
	expandDiv.insert(kidscommentTable);
	var rowcomment = kidscommentTable.insertRow(-1);
	var commentCell = rowcomment.insertCell(-1);

	var commentHeading = new Element('h3');
	commentHeading.style.marginBottom = "2px";
	commentHeading.style.marginTop = "5px";
	commentHeading.style.color = "black";
	commentHeading.style.fontWeight = "normal";
	commentHeading.insert('<?php echo $LNG->IDEA_COMMENTS_CHILDREN_TITLE; ?> (');
	var commentCount = new Element('span', {'id':'count-comment'+uniQ});
	commentCount.insert('0');
	commentHeading.insert(commentCount);
	commentHeading.insert(')');
	commentCell.insert(commentHeading);

	var commentKidsDiv = new Element('div', {
		'id':'commentslist'+uniQ,
		'style':'width:100%;',
	});
	commentKidsDiv.style.borderTop = "1px solid #D8D8D8";
	commentCell.insert(commentKidsDiv);

	<?php if (isset($_SESSION['IS_MODERATOR']) && $_SESSION['IS_MODERATOR']){ ?>

	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var addCommentDiv = new Element("div", {
			'name':'addformdivcomment',
			'id':'addformdivcomment'+uniQ,
		});

		var rowDiv1 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		addCommentDiv.insert(rowDiv1);

		var addcommentname = new Element("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
			'id':'addcommentname'+uniQ,
			'name':'addcommentname',
			'value':'',
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
		});
		rowDiv1.insert(addcommentname);

		var rowDiv2 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		addCommentDiv.insert(rowDiv2);
		var addcommentdesc = new Element("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
			'id':'addcommentdesc'+uniQ,
			'name':'addcommentdesc',
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
		});
		rowDiv2.insert(addcommentdesc);

		var rowDiv3 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		addCommentDiv.insert(rowDiv3);
		var addcommentsave = new Element("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'addcomment',
			'name':'addcomment',
			'value':'<?php echo $LNG->FORM_BUTTON_SUBMIT; ?>',
		});
		Event.observe(addcommentsave,'click',function (){
			addCommentNode(node, uniQ, 'comment', type, includeUser, status);
		});
		rowDiv3.insert(addcommentsave);

		commentCell.insert(addCommentDiv);
	}
	<?php } ?>

	/** PRO AND CON LISTS **/
	var kidsTable = new Element('table', {
		'name':'ideaforagainstdiv',
		'id':'ideaforagainstdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'arguments'+uniQ,
		'class':'ideaforagainsttable table',
		'style':'display:none;'
	});
	itDiv.insert(kidsTable);

	var row = kidsTable.insertRow(-1);
	row.width="100%";

	var forCell = row.insertCell(-1);
	forCell.vAlign = "top";
	forCell.align = "left";
	forCell.className = "for-against";

	var forHeading = new Element('h3');
	forHeading.classList.add("forHeading");
	forHeading.insert('<?php echo $LNG->NODE_CHILDREN_EVIDENCE_PRO; ?> (');
	var forCount = new Element('span', {'id':'count-support'+uniQ});
	forCount.insert('0');
	forHeading.insert(forCount);
	forHeading.insert(')');
	forCell.insert(forHeading);

	var forKidsDiv = new Element('div', {'id':'supportkidsdiv'+uniQ});
	forCell.insert(forKidsDiv);

	<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']){ ?>
	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var addProDiv = new Element("div", {
			'name':'addformdivpro',
			'id':'addformdivpro'+uniQ,
		});

		var rowDiv1 = new Element("div", {
			'class':'formrowsm mt-2 mb-2',
		});
		addProDiv.insert(rowDiv1);

		var addproname = new Element("input", {
			'aria-label':'<?php echo $LNG->FORM_PRO_LABEL_TITLE; ?>',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_PRO_LABEL_TITLE; ?>',
			'id':'addproname'+uniQ,
			'name':'addproname',
			'value':'',
		});
		rowDiv1.insert(addproname);

		var rowDiv2 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		addProDiv.insert(rowDiv2);
		var addprodesc = new Element("textarea", {
			'aria-label':'<?php echo $LNG->FORM_PRO_LABEL_DESC; ?>',
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_PRO_LABEL_DESC; ?>',
			'id':'addprodesc'+uniQ,
			'name':'addprodesc',
		});
		rowDiv2.insert(addprodesc);

		var rowDiv3 = new Element("div", {
			'class':'formrowsm',
			'id':'linksdivpro'+uniQ,
		});
		rowDiv3.linkcount = 0;
		addProDiv.insert(rowDiv3);

		var weblink = new Element("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			'id':'argumentlinkpro'+uniQ+0,
			'name':'argumentlinkpro'+uniQ+'[]',
			'value':'',
			'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
		});
		rowDiv3.insert(weblink);

		var rowDiv4 = new Element("div", {
			'class':'my-3',
		});
		addProDiv.insert(rowDiv4);
		var addURL = new Element("a", {
			'class':'hgrinput',
			'href':'javascript:void(0)',
		});
		addURL.insert('<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>');
		Event.observe(addURL,'click',function (){
			insertArgumentLink(uniQ, 'pro');
		});
		rowDiv4.insert(addURL);

		var rowDiv5 = new Element("div", {
			'class':'formrowsm',
		});
		addProDiv.insert(rowDiv5);
		var addprosave = new Element("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'addprosave',
			'name':'addprosave',
			'value':'<?php echo $LNG->FORM_BUTTON_SUBMIT; ?>',
		});
		Event.observe(addprosave,'click',function (){
			addArgumentNode(node, uniQ, 'pro', 'Pro', type, includeUser, status);
		});
		rowDiv5.insert(addprosave);

		forCell.insert(addProDiv);
	}
	<?php } ?>

	var conCell = row.insertCell(-1);
	conCell.vAlign = "top";
	conCell.align = "left";
	conCell.className = "for-against";

	var conHeading = new Element('h3', {'class':'conHeading'});
	conHeading.insert('<?php echo $LNG->NODE_CHILDREN_EVIDENCE_CON; ?> (');
	var conCount = new Element('span', {'id':'count-counter'+uniQ, 'class':'count-counter'});
	conCount.insert('0');
	conHeading.insert(conCount);
	conHeading.insert(')');
	conCell.insert(conHeading);

	var conKidsDiv = new Element('div', {'id':'counterkidsdiv'+uniQ });
	conCell.insert(conKidsDiv);

	<?php if (isset($_SESSION['HUB_CANADD']) && $_SESSION['HUB_CANADD']){ ?>
	if (type == 'active' && NODE_ARGS['issueDiscussing']) {
		var addConDiv = new Element("div", {
			'name':'addformdivcon',
			'id':'addformdivcon'+uniQ,
		});

		var rowDiv1 = new Element("div", {
			'class':'formrowsm mt-2 mb-2',
		});
		addConDiv.insert(rowDiv1);

		var addconname = new Element("input", {
			'aria-label':'<?php echo $LNG->FORM_CON_LABEL_TITLE; ?>',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_CON_LABEL_TITLE; ?>',
			'id':'addconname'+uniQ,
			'name':'addconname',
			'value':'',
		});
		rowDiv1.insert(addconname);

		var rowDiv2 = new Element("div", {
			'class':'formrowsm mb-2',
		});
		addConDiv.insert(rowDiv2);
		var addcondesc = new Element("textarea", {
			'aria-label':'<?php echo $LNG->FORM_CON_LABEL_DESC; ?>',
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_CON_LABEL_DESC; ?>',
			'id':'addcondesc'+uniQ,
			'name':'addcondesc',
		});
		rowDiv2.insert(addcondesc);

		var rowDiv3 = new Element("div", {
			'class':'formrowsm',
			'id':'linksdivcon'+uniQ,
		});
		rowDiv3.linkcount = 0;
		addConDiv.insert(rowDiv3);

		var weblink = new Element("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			'id':'argumentlinkcon'+uniQ+0,
			'name':'argumentlinkcon'+uniQ+'[]',
			'value':'',
			'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
		});
		rowDiv3.insert(weblink);

		var rowDiv4 = new Element("div", {
			'class':'my-3',
		});
		addConDiv.insert(rowDiv4);
		var addURL = new Element("a", {
			'class':'hgrinput',
			'href':'javascript:void(0)',
		});
		addURL.insert('<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>');
		Event.observe(addURL,'click',function (){
			insertArgumentLink(uniQ, 'con');
		});
		rowDiv4.insert(addURL);

		var rowDiv5 = new Element("div", {
			'class':'formrowsm',
		});
		addConDiv.insert(rowDiv5);
		var addconsave = new Element("input", {
			'type':'button',
			'class':'btn btn-primary',
			'id':'addconsave',
			'name':'addconsave',
			'value':'<?php echo $LNG->FORM_BUTTON_SUBMIT; ?>',
		});
		Event.observe(addconsave,'click',function (){
			addArgumentNode(node, uniQ, 'con', 'Con', type, includeUser, status);
		});
		rowDiv5.insert(addconsave);

		conCell.insert(addConDiv);
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

	var itDiv = new Element("div", {'class':'idea-title boxshadowsquaredarker'});

	var nodeTable = new Element('table', {'style':'width:100%;margin:3px;'});
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";
	itDiv.insert(nodeTable);

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
		var iuDiv = new Element("div", {
			'id':'editformuserdividea'+uniQ,
			'class':'idea-user2',
		});

		var userimageThumb = new Element('img',{'alt':nodeuser.name, 'style':'padding-left:5px;padding-top:5px;', 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = new Element('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.insert(userimageThumb);
			iuDiv.update(imagelink);
		} else {
			iuDiv.insert(userimageThumb)
		}

		userCell.insert(iuDiv);
	}

	var textCell = row.insertCell(-1);
	textCell.vAlign="top";
	textCell.align="left";
	textCell.setAttribute('width','95%');

	var textDiv = new Element("div", {
		'id':'textdividea'+uniQ,
		'style':'clear:both;float:left;width:98%;display:block;padding-left:5px;padding-bottom:3px;'
	});
	textCell.insert(textDiv);

	var title = node.name;

	var textspan = new Element("span", {
		'id':'desctoggle'+uniQ,
		'style':'cursor:pointer;float:left;font-weight:bold;font-size:14pt'
	});
	textspan.insert(title);
	textspan.datadisabled = false;
	textDiv.insert(textspan);
	Event.observe(textspan,'click',function (){
		if (textspan.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	});

	// LEMONING
	var lemonigDiv = new Element('div', {'name':'lemondiv','id':'lemondiv'+node.nodeid,'style':'float:right;padding:0px;margin:0px;'});
	var lemonimg = new Element('img', {'src':'<?php echo $HUB_FLM->getImagePath("lemon22.png"); ?>',  'class':'lemonimgoff' });
	lemonigDiv.insert(lemonimg);
	var lemoncountnum = new Element('span',{'class':'lemoncount'});
	lemoncountnum.insert(node.lemonvotes);
	lemonigDiv.insert(lemoncountnum);
	textDiv.insert(lemonigDiv);

	if(node.description || node.hasdesc){
		var dStr = '<div style="float:left;clear:both;margin:0px;padding:0px;margin-top:5px;font-size:10pt" class="idea-desc" id="desc'+uniQ+'div">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.insert(dStr);
	}

	var argumentLink = new Element("span", {
		'name':'ideaargumentlink',
		'id':'ideaargumentlink'+uniQ,
		'class':'active',
		'style':'clear:both;float:left;display:block;font-size:10pt;margin-top:7px;font-weight:bold;',
		'title':'<?php echo $LNG->IDEA_ARGUMENTS_HINT; ?>',
	});
	argumentLink.nodeid = node.nodeid;
	argumentLink.datadisabled = false;
	Event.observe(argumentLink,'click',function (){
		if (argumentLink.datadisabled == false) {
			ideatoggle('arguments'+uniQ, uniQ, node.nodeid, 'arguments', role.name);
		}
	});

	argumentLink.insert('<?php echo $LNG->IDEA_ARGUMENTS_LINK; ?> (');
	var argumentCount = new Element("span", {
		'id':'ideaargumentcount'+node.nodeid,
	});

	//argumentCount.insert(node.childrencount);
	argumentLink.insert(argumentCount);
	argumentLink.insert(')');

	textDiv.insert(argumentLink);

	var commentLink = new Element("div", {
		'name':'ideacommentlink',
		'id':'ideacommentlink'+uniQ,
		'class':'active',
		'style':'float:left;display:block;font-size:10pt;margin-top:7px;font-weight:bold;margin-left:30px;',
		'title':'<?php echo $LNG->IDEA_COMMENTS_HINT; ?>',
	});
	commentLink.nodeid = node.nodeid;
	commentLink.datadisabled = false;
	Event.observe(commentLink,'click',function (){
		if (commentLink.datadisabled == false) {
			ideatoggle('comments'+uniQ, uniQ, node.nodeid, 'comments', role.name);
		}
	});

	commentLink.insert('<?php echo $LNG->IDEA_COMMENTS_LINK; ?>');
	var commentCount = new Element("span", {'id':'ideacommentscount'+node.nodeid});
	commentCount.insert('0');
	commentLink.insert(' (');
	commentLink.insert(commentCount);
	commentLink.insert(')');

	textDiv.insert(commentLink);

	/** COMMENTS LIST **/
	var expandDiv = new Element("div", {
		'name':'commentsdiv',
		'id':'commentsdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'comments'+uniQ,
		'class':'ideadata',
		'style':'display:none;'
	});
	itDiv.insert(expandDiv);

	var kidscommentTable = new Element('table', {'style':'clear:both;margin-top:0px;width:100%'});
	kidscommentTable.width="100%";
	kidscommentTable.style.paddingLeft = '20px';
	expandDiv.insert(kidscommentTable);
	//kidscommentTable.border = "1";
	var rowcomment = kidscommentTable.insertRow(-1);
	var commentCell = rowcomment.insertCell(-1);

	var commentHeading = new Element('h3');
	commentHeading.style.marginBottom = "2px";
	commentHeading.style.marginTop = "5px";
	commentHeading.style.color = "black";
	commentHeading.style.fontWeight = "normal";
	commentHeading.insert('<?php echo $LNG->IDEA_COMMENTS_CHILDREN_TITLE; ?> (');
	var commentCount = new Element('span', {'id':'count-comment'+uniQ});
	commentCount.insert('0');
	commentHeading.insert(commentCount);
	commentHeading.insert(')');
	commentCell.insert(commentHeading);

	var commentKidsDiv = new Element('div', {
		'id':'commentslist'+uniQ,
		'style':'width:100%;clear:both;float:left;padding-top:5px;margin-top:5px;',
	});
	commentKidsDiv.style.borderTop = "1px solid #D8D8D8";
	commentCell.insert(commentKidsDiv);

	/** PRO AND CON LISTS **/
	var kidsTable = new Element('table', {
		'name':'ideaforagainstdiv',
		'id':'ideaforagainstdiv'+uniQ,
		'nodeid':node.nodeid,
		'id':'arguments'+uniQ,
		'class':'ideaforagainsttable',
		'style':'display:none;'
	});
	kidsTable.width="100%";
	kidsTable.style.paddingLeft = '3px';
	itDiv.insert(kidsTable);
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

	var forHeading = new Element('h3');
	forHeading.style.marginBottom = "2px";
	forHeading.style.marginTop = "5px";
	forHeading.style.color = "green";
	forHeading.style.fontWeight = "normal";
	forHeading.insert('<?php echo $LNG->NODE_CHILDREN_EVIDENCE_PRO; ?> (');
	var forCount = new Element('span', {'id':'count-support'+uniQ});
	forCount.insert('0');
	forHeading.insert(forCount);
	forHeading.insert(')');
	forCell.insert(forHeading);

	var forKidsDiv = new Element('div', {'id':'supportkidsdiv'+uniQ, 'style':'width:100%;clear:both;float:left;'});
	forKidsDiv.style.paddingTop = "5px";
	forKidsDiv.style.borderTop = "1px solid #D8D8D8";
	forCell.insert(forKidsDiv);

	var conCell = row.insertCell(-1);
	conCell.vAlign="top";
	conCell.align="left";
	conCell.valign="top";
	conCell.style.paddingLeft = "10px";
	conCell.style.width = '360px';
	conCell.style.minWidth = '360px';
	conCell.width = "360px";

	var conHeading = new Element('h3');
	conHeading.style.marginBottom = "2px";
	conHeading.style.marginTop = "5px";
	conHeading.style.fontWeight = "normal";
	conHeading.insert('<?php echo $LNG->NODE_CHILDREN_EVIDENCE_CON; ?> (');
	var conCount = new Element('span', {'id':'count-counter'+uniQ, 'class':'count-counter'});
	conCount.insert('0');
	conHeading.insert(conCount);
	conHeading.insert(')');
	conCell.insert(conHeading);

	var conKidsDiv = new Element('div', {'id':'counterkidsdiv'+uniQ, 'style':'width:100%;clear:both;float:left;'});
	conKidsDiv.style.paddingTop = "5px";
	conKidsDiv.style.borderTop = "1px solid #D8D8D8";
	conCell.insert(conKidsDiv);

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

	var nodeTable = new Element('table', {'style':'width:100%'});
	nodeTable.className = "toConnectionsTable";
	nodeTable.width="100%";
	//nodeTable.border = "1";

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
	textCell.vAlign="top";
	textCell.align="left";

	var textDiv = new Element("div", {
		'id':'textdivargument'+uniQ,
	});
	textCell.insert(textDiv);

	var title = node.name;
	var textspan = new Element("span", {
		'style':'font-weight:normal;font-size:11pt',
		'id':'desctoggle'+uniQ,
	});
	textspan.insert(title);
	textDiv.insert(textspan);

	if (USER == nodeuser.userid && type == "active" && NODE_ARGS['issueDiscussing']) {
		var editbutton = new Element("img", {
			
			'class':'imagebuttonfaded',
			'style':'padding-left:10px',
			'src':'<?php echo $HUB_FLM->getImagePath("edit.png"); ?>',
			'title':'<?php echo $LNG->NODE_EDIT_SOLUTION_ICON_HINT; ?>',
		});
		textDiv.insert(editbutton);
		Event.observe(editbutton,'click',function (){
			var innertype = "pro";
			if (role.name == "Con") {
				innertype = "con";
			}
			hideAddForm(node.parentuniq, innertype);
			editInline(uniQ, 'argument');
		});

		var deletename = node.name;
		var del = new Element('img',{'style':'cursor: pointer;padding-left:5px;','alt':'<?php echo $LNG->DELETE_BUTTON_ALT;?>', 'title': '<?php echo $LNG->DELETE_BUTTON_HINT;?>', 'src': '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>'});
		Event.observe(del,'click',function (){
			var callback = function () {
				if (role.name == "Con") {
					$('counterkidsdiv'+node.parentuniq).loaded = 'false';
					loadChildArguments('counterkidsdiv'+node.parentuniq, node.parentid, '<?php echo $LNG->CONS_NAME; ?>', '<?php echo $CFG->LINK_CON_SOLUTION; ?>', 'Con', node.parentid, node.groupid, node.parentuniq, $('count-counter'+node.parentuniq), type, status, $('votebardiv'+node.parentuniq));
					refreshStats();
				} else if (role.name == 'Pro') {
					$('supportkidsdiv'+node.parentuniq).loaded = 'false';
					loadChildArguments('supportkidsdiv'+node.parentuniq, node.parentid, '<?php echo $LNG->PROS_NAME; ?>', '<?php echo $CFG->LINK_PRO_SOLUTION; ?>', 'Pro', node.parentid, node.groupid, node.parentuniq, $('count-support'+node.parentuniq), type, status, $('votebardiv'+node.parentuniq));
					refreshStats();
				}
			}
			deleteNode(node.nodeid, deletename, role.name, callback);
		});
		textDiv.insert(del);
	}

	if (node.urls && node.urls.length > 0) {
		var menuButton = new Element('img',{'alt':'>', 'style':'padding-left:10px;width:16px;height:16px;','width':'16','height':'16','src': '<?php echo $HUB_FLM->getImagePath("nodetypes/Default/reference-32x32.png"); ?>'});
		textDiv.appendChild(menuButton);
		Event.observe(menuButton,'mouseout',function (event){
			hideBox('toolbardiv'+uniQ);
		});
		Event.observe(menuButton,'mouseover',function (event) {
			var position = getPosition(this);
			var panel = $('toolbardiv'+uniQ);
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
		});
		var toolbarDiv = new Element("div", {'id':'toolbardiv'+uniQ, 'style':'left:-1px;top:-1px;clear:both;position:absolute;display:none;z-index:60;padding:5px;width:200px;border:1px solid gray;background:white'} );
		Event.observe(toolbarDiv,'mouseout',function (event){
			hideBox('toolbardiv'+uniQ);
		});
		Event.observe(toolbarDiv,'mouseover',function (event){ showBox('toolbardiv'+uniQ); });
		textDiv.appendChild(toolbarDiv);

		for(var i=0; i< node.urls.length; i++){
			if(node.urls[i].url){
				var next = node.urls[i].url;
				var url = next.url;
				var weblink = new Element("a", {'style':'clear:both;float:left;margin-bottom:6px;font-size:10pt','target':'_blank'});
				weblink.href = url;
				weblink.insert(url);
				toolbarDiv.insert(weblink);
			}
		}
	}

	if(node.description || node.hasdesc){
		var dStr = '<div style="clear:both;margin:0px;padding:0px;margin-top:3px;font-size:10pt;" class="idea-desc" id="desc'+uniQ+'div"><span style="margin-top: 5px;">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.insert(dStr);
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

		var voteDiv = new Element("div", {
			'id':'editformvotedivargument'+uniQ,
			'class':'editformvotedivargument',
		});
		//voteDiv.insert('<span style="margin-right:5px;"><?php echo $LNG->NODE_VOTE_MENU_TEXT; ?></span>');
		voteCell.insert(voteDiv);

		var toRoleName = getNodeTitleAntecedence(connection.torole[0].role.name, false);

		// vote for
		var voteforimg = new Element('img');
		voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-grey.png"); ?>');
		voteforimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_FOR_ICON_ALT; ?>');
		voteforimg.setAttribute('id', connection.connid+'for');
		voteforimg.nodeid = node.nodeid;
		voteforimg.connid = connection.connid;
		voteforimg.vote='Y';
		voteDiv.insert(voteforimg);
		if (!connection.positivevotes) {
			connection.positivevotes = 0;
		}
		voteDiv.insert('<span class="vote-count" id="'+connection.connid+'votefor">'+connection.positivevotes+'</span>');

		if (NODE_ARGS['issueVoting']) {
			if(USER != ""){
				if (nodeuser.userid == USER) {
					voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_OWN_HINT; ?>');
				} else {
					voteforimg.handler = function() {
						var votebar = $('votebardiv'+node.parentuniq);
						var other = $(this.connid+'against');
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
						var votebar = $('votebardiv'+node.parentuniq);
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
						Event.observe(voteforimg,'click',function (){ deleteConnectionVote(this) } );
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>');
						voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!connection.uservote || connection.uservote != 'Y') {
						Event.observe(voteforimg,'click',function (){ connectionVote(this) } );
						voteforimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
						voteforimg.setAttribute('title', voteforimg.oldtitle);
					}
				}
			} else {
				voteforimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_LOGIN_HINT; ?>');
			}
		}

		// vote against
		var voteagainstimg = new Element('img');
		voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-grey.png"); ?>');
		voteagainstimg.setAttribute('alt', '<?php echo $LNG->NODE_VOTE_AGAINST_ICON_ALT; ?>');
		voteagainstimg.setAttribute('id', connection.connid+'against');
		voteagainstimg.nodeid = node.nodeid;
		voteagainstimg.connid = connection.connid;
		voteagainstimg.vote='N';
		voteDiv.insert(voteagainstimg);
		if (!connection.negativevotes) {
			connection.negativevotes = 0;
		}
		voteDiv.insert('<span class="vote-count" id="'+connection.connid+'voteagainst">'+connection.negativevotes+'</span>');

		if (NODE_ARGS['issueVoting']) {
			if(USER != ""){
				if (nodeuser.userid == USER) {
					voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_OWN_HINT; ?>');
				} else {
					voteagainstimg.handler = function() {
						var votebar = $('votebardiv'+node.parentuniq);
						var other = $(this.connid+'for');
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
						var votebar = $('votebardiv'+node.parentuniq);
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
						Event.observe(voteagainstimg,'click',function (){ deleteConnectionVote(this) } );
						voteagainstimg.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>');
						voteagainstimg.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					} else if (!connection.uservote || connection.uservote != 'N') {
						Event.observe(voteagainstimg,'click',function (){ connectionVote(this) } );
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
		var iuDiv = new Element("div", {
			'id':'editformuserdivargument'+uniQ,
			'class':'idea-user2',
		});

		var userimageThumb = new Element('img',{'alt':nodeuser.name, 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = new Element('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.insert(userimageThumb);
			iuDiv.update(imagelink);
		} else {
			iuDiv.insert(userimageThumb)
		}

		userCell.insert(iuDiv);
	}

	var row2 = nodeTable.insertRow(-1);
	var editCell = row2.insertCell(-1);
	editCell.className = "edit-column";

	/** ADD THE EDIT FORM FOR THE ARGUMENT **/
	if (USER == user.userid && type == 'active') {
		var editouterDiv = new Element("fieldset", {
			'id':'editformdivargument'+uniQ,
			'class':'editformdivargument',
			'style':'display:none;'
		});

		var legend = new Element("legend", { 'class':'edit-argument'});
		var legendtitle = new Element("h2");
		legendtitle.insert('<?php echo $LNG->EXPLORE_EDITING_ARGUMENT_TITLE; ?>');
		legend.insert(legendtitle);
		editouterDiv.insert(legend);

		editCell.insert(editouterDiv);

		var editDiv = new Element("div", {
			'class':'edit-argument-form'
		});
		editouterDiv.insert(editDiv);

		var editargumentid = new Element("input", {
			'name':'editargumentid',
			'id':'editargumentid'+uniQ,
			'type':'hidden',
			'value':node.nodeid,
		});
		editDiv.insert(editargumentid);
		var editargumentroleid = new Element("input", {
			'name':'editargumentnodetypeid',
			'id':'editargumentnodetypeid'+uniQ,
			'type':'hidden',
			'value':role.roleid,
		});
		editDiv.insert(editargumentroleid);

		var rowDiv1 = new Element("div", {
			'class':'my-2',
		});
		editDiv.insert(rowDiv1);
		var editargumentname = new Element("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
			'id':'editargumentname'+uniQ,
			'name':'editargumentname',
			'value':node.name,
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>',
		});
		rowDiv1.insert(editargumentname);

		var rowDiv2 = new Element("div", {
			'class':'my-2',
		});
		editDiv.insert(rowDiv2);
		var editargumentdesc = new Element("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
			'id':'editargumentdesc'+uniQ,
			'name':'editargumentdesc',
			'aria-label':'<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>',
		});
		editargumentdesc.insert(node.description);
		rowDiv2.insert(editargumentdesc);

		var rowDiv = new Element("div", {
			'class':'my-2',
			'id':'linksdivedit'+uniQ,
		});
		rowDiv.linkcount = 0;
		editDiv.insert(rowDiv);

		if (node.urls && node.urls.length > 0) {
			rowDiv.linkcount = node.urls.length-1;
			for(var i=0; i< node.urls.length; i++){
				if(node.urls[i].url){
					var next = node.urls[i].url;
					var urlid = next.urlid;
					var url = next.url;

					editDiv.insert(rowDiv);

					var weblink = new Element("input", {
						'class':'form-control',
						'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
						'id':'argumentlinkedit'+uniQ+i,
						'name':'argumentlinkedit'+uniQ+'[]',
						'value':url,
						'style':'margin-bottom:3px;',
						'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
					});
					weblink.urlid = urlid;
					rowDiv.insert(weblink);
				}
			}
		} else {
			rowDiv.linkcount = 0;
			var weblink = new Element("input", {
				'class':'form-control',
				'placeholder':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
				'id':'argumentlinkedit'+uniQ+0,
				'name':'argumentlinkedit'+uniQ+'[]',
				'value':'',
				'style':'margin-bottom:2px;',
				'aria-label':'<?php echo $LNG->FORM_LINK_LABEL; ?>',
			});
			rowDiv.insert(weblink);
		}

		var rowDiv5 = new Element("div", {'class':'my-3'});
		editDiv.insert(rowDiv5);
		var addURL = new Element("a", {
			'class':'hgrinput',
			'href':'javascript:void(0)',
		});
		addURL.insert('<?php echo $LNG->FORM_MORE_LINKS_BUTTONS; ?>');
		Event.observe(addURL,'click',function (){
			insertArgumentLink(uniQ, 'edit');
		});
		rowDiv5.insert(addURL);

		var rowDiv6 = new Element("div", {
			'class':'formrowsm mb-3',
		});
		editDiv.insert(rowDiv6);
		var editargumentsave = new Element("input", {
			'type':'button',
			'class':'btn btn-primary me-3',
			'id':'editargument',
			'name':'editargument',
			'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>',
		});
		Event.observe(editargumentsave,'click',function (){
			editArgumentNode(node, uniQ, 'argument', role.name, type, includeUser, status);
			var innertype = "pro";
			if (role.name == "Con") {
				innertype = "con";
			}
			showAddForm(node.parentuniq, innertype);
		});
		rowDiv6.insert(editargumentsave);
		var editargumentcancel = new Element("input", {
			'type':'button',
			'class':'btn btn-secondary',
			'id':'cancelargument',
			'name':'editargument',
			'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>',
		});
		Event.observe(editargumentcancel,'click',function (){
			var innertype = "pro";
			if (role.name == "Con") {
				innertype = "con";
			}
			showAddForm(node.parentuniq, innertype);
			cancelEditAction(uniQ, 'argument');
		});

		rowDiv6.insert(editargumentcancel);
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

	var nodeTable = new Element('table', {'style':'width:100%'});
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

	var textDiv = new Element("div", {
		'id':'textdivcomment'+uniQ,
	});
	textCell.insert(textDiv);

	var title = node.name;

	var textspan = new Element("span", {
		'id':'desctoggle'+uniQ,
		'style':'font-weight:normal;font-size:11pt'
	});
	textspan.insert(title);
	textDiv.insert(textspan);

	if (USER == nodeuser.userid && type == "active") {
		var editbutton = new Element("img", {
			
			'class':'imagebuttonfaded',
			'style':'padding-left:10px',
			'src':'<?php echo $HUB_FLM->getImagePath("edit.png"); ?>',
			'title':'<?php echo $LNG->NODE_EDIT_SOLUTION_ICON_HINT; ?>',
		});
		textDiv.insert(editbutton);
		Event.observe(editbutton,'click',function (){
			editInline(uniQ, 'comment');
		});

		var deletename = node.name;
		var del = new Element('img',{'style':'cursor: pointer;padding-left:5px;margin-top:5px;','alt':'<?php echo $LNG->DELETE_BUTTON_ALT;?>', 'title': '<?php echo $LNG->DELETE_BUTTON_HINT;?>', 'src': '<?php echo $HUB_FLM->getImagePath("delete.png"); ?>'});
		Event.observe(del,'click',function (){
			var callback = function () {
				$('commentslist'+node.parentuniq).loaded = 'false';
				loadChildComments('commentslist'+node.parentuniq, node.parentid, '<?php echo $LNG->COMMENTS_NAME; ?>', '<?php echo $CFG->LINK_COMMENT_NODE; ?>', 'Comment', node.parentid, node.groupid, node.parentuniq, $('count-comment'+node.parentuniq), type, status);
				refreshStats();
			}
			deleteNode(node.nodeid, deletename, role.name, callback);
		});
		textDiv.insert(del);
	}

	if(node.description || node.hasdesc){
		var dStr = '<div style="clear:both;margin:0px;padding:0px;margin-top:3px;font-size:10pt;" class="idea-desc" id="desc'+uniQ+'div"><span style="margin-top: 5px;">';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.insert(dStr);
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
		var iuDiv = new Element("div", {
			'id':'editformuserdivcomment'+uniQ,
			'class':'idea-user2',
			'style':'float:left;display:block'
		});

		var userimageThumb = new Element('img',{'alt':nodeuser.name, 'src': nodeuser.thumb});
		if (type == "active") {
			var imagelink = new Element('a', {
				'href':URL_ROOT+"user.php?userid="+nodeuser.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.insert(userimageThumb);
			iuDiv.update(imagelink);
		} else {
			iuDiv.insert(userimageThumb)
		}

		userCell.insert(iuDiv);
	}

	var row2 = nodeTable.insertRow(-1);
	var editCell = row2.insertCell(-1);
	editCell.colSpan = "3";

	/** ADD THE EDIT FORM FOR THE IDEA **/
	if (USER == user.userid && type == 'active') {
		var editouterDiv = new Element("div", {
			'id':'editformdivcomment'+uniQ,
			'style':'clear:both;float:left;width:100%;display:none;border:1px solid #E8E8E8;'
		});
		editCell.insert(editouterDiv);

		var editDiv = new Element("div", {
			'style':'clear:both;float:left;margin:5px;'
		});
		editouterDiv.insert(editDiv);

		/*
		var editForm = new Element("form", {
			'name':'editformcomment'+uniQ,
			'id':'editformcomment'+uniQ,
			'action':'',
			'method':'post',
			'enctype':'multipart/form-data',
			'onsubmit': "return checkCommentEditForm('comment','"+uniQ+"');",
		});
		editDiv.insert(editForm);
		*/

		var editideaid = new Element("input", {
			'name':'editcommentid',
			'id':'editcommentid'+uniQ,
			'type':'hidden',
			'value':node.nodeid,
		});
		editDiv.insert(editideaid);
		var editidearole = new Element("input", {
			'name':'editcommentnodetypeid',
			'id':'editcommentnodetypeid'+uniQ,
			'type':'hidden',
			'value':role.roleid,
		});
		editDiv.insert(editidearole);

		var rowDiv1 = new Element("div", {
			'class':'formrowsm',
			'style':'padding-top:0px;',
		});
		editDiv.insert(rowDiv1);
		var editideaname = new Element("input", {
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
			'id':'editcommentname'+uniQ,
			'name':'editcommentname',
			'value':node.name,
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_TITLE; ?>',
		});
		rowDiv1.insert(editideaname);

		var rowDiv2 = new Element("div", {
			'class':'formrowsm',
		});
		editDiv.insert(rowDiv2);
		var editideadesc = new Element("textarea", {
			'rows':'3',
			'class':'form-control',
			'placeholder':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
			'id':'editcommentdesc'+uniQ,
			'name':'editcommentdesc',
			'aria-label':'<?php echo $LNG->IDEA_COMMENT_LABEL_DESC; ?>',
		});
		editideadesc.insert(node.description);
		rowDiv2.insert(editideadesc);

		var rowDiv3 = new Element("div", {
			'class':'formrowsm',
		});
		editDiv.insert(rowDiv3);
		var editideasave = new Element("input", {
			'type':'button',
			'class':'submitright',
			'id':'editcomment',
			'name':'editcomment',
			'value':'<?php echo $LNG->FORM_BUTTON_SAVE; ?>',
		});
		Event.observe(editideasave,'click',function (){
			editCommentNode(node, uniQ, 'comment', type, includeUser, status);
		});
		rowDiv3.insert(editideasave);
		var editideacancel = new Element("input", {
			'type':'button',
			'class':'submitright',
			'id':'cancelcomment',
			'name':'cancelcomment',
			'value':'<?php echo $LNG->FORM_BUTTON_CANCEL; ?>',
			'style':'margin-right:10px;',
		});
		Event.observe(editideacancel,'click',function () {
			cancelEditAction(uniQ, 'comment');
		});

		rowDiv3.insert(editideacancel);
	}

	return nodeTable;
}

/**
 * Render the given node.
 * @param node the node object do render
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

	var nodeTable = new Element('table', {'class':'ideas-table'});
	nodeTable.className = "toConnectionsTable";

	var row = nodeTable.insertRow(-1);

	if (includeUser) {
		var userCell = row.insertCell(-1);

		var cDate = new Date(node.creationdate*1000);
		var dStr = "<?php echo $LNG->NODE_ADDED_BY; ?> "+user.name+ " on "+cDate.format(DATE_FORMAT)
		userCell.title = dStr;

		// Add right side with user image and date below
		var iuDiv = new Element("div", {
			'id':'editformuserdivcomment'+uniQ,
			'class':'idea-user2',
			'style':'display:block'
		});

		var userimageThumb = new Element('img',{'alt':user.name, 'src': user.thumb});
		if (type == "active") {
			var imagelink = new Element('a', {
				'href':URL_ROOT+"user.php?userid="+user.userid
				});
			if (breakout != "") {
				imagelink.target = "_blank";
			}
			imagelink.insert(userimageThumb);
			iuDiv.update(imagelink);
		} else {
			iuDiv.insert(userimageThumb)
		}

		userCell.insert(iuDiv);
	}

	var textCell = row.insertCell(-1);

	var textDiv = new Element("div", {
		'id':'textdivcomment'+uniQ,
		'class':'textdivcomment',
	});
	textCell.insert(textDiv);

	var title = node.name;

	var textspan = new Element("a", {
		'class':'textdivcomment-title'
	});
	textspan.insert(title);
	textspan.href = '<?php echo $CFG->homeAddress; ?>explore.php?id='+node.nodeid;
	textDiv.insert(textspan);

	if(node.description || node.hasdesc){
		var dStr = '<div class="idea-desc" id="desc'+uniQ+'div"><span>';
		if (node.description && node.description != "") {
			dStr += node.description;
		}
		dStr += '</div>';
		textDiv.insert(dStr);
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
	var iDiv = new Element("div", {'style':'clear:both;float:left; margin-bottom:10px'});
	var itDiv = new Element("div", {'style':'float:left;'});

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
			var iconlink = new Element('a', {
				'href':originalurl,
				'title':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'target': '_blank' });
			var nodeicon = new Element('img',{'alt':'<?php echo $LNG->NODE_TYPE_ICON_HINT; ?>', 'style':'width:16px;height:16px;margin-right:5px;','width':'16','height':'16','align':'left', 'src': URL_ROOT + node.imagethumbnail});
			iconlink.insert(nodeicon);
			itDiv.insert(iconlink);
			itDiv.insert(alttext+": ");
		} else if (role.image != null && role.image != "") {
			var nodeicon = new Element('img',{'alt':alttext, 'title':alttext, 'style':'width:16px;height:16px;margin-right:5px;','width':'16','height':'16','align':'left', 'src': URL_ROOT + role.image});
			itDiv.insert(nodeicon);
		} else {
			itDiv.insert(alttext+": ");
		}
	}

	if (node.name != "") {
		iDiv.insert(itDiv);
		var str = "<div style='float:left;width:600px;'>"+node.name;
		str += "</div>";
		iDiv.insert(str);
	}

	return iDiv;
}


/*** HELPER FUNCTIONS ***/

var DEBATE_TREE_OPEN_ARRAY = {};

/**
 * Open and close the knowledge tree
 */
function toggleDebate(section, uniQ) {
    $(section).toggle();

    if($(section).visible()){
    	DEBATE_TREE_OPEN_ARRAY[section] = true;
    	$('explorearrow'+uniQ).src='<?php echo $HUB_FLM->getImagePath("arrow-down-blue.png"); ?>';
	} else {
    	DEBATE_TREE_OPEN_ARRAY[section] = false;
		$('explorearrow'+uniQ).src='<?php echo $HUB_FLM->getImagePath("arrow-right-blue.png"); ?>';
	}
}

function ideaArgumentToggle(section, uniQ, id, sect, rolename, focalnodeid, groupid) {
   $(section).toggle();
   if($('arrow'+section)){
        if($(section).visible()){
            $('arrow'+section).src = "<?php echo $HUB_FLM->getImagePath("arrow-up2.png"); ?>";
        } else {
            $('arrow'+section).src = "<?php echo $HUB_FLM->getImagePath("arrow-down2.png"); ?>";
        }
	}
}

function ideatoggle(section, uniQ, id, sect, rolename, focalnodeid, groupid) {

   if ($(section).style.display == 'block') {
   		$(section).style.display = 'none';
   } else if ($(section).style.display == 'none') {
   		$(section).style.display = 'block';
   }

	//Audit viewing of child lists only if opening area
	if ($(section).style.display == 'block') {
		if (sect == "comments") {
			if ($(section).commentnodes) {
				var nodes = $(section).commentnodes;
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i<count;i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids = nodeids + node.cnode.nodeid;
					} else {
						nodeids = nodeids + ","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				new Ajax.Request(reqUrl, { method:'post',
					onSuccess: function(transport){
						var json = transport.responseText.evalJSON();
						if(json.error){
							alert(json.error[0].message);
						}
					}
				});
			}
		} else if (sect == "arguments") {
			if ($(section).pronodes) {
				var nodes = $(section).pronodes;
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i<count; i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids = nodeids + node.cnode.nodeid;
					} else {
						nodeids = nodeids+","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				new Ajax.Request(reqUrl, { method:'post',
					onSuccess: function(transport){
						var json = transport.responseText.evalJSON();
						if(json.error){
							alert(json.error[0].message);
						}
					}
				});
			}

			if ($(section).connodes) {
				var nodes = $(section).connodes;
				var count = nodes.length;
				var nodeids = "";
				for (var i=0; i<count;i++) {
					var node = nodes[i];
					if (i == 0) {
						nodeids = nodeids + node.cnode.nodeid;
					} else {
						nodeids = nodeids + ","+node.cnode.nodeid;
					}
				}
				var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
				new Ajax.Request(reqUrl, { method:'post',
					onSuccess: function(transport){
						var json = transport.responseText.evalJSON();
						if(json.error){
							alert(json.error[0].message);
						}
					}
				});
			}
		}
	}

	if ($('idearowitem'+uniQ)) {
		if( ($('comments'+uniQ) && $('comments'+uniQ).style.display == 'none' && $('arguments'+uniQ).style.display == 'none') || (!$('comments'+uniQ) && $('arguments'+uniQ).style.display == 'none') ){
			$('idearowitem'+uniQ).style.background = "transparent";
		} else {
			$('idearowitem'+uniQ).style.background = "#E8E8E8";
   		}
   	}
}

function loadStats(nodeid, peoplearea, ideaarea, votearea) {

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = nodeid;
    args['style'] = "long";
	var reqUrl = SERVICE_ROOT + "&method=getdebateministats&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var stats = json.debateministats[0];
			var totalvotes = parseInt(stats.totalvotes);
			var ideacount = parseInt(stats.ideacount);
			var peoplecount = parseInt(stats.peoplecount);
			if (peoplearea) {
				peoplearea.update(peoplecount);
			}
			if (ideaarea) {
				ideaarea.update(ideacount);
			}
			if (votearea) {
				votearea.update(totalvotes);
			}
		}
	});
}

/**
 * load child list, if required as per parameters.
 */
function loadChildComments(section, nodeid, title, linktype, nodetype, focalnodeid, groupid, uniQ, countArea, type, status){

	if (typeof section === "string") {
		section = $(section);
	}

	if (section.loaded == undefined) {
		section.loaded = 'false';
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

    if(section.visible() && (!section.loaded || section.loaded == 'false')){

    	section.update(getLoading("<?php echo $LNG->LOADING_ITEMS; ?>"));

		var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=ASC&orderby=date&status="+status;
		reqUrl += "&filterlist="+linktype+"&filternodetypes="+nodetype+"&scope=all&start=0&max=-1&nodeid="+nodeid;

		//alert(reqUrl);

		new Ajax.Request(reqUrl, { method:'post',
			onSuccess: function(transport){
				var json = transport.responseText.evalJSON();
				if(json.error){
					alert(json.error[0].message);
					return;
				}
				var conns = json.connectionset[0].connections;
				section.update("");
				$('ideacommentscount'+nodeid).update(0);
				if (countArea) {
					countArea.update(0);
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
					countArea.update(nodes.length);
				}
				if ($('ideacommentscount'+nodeid)) {
					$('ideacommentscount'+nodeid).update(nodes.length);
				}

				if (nodes.length > 0){
					displayCommentNodes(section, nodes, parseInt(0), true, uniQ, type, status);
					// for View auditign on toggle
					section.commentnodes = nodes;
				}

				if (otherend !="") {
					openSelectedItem(otherend, 'comments');
				}
			}
		});
	}
}

/**
 * load child list, if required as per parameters.
 */
function loadChildArguments(section, nodeid, title, linktype, nodetype, focalnodeid, groupid, uniQ, countArea, type, status, votebar){

	if (typeof section === "string") {
		section = $(section);
	}

	if (section.loaded == undefined) {
		section.loaded = 'false';
	}

	if (status == undefined) {
		status = <?php echo $CFG->STATUS_ACTIVE; ?>;
	}

    if(section.visible() && (!section.loaded || section.loaded == 'false')){

    	section.update(getLoading("<?php echo $LNG->LOADING_ITEMS; ?>"));

		var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=ASC&orderby=date&status="+status;
		reqUrl += "&filterlist="+linktype+"&filternodetypes="+nodetype+"&scope=all&start=0&max=-1&nodeid="+nodeid;

		//alert(reqUrl);

		new Ajax.Request(reqUrl, { method:'post',
			onSuccess: function(transport){
				var json = transport.responseText.evalJSON();
				if(json.error){
					alert(json.error[0].message);
					return;
				}
				var conns = json.connectionset[0].connections;
				section.update("");
				if ($('ideaargumentcount'+nodeid)) {
					$('ideaargumentcount'+nodeid).update(0);
				}
				if (countArea) {
					countArea.update(0);
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
					countArea.update(nodes.length);
				}
				var otherAmount = 0;
				if (nodetype == "Pro") {
					otherAmount = parseInt($('count-counter'+uniQ).innerHTML);
				} else {
					otherAmount = parseInt($('count-support'+uniQ).innerHTML);
				}
				if ($('ideaargumentcount'+nodeid)) {
					$('ideaargumentcount'+nodeid).update(otherAmount+nodes.length);
				}

				if (nodes.length > 0){
					displayArgumentNodes(section, nodes, parseInt(0), true, uniQ, type, status);

					// for View auditing on toggle
					if ($('arguments'+uniQ)) {
						if (nodetype == "Con") {
							$('arguments'+uniQ).connodes = nodes;
						} else {
							$('arguments'+uniQ).pronodes = nodes;
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
					$('arguments'+uniQ).style.display = "block";

					//audit views of this child list now it is opened
					var count = nodes.length;
					var nodeids = "";
					for (var i=0; i<count; i++) {
						var node = nodes[i];
						if (i == 0) {
							nodeids = nodeids + node.cnode.nodeid;
						} else {
							nodeids = nodeids+","+node.cnode.nodeid;
						}
					}
					var reqUrl = SERVICE_ROOT + "&method=auditnodeviewmulti&nodeids="+nodeids+"&viewtype=list";
					new Ajax.Request(reqUrl, { method:'post',
						onSuccess: function(transport){
							var json = transport.responseText.evalJSON();
							if(json.error){
								alert(json.error[0].message);
							}
						}
					});
				}

				if (otherend != "") {
					openSelectedItem(otherend, 'arguments');
				}
			}
		});
	}
}

/**
 * load child list on solutionas for built froms.
 */
function loadBuiltFromsCount(section, nodeid){

	var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=DESC&orderby=date&status=<?php echo $CFG->STATUS_ACTIVE; ?>";
	reqUrl += "&filterlist=<?php echo $CFG->LINK_BUILT_FROM; ?>&filternodetypes=Solution&scope=all&start=0&max=0&nodeid="+nodeid;
	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();

			if(json.error){
				//alert(json.error[0].message);
				return;
			}

			var count = json.connectionset[0].totalno;
			if (count > 0) {
				section.style.display = "block";
			}
		}
	});
}


/**
 * Delete the selected node
 */
function deleteNode(nodeid, name, type, handler, handlerparams){
	var typename = getNodeTitleAntecedence(type, false);
	if (type == "") {
		typename = '<?php echo $LNG->NODE_DELETE_CHECK_MESSAGE_ITEM; ?>';
	}

	var ans = confirm("<?php echo $LNG->NODE_DELETE_CHECK_MESSAGE; ?> "+typename+": '"+htmlspecialchars_decode(name)+"'?");
	if(ans){
		var reqUrl = SERVICE_ROOT + "&method=deletenode&nodeid=" + encodeURIComponent(nodeid);
		new Ajax.Request(reqUrl, { method:'get',
  			onSuccess: function(transport){

  				var json = transport.responseText.evalJSON();
      			if(json.error){
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
    		}
  		});
	}
}

function nodeVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=nodevote&vote="+obj.vote+"&nodeid="+obj.nodeid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
   				if (obj.vote == 'Y') {

					// if they vote on an idea, make sure they follow the main debate issue
					if (nodeObj && nodeObj.role.name == 'Issue' && !nodeObj.userfollow || nodeObj.userfollow == "N") {
						followNode(nodeObj, null, 'refreshMainIssue');
					}

  					$$("#nodevotefor"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].positivevotes; });

   					$$("#nodefor"+obj.nodeid).each(function(elmt) {
   						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled3.png"); ?>');
   						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
   						Event.stopObserving(elmt, 'click');
   						Event.observe(elmt,'click', function (){deleteNodeVote(this);});
   					});

  					$$("#nodevoteagainst"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].negativevotes; });

  					$$("#nodeagainst"+obj.nodeid).each(function(elmt) {
  						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
  						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
  						Event.stopObserving(elmt, 'click');
  						Event.observe(elmt,'click', function (){nodeVote(this) } );
  					});
				} else if (obj.vote == 'N') {
					$$("#nodevoteagainst"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].negativevotes; });

   					$$("#nodeagainst"+obj.nodeid).each(function(elmt) {
   						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled3.png"); ?>');
   						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
   						Event.stopObserving(elmt, 'click');
   						Event.observe(elmt,'click', function (){deleteNodeVote(this) } );
   					});

  					$$("#nodevotefor"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].positivevotes; });

  					$$("#nodefor"+obj.nodeid).each(function(elmt) {
  						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
  						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
  						Event.stopObserving(elmt, 'click');
  						Event.observe(elmt,'click', function (){nodeVote(this) } );
  					});
				}
   			}
   		}
  	});
}

function deleteNodeVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=deletenodevote&vote="+obj.vote+"&nodeid="+obj.nodeid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
   				if (obj.vote == 'Y') {

  					$$("#nodevotefor"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].positivevotes; });

   					$$("#nodefor"+obj.nodeid).each(function(elmt) {
   						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
   						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
   						Event.stopObserving(elmt, 'click');
   						Event.observe(elmt,'click', function (){nodeVote(this);});
   					});

  					$$("#nodevoteagainst"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].negativevotes; });

  					$$("#nodeagainst"+obj.nodeid).each(function(elmt) {
  						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
  						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
  						Event.stopObserving(elmt, 'click');
  						Event.observe(elmt,'click', function (){nodeVote(this) } );
  					});

					$(obj.nodeid+obj.uniqueid+'nodeagainst').setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
					$(obj.nodeid+obj.uniqueid+'nodeagainst').setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
					Event.stopObserving($(obj.nodeid+obj.uniqueid+'nodeagainst'), 'click');
					Event.observe($(obj.nodeid+obj.uniqueid+'nodeagainst'),'click', function (){ connectionVote(this) } );

				} if (obj.vote == 'N') {
					$$("#nodevoteagainst"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].negativevotes; });

   					$$("#nodeagainst"+obj.nodeid).each(function(elmt) {
   						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty3.png"); ?>');
   						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_AGAINST_ADD_HINT; ?>');
   						Event.stopObserving(elmt, 'click');
   						Event.observe(elmt,'click', function (){nodeVote(this) } );
   					});

  					$$("#nodevotefor"+obj.nodeid).each(function(elmt) { elmt.innerHTML = json.cnode[0].positivevotes; });

  					$$("#nodefor"+obj.nodeid).each(function(elmt) {
  						elmt.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty3.png"); ?>');
  						elmt.setAttribute('title', '<?php echo $LNG->NODE_VOTE_FOR_ADD_HINT; ?>');
  						Event.stopObserving(elmt, 'click');
  						Event.observe(elmt,'click', function (){nodeVote(this) } );
  					});
				}
   			}
   		}
  	});
}

function followNode(node, obj, handler) {
	var reqUrl = SERVICE_ROOT + "&method=addfollowing&itemid="+node.nodeid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
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
					Event.stopObserving(obj, 'click');
					Event.observe(obj,'click', function (){ unfollowNode(node, this, handler) } );
				}
   			}
   		}
  	});
}

function unfollowNode(node, obj, handler) {
	var reqUrl = SERVICE_ROOT + "&method=deletefollowing&itemid="+node.nodeid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
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
					Event.stopObserving(obj, 'click');
					Event.observe(obj,'click', function (){ followNode(node, this, handler) } );
				}
   			}
   		}
  	});
}

/**
 * Called from user home page follow list.
 */
function unfollowMyNode(nodeid) {
	var reqUrl = SERVICE_ROOT + "&method=deletefollowing&itemid="+nodeid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
				try {
					window.location.reload(true);
				} catch(err) {
					//do nothing
				}
   			}
   		}
  	});
}

function lemonNode(nodeid, issueid, handler) {
	var reqUrl = SERVICE_ROOT + "&method=addlemon&nodeid="+nodeid+"&issueid="+issueid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
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
   			}
   		}
  	});
}

function unlemonNode(nodeid, issueid, handler) {
	var reqUrl = SERVICE_ROOT + "&method=deletelemon&nodeid="+nodeid+"&issueid="+issueid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
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
   			}
   		}
  	});
}

/**
 *	show an RSS feed of the nodes for the given arguments
 */
function getNodesFeed(nodeargs) {
	var url = SERVICE_ROOT.replace('format=json','format=rss');
	var args = Object.clone(nodeargs);
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
	var args = Object.clone(nodeargs);
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

	var args = Object.clone(nodeargs);
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

	var args = Object.clone(nodeargs);
	args["start"] = 0;
	args["max"] = -1;
	args["style"] = 'long';

	var reqUrl = url+"&method=getconnectednodesby"+CONTEXT+"&"+Object.toQueryString(args);
	var urlcall =  URL_ROOT+"ui/popups/printnodes.php?context="+CONTEXT+"&title="+title+"&filternodetypes="+args['filternodetypes']+"&url="+encodeURIComponent(reqUrl);

	loadDialog('printnodes', urlcall, 800, 700);
}

// NODE CONNECTION FUNCTIONS
function connectionVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=connectionvote&vote="+obj.vote+"&connid="+obj.connid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {

				// if they vote on an idea, make sure they follow the main debate issue
				if (nodeObj && nodeObj.role.name == 'Issue' && !nodeObj.userfollow || nodeObj.userfollow == "N") {
					followNode(nodeObj, null, 'refreshMainIssue');
				}

   				if (obj.vote == 'Y') {
					$(obj.connid+'votefor').innerHTML = json.connection[0].positivevotes;
					obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-filled.png"); ?>');
					obj.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					Event.stopObserving(obj, 'click');
					Event.observe(obj,'click', function (){ deleteConnectionVote(this) } );

					$(obj.connid+'voteagainst').innerHTML = json.connection[0].negativevotes;
					$(obj.connid+'against').setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
					$(obj.connid+'against').setAttribute('title', $(obj.connid+'against').oldtitle);
					Event.stopObserving($(obj.connid+'against'), 'click');
					Event.observe($(obj.connid+'against'),'click', function (){ connectionVote(this) } );
				} else if (obj.vote == 'N') {
					$(obj.connid+'voteagainst').innerHTML = json.connection[0].negativevotes;
					obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-filled.png"); ?>');
					obj.setAttribute('title', '<?php echo $LNG->NODE_VOTE_REMOVE_HINT; ?>');
					Event.stopObserving(obj, 'click');
					Event.observe(obj,'click', function (){ deleteConnectionVote(this) } );

					$(obj.connid+'votefor').innerHTML = json.connection[0].positivevotes;
					$(obj.connid+'for').setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
					$(obj.connid+'for').setAttribute('title', $(obj.connid+'for').oldtitle);
					Event.stopObserving($(obj.connid+'for'), 'click');
					Event.observe($(obj.connid+'for'),'click', function (){ connectionVote(this) } );
				}
   			}
   		}
  	});

  	if (obj.handler != undefined) {
  		obj.handler();
  	}
}

function deleteConnectionVote(obj) {
	var reqUrl = SERVICE_ROOT + "&method=deleteconnectionvote&vote="+obj.vote+"&connid="+obj.connid;
	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
   			if(json.error) {
   				alert(json.error[0].message);
   				return;
   			} else {
   				if (obj.vote == 'Y') {
					$(obj.connid+'votefor').innerHTML = json.connection[0].positivevotes;
					obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
					obj.setAttribute('title', obj.oldtitle);
					Event.stopObserving(obj, 'click');
					Event.observe(obj,'click', function (){ connectionVote(this) } );

					$(obj.connid+'voteagainst').innerHTML = json.connection[0].negativevotes;
					$(obj.connid+'against').setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
					$(obj.connid+'against').setAttribute('title', $(obj.connid+'against').oldtitle);
					Event.stopObserving($(obj.connid+'against'), 'click');
					Event.observe($(obj.connid+'against'),'click', function (){ connectionVote(this) } );
				} if (obj.vote == 'N') {
					$(obj.connid+'voteagainst').innerHTML = json.connection[0].negativevotes;
					obj.setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-down-empty.png"); ?>');
					obj.setAttribute('title', obj.oldtitle);
					Event.stopObserving(obj, 'click');
					Event.observe(obj,'click', function (){ connectionVote(this) } );

					$(obj.connid+'votefor').innerHTML = json.connection[0].positivevotes;
					$(obj.connid+'for').setAttribute('src', '<?php echo $HUB_FLM->getImagePath("thumb-up-empty.png"); ?>');
					$(obj.connid+'for').setAttribute('title', $(obj.connid+'for').oldtitle);
					Event.stopObserving($(obj.connid+'for'), 'click');
					Event.observe($(obj.connid+'for'),'click', function (){ connectionVote(this) } );
				}
   			}
   		}
  	});

  	if (obj.handlerdelete != undefined) {
  		obj.handlerdelete();
  	}
}

/**
 * Delete the connection for the given connection id.
 */
function deleteNodeConnection(connid, childname, parentname, handler) {
	var ans = confirm("<?php echo $LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART1; ?> \n\r\n\r'"+htmlspecialchars_decode(childname)+"'\n\r\n\r <?php echo $LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART2; ?> \n\r\n\r'"+htmlspecialchars_decode(parentname)+"' <?php echo $LNG->NODE_DISCONNECT_CHECK_MESSAGE_PART3; ?>");
	if(ans){
		var reqUrl = SERVICE_ROOT + "&method=deleteconnection&connid=" + encodeURIComponent(connid);
		new Ajax.Request(reqUrl, { method:'get',
  			onSuccess: function(transport){
  				var json = transport.responseText.evalJSON();
      			if(json.error){
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
    		}
  		});
	}
}

/**
 * Send a spam alert to the server.
 */
function reportNodeSpamAlert(obj, nodetype, node) {

	var name = node.name;

	var ans = confirm("<?php echo $LNG->SPAM_CONFIRM_MESSAGE_PART1; ?>\n\n"+name+"\n\n<?php echo $LNG->SPAM_CONFIRM_MESSAGE_PART2; ?>\n\n");
	if (ans){
		var reqUrl = URL_ROOT + "ui/admin/spamalert.php?type=idea&id="+node.nodeid;
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
		   		alert(error);
			},
			onSuccess: function(transport){
				node.status = 1;
				obj.title = '<?php echo $LNG->SPAM_REPORTED_HINT; ?>';
				if (obj.alt) {
					obj.alt = '<?php echo $LNG->SPAM_REPORTED_TEXT; ?>';
					obj.src= '<?php echo $HUB_FLM->getImagePath('spam-reported.png'); ?>';
					obj.style.cursor = 'auto';
					Event.stopObserving(obj, 'click');
				} else {
					obj.innerHTML = '<?php echo $LNG->SPAM_REPORTED_TEXT; ?>';
				}
				obj.className = "";
				fadeMessage(name+"<br /><br /><?php echo $LNG->SPAM_SUCCESS_MESSAGE; ?>");
			}
		});
	}
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

		for (var i=0; i<count; i++) {
			next = cellsArray[i];
			var nodeid = next.getAttribute('nodeid');
			var uniQ = next.getAttribute('uniQ');
			if (nodeid == parentid) {
				if (type == 'arguments') {
					$('arguments'+uniQ).style.display = "block";
					var pos = getPosition($('arguments'+uniQ));
					window.scroll(0,pos.y-100);
				} else if (type == 'comments') {
					$('comments'+uniQ).style.display = "block";
					var pos = getPosition($('comments'+uniQ));
					window.scroll(0,pos.y-100);
				}
			}
		}
	}
}

/**
 *	get all the selected node ids
 */
function getSelectedNodeIDs(conatiner){
	var retArr = new Array();
	var nodes = conatiner.select('[class="nodecheck"]');
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
		container = $(container);
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

	if (focalnodeid && $('debatestatsvotes'+focalnodeid)) {
		$('debatestatsvotes'+focalnodeid).votes[uniQ] = container.propositivevotes+container.pronegativevotes+container.connegativevotes+container.conpositivevotes;
		recalculateVotes(focalnodeid);
	}

	if (total == 0) {
		container.update("");
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

		var votebar = new Element("div", {'class':'progress'} );

		if (positiveHint == 100) {
			var bar = new Element("div", {'class':'barall', 'title':positiveHint+'% <?php echo $LNG->STATS_PRO_HINT_TEXT;?>', 'style':'width:'+positiveWidth+'%'} );
			votebar.insert(bar);
			bar.insert(positiveHint+"%");
		} else if (negativeHint == 100) {
			var remainder = new Element("div", {'class':'remainderall','title':negativeHint+'%  <?php echo $LNG->STATS_CON_HINT_TEXT;?>', 'style':'width:'+negativeWidth+'%'} );
			votebar.insert(remainder);
			remainder.insert(negativeHint+"%");
		} else {
			// otherwise it wraps.
			positiveWidth = positiveWidth-2;
			negativeWidth = negativeWidth-2;

			var bar = new Element("div", {'class':'bar', 'title':positiveHint+'%  <?php echo $LNG->STATS_PRO_HINT_TEXT;?>', 'style':'width:'+positiveWidth+'%'} );
			var remainder = new Element("div", {'class':'remainder','title':negativeHint+'%  <?php echo $LNG->STATS_CON_HINT_TEXT;?>', 'style':'width:'+negativeWidth+'%'} );
			votebar.insert(bar);
			votebar.insert(remainder);

			if (positiveWidth > negativeWidth) {
				bar.insert(positiveHint+"%");
			} else if (negativeWidth > positiveWidth) {
				remainder.insert(negativeHint+"%");
			} else {
				bar.insert(positiveHint+"%");
				remainder.insert(negativeHint+"%");
			}
		}

		container.update(votebar);
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
		container.update(message+' <span style="padding-left:10px;">'+idays+' '+daylabel+'</span><span style="padding-left:10px;">'+ihours+hourlabel+'</span><span style="padding-left:10px;">'+iminutes+minlabel+'</span><span style="padding-left:10px;">'+iseconds+seclabel+'</span>');
	} else {
		container.update(message+' <span style="padding-left:10px;">'+idays+' '+daylabel+'</span><span style="padding-left:10px;">'+ihours+hourlabel+'</span><span style="padding-left:10px;">'+iminutes+minlabel+'</span>');
	}

	function showRemaining() {
		var now = new Date();
		var distance = end.getTime() - now.getTime();
		if (distance < 0) {
			clearInterval(timer);
			if (containerid) {
				$('div-'+containerid).style.className = "remainderall";
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
			container.update(message+' <span style="padding-left:10px;">'+days+' '+daylabel+'</span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span><span style="padding-left:10px;">'+seconds+seclabel+'</span>');
		} else {
			container.update(message+' <span style="padding-left:10px;">'+days+' '+daylabel+'</span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span>');
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
	container.update(message+' <span style="padding-left:10px;">'+idays + ' days</span><span style="padding-left:10px;">' + ihours + 'hrs</span><span style="padding-left:10px;">' + iminutes + 'mins</span>');

	function showRemainingVotes() {
		var now = new Date();
		var distance = end.getTime() - now.getTime();
		if (distance < 0) {
			clearInterval(timer);
			//$('div-'+containerid).style.className = "remainderall";
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

		//container.update(message+' <span style="padding-left:10px;">'+days+' '+daylabel+'</span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span>');

		var seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECONDS; ?>';
		if (seconds == 1) {
			seclabel = '<?php echo $LNG->NODE_COUNTDOWN_SECOND; ?>';
		}
		container.update(message+' <span style="padding-left:10px;">'+days+' '+daylabel+'<?php echo $LNG->NODE_COUNTDOWN_DAYS; ?></span><span style="padding-left:10px;">'+hours+hourlabel+'</span><span style="padding-left:10px;">'+minutes+minlabel+'</span><span style="padding-left:10px;">'+seconds+seclabel+'</span>');
	}

	timer = setInterval(showRemainingVotes, 1000);
	timers.push(timer);
}

function recalculateVotes(focalnodeid) {
	if (focalnodeid && $('debatestatsvotes'+focalnodeid)) {
		var votes = $('debatestatsvotes'+focalnodeid).votes;
		var total = 0;
		for (var key in votes) {
		    if (votes.hasOwnProperty(key)) {
		        total += parseInt(votes[key]);
		    }
		}
		$('debatestatsvotes'+focalnodeid).update(total);
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
				var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.insert(bar);
				var discussionend = 0;
				if (node.properties[0] && node.properties[0].discussionend) {
					discussionend = convertUTCTimeToLocalDate(node.properties[0].discussionend);
				} else if (node.properties.discussionend) {
					discussionend = convertUTCTimeToLocalDate(node.properties.discussionend);
				}
				countDownIssueTimer(discussionend.getTime(), bar, '<?php echo $LNG->NODE_COUNTDOWN_DISCUSSION_END; ?>', true);
			} else {
				var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.insert(bar);
				bar.insert("<?php echo $LNG->ISSUE_PHASE_CURRENT.': '.$LNG->ISSUE_PHASE_DISCUSS; ?>");
			}
		} else if (phase == REDUCE_PHASE) {
			if (mainheading) {
				var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.insert(bar);
				var lemonend = 0;
				if (node.properties[0] && node.properties[0].lemoningend) {
					lemonend = convertUTCTimeToLocalDate(node.properties[0].lemoningend);
				} else if (node.properties.lemoningend) {
					lemonend = convertUTCTimeToLocalDate(node.properties.lemoningend);
				}
				countDownIssueTimer(lemonend.getTime(), bar, '<?php echo $LNG->NODE_COUNTDOWN_REDUCING_END; ?>', true);
			} else {
				var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.insert(bar);
				bar.insert("<?php echo $LNG->ISSUE_PHASE_CURRENT.': '.$LNG->ISSUE_PHASE_REDUCE; ?>");
			}
		} else if (phase == DECIDE_PHASE) {
			if (mainheading) {
				var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.insert(bar);
				var voteend = 0;
				if (node.properties[0] && node.properties[0].votingend) {
					voteend = convertUTCTimeToLocalDate(node.properties[0].votingend);
				} else if (node.properties.votingend) {
					voteend = convertUTCTimeToLocalDate(node.properties.votingend);
				}
				countDownIssueTimer(voteend.getTime(), bar, '<?php echo $LNG->NODE_COUNTDOWN_DECIDING_END; ?>', true);
			} else {
				var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
				countdowntableDiv.insert(bar);
				bar.insert("<?php echo $LNG->ISSUE_PHASE_CURRENT.': '.$LNG->ISSUE_PHASE_DECIDE; ?>");
			}
		} else if (phase == TIMED_VOTEON_PHASE) {
			var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.insert(bar);
			bar.insert("<?php echo $LNG->NODE_VOTE_COUNTDOWN_OPEN; ?>");
		} else if (phase == TIMED_VOTEPENDING_PHASE) {
			var votestart = 0;
			if (node.properties[0] && node.properties[0].votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties[0].votingstart);
			} else if (node.properties.votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties.votingstart);
			}
			var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.insert(bar);
			countDownIssueVoteTimer(votestart.getTime(), bar, "<?php echo $LNG->NODE_VOTE_COUNTDOWN_START; ?>");
		} else if (phase == OPEN_VOTEON_PHASE || phase == OPEN_PHASE) {
			var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.insert(bar);
			bar.insert("<?php echo $LNG->NODE_VOTE_COUNTDOWN_OPEN; ?>");
		} else if (phase == OPEN_VOTEPENDING_PHASE) {
			var votestart = 0;
			if (node.properties[0] && node.properties[0].votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties[0].votingstart);
			} else if (node.properties.votingstart) {
				votestart = convertUTCTimeToLocalDate(node.properties.votingstart);
			}
			var bar = new Element("div", {'class':'issuecountdownrightdiv'} );
			countdowntableDiv.insert(bar);
			countDownIssueVoteTimer(votestart.getTime(), bar, "<?php echo $LNG->NODE_VOTE_COUNTDOWN_START; ?>");
		}
	}
}
