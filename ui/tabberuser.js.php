<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2024 The Open University UK                            *
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
var stpHome = function(event) { setTabPushed.call(document.getElementById('tab-home-list-obj'), event, 'home'); };
var stpData = function(event) { setTabPushed.call(document.getElementById('tab-data-list-obj'), event, 'data'); };
var stpGroup = function(event) { setTabPushed.call(document.getElementById('tab-group-list-obj'), event, 'group'); };
var stpSocial = function(event) { setTabPushed.call(document.getElementById('tab-social-list-obj'), event, 'social'); };

var stpIssueList = function(event) { setTabPushed.call(document.getElementById('tab-issue-list-obj'), event, 'data-issue'); };
var stpSolutionList = function(event) { setTabPushed.call(document.getElementById('tab-solution-list-obj'), event, 'data-solution'); };
var stpProList = function(event) { setTabPushed.call(document.getElementById('tab-pro-list-obj'), event, 'data-pro'); };
var stpConList = function(event) { setTabPushed.call(document.getElementById('tab-con-list-obj'), event, 'data-con'); };
var stpCommentList = function(event) { setTabPushed.call(document.getElementById('tab-comment-list-obj'), event, 'data-comment'); };

/**
 *	set which tab to show and load first
 */
window.addEventListener('load', function() {

	// add events for clicking on the main tabs
	document.getElementById('tab-home').addEventListener('click', stpHome);
	document.getElementById('tab-data').addEventListener('click', stpData);
	document.getElementById('tab-group').addEventListener('click', stpGroup);
	document.getElementById('tab-social').addEventListener('click', stpSocial);

	document.getElementById('tab-data-issue').addEventListener('click', stpIssueList);
	document.getElementById('tab-data-solution').addEventListener('click', stpSolutionList);
	document.getElementById('tab-data-pro').addEventListener('click', stpProList);
	document.getElementById('tab-data-con').addEventListener('click', stpConList);
	document.getElementById('tab-data-comment').addEventListener('click', stpCommentList);

	setTabPushed(document.getElementById('tab-'+getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ)),getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ));
});

/**
 *	switch between tabs
 */
