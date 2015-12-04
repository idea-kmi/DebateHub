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

?>

/*
"date" => $date,
"type" => $changetype,
"nodeid" => $node->nodeid,
"title" => $node->name,
"nodetype" => $node->role->name,
*/

function displayActivityCrossFilterD3Vis(data, width) {

	var activityChart = dc.barChart("#date-chart");
	//var activitySelectorChart = dc.barChart("#date-selector-chart");
	//var monthChart = dc.rowChart("#month-chart");
	var dayOfWeekChart = dc.rowChart("#days-of-week-chart");
	var itemTypeChart = dc.rowChart("#nodetype-chart");
	var activityTypeChart = dc.rowChart("#type-chart");
	var activityTableList = dc.dataTable("#data-table");

	var formatDateGroup = d3.time.format("%B %Y");
	var formatDate = d3.time.format("%B %d, %Y");
	var formatTime = d3.time.format("%a %d %I:%M %p");

	data.forEach(function(d, i) {
		d.index = i;
		d.date = new Date(d.date*1000);
	});

	var activities = crossfilter(data);
	var all = activities.groupAll();

	/*** MAIN DATE CHART ***/
	var date = activities.dimension(function(d) { return d.date; });
	var dates = date.group(d3.time.day);
	var datesselect = date.group(d3.time.day);

	// Get date start and end dates - range
	var strmDateAccessor = function (d){return d.date;};
	strmDateExtent = [];
	strmDateExtent = d3.extent(data, strmDateAccessor);

	var x = d3.time.scale()
    	.domain([d3.time.day.offset(strmDateExtent[0], -1), strmDateExtent[1]])
    	.rangeRound([0, width - 80]);

	activityChart
		.width(width)
		.height(200)
		.transitionDuration(500)
		.margins({top: 10, right: 40, bottom: 20, left: 40})
		.dimension(date)
		.x(x)
		.round(d3.time.day.round)
		.xUnits(d3.time.days)
		.elasticY(true)
		.elasticX(false)
		.renderHorizontalGridLines(true)
		.renderVerticalGridLines(false)
		.brushOn(true)
		.group(dates)
		.yAxisPadding(0)
		.xAxisPadding(0)
		.title(function(d) { return d.value; })
		.mouseZoomable(false)
		.renderTitle(true)
		.centerBar(true)
		;

	//	.rangeChart(activitySelectorChart)
	//	.renderArea(true)
	//	.mouseZoomable(true)
	//.centerBar(true)

    /*activitySelectorChart
		.width(width)
        .height(60)
		.margins({top: 10, right: 0, bottom: 20, left: 0})
        .dimension(date)
        .group(datesselect)
        .centerBar(true)
		.gap(1)
		.x(x)
		.round(d3.time.month.round)
		.xUnits(d3.time.months)
		.mouseZoomable(false)
		.brushOn(true)
		;
	*/
	//	.mouseZoomable(false)
   //     .alwaysUseRounding(true)

	/*** MONTH OF WEEK ***/
	/*var monthFilter = activities.dimension(function (d) {
		//var newDate = new Date(date.getFullYear(), date.getMonth()+1, date.getDay());
		var day = d.date.getMonth();
		switch (day) {
			case 0: return "0.<?php echo $LNG->STATS_ACTIVITY_JAN; ?>";
			case 1: return "1.<?php echo $LNG->STATS_ACTIVITY_FEB; ?>";
			case 2: return "2.<?php echo $LNG->STATS_ACTIVITY_MAR; ?>";
			case 3:	return "3.<?php echo $LNG->STATS_ACTIVITY_APR; ?>";
			case 4:	return "4.<?php echo $LNG->STATS_ACTIVITY_MAY; ?>";
			case 5:	return "5.<?php echo $LNG->STATS_ACTIVITY_JUN; ?>";
			case 6:	return "6.<?php echo $LNG->STATS_ACTIVITY_JUL; ?>";
			case 7:	return "7.<?php echo $LNG->STATS_ACTIVITY_AUG; ?>";
			case 8:	return "8.<?php echo $LNG->STATS_ACTIVITY_SEP; ?>";
			case 9:	return "9.<?php echo $LNG->STATS_ACTIVITY_OCT; ?>";
			case 10:return "10.<?php echo $LNG->STATS_ACTIVITY_NOV; ?>";
			case 11:return "11.<?php echo $LNG->STATS_ACTIVITY_DEC; ?>";
		}
	});
	var monthGroup = monthFilter.group();

	monthChart
		.width(180)
		.height(180)
		.margins({top: 10, left: 10, right: 15, bottom: 20})
		.group(monthGroup)
		.dimension(monthFilter)
		.colors(['#3182bd', '#6baed6', '#9ecae1', '#c6dbef', '#dadaeb'])
		.label(function (d) { return d.key.split(".")[1]; })
		.labelOffsetX(5)
		.labelOffsetY(13)
		.elasticX(true)
		.title(function (d) { return d.value; })
		.renderTitle(true)
		.gap(2)
		.xAxis().ticks(4);
	*/

	/*** DAYS OF WEEK ***/
	var dayOfWeek = activities.dimension(function (d) {
		var day = d.date.getDay();
		switch (day) {
			case 0: return "0.<?php echo $LNG->STATS_ACTIVITY_SUNDAY; ?>";
			case 1: return "1.<?php echo $LNG->STATS_ACTIVITY_MONDAY; ?>";
			case 2: return "2.<?php echo $LNG->STATS_ACTIVITY_TUESDAY; ?>";
			case 3:	return "3.<?php echo $LNG->STATS_ACTIVITY_WEDNESDAY; ?>";
			case 4:	return "4.<?php echo $LNG->STATS_ACTIVITY_THURSDAY; ?>";
			case 5:	return "5.<?php echo $LNG->STATS_ACTIVITY_FRIDAY; ?>";
			case 6:	return "6.<?php echo $LNG->STATS_ACTIVITY_SATURDAY; ?>";
		}
	});
	var dayOfWeekGroup = dayOfWeek.group();

	dayOfWeekChart
		.width(180)
		.height(180)
		.margins({top: 10, left: 10, right: 15, bottom: 20})
		.group(dayOfWeekGroup)
		.dimension(dayOfWeek)
		.colors(['#3182bd', '#6baed6', '#9ecae1', '#c6dbef', '#dadaeb'])
		.label(function (d) { return d.key.split(".")[1]; })
		.labelOffsetX(5)
		.labelOffsetY(13)
		.elasticX(true)
		.title(function (d) { return d.value; })
		.renderTitle(true)
		.gap(2)
		.xAxis().ticks(4);

	/*** NODE TYPES ***/
	var nodetype = activities.dimension(function(d) {
		var type = d.nodetype;
		switch (type) {
			case "Issue": return "0."+getNodeTitleAntecedence(d.nodetype, false);
			case "Solution": return "1."+getNodeTitleAntecedence(d.nodetype, false);
			case "Pro": return "2."+getNodeTitleAntecedence(d.nodetype, false);
			case "Con": return "3."+getNodeTitleAntecedence(d.nodetype, false);
			default: return "4."+type;
		}
	});
	var nodetypeGroup = nodetype.group();

	// ITEM TYPE AS PIE
	itemTypeChart
		.width(180)
		.height(180)
		.margins({top: 10, left: 10, right: 15, bottom: 20})
		.group(nodetypeGroup)
		.dimension(nodetype)
		.colors(["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A"])
		.label(function (d) { return d.key.split(".")[1];  })
		.labelOffsetX(5)
		.labelOffsetY(20)
		.elasticX(true)
		.title(function (d) { return d.value; })
		.renderTitle(true)
		.gap(2)
		.xAxis().ticks(4);

	/*** ACTIVITY TYPES ***/
	var activitytype = activities.dimension(function(d) { return d.type; });
	var activitytypeGroup = activitytype.group();

	activityTypeChart
		.width(180)
		.height(180)
		.margins({top: 10, left: 10, right: 15, bottom: 20})
		.group(activitytypeGroup)
		.dimension(activitytype)
		.colors(['#3182bd', '#6baed6', '#9ecae1', '#c6dbef', '#dadaeb'])
		.label(function (d) { return d.key; })
		.labelOffsetX(5)
		.labelOffsetY(20)
		.title(function (d) { return d.value; })
		.elasticX(true)
		.renderTitle(true)
		.gap(2)
		.xAxis().ticks(4);

	/*
	dc.pieChart("#nodetype-pie-chart")
		.width(180)
		.height(180)
		.transitionDuration(500)
		.colors(['#3182bd', '#6baed6', '#9ecae1', '#c6dbef', '#dadaeb'])
		// (optional) define color domain to match your data domain if you want to bind data or color
		.colorDomain([-1750, 1644])
		// (optional) define color value accessor
		.colorAccessor(function(d, i){return d.value;})
		.radius(90) // define pie radius
		.innerRadius(40)
		.dimension(gainOrLoss) // set dimension
		.group(gainOrLossGroup) // set group
		.label(function(d) { return d.data.key + "(" + Math.floor(d.data.value / all.value() * 100) + "%)"; })
		.renderLabel(true)
		.title(function(d) { return d.data.key + "(" + Math.floor(d.data.value / all.value() * 100) + "%)"; })
		.renderTitle(true);
	*/

	/*var colorScale = d3.scale.ordinal().range(["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A"]);
	dc.pieChart("#nodetype-nut-chart")
	    .width(180)
	    .height(180)
	    .transitionDuration(500)
		.colors(colorScale)
	    .radius(90)
	    .innerRadius(30)
	    .dimension(nodetype)
	    .group(nodetypeGroup)
	    .label(function(d) { return d.data.key.split(".")[1];  })
	    .renderLabel(true)
	    .title(function(d) { return d.data.key.split(".")[1]+": "+d.data.value; })
    	.renderTitle(true);
    	*/

	/*var colorScale = d3.scale.ordinal().range(["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A"]);
	dc.pieChart("#nodetype-pie-chart")
	    .width(180)
	    .height(180)
	    .transitionDuration(500)
		.colors(colorScale)
	    .radius(90)
	    .dimension(nodetype)
	    .group(nodetypeGroup)
	    .label(function(d) { return d.data.key.split(".")[1];  })
	    .renderLabel(true)
	    .title(function(d) { return d.data.key.split(".")[1]+": "+d.data.value; })
    	.renderTitle(true);*/

	// TABLE OF DATA AT BOTTOM
	activityTableList
		.dimension(date)
		.group(function(d) {
			return formatDateGroup(d.date);
		})
		// (optional) max number of records to be shown, :default = 25
		.size(50)
		.columns([
			function(d) { return formatTime(d.date); },
			function(d) { return d.title; },
			function(d) { return getNodeTitleAntecedence(d.nodetype, false); },
			function(d) { return d.type }
		])
		.sortBy(function(d){ return d.date; })
		.order(d3.ascending);

	dc.dataCount("#data-count")
		.dimension(activities)
		.group(all);

	dc.renderAll();

	//dc.renderAll(chartGroup1);
	//dc.renderAll(chartGroup2);

	// once rendered you can call redrawAll to update charts incrementally when data
	// change without re-rendering everything
	//dc.redrawAll();
	// or you can choose to redraw only those charts associated with a specific chart group
	//dc.redrawAll("group");

 	$('messagearea').innerHTML="";
}

