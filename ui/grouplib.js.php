<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2025 The Open University UK                            *
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

    include_once("../config.php");

    $countries = getCountryList();

?>
/**
 *	set which tab to show and load first
 */
Event.observe(window, 'load', function() {
	var itemobj = renderGroup(groupObj, 760, "", true, false);
	$('maingroupdiv').insert(itemobj);
	refreshIssues();
});

function changeGroupMode(obj, mode) {
	var wasPressed = false
	if (obj.className == "radiobuttonpressed") {
		wasPressed = true;
	}
	$('radiobuttonsum').className = "radiobutton";
	$('radiobuttonshare').className = "radiobutton";
	if (!wasPressed) {
		obj.className = "radiobuttonpressed";
	}

	if (mode == 'Summarize') {
	}
}

/**
 *	Called by forms to refresh the issues view
 */
function refreshIssues() {
	loadissues(CONTEXT,ISSUE_ARGS);
}

function checkIssueAddForm() {
	var checkname = ($('issue').value).trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_ISSUE_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }

    $('issueform').style.cursor = 'wait';

	return true;
}

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

				//display nav
				var total = json.nodeset[0].totalno;
				var currentPage = (json.nodeset[0].start/args["max"]) + 1;
				window.location.hash = "-"+currentPage;

				$("tab-content-issue-list").update(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));

				//display nodes
				if(json.nodeset[0].nodes.length > 0){

					//preprosses nodes to add searchid and groupid
					var nodes = json.nodeset[0].nodes;
					var count = nodes.length;
					for (var i=0; i<count; i++) {
						var node = nodes[i];
						if (args['searchid'] && args['searchid'] != "") {
							node.cnode.searchid = args['searchid'];
						}
						node.cnode.groupid = args['groupid'];
					}

					var tb3 = new Element("div", {'class':'toolbarrow'});

					var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
					tb3.insert(displaySortForm(sortOpts,args,'issue',reorderIssues));

					$("tab-content-issue-list").insert(tb3);

					displayIssueNodes(466, 210, $("tab-content-issue-list"),json.nodeset[0].nodes,parseInt(args['start'])+1, true, "groupissues", 'active', false, true, true);
				}

				//display nav
				if (total > parseInt( args["max"] )) {
					$("tab-content-issue-list").insert(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
				}
    		}
  		});
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
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = new Element("span", {'class':'sortback toolbar2'});
    sbTool.insert("<?php echo $LNG->SORT_BY; ?> ");

    var selOrd = new Element("select");
 	Event.observe(selOrd,'change',handler);
    selOrd.id = "select-orderby-"+tab;
    selOrd.className = "toolbar";
    selOrd.name = "orderby";
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
    sortBy.className = "toolbar";
    sortBy.name = "sort";
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
	    var pageSpan = new Element("span", {'id':"nav-pages"});
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
		objH.insert("<p><b><?php echo $LNG->LIST_NAV_NO_ITEMS; ?></b></p>");
    }
    return objH;
}

var Pages = {
	next: function(e){
		var data = $A(arguments);
		eval("load"+data[1]+"(data[2],data[3])");
	}
};