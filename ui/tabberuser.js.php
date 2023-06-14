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

header('Content-Type: text/javascript;');
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
$countries = getCountryList();
?>

//this list the tabs
var TABS = {"home":true, "data":true, "group":true, "social":true};

var DATAVIZ = {"issue":true, "solution":true, "pro":true, "con":true, "comment":true};

var DEFAULT_TAB = 'home';
var DEFAULT_VIZ = 'list';

var CURRENT_VIZ = DEFAULT_VIZ;
var CURRENT_TAB = DEFAULT_TAB;

var DATA_LOADED = {"home":false, "group":false, "social":false, "issue":false, "solution":false, "pro":false, "con":false, "comment":false};

//define events for clicking on the tabs
var stpHome = setTabPushed.bindAsEventListener($('tab-home-list-obj'),'home');
var stpData = setTabPushed.bindAsEventListener($('tab-data-list-obj'),'data');
var stpGroup = setTabPushed.bindAsEventListener($('tab-group-list-obj'),'group');
var stpSocial = setTabPushed.bindAsEventListener($('tab-social-list-obj'),'social');

var stpIssueList = setTabPushed.bindAsEventListener($('tab-issue-list-obj'),'data-issue');
var stpSolutionList = setTabPushed.bindAsEventListener($('tab-solution-list-obj'),'data-solution');
var stpProList = setTabPushed.bindAsEventListener($('tab-pro-list-obj'),'data-pro');
var stpConList = setTabPushed.bindAsEventListener($('tab-con-list-obj'),'data-con');
var stpCommentList = setTabPushed.bindAsEventListener($('tab-comment-list-obj'),'data-comment');

/**
 *	set which tab to show and load first
 */
Event.observe(window, 'load', function() {

	// add events for clicking on the main tabs
	Event.observe('tab-home','click', stpHome);
	Event.observe('tab-data','click', stpData);
	Event.observe('tab-group','click', stpGroup);
	Event.observe('tab-social','click', stpSocial);

	Event.observe('tab-data-issue','click', stpIssueList);
	Event.observe('tab-data-solution','click', stpSolutionList);
	Event.observe('tab-data-pro','click', stpProList);
	Event.observe('tab-data-con','click', stpConList);
	Event.observe('tab-data-comment','click', stpCommentList);

	setTabPushed($('tab-'+getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ)),getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ));
});

/**
 *	switch between tabs
 */
