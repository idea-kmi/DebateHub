<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015-2023 The Open University UK                              *
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
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
checkDashboardAccess('GROUP');

$groupid = required_param("groupid",PARAM_ALPHANUMEXT);

$nodeCheck = array();
$totalnodes = 0;

$nodeSet = getNodesByGroup($groupid,0,-1,'date','ASC', '', 'Issue,Solution,Pro,Con', 'mini');
$nodes = $nodeSet->nodes;
$count = 0;
if (is_countable($nodes)) {
	$count = count($nodes);
}

$typeArray = array($LNG->ISSUE_NAME,$LNG->SOLUTION_NAME,$LNG->PRO_NAME, $LNG->CON_NAME);
$coloursArray = array("#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A");
$dateArray = array();

//error_log($count);

for ($i=0; $i<$count; $i++) {
	$node = $nodes[$i];
	$datekey = date('d/m/y', $node->creationdate);
	//$datekey = $node->creationdate*1000;
	$nodetype = getNodeTypeText($node->role->name, false);
	if (in_array($nodetype, $typeArray)) {
		if (!array_key_exists($datekey, $dateArray)) {
			$typearray = array();
			$typearray[$LNG->ISSUE_NAME] = 0;
			$typearray[$LNG->SOLUTION_NAME] = 0;
			$typearray[$LNG->PRO_NAME] = 0;
			$typearray[$LNG->CON_NAME] = 0;
			$typearray[$nodetype] = 1;
			$dateArray[$datekey] = $typearray;
		} else {
			$typearray = $dateArray[$datekey];
			$typearray[$nodetype] = $typearray[$nodetype]+1;
			$dateArray[$datekey] = $typearray;
		}
	}
}

$data = array();
$finalArray = array();

foreach ($typeArray as $next) {
	foreach ($dateArray as $key => $innerdata) {
		foreach ($innerdata as $type => $typecount) {
			if ($type == $next) {
				if (!array_key_exists($next, $finalArray)) {
					$nextarray = array();
					array_push($nextarray, array($key, $typecount));
					$finalArray[$next] = $nextarray;
				} else {
					$nextarray = $finalArray[$next];
					array_push($nextarray, array($key, $typecount));
					$finalArray[$next] = $nextarray;
				}
			}
		}
	}
}

foreach ($finalArray as $key => $values) {
	$next = new stdClass();
	$next->key = $key;
	$next->values = $values;
	array_push($data, $next);
}


include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));

?>

<script type='text/javascript'>
	var NODE_ARGS = new Array();

	Event.observe(window, 'load', function() {
		NODE_ARGS['data'] = <?php echo json_encode($data, JSON_INVALID_UTF8_IGNORE); ?>;
		NODE_ARGS['groupid'] = <?php echo $groupid; ?>;

		addScriptDynamically('<?php echo $HUB_FLM->getCodeWebPath("ui/networkmaps/stats-streamgraph.js.php"); ?>', 'stats-groups-streamgraph-script');
	});
</script>

<div class="d-flex flex-column">
	<h1><?php echo $dashboarddata[$pageindex][0]; ?></h1>
	<p><?php echo $dashboarddata[$pageindex][5]; ?></p>

	<div id="streamgraph-div" class="statsgraph"></div>
</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>
