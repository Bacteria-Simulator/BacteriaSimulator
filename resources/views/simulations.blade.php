@extends('layouts.master-layout')
@section('title')
<title>Run Simulation</title>
@endsection
@section('script')
        <!-- creates the container that will hold all of the cells that populate.
        the defs/pattern are here to hold the background image.
        the path tag draws a square and fills it with the pattern. -->
        <svg id="svgtag" width="960" height="500" style="border: 1pt solid black">
            <defs>
                <pattern id="imgpattern" width="1" height="1">
                    <image id="imglink" width="960" height="500"
                    xlink:href=""/>
                </pattern>
            </defs>
            <path fill="url(#imgpattern)" stroke-width="1"
            d="M 0,0 L 0,960 960,500 960,0  Z" />
        </svg>
        <!-- sources for the alert and hexagons/cells that will be colored -->
        <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
        <script src="https://d3js.org/d3.v4.min.js"></script>
        <script src="https://d3js.org/d3-hexbin.v0.2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.25.6/d3-legend.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.25.6/d3-legend.min.js"></script>
        <!--<script src="{{ asset('js/simulation.js') }}"></script>-->
        <script>
    //When the page loads, load these jquery functions
    $(document).ready(function() {
        var visible = <?php echo $visible; ?>;
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
                url:"{{ route('getTemperatures')}}",
                method:'post',
                data:{pathogen_name:pathogen_name},
                success:function(data)
                {
                    $('#select-temp option[value="0"]').text(data.low_temp);
                    $('#select-temp option[value="1.5"]').text(data.mid_temp);
                    $('#select-temp option[value="1"]').text(data.high_temp);
                    $("#link").attr("href", data.desc_link)
                    $("#link").text(data.desc_link);
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
                if(($("#select-pathogen :selected").val() != "") && ($("#select-food :selected").val() != '') && ($("#select-temp :selected").val() != '') && ($("#time").val() >= 1) && ($("#cells").val() >= 1) && ($("#time").val() <= 1000) && ($("#cells").val() <= 1001) && ($("#title").val() != '')){
                    var pathogen_name = $("#select-pathogen :selected").val();
                    var food_name = $("#select-food :selected").val();
                    var temp = $("#select-temp :selected").text();
                    var time = Number($("#time").val());
                    var cells = Number($("#cells").val());
                    var title = $("#title").val();
                    var infectious_dosage = $("#select-pathogen :selected").attr("id");
                    var img = $("#select-food :selected").attr("id");
                    var doubling = doubling_time;
                    var growth_rate = $("#select-temp :selected").val();
                    $.ajax({
                        url:"{{ route('saveSimulation')}}",
                        method:'post',
                        data:{pathogen_name:pathogen_name, food_name:food_name, temp:temp, time:time, 
                            cells:cells, title:title, infectious_dosage:infectious_dosage, doubling:doubling_time, img:img, growth_rate:growth_rate, userID:userID},
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
                    swal("INPUT ERROR!", "Please select a Pathogen, a Food, and a Temperature! Length of Time and Starting Cells must be greater than 0 and less than 1000. A title is also required.", "error");
                }
            }
        });
        var simulationClicked = 0;
        //when the run simulation button is clicked we take the input from the user and print it out
        $("#run-simulation").click(function(){
            //checks if all the fields are not null and greater than 0
            if(($("#select-pathogen :selected").val() != "") && ($("#select-food :selected").val() != '') && ($("#select-temp :selected").val() != '') && ($("#time").val() >= 1) && ($("#cells").val() >= 1) && ($("#time").val() <= 1000) && ($("#cells").val() <= 1001)){
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
                /*
                //this method collects the data from the currently run simulation
                */
                var pathogen_name = $("#select-pathogen :selected").val();
                var food_name = $("#select-food :selected").val();
                var temp = $("#select-temp :selected").text();
                var time = Number($("#time").val());
                var cells = Number($("#cells").val());
                var infectious_dosage = $("#select-pathogen :selected").attr("id");
                var doubling = doubling_time;
                var growth_rate = $("#select-temp :selected").val();
                $.ajax({
                    url:"{{ route('collectData')}}",
                    method:'post',
                    data:{pathogen_name:pathogen_name, food_name:food_name, temp:temp, time:time, 
                        cells:cells, infectious_dosage:infectious_dosage, doubling:doubling_time, growth_rate:growth_rate, userID:userID}
                    });
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
                //doubling time to keep the animations interesting need to be in the double digits
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
              //  $("#lot").html("Length of time " + msg + ": " + minutes);
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
                .attr("transform", "translate(10,10)");
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
                                    if(doubling_counter > 0 && speed > 1)
                                        var duration = (minutes%10)+(doubling_counter*Number(doubling_time));
                                    else
                                        var duration = (minutes*speed);
                                    swal({
                                        title: 'Food is unsafe to eat!',
                                        text: "Number of Cells: " + cells + "       Duration: " + duration + " minutes.",
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
                            if(doubling_counter > 0 && speed > 1)
                                var duration = (minutes%10)+(doubling_counter*Number(doubling_time));
                            else
                                var duration = (minutes*speed);
                            if (cells < infectious_dosage) {
                            swal({
                                title: 'Food is safe to eat!',
                                text: "Number of Cells: " + cells + "       Duration: " + duration + " minutes.",
                                imageUrl: 'https://cdn.shopify.com/s/files/1/1061/1924/products/Slightly_Smiling_Face_Emoji_87fdae9b-b2af-4619-a37f-e484c5e2e7a4_large.png?v=1480481059',
                                imageWidth: 210,
                                imageHeight: 200,
                                imageAlt: 'Sick emoji',
                                animation: false
                            })}
                            else {
                                swal({
                                        title: 'Food is unsafe to eat!',
                                        text: "Number of Cells: " + cells + "       Duration: " + duration + " minutes.",
                                        imageUrl: 'http://www.dadshopper.com/wp-content/uploads/2016/10/21.png',
                                        imageWidth: 210,
                                        imageHeight: 200,
                                        imageAlt: 'Sick emoji',
                                        animation: false
                                    })}
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
</script>
@endsection
@section('content')
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline">
                <div class="col-md-4">
                    <!-- Creating the label and input for pathogen drop down-->
                    <div class="col-md-2">
                        <label for="select-pathogen">Pathogen: </label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" id="select-pathogen" name="select-pathogen">
                            <option value="" disabled="disabled" selected="selected">
                                -- Select a Pathogen --
                            </option>
                            @foreach($pathogens as $pathogen)
                            <option id="{{ $pathogen->infectious_dose }}" value="{{ $pathogen->pathogen_name }}"> {{ $pathogen->pathogen_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Creating the label and input for food drop down-->
                    <div class="col-md-2">
                        <label>Food: </label>
                    </div>
                    <select class="form-control" id="select-food" name="select-food">
                        <option value="" disabled="disabled" selected="selected">
                            -- Select a Food --
                        </option>
                        @foreach($foods as $food)
                        <option id="{{ $food->image_link }}" value="{{ $food->food_name }}"> {{ $food->food_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <!-- Creating the label and drop down for temperature -->
                    <div class="col-md-6">
                        <label>Temperature (F): </label>
                    </div>
                    <div class="col-md-1">
                        <select class="form-control" id="select-temp" name="select-temp">
                            <option value="" disabled="disabled" selected="selected">
                                -- Select a Temp --
                            </option>
                            <option id="low_temp" value="0"></option>
                            <option id="mid_temp" value="1.5"></option>
                            <option id="high_temp" value="1"></option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Creating the label and input for length of time -->
            <div class="col-md-4">
                <div class="col-md-7">
                    <label>Length of Time(seconds):
                           (1 second = 1 minute)</label>
                </div>
                <div class="col-md-1">
                <!-- represents the length of time in minutes with the min being 1 and max at 1000 -->
                    <input type="number" name="time" id="time" value="1" min="1" max="1000" style="width: 50px;">
                </div>
            </div>
            <!-- checkbox for letting simulation run until time runs out -->
            <div class="col-md-4">
                <div class="col-md-5" style="margin-right: 15px">
                    <label>Full Duration:</label>
                </div>
                <div class="col-md-2" style="margin-right: 15px">
                    <input type="checkbox" name="time-chk-box" id="time-chk-box">
                </div>
            </div>
            <!-- Creating the label and input for starting cells -->
            <div class="col-md-4">
                <div class="col-md-6 col-sm-offset-1" style="margin-right: 15px">
                    <label>Starting Cells:</label>
                </div>
                <div class="col-md-1" style="margin-right: 15px">
                    <input type="number" name="cells" id="cells" value="1" min="1" max="1001" step="100" style="width: 50px;">
                </div>
            </div>
        </form>
    </div>
    <!-- Creating the run simulations button -->
    <div>
        <br>
        <button id="run-simulation" name="run-simulation" class="btn btn-primary">
            Run Simulation
        </button>
        <br>
    </div>
</div>
</br>
<center>
    <h3 id="path_name"></h3>
    <label id="legend_info" style="position: absolute; top: 400px; left: 170px;">Cells</label>
    <label id="legend_info" style="position: absolute; top: 410px; left: 170px;">per</label>
    <label id="legend_info" style="position: absolute; top: 420px; left: 170px;">Hexagon:</label>
    <label id="num_cells">Number of Cells: 0</label>
    <label id="lot" class="col-md-offset-1">Length of Time (Minutes): 0</label>
    <label class="col-md-offset-1" for="link">More Info: </label><a id="link" href=""></a>
</center>
@endsection
@section('save_simulation')
@if(!Auth::guest())
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline">
                <!-- Creating the label and input for length of time -->
                <label class="col-md-offset-7">Title this Simulation:</label>
                <input type="text" name="title" id="title">
                <button id="save-simulation" name="save-simulation" class="btn btn-primary">
                    Save Simulation
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
