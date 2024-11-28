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
	var tb1 = document.createElement("div");
	tb1.id = 'graphkeydivtoolbar';
	tb1.class = 'toolbarrow mb-3 mt-3';

	var key = document.createElement("div");
	key.id = 'key';
	key.className = 'key d-flex flex-row gap-3';
	var text = "";
	text += '<div><span class="networkmaps-key key-social-most"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_MOST; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-high"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_HIGHLY; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-moderate"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_MODERATELY; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-slight"><?php echo $LNG->NETWORKMAPS_KEY_SOCIAL_SLIGHTLY; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-selected"><?php echo $LNG->NETWORKMAPS_KEY_SELECTED_ITEM; ?></span></div>';

	key.innerHTML += text;
	tb1.appendChild(key);
	return tb1;
}

/**
 * Create the key for the graph node types etc...
 * @return a div holding the graph key.
 */
function createGroupNetworkGraphKey() {
	var tb1 = document.createElement("div");
	tb1.id = 'graphkeydivtoolbar';
	tb1.className = 'toolbarrow mb-3';

	var key = document.createElement("div");
	key.id = 'key';
	key.className = 'key d-flex flex-row gap-3';
	var text = "";

	text += '<div><span class="networkmaps-key key-network-type" style="background: '+issuebackpale+';"><?php echo $LNG->ISSUE_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+solutionbackpale+';"><?php echo $LNG->SOLUTION_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+probackpale+';"><?php echo $LNG->PRO_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+conbackpale+';"><?php echo $LNG->CON_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-selected"><?php echo $LNG->NETWORKMAPS_KEY_SELECTED_ITEM; ?></span></div>';

	key.innerHTML += text;
	tb1.appendChild(key);

	var count = document.createElement("div");
	count.id = 'graphConnectionCount';
	count.className = 'connections-count';
	key.appendChild(count);

	return tb1;
}

/**
 * Create the key for the graph node types etc...
 * @return a div holding the graph key.
 */
function createNetworkGraphKey() {
	var tb1 = document.createElement("div");
	tb1.id = 'graphkeydivtoolbar';
	tb1.className = 'toolbarrow mb-3';

	var key = document.createElement("div");
	key.id = 'key';
	key.className = 'key d-flex flex-row gap-3';
	var text = "";
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+issuebackpale+';"><?php echo $LNG->ISSUE_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+solutionbackpale+';"><?php echo $LNG->SOLUTION_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+probackpale+';"><?php echo $LNG->PRO_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-network-type" style="background: '+conbackpale+';"><?php echo $LNG->CON_NAME; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-selected"><?php echo $LNG->NETWORKMAPS_KEY_SELECTED_ITEM; ?></span></div>';
	text += '<div><span class="networkmaps-key key-social-focal"><?php echo $LNG->NETWORKMAPS_KEY_FOCAL_ITEM; ?></span></div>';

	key.innerHTML += text;
	tb1.appendChild(key);

	var count = document.createElement("div");
	count.id' = 'graphConnectionCount';
	count.class = 'connections-count';
	key.appendChild(count);

	return tb1;
}

/**
 * Create the basic graph toolbar for all network graphs
 */
