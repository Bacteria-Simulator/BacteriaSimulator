@extends('layouts.master-layout')
@section('title')
<title>Administrator Controls</title>
@endsection
@section('script')
<script>
	//When the page loads, load these jquery functions
	$(document).ready(function() {
		//creates the tabs for view database, and managedatabase
		$( function() {
			$("#tabs").tabs();
		});
		//The drop down tabs for each of the functions in view database
		$( function() {
			$("#accordion_view").accordion({
				//collapsible means you can click on a tab and close it
				collapsible: true,
				heightStyle: "content",
				widthStyle: "content",
				clearStyle: true
			});
		});
		//The drop down tabs for each of the functions in manage database
		$( function() {
			$("#accordion_manage").accordion({
				collapsible: true,
				heightStyle: "content",
				widthStyle: "content",
				clearStyle: true
			});
		});
	});
</script>
@endsection
@section('content')
<?php $user = Auth::user()?>
@if ($user->user_level >= 1)
<body>
	<div class="container" style="margin-top: 50px;">
		@if(session()->has('message'))
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{{ session()->get('message') }}
		</div>
		@endif
		<!-- Creating the tabs -->
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Manage Database</a></li>
				<li><a href="#tabs-2">View Database</a></li>
			</ul>
			<!-- Creating the output in each tab -->
			<div id="tabs-1">
				<!-- Creating the drop downs in each accordion, every new section begins with <h3> -->
					
					<div id="accordion_manage">
						<h3>Add Pathogen</h3>
						<div>
							<!-- bootstrap formatting to keep the form responsive -->
							<div class="container">
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/pathogen') }}">
									<!-- csrf token -->
									{{ csrf_field() }}
									<!-- Creating the label and input for new pathogen name -->
									<div class="form-group{{ $errors->has('pathogen-name') ? ' has-error' : '' }}">
										<label for="pathogen-name" class="col-md-4 control-label">Pathogen Name</label>
										<div class="col-md-6">
											<input id="pathogen-name" type="text" class="form-control" name="pathogen-name">
											@if ($errors->has('pathogen-name'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('pathogen-name') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen info link -->
									<div class="form-group{{ $errors->has('info-link') ? ' has-error' : '' }}">
										<label for="info-link" class="col-md-4 control-label">Link to More Information</label>
										<div class="col-md-6">
											<input id="info-link" type="url" class="form-control" name="info-link">
											@if ($errors->has('info-link'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('info-link') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen image link -->
									<div class="form-group{{ $errors->has('image-link') ? ' has-error' : '' }}">
										<label for="image-link" class="col-md-4 control-label">Link to Pathogen Image</label>
										<div class="col-md-6">
											<input id="image-link" type="url" class="form-control" name="image-link">
											@if ($errors->has('image-link'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('image-link') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen formula -->
									<div class="form-group{{ $errors->has('formula') ? ' has-error' : '' }}">
										<label for="formula" class="col-md-4 control-label">Doubling Time (Minutes)</label>
										<div class="col-md-6">
											<input id="formula" type="text" class="form-control" name="formula">
											@if ($errors->has('formula'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('formula') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen low temp -->
									<div class="form-group{{ $errors->has('low-temp') ? ' has-error' : '' }}">
										<label for="low-temp" class="col-md-4 control-label">Temperature Where Pathogen Can't Grow</label>
										<div class="col-md-6">
											<input id="low-temp" type="text" class="form-control" name="low-temp">
											@if ($errors->has('low-temp'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('low-temp') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen mid temp -->
									<div class="form-group{{ $errors->has('mid-temp') ? ' has-error' : '' }}">
										<label for="mid-temp" class="col-md-4 control-label">Temperature Where Pathogen Grows Slowly</label>
										<div class="col-md-6">
											<input id="mid-temp" type="text" class="form-control" name="mid-temp">
											@if ($errors->has('mid-temp'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('mid-temp') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen high temp -->
									<div class="form-group{{ $errors->has('high-temp') ? ' has-error' : '' }}">
										<label for="high-temp" class="col-md-4 control-label">Temperature Where Pathogen Grows Best</label>
										<div class="col-md-6">
											<input id="high-temp" type="text" class="form-control" name="high-temp">
											@if ($errors->has('high-temp'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('high-temp') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen infectious dose -->
									<div class="form-group{{ $errors->has('infectious') ? ' has-error' : '' }}">
										<label for="infectious" class="col-md-4 control-label">Infectious Dose</label>
										<div class="col-md-6">
											<input id="infectious" type="number" class="form-control" name="infectious">
											@if ($errors->has('infectious'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('infectious') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Add Pathogen
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<h3>Add Food</h3>
						<div>
							<!-- bootstrap formatting to keep the form responsive -->
							<div class="container">
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/food') }}">
									<!-- csrf token -->
									{{ csrf_field() }}
									<!-- Creating the label and input for new food name -->
									<div class="form-group{{ $errors->has('food-name') ? ' has-error' : '' }}">
										<label for="food-name" class="col-md-4 control-label">Food Name</label>
										<div class="col-md-6">
											<input id="food-name" type="text" class="form-control" name="food-name">
											@if ($errors->has('food-name'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('food-name') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new food image link -->
									<div class="form-group{{ $errors->has('cooked') ? ' has-error' : '' }}">
										<label for="cooked" class="col-md-4 control-label">Is Food Cooked</label>
										<div class="col-md-6">
											<select class="form-control" id="cooked" name="cooked">
												<option value="" disabled="disabled" selected="selected">
													Select a Category
												</option>
												<option value="1">Yes</option>
												<option value="2">No</option>
											</select>
											@if ($errors->has('cooked'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('cooked') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new water content -->
									<div class="form-group{{ $errors->has('water-content') ? ' has-error' : '' }}">
										<label for="water-content" class="col-md-4 control-label">Water Content (% of food)</label>
										<div class="col-md-6">
											<input id="water-content" type="number" step=".01" min="0" max="100" class="form-control" name="water-content">
											@if ($errors->has('water-content'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('water-content') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new ph level -->
									<div class="form-group{{ $errors->has('ph') ? ' has-error' : '' }}">
										<label for="ph" class="col-md-4 control-label">PH Level</label>
										<div class="col-md-6">
											<input id="ph" type="number" step=".01" min="0" max="14" class="form-control" name="ph">
											@if ($errors->has('ph'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('ph') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen image link -->
									<div class="form-group{{ $errors->has('image-link') ? ' has-error' : '' }}">
										<label for="image-link" class="col-md-4 control-label">Link to Food Image</label>
										<div class="col-md-6">
											<input id="image-link" type="url" class="form-control" name="image-link">
											@if ($errors->has('image-link'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('image-link') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Add Food
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<h3>Edit Food</h3>
						<div>
							<!-- bootstrap formatting to keep the form responsive -->
							<div class="container">
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/editfood') }}">
									<!-- csrf token -->
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('select-food') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label" for="select-food">Select Food</label>
										<div class="col-md-6">
											<select class="form-control" id="select-food" name="select-food">
												<option value="" disabled="disabled" selected="selected">
													Select a Food
												</option>
												@foreach($foods as $food)
												<option value="{{ $food->food_name }}"> {{ $food->food_name }}</option>
												@endforeach
											</select>
											@if ($errors->has('select-food'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('select-food') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div> 

									<!-- Creating the label and input for new food name -->
									<div class="form-group{{ $errors->has('new-food-name') ? ' has-error' : '' }}">
										<label for="new-food-name" class="col-md-4 control-label">Food Name</label>
										<div class="col-md-6">
											<input id="new-food-name" type="text" class="form-control" name="new-food-name">
											@if ($errors->has('new-food-name'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-food-name') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new food image link -->
									<div class="form-group{{ $errors->has('new-cooked') ? ' has-error' : '' }}">
										<label for="new-cooked" class="col-md-4 control-label">Is Food Cooked</label>
										<div class="col-md-6">
											<select class="form-control" id="new-cooked" name="new-cooked">
												<option value="" disabled="disabled" selected="selected">
													Select a Category
												</option>
												<option value="1">Yes</option>
												<option value="2">No</option>
											</select>
											@if ($errors->has('new-cooked'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-cooked') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new water content -->
									<div class="form-group{{ $errors->has('new-water-content') ? ' has-error' : '' }}">
										<label for="new-water-content" class="col-md-4 control-label">Water Content (% of food)</label>
										<div class="col-md-6">
											<input id="new-water-content" type="number" step=".01" max="100" class="form-control" name="new-water-content">
											@if ($errors->has('new-water-content'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-water-content') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new ph level -->
									<div class="form-group{{ $errors->has('new-ph') ? ' has-error' : '' }}">
										<label for="new-ph" class="col-md-4 control-label">PH Level</label>
										<div class="col-md-6">
											<input id="new-ph" type="number" step=".01" max="14" class="form-control" name="new-ph">
											@if ($errors->has('new-ph'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-ph') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- link to the image -->
									<div class="form-group{{ $errors->has('new-image-link') ? ' has-error' : '' }}">
										<label for="new-image-link" class="col-md-4 control-label">Link to Food Image</label>
										<div class="col-md-6">
											<input id="new-image-link" type="url" class="form-control" name="new-image-link">
											@if ($errors->has('new-image-link'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-image-link') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Edit Food
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>

						<h3>Edit Pathogen</h3>
						<div>
							<!-- bootstrap formatting to keep the form responsive -->
							<div class="container">
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/editpathogen') }}">
									<!-- csrf token -->
									{{ csrf_field() }}
									<div class="form-group{{ $errors->has('pathogen-name') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label" for="select-pathogen">Select Pathogen</label>
										<div class="col-md-6">
											<select class="form-control" id="select-pathogen" name="select-pathogen" onchange="fillEditPathogen()">
												<option value="" disabled="disabled" selected="selected">
													Select a pathogen
												</option>
												@foreach($pathogens as $pathogen)
												<option value="{{ $pathogen->pathogen_name}}"> {{ $pathogen->pathogen_name }}</option>
												@endforeach
											</select>
											@if ($errors->has('select-pathogen'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('select-pathogen') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<script>
											function fillEditPathogen()
											{
												


												//if(document.getElementById("select-pathogen").value != "")
												//{
													document.getElementById("new-pathogen-name").value = document.getElementById("select-pathogen").value;
													//window.alert(var pathogens);
													//for (var i = 0; i < $pathogens.length; i++) {
    												//console.log("PAIR " + i + ": " + $pathogens[i].oid);
    												//console.log("PAIR " + i + ": " + $pathogens[i].cid);
													//}

												//}
												
											}
									</script>
									<div class="form-group{{ $errors->has('new-pathogen-name') ? ' has-error' : '' }}">
										<label for="new-pathogen-name" class="col-md-4 control-label">Pathogen Name</label>
										<div class="col-md-6">
											<input id="new-pathogen-name" type="text" class="form-control" name="new-pathogen-name">
											@if ($errors->has('new-pathogen-name'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-pathogen-name') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen info link -->
									<div class="form-group{{ $errors->has('new-info-link') ? ' has-error' : '' }}">
										<label for="new-info-link" class="col-md-4 control-label">Link to More Information</label>
										<div class="col-md-6">
											<input id="new-info-link" type="url" class="form-control" name="new-info-link">
											@if ($errors->has('new-info-link'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-info-link') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen image link -->
									<div class="form-group{{ $errors->has('new-image-link') ? ' has-error' : '' }}">
										<label for="image-link" class="col-md-4 control-label">Link to Pathogen Image</label>
										<div class="col-md-6">
											<input id="new-image-link" type="url" class="form-control" name="new-image-link">
											@if ($errors->has('new-image-link'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-image-link') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen formula -->
									<div class="form-group{{ $errors->has('new-formula') ? ' has-error' : '' }}">
										<label for="new-formula" class="col-md-4 control-label">Growth Rate Formula</label>
										<div class="col-md-6">
											<input id="new-formula" type="text" class="form-control" name="new-formula">
											@if ($errors->has('new-formula'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-formula') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Creating the label and input for new pathogen infectious dose -->
									<div class="form-group{{ $errors->has('new-infectious-dose') ? ' has-error' : '' }}">
										<label for="new-infectious-dose" class="col-md-4 control-label">Infectious Dose</label>
										<div class="col-md-6">
											<input id="new-infectious-dose" type="number" class="form-control" name="new-infectious-dose">
											@if ($errors->has('new-pathogen-name'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('new-infectious-dose') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Edit Pathogen
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>

						

						@if ($user->user_level == 2)
						<h3>Promote User</h3>
						<div>
							<div class="container">
								<!-- Input users email -->
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/promote') }}">
									{{ csrf_field() }}
									<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
										<label for="email" class="col-md-4 control-label">E-Mail Address</label>
										<div class="col-md-6">
											<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
											@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Promote User
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						@endif
						<h3>Delete Pathogen</h3>
						<div>
							<div class="container">
								<!-- Select pathogen to delete -->
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/delete_pathogen') }}">
									{{ csrf_field() }}
									<!-- Creating the label and input for new pathogen drop down -->
									<div class="form-group">
										<label for="delete-pathogen" class="col-md-4 control-label">Select Pathogen</label>
										<div class="col-md-6">
											<select class="form-control" id="delete-pathogen" name="delete-pathogen">
												<option value="" disabled="disabled" selected="selected">
													Select a Pathogen
												</option>
												@foreach($pathogens as $pathogen)
												<option value="{{ $pathogen->pathogen_name }}"> {{ $pathogen->pathogen_name }}</option>
												@endforeach
											</select>
											@if ($errors->has('delete-pathogen'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('delete-pathogen') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Delete Pathogen
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<h3>Delete Food</h3>
						<div>
							<div class="container">
								<!-- Select food to delete -->
								<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/delete_food') }}">
									{{ csrf_field() }}
									<!-- Creating the label and input for food drop down -->
									<div class="form-group{{ $errors->has('') ? ' has-error' : '' }}">
										<label for="delete-food" class="col-md-4 control-label">Select Food</label>
										<div class="col-md-6">
											<select class="form-control" id="delete-food" name="delete-food">
												<option value="" disabled="disabled" selected="selected">
													Select a Food
												</option>
												@foreach($foods as $food)
												<option value="{{ $food->food_name }}"> {{ $food->food_name }}</option>
												@endforeach
											</select>
											@if ($errors->has('delete-food'))
                                				<span class="help-block">
                                    				<strong>{{ $errors->first('delete-food') }}</strong>
                                				</span>
                                			@endif
										</div>
									</div>
									<!-- Submit button -->
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Delete Food
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						@if ($user->user_level == 2)
						<h3>Demote User</h3>
						<div>
							<!-- Input users email -->
							<form class="form-horizontal" method="POST" action="{{ secure_url('admin_controls/demote') }}">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="email" class="col-md-4 control-label">E-Mail Address</label>
									<div class="col-md-6">
										<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
										@if ($errors->has('email'))
										<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
									</div>
								</div>
								<!-- Submit button -->
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Demote User
										</button>
									</div>
								</div>
							</form>
						</div>
						@endif
					</div>
				</div>
				<!-- this is the tab for the view database each accordion has a table filled with their resective information -->
				<div id="tabs-2">
					<div id="accordion_view">
						<!-- this is an accordion for view pathogens from the database -->
						<h3>View Pathogens</h3>
						<div>
							<table class="table table-hover table-striped table-bordered">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Pathogen Name</th>
										<th scope="col">Doubling Time (Minutes)</th>
										<th scope="col">Infectious Dosage</th>
										<th scope="col">Low Temperature (No growth)</th>
										<th scope="col">Mid Temperature (Slow growth)</th>
										<th scope="col">High Temperature (Normal growth)</th>
										<th scope="col">Description Link</th>
										<th scope="col">Image Link</th>
									</tr>
								</thead>
								<tbody>
									@foreach($pathogens as $pathogen)
									<tr>
										<th scope="col">{{ $pathogen->path_id }}</th>
										<td>{{ $pathogen->pathogen_name }}</td>
										<td>{{ $pathogen->formula }}</td>
										<td>{{ $pathogen->infectious_dose }}</td>
										<td>{{ $pathogen->low_temp }}</td>
										<td>{{ $pathogen->mid_temp }}</td>
										<td>{{ $pathogen->high_temp }}</td>										
										<td>{{ $pathogen->desc_link }}</td>
										<td>{{ $pathogen->image }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<!-- this is an accordion for view foods from the database -->
						<h3>View Food</h3>
						<div>
							<table class="table table-hover table-bordered table-striped">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">Food Name</th>
										<th scope="col">Cooked</th>
										<th scope="col">Water Content</th>
										<th scope="col">PH Level</th>
										<th scope="col">Image Link</th>
									</tr>
								</thead>
								<tbody>
									@foreach($foods as $food)
									<tr>
										<th scope="col">{{ $food->food_id }}</th>
										<td>{{ $food->food_name }}</td>
										<td>@if($food->cooked == 1) True @else False @endif</td>
										<td>{{ $food->available_water }}</td>
										<td>{{ $food->ph_level }}</td>
										<td>{{ $food->image_link }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<!-- this is an accordion for view administrators from the database -->
						<h3>View Adminstrators</h3>
						<div>
							<table class="table table-hover table-bordered table-striped">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">User Email</th>
										<th scope="col">User Type</th>
									</tr>
								</thead>
								<tbody>
									@foreach($admins as $admin)
									@if ($admin->user_level >= 1)
									<tr>
										<th class="data_id" scope="col">{{ $admin->id }}</th>
										<td>{{ $admin->email }}</td>
										@if ($user->user_type == 1)<td>Educator</td>
                                    	@elseif ($user->user_type == 2) <td>Student</td>
                                    	@else <td>General Public</td>
                                    	@endif
									</tr>
									@endif
									@endforeach
								</tbody>
							</table>
						</div>
						<h3>View Completed Simulations</h3>
						<div>
							<table class="table table-hover table-bordered table-striped">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">User Email</th>
										<th scope="col">User Type</th>
										<th scope="col">Pathogen Name</th>
										<th scope="col">Food Name</th>
										<th scope="col">Selected Temperature</th>
										<th scope="col">Starting Cells</th>
										<th scope="col">Length of Time</th>
										<th scope="col">Doubling Time</th>
										<th scope="col">Infectious Dosage</th>
										<th scope="col">Growth Rate</th>
									</tr>
								</thead>
								<tbody>
									@foreach($simulations as $simulation)
									<tr>
										<th class="data_id" scope="col">{{ $simulation->id }}</th>
										<td>{{ $simulation->user_email }}</td>
										<td>{{ $simulation->person_type }}</td>
										<td>{{ $simulation->pathogen_name }}</td>
										<td>{{ $simulation->food_name }}</td>
										<td>{{ $simulation->temp }}</td>
										<td>{{ $simulation->cells }}</td>
										<td>{{ $simulation->time }}</td>
										<td>{{ $simulation->doubling_time }}</td>
										<td>{{ $simulation->infectious_dosage }}</td>
										<td>{{ $simulation->growth_rate }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	@endsection
	@endif