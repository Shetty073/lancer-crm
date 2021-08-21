@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Name</label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Project name"
        value="@if(isset($project)){{ $project->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="details">Details</label>
        <textarea class="form-control" id="details" name="details" placeholder="Details about the project"
        required>@if(isset($project)){{ $project->details }}@else{{ old('details') }}@endif</textarea>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($project)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('projects.index') }}">Cancel</a>
</div>