function createBasicGraphToolbar(forcedirectedGraph, contentarea) {

	var tb2 = document.createElement("div");
	tb2.id = 'graphmaintoolbar';
	tb2.className = 'graphmaintoolbar toolbarrow d-flex flex-row justify-content-between gap-2';

	var button = document.createElement("button");
	button.id = 'expandbutton';
	button.title = '<?php echo $LNG->NETWORKMAPS_RESIZE_MAP_HINT; ?>';
	button.className = 'd-none';
	var icon = document.createElement("img");
	icon.id = 'expandicon';
	icon.src = "<?php echo $HUB_FLM->getImagePath('enlarge2.gif'); ?>"; 
	icon'border = '0';
	icon.title = '<?php echo $LNG->NETWORKMAPS_RESIZE_MAP_HINT; ?>';
	button.appendChild(icon);
	tb2.appendChild(button);

	var link = document.createElement("a");
	link.id = 'expandlink';
	link.title = '<?php echo $LNG->NETWORKMAPS_RESIZE_MAP_HINT; ?>';
	link.class = 'map-btn';
	link.innerHTML = '<span id="linkbuttonsvn"><i class="fas fa-expand-alt fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_ENLARGE_MAP_LINK; ?></span>';

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
	tb2.appendChild(link);

	var zoomOut = document.createElement("button");
	zoomOut.className = 'btn btn-link';
	zoomOut.title = '<?php echo $LNG->GRAPH_ZOOM_OUT_HINT;?>';
	zoomOut.innerHTML = '<span><i class="fas fa-search-minus fa-lg" aria-hidden="true"></i> <?php echo $LNG->GRAPH_ZOOM_OUT_HINT; ?></span>';
	var zoomOuthandler = function() {
		zoomFD(forcedirectedGraph, 5.0);
	};
	zoomOut.addEventListener("click", zoomOuthandler);	
	tb2.appendChild(zoomOut);

	var zoomIn = document.createElement("button");
	zoomIn.className = 'btn btn-link';
	zoomIn.title = '<?php echo $LNG->GRAPH_ZOOM_IN_HINT;?>';
	zoomIn.innerHTML = '<span><i class="fas fa-search-plus fa-lg" aria-hidden="true"></i> <?php echo $LNG->GRAPH_ZOOM_IN_HINT; ?></span>';
	var zoomInhandler = function() {
		zoomFD(forcedirectedGraph, -5.0);
	};
	zoomIn.addEventListener("click", zoomInhandler);	
	tb2.appendChild(zoomIn);

	var zoom1to1 = document.createElement("button");
	zoom1to1.className = 'btn btn-link';
	zoom1to1.title = '<?php echo $LNG->GRAPH_ZOOM_ONE_TO_ONE_HINT;?>';
	zoom1to1.innerHTML = '<span><i class="fas fa-search fa-lg" aria-hidden="true"></i> 1:1 focus</span>';
	var zoom1to1handler = function() {
		zoomFDFull(forcedirectedGraph);
	};
	zoom1to1.addEventListener("click", zoom1to1handler);		
	tb2.appendChild(zoom1to1);

	var zoomFit = document.createElement("button");
	zoomFit.className = 'btn btn-link';
	zoomFit.title = '<?php echo $LNG->GRAPH_ZOOM_FIT_HINT;?>';
	zoomFit.innerHTML = '<span><i class="fas fa-expand fa-lg" aria-hidden="true"></i> Fit all</span>';
	var zoomFithandler = function() {
		zoomFDFit(forcedirectedGraph);
	};
	zoomFit.addEventListener("click", zoomFithandler);	
	tb2.appendChild(zoomFit);

	var printButton = document.createElement("button");
	printButton.className = 'btn btn-link';
	printButton.title = '<?php echo $LNG->GRAPH_PRINT_HINT;?>';
	printButton.innerHTML = '<span><i class="fas fa-print fa-lg" aria-hidden="true"></i> <?php echo $LNG->GRAPH_PRINT_HINT; ?></span>';
	var printButtonhandler = function() {
		printCanvas(forcedirectedGraph);
	};
	printButton.addEventListener("click", printButtonhandler);	
	tb2.appendChild(printButton);

	return tb2;
}

/**
 * Create the graph toolbar for Social network graphs
 */