function setTabPushed(e) {

	var data = $A(arguments);
	var tabID = data[1];

	// get tab and the visualisation from the #
	var parts = tabID.split("-");
	var tab = parts[0];
	var viz="";
	if (parts.length > 1) {
		viz = parts[1];
	}
	var page=1;
	if (parts.length > 2) {
		page = parseInt(parts[2]);
	}

	// Check tab is know else default to default
	if (!TABS.hasOwnProperty(tab)) {
		tab = DEFAULT_TAB;
		viz = DEFAULT_VIZ;
	}

	var i="";
	for (i in TABS){

		if ($("tab-"+i)) {
			if(tab == i){
				if($("tab-"+i)) {
					$("tab-"+i).addClassName("active");
					if ($("tab-content-"+i+"-div")) {
						$("tab-content-"+i+"-div").show();
					}
				}
			} else {
				if($("tab-"+i)) {
					$("tab-"+i).removeClassName("active");
					if ($("tab-content-"+i+"-div")) {
						$("tab-content-"+i+"-div").hide();
					}
				}
			}
		}
	}



	if (tab =="data") {
		if (viz == "") {
			viz = "issue";
		}

		for (i in DATAVIZ){
			if(viz == i){
				if ($("tab-"+tab+"-"+i)) {
					$("tab-"+tab+"-"+i).addClassName("active");
					$("tab-content-"+tab+"-"+i+"-div").show();
					$("tab-content-"+tab+"-"+i).show();
				}
			} else {
				if ($("tab-"+tab+"-"+i)) {
					$("tab-"+tab+"-"+i).removeClassName("active");
					$("tab-content-"+tab+"-"+i+"-div").hide();
					$("tab-content-"+tab+"-"+i).hide();
				}
			}
		}
	}

	CURRENT_TAB = tab;
	CURRENT_VIZ = viz;

	//LOAD DATA IF REQUIRED
	if (tab == "social") {
		if (!DATA_LOADED.social) {
			loadUserHomeNet();
		}
	} else if (tab == "group") {
		$('tab-group').setAttribute("href",'#group');
		Event.stopObserving('tab-group','click');
		Event.observe('tab-group','click', stpGroup);
		if(!DATA_LOADED.group) {
			GROUP_ARGS['start'] = (page-1) * GROUP_ARGS['max'];
			loadmygroups(CONTEXT,GROUP_ARGS);
		}
	} else if (tab == "data") {
		switch(viz){
			case 'issue':
				$('tab-data').setAttribute("href",'#data-issue');
				StopObservingDataTab();
				Event.observe('tab-data','click', stpIssueList);
				if(!DATA_LOADED.issue){
					ISSUE_ARGS['start'] = (page-1) * ISSUE_ARGS['max'];
					loadissues(CONTEXT,ISSUE_ARGS);
				} else {
					updateAddressParameters(ISSUE_ARGS);
				}
				break;
			case 'solution':
				$('tab-data').setAttribute("href",'#data-solution');
				StopObservingDataTab();
				Event.observe('tab-data','click', stpSolutionList);
				if(!DATA_LOADED.solution){
					SOLUTION_ARGS['start'] = (page-1) * SOLUTION_ARGS['max'];
					loadsolutions(CONTEXT,SOLUTION_ARGS);
				} else {
					updateAddressParameters(SOLUTION_ARGS);
				}
				break;
			case 'pro':
				$('tab-data').setAttribute("href",'#data-pro');
				StopObservingDataTab();
				Event.observe('tab-data','click', stpProList);
				if(!DATA_LOADED.pro){
					PRO_ARGS['start'] = (page-1) * PRO_ARGS['max'];
					loadpros(CONTEXT,PRO_ARGS);
				} else {
					updateAddressParameters(PRO_ARGS);
				}
				break;
			case 'con':
				$('tab-data').setAttribute("href",'#data-con');
				StopObservingDataTab();
				Event.observe('tab-data','click', stpConList);
				if(!DATA_LOADED.con){
					CON_ARGS['start'] = (page-1) * CON_ARGS['max'];
					loadcons(CONTEXT,CON_ARGS);
				} else {
					updateAddressParameters(CON_ARGS);
				}
				break;
			case 'comment':
				$('tab-data').setAttribute("href",'#data-comment');
				StopObservingDataTab();
				Event.observe('tab-data','click', stpCommentList);
				if(!DATA_LOADED.comment){
					COMMENT_ARGS['start'] = (page-1) * COMMENT_ARGS['max'];
					loadcomments(CONTEXT,COMMENT_ARGS);
				} else {
					updateAddressParameters(COMMENT_ARGS);
				}
				break;
		}
	}
}

function StopObservingDataTab() {
	Event.stopObserving('tab-data','click');
}

function refreshGroups() {
	loadmygroups(CONTEXT,GROUP_ARGS);
}

function refreshIssues() {
	loadissues(CONTEXT,ISSUE_ARGS);
}

function refreshSolutions() {
	loadsolutions(CONTEXT,SOLUTIONS_ARGS);
}

function refreshPros() {
	loadpros(CONTEXT,PRO_ARGS);
}

function refreshCons() {
	loadcons(CONTEXT,CON_ARGS);
}

function refreshComments() {
	loadcomments(CONTEXT,COMMENT_ARGS);
}

