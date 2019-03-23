@extends('layouts.master-layout')
@section('content')
<div class="container" style="margin-top: 50px;">
	<body>
		<!-- creating the table that holds all of the saved simulations for a specific user -->
		<table class="table table-hover table-bordered table-striped">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Title</th>
					<th scope="col">Selected Pathogen</th>
					<th scope="col">Selected Food</th>
					<th scope="col">Selected Temp</th>
					<th scope="col">Length of Time</th>
					<th scope="col">Number of Cells</th>
					<th scope="col">Doubling Time</th>
					<th scope="col">Infectious Dosage</th>
					<th scope="col">Growth Rate</th>
					<th scope="col">Run Again</th>
					<th scope="col">Delete</th>
				</tr>
			</thead>
			<!-- filling the table with the information from the database -->
			<tbody>
				@foreach($saved_simulations as $saved_simulation)
				<tr>
					<th class="saved_sim_id" scope="col">{{ $saved_simulation->saved_sim_id }}</th>
					<td class="simulation_name">{{ $saved_simulation->simulation_name }}</td>
					<td class="pathogen_name">{{ $saved_simulation->pathogen_name }}</td>
					<td class="food_name">{{ $saved_simulation->food_name }}</td>
					<td class="temp">{{ $saved_simulation->temp }}</td>
					<td class="time">{{ $saved_simulation->time }}</td>
					<td class="cells">{{ $saved_simulation->cells }}</td>
					<td class="doubling_time">{{ $saved_simulation->doubling_time }}</td>
					<td class="infectious_dosage">{{ $saved_simulation->infectious_dosage }}</td>
					<td class="growth_rate">{{ $saved_simulation->growth_rate }}</td>
					<td class="img d-lg-none">{{ $saved_simulation->img }}</td>
					<td><button name="run-simulation" class="run-simulation btn btn-sm btn-primary">
						Run Simulation
					</button></td>
					<td><button name="delete-simulation" class="delete-simulation btn btn-sm btn-danger">
						Delete Simulation
					</button></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
	<center>
		<h3 id="path_name"></h3>
		<label id="num_cells">Number of Cells: 0</label>
		<label id="lot" class="col-md-offset-1">Length of Time (Minutes): 0</label>
	</center>
</div>
@endsection
@section('script')
<!-- all the functions from the simulations page -->
<svg id="svgtag" width="960" height="500" style="border: 2pt solid maroon">
	<defs>
		<pattern id="imgpattern" width="1" height="1">
			<image id="imglink" width="960" height="500"
			xlink:href=""/>
		</pattern>
	</defs>
	<path fill="url(#imgpattern)" stroke-width="1"
	d="M 0,0 L 0,960 960,500 960,0  Z" />
</svg>
<script>
	$(document).ready(function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$(".delete-simulation").click(function(){
			var saved_sim_id = $(this).closest('tr').find('.saved_sim_id').text();
			$.ajax({
				url:"{{ route('delete_saved_sim') }}",
				mehtod:"post",
				data:{saved_sim_id:saved_sim_id},
				success:function(data)
				{
					location.reload();
				}
			})
		});
		var simulationClicked = 0;
		$(".run-simulation").click(function(){
			document.getElementById('path_name').scrollIntoView()
			var pathogen_name = $(this).closest('tr').find('.pathogen_name').text();
			var food_name = $(this).closest('tr').find('.food_name').text();
			var temp = Number($(this).closest('tr').find('.temp').text());
			var time = Number($(this).closest('tr').find('.time').text());
			var cells = Number($(this).closest('tr').find('.cells').text());
			var img = $(this).closest('tr').find('.img').text();
			var infectious_dosage = Number($(this).closest('tr').find('.infectious_dosage').text());
			var doubling_time = Number($(this).closest('tr').find('.doubling_time').text());
			var growth_rate = Number($(this).closest('tr').find('.growth_rate').text());
			var userID = '<?php echo $user ;?>';
			if(userID != "-1"){
				$.ajax({
					url:"{{ route('updateTotalSimsRun') }}",
					method:'post',
					data:{userID:userID}
				});
			}
			$.ajax({
				url:"{{ route('collectData')}}",
				method:'post',
				data:{pathogen_name:pathogen_name, food_name:food_name, temp:temp, time:time, 
					cells:cells, infectious_dosage:infectious_dosage, doubling:doubling_time, growth_rate:growth_rate, userID:userID}
				});
			simulationClicked++;
			var doubling_counter = 0;
			d3.selectAll("svg > *").remove();
			d3.selectAll("svg > path").remove();
			d3.selectAll("svg > g").remove();
			var svg = d3.select("svg"),
			width = +svg.attr("width"),
			height = +svg.attr("height"),
			style = +svg.attr("style");
			var lot = 1;
			if(doubling_time > 100){
				var doublingTime = Math.round(Number(doubling_time)/100) * growth_rate;
				var msg = "(~10 minutes per second)";
				var speed = 100;
			}
			else{
				var doublingTime = Number(doubling_time) * growth_rate;
				var msg = "(1 minutes per second)";
				var speed = 1;
			}
			var minutes = 0;
			path_name.innerText = pathogen_name + " on " + food_name;
			$("#num_cells").html("Number of Cells: " + cells);
			$("#lot").html("Length of time " + msg + ": " + minutes);
			var svg = d3.select("svg"),
			margin = {top: 20, right: 20, bottom: 30, left: 40},
			width = +svg.attr("width") - margin.left - margin.right,
			height = +svg.attr("height") - margin.top - margin.bottom,
			g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");
			var rx = d3.randomNormal(width / 2, 80),
			ry = d3.randomNormal(height / 2, 80),
			points = d3.range(cells).map(function() { return [rx(), ry()]; });
			background = d3.range(1).map(function() { return [rx(), ry()]; });
			var color = d3.scaleSequential(d3.interpolateLab("white", "green"))
			.domain([0, infectious_dosage/100]);
			var hexbin = d3.hexbin()
			.radius(20)
			.extent([[0, 0], [width, height]]);
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
			.attr("xlink:href", function(d) { return img });

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
	                    	//if(!checkBox.checked == true){
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
	                            	if(doubling_counter > 0 && speed > 1)
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
	                        /*}
	                        else{
	                        	if(minutes != 0 & minutes%doublingTime == 0)
	                        		cells = cells * 2;
	                        	else
	                        		cells = cells;
	                        }*/
	                    }
	                    //if the time is reached before infectious don't show the sweet alert and stop the function
	                    else{
	                    	simulationClicked = 0;
	                    	if(doubling_counter > 0 && speed > 1)
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
	        });
});
</script>
@endsection
