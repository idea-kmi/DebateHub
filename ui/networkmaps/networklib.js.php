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

var SELECTED_GRAPH_NODE = "";

function createSocialNetworkGraphKey() {
	var tb1 = new Element("div", {'id':'graphkeydivtoolbar','class':'toolbarrow mb-3 mt-3'});

	var key = new Element("div", {'id':'key', 'class':'key d-flex flex-row gap-3'});
	var text = "";
	text += '<div><span class="networkmaps-key key-social-most"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_MOST; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-high"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_HIGHLY; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-moderate"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_MODERATELY; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-slight"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_SLIGHTLY; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-selected"><?php echo $LNG->NETWORKMAPS_KEY_SELECTED_ITEM; ?></span></div>';

	key.insert(text);
	tb1.insert(key);
	return tb1;
}

/**
 * Create the key for the graph node types etc...
 * @return a div holding the graph key.
 */
function createGroupNetworkGraphKey() {
	var tb1 = new Element("div", {'id':'graphkeydivtoolbar','class':'toolbarrow mb-3'});

	var key = new Element("div", {'id':'key', 'class':'key d-flex flex-row gap-3'});
	var text = "";

	text += '<div><span class="networkmaps-key key-network-type" style="background: '+issuebackpale+';"><?php echo $LNG->ISSUE_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+solutionbackpale+';"><?php echo $LNG->SOLUTION_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+probackpale+';"><?php echo $LNG->PRO_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+conbackpale+';"><?php echo $LNG->CON_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-selected"><?php echo $LNG->NETWORKMAPS_KEY_SELECTED_ITEM; ?></span></div>';

	key.insert(text);
	tb1.insert(key);

	var count = new Element("div", {'id':'graphConnectionCount', 'class':'connections-count'});
	key.insert(count);

	return tb1;
}

/**
 * Create the key for the graph node types etc...
 * @return a div holding the graph key.
 */
function createNetworkGraphKey() {
	var tb1 = new Element("div", {'id':'graphkeydivtoolbar','class':'toolbarrow mb-3'});

	var key = new Element("div", {'id':'key', 'class':'key d-flex flex-row gap-3'});
	var text = "";
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+issuebackpale+';"><?php echo $LNG->ISSUE_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+solutionbackpale+';"><?php echo $LNG->SOLUTION_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+probackpale+';"><?php echo $LNG->PRO_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+conbackpale+';"><?php echo $LNG->CON_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-selected"><?php echo $LNG->NETWORKMAPS_KEY_SELECTED_ITEM; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-focal"><?php echo $LNG->NETWORKMAPS_KEY_FOCAL_ITEM; ?></span></div>';

	key.insert(text);
	tb1.insert(key);

	var count = new Element("div", {'id':'graphConnectionCount', 'class':'connections-count'});
	key.insert(count);

	return tb1;
}

/**
 * Create the basic graph toolbar for all network graphs
 */