function refreshData() {
	switch(CURRENT_VIZ){
		case 'issue':
			loadissues(CONTEXT,ISSUE_ARGS);
			break;
		case 'solution':
			loadsolutions(CONTEXT,ISSUE_ARGS);
			break;
		case 'pro':
			loadpros(CONTEXT,CON_ARGS);
			break;
		case 'con':
			loadcons(CONTEXT,PRO_ARGS);
			break;
		case 'comment':
			loadcomments(CONTEXT,COMMENT_ARGS);
			break;
		default:
	}
}

// LOAD GROUPS //
/**
 *	load groups for the current user
 */
function loadmygroups(context,args){

	//updateAddressParameters(args);

	$("tab-content-group-list").update(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getmygroups&userid="+args['userid'];

	//alert(reqUrl);

	new Ajax.Request(reqUrl, { method:'get',
		onError: function(error) {
			alert(error);
		},
		onSuccess: function(transport){
			var json = transport.responseText.evalJSON();
			if(json.error){
				alert(json.error[0].message);
				return;
			}
			$("tab-content-group-list").innerHTML = "";
			$("tab-content-group").style.display = 'none';
			$("tab-content-group-admin-list").innerHTML = "";
			$("tab-content-group-admin").style.display = 'none';

			var total = json.groupset[0].groups.length;
			//alert(total);

			if(total > 0){
				var groups = json.groupset[0].groups;
				var count = groups.length;
				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					for (var i=0; i<count; i++) {
						var group = groups[i].group;
						group.searchid = args['searchid'];
					}
				}

				var innerreqUrl = SERVICE_ROOT + "&method=getmyadmingroups&userid="+args['userid'];
				new Ajax.Request(innerreqUrl, { method:'get',
					onError: function(error) {
						alert(error);
					},
					onSuccess: function(innertransport){
						var innerjson = innertransport.responseText.evalJSON();
						if(innerjson.error){
							alert(innerjson.error[0].message);
							return;
						}
						var innertotal = innerjson.groupset[0].groups.length;
						if(innertotal > 0) {
							var admingroups = innerjson.groupset[0].groups;
							for (var i=0; i<innertotal; i++) {
								var group = admingroups[i].group;
								group.searchid = args['searchid'];
							}

							var finalgroups = new Array();
							for (var k=0; k<count; k++) {
								var group = groups[k].group;
								var found = false;
								for (var j=0; j<innertotal; j++) {
									var innergroup = admingroups[j].group;
									if (innergroup.groupid == group.groupid) {
										found = true;
										break;
									}
								}
								if (!found) {
									finalgroups.push(groups[k]);
								}
							}
							$("tab-content-group-admin").style.display = 'block';
							$("tab-content-group").style.display = 'block';

							displayMyGroups($("tab-content-group-admin-list"),admingroups, 1);
							displayMyGroups($("tab-content-group-list"),finalgroups, 1);
						} else {
							$("tab-content-group").style.display = 'block';
							displayGroups($("tab-content-group-list"),groups, 1, 466,180, false, true);
						}
					}
				});
			} else {
				$("tab-content-group").style.display = 'block';
				$("tab-content-group").insert('<?php echo $LNG->WIDGET_NO_GROUPS_FOUND; ?>');
			}
		}
	});
  	DATA_LOADED.group = true;
}

// LOAD LISTS///

/**
 *	load next/previous set of con nodes
 */
function loadcons(context,args){

	var types = "Con";

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}
	updateAddressParameters(args);

	$("tab-content-data-con").update(getLoading("<?php echo $LNG->LOADING_CONS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
  			onSuccess: function(transport){

  				try {
  					var json = transport.responseText.evalJSON();
  				} catch(err) {
  					console.log(err);
  				}

      			if(json.error){
      				alert(json.error[0].message);
      				return;
      			}

				var total = json.nodeset[0].totalno;
				if (CURRENT_TAB == 'data') {
					var currentPage = (json.nodeset[0].start/args["max"]) + 1;
					window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
				}
				var navbar = createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"con")

				$("tab-content-data-con").update(navbar);

				//display nodes
				if(json.nodeset[0].nodes.length > 0){
					//preprosses nodes to add searchid if it is there
					if (args['searchid'] && args['searchid'] != "") {
						var nodes = json.nodeset[0].nodes;
						var count = nodes.length;
						for (var i=0; i<count; i++) {
							var node = nodes[i];
							node.cnode.searchid = args['searchid'];
						}
					}

					var tb3 = new Element("div", {'class':'toolbarrow'});

					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>'};
					tb3.insert(displaySortForm(sortOpts,args,'con',reorderCons));

					$("tab-content-data-con").insert(tb3);
					displayUsersNodes($("tab-content-data-con"),json.nodeset[0].nodes,parseInt(args['start'])+1);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-data-con").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"con"));
				}
    		}
  		});
  	DATA_LOADED.con = true;
}

