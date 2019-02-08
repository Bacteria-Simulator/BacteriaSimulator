<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pathogen;
use App\Food;
use App\User;
use App\DataCollection;
use Auth;
use Hash;
use Session;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    /** 
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account');
    }

    /**
    *Show the admin controls view
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function adminControls()
    {
        //creating and returning variables for pathogens, foods, and admins from the database
        $pathogens = Pathogen::all();
        $foods = Food::all();
        $admins = User::all();
        $simulations = DataCollection::all();
        return view('admin_controls', ['pathogens' => $pathogens,
            'foods' => $foods,
            'admins' => $admins, 'simulations' => $simulations]);
    }

    /**
    *post method to add a pathogen
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function addPathogen(Request $request)
    {   
        //if none of the input fields are empty, create a new pathogen, otherwise return the page with an error
        if(($request->input('pathogen-name') != '') && ($request->input('info-link') != '') && ($request->input('image-link') != '') && ($request->input('formula') != '') && ($request->input('infectious') != '')){
            Pathogen::create([
                'pathogen_name' => $request->input('pathogen-name'),
                'desc_link' => $request->input('info-link'),
                'image' => $request->input('image-link'),
                'formula' => $request->input('formula'),
                'low_temp' => $request->input('low-temp'),
                'mid_temp' => $request->input('mid-temp'),
                'high_temp' => $request->input('high-temp'),
                'infectious_dose' => $request->input('infectious')
            ]);
        }
        else
        {
            return Redirect::back()->withErrors(['The entire form must be filled out', 'The pathogen was not added to the database.']);
        }
        return redirect()->back()->with('message', 'Pathogen successfully added!');
    }

    /**
    *post method to add a food
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function addFood(Request $request)
    {
        //if none of the input fields are empty, create a new food, otherwise return the page with an error
        if(($request->input('food-name') != '') && ($request->input('cooked') != '') && ($request->input('water-content') != '') && ($request->input('ph') != '') && ($request->input('image-link') != '')){
            Food::create([
                'food_name' => $request->input('food-name'),
                'cooked' => $request->input('cooked'),
                'available_water' => $request->input('water-content'),
                'ph_level' => $request->input('ph'),
                'image_link' => $request->input('image-link')
            ]);
        }
        else{
            return Redirect::back()->withErrors(['The entire form must be filled out', 'The food was not added to the database.']);
        }
        return redirect()->back()->with('message', 'Food successfully added!');
    }

    /**
    *post method to promote a user to admin
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function promote(Request $request)
    {
        //get the email from the form and set the user level to 1 (which is co-admin), then redirect back to the page
        $email = $request->input('email');
        if ($email != ''){
            $user = User::where('email', $email) -> first();
            $user->user_level = 1;
            $user->save();
            return redirect()->back()->with('message', 'User successfully promoted!');
        }
        else {
            return Redirect::back()->withErrors(['No email address entered', 'The account has not been updated. Please double check the email address.']);
        }
        
    }

    /**
    *post method to demote a user to admin
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function demote(Request $request)
    {
        //get the email from the form and set the user level to 1 (which is co-admin), then redirect back to the page
        $email = $request->input('email');
        if ($email != ''){
            $user = User::where('email', $email) -> first();
            $user->user_level = 0;
            $user->save();
            return redirect()->back()->with('message', 'User successfully demoted!');
        }
        else {
            return Redirect::back()->withErrors(['No email address entered', 'The account has not been updated. Please double check the email address.']);
        }
    }

    /**
    *post method to delete a food from the database
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function deleteFood(Request $request)
    {
        //get the food from the form and delete it from the database
        $food_name = $request->get('delete-food');
        
        if ($food_name != ''){
            Food::where('food_name', $food_name) -> delete();
            return redirect()->back()->with('message', 'Pathogen successfully updated!');
        }
        else {
            return Redirect::back()->withErrors(['Something went wrong', 'Food not deleted.']);
        }
    }

    /**
    *post method to delete a pathogen from the database
    *
    * @return \Illuminate\Http\Response
    *
    */
    public function deletePathogen(Request $request)
    {
        //get the pathogen from the form and delete it from the database
        $pathogen_name = $request->input('delete-pathogen');
        if ($pathogen_name != ''){
            Pathogen::where('pathogen_name', $pathogen_name) -> delete();
            return redirect()->back()->with('message', 'Pathogen successfully deleted!');
        }
        else {
            return Redirect::back()->withErrors(['Something went wrong', 'Pathogen not deleted.']);
        }
    }
    //edit food function
    public function editFood(Request $request)
    {
        //get the correct food from the database based on the name
        $food_name = $request->get('select-food');
        $food = Food::where('food_name', '=', $food_name)->first();
        //if any fields are empty we make the new value equal to the old value
        if(!$request->input('new-food-name') == ""){
            $new_food_name = $request->input('new-food-name');
        }
        else{
            $new_food_name = $food->food_name;
        }
        if(!$request->input('new-cooked') == ""){
            $new_cooked = $request->input('new-cooked');
        }
        else{
            $new_cooked = $food->cooked;
        }
        if(!$request->input('new-water-content') == ""){
            $new_water = $request->input('new-water-content');
        }
        else{
            $new_water = $food->available_water;
        }
        if(!$request->input('new-ph') == ""){
            $new_ph = $request->input('new-ph');
        }
        else{
            $new_ph = $food->ph_level;
        }
        if(!$request->input('new-image-link') == ""){
            $new_image = $request->input('new-image-link');
        }
        else{
            $new_image = $food->image_link;
        }
        //update the pathogen with the new values
        $food -> update([
            'food_name' => $new_food_name,
            'cooked' => $new_cooked,
            'available_water' => $new_water,
            'ph_level' => $new_ph,
            'image_link' => $new_image
        ]);
        //return with a success message
        return redirect()->back()->with('message', 'Food successfully updated!');
    }
    //edit pathogen function
    public function editPathogen(Request $request)
    {
        //get the correct pathogen from the database based on the name
        $pathogen_name = $request->get('select-pathogen');
        $path = Pathogen::where('pathogen_name', '=', $pathogen_name)->first();
        //if any fields are empty we make the new value equal to the old value
        if(!$request->input('new-pathogen-name') == ""){
            $new_path_name = $request->input('new-pathogen-name');
        }
        else{
            $new_path_name = $path->pathogen_name;
        }
        if(!$request->input('new-formula') == ""){
            $new_formula = $request->input('new-formula');
        }
        else{
            $new_formula = $path->formula;
        }
        if(!$request->input('new-image-link') == ""){
            $new_image_link = $request->input('new-image-link');
        }
        else{
            $new_image_link = $path->image;
        }
        if(!$request->input('new-info-link') == ""){
            $new_info_link = $request->input('new-info-link');
        }
        else{
            $new_info_link = $path->desc_link;
        }
        if(!$request->input('new-infectious-dose') == ""){
            $new_infectious_dose = $request->input('new-infectious-dose');
        }
        else{
            $new_infectious_dose = $path->infectious_dose;
        }
        //update the pathogen with the new values
        $path -> update([
            'pathogen_name' => $new_path_name,
            'desc_link' => $new_info_link,
            'image' => $new_image_link,
            'formula' => $new_formula,
            'infectious_dose' => $new_infectious_dose
        ]);
        //return with a success message
        return redirect()->back()->with('message', 'Pathogen successfully updated!');
    }
}
