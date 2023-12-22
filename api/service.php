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
 * REST service API
 *
 * All the methods listed are are available to users through REST-style URL calls
 * The methods should call the corresponding methods in the PHP API (core/apilib.php)
 *
 */

include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

global $USER,$CFG,$LNG;

//send the header info
set_service_header();

$method = optional_param("method","",PARAM_ALPHA);

// If this system has been set to be a private Site that means all access requires login.
// So unless your API request is to login, check they are logged in before proceeding.
if ($CFG->privateSite && $method != "login" && (!isset($USER->userid) || $USER->userid == "")) {
    global $ERROR;
    $ERROR = new Hub_Error;
    $ERROR->createAccessDeniedError();
	include($HUB_FLM->getCodeDirPath("core/formaterror.php"));
    die;
}

// optional params for ordering, max no and sorting sets of objects and filtering
$start = optional_param("start",0,PARAM_INT);
$max = optional_param("max",20,PARAM_INT);
$o = optional_param("orderby","date",PARAM_ALPHA);
$s = optional_param("sort","DESC",PARAM_ALPHA);
$filterlinkgroup = optional_param("filtergroup","all", PARAM_TEXT);
$filterlinktypes = optional_param("filterlist","", PARAM_TEXT);
$filternodetypes = optional_param('filternodetypes', '', PARAM_TEXT);
$style= optional_param('style','long',PARAM_TEXT);
$status = optional_param("status",0,PARAM_INT);

// this needs to be locked down, as the Evidence Hub is an open system and does not give this choice as Cohere does.
$private = "N";

//check start and max are more than 0!
if($start < 0){
    $start = 0;
}
if ($max < -1 ){
    $max = -1;
}