/**
 *	load next/previous set of pro nodes
 */
function loadpros(context,args){

	var types = "Pro";

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}
	updateAddressParameters(args);

	$("tab-content-data-pro").update(getLoading("<?php echo $LNG->LOADING_PROS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
  			onSuccess: function(transport){

  				try {
  					var json = transport.responseText.evalJSON();
  				} catch(err) {
  					console.log(err);
  				}

      			if(json.error){
      				alert(json.error[0].message);
      				return;
      			}

				var total = json.nodeset[0].totalno;
				if (CURRENT_TAB == 'data') {
					var currentPage = (json.nodeset[0].start/args["max"]) + 1;
					window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
				}
				var navbar = createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"pro")

				$("tab-content-data-pro").update(navbar);

				//display nodes
				if(json.nodeset[0].nodes.length > 0){
					//preprosses nodes to add searchid if it is there
					if (args['searchid'] && args['searchid'] != "") {
						var nodes = json.nodeset[0].nodes;
						var count = nodes.length;
						for (var i=0; i<count; i++) {
							var node = nodes[i];
							node.cnode.searchid = args['searchid'];
						}
					}

					var tb3 = new Element("div", {'class':'toolbarrow'});

					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>'};
					tb3.insert(displaySortForm(sortOpts,args,'pro',reorderPros));

					$("tab-content-data-pro").insert(tb3);
					displayUsersNodes($("tab-content-data-pro"),json.nodeset[0].nodes,parseInt(args['start'])+1);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-data-pro").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"pro"));
				}
    		}
  		});
  	DATA_LOADED.pro = true;
}

/**
 *	load next/previous set of nodes
 */
function loadissues(context,args){
	args['filternodetypes'] = "Issue";
	updateAddressParameters(args);

	$("tab-content-data-issue").update(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
  			onSuccess: function(transport){

  				try {
  					var json = transport.responseText.evalJSON();
  				} catch(err) {
  					console.log(err);
  				}

      			if(json.error){
      				alert(json.error[0].message);
      				return;
      			}

				//display nav
				var total = json.nodeset[0].totalno;
				if (CURRENT_TAB == 'data') {
					var currentPage = (json.nodeset[0].start/args["max"]) + 1;
					window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
				}
				$("tab-content-data-issue").update(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));

				//display nodes
				if(json.nodeset[0].nodes.length > 0){
					//preprosses nodes to add searchid if it is there
					if (args['searchid'] && args['searchid'] != "") {
						var nodes = json.nodeset[0].nodes;
						var count = nodes.length;
						for (var i=0; i<count; i++) {
							var node = nodes[i];
							node.cnode.searchid = args['searchid'];
						}
					}

					var tb3 = new Element("div", {'class':'toolbarrow'});

					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
					tb3.insert(displaySortForm(sortOpts,args,'issue',reorderIssues));

					$("tab-content-data-issue").insert(tb3);

					//displayIssueNodes($("tab-content-data-issue"),json.nodeset[0].nodes,parseInt(args['start'])+1);
					displayIssueNodes(466, 210, $("tab-content-data-issue"),json.nodeset[0].nodes,parseInt(args['start'])+1, true, "issues", 'active', false, true, true);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-data-issue").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
				}
    		}
  		});
  	DATA_LOADED.issue = true;
}

