<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Pathogen;
use App\Food;
use App\User;
use App\DataCollection;
use App\SavedSimulations;
use Auth;
use Hash;
use Session;
use Illuminate\Support\Facades\Redirect;

class SimulationsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if visible is 0 the simulation page knows to not look for pre determined selections
        $visible = 0;
        //pathogen and food variables in an array from the database
        $pathogens = Pathogen::all();
        $foods = Food::all();
        if(\Auth::user() != null){
            $user = \Auth::user()->id;
        }
        else{
            $user = -1;
        }
        //returning the simulations page with pathogen and food variables
        return view('simulations', ['pathogens' => $pathogens,
            'foods' => $foods, 'user'=>$user, 'visible'=>$visible]);
    }

    public function getTemperatures(Request $request)
    {
        $pathogen = $request->pathogen_name;
        $path = DB::table('pathogen')->where('pathogen_name', $pathogen)->first();
        return response()->json($path);
    }

    public function updateTotalSimsRun(Request $request)
    {
        //get the user based on the id sent in and increment their simulations run
        $user_id = $request->userID;
        $user = User::where('id', $user_id) -> first();
        $user->total_sims_run = $user->total_sims_run + 1;
        $user->save();
        return response()->json($user);
    }

    public function saveSimulation(Request $request)
    {
        //if none of the input fields are empty, create a new pathogen, otherwise return the page with an error
        if(($request->input('pathogen_name') != '') && ($request->input('food_name') != '') && ($request->input('temp') != '') && ($request->input('time') != '') && ($request->input('cells') != '') && ($request->input('title') != '')){
            SavedSimulations::create([
                'pathogen_name' => $request->input('pathogen_name'),
                'food_name' => $request->input('food_name'),
                'temp' => $request->input('temp'),
                'time' => $request->input('time'),
                'cells' => $request->input('cells'),
                'simulation_name' => $request->input('title'),
                'infectious_dosage' => $request->input('infectious_dosage'),
                'doubling_time' => $request->input('doubling'),
                'img' => $request->input('img'),
                'growth_rate' => $request->input('growth_rate'),
                'user_id' => $request->input('userID')
            ]);
        }
    }

    public function getSavedSimulations(){
        $user_id = \Auth::user()->id;
        $saved_simulations = SavedSimulations::where('user_id', $user_id)->get();
        //returning the simulations page with pathogen and food variables
        return view('saved_simulations', ['saved_simulations'=>$saved_simulations, 'user'=>$user_id]);
    }

    public function deleteSavedSimulations(Request $request){
        //get the simulation based on the id sent to the function and delete it from the database
        $sim_id = $request->input('saved_sim_id');
        $saved_simulations = SavedSimulations::where('saved_sim_id', $sim_id)->first();
        $saved_simulations->delete();
    }

    public function collectData(Request $request)
    {
        //every time a simulation is run put that information in the database
        //if the user is a guest put in guest user information
        if($request->input('userID') === "-1"){
            $userID = 0;
            $user_email = "GUEST";
            $user_type = "GUEST";
        }
        else{
            $user_id = $request->input('userID');
            $user = User::where('id', $user_id)->first();
            $user_email = $user->email;
            $type = $user->user_type;
            if($type == 1){
                $user_type = "Educator";
            }
            elseif($type == 2){
                $user_type = "Student";
            }
            else{
                $user_type = "General Public";
            }
        }
        DataCollection::create([
            'pathogen_name' => $request->input('pathogen_name'),
            'food_name' => $request->input('food_name'),
            'temp' => $request->input('temp'),
            'time' => $request->input('time'),
            'cells' => $request->input('cells'),
            'infectious_dosage' => $request->input('infectious_dosage'),
            'doubling_time' => $request->input('doubling'),
            'growth_rate' => $request->input('growth_rate'),
            'user_email' => $user_email,
            'person_type' => $user_type
        ]);
    }
}
