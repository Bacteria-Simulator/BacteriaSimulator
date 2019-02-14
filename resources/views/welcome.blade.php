@extends('layouts.master-layout')
@section('title')
<title>Welcome to Bacterial Growth Simulator</title>
@endsection
@section('content')
<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Bacterial Growth Simulator
            </div>
            <div class="container">
                <p>Welcome to the Bacterial Growth Simulator. The purpose of this site is to simulate how the factors of time and temperature can influence the rate of bacterial growth to unsafe levels on commonly associated foods.<br><br>In addition to time and temperature, other factors can affect the safety of our food, including the nutrients available in food, the amount of acidity, oxygen (or lack thereof), and moisture.  If these conditions are favorable, then bacteria can grow more quickly. However, this site will focus upon time and temperature.<br><br>You may run a simulation any time that you want, but to save a simulation you will need to set up a user account.  This account will be used only to track how the site is being used and will be available for the user to start and re-start simulations as needed.  No personal information will be collected, and all information shared will be kept confidential.<br><br>Disclaimer: The information in these simulation(s) is to be used for educational purposes only and not as a predictor of food safety.<br><br><br>To start the simulation(s), click <a role="button" class="btn btn-info" id='simulations' href="/simulations">Run Simulation</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
