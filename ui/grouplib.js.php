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

    include_once("../config.php");

    $countries = getCountryList();

?>
/**
 *	set which tab to show and load first
 */
window.addEventListener('load', function() {
	var itemobj = renderGroup(groupObj, 760, "", true, false);
	document.getElementById('maingroupdiv').appendChild(itemobj);
	refreshIssues();
});

function changeGroupMode(obj, mode) {
	var wasPressed = false
	if (obj.className == "radiobuttonpressed") {
		wasPressed = true;
	}
	document.getElementById('radiobuttonsum').className = "radiobutton";
	document.getElementById('radiobuttonshare').className = "radiobutton";
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
	const checkname = document.getElementById('issue').value.trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_ISSUE_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }

	document.getElementById('issueform').style.cursor = 'wait';
	return true;
}

/**
 *	load next/previous set of nodes
 */
async function loadissues(context,args){
	args['filternodetypes'] = "Issue";

	updateAddressParameters(args);

	const tabcontentissuelist = document.getElementById('tab-content-issue-list');
	tabcontentissuelist.innerHTML = getLoading("<?php echo $LNG->LOADING_ISSUES; ?>");

	var reqUrl = SERVICE_ROOT + "&method=getnodesby" + context + "&" + Object.toQueryString(args);
	try {
		const json = await makeAPICall(reqUrl, 'GET');
		if (json.error) {
			alert(json.error[0].message);
			return;
		}	

		//display nav
		var total = json.nodeset[0].totalno;
		var currentPage = (json.nodeset[0].start/args["max"]) + 1;
		window.location.hash = "-"+currentPage;

		document.getElementById("tab-content-issue-list").innerHTML = (createNavCounter(total,json.nodeset[0].start,json.nodeset[0].count,"issues")).innerHTML;

		const tabcontentissuelist = document.getElementById("tab-content-issue-list");

		//display nodes
		if(json.nodeset[0].nodes.length > 0){

			//preprosses nodes to add searchid and groupid
			var nodes = json.nodeset[0].nodes;
			var count = nodes.length;
			for (var i=0; i < count; i++) {
				var node = nodes[i];
				if (args['searchid'] && args['searchid'] != "") {
					node.cnode.searchid = args['searchid'];
				}
				node.cnode.groupid = args['groupid'];
			}

			var tb3 = document.createElement("div");
			tb3.className = 'toolbarrow';

			var sortOpts = {date: '<?php echo $LNG->SORT_CREATIONDATE; ?>', name: '<?php echo $LNG->SORT_TITLE; ?>', moddate: '<?php echo $LNG->SORT_MODDATE; ?>',connectedness:'<?php echo $LNG->SORT_CONNECTIONS; ?>', vote:'<?php echo $LNG->SORT_VOTES; ?>'};
			tb3.appendChild(displaySortForm(sortOpts,args,'issue',reorderIssues));

			document.getElementById("tab-content-issue-list").appendChild(tb3);
			displayIssueNodes(466, 210,tabcontentissuelist,json.nodeset[0].nodes,parseInt(args['start'])+1, true, "groupissues", 'active', false, true, true);
		}

		//display nav
		if (total > parseInt( args["max"] )) {
			tabcontentissuelist.appendChild(createNav(total,json.nodeset[0].start,json.nodeset[0].count,args,context,"issues"));
		}
	} catch (err) {
		alert("There was an error: "+err.message);
		console.log(err)
	}
}

/**
*	Reorder the issue tab
*/
function reorderIssues(){
 	// change the sort and orderby ARG values
 	ISSUE_ARGS['start'] = 0;
	const sortissue = getElementById('select-sort-issue');
 	ISSUE_ARGS['sort'] = sortissue.options[sortissue.selectedIndex].value;
	const sortorderissue = getElementById('select-orderby-issue');
 	ISSUE_ARGS['orderby'] = sortorderissue.options[sortorderissue.selectedIndex].value;

 	loadissues(CONTEXT,ISSUE_ARGS);
}

/**
 *	Filter the issues by search criteria
 */
