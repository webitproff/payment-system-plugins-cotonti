<!-- BEGIN: MAIN -->

<div class="breadcrumb">{PHP.L.millikartbilling_title}</div>

<!-- BEGIN: ERROR -->
	<h4>{MILLI_TITLE}</h4>
	{MILLI_ERROR}
	
	
	<!-- IF {MILLI_REDIRECT_URL} -->
	<br/>
	<p class="small">{MILLI_REDIRECT_TEXT}</p>
	<script>
		$(function(){
			setTimeout(function () {
				location.href='{MILLI_REDIRECT_URL}';
			}, 5000);
		});
	</script>
	<!-- ENDIF -->
<!-- END: ERROR -->


<!-- END: MAIN -->