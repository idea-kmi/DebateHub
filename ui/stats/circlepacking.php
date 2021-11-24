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
 /** Author: Michelle Bachler, KMi, The Open University **/

include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/analyticservices.php"));

$issueNodes = getNodesByGlobal(0,-1,'date','DESC', 'Issue', 'mini');
$nodes = $issueNodes->nodes;

$count = 0;
if (is_countable($nodes)) {
	$count = count($nodes);
}

$json =  '{';
$json .=  '"name": "System",';
$json .=  '"nodetype": "Group",';
$json .=  '"nodetypename": "Group"';

if ($count > 0) {
	$json .=  ',"children": [';

	for ($i=0; $i<$count; $i++) {

		$node = $nodes[$i];

		$json .=  '{';
		$json .=  '"name": "'.parseToJSON($node->name).'",';
		$json .=  '"nodetype": "'.parseToJSON($node->role->name).'",';
		$json .=  '"nodetypename": "'.getNodeTypeText(parseToJSON($node->role->name), false).'"';

		$logictype = 'or';
		$scope = 'all';
		$labelmatch='false';
		$depth=1;
		$uniquepath ='true';
		$status=0;
		$nodetypes = array('Solution');
		$linklabels = array('');
		$linkgroups = array('');
		$directions = array('incoming');
		$nodeids = array('');
		$style='long';

		// This only works because nodes are not transcluded in Debate Hub
		$ideaConnections =  getConnectionsByPathByDepth($logictype, $scope, $labelmatch, $node->nodeid, $depth, $linklabels, $linkgroups, $directions, $nodetypes, $nodeids, $uniquepath, $style, $status);

		//$debateConnections = getDebate($node->nodeid);
		$cons = $ideaConnections->connections;
		$countcons = 0;
		if (is_countable($cons)) {
			$countcons = count($cons);
		}

		if ($countcons > 0) {
			$json .=  ',"children": [';


			for ($j=0; $j<$countcons; $j++) {
				$con = $cons[$j];
				$fromNode = $con->from;

				$json .=  "{";
				$json .=  '"name": "'.parseToJSON($fromNode->name).'",';
				$json .=  '"nodetype": "'.parseToJSON($fromNode->role->name).'",';
				$json .=  '"nodetypename": "'.getNodeTypeText(parseToJSON($fromNode->role->name), false).'"';

				$logictype = 'or';
				$scope = 'all';
				$labelmatch='false';
				$depth=1;
				$uniquepath ='true';
				$status=0;
				$nodetypes = array('Pro,Con');
				$linklabels = array('supports,challenges');
				$linkgroups = array('');
				$directions = array('incoming');
				$nodeids = array('');

				// This only works because nodes are not transcluded in Debate Hub
				$argumentConnections =  getConnectionsByPathByDepth($logictype, $scope, $labelmatch, $fromNode->nodeid, $depth, $linklabels, $linkgroups, $directions, $nodetypes, $nodeids, $uniquepath, $style, $status);
				$args = $argumentConnections->connections;
				$countargs = 0;
				if (is_countable($args)) {
					$countargs = count($args);
				}

				if ($countargs > 0) {
					$json .=  ',"children": [';

					for ($k=0; $k<$countargs; $k++) {
						$conarg = $args[$k];
						$fromNodeArg = $conarg->from;

						$json .=  "{";
						$json .=  '"name": "'.parseToJSON($fromNodeArg->name).'",';
						$size=$fromNodeArg->positivevotes;
						$json .=  '"size": "50",';
						$json .=  '"nodetype": "'.parseToJSON($fromNodeArg->role->name).'",';
						$json .=  '"nodetypename": "'.getNodeTypeText(parseToJSON($fromNodeArg->role->name), false).'"';
						$json .=  '}';
						if ($k<$countargs-1) {
							$json .=  ',';
						}
					}

					$json .=  ']';
				} else {
					$json .=  ',"size": "50"';
				}

				$json .=  '}';
				if ($j<$countcons-1) {
					$json .=  ',';
				}
			}

			$json .=  ']';
		}  else {
			$json .=  ',"size": "50"';
		}

		$json .=  "}";
		if ($i<$count-1) {
			$json .=  ',';
		}
	}

	$json .=  "]";
} else {
	$json .=  ',"size": "50"';
}

$json .=  "}";

//error_log(print_r($json,true));

include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));
?>
<script type='text/javascript'>
var NODE_ARGS = new Array();

Event.observe(window, 'load', function() {
	NODE_ARGS['jsondata'] = <?php echo $json; ?>;

	addScriptDynamically('<?php echo $HUB_FLM->getCodeWebPath("ui/networkmaps/stats-circlepacking.js.php"); ?>', 'stats-circlepacking-script');
});
</script>

<div style="float:left;margin:5px;margin-left:10px;">
	<h1 style="margin:0px;margin-bottom:5px;"><?php echo $dashboarddata[$pageindex][0]; ?>
		<span><img style="padding-left:10px;vertical-align:middle;" title="<?php echo $LNG->STATS_DASHBOARD_HELP_HINT; ?>" onclick="if($('vishelp').style.display == 'none') { this.src='<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>'; $('vishelp').style.display='block'; } else {this.src='<?php echo $HUB_FLM->getImagePath('rightarrowbig.gif'); ?>'; $('vishelp').style.display='none'; }" src="<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>"/></span>
	</h1>
	<div class="boxshadowsquare" id="vishelp" style="font-size:12pt;"><?php echo $dashboarddata[$pageindex][5]; ?></div>

	<div id="circlepacking-div" style="float:left;width:100%;height:100%;"></div>
</div>

<?php
include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>