/**
 *	load next/previous set of nodes
 */
function loadsolutions(context,args){
	args['filternodetypes'] = "Solution";
	updateAddressParameters(args);

	$("tab-content-data-solution").update(getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	new Ajax.Request(reqUrl, { method:'get',
  			onSuccess: function(transport){

  				try {
  					var json = transport.responseText.evalJSON();
  				} catch(err) {
  					console.log(err);
  				}

      			if(json.error){
      				alert(json.error[0].message);
      				return;
      			}

				//display nav
				var total = json.nodeset[0].totalno;
				if (CURRENT_TAB == 'data') {
					var currentPage = (json.nodeset[0].start/args["max"]) + 1;
					window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
				}
				$("tab-content-data-solution").update(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"solutions"));

				if(json.nodeset[0].nodes.length > 0){
					//preprosses nodes to add searchid if it is there
					if (args['searchid'] && args['searchid'] != "") {
						var nodes = json.nodeset[0].nodes;
						var count = nodes.length;
						for (var i=0; i<count; i++) {
							var node = nodes[i];
							node.cnode.searchid = args['searchid'];
						}
					}

					var tb3 = new Element("div", {'class':'toolbarrow'});

					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
					tb3.insert(displaySortForm(sortOpts,args,'solution',reorderSolutions));

					$("tab-content-data-solution").insert(tb3);
					displayUsersNodes($("tab-content-data-solution"),json.nodeset[0].nodes,parseInt(args['start'])+1);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-data-solution").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"solutions"));
				}
    		}
  		});
  	DATA_LOADED.solution = true;
}

/**
 *	load next/previous set of comment nodes
 */
function loadcomments(context,args) {
	args['filternodetypes'] = 'Comment';
	updateAddressParameters(args);

	$("tab-content-data-comment").update(getLoading("<?php echo $LNG->LOADING_COMMENTS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);

	//alert(reqUrl);
	new Ajax.Request(reqUrl, { method:'post',
		onSuccess: function(transport){
			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}

			if(json.error){
				alert(json.error[0].message);
				return;
			}

			var count = 0;
			if (json.nodeset[0].totalno) {
				count = json.nodeset[0].totalno;
			}

			var nodes = json.nodeset[0].nodes;

			//display nav
			var total = json.nodeset[0].totalno;
			if (CURRENT_TAB == 'data') {
				var currentPage = (json.nodeset[0].start/args["max"]) + 1;
				window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
			}
			$("tab-content-data-comment").update(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"comments"));

			if(nodes.length > 0){
				//preprosses nodes to add searchid if it is there
				if (args['searchid'] && args['searchid'] != "") {
					var count = nodes.length;
					for (var i=0; i<count; i++) {
						var node = nodes[i];
						node.cnode.searchid = args['searchid'];
					}
				}

				var tb3 = new Element("div", {'class':'toolbarrow'});
				var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>'};
				tb3.insert(displaySortForm(sortOpts,args,'comment',reorderComments));
				$("tab-content-data-comment").insert(tb3);

				displayUsersNodes($("tab-content-data-comment"),nodes,parseInt(args['start'])+1);
			}

			//display nav
			if (total > parseInt( args["max"] )) {
				$("tab-content-data-comment").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"comments"));
			}
		}
	});

  	DATA_LOADED.comment = true;
}

/**
 *	Reorder the challenge tab
 */
function reorderPros(){
 	// change the sort and orderby ARG values
 	PRO_ARGS['start'] = 0;
 	PRO_ARGS['sort'] = $('select-sort-pro').options[$('select-sort-pro').selectedIndex].value;
 	PRO_ARGS['orderby'] = $('select-orderby-pro').options[$('select-orderby-pro').selectedIndex].value;

 	loadpros(CONTEXT,PRO_ARGS);
}

/**
 *	Reorder the org tab
 */