function createBasicGraphToolbar(forcedirectedGraph, contentarea) {

	var tb2 = new Element("div", {'id':'graphmaintoolbar','class':'graphmaintoolbar toolbarrow d-flex flex-row justify-content-between gap-2'});

	var button = new Element("button", {'id':'expandbutton','title':'<?php echo $LNG->NETWORKMAPS_RESIZE_MAP_HINT; ?>', 'class':'d-none'});
	var icon = new Element("img", {'id':'expandicon', 'src':"<?php echo $HUB_FLM->getImagePath('enlarge2.gif'); ?>", 'border':'0', 'title':'<?php echo $LNG->NETWORKMAPS_RESIZE_MAP_HINT; ?>'});
	button.insert(icon);
	tb2.insert(button);

	var link = new Element("a", {'id':'expandlink', 'title':'<?php echo $LNG->NETWORKMAPS_RESIZE_MAP_HINT; ?>', 'class':'map-btn'});
	link.insert('<span id="linkbuttonsvn"><i class="fas fa-expand-alt fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_ENLARGE_MAP_LINK; ?></span>');

	var handler = function() {
		if (document.getElementById('header').style.display == "none") {
			document.getElementById('linkbuttonsvn').innerHTML = '<i class="fas fa-expand-alt fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_ENLARGE_MAP_LINK; ?>';
			reduceMap(contentarea, forcedirectedGraph);
		} else {
			document.getElementById('linkbuttonsvn').innerHTML = '<i class="fas fa-compress-alt fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_REDUCE_MAP_LINK; ?>';
			enlargeMap(contentarea, forcedirectedGraph);
		}
	};
	link.addEventListener("click", handler);	
	button.addEventListener("click", handler);		
	tb2.insert(link);

	var zoomOut = new Element("button", {'class':'btn btn-link', 'title':'<?php echo $LNG->GRAPH_ZOOM_OUT_HINT;?>'});
	zoomOut.insert('<span><i class="fas fa-search-minus fa-lg" aria-hidden="true"></i> <?php echo $LNG->GRAPH_ZOOM_OUT_HINT; ?></span>');
	var zoomOuthandler = function() {
		zoomFD(forcedirectedGraph, 5.0);
	};
	zoomOut.addEventListener("click", zoomOuthandler);	
	tb2.insert(zoomOut);

	var zoomIn = new Element("button", {'class':'btn btn-link', 'title':'<?php echo $LNG->GRAPH_ZOOM_IN_HINT;?>'});
	zoomIn.insert('<span><i class="fas fa-search-plus fa-lg" aria-hidden="true"></i> <?php echo $LNG->GRAPH_ZOOM_IN_HINT; ?></span>');
	var zoomInhandler = function() {
		zoomFD(forcedirectedGraph, -5.0);
	};
	zoomIn.addEventListener("click", zoomInhandler);	
	tb2.insert(zoomIn);

	var zoom1to1 = new Element("button", {'class':'btn btn-link', 'title':'<?php echo $LNG->GRAPH_ZOOM_ONE_TO_ONE_HINT;?>'});
	zoom1to1.insert('<span><i class="fas fa-search fa-lg" aria-hidden="true"></i> 1:1 focus</span>');
	var zoom1to1handler = function() {
		zoomFDFull(forcedirectedGraph);
	};
	zoom1to1.addEventListener("click", zoom1to1handler);		
	tb2.insert(zoom1to1);

	var zoomFit = new Element("button", {'class':'btn btn-link', 'title':'<?php echo $LNG->GRAPH_ZOOM_FIT_HINT;?>'});
	zoomFit.insert('<span><i class="fas fa-expand fa-lg" aria-hidden="true"></i> Fit all</span>');
	var zoomFithandler = function() {
		zoomFDFit(forcedirectedGraph);
	};
	zoomFit.addEventListener("click", zoomFithandler);	
	tb2.insert(zoomFit);

	var printButton = new Element("button", {'class':'btn btn-link', 'title':'<?php echo $LNG->GRAPH_PRINT_HINT;?>'});
	printButton.insert('<span><i class="fas fa-print fa-lg" aria-hidden="true"></i> <?php echo $LNG->GRAPH_PRINT_HINT; ?></span>');
	var printButtonhandler = function() {
		printCanvas(forcedirectedGraph);
	};
	printButton.addEventListener("click", printButtonhandler);	
	tb2.insert(printButton);

	return tb2;
}

/**
 * Create the graph toolbar for Social network graphs
 */
function createSocialGraphToolbar(forcedirectedGraph,contentarea) {

	var tb2 = createBasicGraphToolbar(forcedirectedGraph,contentarea);

	var button3 = new Element("button", {'id':'viewdetailbutton','class':'d-none','title':'<?php echo $LNG->NETWORKMAPS_SOCIAL_ITEM_HINT; ?>'});
	tb2.insert(button3);

	var view3 = new Element("a", {'id':'viewdetaillink', "class":"map-btn", 'title':"<?php echo $LNG->NETWORKMAPS_SOCIAL_ITEM_HINT; ?>"});
	view3.insert('<span id="viewbuttons"><i class="fas fa-user fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_SOCIAL_ITEM_LINK; ?></span>');

	var handler3 = function() {
		var node = getSelectFDNode(forcedirectedGraph);
		if (node != null && node != "") {
			var userid = node.getData('oriuser').userid;
			if (userid != "") {
				viewUserHome(userid);
			} else {
				alert("<?php echo $LNG->NETWORKMAPS_SELECTED_NODEID_ERROR; ?>");
			}
		}
	};
	button3.addEventListener("click", handler3);
	view3.addEventListener("click", handler3);	
	tb2.insert(view3);

	var button2 = new Element("button", {'id':'viewdetailbutton','class':'d-none', 'title':'<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>'});
	tb2.insert(button2);

	var view = new Element("a", {'id':'viewdetaillink', 'class':'map-btn', 'title':"<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>"});
	view.insert('<span id="viewbuttons"><i class=\"fas fa-link fa-lg\" aria-hidden=\"true\"></i> <?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_LINK; ?></span>');
	var handler2 = function() {
		var adj = getSelectFDLink(forcedirectedGraph);
		var connectionids = adj.getData('connections');
		if (connectionids != "") {
			showMultiConnections(connectionids);
		} else {
			alert("<?php echo $LNG->NETWORKMAPS_SELECTED_NODEID_ERROR; ?>");
		}
	};
	button2.addEventListener("click", handler2);
	view.addEventListener("click", handler2);	
	tb2.insert(view);

	return tb2;
}