function setTabPushed(e, tabID) {

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
		const tabobj = document.getElementById('tab-'+i);
		const tabContent = document.getElementById('tab-content-'+i+'-div');
		if (tabobj) {
			if(tab == i){
				tabobj.classList.add("active");				
				if (tabContent) {
					tabContent.style.display = "block";
				}
			} else {
				tabobj.classList.remove("active");
				if (tabContent) {
					tabContent.style.display = "none";
				}
			}
		}
	}

	if (tab =="data") {
		if (viz == "") {
			viz = "issue";
		}

		for (i in DATAVIZ){
			const tabtab = document.getElementById('tab-'+tab+'-'+i);
			const tabcontentdiv = document.getElementById('tab-content-'+tab+'-'+i+'-div');
			const tabcontent = document.getElementById('tab-content-'+tab+'-'+i);
			if(viz == i){
				if (tabtab) {
					tabtab.classList.add("active");
					tabcontentdiv.style.display = "block";
					tabcontent.style.display = "block";
				}
			} else {
				if (tabtab) {
					tabtab.classList.remove("active");
					tabcontentdiv.style.display = "none";
					tabcontent.style.display = "none";
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
		const tabgroup  = document.getElementById('tab-group');
		tabgroup.setAttribute("href",'#group');
		tabgroup.onclick = stpGroup;
		if(!DATA_LOADED.group) {
			GROUP_ARGS['start'] = (page-1) * GROUP_ARGS['max'];
			loadmygroups(CONTEXT,GROUP_ARGS);
		}
	} else if (tab == "data") {
		switch(viz){
			case 'issue':
				const tabdata = document.getElementById('tab-data');
				tabdata.setAttribute("href",'#data-issue');
				tabdata.onclick = stpIssueList;
				if(!DATA_LOADED.issue){
					ISSUE_ARGS['start'] = (page-1) * ISSUE_ARGS['max'];
					loadissues(CONTEXT,ISSUE_ARGS);
				} else {
					updateAddressParameters(ISSUE_ARGS);
				}
				break;
			case 'solution':
				const tabdata = document.getElementById('tab-data');
				tabdata.setAttribute("href",'#data-solution');
				tabdata.onclick', stpSolutionList;
				if(!DATA_LOADED.solution){
					SOLUTION_ARGS['start'] = (page-1) * SOLUTION_ARGS['max'];
					loadsolutions(CONTEXT,SOLUTION_ARGS);
				} else {
					updateAddressParameters(SOLUTION_ARGS);
				}
				break;
			case 'pro':
				const tabdata = document.getElementById('tab-data');
				tabdata.setAttribute("href",'#data-pro');
				tabdata.onclick = stpProList;
				if(!DATA_LOADED.pro){
					PRO_ARGS['start'] = (page-1) * PRO_ARGS['max'];
					loadpros(CONTEXT,PRO_ARGS);
				} else {
					updateAddressParameters(PRO_ARGS);
				}
				break;
			case 'con':
				const tabdata = document.getElementById('tab-data');
				tabdata.setAttribute("href",'#data-con');
				tabdata.onclick = stpConList;
				if(!DATA_LOADED.con){
					CON_ARGS['start'] = (page-1) * CON_ARGS['max'];
					loadcons(CONTEXT,CON_ARGS);
				} else {
					updateAddressParameters(CON_ARGS);
				}
				break;
			case 'comment':
				const tabdata = document.getElementById('tab-data');
				tabdata.setAttribute("href",'#data-comment');
				tabdata.onclick = stpCommentList;
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
async function loadmygroups(context,args){

	//updateAddressParameters(args);

	const tabcontentgrouplist = document.getElementById("tab-content-group-list");
	tabcontentgrouplist.innerHTML = "";
	tabcontentgrouplist.appendChild(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getmygroups&userid="+args['userid'];
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		document.getElementById("tab-content-group-list").innerHTML = "";
		document.getElementById("tab-content-group").style.display = 'none';
		document.getElementById("tab-content-group-admin-list").innerHTML = "";
		document.getElementById("tab-content-group-admin").style.display = 'none';

		var total = json.groupset[0].groups.length;
		//alert(total);

		if(total > 0){
			var groups = json.groupset[0].groups;
			var count = groups.length;
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				for (var i=0; i < count; i++) {
					var group = groups[i].group;
					group.searchid = args['searchid'];
				}
			}

			var innerreqUrl = SERVICE_ROOT + "&method=getmyadmingroups&userid="+args['userid'];
			try {
				const json = await makeAPICall(innerreqUrl, 'GET');
				if (json.error) {
					alert(json.error[0].message);
					return;
				}			
				var innerjson = innertransport.responseText.evalJSON();
				if(innerjson.error){
					alert(innerjson.error[0].message);
					return;
				}
				var innertotal = innerjson.groupset[0].groups.length;
				if(innertotal > 0) {
					var admingroups = innerjson.groupset[0].groups;
					for (var i=0; i < innertotal; i++) {
						var group = admingroups[i].group;
						group.searchid = args['searchid'];
					}

					var finalgroups = new Array();
					for (var k=0; k < count; k++) {
						var group = groups[k].group;
						var found = false;
						for (var j=0; j < innertotal; j++) {
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
					document.getElementById("tab-content-group-admin").style.display = 'block';
					document.getElementById("tab-content-group").style.display = 'block';

					displayMyGroups(document.getElementById("tab-content-group-admin-list"),admingroups, 1);
					displayMyGroups(document.getElementById("tab-content-group-list"),finalgroups, 1);
				} else {
					document.getElementById("tab-content-group").style.display = 'block';
					displayGroups(document.getElementById("tab-content-group-list"),groups, 1, 466,180, false, true);
				}
			} catch (innererr) {
				alert("There was an error: "+innererr.message);
				console.log(innererr)
			}
		} else {
			document.getElementById("tab-content-group").style.display = 'block';
			document.getElementById("tab-content-group").innerHTML += '<?php echo $LNG->WIDGET_NO_GROUPS_FOUND; ?>';
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
  	DATA_LOADED.group = true;
}

// LOAD LISTS///

/**
 *	load next/previous set of con nodes
 */
async function loadcons(context,args){

	var types = "Con";

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}
	updateAddressParameters(args);

	const tabcontentdatacon = document.getElementById("tab-content-data-con");
	tabcontentdatacon.innerHTML = "";
	tabcontentdatacon.appendChild(getLoading("<?php echo $LNG->LOADING_CONS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		var total = json.nodeset[0].totalno;
		if (CURRENT_TAB == 'data') {
			var currentPage = (json.nodeset[0].start/args["max"]) + 1;
			window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
		}
		var navbar = createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"con")

		document.getElementById("tab-content-data-con").appendChild(navbar);

		//display nodes
		if(json.nodeset[0].nodes.length > 0){
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				var nodes = json.nodeset[0].nodes;
				var count = nodes.length;
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					node.cnode.searchid = args['searchid'];
				}
			}

			var tb3 = new Element("div", {'class':'toolbarrow'});

			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>'};
			tb3.insert(displaySortForm(sortOpts,args,'con',reorderCons));

			document.getElementById("tab-content-data-con").appendChild(tb3);
			displayUsersNodes(document.getElementById("tab-content-data-con"),json.nodeset[0].nodes,parseInt(args['start'])+1);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			document.getElementById("tab-content-data-con").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"con"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	DATA_LOADED.con = true;
}

/**
 *	load next/previous set of pro nodes
 */
async function loadpros(context,args){

	var types = "Pro";

	if (args['filternodetypes'] == "" || types.indexOf(args['filternodetypes']) == -1) {
		args['filternodetypes'] = types;
	}
	updateAddressParameters(args);

	const tabcontentdatapro = document.getElementById("tab-content-data-pro");
	tabcontentdatapro.innerHTML = "";
	tabcontentdatapro.appendChild(getLoading("<?php echo $LNG->LOADING_PROS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		var total = json.nodeset[0].totalno;
		if (CURRENT_TAB == 'data') {
			var currentPage = (json.nodeset[0].start/args["max"]) + 1;
			window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
		}
		var navbar = createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"pro")

		const tabcontentdatapro = document.getElementById("tab-content-data-pro");
		tabcontentdatapro.innerHTML = "";
		tabcontentdatapro.appanedChild(navbar);

		//display nodes
		if(json.nodeset[0].nodes.length > 0){
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				var nodes = json.nodeset[0].nodes;
				var count = nodes.length;
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					node.cnode.searchid = args['searchid'];
				}
			}

			var tb3 = new Element("div", {'class':'toolbarrow'});

			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>'};
			tb3.insert(displaySortForm(sortOpts,args,'pro',reorderPros));

			const tabcontentdatapro = document.getElementById("tab-content-data-pro");
			tabcontentdatapro.appendChild(tb3);
			displayUsersNodes(tabcontentdatapro,json.nodeset[0].nodes,parseInt(args['start'])+1);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			const tabcontentdatapro = document.getElementById("tab-content-data-pro");
			tabcontentdatapro.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"pro"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	DATA_LOADED.pro = true;
}

/**
 *	load next/previous set of nodes
 */
async function loadissues(context,args){
	args['filternodetypes'] = "Issue";
	updateAddressParameters(args);

	const tabcontentdataissue = document.getElementById("tab-content-data-issue");
	tabcontentdataissue.innerHTML = "";	
	tabcontentdataissue.appendChild(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		//display nav
		var total = json.nodeset[0].totalno;
		if (CURRENT_TAB == 'data') {
			var currentPage = (json.nodeset[0].start/args["max"]) + 1;
			window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
		}

		const tabcontentdataissue = document.getElementById("tab-content-data-issue");
		tabcontentdataissue.innerHTML = "";
		tabcontentdataissue.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));

		//display nodes
		if(json.nodeset[0].nodes.length > 0){
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				var nodes = json.nodeset[0].nodes;
				var count = nodes.length;
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					node.cnode.searchid = args['searchid'];
				}
			}

			var tb3 = new Element("div", {'class':'toolbarrow'});

			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
			tb3.insert(displaySortForm(sortOpts,args,'issue',reorderIssues));

			document.getElementById("tab-content-data-issue").appendChild(tb3);

			//displayIssueNodes(document.getElementById("tab-content-data-issue"),json.nodeset[0].nodes,parseInt(args['start'])+1);
			displayIssueNodes(466, 210, document.getElementById("tab-content-data-issue"),json.nodeset[0].nodes,parseInt(args['start'])+1, true, "issues", 'active', false, true, true);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			document.getElementById("tab-content-data-issue").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	DATA_LOADED.issue = true;
}

/**
 *	load next/previous set of nodes
 */
async function loadsolutions(context,args){
	args['filternodetypes'] = "Solution";
	updateAddressParameters(args);

	const tabcontentdatasolution = document.getElementById("tab-content-data-solution");
	tabcontentdatasolution.innerHTML = "";
	tabcontentdatasolution.appendChild(getLoading("<?php echo $LNG->LOADING_SOLUTIONS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		//display nav
		var total = json.nodeset[0].totalno;
		if (CURRENT_TAB == 'data') {
			var currentPage = (json.nodeset[0].start/args["max"]) + 1;
			window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
		}
		const tabcontentdatasolution = document.getElementById("tab-content-data-solution");
		tabcontentdatasolution.innerHTML = "";
		tabcontentdatasolution.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"solutions"));

		if(json.nodeset[0].nodes.length > 0){
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				var nodes = json.nodeset[0].nodes;
				var count = nodes.length;
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					node.cnode.searchid = args['searchid'];
				}
			}

			var tb3 = new Element("div", {'class':'toolbarrow'});

			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
			tb3.insert(displaySortForm(sortOpts,args,'solution',reorderSolutions));

			document.getElementById("tab-content-data-solution").appendChild(tb3);
			displayUsersNodes(document.getElementById("tab-content-data-solution"),json.nodeset[0].nodes,parseInt(args['start'])+1);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			document.getElementById("tab-content-data-solution").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"solutions"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
  	DATA_LOADED.solution = true;
}

/**
 *	load next/previous set of comment nodes
 */
async function loadcomments(context,args) {
	args['filternodetypes'] = 'Comment';
	updateAddressParameters(args);

	const tabcontentdatacomment = document.getElementById("tab-content-data-comment");
	tabcontentdatacomment.innerHTML = "";
	tabcontentdatacomment.appendChild(getLoading("<?php echo $LNG->LOADING_COMMENTS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'POST');
		if (json.error) {
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
		const tabcontentdatacomment = document.getElementById("tab-content-data-comment");
		tabcontentdatacomment.innerHTML = "";
		tabcontentdatacomment.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"comments"));

		if(nodes.length > 0){
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				var count = nodes.length;
				for (var i=0; i < count; i++) {
					var node = nodes[i];
					node.cnode.searchid = args['searchid'];
				}
			}

			var tb3 = new Element("div", {'class':'toolbarrow'});
			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>'};
			tb3.insert(displaySortForm(sortOpts,args,'comment',reorderComments));
			document.getElementById("tab-content-data-comment").appendChild(tb3);

			displayUsersNodes(document.getElementById("tab-content-data-comment"),nodes,parseInt(args['start'])+1);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			document.getElementById("tab-content-data-comment").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"comments"));
		}

	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	DATA_LOADED.comment = true;
}

/**
 *	Reorder the challenge tab
 */
function reorderPros(){
 	// change the sort and orderby ARG values
 	PRO_ARGS['start'] = 0;
 	PRO_ARGS['sort'] = document.getElementById('select-sort-pro').options[document.getElementById('select-sort-pro').selectedIndex].value;
 	PRO_ARGS['orderby'] = document.getElementById('select-orderby-pro').options[document.getElementById('select-orderby-pro').selectedIndex].value;

 	loadpros(CONTEXT,PRO_ARGS);
}

/**
 *	Reorder the org tab
 */
function reorderCons(){
	// change the sort and orderby ARG values
	CON_ARGS['start'] = 0;
	CON_ARGS['sort'] = document.getElementById('select-sort-con').options[document.getElementById('select-sort-con').selectedIndex].value;
	CON_ARGS['orderby'] = document.getElementById('select-orderby-con').options[document.getElementById('select-orderby-con').selectedIndex].value;

	loadcons(CONTEXT,CON_ARGS);
}

/**
*	Reorder the issue tab
*/
function reorderIssues(){
 	// change the sort and orderby ARG values
 	ISSUE_ARGS['start'] = 0;
 	ISSUE_ARGS['sort'] = document.getElementById('select-sort-issue').options[document.getElementById('select-sort-issue').selectedIndex].value;
 	ISSUE_ARGS['orderby'] = document.getElementById('select-orderby-issue').options[document.getElementById('select-orderby-issue').selectedIndex].value;

 	loadissues(CONTEXT,ISSUE_ARGS);
}


/**
 *	Reorder the solutions tab
 */
function reorderSolutions(){
	// change the sort and orderby ARG values
	SOLUTION_ARGS['start'] = 0;
	SOLUTION_ARGS['sort'] = document.getElementById('select-sort-solution').options[document.getElementById('select-sort-solution').selectedIndex].value;
	SOLUTION_ARGS['orderby'] = document.getElementById('select-orderby-solution').options[document.getElementById('select-orderby-solution').selectedIndex].value;

	loadsolutions(CONTEXT,SOLUTION_ARGS);
}

/**
 *	Reorder the users tab
 */
function reorderUsers(){
	// change the sort and orderby ARG values
	USER_ARGS['start'] = 0;
	USER_ARGS['sort'] = document.getElementById('select-sort-user').options[document.getElementById('select-sort-user').selectedIndex].value;
	USER_ARGS['orderby'] = document.getElementById('select-orderby-user').options[document.getElementById('select-orderby-user').selectedIndex].value;

	loadusers(CONTEXT,USER_ARGS);
}

/**
 *	Reorder the comments tab
 */
function reorderComments(){
	// change the sort and orderby ARG values
	COMMENT_ARGS['start'] = 0;
	COMMENT_ARGS['sort'] = document.getElementById('select-sort-comment').options[document.getElementById('select-sort-comment').selectedIndex].value;
	COMMENT_ARGS['orderby'] = document.getElementById('select-orderby-comment').options[document.getElementById('select-orderby-comment').selectedIndex].value;

	loadcomments(CONTEXT,COMMENT_ARGS);
}

/**
 *	Filter the pro by search criteria
 */
 async function filterSearchPros() {
 	PRO_ARGS['q'] = document.getElementById('qpro').value;
 	var scope = 'all';
 	PRO_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+PRO_ARGS['userid']+"&type=userpro&format=text&q="+PRO_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}
			var searchid = transport.responseText;
			if (searchid != "") {
				PRO_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.pro = false;
			setTabPushed(document.getElementById('tab-pro-list-obj'),'data-pro');

		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.pro = false;
		setTabPushed(document.getElementById('tab-pro-list-obj'),'data-pro');
	}
 }

/**
 *	Filter the cons by search criteria
 */
async function filterSearchCons() {
	CON_ARGS['q'] = document.getElementById('qcon').value;
	var scope = 'all';
	CON_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+CON_ARGS['userid']+"&type=usercon&format=text&q="+CON_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}		
			var searchid = transport.responseText;
			if (searchid != "") {
				CON_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.con = false;
			setTabPushed(document.getElementById('tab-con-list-obj'),'data-con');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.con = false;
		setTabPushed(document.getElementById('tab-con-list-obj'),'data-con');
	}
}