function reorderCons(){
	// change the sort and orderby ARG values
	CON_ARGS['start'] = 0;
	CON_ARGS['sort'] = $('select-sort-con').options[$('select-sort-con').selectedIndex].value;
	CON_ARGS['orderby'] = $('select-orderby-con').options[$('select-orderby-con').selectedIndex].value;

	loadcons(CONTEXT,CON_ARGS);
}

/**
*	Reorder the issue tab
*/
function reorderIssues(){
 	// change the sort and orderby ARG values
 	ISSUE_ARGS['start'] = 0;
 	ISSUE_ARGS['sort'] = $('select-sort-issue').options[$('select-sort-issue').selectedIndex].value;
 	ISSUE_ARGS['orderby'] = $('select-orderby-issue').options[$('select-orderby-issue').selectedIndex].value;

 	loadissues(CONTEXT,ISSUE_ARGS);
}


/**
 *	Reorder the solutions tab
 */
function reorderSolutions(){
	// change the sort and orderby ARG values
	SOLUTION_ARGS['start'] = 0;
	SOLUTION_ARGS['sort'] = $('select-sort-solution').options[$('select-sort-solution').selectedIndex].value;
	SOLUTION_ARGS['orderby'] = $('select-orderby-solution').options[$('select-orderby-solution').selectedIndex].value;

	loadsolutions(CONTEXT,SOLUTION_ARGS);
}

/**
 *	Reorder the users tab
 */
function reorderUsers(){
	// change the sort and orderby ARG values
	USER_ARGS['start'] = 0;
	USER_ARGS['sort'] = $('select-sort-user').options[$('select-sort-user').selectedIndex].value;
	USER_ARGS['orderby'] = $('select-orderby-user').options[$('select-orderby-user').selectedIndex].value;

	loadusers(CONTEXT,USER_ARGS);
}

/**
 *	Reorder the comments tab
 */
function reorderComments(){
	// change the sort and orderby ARG values
	COMMENT_ARGS['start'] = 0;
	COMMENT_ARGS['sort'] = $('select-sort-comment').options[$('select-sort-comment').selectedIndex].value;
	COMMENT_ARGS['orderby'] = $('select-orderby-comment').options[$('select-orderby-comment').selectedIndex].value;

	loadcomments(CONTEXT,COMMENT_ARGS);
}

/**
 *	Filter the pro by search criteria
 */
 function filterSearchPros() {
 	PRO_ARGS['q'] = $('qpro').value;
 	var scope = 'all';
 	PRO_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+PRO_ARGS['userid']+"&type=userpro&format=text&q="+PRO_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					PRO_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.pro = false;
				setTabPushed($('tab-pro-list-obj'),'data-pro');
			}
		});
	} else {
		DATA_LOADED.pro = false;
		setTabPushed($('tab-pro-list-obj'),'data-pro');
	}
 }

/**
 *	Filter the cons by search criteria
 */
function filterSearchCons() {
	CON_ARGS['q'] = $('qcon').value;
	var scope = 'all';
	CON_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+CON_ARGS['userid']+"&type=usercon&format=text&q="+CON_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					CON_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.con = false;
				setTabPushed($('tab-con-list-obj'),'data-con');
			}
		});
	} else {
		DATA_LOADED.con = false;
		setTabPushed($('tab-con-list-obj'),'data-con');
	}
}

/**
 *	Filter the issues by search criteria
 */
function filterSearchIssues() {
	ISSUE_ARGS['q'] = $('qissue').value;
	var scope = 'all';
	ISSUE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+ISSUE_ARGS['userid']+"&type=userissue&format=text&q="+ISSUE_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					ISSUE_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.issue = false;
				setTabPushed($('tab-issue-list-obj'),'data-issue');
			}
		});
	} else {
		DATA_LOADED.issue = false;
		setTabPushed($('tab-issue-list-obj'),'data-issue');
	}
}

/**
 *	Filter the solutions by search criteria
 */
