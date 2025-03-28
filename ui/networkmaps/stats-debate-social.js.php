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

function loadSocialDebateNet() {

	$("social-debate-div").innerHTML = "";

	/**** CHECK GRAPH SUPPORTED ****/
	if (!isCanvasSupported()) {
		$("social-debate-div").insert('<div style="float:left;font-weight:12pt;padding:10px;"><?php echo $LNG->GRAPH_NOT_SUPPORTED; ?></div>');
		return;
	}
1
	/**** SETUP THE GRAPH ****/

	var graphDiv = new Element('div', {'id':'graphUserDiv', 'style': 'clear:both;float:left'});
	var width = 4000;
	var height = 4000;

	var messagearea = new Element("div", {'id':'netusermessage','class':'toolbitem','style':'float:left;clear:both;font-weight:bold'});

	graphDiv.style.width = width+"px";
	graphDiv.style.height = height+"px";

	var outerDiv = new Element('div', {'id':'graphUserDiv-outer', 'style': 'border:1px solid gray;clear:both;float:left;margin-left:5px;margin-bottom:5px;overflow:hidden'});
	outerDiv.insert(messagearea);
	outerDiv.insert(graphDiv);
	$("social-debate-div").insert(outerDiv);

	forcedirectedGraph = createNewForceDirectedGraphSocial('graphUserDiv', "");

	// THE KEY
	var keybar = createSocialNetworkGraphKey();
	// THE TOOLBAR
	var toolbar = createSocialGraphToolbar(forcedirectedGraph, "social-debate-div");

	$("social-debate-div").insert({top: toolbar});
	$("social-debate-div").insert({top: keybar});

	//event to resize
	Event.observe(window,"resize",function() {
		resizeFDGraph(forcedirectedGraph, "social-debate-div", false);
	});

 	var size = calulateInitialGraphViewport("social-debate-div");
	outerDiv.style.width = size.width+"px";
	outerDiv.style.height = size.height+"px";

	loadSocialData(forcedirectedGraph, toolbar, messagearea);
}

function loadSocialData(forcedirectedGraph, toolbar, messagearea) {

	messagearea.update(getLoading("<?php echo $LNG->NETWORKMAPS_SOCIAL_LOADING_MESSAGE; ?>"));

	var args = {}; //must be an empty object to send down the url, or all the Array functions get sent too.
	args["nodeid"] = NODE_ARGS['nodeid'];
	args["start"] = 0;
    args['max'] = "-1";
    args['scope'] = "all";

    args['style'] = "mini";
    args['depth'] = 2;

    args['labelmatch'] = 'false';
    args['uniquepath'] = 'true';
    args['logictype'] = 'or';

	var extra = "&nodeids[]=";
	extra += "&nodeids[]=";

	extra += "&nodetypes[]="+encodeURIComponent('Solution');
	extra += "&nodetypes[]="+encodeURIComponent('Pro,Con,Comment');

	extra += "&directions[]=incoming";
	extra += "&directions[]=incoming";

	extra += "&linklabels[]=";
	extra += "&linklabels[]="+encodeURIComponent('supports,challenges');

	extra += "&linkgroups[]=All";
	extra += "&linkgroups[]=";

	var reqUrl = SERVICE_ROOT + "&method=getconnectionsbypathbydepth"+extra+"&" + Object.toQueryString(args);

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
				//$('graphConnectionCount').innerHTML = "";
				//$('graphConnectionCount').insert('<span style="font-size:10pt;color:black;float:left;margin-left:20px"><?php echo $LNG->GRAPH_CONNECTION_COUNT_LABEL; ?> '+conns.length+'</span>');

				let concount = 0;
      			if (conns.length > 0) {
	      			for(var i=0; i< conns.length; i++){
	      				var c = conns[i].connection;
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
					layoutAndAnimateFD(forcedirectedGraph, messagearea);
					toolbar.style.display = 'block';
				} else {
					messagearea.innerHTML="<?php echo $LNG->NETWORKMAPS_NO_RESULTS_MESSAGE; ?>";
					toolbar.style.display = 'none';
				}
      		}
      	});
}

loadSocialDebateNet();