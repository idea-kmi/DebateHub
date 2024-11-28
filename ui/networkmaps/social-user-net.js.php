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

function loadSocialNet() {

	document.getElementById("tab-content-social").innerHTML = "";

	/**** CHECK GRAPH SUPPORTED ****/
	if (!isCanvasSupported()) {
		document.getElementById("tab-content-social").innerHTML += '<div style="float:left;font-weight:12pt;padding:10px;"><?php echo $LNG->GRAPH_NOT_SUPPORTED; ?></div>';
		return;
	}

	/**** SETUP THE GRAPH ****/

	var graphDiv = document.createElement('div');
	graphDiv.id = 'graphUserDiv';
	graphDiv.style = 'clear:both;float:left';
	var width = 4000;
	var height = 4000;

	var messagearea = document.createElement("div");
	messagearea.id = 'netusermessage';
	messagearea.className = 'toolbitem';
	messagearea.style = 'float:left;clear:both;font-weight:bold';

	graphDiv.style.width = width+"px";
	graphDiv.style.height = height+"px";

	var outerDiv = document.createElement('div');
	outerDiv.id = 'graphUserDiv-outer';
	outerDiv.style = 'border:1px solid gray;margin-left:5px;margin-bottom:5px;overflow:hidden';
	outerDiv.appendChild(messagearea);
	outerDiv.appendChild(graphDiv);
	document.getElementById("tab-content-social").appendChild(outerDiv);

	forcedirectedGraph = createNewForceDirectedGraphSocial('graphUserDiv', USER_ARGS['userid']);

	// THE KEY
	var keybar = createSocialNetworkGraphKey();
	// THE TOOLBAR
	var toolbar = createSocialGraphToolbar(forcedirectedGraph, "tab-content-social");

	document.getElementById("tab-content-social").prepend(toolbar);
	document.getElementById("tab-content-social").prepend(keybar);

	//event to resize
	window.addEventListener("resize", function() {
		resizeFDGraph(forcedirectedGraph, "tab-content-social", false);
	});

 	var size = calulateInitialGraphViewport("tab-content-social");
	outerDiv.style.width = size.width+"px";
	outerDiv.style.height = size.height+"px";

	loadSocialData(forcedirectedGraph, toolbar, messagearea);
}

async function loadSocialData(forcedirectedGraph, toolbar, messagearea) {

	messagearea.innerHTML = "";
	messagearea.appendChild(getLoading("<?php echo $LNG->NETWORKMAPS_SOCIAL_LOADING_MESSAGE; ?>"));

	var nodetypes = "";

	var count = BASE_TYPES.length;
	for(var i=0; i < count; i++){
		if (i == 0) {
			nodetypes += BASE_TYPES[i];
		} else {
			nodetypes += ","+BASE_TYPES[i];
		}
	}
	count = EVIDENCE_TYPES.length;
	for (var i=0; i < count; i++) {
		nodetypes += ","+EVIDENCE_TYPES[i];
	}

	nodetypes += ",Pro,Con";

	var args = { ...NODE_ARGS };

	args["scope"] = 'all';
	args["start"] = 0;
    args['max'] = "-1";
    args['orderby'] = 'date'; // so you do not get vote - irrelevant anyway for applet
    args['sort'] = 'DESC';
    args['filternodetypes'] = nodetypes;
    args['linklabels'] = "";
    args['style'] = "short";

	//request to get the current connections
	var reqUrl = SERVICE_ROOT + "&method=getconnectionsbysocial&"+Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'POST');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		var conns = json.connectionset[0].connections;
		//document.getElementById('graphConnectionCount').innerHTML = "";
		//document.getElementById('graphConnectionCount').innerHTML += '<span style="font-size:10pt;color:black;float:left;margin-left:20px"><?php echo $LNG->GRAPH_CONNECTION_COUNT_LABEL; ?> '+conns.length+'</span>';

		//alert("connection count = "+conns.length);
		let concount = 0;
		console.log("COUNT", conns.length);
		if (conns.length > 0) {
			var connectionadded = false;
			for(var i=0; i< conns.length; i++){
				var c = conns[i].connection;
				var fN = c.from[0].cnode;
				var tN = c.to[0].cnode;
				console.log("INNER", i);
				if (addConnectionToFDGraphSocial(c, forcedirectedGraph)) {
					concount++;
				}
			}
		}

		let socialcount = 0;
		for(var i in forcedirectedGraph.graph.nodes) {
			socialcount++;
		}

		if (concount > 0 && socialcount > 0) {
			computeMostConnectedNode(forcedirectedGraph);
			layoutAndAnimateSocial(forcedirectedGraph, messagearea);
			toolbar.style.display = 'block';
		} else {
			messagearea.innerHTML="<?php echo $LNG->NETWORKMAPS_NO_RESULTS_MESSAGE; ?>";
			toolbar.style.display = 'none';
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

loadSocialNet();