async function filterSearchIssues() {
	ISSUE_ARGS['q'] = document.getElementByIf('qissue').value;
	var scope = 'all';
	const scopeidissuemy = getElementById('scopeissuemy');
	if (scopeidissuemy && scopeidissuemy.selected) {
		scope = 'my';
	}
	ISSUE_ARGS['scope'] = scope;

	const tabissuelistobj = getElementById('tab-issue-list-obj');
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
			setTabPushed(tabissuelistobj,'issue-list');
			
		} catch (err) {
			alert("There was an error: "+err.message);
			console.log(err)
		}
	} else {
		DATA_LOADED.issue = false;
		setTabPushed(tabissuelistobj,'issue-list');
	}
}

/**
 * show the sort form
 */
function displaySortForm(sortOpts,args,tab,handler){

	var sbTool = document.createElement("span");
	sbTool.className = 'sortback toolbar2';
    sbTool.innerHTML = "<?php echo $LNG->SORT_BY; ?> ";

    var selOrd = document.createElement("select");
 	selOrd.onchange = handler;
    selOrd.id = "select-orderby-"+tab;
    selOrd.className = "toolbar";
    selOrd.name = "orderby";
    sbTool.appendChild(selOrd);
    for(var key in sortOpts){
        var opt = document.createElement("option");
        opt.value=key;
        opt.innerHTML = sortOpts[key].valueOf();
        selOrd.appendChild(opt);
        if(args.orderby == key){
        	opt.selected = true;
        }
    }
    var sortBys = {ASC: '<?php echo $LNG->SORT_ASC; ?>', DESC: '<?php echo $LNG->SORT_DESC; ?>'};
    var sortBy = document.createElement("select");
 	sortBy.onchange = handler;
    sortBy.id = "select-sort-"+tab;
    sortBy.className = "toolbar";
    sortBy.name = "sort";
    sbTool.appendChild(sortBy);
    for(var key in sortBys){
        var opt = document.createElement("option");
        opt.value=key;
        opt.innerHTML = sortBys[key];
        sortBy.appendChild(opt);
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

	var nav = document.createElement("div");
	nav.id = 'page-nav';
	nav.className = 'toolbarrow pb-3';

	var header = createNavCounter(total, start, count, type);
	nav.appendChild(header);

	if (total > parseInt( argArray["max"] )) {
		//previous
	    var prevSpan = document.createElement("span");
		prevSpan.id = "nav-previous";
		prevSpan.className = "page-nav page-chevron";
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

	    //pages
	    var pageSpan = document.createElement("span");
		pageSpan.id = "nav-pages";
	    var totalPages = Math.ceil(total/argArray["max"]);
	    var currentPage = (start/argArray["max"]) + 1;
	    for (var i = 1; i < totalPages+1; i++){
	    	var page = document.createElement("span");
			page.className = "nav-page";
			page.innerHTML = i;
	    	if(i != currentPage){
		    	page.classList.add("active");
		    	var newArr = { ...argArray };
		    	newArr["start"] = newArr["max"] * (i-1) ;
		    	page.onclick = Pages.next.bindAsEventListener(Pages,type,context,newArr);
	    	} else {
	    		page.classList.add("currentpage");
	    	}
	    	pageSpan.appendChild(page);
	    }

	    //next
	    var nextSpan = document.createElement("span");
		nextSpan.id = "nav-next";
		nextSpan.className = "page-nav page-chevron";
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

	    if( start>0 || (parseInt(start)+parseInt(count) < parseInt(total))){
	    	nav.appendChild(prevSpan);
			nav.appendChild(pageSpan);
			nav.appendChild(nextSpan);
	    }
	}

	return nav;
}

/**
 * display nav header
 */
function createNavCounter(total, start, count, type){

    if(count != 0){
    	var objH = document.createElement("span");
		objh.className = 'class':'nav';
    	var s1 = parseInt(start)+1;
    	var s2 = parseInt(start)+parseInt(count);
        objH.innerHTML = "<b>" + s1 + " <?php echo $LNG->LIST_NAV_TO; ?> " + s2 + " (" + total + ")</b>";
    } else {
    	var objH = document.createElement("span");
		objH.innerHTML = "<p><b><?php echo $LNG->LIST_NAV_NO_ITEMS; ?></b></p>";
    }
    return objH;
}

var Pages = {
	next: function(e){
		var data = $A(arguments);
		eval("load"+data[1]+"(data[2],data[3])");
	}
};