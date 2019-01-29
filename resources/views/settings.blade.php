@extends('layouts.dashboard')
@section('page_heading','Settings')
@section('section')

<div class="col-sm-12">
    <div class="row">
        <div class="col-lg-6">




            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <h2>Change password</h2>
            <form method="POST" action="{{ route('changePassword') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                    <label for="new-password" class="control-label">Current Password</label>


                    <input id="current-password" type="password" class="form-control" name="current-password" required>

                    @if ($errors->has('current-password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('current-password') }}</strong>
                    </span>
                    @endif

                </div>

                <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                    <label for="new-password" class="control-label">New Password</label>


                    <input id="new-password" type="password" class="form-control" name="new-password" required>

                    @if ($errors->has('new-password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('new-password') }}</strong>
                    </span>
                    @endif

                </div>

                <div class="form-group">
                    <label for="new-password-confirm" class="control-label">Confirm New Password</label>
                    <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>

                </div>

                <button type="submit" class="btn btn-primary">
                    Change Password
                </button>
            </form>




            
            <h2>API Setteings</h2>
            <form role="form">
                <div class="form-group has-success">
                    <label class="control-label" for="inputSuccess">Input with success</label>
                    <input type="text" class="form-control" id="inputSuccess">
                </div>
                <div class="form-group has-warning">
                    <label class="control-label" for="inputWarning">Input with warning</label>
                    <input type="text" class="form-control" id="inputWarning">
                </div>
                <div class="form-group has-error">
                    <label class="control-label" for="inputError">Input with error</label>
                    <input type="text" class="form-control" id="inputError">
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <h2>Disabled Form States</h2>
            <form role="form">
                <fieldset disabled>
                    <div class="form-group">
                        <label for="disabledSelect">Disabled input</label>
                        <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect">Disabled select menu</label>
                        <select id="disabledSelect" class="form-control">
                            <option>Disabled select</option>
                        </select>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox">Disabled Checkbox
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Disabled Button</button>
                </fieldset>
            </form>
            <h2>Form Validation</h2>
            <form role="form">
                <div class="form-group has-success">
                    <label class="control-label" for="inputSuccess">Input with success</label>
                    <input type="text" class="form-control" id="inputSuccess">
                </div>
                <div class="form-group has-warning">
                    <label class="control-label" for="inputWarning">Input with warning</label>
                    <input type="text" class="form-control" id="inputWarning">
                </div>
                <div class="form-group has-error">
                    <label class="control-label" for="inputError">Input with error</label>
                    <input type="text" class="form-control" id="inputError">
                </div>
            </form>
            <h2>Input Groups</h2>
            <form role="form">
                <div class="form-group input-group">
                    <span class="input-group-addon">@</span>
                    <input type="text" class="form-control" placeholder="Username">
                </div>
                <div class="form-group input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-addon">.00</span>
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon"><i class="fa fa-eur"></i></span>
                    <input type="text" class="form-control" placeholder="Font Awesome Icon">
                </div>
                <div class="form-group input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control">
                    <span class="input-group-addon">.00</span>
                </div>
                <div class="form-group input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                </div>
            </form>
            <p>For complete documentation, please visit <a href="http://getbootstrap.com/css/#forms">Bootstrap's Form Documentation</a>.</p>
        </div>
    </div>
</div>



@stop
