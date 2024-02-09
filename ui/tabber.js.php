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
var stpHomeList = setTabPushed.bindAsEventListener($('tab-home-list-obj'),'home-list');
var stpIssueList = setTabPushed.bindAsEventListener($('tab-issue-list-obj'),'issue-list');
var stpGroupList = setTabPushed.bindAsEventListener($('tab-group-list-obj'),'group-list');

/**
 *	set which tab to show and load first
 */
Event.observe(window, 'load', function() {

	Event.observe('tab-home','click', stpHomeList);
	Event.observe('tab-issue','click', stpIssueList);
	Event.observe('tab-group','click', stpGroupList);

	setTabPushed($('tab-'+getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ)),getAnchorVal(DEFAULT_TAB + "-" + DEFAULT_VIZ));
});

/**
 *	switch between tabs
 */
function setTabPushed(e) {

	var data = $A(arguments);
	var tabID = data[1];

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
		if(tab == i){
			if($("tab-"+i)) {
				$("tab-"+i).removeClassName("unselected");
				$("tab-"+i).addClassName("current");
			}
		} else {
			if($("tab-"+i)) {
				$("tab-"+i).removeClassName("current");
				$("tab-"+i).addClassName("unselected");
			}
		}
		if(tab == i){
			if ($("tab-content-"+i+"-div")) {
				$("tab-content-"+i+"-div").show();
			}
		} else {
			if ($("tab-content-"+i+"-div")) {
				$("tab-content-"+i+"-div").hide();
			}
		}
	}

	CURRENT_TAB = tab;
	CURRENT_VIZ = viz;

	switch(viz){
		case 'list':
			switch(tab) {
				case 'home':
					$('tab-home').setAttribute("href",'#home-list');
					Event.observe('tab-home','click', stpHomeList);
					break;
				case 'issue':
					$('tab-issue').setAttribute("href",'#issue-list');
					Event.stopObserving('tab-issue','click');
					Event.observe('tab-issue','click', stpIssueList);
					if(!DATA_LOADED.issue){
						ISSUE_ARGS['start'] = (page-1) * ISSUE_ARGS['max'];
						loadissues(CONTEXT,ISSUE_ARGS);
					} else {
						updateAddressParameters(ISSUE_ARGS);
					}
					break;
				case 'group':
					$('tab-group').setAttribute("href",'#group-list');
					Event.stopObserving('tab-group','click');
					Event.observe('tab-group','click', stpGroupList);
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
function loadissues(context,args){
	args['filternodetypes'] = "Issue";

	updateAddressParameters(args);

	$("tab-content-issue-list").update(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));

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

      			//set the count in tab header
      			//$('issue-list-count').innerHTML = "";
      			//$('issue-list-count').insert("("+json.nodeset[0].totalno+")");

      			//$('issuebuttons').innerHTML = "";

				//display nav
				var total = json.nodeset[0].totalno;
				if (CURRENT_VIZ == 'list') {
					var currentPage = (json.nodeset[0].start/args["max"]) + 1;
					window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
				}
				$("tab-content-issue-list").update(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));

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

					$("tab-content-issue-list").insert(tb3);

					displayIssueNodes(466, 210, $("tab-content-issue-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true, "issues", 'active', false, true, true);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-issue-list").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
				}
    		}
  		});
  	DATA_LOADED.issue = true;
  	DATA_LOADED.issuesimile = false;
}

/**
 *	load next/previous set of users
 */
