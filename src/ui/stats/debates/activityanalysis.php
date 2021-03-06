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

$nodeid = required_param("nodeid",PARAM_ALPHANUMEXT);
$node = getNode($nodeid);

$nodes = array();
array_push($nodes, $node);

$checkNodes = array();
$conSet = getDebate($nodeid, 'long');
if (!$conSet instanceof Error) {
	$countj = count($conSet->connections);
	for ($j=0; $j<$countj;$j++) {
		$con = $conSet->connections[$j];
		$from = $con->from;
		if (in_array($from->nodeid, $checkNodes) === FALSE) {
			$checkNodes[$from->nodeid] = $from->nodeid;
			array_push($nodes, $from);
		}
	}
}

$count = count($nodes);
$data = array();
for ($i=0; $i<$count; $i++) {
	$node = $nodes[$i];
	$activitieSet = getAllNodeActivity($node->nodeid, 0, 0, -1);
	$activities = $activitieSet->activities;
	foreach($activities as $activity) {
		if (isset($activity)) {
			$date = $activity->modificationdate;
			$type = $activity->type;
			$changetype = $activity->changetype;
			if ($type == "Node" || $type == "View") {
				$activitytype = $type;
				if ($type == "Node") {
					$activitytype = $changetype;
				}
				if (!isset($activity->userid) || $activity->userid == "") {
					continue;
				}

				$role = $node->role->name;

				$nexttopic = array(
					"date" => $date,
					"type" => $activitytype,
					"nodeid" => $node->nodeid,
					"title" => $node->name,
					"nodetype" => $role,
				);
				array_push($data, (object)$nexttopic);
			}
		}
	}
}

// sort by date
function datesort($datekeyA, $datekeyB) {
	$a = $datekeyA->date;
	$b = $datekeyB->date;
	if ($a == $b) $r = 0;
	else $r = ($a > $b) ? 1: -1;
    return $r;
}
//uksort($data, "datesort");


include_once($HUB_FLM->getCodeDirPath("ui/headerstats.php"));
?>
<script type='text/javascript'>
var NODE_ARGS = new Array();

Event.observe(window, 'load', function() {
	NODE_ARGS['data'] = <?php echo json_encode($data); ?>;

	$('messagearea').update(getLoadingLine("<?php echo $LNG->LOADING_DATA; ?>"));

	var data = NODE_ARGS['data'];
 	if (data != "") {
		displayActivityCrossFilterD3Vis(data, 980);
	} else {
		$('messagearea').innerHTML="<?php echo $LNG->NETWORKMAPS_NO_RESULTS_MESSAGE; ?>";
	}
});
</script>

<div style="float:left;margin:5px;margin-left:10px;">
	<h1 style="margin:0px;margin-bottom:5px;"><?php echo $dashboarddata[$pageindex][0]; ?>
		<span><img style="padding-left:10px;vertical-align:middle;" title="<?php echo $LNG->STATS_DASHBOARD_HELP_HINT; ?>" onclick="if($('vishelp').style.display == 'none') { this.src='<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>'; $('vishelp').style.display='block'; } else {this.src='<?php echo $HUB_FLM->getImagePath('rightarrowbig.gif'); ?>'; $('vishelp').style.display='none'; }" src="<?php echo $HUB_FLM->getImagePath('uparrowbig.gif'); ?>"/></span>
	</h1>
	<div class="boxshadowsquare" id="vishelp" style="font-size:12pt;"><?php echo $dashboarddata[$pageindex][5]; ?></div>
</div>

<div style="clear:both;float:left;padding:5px;">
	<div id="messagearea"></div>

	<div style="clear:both;float:left;">
		<div style="clear:both;float:left;height:250px;" id="date-chart">
			<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_DATE_TITLE; ?></div>
		</div>
		<!-- div style="clear:both;float:left;width:100%;height:60px;" id="date-selector-chart"></div -->

		<div style="clear:both;float:left;margin-top:20px;width:100%;">
			<!-- div style="clear:both;float:left;height:200px;" id="month-chart">
				<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_MONTH_TITLE; ?></div>
			</div -->
			<div style="float:left;height:200px;width:200px;" id="days-of-week-chart">
				<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_DAYS_TITLE; ?></div>
			</div>
			<div style="float:left;height:200px;width:200px;margin-left:20px;" id="nodetype-chart">
				<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_ITEM_TYPES_TITLE; ?></div>
			</div>
			<div style="float:left;height:200px;width:200px;margin-left:20px;" id="type-chart">
				<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_TYPES_TITLE; ?></div>
			</div>
			<!-- div style="float:left;height:200px;margin-left:20px;" id="nodetype-nut-chart">
				<div class="title">Item Types (doughnut)</div>
			</div -->
			<!--div style="clear:both;float:left;height:200px;margin-left:20px;margin-top:20px;" id="nodetype-pie-chart">
				<div class="title">Item Types (pie)</div>
			</div -->
		</div>
	</div>

	<div style="clear:both;float:left;margin-top:30px;">
		<div id="data-count">
			<span class="filter-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART1; ?> <span class="total-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART2; ?> | <a
				href="javascript:dc.filterAll(); dc.renderAll();"><?php echo $LNG->STATS_ACTIVITY_RESET_ALL_BUTTON; ?></a>
		</div>
		<table id="data-table" class="table table-hover dc-data-table" style="clear:both;float:left;width:980px">
			<thead>
			<tr class="header">
				<th width="20%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_DATE; ?></th>
				<th width="50%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_TITLE; ?></th>
				<th width="15%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_ITEM_TYPE; ?></th>
				<th width="15%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_TYPE; ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>

<?php
include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>