@extends('backend.layouts.app')

@section('content')

    <div class="row">
    	<div class="col-lg-8 mx-auto">
    		<div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('General') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST">
    					@csrf
                    	<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Shipping Cost') }}</label>
                            <div class="col-md-8">
        					 <div class="col-md-8">
        						<input type="hidden" name="types[]" value="shipping_cost">
        						<input type="text" class="form-control" placeholder="{{ translate('Shipping Cost') }}" name="shipping_cost" value="{{ get_setting('shipping_cost') }}">
                            </div>
        				
                            </div>
    					</div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
                    </form>
    			</div>
    		</div>
    
    	</div>
    </div>

@endsection
