@extends('layouts.master-layout')
@section('title')
<title>Account</title>
@endsection
@section('content')
<!-- container class holds everything in a responsive box
    row class sets each new form into a new row (12 cols per line)
    col-md-# defines the column size of the div, offset sets the column that many cols to the right -->
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Account Information: </div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <!-- a form group holds a set of labels and inputs -->
                            <div class="form-group">
                                <!-- this displays the user account type from the database -->
                                <label for="account_type" class="col-md-6 control-label">Account Type: </label>
                                <?php $user = Auth::user()?>
                                <label name="account_type" id="account_type" class="control-label">
                                    @if ($user->user_type == 1) Educator
                                    @elseif ($user->user_type == 2) Student
                                    @else General Public
                                    @endif
                                </label>                                
                            </div>
                            <!-- this displays the user's email address from the database -->
                            <div class="form-group">
                                <label for="email" class="col-md-6 control-label col-centered">E-mail: </label>
                                <label name="email" id="email" class="control-label"> {{ $user->email }}</label>
                            </div>
                            <!-- this displays the total simulations run by the user from the database -->
                            <div class="form-group">
                                <label for="total" class="col-md-6 control-label col-centered">Total Simulation Run: </label>
                                <label name="total" id="total" class="control-label">{{ $user->total_sims_run }}</label>
                            </div>
                            <!-- this displays when the account was created -->
                            <div class="form-group">
                                <label for="creation" class="col-md-6 control-label col-centered">User Since: </label>
                                <label name="creation" id="creation" class="control-label">{{ $user->created_at }}</label>
                            </div>
                            <!-- this is an anchor tab in the shape of a button to take the user to the edit account page -->
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3 col-centered">
                                    <a role="button" class="btn btn-info" id='edit-link' href="/edit_account">
                                        Edit Account
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
