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

	/**
	 * Statistics Utility library
	 * Stats functions
	 */

	/*** VOTING STATS ***/

	function getTotalItemVotes() {
		global $DB,$CFG,$HUB_SQL;

		$params = array();

		$totals = array();

		$sql = $HUB_SQL->STATSLIB_TOTAL_ITEM_VOTES_SELECT;
		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($totals, $array);
			}
		}

		return $totals;
	}

	function getTotalConnectionVotes() {
		global $DB,$CFG,$HUB_SQL;

		$params = array();

		$totals = array();
		$sql = $HUB_SQL->STATSLIB_TOTAL_CONNECTION_VOTES_SELECT ;
		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($totals, $array);
			}
		}

		return $totals;
	}

	function getTotalVotes() {
		global $DB,$CFG,$HUB_SQL;

		$params = array();

		$totals = array();
		$sql = $HUB_SQL->STATSLIB_TOTAL_VOTES_SELECT;
		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($totals, $array);
			}
		}

		return $totals;
	}

	/**
	 * Get the Top Voted on Items.
	 * @return a 'NodeSet' with variable where each node has an additional properties:
	 * 'vote' = total votes, 'up'=for node votes, 'down'=against node votes,
	 * 'cup' = for connection votes, 'cdown' = against connection votes
	 */
	function getTotalTopVotes($count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $count;

		$topVotedNodes = array();

		$sql = $HUB_SQL->STATSLIB_TOTAL_TOP_VOTES;

		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		/*$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($topVotedNodes, $array);
			}
		}
		return $topVotedNodes;
		*/

		$ns = new NodeSet();
		return $ns->loadNodesWithExtras($sql,$params,'short');
	}

	/**
	 * Get the Top Voted FOR Items.
	 * @return a 'NodeSet' with variable where each node has an additional properties.
	 * 'vote' = total votes, 'up'=for node votes, 'cup' = for connection votes.
	 */
	function getTopNodeForVotes($count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $count;

		$topVotedForNodes = array();

		$sql = $HUB_SQL->STATSLIB_TOP_NODE_FOR_VOTES;
		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		/*
		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($topVotedForNodes, $array);
			}
		}
		return $topVotedForNodes;
		*/

		$ns = new NodeSet();
		return $ns->loadNodesWithExtras($sql,$params,'short');
	}

	/**
	 * Get the Top Voted AGAINST Items.
	 * @return a 'NodeSet' with variable where each node has an additional properties.
	 * 'vote' = total votes, 'down'=against node votes, 'cdown' = against connection votes.
	 */
	function getTopNodeAgainstVotes($count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $count;

		$topVotedAgainstNodes = array();

		$sql = $HUB_SQL->STATSLIB_TOP_NODE_AGAINST_VOTES;
		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		/*
		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($topVotedAgainstNodes, $array);
			}
		}
		return $topVotedAgainstNodes;
		*/

		$ns = new NodeSet();
		return $ns->loadNodesWithExtras($sql,$params,'short');
	}

	function getTopVoters($count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $count;

		$topVoters = array();

		$sql = $HUB_SQL->STATSLIB_TOP_VOTERS;
		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($topVoters, $array);
			}
		}

		return $topVoters;
	}

	function getTopForVoters($count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $count;

		$topVotersFor = array();

		$sql = $HUB_SQL->STATSLIB_TOP_FOR_VOTERS;
		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($topVotersFor, $array);
			}
		}

		return $topVotersFor;
	}

	function getTopAgainstVoters($count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $count;

		$topVotersAgainst = array();

		$sql = $HUB_SQL->STATSLIB_TOP_FOR_VOTERS;
		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($topVotersAgainst, $array);
			}
		}

		return $topVotersAgainst;
	}

	function getAllVoting(&$direction, $sort, $oldsort) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		if ($direction) {
			if ($oldsort === $sort) {
				if ($direction === 'ASC') {
					$direction = "DESC";
				} else {
					$direction = "ASC";
				}
			} else {
				$direction = "DESC";
			}
		} else {
			$direction = "DESC";
		}

		$allNodeVotes = array();
		$sql = $HUB_SQL->STATSLIB_ALL_VOTING;

		if ($sort != 'Name' && $sort != "NodeType") {
			$sql .= $HUB_SQL->STATSLIB_ALL_VOTING_ORDER_BY.$sort." ".$direction;
		}

		/*$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($allNodeVotes, $array);
			}
		}

		return $allNodeVotes;
		*/

		$ns = new NodeSet();
		$ns->loadNodesWithExtras($sql,$params,'short');

		// These properties had to be taken out of the original sql call
		// as Virtuoso complained about a long data type error
		// Do now data called as separate Nodes and these sorts are done afterwards.
		if ($sort === "Name") {
			if ($direction === "ASC") {
				usort($ns->nodes, 'nameSortASC');
			} else {
				usort($ns->nodes, 'nameSortDESC');
			}
		} else if ($sort === "NodeType") {
			if ($direction === "ASC") {
				usort($ns->nodes, 'roleTextSortASC');
			} else {
				usort($ns->nodes, 'roleTextSortDESC');
			}
		}

		return $ns;
	}

	/*** USER CONTEXT STATS ***/

	function getTotalVotesForUser($userid) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $userid;

		$totals = array();

		$sql = $HUB_SQL->STATSLIB_USER_TOTAL_VOTES;

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($totals, $array);
			}
		}

		return $totals;
	}

	function getAllVotingForUser($userid, $direction, $sort, $oldsort) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $userid;

		$allNodeVotes = array();

		if ($direction) {
			if ($oldsort === $sort) {
				if ($direction === 'ASC') {
					$direction = "DESC";
				} else {
					$direction = "ASC";
				}
			} else {
				$direction = "DESC";
			}
		} else {
			$direction = "DESC";
		}

		$allNodeVotes = array();
		$sql = $HUB_SQL->STATSLIB_USER_ALL_VOTING;

		if ($sort != 'Name' && $sort != "NodeType") {
			$sql .= $HUB_SQL->STATSLIB_ALL_VOTING_ORDER_BY.$sort." ".$direction;
		}

		$ns = new NodeSet();
		$ns->loadNodesWithExtras($sql,$params,'short');

		// These properties had to be taken out of the original sql call
		// as Virtuoso complained about a long data type error
		// Do now data called as separate Nodes and these sorts are done afterwards.
		if ($sort === "Name") {
			if ($direction === "ASC") {
				usort($ns->nodes, 'nameSortASC');
			} else {
				usort($ns->nodes, 'nameSortDESC');
			}
		} else if ($sort === "NodeType") {
			if ($direction === "ASC") {
				usort($ns->nodes, 'roleTextSortASC');
			} else {
				usort($ns->nodes, 'roleTextSortDESC');
			}
		}

		return $ns;
	}

	function getTopTagForUser($userid, $count) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $userid;
		$params[1] = $userid;
		$params[2] = $userid;
		$params[3] = $userid;

		$tags = array();

		$sql = $HUB_SQL->STATSLIB_USER_TOP_TAG;
		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, $count);

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$name = $array['Name'];
				$tags[$name] = $array['UseCount'];
			}
		}

		return $tags;
	}

	function getLinkTypesForUser($userid) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $userid;

		$linkArray = array();

		$sql = $HUB_SQL->STATSLIB_USER_LINK_TYPES;

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$name = $array['Label'];
				$count = $array['num'];
				$linkArray[$name] = $count;
			}
		}

		return $linkArray;
	}

	function getNodeTypesForUser($userid) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $userid;

		$nodeArray = array();

		$sql = $HUB_SQL->STATSLIB_USER_NODE_TYPES;

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$name = $array['Name'];
				$nodeArray[$name] = $array['num'];
			}
		}

		return $nodeArray;
	}

	function getComparedThinkingForUser($userid) {
		global $DB,$CFG, $USER,$HUB_SQL;

		$currentuser = '';
		if (isset($USER->userid)) {
			$currentuser = $USER->userid;
		}

		$params = array();
		$params[0] = $userid;
		$params[1] = 'N';
		$params[2] = $currentuser;
		$params[3] = $currentuser;
		$params[4] = 'N';
		$params[5] = $currentuser;
		$params[6] = $currentuser;
		$params[7] = 'N';
		$params[8] = $currentuser;
		$params[9] = $currentuser;
		$params[10] = $userid;
		$params[11] = 'N';
		$params[12] = $currentuser;
		$params[13] = $currentuser;
		$params[14] = 'N';
		$params[15] = $currentuser;
		$params[16] = $currentuser;
		$params[17] = 'N';
		$params[18] = $currentuser;
		$params[19] = $currentuser;
		$params[20] = $userid;
		$params[21] = $userid;
		$params[22] = $userid;
		$params[23] = $userid;

		$connectionSet = new ConnectionSet();

		$sql = $HUB_SQL->STATSLIB_USER_COMPARED_THINKING;

		$resArray = $DB->select($sql, $params);
		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$id = $array['TripleID'];
				$con = new Connection();
				$con->connid = $id;
				$con = $con->load();
				if (!$con instanceof Hub_Error) {
					$countarray = 0;
					if (is_countable($comparedArray)) {
						$countarray = count($comparedArray);
					}
					$comparedArray[$countarray] = $con;
				}
			}

			$connectionSet->totalno = 0;
			if (is_countable($connectionSet->connections)) {
				$connectionSet->totalno = count($connectionSet->connections);
			}
			$connectionSet->start = 0;
			$connectionSet->count = $connectionSet->totalno;
		}

		return $connectionSet;
	}

	function getInformationBrokeringForUser($userid) {
		global $DB,$CFG, $USER,$HUB_SQL;

		$currentuser = '';
		if (isset($USER->userid)) {
			$currentuser = $USER->userid;
		}

		$params = array();
		$params[0] = $userid;
		$params[1] = 'N';
		$params[2] = $currentuser;
		$params[3] = $currentuser;
		$params[4] = 'N';
		$params[5] = $currentuser;
		$params[6] = $currentuser;
		$params[7] = 'N';
		$params[8] = $currentuser;
		$params[9] = $currentuser;
		$params[10] = $userid;
		$params[11] = 'N';
		$params[12] = $currentuser;
		$params[13] = $currentuser;
		$params[14] = 'N';
		$params[15] = $currentuser;
		$params[16] = $currentuser;
		$params[17] = 'N';
		$params[18] = $currentuser;
		$params[19] = $currentuser;
		$params[20] = $userid;
		$params[21] = $userid;

		$brokerConnectionSet = new ConnectionSet();

		$sql = $HUB_SQL->STATSLIB_USER_INFORMATION_BROKERING;
		$resArray = $DB->select($sql, $params);

		$nodeArray = array();
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$id = $array['TripleID'];
				$con = new Connection();
				$con->connid = $id;
				$con = $con->load();
				if (!$con instanceof Hub_Error) {
					$brokerConnectionSet->add($con);
				}
			}

			$brokerConnectionSet->totalno = 0;
			if (is_countable($brokerConnectionSet->connections)) {
				$brokerConnectionSet->totalno = count($brokerConnectionSet->connections);
			}
			$brokerConnectionSet->start = 0;
			$brokerConnectionSet->count = $brokerConnectionSet->totalno;
		}

		return $brokerConnectionSet;
	}

	/*** GLOBAL STATS ***/

	/**
	 * Get the most used Link Type
	 * return an array with two items; 0=count, 1=Link Type name
	 */
	function getMostUsedLinkType() {
		global $DB,$HUB_SQL,$CFG;

		$linkCount = 0;
		$linkName = "";

		$params = array();

		// MOST USED LinkType
		$sql = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_SELECT;

		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			// Get Node Types
			$nodetypesArray = array();
			$nodetypes = "";
			$count = 0;
			if (is_countable($CFG->BASE_TYPES)) {
				$count = count($CFG->BASE_TYPES);
			}
			for($i=0; $i<$count; $i++){
				$nodetypesArray[count($nodetypesArray)] = $CFG->BASE_TYPES[$i];
				if ($i == 0) {
					$nodetypes .= "?";
				} else {
					$nodetypes .= ",?";
				}
			}
			$count = 0;
			if (is_countable($CFG->EVIDENCE_TYPES)) {
				$count = count($CFG->EVIDENCE_TYPES);
			}
			for ($i=0; $i<$count; $i++) {
				$nodetypesArray[count($nodetypesArray)] = $CFG->EVIDENCE_TYPES[$i];
				$nodetypes .= ",?";
			}
			$nodetypesArray[count($nodetypesArray)] = 'Pro';
			$nodetypes .= ",?";
			$nodetypesArray[count($nodetypesArray)] = 'Con';
			$nodetypes .= ",?";

			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$name = $array['Label'];

				$params = array();

				$qry4 = $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_PART1;
				$params = array_merge($params, $nodetypesArray);
				$qry4 .= $nodetypes;
				$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_PART2;

				$params = array_merge($params, $nodetypesArray);
				$qry4 .= $nodetypes;

				$params[count($params)] = $name;
				$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_PART3;

				$resArray2 = $DB->select($qry4, $params);
				if ($resArray2 !== false) {
					$countk = 0;
					if (is_countable($resArray2)) {
						$countk = count($resArray2);
					}
					for ($k=0; $k<$countk; $k++) {
						$array = $resArray2[$k];
						$counts = $array['num'];
						if ($counts > $linkCount) {
							$linkCount = $counts;
							$linkName = $name;
						}
					}
				}
			}
		}

		return array($linkCount, $linkName);
	}

	/**
	 * Get the most used Node Type
	 * return an array with two items; 0=count, 1=Node Type name
	 */
	function getMostUsedNodeType() {
		global $DB,$HUB_SQL,$CFG;

		$params = array();

		//$nodetypes = getAllNodeTypeNames();
		//$innersql = getSQLForNodeTypeIDsForLabels($params,$nodetypes);

		// Get Node Types
		$nodetypesArray = array();
		$nodetypes = "";
		$count = 0;
		if (is_countable($CFG->BASE_TYPES)) {
			$count = count($CFG->BASE_TYPES);
		}
		for($i=0; $i<$count; $i++){
			$nodetypesArray[count($nodetypesArray)] = $CFG->BASE_TYPES[$i];
			if ($i == 0) {
				$nodetypes .= "?";
			} else {
				$nodetypes .= ",?";
			}
		}
		$count = 0;
		if (is_countable($CFG->EVIDENCE_TYPES)) {
			$count = count($CFG->EVIDENCE_TYPES);
		}
		for ($i=0; $i<$count; $i++) {
			$nodetypesArray[count($nodetypesArray)] = $CFG->EVIDENCE_TYPES[$i];
			$nodetypes .= ",?";
		}
		$nodetypesArray[count($nodetypesArray)] = 'Pro';
		$nodetypes .= ",?";
		$nodetypesArray[count($nodetypesArray)] = 'Con';
		$nodetypes .= ",?";

		$sql = $HUB_SQL->STATSLIB_GLOBAL_NODETYPE_SELECT_PART1;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_NODETYPE_SELECT_PART2;

		$roleCount = 0;
		$roleName = "";

		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			$roleIDs = "";
			$previousName = "";
			for ($i=0; $i<$count; $i++) {

				$array = $resArray[$i];
				$name = $array['Name'];
				$roleid = $array['NodeTypeID'];

				$params = array();

				if ($previousName == "") {
					$previousName = $name;
				}

				if ($previousName != $name) {

					$qry4 = $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART1;
					$params = array_merge($params, $nodetypesArray);
					$qry4 .= $nodetypes;
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART2;
					$params = array_merge($params, $nodetypesArray);
					$qry4 .= $nodetypes;
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART3 = "))) AND (FromContextTypeID IN (";
					$qry4 .= $roleIDs; // Do not need escaping so can go stright in
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART4 = ") or ToContextTypeID IN (";
					$qry4 .= $roleIDs; // Do not need escaping so can go stright in
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART5 = "))";

					$resArray2 = $DB->select($qry4, $params);
					if ($resArray2 !== false) {
						$countk = 0;
						if (is_countable($resArray2)) {
							$countk = count($resArray2);
						}
						for ($k=0; $k<$countk; $k++) {
							$array2 = $resArray2[$k];
							$counts = $array2['num'];
							if ($counts > $roleCount) {
								$roleCount = $counts;
								$roleName = $previousName;
							}
						}
					}

					$roleIDs = "";
				}

				$previousName = $name;

				if ($roleIDs == "") {
					$roleIDs .= "'".$roleid."'";
				} else {
					$roleIDs .= ", '".$roleid."'";
				}
			}
		}

		return array($roleCount, $roleName);
	}

	/**
	 * Get the most Connected Node
	 * @param $nodetypes the Node Type Names to get the information for
	 * return an array with three items; 1=Name, 2=Node Type 3=count.
	 */
	function getMostConnectedNode() {
		global $DB,$USER,$HUB_SQL,$CFG;

		$currentuser = '';
		if (isset($USER->userid)) {
			$currentuser = $USER->userid;
		}

		$params = array();

		// Get Node Types
		$nodetypesArray = array();
		$nodetypes = "";
		$count = 0;
		if (is_countable($CFG->BASE_TYPES)) {
			$count = count($CFG->BASE_TYPES);
		}
		for($i=0; $i<$count; $i++){
			$nodetypesArray[count($nodetypesArray)] = $CFG->BASE_TYPES[$i];
			if ($i == 0) {
				$nodetypes .= "?";
			} else {
				$nodetypes .= ",?";
			}
		}
		$count = 0;
		if (is_countable($CFG->EVIDENCE_TYPES)) {
			$count = count($CFG->EVIDENCE_TYPES);
		}
		for ($i=0; $i<$count; $i++) {
			$nodetypesArray[count($nodetypesArray)] = $CFG->EVIDENCE_TYPES[$i];
			$nodetypes .= ",?";
		}
		$nodetypesArray[count($nodetypesArray)] = 'Pro';
		$nodetypes .= ",?";
		$nodetypesArray[count($nodetypesArray)] = 'Con';
		$nodetypes .= ",?";

		$mostconidea = "";
		$mostcontype = "";
		$mostconideaCount = 0;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART1;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART1b;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART2;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;

		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART3;

		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART4;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART4b;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART5;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;

		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART6;

		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, 1);

		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$id = $array['ID'];
				$node = new CNode();
				$node->nodeid = $id;
				$node = $node->load();
				if (!$node instanceof Hub_Error) {
					$countr = $array['num'];
					$mostconidea = $node->name;
					$mostcontype = $node->role->name;
					$mostconideaCount = $countr;
				}
			}
		}
		return array($mostconidea, $mostcontype, $mostconideaCount);
	}

	/**
	 * Get the link tpye usage for each user
	 * return an array with two items; 1=Array of arrays of link type use counts, 2=An array of users for thos counts.
	 * Both return arrays are associative mapped to the userids.
	 */
	function getLinkTypeUsage() {
		global $DB,$HUB_SQL;

		$params = array();

		$qryStart = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART1;
		$qryNames = "";
		$qryMiddle = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART2;
		$qryIDs = "";
		$qryEnd = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART3;
		$qry = "";

		$userSet = getActiveConnectionUsers(0, 10);
		if ($userSet->count > 0) {
			for ($i = 0; $i < $userSet->count; $i++) {
				$user = $userSet->users[$i];
				if ($user->userid && $user->userid != "") {
					$name = "_".$user->userid; // because can't use a number to start an alias name

					if ($i==0) {
						$qryNames .= $name;
						$qryIDs .= "'".$user->userid."'";
					} else {
						$qryNames .= ",".$name;
						$qryIDs .= ",'".$user->userid."'";
					}

					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART4.$name;

					$params[count($params)] = $user->userid;
					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART5.$user->userid;
					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART6.$user->userid;
					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART7;
				}
			}
		}

		if ($qryNames != "" && $qryIDs != "") {
			$qryFinal = $qryStart;
			$qryFinal .= $qryNames;
			$qryFinal .= $qryMiddle;
			$qryFinal .= $qryIDs;
			$qryFinal .= $qryEnd;
			$qryFinal .= $qry;
			$qryFinal .= $HUB_SQL->CLOSING_BRACKET;

			$linktypeUse = array();
			$resArray = $DB->select($qryFinal, $params);
			if ($resArray !== false) {
				$count = 0;
				if (is_countable($resArray)) {
					$count = count($resArray);
				}
				for ($i=0; $i<$count; $i++) {
					$array = $resArray[$i];
					$linktypeUse[count($linktypeUse)] = $array;
				}
			}

			$qry = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_SELECT;
			$qry .= $qryIDs;
			$qry .= $HUB_SQL->CLOSING_BRACKET;

			$linktypeName = array();
			$resArray = $DB->select($qry, $params);
			if ($resArray !== false) {
				$count = 0;
				if (is_countable($resArray)) {
					$count = count($resArray);
				}
				for ($i=0; $i<$count; $i++) {
					$array = $resArray[$i];
					$linktypeName[$array['UserID']] = $array['Name'];
				}
			}
		}

		return array($linktypeUse, $linktypeName);
	}

	/**
	 * Return the total count of all users (not groups) excluding the default user.
	 */
	function getTotalUsersCount() {
		global $DB, $CFG,$HUB_SQL;

		$params = array();
		$params[0] = $CFG->defaultUserID;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_REGISTERED_USERS_COUNT;

		$count = 0;
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$icount = 0;
			if (is_countable($resArray)) {
				$icount = count($resArray);
			}
			for ($i=0; $i<$icount; $i++) {
				$array = $resArray[$i];
				$count = $array['num'];
			}
		}

		return $count;
	}

	function getRegisteredUsers($direction, $sort, $oldsort) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $CFG->defaultUserID;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_REGISTERED_USERS;

		if ($sort) {
			if ($direction) {
				if ($oldsort === $sort) {
					if ($direction === 'ASC') {
						$direction = "DESC";
					} else {
						$direction = "ASC";
					}
				} else {
					$direction = "ASC";
				}
			} else {
				$direction = "ASC";
			}

			if ($sort == 'name') {
				$sql .= $HUB_SQL->ORDER_BY_NAME.$direction;
			} else if ($sort == 'date') {
				$sql .= $HUB_SQL->ORDER_BY_CREATIONDATE.$direction;
			} else if ($sort == 'login') {
				$sql .= $HUB_SQL->ORDER_BY_LASTLOGIN.$direction;
			} else if ($sort == 'email') {
				$sql .= $HUB_SQL->ORDER_BY_EMAIL.$direction;
			} else if ($sort == 'web') {
				$sql .= $HUB_SQL->ORDER_BY_WEBSITE.$direction;
			} else if ($sort == 'location') {
				$sql .= $HUB_SQL->ORDER_BY_LOCATION.$direction;
			}
		} else {
			$sql .= ' order by CreationDate DESC';
		}

		$registeredUsers = array();
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($registeredUsers,$array);
			}
		}

		// VIRTUOSO cannot order by long fields
		if ($sort == 'desc') {
			if ($direction == 'ASC') {
				usort($registeredUsers, 'descArraySortASC');
			} else {
				usort($registeredUsers, 'descArraySortDESC');
			}
		}

		return $registeredUsers;
	}

	/**
	 * Calculate the count of user registrations between the given dates/times
	 * @param $mintime the start time to count user registration from (timestamp).
	 * @param $maxtime the end time to count user registrations to (timestamp).
	 * return an inter count of the user registrations between the given times/dates
	 */
	function getRegisteredUserCount($mintime, $maxtime) {
		global $DB, $CFG,$HUB_SQL;

		$params = array();
		$params[0] = $CFG->defaultUserID;
		$params[1] = $mintime;
		$params[2] = $maxtime;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_REGISTERED_USER_COUNT_DATE;

		$num = 0;
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$icount = 0;
			if (is_countable($resArray)) {
				$icount = count($resArray);
			}
			for ($i=0; $i<$icount; $i++) {
				$array = $resArray[$i];
				$num = $array['num'];
			}
		}
		return $num;
	}

	/**
	 * Calculate the count of nodecreations between the given dates/times for the given node type name
	 * @param $nodetypenames the names of the node type to count for (comma separated list).
	 * @param $mintime the start time to count node creations from (timestamp).
	 * @param $maxtime the end time to count node creations to (timestamp). Optional. If not set then it is until now.
	 * return an inter count of the node creations between the given times/dates for the given node type name
	 */
	function getNodeCreationCount($nodetypenames,$mintime, $maxtime="") {
		global $DB, $CFG,$HUB_SQL;

		$params = array();

		$nodetypes = "";
		$nodetypesArray = array();
		$types = explode("," , $nodetypenames);
		$count = 0;
		if (is_countable($types)) {
			$count = count($types);
		}
		for($k=0; $k<$count; $k++){
			$nodetypesArray[count($nodetypesArray)] = $types[$k];
			if ($k == 0) {
				$nodetypes .= "?";
			} else {
				$nodetypes .= ",?";
			}
		}

		$params[0] = $mintime;
		$qry = $HUB_SQL->STATSLIB_GLOBAL_NODE_CREATION_COUNT;
		if (isset($maxtime) && $maxtime != "") {
			$params[count($params)] = $maxtime;
			$qry .= $HUB_SQL->STATSLIB_GLOBAL_NODE_CREATION_COUNT_DATE;
		}

		$params = array_merge($params, $nodetypesArray);
		$qry .= $HUB_SQL->STATSLIB_GLOBAL_NODE_CREATION_COUNT_NODE_TYPE_PART1;
		$qry .= $nodetypes;
		$qry .= $HUB_SQL->STATSLIB_GLOBAL_NODE_CREATION_COUNT_NODE_TYPE_PART2;


		$num = 0;
		$resArray = $DB->select($qry, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$num = $array['num'];
			}
		}
		return $num;
	}

	/*** BY GROUP ***/

	/**
	 * Get the most used Link Type
	 * @param $nodetypes the Node Type Names to get the information for
	 * @param $groupid the id of the group to filter on
	 * return an array with two items; 0=count, 1=Link Type name
	 */
	function getMostUsedLinkTypeByGroup($nodetypes, $groupid) {
		global $DB,$HUB_SQL,$CFG;

		$linkCount = 0;
		$linkName = "";

		$params = array();

		// MOST USED LinkType
		$sql = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_SELECT;
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {

			// Get Node Types
			$nodetypesArray = array();
			$nodetypes = "";
			$count = 0;
			if (is_countable($CFG->BASE_TYPES)) {
				$count = count($CFG->BASE_TYPES);
			}
			for($k=0; $k<$count; $k++){
				$nodetypesArray[count($nodetypesArray)] = $CFG->BASE_TYPES[$k];
				if ($k == 0) {
					$nodetypes .= "?";
				} else {
					$nodetypes .= ",?";
				}
			}
			$count = 0;
			if (is_countable($CFG->EVIDENCE_TYPES)) {
				$count = count($CFG->EVIDENCE_TYPES);
			}
			for ($k=0; $k<$count; $k++) {
				$nodetypesArray[count($nodetypesArray)] = $CFG->EVIDENCE_TYPES[$k];
				$nodetypes .= ",?";
			}
			$nodetypesArray[count($nodetypesArray)] = 'Pro';
			$nodetypes .= ",?";
			$nodetypesArray[count($nodetypesArray)] = 'Con';
			$nodetypes .= ",?";

			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$name = $array['Label'];

				$params = array();

				$qry4 = $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_PART1_GROUP;
				$params = array_merge($params, $nodetypesArray);
				$qry4 .= $nodetypes;
				$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_PART2;

				$params = array_merge($params, $nodetypesArray);
				$qry4 .= $nodetypes;

				$params[count($params)] = $name;
				$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_PART3;

				$params[count($params)] = $groupid;
				$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_LINKTYPE_SELECT_GROUP_FILTER;

				$resArray2 = $DB->select($qry4, $params);
				if ($resArray2 !== false) {
					$counti = 0;
					if (is_countable($resArray2)) {
						$counti = count($resArray2);
					}
					for ($k=0; $k<$counti; $k++) {
						$array = $resArray2[$k];
						$countk = $array['num'];
						if ($countk > $linkCount) {
							$linkCount = $countk;
							$linkName = $name;
						}
					}
				}
			}
		}

		return array($linkCount, $linkName);
	}

	/**
	 * Get the most used Node Type
	 * @param $nodetypes the Node Type Names to get the information for
	 * @param $groupid the id of the group to filter on
	 * return an array with two items; 0=count, 1=Node Type name
	 */
	function getMostUsedNodeTypeByGroup($nodetypes, $groupid) {
		global $DB,$HUB_SQL,$CFG;

		$params = array();

		//$nodetypes = getAllNodeTypeNames();
		//$innersql = getSQLForNodeTypeIDsForLabels($params,$nodetypes);

		// Get Node Types
		$nodetypesArray = array();
		$nodetypes = "";
		$count = 0;
		if (is_countable($CFG->BASE_TYPES)) {
			$count = count($CFG->BASE_TYPES);
		}
		for($i=0; $i<$count; $i++){
			$nodetypesArray[count($nodetypesArray)] = $CFG->BASE_TYPES[$i];
			if ($i == 0) {
				$nodetypes .= "?";
			} else {
				$nodetypes .= ",?";
			}
		}
		$count = 0;
		if (is_countable($CFG->EVIDENCE_TYPES)) {
			$count = count($CFG->EVIDENCE_TYPES);
		}
		for ($i=0; $i<$count; $i++) {
			$nodetypesArray[count($nodetypesArray)] = $CFG->EVIDENCE_TYPES[$i];
			$nodetypes .= ",?";
		}
		$nodetypesArray[count($nodetypesArray)] = 'Pro';
		$nodetypes .= ",?";
		$nodetypesArray[count($nodetypesArray)] = 'Con';
		$nodetypes .= ",?";

		$sql = $HUB_SQL->STATSLIB_GLOBAL_NODETYPE_SELECT_PART1;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_NODETYPE_SELECT_PART2;

		$roleCount = 0;
		$roleName = "";

		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			$roleIDs = "";
			$previousName = "";
			for ($i=0; $i<$count; $i++) {

				$array = $resArray[$i];
				$name = $array['Name'];
				$roleid = $array['NodeTypeID'];

				$params = array();

				if ($previousName == "") {
					$previousName = $name;
				}

				if ($previousName != $name) {

					$qry4 = $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART1_GROUP;
					$params = array_merge($params, $nodetypesArray);
					$qry4 .= $nodetypes;
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART2;
					$params = array_merge($params, $nodetypesArray);
					$qry4 .= $nodetypes;
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART3;
					$qry4 .= $roleIDs; // Do not need escaping so can go stright in
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART4;
					$qry4 .= $roleIDs; // Do not need escaping so can go stright in
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_PART5;

					$params[count($params)] = $groupid;
					$qry4 .= $HUB_SQL->STATSLIB_GLOBAL_MOST_USED_NODETYPE_SELECT_GROUP_FILTER;

					$resArray2 = $DB->select($qry4, $params);
					if ($resArray2 !== false) {
						$countk = 0;
						if (is_countable($resArray2)) {
							$countk = count($resArray2);
						}
						for ($k=0; $k<$countk; $k++) {
							$array2 = $resArray2[$k];
							$counts = $array2['num'];
							if ($counts > $roleCount) {
								$roleCount = $counts;
								$roleName = $previousName;
							}
						}
					}

					$roleIDs = "";
				}

				$previousName = $name;

				if ($roleIDs == "") {
					$roleIDs .= "'".$roleid."'";
				} else {
					$roleIDs .= ", '".$roleid."'";
				}
			}
		}
		return array($roleCount, $roleName);
	}

	/**
	 * Get the link type usage for each user
	 * return an array with two items; 1=Array of arrays of link type use counts, 2=An array of users for thos counts.
	 * Both return arrays are associative mapped to the userids.
	 * @param $groupid the id of the group to filter on
	 */
	function getLinkTypeUsageByGroup($groupid) {
		global $DB,$HUB_SQL;

		$params = array();

		$qryStart = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART1;
		$qryNames = "";
		$qryMiddle = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART2;
		$qryIDs = "";
		$qryEnd = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART3;
		$qry = "";

		$userSet = getActiveConnectionUsers(0, 10);
		if ($userSet->count > 0) {
			for ($i = 0; $i < $userSet->count; $i++) {
				$user = $userSet->users[$i];
				if ($user->userid && $user->userid != "") {
					$name = "_".$user->userid; // because can't use a number to start an alias name

					if ($i==0) {
						$qryNames .= $name;
						$qryIDs .= "'".$user->userid."'";
					} else {
						$qryNames .= ",".$name;
						$qryIDs .= ",'".$user->userid."'";
					}

					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART4.$name;

					$params[count($params)] = $groupid;
					$params[count($params)] = $user->userid;
					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART5_GROUPS.$user->userid;
					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART6.$user->userid;
					$qry .= $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_PART7;
				}
			}
		}

		if ($qryNames != "" && $qryIDs != "") {
			$qryFinal = $qryStart;
			$qryFinal .= $qryNames;
			$qryFinal .= $qryMiddle;
			$qryFinal .= $qryIDs;
			$qryFinal .= $qryEnd;
			$qryFinal .= $qry;
			$qryFinal .= $HUB_SQL->CLOSING_BRACKET;

			$linktypeUse = array();

			$resArray = $DB->select($qryFinal, $params);
			if ($resArray !== false) {
				$count = 0;
				if (is_countable($resArray)) {
					$count = count($resArray);
				}
				for ($i=0; $i<$count; $i++) {
					$array = $resArray[$i];
					$linktypeUse[count($linktypeUse)] = $array;
				}
			}

			$qry = $HUB_SQL->STATSLIB_GLOBAL_LINKTYPE_USAGE_SELECT;
			$qry .= $qryIDs;
			$qry .= $HUB_SQL->CLOSING_BRACKET;

			$linktypeName = array();
			$resArray = $DB->select($qry, $params);
			if ($resArray !== false) {
				$count = 0;
				if (is_countable($resArray)) {
					$count = count($resArray);
				}
				for ($i=0; $i<$count; $i++) {
					$array = $resArray[$i];
					$linktypeName[$array['UserID']] = $array['Name'];
				}
			}

		}

		return array($linktypeUse, $linktypeName);
	}

	/**
	 * Get the most Connected Node
	 * @param $nodetypes the Node Type Names to get the information for
	 * @param $groupid the id of the group to filter on
	 * return an array with three items; 1=Name, 2=Node Type 3=count.
	 */
	function getMostConnectedNodeByGroup($nodetypes, $groupid) {
		global $DB,$USER,$HUB_SQL,$CFG;

		$currentuser = '';
		if (isset($USER->userid)) {
			$currentuser = $USER->userid;
		}

		$params = array();

		// Get Node Types
		$nodetypesArray = array();
		$nodetypes = "";
		$count = 0;
		if (is_countable($CFG->BASE_TYPES)) {
			$count = count($CFG->BASE_TYPES);
		}
		for($i=0; $i<$count; $i++){
			$nodetypesArray[count($nodetypesArray)] = $CFG->BASE_TYPES[$i];
			if ($i == 0) {
				$nodetypes .= "?";
			} else {
				$nodetypes .= ",?";
			}
		}
		$count = 0;
		if (is_countable($CFG->EVIDENCE_TYPES)) {
			$count = count($CFG->EVIDENCE_TYPES);
		}
		for ($i=0; $i<$count; $i++) {
			$nodetypesArray[count($nodetypesArray)] = $CFG->EVIDENCE_TYPES[$i];
			$nodetypes .= ",?";
		}
		$nodetypesArray[count($nodetypesArray)] = 'Pro';
		$nodetypes .= ",?";
		$nodetypesArray[count($nodetypesArray)] = 'Con';
		$nodetypes .= ",?";

		$mostconidea = "";
		$mostcontype = "";
		$mostconideaCount = 0;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART1_GROUP;
		$params[count($params)] = $groupid;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART1b_GROUP;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART2;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;

		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART3;

		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART4_GROUP;

		$params[count($params)] = $groupid;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART4b_GROUP;

		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART5;
		$params = array_merge($params, $nodetypesArray);
		$sql .= $nodetypes;

		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$params[count($params)] = 'N';
		$params[count($params)] = $currentuser;
		$params[count($params)] = $currentuser;
		$sql .= $HUB_SQL->STATSLIB_GLOBAL_MOST_CONNECTED_NODE_PART6;

		// ADD LIMITING
		$sql = $DB->addLimitingResults($sql, 0, 1);

		//error_log(print_r($sql, true));

		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				$id = $array['ID'];
				$node = new CNode();
				$node->nodeid = $id;
				$node = $node->load();
				if (!$node instanceof Hub_Error) {
					$countr = $array['num'];
					$mostconidea = $node->name;
					$mostcontype = $node->role->name;
					$mostconideaCount = $countr;
				}
			}
		}
		return array($mostconidea, $mostcontype, $mostconideaCount);
	}

	/**
	 * Sort objects by connection from name value ASC
	 */
	function fromNameArraySortASC($a,$b) {
		return strcmp($a['FromName'], $b['FromName']);
	}

	/**
	 * Sort objects by connection from name value DESC
	 */
	function fromNameArraySortDESC($a,$b) {
		$result = strcmp($a['FromName'], $b['FromName']);
		if ($results < 0) {
			return 1;
		} else if ($results > 0) {
			return -1;
		} else {
			return 0;
		}
	}

	/**
	 * Sort objects by connection to name value ASC
	 */
	function toNameArraySortASC($a,$b) {
		return strcmp($a['ToName'], $b['ToName']);
	}

	/**
	 * Sort objects by connection to name value DESC
	 */
	function toNameArraySortDESC($a,$b) {
		$result = strcmp($a['ToName'], $b['ToName']);
		if ($results < 0) {
			return 1;
		} else if ($results > 0) {
			return -1;
		} else {
			return 0;
		}
	}

	/*** BY DEBATE ***/
	function getConnectionsForDebate($nodeid) {
		return getDebate($nodeid);
	}


	/**
	 * Return the total count of all groups (not users) excluding the default user.
	 */
	function getTotalGroupsCount() {
		global $DB, $CFG,$HUB_SQL;

		$params = array();
		$params[0] = $CFG->defaultUserID;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_REGISTERED_USERS_COUNT;

		$count = 0;
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$icount = 0;
			if (is_countable($resArray)) {
				$icount = count($resArray);
			}
			for ($i=0; $i<$icount; $i++) {
				$array = $resArray[$i];
				$count = $array['num'];
			}
		}

		return $count;
	}

	function getRegisteredGroups($direction, $sort, $oldsort) {
		global $DB,$CFG,$HUB_SQL;

		$params = array();
		$params[0] = $CFG->defaultUserID;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_REGISTERED_USERS;

		if ($sort) {
			if ($direction) {
				if ($oldsort === $sort) {
					if ($direction === 'ASC') {
						$direction = "DESC";
					} else {
						$direction = "ASC";
					}
				} else {
					$direction = "ASC";
				}
			} else {
				$direction = "ASC";
			}

			if ($sort == 'name') {
				$sql .= $HUB_SQL->ORDER_BY_NAME.$direction;
			} else if ($sort == 'date') {
				$sql .= $HUB_SQL->ORDER_BY_CREATIONDATE.$direction;
			} else if ($sort == 'login') {
				$sql .= $HUB_SQL->ORDER_BY_LASTLOGIN.$direction;
			} else if ($sort == 'email') {
				$sql .= $HUB_SQL->ORDER_BY_EMAIL.$direction;
			} else if ($sort == 'web') {
				$sql .= $HUB_SQL->ORDER_BY_WEBSITE.$direction;
			} else if ($sort == 'location') {
				$sql .= $HUB_SQL->ORDER_BY_LOCATION.$direction;
			}
		} else {
			$sql .= ' order by CreationDate DESC';
		}

		$registeredUsers = array();
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$count = 0;
			if (is_countable($resArray)) {
				$count = count($resArray);
			}
			for ($i=0; $i<$count; $i++) {
				$array = $resArray[$i];
				array_push($registeredUsers,$array);
			}
		}

		// VIRTUOSO cannot order by long fields
		if ($sort == 'desc') {
			if ($direction == 'ASC') {
				usort($registeredUsers, 'descArraySortASC');
			} else {
				usort($registeredUsers, 'descArraySortDESC');
			}
		}

		return $registeredUsers;
	}

	/**
	 * Calculate the count of user registrations between the given dates/times
	 * @param $mintime the start time to count user registration from (timestamp).
	 * @param $maxtime the end time to count user registrations to (timestamp).
	 * return an inter count of the user registrations between the given times/dates
	 */
	function getRegisteredGroupCount($mintime, $maxtime) {
		global $DB, $CFG,$HUB_SQL;

		$params = array();
		$params[0] = $CFG->defaultUserID;
		$params[1] = $mintime;
		$params[2] = $maxtime;

		$sql = $HUB_SQL->STATSLIB_GLOBAL_REGISTERED_USER_COUNT_DATE;

		$num = 0;
		$resArray = $DB->select($sql, $params);
		if ($resArray !== false) {
			$icount = 0;
			if (is_countable($resArray)) {
				$icount = count($resArray);
			}
			for ($i=0; $i<$icount; $i++) {
				$array = $resArray[$i];
				$num = $array['num'];
			}
		}
		return $num;
	}
?>