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
    include_once("../../config.php");

    $me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
    if ($HUB_FLM->hasCustomVersion($me)) {
    	$path = $HUB_FLM->getCodeDirPath($me);
    	include_once($path);
		die;
	}

    $nodeid = required_param("nodeid", PARAM_ALPHANUMEXT);
    $node = getNode($nodeid);

    include_once($HUB_FLM->getCodeDirPath("ui/headerdialog.php"));
 ?>

<script type="text/javascript">
//<![CDATA[

	var NODE_ARGS = {};

	var nodeid = '<?php echo $nodeid; ?>';

	/**
	 * load child list on solutionas for built froms.
	 */
	function getNodes(){

		var reqUrl = SERVICE_ROOT + "&method=getconnectionsbynode&style=long&sort=DESC&orderby=date&status=<?php echo $CFG->STATUS_ACTIVE; ?>";
		reqUrl += "&filterlist=<?php echo $CFG->LINK_BUILT_FROM; ?>&filternodetypes=Solution&scope=all&start=0&max=-1&nodeid="+nodeid;

		new Ajax.Request(reqUrl, { method:'post',
			onSuccess: function(transport){
				var json = transport.responseText.evalJSON();
				if(json.error){
					alert(json.error[0].message);
					return;
				}

				var conns = json.connectionset[0].connections;

				$("builtfromnodes").innerHTML = "";

				if (conns.length > 0) {
					var nodes = new Array();
					for(var i=0; i< conns.length; i++){
						var c = conns[i].connection;
						var fN = c.from[0].cnode;
						var tN = c.to[0].cnode;

						if (fN.nodeid == nodeid) {
							var next = c.to[0];
							next.cnode['connection'] = c;
							next.cnode['parentid'] = "";
							nodes.push(next);
						}
					}

					if (nodes.length > 0){
						displayIdeaList($("builtfromnodes"),nodes,parseInt(0),true,'builtfrom'+nodeid, 'retired', <?php echo $CFG->STATUS_RETIRED; ?>);
					} else {
						$("builtfromnodes").update("<?php echo $LNG->WIDGET_NO_RESULTS_FOUND; ?>");
					}
				}
			}
		});
	}

    /**
     *  set which tab to show and load first
     */
    Event.observe(window, 'load', function() {
	    $('dialogheader').insert('<?php echo '<span style="color: black">'.$node->name.'</span><br>'.$LNG->BUILTFROM_DIALOG_TITLE; ?>');
        getNodes();
    });
//]]>
</script>

<br>
<div id="builtfromnodes" name="builtfromnodes" style="margin: 10px; padding-bottom: 10px;display:block">
	<div class="loading">
		<center><img src='<?php echo $HUB_FLM->getImagePath("ajax-loader.gif"); ?>'/>
		<br/>(<?php echo $LNG->LOADING_ITEMS; ?>...)</center>
	</div>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>