@extends('layouts.master-layout')
@section('title')
<title>Edit Account</title>
@endsection
@section('content')
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Account</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ secure_url('edit_account') }}">
                        {{ csrf_field() }}
                        <!-- email -->
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
                        <!-- new password -->
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">New Password</label>
                            <div class="col-md-6">
                                <input id="new-password" type="password" class="form-control" name="new-password">
                                @if ($errors->has('new-password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('new-password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- new user type -->
                        <div class="form-group">
                            <label for="user_type" class="col-md-4 control-label">What Type of User Are You?</label>
                            <div class="col-md-6">
                                <select class="form-control" id="user_type" name="user_type">
                                    <option value="" disabled="disabled" selected="selected">
                                        Select a Category
                                    </option>
                                    <option value="1">Educator</option>
                                    <option value="2">Student</option>
                                    <option value="3">General Public</option>
                                </select>
                                @if ($errors->has('user_type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- current password to be able to submit the form -->
                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Current Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password-confirm" required>
                                @if($errors->any())
                                <span class="help-block">
                                    <strong>{{$errors->first()}}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- submit button -->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button id="update-information" type="submit" class="btn btn-primary">
                                    Update Information
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
