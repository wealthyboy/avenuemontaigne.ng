@extends('admin.layouts.app') @section('content')
<div class="row">
    <div class="col-md-12">
        <div class="text-right">
            <a href="{{ route('vouchers') }}" rel="tooltip" title="back" class="btn btn-primary btn-simple btn-xs">
                <i class="material-icons">reply</i>
            </a>

        </div>
    </div>
    <div class="col-md-12">

        <div class="card">
            @include('errors.errors')

            <div class="card-content">
                <h4 class="card-title">Discounts</h4>
                <div class="toolbar">
                    <!--        Here you can write extra buttons/actions for the toolbar              -->
                </div>
                <div class="material-datatables">
                    <form action="{{ route('discounts.update',['discount'=> $discount->id ]) }}" method="post">
                        {{ method_field('PATCH') }}
                        @csrf
                        <div class="col-lg-4 col-sm-4">
                           <div class="input-group">
                              <span class="input-group-addon">
                                 <i class="fa fa-dollar"></i>
                              </span>
                              <input name="percentage_discount" value="{{ $discount->percentage_off }}" required value="" placeholder="Discount in (%)" id="input-discount-name" class="form-control" type="number">
                           </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
                                <input class="form-control  datepicker pull-right" value="{{ $discount->created_at->format('d/m/y') }}" name="expires" id="datepicker" type="text">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <select name="category_id" required="true" class="form-control  pull-left" required>
                                <option class="" value="" >Select One</option>
                                @foreach($categories as $category)
                                   @if($discount->category_id ==  $category->id )
                                        <option class="" value="{{ $category->id }}" selected="selected">{{ $category->name }} </option>                                        
                                        @include('includes.children_options',['obj'=>$category,'space'=>'&nbsp;&nbsp;'])

                                    @else
                                        <option class="" value="{{ $category->id }}" >{{ $category->name }} </option>
                                        @include('includes.children_options',['model' => $discount,'obj'=>$category,'space'=>'&nbsp;&nbsp;'])
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="clearfix"></div>

                        <input value="search" name="search" type="hidden">
                        <div class="form-group text-right">
                            <button type="submit" id="button-filter" class="btn btn-primary btn-round"><i class=""></i> Submit</button>
                        </div>
                </div>
                </form>

            </div>
            <!-- end content-->
        </div>
        <!--  end card  -->
    </div>
    <!-- end col-md-12 -->
</div>
<!-- end row -->
@endsection @section('pagespecificscripts')
<script src="{{ asset('store/js/moment.min.js') }}"></script>
<script src="{{ asset('store/js/bootstrap-datetimepicker.js') }}"></script>
@stop @section('inline-scripts') $(document).ready(function() { s.initFormExtendedDatetimepickers(); }); @stop