/**
 * Create the graph toolbar for Node network graphs
 */
function createGraphToolbar(forcedirectedGraph,contentarea) {

	var tb2 = createBasicGraphToolbar(forcedirectedGraph,contentarea);

	var button2 = new Element("button", {'id':'viewdetailbutton','class':'d-none','title':'<?php echo $LNG->NETWORKMAPS_EXPLORE_ITEM_HINT; ?>'});
	tb2.insert(button2);

	var view = new Element("a", {'id':'viewdetaillink', "class":"map-btn", 'title':"<?php echo $LNG->NETWORKMAPS_EXPLORE_ITEM_HINT; ?>"});
	view.insert('<span id="viewbuttons"><i class="fas fa-lightbulb fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_EXPLORE_ITEM_LINK; ?></span>');

	var handler2 = function() {
		var node = getSelectFDNode(forcedirectedGraph);
		if (node != null && node != "") {
			var nodeid = node.id;
			var nodetype = node.getData('nodetype');
			var width = getWindowWidth();
			var height = getWindowHeight()-20;
			viewNodeDetails(nodeid, nodetype, width, height);
		} else {
			alert("<?php echo $LNG->NETWORKMAPS_SELECTED_NODEID_ERROR; ?>");
		}
	};
	button2.addEventListener("click", handler2);
	view.addEventListener("click", handler2);	
	tb2.insert(view);

	var button3 = new Element("button", {'id':'viewdetailbutton', 'class':'d-none', 'title':'<?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_HINT; ?>'});
	tb2.insert(button3);

	var view3 = new Element("a", {'id':'viewdetaillink', 'class':'map-btn', 'title':"<?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_HINT; ?>"});
	view3.insert('<span id="viewbuttons"><i class=\"fas fa-user fa-lg\" aria-hidden=\"true\"></i> <?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_LINK; ?></span>');
	var handler3 = function() {
		var node = getSelectFDNode(forcedirectedGraph);
		if (node != null && node != "") {
			var userid = node.getData('oriuser').userid;
			if (userid != "") {
				viewUserHome(userid);
			} else {
				alert("<?php echo $LNG->NETWORKMAPS_SELECTED_NODEID_ERROR; ?>");
			}
		}
	};
	button3.addEventListener("click", handler3);
	view3.addEventListener("click", handler3);	
	tb2.insert(view3);

	return tb2;
}

/**
 * Create the graph toolbar for all embedded network graphs
 */
