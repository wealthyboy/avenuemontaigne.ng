    <h4 class="info-text">Enter Apartment Details</h4>
    <div class="simple-product ">
        @include('admin.products.product_images') 
     </div>
               
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group label-floating is-empty">
                    <label class="control-label">Apartment Name</label>
                    <input  required="true" name="apartment_name" data-msg="" value="{{ old('apartment_name') }}" class="form-control" type="text">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="form-group label-floating is-empty">
                    <label class="control-label">Address</label>
                    <input  required="true" name="address" data-msg="" value="{{ old('address') }}" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group label-floating is-empty">
                           <label class="control-label"></label>
                        <select name="city_id" required="true" class="form-control">
                            <option  value="" selected="">--Choose City--</option>
                            @foreach($locations as $location)
                                <option class="" disabled value="{{ $location->id }}" >{{ $location->name }} </option>
                                @include('includes.children_options',['obj'=>$location,'space'=>'&nbsp;&nbsp;'])
                            @endforeach
                                
                        </select>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                    <label>Description</label>
                    <div class="form-group ">
                        <label class="control-label"> Enter description here</label>
                        <textarea name="description" 
                        id="description" class="form-control" rows="50">{{ old('description') }}</textarea>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <legend>  
                    Enable/Disable
                    </legend>
                    <div class="togglebutton">
                    <label>
                    <input name="allow"  value="1" type="checkbox" checked>
                    Enable/Disable
                    </label>
                    </div>
                </div>

                <div class="col-md-6">
                    <legend>  
                    Featured 
                    </legend>
                    <div class="togglebutton">
                    <label>
                        <input name="featured"  value="1" type="checkbox" >
                        Yes/No
                    </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-4">
            <label>Facilities</label>
            <div class="well well-sm" style="height: 250px; background-color: #fff; color: black; overflow: auto;">
                @foreach($facilities as $facility)
                    <div class="parent" value="{{ $facility->id }}">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="{{ $facility->id }}" name="facility_id[]" >
                                {{ $facility->name }}  
                                <a href="#">
                                <i class="fa fa-pencil"></i> Edit</a> 
                            </label>
                        </div>   
                    </div>
                @endforeach
            </div>

           
           
           
            
        </div>
    </div>