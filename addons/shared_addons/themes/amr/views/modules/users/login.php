<script>
	function checkCookie(){
	    var cookieEnabled=(navigator.cookieEnabled)? true : false   
	    if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){ 
	        document.cookie="testcookie";
	        cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
	    }
	    return (cookieEnabled) ? true : showCookieFail();
	}
	function showCookieFail(){
	  alert('Cookies deshabilitadas!. Debe habilitar las cookies para poder loguearse');
	}
	checkCookie();
</script>
<div class="inner-page sign">
	<div class="container">
		<h2><i class="fa fa-sign-in blue"></i><strong>Login</strong> Ingresar a America Meeting Rooms</h2>
		<div class="sign-content">
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<noscript>
						<div class="alert alert-danger">Javascript esta deshabilitado, debe habilitarlo para una mejor experiencia del usuario.</div>
					</noscript>	

					<!-- Sign In Area -->
					<div class="sign-in">
						<h3><i class="fa fa-user blue"></i> &nbsp;Login</h3>
						<?php if (validation_errors()): ?>
						<div class="alert alert-danger">
						<?php echo validation_errors();?>
						</div>
						<?php endif ?>
						<!-- Sign in Form Start -->
						<?php echo form_open('users/login', array('id'=>'login', 'class'=>'form-horizontal', 'role'=>'form'), array('redirect_to' => $redirect_to)) ?>							
							<div class="form-group">
								<label for="email" class="col-lg-3 control-label"><?php echo lang('global:email') ?></label>
								<div class="col-lg-9">
									<?php echo form_input( array('name'=>'email', 'id'=>'username', 'class'=>'form-control', 'value'=>$this->input->post('email') ? $this->input->post('email') : ''))?>
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-lg-3 control-label"><?php echo lang('global:password') ?></label>
								<div class="col-lg-9">
									<input type="password" class="form-control" id="password" name="password" maxlength="20" />
								</div>
							</div>							
							<div class="form-group">
								<div class="col-lg-offset-3 col-lg-9">
									<button type="submit" class="btn btn-info">Login</button>
								</div>
							</div>
						</form>
						<!-- Sign in form End -->
						<h4><span>OR</span></h4>
						<!-- Sign In with Social media -->
						<div class="social-sign">
							<a class="btn btn-primary"><i class="fa fa-facebook"></i>&nbsp; Sign in With Facebook</a>&nbsp;
							<a class="btn btn-danger"><i class="fa fa-google-plus"></i>&nbsp; Sign in With Google</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>			