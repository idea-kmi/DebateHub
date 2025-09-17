<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2025 The Open University UK                            *
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

var forcedirectedGraph = null;

function loadExploreDebateNet(){

	$("network-debate-div").innerHTML = "";

	/**** CHECK GRAPH SUPPORTED ****/
	if (!isCanvasSupported()) {
		$("network-debate-div").insert('<div style="float:left;font-weight:12pt;padding:10px;"><?php echo $LNG->GRAPH_NOT_SUPPORTED; ?></div>');
		return;
	}

	/**** SETUP THE GRAPH ****/

	var graphDiv = new Element('div', {'id':'graphIssueDiv', 'style': 'clear:both;float:left'});
	var width = 4000;
	var height = 4000;

	var messagearea = new Element("div", {'id':'netissuemessage','class':'toolbitem','style':'float:left;clear:both;font-weight:bold'});

	graphDiv.style.width = width+"px";
	graphDiv.style.height = height+"px";

	var outerDiv = new Element('div', {'id':'graphIssueDiv-outer', 'style': 'border:1px solid gray;clear:both;float:left;margin-left:5px;margin-bottom:5px;overflow:hidden'});

	outerDiv.insert(messagearea);
	outerDiv.insert(graphDiv);
	$("network-debate-div").insert(outerDiv);

	forcedirectedGraph = createNewForceDirectedGraph('graphIssueDiv', NODE_ARGS['nodeid']);

	// THE KEY
	var keybar = createNetworkGraphKey();
	// THE TOOLBAR
	var toolbar = createGraphToolbar(forcedirectedGraph, "network-debate-div");

	$("network-debate-div").insert({top: toolbar});
	$("network-debate-div").insert({top: keybar});

	//event to resize
	Event.observe(window,"resize",function() {
		resizeFDGraph(forcedirectedGraph, "network-debate-div", false);
	});

 	var size = calulateInitialGraphViewport("network-debate-div");
	outerDiv.style.width = size.width+"px";
	outerDiv.style.height = size.height+"px";

	loadIssueData(forcedirectedGraph, toolbar, messagearea);
}

function loadIssueData(forcedirectedGraph, toolbar, messagearea) {

	messagearea.update(getLoadingLine("<?php echo $LNG->NETWORKMAPS_LOADING_MESSAGE; ?>"));

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.

	args["nodeid"] = NODE_ARGS['nodeid'];
    args['style'] = "mini";

	var reqUrl = SERVICE_ROOT + "&method=getdebate&" + Object.toQueryString(args);

	//alert(reqUrl);

	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){
			var json = null;
			try {
				json = transport.responseText.evalJSON();
			} catch(e) {
				alert(e);
			}
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var conns = json.connectionset[0].connections;
			//alert("conns: "+conns.length);
			let conslenth = 0;
			if (conns.length > 0) {
				for(var i=0; i< conns.length; i++){
					var c = conns[i].connection;
					if (addConnectionToFDGraph(c, forcedirectedGraph.graph)) {
						conslenth++;
					}
				}
			}

			$('graphConnectionCount').innerHTML = "";
			$('graphConnectionCount').insert('<span style="font-size:10pt;color:black;float:left;margin-left:20px"><?php echo $LNG->GRAPH_CONNECTION_COUNT_LABEL; ?> '+conslenth+'</span>');

			if (conns.length > 0) {
				computeMostConnectedNode(forcedirectedGraph);
				layoutAndAnimateFD(forcedirectedGraph, messagearea);
				toolbar.style.display = 'block';
			} else {
				messagearea.innerHTML="<?php echo $LNG->NETWORKMAPS_NO_RESULTS_MESSAGE; ?>";
				toolbar.style.display = 'none';
			}
		}
	});
}

loadExploreDebateNet();