function filterSearchSolutions() {
	SOLUTION_ARGS['q'] = $('qsolution').value;
	var scope = 'all';
	SOLUTION_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+SOLUTION_ARGS['userid']+"&type=usersolution&format=text&q="+SOLUTION_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					SOLUTION_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.solution = false;
				setTabPushed($('tab-solution-list-obj'),'data-solution');
			}
		});
	} else {
		DATA_LOADED.solution = false;
		setTabPushed($('tab-solution-list-obj'),'data-solution');
	}
}

/**
 *	Filter the websites by search criteria
 */
function filterSearchResources() {
	RESOURCE_ARGS['q'] = $('qweb').value;
	var scope = 'all';
	RESOURCE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+RESOURCE_ARGS['userid']+"&type=userresource&format=text&q="+URL_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					RESOURCE_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.resource = false;
				setTabPushed($('tab-resource-list-obj'),'data-resource');
			}
		});
	} else {
		DATA_LOADED.resource = false;
		setTabPushed($('tab-resource-list-obj'),'data-resource');
	}
}

/**
 *	Filter the users by search criteria
 */
function filterSearchComments() {
	COMMENT_ARGS['q'] = $('qcomment').value;
	var scope = 'all';
	COMMENT_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+NODE_ARGS['userid']+"&type=usercomment&format=text&q="+NODE_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					COMMENT_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.comment = false;
				setTabPushed($('tab-comment-list-obj'),'data-comment');
			}
		});
	} else {
		DATA_LOADED.comment = false;
		setTabPushed($('tab-comment-list-obj'),'data-comment');
	}
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2  col-auto'});
    sbTool.insert("<?php echo $LNG->SORT_BY; ?> ");

    var selOrd = new Element("select");
 	Event.observe(selOrd,'change',handler);
    selOrd.id = "select-orderby-"+tab;
    selOrd.className = "toolbar form-select";
    selOrd.name = "orderby";
    selOrd.setAttribute("aria-label","Sort by");
    sbTool.insert(selOrd);
    for(var key in sortOpts){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortOpts[key].valueOf());
        selOrd.insert(opt);
        if(args.orderby == key){
        	opt.selected = true;
        }
    }
    var sortBys = {ASC: '<?php echo $LNG->SORT_ASC; ?>', DESC: '<?php echo $LNG->SORT_DESC; ?>'};
    var sortBy = new Element("select");
 	Event.observe(sortBy,'change',handler);
    sortBy.id = "select-sort-"+tab;
    sortBy.className = "toolbar form-select";
    sortBy.name = "sort";
    sortBy.setAttribute("aria-label","Order by");
    sbTool.insert(sortBy);
    for(var key in sortBys){
        var opt = new Element("option");
        opt.value=key;
        opt.insert(sortBys[key]);
        sortBy.insert(opt);
        if(args.sort == key){
        	opt.selected = true;
        }
    }

    return sbTool;
}

/**
 * Called by the node type popup after node types have been selected.
 */
function setSelectedNodeTypes(types) {
	SELECTED_NODETYPES = types;

	if ($('select-filter-conn')) {
		$('select-filter-conn').options[0].selected = true;
	} else if ($('select-filter-neighbourhood')) {
		$('select-filter-neighbourhood').options[0].selected = true;
	} else if ($('nodetypegroups')) {
		($('nodetypegroups')).options[0].selected = true;
	}
}

/**
 * Called by the link type popup after link types have been selected.
 */
function setSelectedLinkTypes(types) {
	SELECTED_LINKTYES = types;

	if ($('select-filter-conn')) {
		$('select-filter-conn').options[0].selected = true;
	} else if ($('select-filter-neighbourhood')) {
		$('select-filter-neighbourhood').options[0].selected = true;
	} else if ($('linktypegroups')) {
		($('linktypegroups')).options[0].selected = true;
	}
}

/**
 * Called by the users popup after users have been selected.
 */
function setSelectedUsers(types) {
	SELECTED_USERS = types;
}

/**
 * display Nav
 */