/**
 *	Filter the issues by search criteria
 */
async function filterSearchIssues() {
	ISSUE_ARGS['q'] = document.getElementById('qissue').value;
	var scope = 'all';
	ISSUE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+ISSUE_ARGS['userid']+"&type=userissue&format=text&q="+ISSUE_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}		
			var searchid = transport.responseText;
			if (searchid != "") {
				ISSUE_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.issue = false;
			setTabPushed(document.getElementById('tab-issue-list-obj'),'data-issue');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.issue = false;
		setTabPushed(document.getElementById('tab-issue-list-obj'),'data-issue');
	}
}

/**
 *	Filter the solutions by search criteria
 */
async function filterSearchSolutions() {
	SOLUTION_ARGS['q'] = document.getElementById('qsolution').value;
	var scope = 'all';
	SOLUTION_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+SOLUTION_ARGS['userid']+"&type=usersolution&format=text&q="+SOLUTION_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			var searchid = transport.responseText;
			if (searchid != "") {
				SOLUTION_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.solution = false;
			setTabPushed(document.getElementById('tab-solution-list-obj'),'data-solution');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.solution = false;
		setTabPushed(document.getElementById('tab-solution-list-obj'),'data-solution');
	}
}

/**
 *	Filter the websites by search criteria
 */