$response = "";
switch($method) {

	/** LOGIN IN / OUT **/
    case "validatesession":
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = validateUserSession($userid);
        break;
    case "login":
        $username = required_param('username',PARAM_TEXT);
        $password = required_param('password',PARAM_TEXT);
        $response = login($username,$password);
        break;
    case "logout":
        clearSession();
        $response = new Result("logout","logged out");
        break;

    /** NODES **/
    case "getnode":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = getNode($nodeid,$style);
        break;
 	case "addnode":
        $name = required_param('name',PARAM_TEXT);
        $desc = required_param('desc',PARAM_HTML);
        $nodetypeid = optional_param('nodetypeid',"",PARAM_ALPHANUMEXT);
        $imageurlid = optional_param('imageurlid',"",PARAM_TEXT);
        $imagethumbnail = optional_param('imagethumbnail',"",PARAM_TEXT);
        $response = addNode($name,$desc,$private,$nodetypeid,$imageurlid,$imagethumbnail);
        break;
 	case "addnodeandconnect":
        $name = required_param('name',PARAM_TEXT);
        $desc = required_param('desc',PARAM_HTML);
        $nodetypename = required_param('nodetypename',PARAM_TEXT);
        $focalnodeid = required_param('focalnodeid',PARAM_ALPHANUMEXT);
        $linktypename = required_param('linktypename',PARAM_TEXT);
        $direction = optional_param('direction','from',PARAM_ALPHA);
        $groupid = optional_param('groupid',"",PARAM_ALPHANUMEXT);
        $imageurlid = optional_param('imageurlid',"",PARAM_TEXT);
        $imagethumbnail = optional_param('imagethumbnail',"",PARAM_TEXT);
        $resources = optional_param('resources',"",PARAM_TEXT);
        $response = addNodeAndConnect($name,$desc,$nodetypename,$focalnodeid,$linktypename,$direction,$groupid,$private,$imageurlid,$imagethumbnail,$resources);
        break;
    case "editnode":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $name = required_param('name',PARAM_TEXT);
        $desc = required_param('desc',PARAM_HTML);
        $nodetypeid = optional_param('nodetypeid',"",PARAM_TEXT);
        $resources = optional_param('resources',"",PARAM_TEXT);
        $response = editNode($nodeid,$name,$desc,$private,$nodetypeid,"","",$resources);
        break;
    case "updatenodestartdate":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $startdatetime = optional_param('startdatetime',"",PARAM_TEXT);
        $response = updateNodeStartDate($nodeid,$startdatetime);
        break;
    case "updatenodeenddate":
        $nodeid = required_param('nodeid',PARAM_TEXT);
        $enddatetime = optional_param('enddatetime',"",PARAM_TEXT);
        $response = updateNodeEndDate($nodeid,$enddatetime);
        break;
    case "updatenodelocation":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $location = optional_param('location',"",PARAM_TEXT);
        $loccountry = optional_param('loccountry',"",PARAM_TEXT);
        $address1 = optional_param('address1',"",PARAM_TEXT);
        $address2 = optional_param('address2',"",PARAM_TEXT);
        $postcode = optional_param('postcode',"",PARAM_TEXT);
        $response = updateNodeLocation($nodeid,$location,$loccountry,$address1,$address2,$postcode);
        break;
    case "deletenode":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = deleteNode($nodeid);
        break;

    case "getnodesbydate":
        $date = required_param('date',PARAM_INT);
        $response = getNodesByDate($date,$start,$max,$o,$s,$style,$status);
        break;
    case "getnodesbyname":
        $name = required_param('name',PARAM_TEXT);
        $response = getNodesByName($name,$start,$max,$o,$s,$style,$status);
        break;
    case "getnodesbytag":
        $tagid= required_param('tagid',PARAM_ALPHANUMEXT);
        $response = getNodesByTag($tagid,$start,$max,$o,$s,$style,$status);
        break;
    case "getnodesbyurl":
        $url= required_param('url',PARAM_URL);
        $response = getNodesByURL($url,$start,$max,$o,$s,$filternodetypes,$style,$status);
        break;
    case "getnodesbyuser":
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $query = optional_param('q', "",PARAM_TEXT);
        $connectionfilter = optional_param('filterbyconnection','',PARAM_ALPHA);
        $response = getNodesByUser($userid,$start,$max,$o,$s,$filternodetypes,$style, $query, $connectionfilter,$status);
        break;
    case "getnodesbygroup":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $filterusers = optional_param('filterusers', '', PARAM_TEXT);
        $query = optional_param('q', "",PARAM_TEXT);
        $connectionfilter = optional_param('filterbyconnection','',PARAM_ALPHA);
        $response = getNodesByGroup($groupid,$start,$max,$o,$s,$filterusers,$filternodetypes, $style, $query, $connectionfilter,$status);
        break;
    case "getnodesbyglobal":
        $query = optional_param('q', "",PARAM_TEXT);
        $scope = optional_param('scope','all',PARAM_TEXT);
        $tagsonly = optional_param('tagsonly',false,PARAM_BOOL);
        $connectionfilter = optional_param('filterbyconnection','',PARAM_ALPHA);
        $response = getNodesByGlobal($start,$max,$o,$s,$filternodetypes,$style,$query,$scope,$tagsonly,$connectionfilter,$status);
        break;
    case "getmostconnectednodes":
        $scope = optional_param('scope','my',PARAM_TEXT);
        $response = getMostConnectedNodes($scope,$start,$max,$style,$status);
        break;
    case "getnodesbyfirstcharacters":
        $query = required_param('q',PARAM_TEXT);
        $scope = optional_param('scope','my',PARAM_TEXT);
        $response = getNodesByFirstCharacters($query,$scope,$start,$max,"name","ASC",$filternodetypes,$style,$status);
        break;
    case "getmultinodes": // not used as far as I can find
        $nodeids = required_param('nodeids',PARAM_TEXT);
        $response = getMultiNodes($nodeids,$start,$max,$o,$s,$style); //should this pass the status?
    	break;

	/** RESOURCE **/
    case "addwebresource":
        $url = required_param('url',PARAM_URL);
        $title = required_param('title',PARAM_TEXT);
        $desc = required_param('desc',PARAM_TEXT);
        $clip = optional_param('clip',"",PARAM_TEXT);
        $clippath = urldecode(optional_param('clippath',"",PARAM_HTML));
        $response = addWebResource($url, $title, $desc, $private,$clip, $clippath);
        break;

    /** URLS **/
    case "autocompleteurldetails":
        $url= required_param('url',PARAM_URL);
        $response = autoCompleteURLDetails($url);
        break;
    case "addurl":
        $url = required_param('url',PARAM_URL);
        $title = required_param('title',PARAM_TEXT);
        $desc = required_param('desc',PARAM_TEXT);
        $clip = optional_param('clip',"",PARAM_TEXT);
        $clippath = urldecode(optional_param('clippath',"",PARAM_HTML));
        //$cliphtml = urldecode(optional_param('cliphtml',"",PARAM_HTML));
        $cliphtml = "";
        $response = addURL($url, $title, $desc, $private,$clip, $clippath, $cliphtml);
        break;
    case "deleteurl":
        $urlid = required_param('urlid',PARAM_ALPHANUMEXT);
        $response = deleteURL($urlid);
        break;

    /** CONNECTING NODE AND URL **/
    case "addurltonode":
        $urlid = required_param('urlid',PARAM_ALPHANUMEXT);
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $comments = optional_param("comments","",PARAM_TEXT);
        $response = addURLToNode($urlid,$nodeid,$comments);
        break;

    /** CONNECTIONS **/
    case "getconnection":
        $connid = required_param('connid',PARAM_ALPHANUMEXT);
        $response = getConnection($connid,$style);
        break;
    case "getconnectionsbyuser":
        $query = required_param('q',PARAM_TEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = getConnectionsByUser($userid,$start,$max,$o,$s,$filterlinkgroup,$filterlinktypes,$filternodetypes,$style,$query,$status);
        break;
    case "getconnectionsbyglobal":
        $query = optional_param('q', "",PARAM_TEXT);
        $scope = optional_param('scope','all',PARAM_TEXT);
        $tagsonly = optional_param('tagsonly',false,PARAM_BOOL);
        $response = getConnectionsByGlobal($start,$max,$o,$s,$filterlinkgroup,$filterlinktypes,$filternodetypes,$style,$query,$scope,$tagsonly,$status);
        break;
    case "getconnectionsbynode":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = getConnectionsByNode($nodeid,$start,$max,$o,$s,$filterlinkgroup,$filterlinktypes,$filternodetypes,$style,$status);
        break;
   	case "getconnectionsbyurl":
        $url= required_param('url',PARAM_URL);
        $response = getConnectionsByURL($url,$start,$max,$o,$s,$filterlinkgroup,$filterlinktypes,$filternodetypes,$style,$status);
        break;
    case "getconnectionsbysocial":
        $linklabels = required_param('linklabels',PARAM_TEXT);
        $filternodetypes = required_param('filternodetypes',PARAM_TEXT);
        $scope = optional_param('scope','all',PARAM_ALPHANUM);
        $userid = optional_param('userid', '', PARAM_ALPHANUMEXT);
        $response = getConnectionsBySocial($scope,$start,$max,$o,$s,$linklabels,$filternodetypes,$userid,$style,$status);
        break;
    case "getmulticonnections":
        $connectionids = parseToJSON(required_param('connectionids',PARAM_TEXT)); // needs this parsing to convert single speech marks back.
        $response = getMultiConnections($connectionids,$start,$max,$o,$s,$style);
    	break;
    case "getconnectionsbypath":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $linklabels = required_param('linklabels',PARAM_TEXT);
        $scope = optional_param('scope','all',PARAM_TEXT);
        $userid = ""; //optional_param('userid','',PARAM_ALPHANUMEXT);
        $linkgroup = optional_param('linkgroup','',PARAM_TEXT);
        $depth = optional_param('depth','7',PARAM_INT);
        $direction = optional_param('direction','both',PARAM_TEXT);
        $labelmatch = optional_param('labelmatch','false',PARAM_TEXT);
        $nodetypes = optional_param('nodetypes',null,PARAM_TEXT);
        $response = getConnectionsByPath($nodeid,$linklabels,$userid,$scope,$linkgroup,$depth,$direction,$labelmatch,$nodetypes,$style,$status);
        break;
    case "getconnectionsbypathbydepth":
        $nodeid = optional_param('nodeid','',PARAM_ALPHANUMEXT);
        $searchid = optional_param('searchid','', PARAM_ALPHANUMEXT);
        $scope = optional_param('scope','all',PARAM_TEXT);
        $labelmatch = optional_param('labelmatch','false',PARAM_TEXT);
        $depth = optional_param('depth','7',PARAM_INT);
        $linklabels = optional_param('linklabels',null,PARAM_TEXT);
        $linkgroups = optional_param('linkgroups',null,PARAM_TEXT);
        $directions = optional_param('directions',null,PARAM_TEXT);
        $nodetypes = optional_param('nodetypes',null,PARAM_TEXT);
        $nodeids = optional_param('nodeids',null,PARAM_TEXT);
        $logictype = optional_param('logictype','or',PARAM_TEXT);
        $uniquepath = optional_param('uniquepath','false',PARAM_TEXT);
        $response = getConnectionsByPathByDepth($logictype,$scope,$labelmatch,$nodeid,$depth,$linklabels,$linkgroups,$directions,$nodetypes,$nodeids, $uniquepath, $style,$status);
	    break;
 	case "addconnection":
        $fromnodeid = required_param('fromnodeid',PARAM_ALPHANUMEXT);
        $fromroleid = required_param('fromroleid',PARAM_ALPHANUMEXT);
        $linktypeid = required_param('linktypeid',PARAM_ALPHANUMEXT);
        $tonodeid = required_param('tonodeid',PARAM_ALPHANUMEXT);
        $toroleid = required_param('toroleid',PARAM_ALPHANUMEXT);
        $description = optional_param('description',"",PARAM_TEXT);
        $response = addConnection($fromnodeid,$fromroleid,$linktypeid,$tonodeid,$toroleid,$private,$description);
        break;
 	case "addconnectionlinkname":
        $fromnodeid = required_param('fromnodeid',PARAM_ALPHANUMEXT);
        $fromroleid = required_param('fromroleid',PARAM_ALPHANUMEXT);
        $linktypename = required_param('linktypename',PARAM_TEXT);
        $tonodeid = required_param('tonodeid',PARAM_ALPHANUMEXT);
        $toroleid = required_param('toroleid',PARAM_ALPHANUMEXT);
        $description = optional_param('description',"",PARAM_TEXT);

        $link = getLinkTypeByLabel($linktypename);
        if (!$link instanceof Hub_Error) {
        	$response = addConnection($fromnodeid,$fromroleid,$link->linktypeid,$tonodeid,$toroleid,$private,$description);
        } else {
			$ERROR = new Hub_Error;
			return $ERROR->createInvalidConnectionError();
        }
        break;
   case "editconnectiondescription":
        $connid = required_param('connid',PARAM_ALPHANUMEXT);
        $description = optional_param('description',"",PARAM_TEXT);
        $response = editConnectionDescription($connid,$description);
        break;
    case "deleteconnection":
        $connid = required_param('connid',PARAM_ALPHANUMEXT);
        $response = deleteConnection($connid);
        break;

    /** ROLES aka NODE TYPES **/
    case "getrolebyname":
        $rolename = required_param('rolename',PARAM_TEXT);
        $response = getRoleByName($rolename);
        break;

    /** LINK TYPES **/
    case "getlinktypebylabel":
        $label = required_param('label',PARAM_TEXT);
        $response = getLinkTypeByLabel($label);
        break;

    /** USERS **/
	case "getuser":
		$userid = required_param('userid',PARAM_ALPHANUMEXT);
		$response = getUser($userid,$style);
		break;
    case "getactiveconnectionusers":
    	$response = getActiveConnectionUsers($start,$max,$style);
    	break;
    case "getactiveideausers":
    	$response = getActiveIdeaUsers($start,$max,$style);
    	break;
    case "getusersbyfollowing":
        $itemid = required_param('itemid',PARAM_ALPHANUMEXT);
        $response = getUsersByFollowing($itemid,$start,$max,$o,$s,$style);
        break;
    case "getusersbymostfollowed":
        $limit = required_param('limit',PARAM_TEXT);
    	$response = getUsersByMostFollowed($limit,$style);
    	break;
    case "getusersmostactive":
        $limit = required_param('limit',PARAM_TEXT);
        $from = required_param('from',PARAM_INT);
    	$response = getUsersMostActive($limit, $from, $style);
    	break;
    case "getusersbyglobal":
        $includegroups = optional_param('includegroups',false,PARAM_BOOL);
        $query = optional_param('q', "", PARAM_TEXT);
        $response = getUsersByGlobal($includegroups, $start,$max,$o,$s,$style,$query,$status);
        break;

    /** TAGS **/
    case "gettag":
        $tagid = required_param('tagid',PARAM_ALPHANUMEXT);
        $response = getTag($tagid);
        break;
    case "getusertags":
        $response = getUserTags();
        break;
    case "addtag":
        $tagname = required_param('tagname',PARAM_TEXT);
        $response = addTag($tagname);
        break;
    case "edittag":
        $tagid = required_param('tagid',PARAM_ALPHANUMEXT);
        $tagname = required_param('tagname',PARAM_TEXT);
        $response = editTag($tagid,$tagname);
        break;
    case "deletetag":
        $tagid = required_param('tagid',PARAM_ALPHANUMEXT);
        $response = deleteTag($tagid);
        break;
    case "gettagsbyfirstcharacters":
        $query = required_param('q',PARAM_TEXT);
        $scope = optional_param('scope','my',PARAM_ALPHANUMEXT);
        $response = getTagsByFirstCharacters($query,$scope);
        break;

    /** VOTING **/
	case "addlemon":
        $issueid = required_param('issueid',PARAM_ALPHANUMEXT);
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = addLemon($issueid,$nodeid);
		break;
	case "deletelemon":
        $issueid = required_param('issueid',PARAM_ALPHANUMEXT);
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = deleteLemon($issueid,$nodeid);
		break;
	case "nodevote":
        $vote = required_param('vote',PARAM_ALPHANUMEXT);
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = nodeVote($nodeid, $vote);
		break;
	case "deletenodevote":
        $vote = required_param('vote',PARAM_ALPHANUMEXT);
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = deleteNodeVote($nodeid, $vote);
		break;
	case "connectionvote":
        $vote = required_param('vote',PARAM_ALPHANUMEXT);
        $connid = required_param('connid',PARAM_ALPHANUMEXT);
        $response = connectionVote($connid, $vote);
		break;
	case "deleteconnectionvote":
        $vote = required_param('vote',PARAM_ALPHANUMEXT);
        $connid = required_param('connid',PARAM_ALPHANUMEXT);
        $response = deleteConnectionVote($connid, $vote);
		break;

	/** FOLLOWING **/
	case "addfollowing":
        $itemid = required_param('itemid',PARAM_ALPHANUMEXT);
        $response = addFollowing($itemid);
		break;
	case "deletefollowing":
        $itemid = required_param('itemid',PARAM_ALPHANUMEXT);
        $response = deleteFollowing($itemid);
		break;

 	/** AUDITING **/
 	case "auditsearch":
        $query = required_param('q',PARAM_TEXT);
        $tagsonlyoption = optional_param('tagsonly','N',PARAM_TEXT);
		$type = optional_param('type','main',PARAM_ALPHA);
		$typeitemid = optional_param('typeitemid','',PARAM_ALPHANUMEXT);
		$searchid = "";
        if (isset($USER->userid)) {
 			$searchid = auditSearch($USER->userid, $query, $tagsonlyoption, $type, $typeitemid);
        }
 		$response = $searchid;
 		break;
	case "auditnodeviewmulti":
        $nodeids = required_param('nodeids',PARAM_TEXT); // comma separated list
        $viewtype = required_param('viewtype',PARAM_ALPHA);
		$userid = "";
		if (isset($USER->userid)) {
			$userid = $USER->userid;
		}
		auditViewMulti($userid, $nodeids, $viewtype);

		break;
	case "auditnodeview":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $viewtype = required_param('viewtype',PARAM_ALPHA);
		$userid = "";
		if (isset($USER->userid)) {
			$userid = $USER->userid;
		}
		auditView($userid, $nodeid, $viewtype);

		break;
 	case "audittesting":
        $trialname = required_param('trialname',PARAM_TEXT);
        $itemid = required_param('itemid',PARAM_ALPHANUMEXT);
		$testelementid = required_param('testelementid',PARAM_ALPHANUMEXT);
		$event = required_param('event',PARAM_TEXT);
		$state = required_param('state',PARAM_XML);
		$userid = "";
        if (isset($USER->userid)) {
			$userid = $USER->userid;
		}

		$response = auditTesting($trialname, $userid, $itemid, $testelementid, $event, $state);
 		break;

	/** GROUPS **/
    case "getconnectionsbygroup":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $linklabels = required_param('linklabels',PARAM_TEXT);
        $filternodetypes = required_param('filternodetypes',PARAM_TEXT);
        $scope = optional_param('scope','all',PARAM_TEXT);
        $userid = optional_param('userid', '', PARAM_ALPHANUMEXT);
        $response = getConnectionsByGroup($groupid, $scope,$start,$max,$o,$s,$linklabels,$filternodetypes,$userid,$style);
        break;
    case "getgroupsbyglobal":
        $query = optional_param('q', "", PARAM_TEXT);
        $response = getGroupsByGlobal($start,$max,$o,$s,$style,$query);
        break;
    case "getmygroups":
        $userid = optional_param('userid','',PARAM_ALPHANUMEXT);
        $response = getMyGroups($userid);
        break;
    case "getmyadmingroups":
        $userid = optional_param('userid','',PARAM_ALPHANUMEXT);
        $response = getMyAdminGroups($userid);
        break;
    case "addgroup":
        $groupname = required_param('groupname',PARAM_TEXT);
        $response = addGroup($groupname);
        break;
    case "deletegroup":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $response = deleteGroup($groupid);
        break;
    case "addgroupmember":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = addGroupMember($groupid,$userid);
        break;
    case "makegroupadmin":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = makeGroupAdmin($groupid,$userid);
        break;
    case "removegroupadmin":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = removeGroupAdmin($groupid,$userid);
        break;
    case "removegroupmember":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = removeGroupMember($groupid,$userid);
        break;
    case "rejectgroupmemberjoin":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = rejectGroupMemberJoin($groupid,$userid);
        break;
    case "approvegroupmemberjoin":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $userid = required_param('userid',PARAM_ALPHANUMEXT);
        $response = approveGroupMemberJoin($groupid,$userid);
        break;
    case "mergeselectednodes":
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $issuenodeid = required_param('issuenodeid',PARAM_ALPHANUMEXT);
        $ids = required_param('ids',PARAM_TEXT);
        $title = required_param('title',PARAM_TEXT);
        $desc = required_param('desc',PARAM_TEXT);
        $response = mergeSelectedNodes($issuenodeid,$groupid,$ids,$title,$desc);
        break;

	/** DEBATES **/
    case "getdebate":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = getDebate($nodeid, $style);
        break;
    case "getdebateideaconnections":
        $issueid = required_param('issueid',PARAM_ALPHANUMEXT);
    	$response = getDebateIdeaConnections($issueid, $o, $s, $status);
    	break;
    case "getdebateideaconnectionswithlemoning":
        $issueid = required_param('issueid',PARAM_ALPHANUMEXT);
    	$response = getDebateIdeaConnectionsWithLemoning($issueid, $o, $s);
    	break;
    case "getdebateideaconnectionsremoved":
        $issueid = required_param('issueid',PARAM_ALPHANUMEXT);
    	$response = getDebateIdeaConnectionsRemoved($issueid);
    	break;
    case "getdebateministats":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = getDebateMiniStats($nodeid, $style);
        break;
    case "getdebateparticipationstats":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = getDebateParticipationStats($nodeid, $style);
        break;
    case "getdebatecontributionstats":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $response = getDebateContributionStats($nodeid, $style);
        break;
    case "getdebateviewingstats":
        $nodeid = required_param('nodeid',PARAM_ALPHANUMEXT);
        $groupid = required_param('groupid',PARAM_ALPHANUMEXT);
        $response = getDebateViewingStats($nodeid, $groupid);
        break;

	case "getconnectionsfromjsonld" :
		require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/catalyst_jsonld_reader.class.php"));
		$url = required_param('url', PARAM_URL);
		$withhistory = optional_param('withhistory',false,PARAM_BOOL);
		$withvotes = optional_param('withvotes',false,PARAM_BOOL);
		$reader = new catalyst_jsonld_reader();
		$reader = $reader->load($url, $withhistory, $withvotes);
		$response = $reader->connectionSet;
		break;

	case "getnodesfromjsonld" :
		require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/catalyst_jsonld_reader.class.php"));
		$url = required_param('url', PARAM_URL);
		$withhistory = optional_param('withhistory',false,PARAM_BOOL);
		$withvotes = optional_param('withvotes',false,PARAM_BOOL);
		$reader = new catalyst_jsonld_reader();
		$reader = $reader->load($url, $withhistory, $withvotes);
		$response = $reader->nodeSet;
		break;

	case "getalerts" :
		require_once($HUB_FLM->getCodeDirPath("core/io/catalyst/analyticservices.php"));
		require_once($HUB_FLM->getCodeDirPath("core/formats/cipher.php"));
		$issueid = required_param('issueid', PARAM_ALPHANUMEXT);
		$url = required_param('url', PARAM_URL);
		$alerts = optional_param('alerts',"",PARAM_TEXT);
		$timeout = optional_param('timeout',60,PARAM_INT);
		$userids = optional_param('userids','',PARAM_TEXT);

		$response = getAlertsData($issueid,$url,$alerts,$timeout,$userids);
		break;

	/** ODD **/
 	case "gettreedata":
		$fromdate = optional_param('fromdate','',PARAM_ALPHANUMEXT);
		$todate = optional_param('todate','',PARAM_ALPHANUMEXT);
        $response = getTreeData($fromdate, $todate);
 		break;

    default:
        //error as method not defined.
        global $ERROR;
        $ERROR = new Hub_Error;
        $ERROR->createInvalidMethodError();
        include($HUB_FLM->getCodeDirPath("core/formaterror.php"));
        die;
}

// finally format the output based on the format param in url
echo format_output($response);
?>