@extends('layouts.template-dashboard')
@section('page_heading','Campaigns')
@section('section')

<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-12">
            @section ('cotable_panel_title')
            <button class="btn btn-success" data-toggle="modal" data-target="#modalCampaign">Create</button>
            @endsection
            @section ('cotable_panel_body')
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John</td>
                        <td>john@gmail.com</td>
                        <td>
                            <button class="btn btn-info" data-toggle="modal" data-target="#modalCampaign">Update</button>
                            <button class="btn btn-danger delete-campaign">Delete</button>
                        </td>
                    </tr>

                </tbody>
            </table>	
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalCampaign" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Campaign</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">Name</label>
                        <input type="text" class="form-control" id="name-cam">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="inputWarning">Url</label>
                        <input type="text" class="form-control" id="url-cam">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-campaign">Save changes</button>
            </div>
        </div>
    </div>
</div>



@stop
