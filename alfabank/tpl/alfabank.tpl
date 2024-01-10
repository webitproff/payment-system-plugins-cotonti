<!-- BEGIN: MAIN -->

<div class="breadcrumb">{PHP.L.alfabank_title}</div>

<!-- BEGIN: ERROR -->
	<h4>{AB_TITLE}</h4>
	{AB_ERROR}
	
	
	<!-- IF {AB_REDIRECT_URL} -->
	<br/>
	<p class="small">{AB_REDIRECT_TEXT}</p>
	<script>
		$(function(){
			setTimeout(function () {
				location.href='{AB_REDIRECT_URL}';
			}, 5000);
		});
	</script>
	<!-- ENDIF -->
<!-- END: ERROR -->


<!-- END: MAIN -->