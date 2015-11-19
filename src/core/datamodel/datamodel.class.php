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
 * DESCRIBES THE DATAMODEL OF CONNECTIONS.
 * For the evidence hub server side code to use.
 **/

/**
 * The object that holds all the information about a given connection definition
 */
class DataModelConnection {

	private $fromnodetypeArray;
	private $tonodetypeArray;
	private $linktype;

	function load($fromnodetypes, $linktype, $tonodetypes) {
		$this->fromnodetypeArray = $fromnodetypes;
		$this->linktype = $linktype;
		$this->tonodetypeArray = $tonodetypes;
	}

	/**
	 * do the passed items match this Connection definition
	 */
	function matches($fromnode, $link, $tonode) {
		$matches  = false;

		/*
		error_log(print_r("in array from=".$fromnode,true));
		error_log(print_r("in array to=".$tonode, true));
		error_log(print_r("link=".$link, true));
		error_log(print_r($this->fromnodetypeArray, true));
		error_log(print_r($this->linktype, true));
		error_log(print_r($this->tonodetypeArray, true));
		*/

		if (in_array($fromnode, $this->fromnodetypeArray)
			&& $this->linktype === $link
				&& in_array($tonode, $this->tonodetypeArray)) {
			$matches = true;
			//echo "MATCHES";
		}

		return $matches;
	}
}


/**
 * The object that holds all the connection definitions and functions to check data against them.
 */
class DataModel {

	private $hubmodel = array();

	function load() {
		global $CFG;

		$this->hubmodel[0] = new DataModelConnection();
		$this->hubmodel[0]->load( array("Solution"), $CFG->LINK_SOLUTION_ISSUE, array("Issue"));

		$this->hubmodel[1] = new DataModelConnection();
		$this->hubmodel[1]->load( array("Pro"), $CFG->LINK_PRO_SOLUTION, array("Solution"));

		$this->hubmodel[2] = new DataModelConnection();
		$this->hubmodel[2]->load( array("Con"), $CFG->LINK_CON_SOLUTION, array("Solution"));

		$this->hubmodel[3] = new DataModelConnection();
		$this->hubmodel[3]->load( array("Comment"), $CFG->LINK_COMMENT_NODE, array("Solution"));

		$this->hubmodel[4] = new DataModelConnection();
		$this->hubmodel[4]->load( array("Solution"), $CFG->LINK_BUILT_FROM, array("Solution"));
	}

	function matchesModel($fromnode, $link, $tonode) {
		$matches = false;

		$i=0;
		$count=count($this->hubmodel);
		for ($i=0; $i<$count; $i++) {
			$next = $this->hubmodel[$i];
			//echo $i." ";
			if ($next->matches($fromnode, $link, $tonode)) {
				$matches = true;
				break;
			}
		}

		return $matches;
	}

 	function matchesModelPro($fromnode, $link, $tonode) {
 		global $CFG;

		$matches = false;
		if ($fromnode == "Pro"
			&& $link === $CFG->LINK_PRO_SOLUTION
				&& $tonode == "Solution") {

			$matches = true;
		}

		return $matches;
	}

	function matchesModelCon($fromnode, $link, $tonode) {
		global $CFG;

		$matches  = false;

		if ($fromnode == "Con"
			&& $link === $CFG->LINK_CON_SOLUTION
				&& $tonode == "Solution") {

			$matches = true;
		}

		return $matches;
	}
}
?>