function loadgroups(context,args){

	updateAddressParameters(args);

	$("tab-content-group-list").update(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));

	var reqUrl = SERVICE_ROOT + "&method=getgroupsby" + context + "&includegroups=false&" + Object.toQueryString(args);

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

				var tb1 = new Element("div", {'class':'toolbarrow'});
				$("tab-content-group-list").insert(tb1);

				var total = json.groupset[0].totalno;

				if (CURRENT_VIZ == 'list') {
					var currentPage = (json.groupset[0].start/args["max"]) + 1;
					window.location.hash = CURRENT_TAB+"-"+CURRENT_VIZ+"-"+currentPage;
				}

				$("tab-content-group-list").update(createNav(total,json.groupset[0].start,json.groupset[0].count,args,context,"groups"));

				if(json.groupset[0].count > 0){
					//preprosses nodes to add searchid if it is there
					if (args['searchid'] && args['searchid'] != "") {
						var groups = json.groupset[0].groups;
						var count = groups.length;
						for (var i=0; i<count; i++) {
							var group = groups[i].group;
							group.searchid = args['searchid'];
						}
					}

					var tb2 = new Element("div", {'class':'toolbarrow'});
					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>', members: '<?php echo $LNG->SORT_MEMBERS; ?>'};
					tb2.insert(displaySortForm(sortOpts,args,'group',reorderGroups));
					$("tab-content-group-list").insert(tb2);
					displayGroups($("tab-content-group-list"),json.groupset[0].groups,parseInt(args['start'])+1, false, true);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-group-list").insert(createNav(total,json.groupset[0].start,json.groupset[0].count,args,context,"groups"));
				}
    		}
  		});
  	DATA_LOADED.group = true;
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
 *	Reorder the group tab
 */
function reorderGroups(){
	// change the sort and orderby ARG values
	GROUP_ARGS['start'] = 0;
	GROUP_ARGS['sort'] = $('select-sort-group').options[$('select-sort-group').selectedIndex].value;
	GROUP_ARGS['orderby'] = $('select-orderby-group').options[$('select-orderby-group').selectedIndex].value;

	loadgroups(CONTEXT,GROUP_ARGS);
}

/**
 *	Filter the issues by search criteria
 */
function filterSearchIssues() {
	ISSUE_ARGS['q'] = $('qissue').value;
	var scope = 'all';
	if ($('scopeissuemy') && $('scopeissuemy').selected) {
		scope = 'my';
	}
	ISSUE_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&type=issue&format=text&q="+ISSUE_ARGS['q'];
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
				setTabPushed($('tab-issue-list-obj'),'issue-list');
			}
		});
	} else {
		DATA_LOADED.issue = false;
		setTabPushed($('tab-issue-list-obj'),'issue-list');
	}
}

/**
 *	Filter the groups by search criteria
 */
function filterSearchGroups() {
	GROUP_ARGS['q'] = $('qgroup').value;
	var scope = 'all';
	if ($('scopegroupmy') && $('scopegroupmy').selected) {
		scope = 'my';
	}
	GROUP_ARGS['scope'] = scope;

	if (USER != "") {
		var reqUrl = SERVICE_ROOT + "&method=auditsearch&type=group&format=text&q="+GROUP_ARGS['q'];
		new Ajax.Request(reqUrl, { method:'get',
			onError: function(error) {
				alert(error);
			},
	  		onSuccess: function(transport){
				var searchid = transport.responseText;
				if (searchid != "") {
					GROUP_ARGS['searchid'] = searchid;
				}
				DATA_LOADED.group = false;
				setTabPushed($('tab-group-list-obj'),'group-list');
			}
		});
	} else {
		DATA_LOADED.group = false;
		setTabPushed($('tab-group-list-obj'),'group-list');
	}
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2 col-auto'});
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
		
		pageUL.insert(prevSpan)

	    //pages
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages +1; i++){
	    	var page = new Element("li", {'class':"page-link"}).insert(i);
	    	if(i != currentPage){
		    	page.addClassName("active");
		    	var newArr = Object.clone(argArray);
		    	newArr["start"] = newArr["max"] * (i-1) ;
		    	Event.observe(page,"click", Pages.next.bindAsEventListener(Pages,type,context,newArr));
	    	} else {
	    		page.addClassName("currentpage");
	    	}
	    	pageUL.insert(page);
	    }

	    //next
	    var nextSpan = new Element("li", {'id':"nav-next", "class": "page-link"});
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