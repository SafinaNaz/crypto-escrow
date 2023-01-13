@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">

	<div class="container">
		<div class="row justify-content-lg-center">
			<div class="col-lg-12">
				@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				<div class="get-started-form">
					<form class="profile-form" action="{{route('get-started')}}" method="POST" id="profile-form" class="form">
						@csrf
						<div class="heading-block d-flex justify-content-between">
							<h2>Start your Escrow</h2>


						</div>


						@if(!Auth::user())
						<div class="form-group">
							<label class="checkbox">I'm New User
								<input type="radio" class="user_type" name="user_type" value="new">
								<span class="checkmark"></span>
							</label>
							<label class="checkbox">I'm Returning User
								<input type="radio" class="user_type user_old" name="user_type" checked="checked" value="old">
								<span class="checkmark"></span>
							</label>
						</div>
						<div class="form-group">
							<label>Enter Your UserName</label>
							<input type="text" id="username" name="username" class="form-control" placeholder="UserName">
						</div>
						<div class="form-group">
							<label>Please Enter a New Password</label>
							<input type="password" id="password" name="password" class="form-control" placeholder="Password">
						</div>
						@endif

						<h3>Selling Information</h3>
						<input type="hidden" value="seller" class="buying_selling_option" name="buying_selling_option">


						<div class="form-group">
							<label>Buyer's UserName</label>
							<input type="text" class="form-control" id="buyer_username" name="buyer_username" placeholder="User Name" value="{{@$row['buyer_username']}}">
							<p><b>Note:</b> Enter here buyer's username in order for this escrow to show up in his account. (Make sure you enter  the exact username  as given by the buyer!)</p>
						</div>
						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<label>Price</label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<img loading="lazy" class="img-fluid currencyIcon" src="@if(@$row['currency_id']==1 ){{ _asset('frontend/assets/img/biticon.svg') }}@elseif(@$row['currency_id']==2 ){{ _asset('frontend/assets/img/monero.svg') }}@endif" />
											</span>
										</div>
										<input type="number" step="0.001" value="{{@$row['price']}}" class="form-control" id="price" name="price" required placeholder="Price">
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label>Currency</label>
									<select class="form-control" id="currency_id" name="currency_id" required>
										@foreach($currencies as $curr)
										<option value="{{$curr->id}}" @if(@$row['currency']==$curr->id ) selected @endif>{{$curr->currency}}</option>
										@endforeach
									</select>
								</div>

							</div>
						</div>
						<div class="form-group">
							<label>Description <small>UP TO 500 CHARACTERS</small> | <span class="text-danger" data-toggle="tooltip" data-placement="right" title="We are using an encryption package with private and public key with an encryption password. We are using  Spatie Crypto package for encrypt and decrypt data.">Encrypted area</span></label>
							<textarea class="form-control limited" maxlength="500" id="encrypted_text" name="encrypted_text">{{@$row['encrypted_text']}}</textarea>
							<p class="text-primary">Please write here sensitive information you would like to keep private between you and vendor. (home address etc).</p>
						</div>
						<div class="form-group">
							<label>Description <small>UP TO 500 CHARACTERS</small> | <span class="text-danger">Non Encrypted area</span></label>
							<textarea class="form-control limited" maxlength="500" id="non_encrypted_text" name="non_encrypted_text">{{@$row['non_encrypted_text']}}</textarea>
							<p class="text-primary">Please write here only essential/non sensitive information about this trade to aid the mediator in case of a dispute.</p>
						</div>

						<div class="form-group">
							<label>Term & Conditions</label>
							<textarea class="form-control" rows="7" id="term_conditions" name="term_conditions">{{@$row['term_conditions']}}</textarea>
						</div>


						<div class="row">
							<input type="hidden" name="escrow_fee_payer" value="1">

							<div class="col-lg-8">
								<label>Immediate Release</label>
								<div class="form-group">
									<label class="checkbox">Yes
										<input type="radio" class="immediate_release" name="immediate_release" value="1" >
										<span class="checkmark"></span>
									</label>
									<label class="checkbox">No
										<input type="radio" class="immediate_release" checked name="immediate_release" value="0" required>
										<span class="checkmark"></span>
									</label>

								</div>
								<div class="form-group">
									<label>Completion Time (Days)</label>
									<input type="number" step="1" min="0" class="form-control" id="completion_days" name="completion_days" placeholder="Completion Days" />

									<p>Within this specified period the escrow must be finalized by either the seller or the buyer if not the mediator will step in</p>
								</div>
							</div>
						</div>


						<h3 class="text-primary">General Escrow Instructions</h3>
						<p>With respect to escrow fee, Total fee will be settled.
							Scenarios may differ if buyer pays escrow fees or seller pays or both pays 50/50 Fee. </p>
						<p>Once the above form is completed. Escrow will be generated with a bitcoin or monero multi-sig wallet for the buyer/seller to send funds. Once the funds are transferred, Escrow is “activated” then and the time limit of the escrow begins. </p>

						<div class="text-right">
							<button type="submit" class="btn btn-primary">Start your Transaction</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


</div>

@endsection
