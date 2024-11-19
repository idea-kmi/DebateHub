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
var TABS = {"home":true, "group":true, "issue":true };
var VIZ = {"list":true};
var DATA_LOADED = {"home":false, "group":false, "issue":false};

var DEFAULT_TAB = 'home';
var DEFAULT_VIZ = 'list';

var CURRENT_VIZ = DEFAULT_VIZ;
var CURRENT_TAB = DEFAULT_TAB;

var NEGATIVE_LINKGROUP_NAME = "Negative";
var POSITIVE_LINKGROUP_NAME = "Positive";
var NEUTRAL_LINKGROUP_NAME = "Neutral";

//define events for clicking on the tabs
var stpHomeList = function(event) { setTabPushed.call(document.getElementById('tab-home-list-obj'), event, 'home-list'); };
var stpIssueList = function(event) { setTabPushed.call(document.getElementById('tab-issue-list-obj'), event, 'issue-list'); };
var stpGroupList = function(event) { setTabPushed.call(document.getElementById('tab-group-list-obj'), event, 'group-list'); };

/**
 *	set which tab to show and load first
 */
window.addEventListener('load', function() {

	document.getElementById('tab-home').addEventListener('click', stpHomeList);
	document.getElementById('tab-issue').addEventListener('click', stpIssueList);
	document.getElementById('tab-group').addEventListener('click', stpGroupList);

	setTabPushed(document.getElementById(''tab-'+getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ)), getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ));
});

/**
 *	switch between tabs
 */
function setTabPushed(e, tabID) {

	// Social Sign On bug - returns strange #_=_ when calling index page
	if (tabID == '_=_') {
		tabID = 'home-overview';
		window.location.hash = tabID;
		if (typeof window.history.replaceState == 'function') {
			window.history.replaceState("string", "Title", "#home-overview");
		}
	}

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
		const tabi = document.getElementById('tab-'+i);
		if(tab == i){
			if(tabi) {
				tabi.classList.remove("unselected");
				tabi..classList.add("current");
			}
		} else {
			if(tabi) {
				tabi.classList.remove("current");
				tabi.classList.add("unselected");
			}
		}
		const tabcontenti = document.getElementById('tab-content-'+i+'-div');
		if(tab == i){
			if (tabcontenti) {
				tabcontenti.style.display = "block";
			}
		} else {
			if (tabcontenti) {
				tabcontenti.style.display = "none";
			}
		}
	}

	CURRENT_TAB = tab;
	CURRENT_VIZ = viz;

	switch(viz){
		case 'list':
			switch(tab) {
				case 'home':
					const tabhome = document.getElementById('tab-home');
					tabhome.setAttribute("href",'#home-list');
					tabhome.onclick'= stpHomeList;
					break;
				case 'issue':
					const tabissue = document.getElementById('tab-issue');
					tabissue.setAttribute("href",'#issue-list');
					tabissue.onclick = stpIssueList;
					if(!DATA_LOADED.issue){
						ISSUE_ARGS['start'] = (page-1) * ISSUE_ARGS['max'];
						loadissues(CONTEXT,ISSUE_ARGS);
					} else {
						updateAddressParameters(ISSUE_ARGS);
					}
					break;
				case 'group':
					const tabgroup = document.getElementById('tab-group');
					tabgroup.setAttribute("href",'#group-list');
					tabgroup.onclick = stpGroupList;
					if(!DATA_LOADED.group) {
						GROUP_ARGS['start'] = (page-1) * GROUP_ARGS['max'];
						loadgroups(CONTEXT,GROUP_ARGS);
					} else {
						updateAddressParameters(GROUP_ARGS);
					}
					break;
				}
			break;

		default:
			//alert("default");
	}
}

/**
 *	Called by forms to refresh the issues view
 */
function refreshIssues() {
	loadissues(CONTEXT,ISSUE_ARGS);
}

/**
 *	Called by forms to refresh the groups view
 */
function refreshGroups() {
	loadgroups(CONTEXT,GROUP_ARGS);
}

// LOAD LISTS///
/**
 *	load next/previous set of nodes
 */
async function loadissues(context,args){
	args['filternodetypes'] = "Issue";

	updateAddressParameters(args);

	const tabcontentissuelist = document.getElementById("tab-content-issue-list");
	tabcontentissuelist.innerHTML = "";
	tabcontentissuelist.appendChild(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		//set the count in tab header
		//document.getElementById('issue-list-count').innerHTML = "";
		//document.getElementById('issue-list-count').appendChild("("+json.nodeset[0].totalno+")");

		//document.getElementById('issuebuttons').innerHTML = "";

		//display nav
		var total = json.nodeset[0].totalno;
		if (CURRENT_VIZ == 'list') {
			var currentPage = (json.nodeset[0].start/args["max"]) + 1;
			window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
		}
		const tabcontentissuelist = document.getElementById("tab-content-issue-list");
		tabcontenteissuelist.innerHTML = "";
		tabcontentissuelist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));

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

			document.getElementById("tab-content-issue-list").appendChild(tb3);

			displayIssueNodes(466, 210, document.getElementById("tab-content-issue-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true, "issues", 'active', false, true, true);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			document.getElementById("tab-content-issue-list").appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}

  	DATA_LOADED.issue = true;
  	DATA_LOADED.issuesimile = false;
}

/**
 *	load next/previous set of users
 */
async function loadgroups(context,args){

	updateAddressParameters(args);

	const tabcontentgrouplist = document.getElementById("tab-content-group-list");
	tabcontentgrouplist.innerHTML = "";
	tabcontentgrouplist.appendChild(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getgroupsby" + context + "&includegroups=false&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}
		document.getElementById("tab-content-group-list").innerHTML = "";

		var tb1 = new Element("div", {'class':'toolbarrow'});
		document.getElementById("tab-content-group-list").appendChild(tb1);

		var total = json.groupset[0].totalno;

		if (CURRENT_VIZ == 'list') {
			var currentPage = (json.groupset[0].start/args["max"]) + 1;
			window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
		}

		const tabcontentgrouplist = document.getElementById("tab-content-group-list");
		tabcontentgrouplist.innerHTML = "";
		tabcontentgrouplist.appendChild(createNav(total,json.groupset[0].start,json.groupset[0].count,args,context,"groups"));

		if(json.groupset[0].count > 0){
			//preprosses nodes to add searchid if it is there
			if (args['searchid'] && args['searchid'] != "") {
				var groups = json.groupset[0].groups;
				var count = groups.length;
				for (var i=0; i < count; i++) {
					var group = groups[i].group;
					group.searchid = args['searchid'];
				}
			}

			var tb2 = new Element("div", {'class':'toolbarrow'});
			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>', members: '<?php echo $LNG->SORT_MEMBERS; ?>'};
			tb2.insert(displaySortForm(sortOpts,args,'group',reorderGroups));
			document.getElementById("tab-content-group-list").appendChild(tb2);
			displayGroups(document.getElementById("tab-content-group-list"),json.groupset[0].groups,parseInt(args['start'])+1, false, true);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			document.getElementById("tab-content-group-list").appendChild(createNav(total,json.groupset[0].start,json.groupset[0].count,args,context,"groups"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
  	DATA_LOADED.group = true;
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
 *	Reorder the group tab
 */
function reorderGroups(){
	// change the sort and orderby ARG values
	GROUP_ARGS['start'] = 0;
	GROUP_ARGS['sort'] = document.getElementById('select-sort-group').options[document.getElementById('select-sort-group').selectedIndex].value;
	GROUP_ARGS['orderby'] = document.getElementById('select-orderby-group').options[document.getElementById('select-orderby-group').selectedIndex].value;

	loadgroups(CONTEXT,GROUP_ARGS);
}

/**
 *	Filter the issues by search criteria
 */
async function filterSearchIssues() {
	ISSUE_ARGS['q'] = document.getElementById('qissue').value;
	var scope = 'all';
	if (document.getElementById('scopeissuemy') && document.getElementById('scopeissuemy').selected) {
		scope = 'my';
	}
	ISSUE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&type=issue&format=text&q="+ISSUE_ARGS['q'];
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
			setTabPushed(document.getElementById('tab-issue-list-obj'),'issue-list');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.issue = false;
		setTabPushed(document.getElementById('tab-issue-list-obj'),'issue-list');
	}
}

/**
 *	Filter the groups by search criteria
 */
async function filterSearchGroups() {
	GROUP_ARGS['q'] = document.getElementById('qgroup').value;
	var scope = 'all';
	if (document.getElementById('scopegroupmy') && document.getElementById('scopegroupmy').selected) {
		scope = 'my';
	}
	GROUP_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&type=group&format=text&q="+GROUP_ARGS['q'];
		try {
			const json = await makeAPICall(reqUrl, 'GET');
			if (json.error) {
				alert(json.error[0].message);
				return;
			}		
			var searchid = transport.responseText;
			if (searchid != "") {
				GROUP_ARGS['searchid'] = searchid;
			}
			DATA_LOADED.group = false;
			setTabPushed(document.getElementById('tab-group-list-obj'),'group-list');
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.group = false;
		setTabPushed(document.getElementById('tab-group-list-obj'),'group-list');
	}
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2 col-auto'});
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
	        prevSpan.onclick = function() {
	            var newArr = argArray;
	            newArr["start"] = parseInt(start) - newArr["max"];
	            eval("load"+type+"(context,newArr)");
	        };
	    } else {
			prevSpan.innerHTML = "<i disabled class=\"fas fa-chevron-left fa-lg\" aria-hidden=\"true\"></i><span class=\"sr-only\"><?php echo $LNG->LIST_NAV_NO_PREVIOUS_HINT; ?></span>";
	        prevSpan.classList.add("inactive");
	    }
		
		pageUL.insert(prevSpan)

	    //pages
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages +1; i++){
	    	var page = new Element("li", {'class':"page-link"}).insert(i);
	    	if(i != currentPage){
		    	page.classList.add("active");
		    	var newArr = Object.clone(argArray);
		    	newArr["start"] = newArr["max"] * (i-1) ;
				page.addEventListener("click", function(event) { Pages.next.call(Pages, type, context, newArr); });
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

	    if( start > 0 || (parseInt(start)+parseInt(count) < parseInt(total))){
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
		objH.insert("<strong><?php echo $LNG->LIST_NAV_NO_ITEMS; ?></strong>");
    }
    return objH;
}

var Pages = {
	next: function(e){
		var data = $A(arguments);
		eval("load"+data[1]+"(data[2],data[3])");
	}
};