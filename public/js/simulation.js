src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"
src="https://d3js.org/d3.v4.min.js"
src="https://d3js.org/d3-hexbin.v0.2.min.js"
src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.25.6/d3-legend.js"
src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.25.6/d3-legend.min.js"

$(document).ready(function() {
    //needed in order to use ajax calls to get json from SimulationsController for temperature and user
    $.ajaxSetup({
    	headers: {
    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	}
    });
    /*
    //when the pathogen is selected, send the pathogen name to the getTemperatures route
    //that functions searches the database to get the temperatures and inserts them
    //into the drop down menu for temperatures. this affects the growth rate.
    */
    $("#select-pathogen").change(function(){
    	var pathogen_name = $("#select-pathogen :selected").val();
    	$.ajax({
    		url:"{{ route('getTemperatures') }}",
    		method:'post',
    		data:{pathogen_name:pathogen_name},
    		success:function(data)
    		{
    			$('#select-temp option[value="0"]').text(data.low_temp);
    			$('#select-temp option[value="1.5"]').text(data.mid_temp);
    			$('#select-temp option[value="1"]').text(data.high_temp);
    			return doubling_time = data.formula;
    		}
    	});
    });
    /*
    //creating a saved simulation database entry through ajax based on the selected input.
    //all fields must have some value otherwise an alert tells the user to fill all fields
    */
    $("#save-simulation").click(function(){
    	var userID = '<?php echo $user ;?>';
    	if(userID != "-1"){
    		if(($("#select-pathogen :selected").val() != "") && ($("#select-food :selected").val() != '') && ($("#select-temp :selected").val() != '') && ($("#time").val() >= 1) && ($("#cells").val() >= 1) && ($("#title").val() != '')){
    			var pathogen_name = $("#select-pathogen :selected").val();
    			var food_name = $("#select-food :selected").val();
    			var temp = $("#select-temp :selected").text();
    			var time = Number($("#time").val());
    			var cells = Number($("#cells").val());
    			var title = $("#title").val();
    			$.ajax({
    				url:"{{ route('saveSimulation')}}",
    				method:'post',
    				data:{pathogen_name:pathogen_name, food_name:food_name, temp:temp, time:time, 
    					cells:cells, title:title, userID:userID},
    					success:function(data)
    					{
    						swal({text: "Simualtion saved!", type: "success"});
    					},
    					error: function (error) {
    						swal({text: "Something went wrong! Please try again later.", type: "error"});
    					}
    				});
    		}
    		else{
    			swal("INPUT ERROR!", "Please select a Pathogen, a Food, and a Temperature! Length of Time and Starting Cells must be 1 or greater. A title is also required.", "error");
    		}
    	}
    });
    var simulationClicked = 0;
        /*
        //The actually script to run the simulation. Based on all user input the d3.js will populate the screen
        //will hexagons, their colors ranging from white to black with green in-between.
        */
        $("#run-simulation").click(function(){
            //checks if all the fields are not null and greater than 0
            if(($("#select-pathogen :selected").val() != "") && ($("#select-food :selected").val() != '') && ($("#select-temp :selected").val() != '') && ($("#time").val() >= 1) && ($("#cells").val() >= 1)){
            	var checkBox = document.getElementById("time-chk-box");
            	var userID = '<?php echo $user ;?>';
                /*
                //checks the user id passed in from the controller to see if a user is loggedin
                //if the user is logged in update their total simulations run
                */
                if(userID != "-1"){
                	$.ajax({
                		url:"{{ route('updateTotalSimsRun')}}",
                		method:'post',
                		data:{userID:userID}
                	});
                }
                simulationClicked++; //needed to reset/clear the function so two simulations aren't running at the same time.
                //setting the infection dose
                var doubling_counter = 0;
                var infectious_dosage = $("#select-pathogen :selected").attr("id");
                //setting the image http path
                var img = $("#select-food :selected").attr("id");
                //casting the time from user input to a number
                var time = Number($("#time").val());
                var cells = $("#cells").val();
                var temp = $("#temp").val();

                //removing old svg elements so cells don't stay on the screen when a user wants to run the next simulation
                d3.selectAll("svg > *").remove();
                d3.selectAll("svg > path").remove();
                d3.selectAll("svg > g").remove();

                //filling svg variables with defined in the svg tag at the top
                var svg = d3.select("svg"),
                width = +svg.attr("width"),
                height = +svg.attr("height"),
                style = +svg.attr("style");

                var cells = Number(cells), //number of starting cells and total cells
                infectious_dosage = Number(infectious_dosage), //infectious dose
                lot = 1; //length of time
                //doublind time to keep the animations interesting need to be in the double digits
                if(doubling_time > 100){
                    var doublingTime = Math.round(Number(doubling_time)/100) * Number($("#select-temp :selected").val()); //the growth rate in minutes
                    var msg = "(~10 minutes per second)";
                    var speed = 100;
                }
                else{
                	var doublingTime = Number(doubling_time) * Number($("#select-temp :selected").val());
                	var msg = "(1 minutes per second)";
                	var speed = 1;
                }
                var minutes = 0; // Total number of random points.
                //changing the header based on the pathogen selected.
                path_name.innerText = $("#select-pathogen :selected").val() + " on " + $("#select-food :selected").val();
                //resets the num cells and length of time tags to 0
                $("#num_cells").html("Number of Cells: " + cells);
                $("#lot").html("Length of time " + msg + ": " + minutes);

                //giving the svg more variables
                var svg = d3.select("svg"),
                margin = {top: 20, right: 20, bottom: 30, left: 40},
                width = +svg.attr("width") - margin.left - margin.right,
                height = +svg.attr("height") - margin.top - margin.bottom,
                g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                //creating the starting cells
                var rx = d3.randomNormal(width / 2, 80),
                ry = d3.randomNormal(height / 2, 80),
                points = d3.range(cells).map(function() { return [rx(), ry()]; });
                background = d3.range(1).map(function() { return [rx(), ry()]; });
                //setting the color gradient based on infectious dose
                var color = d3.scaleSequential(d3.interpolateLab("white", "green"))
                .domain([0, infectious_dosage/100]);

                //creating the hexagons
                var hexbin = d3.hexbin()
                .radius(20)
                .extent([[0, 0], [width, height]]);

                //recreating the def tag, pattern tag, and image tag to display the background
                var defs = svg.selectAll("defs")
                .data(hexbin(background))
                .enter().append("defs")
                .append("linearGradient")
                .append("pattern")
                .attr("id", "imgpattern")
                .attr("width", "1")
                .attr("height", "1")
                .append("image")
                .attr("id", "imglink")
                .attr("width", "960")
                .attr("height", "500")
                .attr("xlink:href", function(d) { return $("#select-food :selected").attr("id")});

                //recreating the path for the background so it displays on the screen
                var background = svg.selectAll("#background")
                .data(hexbin(background))
                .enter().append("path")
                .attr("id", "background")
                .attr("d", "M 0,0 L 0,960 960,500 960,0  Z")
                .attr("stroke-width", "1")
                .attr("fill", "url(#imgpattern)");

                //giving the hexgon values and appending it to the svg path
                var hexagon = svg.selectAll("path")
                .data(hexbin(points))
                .enter().append("path")
                .attr("d", hexbin.hexagon(19.5))
                .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
                .attr("fill", function(d) { return color(d.length); });

                //adding legend to the svg
                var log = d3.scaleLog()
                .domain([ 1, infectious_dosage/100 ])
                .range(["white", "green"]);
                var svg = d3.select("svg");
                svg.append("g")
                .attr("class", "legendLog")
                .attr("transform", "translate(20,20)");
                var logLegend = d3.legendColor()
                .cells([0, infectious_dosage/1000, infectious_dosage/500, infectious_dosage/250, infectious_dosage/100, infectious_dosage/20, infectious_dosage/10])
                .scale(log);
                svg.select(".legendLog")
                .call(logLegend);


                //the recursive function to add cells until the timer runs out or the infectious dose is reached
                var makeCallback = function() {
                    // note that we're returning a new callback function each time
                    return function(elapsed) {
                        //change based on how fast you want the minutes to calculate (higher makes for weird asynchronous problems)
                        if(lot % 1 != 0)
                        	lot++;
                        else{
                        	minutes++;
                        	lot = 1;
                        	$("#lot").html("Length of time " + msg + ": " + minutes);
                        }
                        //creating a new plot based on the amount of cells
                        points = d3.range(cells).map(function() { return [rx(), ry()]; });
                        
                        //adding those new points to the hexagon
                        hexagon = hexagon
                        .data(hexbin(points));

                        //removing old hexagons
                        hexagon.exit().remove();

                        //adding new hexagon attributes
                        hexagon = hexagon.enter().append("path")
                        .attr("d", hexbin.hexagon(19.5))
                        .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
                        .merge(hexagon)
                        .attr("fill", function(d) { return color(d.length); });

                        //determines whether or not the function continues running and when to double the cells
                        if(minutes < time){
                        	if(!checkBox.checked == true){
                        		if(cells < infectious_dosage){
                        			if(minutes != 0 & minutes%doublingTime == 0){
                        				cells = cells * 2;
                        				doubling_counter++;
                        			}
                        			else
                        				cells = cells;
                        		}
                                //once infectious show the sweet alert
                                else{
                                	simulationClicked = 0;
                                	if(doubling_counter > 0)
                                		var duration = (minutes%10)+(doubling_counter*Number(doubling_time));
                                	else
                                		var duration = (minutes*speed);
                                	swal({
                                		title: 'You will most likely be sick if you eat this!',
                                		text: "Number of Cells: " + cells + " Duration: " + duration + " minutes.",
                                		imageUrl: 'http://www.dadshopper.com/wp-content/uploads/2016/10/21.png',
                                		imageWidth: 210,
                                		imageHeight: 200,
                                		imageAlt: 'Sick emoji',
                                		animation: false
                                	})
                                	return false;
                                }
                            }
                            else{
                            	if(minutes != 0 & minutes%doublingTime == 0)
                            		cells = cells * 2;
                            	else
                            		cells = cells;
                            }
                        }
                        //if the time is reached before infectious don't show the sweet alert and stop the function
                        else{
                        	simulationClicked = 0;
                        	if(doubling_counter > 0)
                        		var duration = (minutes%10)+(doubling_counter*Number(doubling_time));
                        	else
                        		var duration = (minutes*speed);
                        	swal({
                        		title: 'This is what it would look like if the food was left out for that long!',
                        		text: "Number of Cells: " + cells + " Duration: " + duration + " minutes.",
                        		imageUrl: 'http://www.dadshopper.com/wp-content/uploads/2016/10/21.png',
                        		imageWidth: 210,
                        		imageHeight: 200,
                        		imageAlt: 'Sick emoji',
                        		animation: false
                        	})
                        	return false;
                        }

                        $("#num_cells").html("Number of Cells: " + cells);

                        //if the simulation is run during another simulation retrun false on the old function
                        //kind of a hacky way to stop the old function
                        if(simulationClicked == 2){
                        	simulationClicked = 1;
                        	return false;
                        }

                        //recursive call to keep the function running
                        d3.timeout(makeCallback(), 100);
                        return true;
                    }
                };
                //the first recursive call so the simulation actually runs
                var interval = d3.timeout(makeCallback(), 100);
            }
            else{
            	swal("INPUT ERROR!", "Please select a Pathogen, a Food, and a Temperature! Length of Time and Starting Cells must be 1 or greater.", "error");
            }
        });
});