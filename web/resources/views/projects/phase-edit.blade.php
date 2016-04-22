 <div class="phase-edit">
        <div class="col-md-8">
            <div class="form-group">
                <label for="name">Fase titel</label>
                <input type="text" id="name" name="name" class="form-control input-lg">
            </div>
            <div class="form-group">
                <label for="description">Beschrijving</label>
                <textarea name="description" id="description" class="form-control" maxlength="600"></textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="upload">
                <label class="label-control" for="image">Upload foto</label>
                <div id="imagePlaceholder">
                    <img src="#" alt="Project afbeelding">
                    <label for="image">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </label>
                    <input type="file" name="image" id="image">
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="locatieplaceholder">
                <p>Locatieplaceholder</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="col-md-12">
                <label class="control-label" for="startDate">Start datum</label>
                <input type="date" class="form-control" id="startDate" name="startDate">
            </div>
            <div class="col-md-12">
                <label class="control-label" for="endDate">Eind datum</label>
                <input type="date" class="form-control" id="endDate" name="endDate">
            </div>
        </div>
    </div>