function createEmbedBasicGraphToolbar(forcedirectedGraph, contentarea) {

	var tb2 = new Element("div", {'id':'graphmaintoolbar','class':'toolbarrow', 'style':'padding-top:5px;display:block;'});

	var zoomOut = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_IN_HINT;?>'});
	var zoomOuticon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('magminus.png'); ?>", 'border':'0'});
	zoomOut.insert(zoomOuticon);
	var zoomOuthandler = function() {
		zoomFD(forcedirectedGraph, 5.0);
	};
	zoomOut.addEventListener("click", zoomOuthandler);	
	tb2.insert(zoomOut);

	var zoomIn = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_OUT_HINT;?>'});
	var zoomInicon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('magplus.png'); ?>", 'border':'0'});
	zoomIn.insert(zoomInicon);
	var zoomInhandler = function() {
		zoomFD(forcedirectedGraph, -5.0);
	};
	zoomIn.addEventListener("click", zoomInhandler);
	tb2.insert(zoomIn);

	var zoom1to1 = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_ONE_TO_ONE_HINT;?>'});
	var zoom1to1icon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('zoomfull.png'); ?>", 'border':'0'});
	zoom1to1.insert(zoom1to1icon);
	var zoom1to1handler = function() {
		zoomFDFull(forcedirectedGraph);
	};
	zoom1to1.addEventListener("click", zoom1to1handler);		
	tb2.insert(zoom1to1);

	var zoomFit = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_FIT_HINT;?>'});
	var zoomFiticon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('zoomfit.png'); ?>", 'border':'0'});
	zoomFit.insert(zoomFiticon);
	var zoomFithandler = function() {
		zoomFDFit(forcedirectedGraph);
	};
	zoomFit.addEventListener("click", zoomFithandler);	
	tb2.insert(zoomFit);

	var printButton = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_PRINT_HINT;?>'});
	var printButtonicon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('printer.png'); ?>", 'border':'0'});
	printButton.insert(printButtonicon);
	var printButtonhandler = function() {
		printCanvas(forcedirectedGraph);
	};
	printButton.addEventListener("click", printButtonhandler);	
	tb2.insert(printButton);

	var count = new Element("div", {'id':'graphConnectionCount','style':'float:left;margin-left-25px;margin-top:7px;'});
	tb2.insert(count);

	return tb2;
}

var lastconnections = "";
function getLastConnections() {
	return lastconnections;
}

/**
 * Create the graph toolbar for Social network graphs
 */
function createEmbedSocialGraphToolbar(forcedirectedGraph,contentarea) {

	var tb2 = createEmbedBasicGraphToolbar(forcedirectedGraph,contentarea);

	var button2 = new Element("button", {'id':'viewdetailbutton','style':'margin-left: 30px;','title':'<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>'});
	var icon2 = new Element("img", {'id':'viewdetailicon', 'src':"<?php echo $HUB_FLM->getImagePath('connection.png'); ?>", 'border':'0'});
	button2.insert(icon2);
	tb2.insert(button2);

	var view = new Element("a", {'id':'viewdetaillink', 'title':"<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>"});
	view.insert('<span id="viewbuttons"><?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_LINK; ?></span>');
	var handler2 = function() {
		var adj = getSelectFDLink(forcedirectedGraph);
		var connections = adj.getData('connections');
		lastconnections = "";
		if (connections && connections.length > 0) {
			lastconnections = connections;
			windowRef = loadDialog("multiconnections", URL_ROOT+"ui/popups/showmulticonns.php", 790, 450);
		} else {
			alert("<?php echo $LNG->NETWORKMAPS_SELECTED_NODEID_ERROR; ?>");
		}
	};
	button2.addEventListener("click", handler2);
	view.addEventListener("click", handler2);
	tb2.insert(view);

	return tb2;
}

/**
 * Create the graph toolbar for embedded network graphs
 */