function createSocialGraphToolbar(forcedirectedGraph,contentarea) {

	var tb2 = createBasicGraphToolbar(forcedirectedGraph,contentarea);

	var button3 = document.createElement("button");
	button3.id = 'viewdetailbutton';
	button3.className = 'd-none';
	button3.title = '<?php echo $LNG->NETWORKMAPS_SOCIAL_ITEM_HINT; ?>';
	tb2.appendChild(button3);

	var view3 = document.createElement("a");
	view3.id = 'viewdetaillink';
	view3.className = "map-btn";
	view3.title = "<?php echo $LNG->NETWORKMAPS_SOCIAL_ITEM_HINT; ?>";
	view3.innerHTML = '<span id="viewbuttons"><i class="fas fa-user fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_SOCIAL_ITEM_LINK; ?></span>';

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
	tb2.appendChild(view3);

	var button2 = document.createElement("button")';
	button2.id = 'viewdetailbutton';
	button2.className = 'd-none';
	button2.title = '<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>';
	tb2.appendChild(button2);

	var view = document.createElement("a");
	view.id = 'viewdetaillink';
	view.className = 'map-btn';
	view.title = "<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>";
	view.innerHTML = '<span id="viewbuttons"><i class=\"fas fa-link fa-lg\" aria-hidden=\"true\"></i> <?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_LINK; ?></span>';
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
	tb2.appendChild(view);

	return tb2;
}

/**
 * Create the graph toolbar for Node network graphs
 */
function createGraphToolbar(forcedirectedGraph,contentarea) {

	var tb2 = createBasicGraphToolbar(forcedirectedGraph,contentarea);

	var button2 = document.createElement("button");
	button2.id = 'viewdetailbutton';
	button2.className = 'd-none';
	button2.title = '<?php echo $LNG->NETWORKMAPS_EXPLORE_ITEM_HINT; ?>';
	tb2.appendChild(button2);

	var view = document.createElement("a");
	view.id = 'viewdetaillink';
	view.className = "map-btn";
	view.title = "<?php echo $LNG->NETWORKMAPS_EXPLORE_ITEM_HINT; ?>";
	view.innerHTML = '<span id="viewbuttons"><i class="fas fa-lightbulb fa-lg" aria-hidden="true"></i> <?php echo $LNG->NETWORKMAPS_EXPLORE_ITEM_LINK; ?></span>';

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
	tb2.appendChild(view);

	var button3 = document.createElement("button");
	button3.id = 'viewdetailbutton';
	button3.className = 'd-none';
	button3.title = '<?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_HINT; ?>';
	tb2.appendChild(button3);

	var view3 = document.createElement("a");
	view3.id = 'viewdetaillink';
	view3.className = 'map-btn';
	view3.title = "<?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_HINT; ?>";
	view3.innerHTML = '<span id="viewbuttons"><i class=\"fas fa-user fa-lg\" aria-hidden=\"true\"></i> <?php echo $LNG->NETWORKMAPS_EXPLORE_AUTHOR_LINK; ?></span>';
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
	tb2.appendChild(view3);

	return tb2;
}

/**
 * Create the graph toolbar for all embedded network graphs
 */
