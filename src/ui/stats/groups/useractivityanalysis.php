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

//$url = required_param('url', PARAM_URL);

$groupid = required_param("groupid",PARAM_ALPHANUMEXT);

$nodeSet = getNodesByGroup($groupid,0,-1,'date','ASC', '', 'Issue,Solution,Pro,Con', 'mini');
$nodes = $nodeSet->nodes;
$count = count($nodes);

$data = array();
$usersCheck = array();
$users = array();

$countUsers = 1;

for ($i=0; $i<$count; $i++) {
	$node = $nodes[$i];
	$activitieSet = getAllNodeActivity($node->nodeid, 0, 0, -1);
	$activities = $activitieSet->activities;
	foreach($activities as $activity) {
		if (isset($activity)) {
			$date = $activity->modificationdate;
			$type = $activity->type;
			$changetype = $activity->changetype;
			if (($type == "Node" && $changetype == 'add') || $type == "Vote") {
				$role = $node->role->name;
				if ($type == "Vote") {
					$role = $LNG->STATS_ACTIVITY_VOTE;
					//if ($changetype == "Y") {
					//	$role = $LNG->STATS_ACTIVITY_VOTED_FOR;
					//} else {
					//	$role = $LNG->STATS_ACTIVITY_VOTED_AGAINST;
					//}
				}

				$userid = $node->users[0]->userid;
				if (!in_array($userid, $usersCheck)) {
					$users[$userid] = $LNG->STATS_ACTIVITY_USER_ANONYMOUS.$countUsers;
					array_push($usersCheck, $userid);

					$userid = $users[$userid];
					$username = $node->users[0]->name;
					$countUsers++;
				} else {
					$userid = $users[$userid];
					$username = $node->users[0]->name;
				}

				$nexttopic = array(
					"date" => $date,
					"userid" => $userid,
					"username" => $username,
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
		displayUserActivityCrossFilterD3Vis(data, 980);
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

<div style="clear:both;float:left;padding:5px;margin-left:10px;height:100%;width:100%;">
	<div id="messagearea"></div>

	<div id="keyarea" style="width:100%;height:30px;"></div>

	<div>
  	  	<div style="float:left;clear:both;">
	  	  	<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_USERS_TITLE; ?></div>
		  	<div style="clear:both;height:330px;width:940px;" id="user-chart"></div>
		</div>
	  	<div style="float:left;clear:both;">
			<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_ACTION_TITLE; ?></div>
			<div style="clear:both;height:220px;width:350px;" id="nodetype-chart"></div>
		</div>
	</div>

	<div style="clear:both;float:left;margin-top:20px;">
		<div id="data-count">
			<span class="filter-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART1; ?> <span class="total-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART2; ?> | <a
				href="javascript:dc.filterAll(); dc.renderAll();"><?php echo $LNG->STATS_ACTIVITY_RESET_ALL_BUTTON; ?></a>
		</div>
		<table id="data-table" class="table table-hover dc-data-table" style="float:left;clear:both;width:940px">
			<thead>
			<tr class="header">
				<th width="20%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_DATE; ?></th>
				<th width="15%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_ACTION; ?></th>
				<th width="50%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_TITLE; ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>


<!-- div style="clear:both;float:left;">
  <div style="clear:both;float:left;height:350px;width:650px;" id="user-chart">
    <div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_USERS_TITLE; ?></div>
  </div>
  <div style="float:left;height:300px;margin-left:20px;width:250px;" id="nodetype-chart">
  	<div class="title"><?php echo $LNG->STATS_ACTIVITY_FILTER_ACTION_TITLE; ?></div>
  </div>
</div>

<div style="clar:both;float:left;margin-top:20px;">
	<div id="data-count">
		<span class="filter-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART1; ?> <span class="total-count"></span> <?php echo $LNG->STATS_ACTIVITY_SELECTED_COUNT_MESSAGE_PART2; ?> | <a
			href="javascript:dc.filterAll(); dc.renderAll();"><?php echo $LNG->STATS_ACTIVITY_RESET_ALL_BUTTON; ?></a>
	</div>
	<table id="data-table" class="table table-hover dc-data-table" style="float:left;width:980px">
		<thead>
		<tr class="header">
			<th width="20%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_DATE; ?></th>
			<th width="15%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_ACTION; ?></th>
			<th width="50%"><?php echo $LNG->STATS_ACTIVITY_COLUMN_TITLE; ?></th>
		</tr>
		</thead>
	</table>
</div -->

<?php
include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>