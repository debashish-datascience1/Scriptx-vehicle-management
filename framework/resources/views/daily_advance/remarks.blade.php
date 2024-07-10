@foreach($users as $user)
    <div class="row">
        {{-- <div class="col-md-4"></div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="remarks" class="form-label required"><strong>Payment Method :</strong></label>
                {!!Form::select("method[{$user->id}]",$methods,null,['class'=>'form-control','placeholder'=>'Select Payment Methods','required'])!!}
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="remarks" class="form-label required"><strong>{{$user->name}}'s remarks :</strong></label>
                <input type="text" class="form-control" required name="remarks[{{$user->id}}]">
            </div>
        </div>
    </div>
@endforeach
    