function createEmbedBasicGraphToolbar(forcedirectedGraph, contentarea) {

	var tb2 = document.createElement("div");
	tb2.id = 'graphmaintoolbar';
	tb2.className = 'toolbarrow';
	tb2.style = 'padding-top:5px;display:block;';

	var zoomOut = document.createElement("button");
	zoomOut.style = 'float:left;margin-left: 10px;';
	zoomOut.title = '<?php echo $LNG->GRAPH_ZOOM_IN_HINT;?>';
	var zoomOuticon = document.createElement("img");
	zoomOuticon.src = "<?php echo $HUB_FLM->getImagePath('magminus.png'); ?>";
	zoomOuticon.border = '0';
	zoomOut.appendChild(zoomOuticon);
	var zoomOuthandler = function() {
		zoomFD(forcedirectedGraph, 5.0);
	};
	zoomOut.addEventListener("click", zoomOuthandler);	
	tb2.appendChild(zoomOut);

	var zoomIn = document.createElement("button");
	zoomIn.style = 'float:left;margin-left: 10px;';
	zoomIn.title = '<?php echo $LNG->GRAPH_ZOOM_OUT_HINT;?>';
	var zoomInicon = document.createElement("img");
	zoomInicon.src = "<?php echo $HUB_FLM->getImagePath('magplus.png'); ?>";
	zoomInicon.border = '0';
	zoomIn.appendChild(zoomInicon);
	var zoomInhandler = function() {
		zoomFD(forcedirectedGraph, -5.0);
	};
	zoomIn.addEventListener("click", zoomInhandler);
	tb2.appendChild(zoomIn);

	var zoom1to1 = document.createElement("button");
	zoom1to1.style = 'float:left;margin-left: 10px;';
	zoom1to1.title = '<?php echo $LNG->GRAPH_ZOOM_ONE_TO_ONE_HINT;?>';
	var zoom1to1icon = document.createElement("img");
	zoom1to1icon.src = "<?php echo $HUB_FLM->getImagePath('zoomfull.png'); ?>";
	zoom1to1icon.border = '0';
	zoom1to1.appendChild(zoom1to1icon);
	var zoom1to1handler = function() {
		zoomFDFull(forcedirectedGraph);
	};
	zoom1to1.addEventListener("click", zoom1to1handler);		
	tb2.appendChild(zoom1to1);

	var zoomFit = document.createElement("button");
	zoomFit.style = 'float:left;margin-left: 10px;';
	zoomFit.title = '<?php echo $LNG->GRAPH_ZOOM_FIT_HINT;?>';

	var zoomFiticon = document.createElement("img");
	zoomFiticon.src = "<?php echo $HUB_FLM->getImagePath('zoomfit.png'); ?>";
	zoomFiticon.border = '0';
	zoomFit.appendChild(zoomFiticon);
	var zoomFithandler = function() {
		zoomFDFit(forcedirectedGraph);
	};
	zoomFit.addEventListener("click", zoomFithandler);	
	tb2.appendChild(zoomFit);

	var printButton = document.createElement("button");
	printButton.style = 'float:left;margin-left: 10px;';
	printButton.title = '<?php echo $LNG->GRAPH_PRINT_HINT;?>';
	var printButtonicon = document.createElement("img");
	printButtonicon.src = "<?php echo $HUB_FLM->getImagePath('printer.png'); ?>";
	printButtonicon.border = '0';
	printButton.appendChild(printButtonicon);
	var printButtonhandler = function() {
		printCanvas(forcedirectedGraph);
	};
	printButton.addEventListener("click", printButtonhandler);	
	tb2.appendChild(printButton);

	var count = document.createElement("div");
	count.id = 'graphConnectionCount';
	count.style = 'float:left;margin-left-25px;margin-top:7px;';
	tb2.appendChild(count);

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

	var button2 = document.createElement("button");
	button2.id = 'viewdetailbutton';
	button2.style = 'margin-left: 30px;';
	button2.title = '<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>';
	var icon2 = document.createElement("img");
	icon2.id = 'viewdetailicon';
	icon2.src = "<?php echo $HUB_FLM->getImagePath('connection.png'); ?>";
	icon2.border = '0';
	button2.appendChild(icon2);
	tb2.appendChild(button2);

	var view = document.createElement("a");
	view.id = 'viewdetaillink';
	view.title = "<?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_HINT; ?>";
	view.innerHTML = '<span id="viewbuttons"><?php echo $LNG->NETWORKMAPS_SOCIAL_CONNECTION_LINK; ?></span>';
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
	tb2.appendChild(view);

	return tb2;
}

/**
 * Create the graph toolbar for embedded network graphs
 */