function displayUserActivityCrossFilterD3Vis(data, width) {

	var formatDateGroup = d3.time.format("%B %Y");
	var formatDate = d3.time.format("%B %d, %Y");
	var formatTime = d3.time.format("%a %d %b %Y %I:%M %p");

	var checkDuplicateNames = {};

	/*data.forEach(function(d, i) {
		d.index = i;
		d.date = new Date(d.date*1000);
	});*/

	data.forEach(function(d, i) {
		d.index = i;
		d.date = new Date(d.date*1000);
		if (d.username != "") {
			// Handle duplicate names
			var name = d.username;
			if (checkDuplicateNames[name]) {
				var nameuseridArray = checkDuplicateNames[name];
				var found = false;
				for (i=0; i<nameuseridArray.length; i++) {
					nextid = nameuseridArray[i];
					if (nextid == d.userid) {
						if (i == 0) {
							d.username = name;
						} else {
							d.username = name+" ("+(i+1)+")";
						}
						found = true;
						break;
					}
				}
				if (!found) {
					nameuseridArray.push(d.userid);
					d.username = name+" ("+(nameuseridArray.length)+")";
					checkDuplicateNames[name] = nameuseridArray;
				}
			} else {
				d.username = name;
				var array = new Array();
				array.push(d.userid);
				checkDuplicateNames[name] = array;
			}
		}
	});


	var activities = crossfilter(data);
	var all = activities.groupAll();

	/*** MAIN DATE CHART ***/
	var user = activities.dimension(function(d) {return d.username;});
	var users = user.group();

	//var user = activities.dimension(function(d) {return d.userid;});
	//var users = user.group();

	var usersGrouped = user.group().reduce(
		function(p, v) {
			p.totalAll++;
			if (v.nodetype == "Issue") {
				p.totalIssue++;
			} else if (v.nodetype == "Solution") {
				p.totalIdea++;
			} else if (v.nodetype == "Pro") {
				p.totalPro++;
			} else if (v.nodetype == "Con") {
				p.totalCon++;
			} else if (v.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTE; ?>") {
				p.totalVote++;
			} else if (v.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTED_FOR; ?>") {
				p.totalVoteFor++;
			} else if (v.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTED_AGAINST; ?>") {
				p.totalVoteAgainst++;
			}
			return p;
		},
		function(p, v) {
			p.totalAll--;
			if (v.nodetype == "Issue") {
				p.totalIssue--;
			} else if (v.nodetype == "Solution") {
				p.totalIdea--;
			} else if (v.nodetype == "Pro") {
				p.totalPro--;
			} else if (v.nodetype == "Con") {
				p.totalCon--;
			} else if (v.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTE; ?>") {
				p.totalVote--;
			} else if (v.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTED_FOR; ?>") {
				p.totalVoteFor--;
			} else if (v.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTED_AGAINST; ?>") {
				p.totalVoteAgainst--;
			}
			return p;
		},
		function() {
			return {
				totalAll:0,
				totalIssue:0,
				totalIdea:0,
				totalPro:0,
				totalCon:0,
				totalVote:0,
				totalVoteFor:0,
				totalVoteAgainst:0
			};
		}
	);

	//	.stack(usersGrouped, "<?php echo $LNG->STATS_ACTIVITY_VOTED_FOR; ?>", function(d){return d.value.totalVoteFor;})
	//	.stack(usersGrouped, "<?php echo $LNG->STATS_ACTIVITY_VOTED_AGAINST; ?>", function(d){return d.value.totalVoteAgainst;})

	var widtha = 0;
	var usercount = users.size();
	if (usercount > 10) {
		widtha = usercount*30;
	} else {
		widtha = usercount*90;
	}

	dc.barChart("#user-chart")
		.width(width)
		.height(300)
		.transitionDuration(500)
		.margins({top: 10, right: 30, bottom: 20, left: 40})
		.group(usersGrouped, getNodeTitleAntecedence("Issue", false))
		.valueAccessor(function(d) { return d.value.totalIssue;	})
		.stack(usersGrouped, getNodeTitleAntecedence("Solution", false), function(d){return d.value.totalIdea;})
		.stack(usersGrouped, getNodeTitleAntecedence("Pro", false), function(d){return d.value.totalPro;})
		.stack(usersGrouped, getNodeTitleAntecedence("Con", false), function(d){return d.value.totalCon;})
		.stack(usersGrouped, "<?php echo $LNG->STATS_ACTIVITY_VOTE; ?>", function(d){return d.value.totalVote;})
		.colors(["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A", "#F9B257", "#E1E353"])
		.dimension(user)
		.elasticY(false)
		.yAxisPadding(0)
		.elasticX(false)
		.xAxisPadding(0)
		.x(d3.scale.ordinal().domain(data.map( function(d){ return d.username;})))
		.xUnits(dc.units.ordinal)
		.centerBar(true)
		.renderHorizontalGridLines(true)
		.renderVerticalGridLines(false)
		.ordering(function(d){ return -d.value.totalAll; })
		.title(function(d){ return "";})
		.renderTitle(false);

		//.legend(dc.legend().x(10).y(10).itemHeight(13).gap(5));

		/*.title(function(d) {
			//alert(d.toSource());
			var title=d.data.value.y;
			if (d.data.y == d.data.value.totalIssue) {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalIssue;
			} else if (d.data.value.nodetype == "Solution") {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalIdea;
			} else if (d.data.value.nodetype == "Pro") {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalPro;
			} else if (d.data.value.nodetype == "Con") {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalCon;
			} else if (d.data.value.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTE; ?>") {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalVote;
			} else if (d.data.value.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTED_FOR; ?>") {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalVoteFor;
			} else if (d.data.value.nodetype == "<?php echo $LNG->STATS_ACTIVITY_VOTED_AGAINST; ?>") {
				title=getNodeTitleAntecedence(d.data.value.nodetype, true)+" "+d.data.value.totalVoteAgainst;
			}

			return title;

		})
		.renderTitle(true);
		*/

	// Hide labels.
	//userChart.xAxis().tickFormat(function(v) { return ""; });

	//	.mouseZoomable(true)

	//d3.select("#user-chart")
    //	.selectAll("text")
	//    .attr("transform", function(d) {
    //    	return "rotate(-90)"
    //	});

	/*** NODE TYPES ***/
	var nodetype = activities.dimension(function(d) {
		var type = d.nodetype;
		switch (type) {
			case "Issue": return "0.Added "+getNodeTitleAntecedence(d.nodetype, false);
			case "Solution": return "1.Added "+getNodeTitleAntecedence(d.nodetype, false);
			case "Pro": return "2.Added "+getNodeTitleAntecedence(d.nodetype, false);
			case "Con": return "3.Added "+getNodeTitleAntecedence(d.nodetype, false);
			case "<?php echo $LNG->STATS_ACTIVITY_VOTE; ?>": return "4."+d.nodetype;
			case "<?php echo $LNG->STATS_ACTIVITY_VOTED_FOR; ?>": return "5."+d.nodetype;
			case "<?php echo $LNG->STATS_ACTIVITY_VOTED_AGAINST; ?>": return "6."+d.nodetype;
			default: return "6."+type;
		}
	});
	var nodetypeGroup = nodetype.group();

	//	.colors(["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A", "#F9B257", "#E1E353"])
	var colorChoice = d3.scale.ordinal().domain([0,1,2,3,4,5])
          .range(["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A", "#F9B257", "#E1E353"]);

	dc.rowChart("#nodetype-chart")
		.width(350)
		.height(200)
		.margins({top: 10, left: 40, right: 0, bottom: 20})
		.group(nodetypeGroup)
		.dimension(nodetype)
		.colors(colorChoice)
		.colorAccessor(function(d) {
			 var key = parseInt(d.key.split(".")[0]);
			 return key;
         })
		.label(function (d) { return d.key.split(".")[1];  })
		.labelOffsetX(5)
		.labelOffsetY(20)
		.elasticX(true)
		.title(function (d) { return d.value; })
		.renderTitle(true)
		.gap(2)
		.xAxis().ticks(6);

	// TABLE OF DATA AT BOTTOM
	//var date = activities.dimension(function(d) { return d.date; });
	dc.dataTable("#data-table")
		.dimension(user)
		.group(function(d) {
			if (d.homepage && d.homepage != "") {
				return '<a target="_blank" href="'+d.homepage+'">'+d.username+'</a>';
			} else {
				return d.username;
			}
		})
		// (optional) max number of records to be shown, :default = 25
		.size(50)
		.columns([
			function(d) { return formatTime(d.date); },
			function(d) {
				var type = d.nodetype;
				switch (type) {
					case "Issue": return "Added "+getNodeTitleAntecedence(type, false);
					case "Solution": return "Added "+getNodeTitleAntecedence(type, false);
					case "Pro": return "Added "+getNodeTitleAntecedence(type, false);
					case "Con": return "Added "+getNodeTitleAntecedence(type, false);
					case "<?php echo $LNG->STATS_ACTIVITY_VOTE; ?>": return type;
					case "<?php echo $LNG->STATS_ACTIVITY_VOTED_FOR; ?>": return type;
					case "<?php echo $LNG->STATS_ACTIVITY_VOTED_AGAINST; ?>": return type;
					default: return type;
				}
			},
			function(d) { return d.title; }
		])
		.sortBy(function(d){ return d.userid; })
		.order(d3.ascending);

	dc.dataCount("#data-count")
		.dimension(activities)
		.group(all);

	dc.renderAll();

	//ADD TOOLTIPS
    /*var tipCrossFilter = d3.tip()
		.attr('class', 'd3-tip')
		.offset([10, 50])
		.html(function(d) {
			alert('d:'+d.toSource());
			//var hint = '<div class="selectedback" style="padding:2px;border:1px solid dimgray">';
			//if (d.id != d.name) {
			//	hint += d.name + " (click to view)";
			//} else {
			//	hint += d.name;
			//}
			//hint += '</div>';

			//return hint;
		})

	d3.select("svg").call(tipCrossFilter);
    d3.select("#user-chart .bar")
		.on('mouseover', function (d,i) {
			tipCrossFilter.show(d)
		})
		.on('mouseout', function (d,i) {
		  	tipCrossFilter.hide(d)
		});
	*/

	/** LEGEND **/
	var colorrange = ["#DFC7EB", "#A4AED4", "#A9C89E", "#D46A6A", "#F9B257", "#E1E353"];
	var legend = d3Legend();
	legend
		.color(colorrange)
		.width(width)
		.height(20)
        .margin({top: 2, right: 0, bottom: 0, left: 15});

	var priorities = [getNodeTitleAntecedence('Issue', false),
					getNodeTitleAntecedence('Solution', false),
					getNodeTitleAntecedence('Pro', false),
					getNodeTitleAntecedence('Con', false),
					getNodeTitleAntecedence('Vote', false)];
	var nest = d3.nest().key(function(d) { return getNodeTitleAntecedence(d.nodetype, false); })
		.sortKeys(function(a,b) { return priorities.indexOf(a) - priorities.indexOf(b); })

	var svg = d3.select("#keyarea").append("svg")
	  .attr("width", width)
	  .attr("height", 30)
	  .append("g")
	  .attr('class', 'legendWrap')
	  .attr("transform", "translate(" + 0 + "," + 0 + ")");

	d3.select('.legendWrap')
          .datum(nest.entries(data))
          .call(legend);

 	$('messagearea').innerHTML="";
}