function createNav(total, start, count, argArray, context, type){

	var nav = new Element ("div",{'id':'page-nav', 'class':'toolbarrow pb-3' });

	var header = createNavCounter(total, start, count, type);
	nav.insert(header);

	if (total > parseInt( argArray["max"] )) {
		//previous
	    var prevSpan = new Element("span", {'id':"nav-previous", "class": "page-nav page-chevron"});
	    if(start > 0){
			prevSpan.update("<i class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_PREVIOUS_HINT; ?></span>");
	        prevSpan.addClassName("active");
	        Event.observe(prevSpan,"click", function(){
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) - newArr["max"];
	            eval("load"+type+"(context,newArr)");
	        });
	    } else {
			prevSpan.update("<i disabled class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_PREVIOUS_HINT; ?></span>");
	        prevSpan.addClassName("inactive");
	    }

	    //pages
	    var pageSpan = new Element("span", {'id':"nav-pages", "class": "page-nav"});
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i<totalPages+1; i++){
	    	var page = new Element("span", {'class':"nav-page"}).insert(i);
	    	if(i != currentPage){
		    	page.addClassName("active");
		    	var newArr = Object.clone(argArray);
		    	newArr["start"] = newArr["max"] * (i-1) ;
		    	Event.observe(page,"click", Pages.next.bindAsEventListener(Pages,type,context,newArr));
	    	} else {
	    		page.addClassName("currentpage");
	    	}
	    	pageSpan.insert(page);
	    }

	    //next
	    var nextSpan = new Element("span", {'id':"nav-next", "class": "page-nav page-chevron"});
	    if(parseInt(start)+parseInt(count) < parseInt(total)){
			nextSpan.update("<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NEXT_HINT; ?></span>");
	        nextSpan.addClassName("active");
	        Event.observe(nextSpan,"click", function(){
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) + parseInt(newArr["max"]);
	            eval("load"+type+"(context, newArr)");
	        });
	    } else {
			nextSpan.update("<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\" disabled></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_NEXT_HINT; ?></span>");
	        nextSpan.addClassName("inactive");
	    }

	    if( start>0 || (parseInt(start)+parseInt(count) < parseInt(total))){
	    	nav.insert(prevSpan).insert(pageSpan).insert(nextSpan);
	    }
	}

	return nav;
}

/**
 * display nav header
 */
function createNavCounter(total, start, count, type){

    if(count != 0){
    	var objH = new Element("span",{'class':'nav'});
    	var s1 = parseInt(start)+1;
    	var s2 = parseInt(start)+parseInt(count);
        objH.insert("<b>" + s1 + " <?php echo $LNG->LIST_NAV_TO; ?> " + s2 + " (" + total + ")</b>");
    } else {
    	var objH = new Element("span");
		switch(type){
			case 'con':
				objH.insert("<p><b><?php echo $LNG->LIST_NAV_USER_NO_CON; ?></b></p>");
				break;
			case 'pro':
				objH.insert("<p><b><?php echo $LNG->LIST_NAV_USER_NO_PRO; ?></b></p>");
				break;
			case 'issues':
				objH.insert("<p><b><?php echo $LNG->LIST_NAV_USER_NO_ISSUE; ?></b></p>");
				break;
			case 'solutions':
				objH.insert("<p><b><?php echo $LNG->LIST_NAV_USER_NO_SOLUTION; ?></b></p>");
				break;
			case 'comment':
				objH.insert("<p><b><?php echo $LNG->LIST_NAV_USER_NO_COMMENT; ?></b></p>");
				break;
		}
    }
    return objH;
}

var Pages = {
	next: function(e){
		var data = $A(arguments);
		eval("load"+data[1]+"(data[2],data[3])");
	}
};

/**
 * load JS file for creating the connection network (applet) for a user's social network
 */
function loadUserHomeNet(){
	addScriptDynamically('<?php echo $HUB_FLM->getCodeWebPath("ui/networkmaps/social-user-net.js.php"); ?>', 'social-user-script');
}