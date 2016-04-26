 <div class="phase-edit">
        <div class="col-md-8">
            <div class="form-group">
                <label for="name">Fase titel</label>
                <input type="text" id="name" name="phase_name{{ $phase->id }}" class="form-control input-lg" value="{{ $phase->name }}">
            </div>
            <div class="form-group">
                <label for="description">Beschrijving</label>
                <textarea name="phase_description{{ $phase->id }}" id="description" class="form-control" maxlength="600">{{ $phase->description }}</textarea>
            </div>
        </div>
     <div class="col-md-4">
         <div class="upload">
             <label class="label-control" for="image">Upload foto</label>
             <div id="imagePlaceholder">
                 <img src="{{ old("hashImage") != "" ? url('/images/tempProject', old("hashImage")) : "" }}"
                      alt="Project afbeelding">
                 <label for="image">
                     <i class="fa fa-plus" aria-hidden="true"></i>
                 </label>
                 <input type="file" name="image" id="image">
                 <input type="hidden" name="hashImage" id="hashImage" value="{{ old("hashImage") }}">
                 <input type="hidden" name="photoOffset" id="photoOffset" value="{{ old("photoOffset") }}">
             </div>
         </div>
     </div>
        <div class="col-md-4">
            <div class="col-md-12">
                <label class="control-label" for="startDate">Start datum</label>
                <input type="date" class="form-control" id="startDate" name="phaseStartDate{{ $phase->id }}" value="{{ $phase->start }}">
            </div>
            <div class="col-md-12">
                <label class="control-label" for="endDate">Eind datum</label>
                <input type="date" class="form-control" id="endDate" name="phaseEndDate{{ $phase->id }}" value="{{ $phase->end }}">
            </div>
        </div>
    </div>