async function filterSearchResources() {
	RESOURCE_ARGS['q'] = document.getElementById('qweb').value;
	var scope = 'all';
	RESOURCE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+RESOURCE_ARGS['userid']+"&type=userresource&format=text&q="+URL_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}			
			var searchid = transport.responseText;
			if (searchid != "") {
				RESOURCE_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.resource = false;
			setTabPushed(document.getElementById('tab-resource-list-obj'),'data-resource');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.resource = false;
		setTabPushed(document.getElementById('tab-resource-list-obj'),'data-resource');
	}
}

/**
 *	Filter the users by search criteria
 */
async function filterSearchComments() {
	COMMENT_ARGS['q'] = document.getElementById('qcomment').value;
	var scope = 'all';
	COMMENT_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&typeitemid="+NODE_ARGS['userid']+"&type=usercomment&format=text&q="+NODE_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}		
			var searchid = transport.responseText;
			if (searchid != "") {
				COMMENT_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.comment = false;
			setTabPushed(document.getElementById('tab-comment-list-obj'),'data-comment');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.comment = false;
		setTabPushed(document.getElementById('tab-comment-list-obj'),'data-comment');
	}
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2  col-auto'});
    sbTool.insert("<?php echo $LNG->SORT_BY; ?> ");

    var selOrd = new Element("select");
 	selOrd.onchange = handler;
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
 	sortBy.onchange = handler;
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

	const selectfiltercon = document.getElementById('select-filter-conn');
	const selectfilterneighbourhood = document.getElementById('select-filter-neighbourhood');
	const nodetypegroups = document.getElementById('nodetypegroups');
	if (selectfiltercon) {
		selectfiltercon.options[0].selected = true;
	} else if (selectfilterneighbourhood) {
		selectfilterneighbourhood.options[0].selected = true;
	} else if (nodetypegroups) {
		nodetypegroups.options[0].selected = true;
	}
}