function displayCrossFilterD3VisTest(width) {

	d3.csv(URL_ROOT+"testing/flights-3m.json", function(error, flights) {

	  // Various formatters.
	  var formatNumber = d3.format(",d"),
		  formatChange = d3.format("+,d"),
		  formatDate = d3.time.format("%B %d, %Y"),
		  formatTime = d3.time.format("%I:%M %p");

	  // A nest operator, for grouping the flight list.
	  var nestByDate = d3.nest()
		  .key(function(d) { return d3.time.day(d.date); });

	  // A little coercion, since the CSV is untyped.
	  flights.forEach(function(d, i) {
		d.index = i;
		d.date = parseDate(d.date);
		d.delay = +d.delay;
		d.distance = +d.distance;
	  });

	  // Create the crossfilter for the relevant dimensions and groups.
	  var flight = crossfilter(flights),
		  all = flight.groupAll(),
		  date = flight.dimension(function(d) { return d.date; }),
		  dates = date.group(d3.time.day),
		  hour = flight.dimension(function(d) { return d.date.getHours() + d.date.getMinutes() / 60; }),
		  hours = hour.group(Math.floor),
		  delay = flight.dimension(function(d) { return Math.max(-60, Math.min(149, d.delay)); }),
		  delays = delay.group(function(d) { return Math.floor(d / 10) * 10; }),
		  distance = flight.dimension(function(d) { return Math.min(1999, d.distance); }),
		  distances = distance.group(function(d) { return Math.floor(d / 50) * 50; });

	  var charts = [

		barChart()
			.dimension(hour)
			.group(hours)
		    .x(d3.scale.linear()
			.domain([0, 24])
			.rangeRound([0, 10 * 24])),

		barChart()
			.dimension(delay)
			.group(delays)
		  .x(d3.scale.linear()
			.domain([-60, 150])
			.rangeRound([0, 10 * 21])),

		barChart()
			.dimension(distance)
			.group(distances)
		  .x(d3.scale.linear()
			.domain([0, 2000])
			.rangeRound([0, 10 * 40])),

		barChart()
			.dimension(date)
			.group(dates)
			.round(d3.time.day.round)
		    .x(d3.time.scale()
			.domain([new Date(2001, 0, 1), new Date(2001, 3, 1)])
			.rangeRound([0, 10 * 90]))
			.filter([new Date(2001, 1, 1), new Date(2001, 2, 1)])

	  ];

	  // Given our array of charts, which we assume are in the same order as the
	  // .chart elements in the DOM, bind the charts to the DOM and render them.
	  // We also listen to the charts brush events to update the display.
	  var chart = d3.selectAll(".chart")
		  .data(charts)
		  .each(function(chart) { chart.on("brush", renderAll).on("brushend", renderAll); });

	  // Render the initial lists.
	  var list = d3.selectAll(".list")
		  .data([flightList]);

	  // Render the total.
	  d3.selectAll("#total")
		  .text(formatNumber(flight.size()));

	  renderAll();

	  // Renders the specified chart or list.
	  function render(method) {
		d3.select(this).call(method);
	  }

	  // Whenever the brush moves, re-rendering everything.
	  function renderAll() {
		chart.each(render);
		list.each(render);
		d3.select("#active").text(formatNumber(all.value()));
	  }

	  // Like d3.time.format, but faster.
	  function parseDate(d) {
			return new Date(2001,
			d.substring(0, 2) - 1,
			d.substring(2, 4),
			d.substring(4, 6),
			d.substring(6, 8));
	  }

	  window.filter = function(filters) {
		filters.forEach(function(d, i) { charts[i].filter(d); });
		renderAll();
	  };

	  window.reset = function(i) {
		charts[i].filter(null);
		renderAll();
	  };

	  function flightList(div) {
		var flightsByDate = nestByDate.entries(date.top(40));

		div.each(function() {

		  var date = d3.select(this).selectAll(".date")
			  .data(flightsByDate, function(d) { return d.key; });

		  date.enter().append("div")
			  .attr("class", "date")
			  .append("div")
			  .attr("class", "day")
			  .text(function(d) { return formatDate(d.values[0].date); });

		  date.exit().remove();

		  var flight = date.order().selectAll(".flight")
			  .data(function(d) { return d.values; }, function(d) { return d.index; });

		  var flightEnter = flight.enter().append("div")
			  .attr("class", "flight");

		  flightEnter.append("div")
			  .attr("class", "time")
			  .text(function(d) { return formatTime(d.date); });

		  flightEnter.append("div")
			  .attr("class", "origin")
			  .text(function(d) { return d.origin; });

		  flightEnter.append("div")
			  .attr("class", "destination")
			  .text(function(d) { return d.destination; });

		  flightEnter.append("div")
			  .attr("class", "distance")
			  .text(function(d) { return formatNumber(d.distance) + " mi."; });

		  flightEnter.append("div")
			  .attr("class", "delay")
			  .classed("early", function(d) { return d.delay < 0; })
			  .text(function(d) { return formatChange(d.delay) + " min."; });

		  flight.exit().remove();

		  flight.order();
		});
	  }

	  function barChart() {
		if (!barChart.id) barChart.id = 0;

		var margin = {top: 10, right: 10, bottom: 20, left: 10},
			x,
			y = d3.scale.linear().range([100, 0]),
			id = barChart.id++,
			axis = d3.svg.axis().orient("bottom"),
			brush = d3.svg.brush(),
			brushDirty,
			dimension,
			group,
			round;

		function chart(div) {
		  var width = x.range()[1],
			  height = y.range()[0];

		  y.domain([0, group.top(1)[0].value]);

		  div.each(function() {
			var div = d3.select(this),
				g = div.select("g");

			// Create the skeletal chart.
			if (g.empty()) {
			  div.select(".title").append("a")
				  .attr("href", "javascript:reset(" + id + ")")
				  .attr("class", "reset")
				  .text("reset")
				  .style("display", "none");

			  g = div.append("svg")
				  .attr("width", width + margin.left + margin.right)
				  .attr("height", height + margin.top + margin.bottom)
				.append("g")
				  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

			  g.append("clipPath")
				  .attr("id", "clip-" + id)
				.append("rect")
				  .attr("width", width)
				  .attr("height", height);

			  g.selectAll(".bar")
				  .data(["background", "foreground"])
				.enter().append("path")
				  .attr("class", function(d) { return d + " bar"; })
				  .datum(group.all());

			  g.selectAll(".foreground.bar")
				  .attr("clip-path", "url(#clip-" + id + ")");

			  g.append("g")
				  .attr("class", "axis")
				  .attr("transform", "translate(0," + height + ")")
				  .call(axis);

			  // Initialize the brush component with pretty resize handles.
			  var gBrush = g.append("g").attr("class", "brush").call(brush);
			  gBrush.selectAll("rect").attr("height", height);
			  gBrush.selectAll(".resize").append("path").attr("d", resizePath);
			}

			// Only redraw the brush if set externally.
			if (brushDirty) {
			  brushDirty = false;
			  g.selectAll(".brush").call(brush);
			  div.select(".title a").style("display", brush.empty() ? "none" : null);
			  if (brush.empty()) {
				g.selectAll("#clip-" + id + " rect")
					.attr("x", 0)
					.attr("width", width);
			  } else {
				var extent = brush.extent();
				g.selectAll("#clip-" + id + " rect")
					.attr("x", x(extent[0]))
					.attr("width", x(extent[1]) - x(extent[0]));
			  }
			}

			g.selectAll(".bar").attr("d", barPath);
		  });

		  function barPath(groups) {
			var path = [],
				i = -1,
				n = groups.length,
				d;
			while (++i < n) {
			  d = groups[i];
			  path.push("M", x(d.key), ",", height, "V", y(d.value), "h9V", height);
			}
			return path.join("");
		  }

		  function resizePath(d) {
			var e = +(d == "e"),
				x = e ? 1 : -1,
				y = height / 3;
			return "M" + (.5 * x) + "," + y
				+ "A6,6 0 0 " + e + " " + (6.5 * x) + "," + (y + 6)
				+ "V" + (2 * y - 6)
				+ "A6,6 0 0 " + e + " " + (.5 * x) + "," + (2 * y)
				+ "Z"
				+ "M" + (2.5 * x) + "," + (y + 8)
				+ "V" + (2 * y - 8)
				+ "M" + (4.5 * x) + "," + (y + 8)
				+ "V" + (2 * y - 8);
		  }
		}

		brush.on("brushstart.chart", function() {
		  var div = d3.select(this.parentNode.parentNode.parentNode);
		  div.select(".title a").style("display", null);
		});

		brush.on("brush.chart", function() {
		  var g = d3.select(this.parentNode),
			  extent = brush.extent();
		  if (round) g.select(".brush")
			  .call(brush.extent(extent = extent.map(round)))
			.selectAll(".resize")
			  .style("display", null);
		  g.select("#clip-" + id + " rect")
			  .attr("x", x(extent[0]))
			  .attr("width", x(extent[1]) - x(extent[0]));
		  dimension.filterRange(extent);
		});

		brush.on("brushend.chart", function() {
		  if (brush.empty()) {
			var div = d3.select(this.parentNode.parentNode.parentNode);
			div.select(".title a").style("display", "none");
			div.select("#clip-" + id + " rect").attr("x", null).attr("width", "100%");
			dimension.filterAll();
		  }
		});

		chart.margin = function(_) {
		  if (!arguments.length) return margin;
		  margin = _;
		  return chart;
		};

		chart.x = function(_) {
		  if (!arguments.length) return x;
		  x = _;
		  axis.scale(x);
		  brush.x(x);
		  return chart;
		};

		chart.y = function(_) {
		  if (!arguments.length) return y;
		  y = _;
		  return chart;
		};

		chart.dimension = function(_) {
		  if (!arguments.length) return dimension;
		  dimension = _;
		  return chart;
		};

		chart.filter = function(_) {
		  if (_) {
			brush.extent(_);
			dimension.filterRange(_);
		  } else {
			brush.clear();
			dimension.filterAll();
		  }
		  brushDirty = true;
		  return chart;
		};

		chart.group = function(_) {
		  if (!arguments.length) return group;
		  group = _;
		  return chart;
		};

		chart.round = function(_) {
		  if (!arguments.length) return round;
		  round = _;
		  return chart;
		};

		return d3.rebind(chart, brush, "on");
	  }
	});
}