function createEmbedNetworkGraphToolbar(forcedirectedGraph,contentarea) {
	var tb2 = document.createElement("div");
	tb2.id = 'graphmaintoolbar';
	tb2.className = 'toolbarrow';
	tb2.style = 'padding-top:5px;display:block;';

	/*
	var button = document.createElement("button");
	button.id = 'homebutton';
	button.style = 'float:left;margin-left:8px;';
	button.title = '<?php echo $LNG->BUILDER_GOTO_HOME_SITE_HINT; ?>';
	var icon = document.createElement("img");
	icon.id = 'homeicon';
	icon.alt = '<?php echo $CFG->SITE_TITLE; ?>';
	icon.src = "<?php echo $HUB_FLM->getImagePath('builder-logo.png'); ?>";
	icon.border = '0';
	button.appendChild(icon);
	tb2.appendChild(button);

	var handler = function() {
		//go to debate hub
	};
	button.addEventListener("click", handler);
	tb2.appendChild(button);
	*/

	var zoomOut = document.createElement("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_IN_HINT;?>'});
	var zoomOuticon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('magminus.png'); ?>", 'border':'0'});
	zoomOut.appendChild(zoomOuticon);
	var zoomOuthandler = function() {
		zoomFD(forcedirectedGraph, 5.0);
	};
	zoomOut.addEventListener("click", zoomOuthandler);
	tb2.appendChild(zoomOut);

	var zoomIn = document.createElement("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_OUT_HINT;?>'});
	var zoomInicon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('magplus.png'); ?>", 'border':'0'});
	zoomIn.appendChild(zoomInicon);
	var zoomInhandler = function() {
		zoomFD(forcedirectedGraph, -5.0);
	};
	zoomIn.addEventListener("click", zoomInhandler);
	tb2.appendChild(zoomIn);

	var zoom1to1 = document.createElement("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_ONE_TO_ONE_HINT;?>'});
	var zoom1to1icon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('zoomfull.png'); ?>", 'border':'0'});
	zoom1to1.appendChild(zoom1to1icon);
	var zoom1to1handler = function() {
		zoomFDFull(forcedirectedGraph);
	};
	zoom1to1.addEventListener("click", zoom1to1handler);
	tb2.appendChild(zoom1to1);

	var zoomFit = document.createElement("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_ZOOM_FIT_HINT;?>'});
	var zoomFiticon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('zoomfit.png'); ?>", 'border':'0'});
	zoomFit.appendChild(zoomFiticon);
	var zoomFithandler = function() {
		zoomFDFit(forcedirectedGraph);
	};
	zoomFit.addEventListener("click", zoomFithandler);
	tb2.appendChild(zoomFit);

	var printButton = document.createElement("button", {'style':'float:left;margin-left: 10px;', 'title':'<?php echo $LNG->GRAPH_PRINT_HINT;?>'});
	var printButtonicon = document.createElement("img", {'src':"<?php echo $HUB_FLM->getImagePath('printer.png'); ?>", 'border':'0'});
	printButton.appendChild(printButtonicon);
	var printButtonhandler = function() {
		printCanvas(forcedirectedGraph);
	};
	printButton.addEventListener("click", printButtonhandler);	
	tb2.appendChild(printButton);

	var key = document.createElement("div", {'id':'key', 'style':'float:left;margin-left:25px;margin-top:10px;'});
	var text = "";
	text+= '<div><span style="background:'+issuebackpale+'; color: black; font-weight:bold"><?php echo $LNG->ISSUE_NAME; ?></span></div>';
	text += '<div><span style="background: '+solutionbackpale+'; color: black; font-weight:bold"><?php echo $LNG->SOLUTION_NAME; ?></span></div>';
	text += '<div><span style="background: '+probackpale+'; color: black; font-weight:bold"><?php echo $LNG->PRO_NAME; ?></span></div>';
	text += '<div><span style="background: '+conbackpale+'; color: black; font-weight:bold"><?php echo $LNG->CON_NAME; ?></span></div>';

	key.innerHTML = text;

	tb2.appendChild(key);

	var count = document.createElement("div", {'id':'graphConnectionCount','style':'float:left;margin-left-25px;margin-top:10px;'});
	tb2.appendChild(count);

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