/**
 * Called by the link type popup after link types have been selected.
 */
function setSelectedLinkTypes(types) {
	SELECTED_LINKTYES = types;

	const selectfiltercon = document.getElementById('select-filter-conn');
	const selectfilterneighbourhood = document.getElementById('select-filter-neighbourhood');
	const linktypegroups = document.getElementById('linktypegroups');
	if (selectfiltercon) {
		selectfiltercon.options[0].selected = true;
	} else if (selectfilterneighbourhood) {
		selectfilterneighbourhood.options[0].selected = true;
	} else if (linktypegroups) {
		linktypegroups.options[0].selected = true;
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

	var pageNav = new Element ("nav",{'aria-label':'Page navigation' }); 
	var pageUL = new Element ("ul",{'class':'pagination' }); 

	if (total > parseInt( argArray["max"] )) {
		//previous
	    var prevSpan = new Element("li", {'id':"nav-previous", "class": "page-link"});
	    if(start > 0){
			prevSpan.innerHTML = "<i class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_PREVIOUS_HINT; ?></span>";
	        prevSpan.classList.add("active");
	        prevSpan.onclick =  function() {
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) - newArr["max"];
	            eval("load"+type+"(context,newArr)");
	        };
	    } else {
			prevSpan.innerHTML = "<i disabled class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_PREVIOUS_HINT; ?></span>";
	        prevSpan.classList.add("inactive");
	    }
		
		pageUL.insert(prevSpan);

	    //pages
	    var pageSpan = new Element("span", {'id':"nav-pages", "class": "page-nav"});
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages +1; i++){
	    	var page = new Element("li", {'class':"page-link"}).insert(i);
	    	if(i != currentPage){
		    	page.classList.add("active");
		    	var newArr = Object.clone(argArray);
		    	newArr["start"] = newArr["max"] * (i-1) ;
				page.addEventListener("click", function(event) {
    				Pages.next.call(Pages, type, context, newArr, event);
				});
	    	} else {
	    		page.classList.add("currentpage");
	    	}
	    	pageUL.insert(page);
	    }

	    //next
	    var nextSpan = new Element("li", {'id':"nav-next", "class": "page-link"});
	    if(parseInt(start)+parseInt(count) < parseInt(total)){
			nextSpan.innerHTML = "<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NEXT_HINT; ?></span>";
	        nextSpan.classList.add("active");
	        nextSpan.onclick = function() {
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) + parseInt(newArr["max"]);
	            eval("load"+type+"(context, newArr)");
	        };
	    } else {
			nextSpan.innerHTML = "<i class=\"fas fa-chevron-right fa-lg\" aria-hidden=\"true\" disabled></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_NEXT_HINT; ?></span>";
	        nextSpan.classList.add("inactive");
	    }
		pageUL.insert(nextSpan);

	    if( start>0 || (parseInt(start)+parseInt(count) < parseInt(total))){
			pageNav.insert(pageUL);
			nav.insert(pageNav);
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
        objH.insert("<strong>" + s1 + " <?php echo $LNG->LIST_NAV_TO; ?> " + s2 + " (" + total + ")</strong>");
    } else {
    	var objH = new Element("span");
		switch(type){
			case 'con':
				objH.insert("<p><strong><?php echo $LNG->LIST_NAV_USER_NO_CON; ?></strong></p>");
				break;
			case 'pro':
				objH.insert("<p><strong><?php echo $LNG->LIST_NAV_USER_NO_PRO; ?></strong></p>");
				break;
			case 'issues':
				objH.insert("<p><strong><?php echo $LNG->LIST_NAV_USER_NO_ISSUE; ?></strong></p>");
				break;
			case 'solutions':
				objH.insert("<p><strong><?php echo $LNG->LIST_NAV_USER_NO_SOLUTION; ?></strong></p>");
				break;
			case 'comment':
				objH.insert("<p><strong><?php echo $LNG->LIST_NAV_USER_NO_COMMENT; ?></strong></p>");
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