function createEmbedNetworkGraphToolbar(forcedirectedGraph,contentarea) {
	var tb2 = new Element("div", {'id':'graphmaintoolbar','class':'toolbarrow', 'style':'padding-top:5px;display:block;'});

	/*
	var button = new Element("button", {'id':'homebutton','style':'float:left;margin-left:8px;','title':'<?php echo $LNG->BUILDER_GOTO_HOME_SITE_HINT; ?>'});
	var icon = new Element("img", {'id':'homeicon', 'alt':'<?php echo $CFG->SITE_TITLE; ?>', 'src':"<?php echo $HUB_FLM->getImagePath('builder-logo.png'); ?>", 'border':'0'});
	button.insert(icon);
	tb2.insert(button);

	var handler = function() {
		//go to debate hub
	};
	button.addEventListener("click", handler);
	tb2.insert(button);
	*/

	var zoomOut = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_IN_HINT;?>'});
	var zoomOuticon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('magminus.png'); ?>", 'border':'0'});
	zoomOut.insert(zoomOuticon);
	var zoomOuthandler = function() {
		zoomFD(forcedirectedGraph, 5.0);
	};
	zoomOut.addEventListener("click", zoomOuthandler);
	tb2.insert(zoomOut);

	var zoomIn = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_OUT_HINT;?>'});
	var zoomInicon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('magplus.png'); ?>", 'border':'0'});
	zoomIn.insert(zoomInicon);
	var zoomInhandler = function() {
		zoomFD(forcedirectedGraph, -5.0);
	};
	zoomIn.addEventListener("click", zoomInhandler);
	tb2.insert(zoomIn);

	var zoom1to1 = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_ONE_TO_ONE_HINT;?>'});
	var zoom1to1icon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('zoomfull.png'); ?>", 'border':'0'});
	zoom1to1.insert(zoom1to1icon);
	var zoom1to1handler = function() {
		zoomFDFull(forcedirectedGraph);
	};
	zoom1to1.addEventListener("click", zoom1to1handler);
	tb2.insert(zoom1to1);

	var zoomFit = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_FIT_HINT;?>'});
	var zoomFiticon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('zoomfit.png'); ?>", 'border':'0'});
	zoomFit.insert(zoomFiticon);
	var zoomFithandler = function() {
		zoomFDFit(forcedirectedGraph);
	};
	zoomFit.addEventListener("click", zoomFithandler);
	tb2.insert(zoomFit);

	var printButton = new Element("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_PRINT_HINT;?>'});
	var printButtonicon = new Element("img", {'src':"<?php echo $HUB_FLM->getImagePath('printer.png'); ?>", 'border':'0'});
	printButton.insert(printButtonicon);
	var printButtonhandler = function() {
		printCanvas(forcedirectedGraph);
	};
	printButton.addEventListener("click", printButtonhandler);	
	tb2.insert(printButton);

	var key = new Element("div", {'id':'key', 'style':'float:left;margin-left:25px;margin-top:10px;'});
	var text = "";
	text+= '<div><span style="background:'+issuebackpale+'; color: black; font-weight:bold"><?php echo $LNG->ISSUE_NAME; ?></span></div>';
	text += '<div><span style="background: '+solutionbackpale+'; color: black; font-weight:bold"><?php echo $LNG->SOLUTION_NAME; ?></span></div>';
	text += '<div><span style="background: '+probackpale+'; color: black; font-weight:bold"><?php echo $LNG->PRO_NAME; ?></span></div>';
	text += '<div><span style="background: '+conbackpale+'; color: black; font-weight:bold"><?php echo $LNG->CON_NAME; ?></span></div>';

	key.insert(text);

	tb2.insert(key);

	var count = new Element("div", {'id':'graphConnectionCount','style':'float:left;margin-left-25px;margin-top:10px;'});
	tb2.insert(count);

	return tb2;
}

/**
 * Calulate the width and height of the visible graph area
 * depending if it is reduced or enlarged at present.
 */
function resizeFDGraph(graphview, contentarea, withInner){
	if (document.getElementById('header')&& document.getElementById('header').style.display == "none") {
		var width = document.getElementById(contentarea).offsetWidth - 35;
		var height = getWindowHeight();
		//alert(height);

		if (document.getElementById('graphkeydivtoolbar')) {
			height -= document.getElementById('graphkeydivtoolbar').offsetHeight;
		}
		if (document.getElementById('graphmaintoolbar')) {
			height -= document.getElementById('graphmaintoolbar').offsetHeight;
		}
		//if (document.getElementById('nodearealineartitle')) {
		//	height -= document.getElementById('nodearealineartitle').offsetHeight;
		//}
		height -= 20;

		//alert(height);

		document.getElementById(graphview.config.injectInto+'-outer').style.width = width+"px";
		document.getElementById(graphview.config.injectInto+'-outer').style.height = height+"px";

		//if (withInner) {
			resizeFDGraphCanvas(graphview, width, height);
		//}
	} else {
		var size = calulateInitialGraphViewport(contentarea)
		document.getElementById(graphview.config.injectInto+'-outer').style.width = size.width+"px";
		document.getElementById(graphview.config.injectInto+'-outer').style.height = size.height+"px";

		//if (withInner) {
			resizeFDGraphCanvas(graphview, width, height);
		//}
	}

	// GRAB FOCUS
	graphview.canvas.getPos(true);
}


function calulateInitialGraphViewport(areaname) {
	var w = document.getElementById(areaname).offsetWidth; // - 30;
	var h = getWindowHeight();
	//alert(h);

	if (document.getElementById('header')) {
		h -= document.getElementById('header').offsetHeight;
	}

	// The explore views toolbar
	if (document.getElementById('nodearealineartitle')) {
		h -= document.getElementById('nodearealineartitle').offsetHeight;
	}
	if (document.getElementById('headertoolbar')) {
		h -= document.getElementById('headertoolbar').offsetHeight;
		h -= 30;
	}

	if (document.getElementById('graphkeydivtoolbar')) {
		h -= document.getElementById('graphkeydivtoolbar').offsetHeight;
	}
	if (document.getElementById('graphmaintoolbar')) {
		h -= document.getElementById('graphmaintoolbar').offsetHeight;
	}

	// Main social Network
	if (document.getElementById('tabs')) { // +user social uses this
		h -= document.getElementById('tabs').offsetHeight;
	}
	if (document.getElementById('tab-content-user-title')) {
		h -= document.getElementById('tab-content-user-title').offsetHeight;
		h -= 35;
	}
	if (document.getElementById('tab-content-user-search')) {
		h -= document.getElementById('tab-content-user-search').offsetHeight;
	}
	if (document.getElementById('usertabs')) {
		h -= document.getElementById('usertabs').offsetHeight;
	}

	// User social network
	if (document.getElementById('context')) {
		h -= document.getElementById('context').offsetHeight;
	}
	if (document.getElementById('tab-content-user-bar')) {
		h -= document.getElementById('tab-content-user-bar').offsetHeight;
		h -= 20;
	}

	//alert(h);
	return {width:w, height:h};
}

/**
 * Called to set the screen to standard view
 */
function reduceMap(contentarea, forcedirectedGraph) {

	if (document.getElementById('header')) {
		document.getElementById('header').style.display="block";
	}

	// The explore views toolbar
	if (document.getElementById('headertoolbar')) {
		document.getElementById('headertoolbar').style.display="block";
	}
	if (document.getElementById('nodearealineartitle')) {
		document.getElementById('nodearealineartitle').style.display="block";
	}

	// Main social Network
	if (document.getElementById('tabs')) { // +user social uses this
		document.getElementById('tabs').style.display="block";
	}
	if (document.getElementById('tab-content-user-title')) {
		document.getElementById('tab-content-user-title').style.display="block";
	}
	if (document.getElementById('tab-content-user-search')) {
		document.getElementById('tab-content-user-search').style.display="block";
	}
	if (document.getElementById('usertabs')) {
		document.getElementById('usertabs').style.display="block";
	}

	// User social network
	if (document.getElementById('context')) {
		document.getElementById('context').style.display="block";
	}
	if (document.getElementById('tab-content-user-bar')) {
		document.getElementById('tab-content-user-bar').style.display="block";
	}

	resizeFDGraph(forcedirectedGraph, contentarea, true);
}

/**
 * Called to remove some screen realestate to increase map area.
 */
function enlargeMap(contentarea, forcedirectedGraph) {

	if (document.getElementById('header')) {
		document.getElementById('header').style.display="none";
	}

	// The explore views toolbar
	if (document.getElementById('headertoolbar')) {
		document.getElementById('headertoolbar').style.display="none";
	}
	if (document.getElementById('nodearealineartitle')) {
		document.getElementById('nodearealineartitle').style.display="none";
	}

	// Main social Network
	if (document.getElementById('tabs')) { // +user social uses this
		document.getElementById('tabs').style.display="none";
	}
	if (document.getElementById('tab-content-user-title')) {
		document.getElementById('tab-content-user-title').style.display="none";
	}
	if (document.getElementById('tab-content-user-search')) {
		document.getElementById('tab-content-user-search').style.display="none";
	}
	if (document.getElementById('usertabs')) {
		document.getElementById('usertabs').style.display="none";
	}

	// User social network
	if (document.getElementById('context')) {
		document.getElementById('context').style.display="none";
	}
	if (document.getElementById('tab-content-user-bar')) {
		document.getElementById('tab-content-user-bar').style.display="none";
	}

	resizeFDGraph(forcedirectedGraph, contentarea, true);
}

/**
 * Called by the Applet to open the applet help
 */
function showHelp() {
    loadDialog('help', URL_ROOT+'ui/pages/networkmap.php');
}

/**
 * Called by the Applet to go to the multi connection expanded view for the given connection
 */
function showMultiConnections(connectionids) {
	loadDialog("multiconnections", URL_ROOT+"ui/popups/showmulticonns.php?connectionids="+connectionids, 790, 450);
}

/**
 * Check if the current brwoser supports HTML5 Canvas.
 * Return true if it does, else false.
 */
function isCanvasSupported(){
  	var elem = document.createElement('canvas');
  	return !!(elem.getContext && elem.getContext('2d'));
}
