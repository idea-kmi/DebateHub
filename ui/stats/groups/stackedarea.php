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
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

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

for ($i=0; $i<$count; $i++) {
	$node = $nodes[$i];
	$datekey = date('d / m / y', $node->creationdate);
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

//accumulate date data
/*$keys = array_keys($dateArray);
$count = 0;
if (is_countable($keys)) {
	$count = count($keys);
}
for($i=1; $i<$count; $i++) {
	$prev = $dateArray[$keys[$i-1]];
	$current = $dateArray[$keys[$i]];
	foreach ($current as $key => $value) {
		$current[$key] = $value+$prev[$key];
	}
	$dateArray[$keys[$i]] = $current;
}
*/

// Turn data into json
$count = 0;
if (is_countable($dateArray)) {
	$count = count($dateArray);
}
$json =  "";
if ($count > 0) {
	$json .=  "{";

	// Add category index list
	$json .=  "'label' : [";
	$countj = 0;
	if (is_countable($typeArray)) {
		$countj = count($typeArray);
	}
	for($j=0; $j<$countj; $j++) {
		$next = $typeArray[$j];
		$json .=  "'".$next."'";
		if ($j < $countj-1) {
			$json .=  ",";
		}
	}
	$json .=  "],";

	// add colours
	$json .=  "'color' : [";
	$countj = 0;
	if (is_countable($coloursArray)) {
		$countj = count($coloursArray);
	}
	for($j=0; $j<$countj; $j++) {
		$next = $coloursArray[$j];
		$json .=  "'".$next."'";
		if ($j < $countj-1) {
			$json .=  ",";
		}
	}
	$json .=  "],";

	// Add values
	$json .=  "'values': [";
	$i=0;
	foreach ($dateArray as $key => $innerdata) {
		$json .=  "{";
		$json .= "'label': '".$key."',";
		$json .= "'values': [";
		$k=0;
		$countk = 0;
		if (is_countable($innerdata)) {
			$countk = count($innerdata);
		}
		foreach ($innerdata as $type => $typecount) {
			$json .= $typecount;
			if ($k < $countk-1) {
				$json .= ",";
			}
			$k++;
		}
		$json .= "]";
		$json .=  "}";

		if ($i < $count-1) {
			$json .= ",";
		}
		$i++;
	}

	$json .= "]}";
}

include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));

?>
<script type='text/javascript'>
var NODE_ARGS = new Array();

Event.observe(window, 'load', function() {
	NODE_ARGS['jsondata'] = <?php echo $json; ?>;
	NODE_ARGS['groupid'] = '<?php echo $groupid; ?>';

	addScriptDynamically('<?php echo $HUB_FLM->getCodeWebPath("ui/networkmaps/stats-stackedarea.js.php"); ?>', 'stats-groups-stackedarea-script');
});
</script>

<div style="float:left;margin:5px;margin-left:10px;">
	<h1 style="margin:0px;margin-bottom:5px;"><?php echo $dashboarddata[$pageindex][0]; ?>
		<span><img style="padding-left:10px;vertical-align:middle;" title="<?php echo $LNG->STATS_DASHBOARD_HELP_HINT; ?>" onclick="if($('vishelp').style.display == 'none') { this.src='<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>'; $('vishelp').style.display='block'; } else {this.src='<?php echo $HUB_FLM->getImagePath('rightarrowbig.gif'); ?>'; $('vishelp').style.display='none'; }" src="<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>"/></span>
	</h1>
	<div class="boxshadowsquare" id="vishelp" style="font-size:12pt;"><?php echo $dashboarddata[$pageindex][5]; ?></div>

	<div id="stackedarea-div" style="clear:both;float:left;width:100%;height:100%;"></div>
</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>