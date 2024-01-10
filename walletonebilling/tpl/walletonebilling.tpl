<!-- BEGIN: MAIN -->

<div class="breadcrumb">{PHP.L.walletonebilling_title}</div>

<!-- BEGIN: WOFORM -->
	<div class="alert alert-info">{PHP.L.walletonebilling_formtext}</div>
	<script>
		$(document).ready(function(){
			setTimeout(function() {
				$("#woform").submit();
			}, 3000);
		});
	</script>
	{WALLETONE_FORM}
<!-- END: WOFORM -->

<!-- BEGIN: ERROR -->
	<h4>{WALLETONE_TITLE}</h4>
	{WALLETONE_ERROR}
	
	
	<!-- IF {WALLETONE_REDIRECT_URL} -->
	<br/>
	<p class="small">{WALLETONE_REDIRECT_TEXT}</p>
	<script>
		$(function(){
			setTimeout(function () {
				location.href='{WALLETONE_REDIRECT_URL}';
			}, 5000);
		});
	</script>
	<!-- ENDIF -->
<!-- END: ERROR -->


<!-- END: MAIN -->