@extends('layouts.master-layout')
@section('content')
<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Bacterial Simulator
            </div>
            <div class="info">
                <p>Bacterial Simulator is a web app that is mobile responsive that will visually simulate bacteria growth on food. The growth of bacteria displays how food would change based on parameters set by the end user (such as time of day, length of exposure, humidity, temperature, etc). There will be a select database of select foods, since food-borne pathogens are typically affiliated with specific foods. The database will include a selection of built-in bacteria species, but will also provide the opportunity for the user to be able to enter new ones. The user will also be able to save settings in order to recreate a previous simulation.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
@endsection