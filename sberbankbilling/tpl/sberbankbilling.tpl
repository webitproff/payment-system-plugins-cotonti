<!-- BEGIN: MAIN -->

<div class="breadcrumb">{PHP.L.sberbankbilling_title}</div>

<!-- BEGIN: ERROR -->
	<h4>{BILLING_TITLE}</h4>
	{BILLING_ERROR}
	
	<!-- IF {BILLING_REDIRECT_URL} -->
	<br/>
	<p class="small">{BILLING_REDIRECT_TEXT}</p>
	<script>
		$(function(){
			setTimeout(function () {
				location.href='{BILLING_REDIRECT_URL}';
			}, 5000);
		});
	</script>
	<!-- ENDIF -->
<!-- END: ERROR -->

<!-- BEGIN: FORM -->
	<h4>{BILLING_TITLE}</h4>

  <form action="{BILLING_FORM_URL}" id="formBinding" method="POST">
    <table cellpadding="10">
      <tbody>
        <tr valign="TOP">
          <td valign="top" width="50%" align="right">
            <span>Выберите карту:</span>
          </td>
          <td valign="top">
            <select name="bindingId">
              <!-- FOR {KEY}, {VAL} IN {BILLING_BINDINGS} -->
                <option value="{KEY}">{VAL}</option>
              <!-- ENDFOR -->
              <option value="" selected="selected">другая</option>
            </select>
          </td>
        </tr>
        <tr class="rbs_hidden">
          <td align="right">
            <span>Введите CVC2/CVV2/CID код :</span><br>(находится на обратной стороне карты)
          </td>
          <td>
            <input name="cvc" maxlength="4" type="password" autocomplete="off" />
          </td>
        </tr>
        <tr class="rbs_hidden_fix">
          <td> </td>
          <td valign="top" >
            <input value="Оплатить" type="submit" id="buttonBindingPayment">
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  <script>
    $('.rbs_hidden').hide();
    $('[name="bindingId"]').change(function() {
      if($(this).val() == '') {
        $('.rbs_hidden').hide();
      } else {
        $('.rbs_hidden').show();
      }
    });
  </script>
<!-- END: FORM -->

<!-- END: MAIN -->