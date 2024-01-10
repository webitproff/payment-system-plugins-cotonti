<!-- BEGIN: MAIN -->
<style>
  form[name="pay"] button {
    outline: 0px;
    background: none;
    border: 1px solid #e1e1e1;
    border-radius: 5px;
    padding: 0px;
    height: 70px;
    width: 70px;
  }
  form[name="pay"] button img {
    max-height: 50px;
    max-width: 50px;
  }
</style>

<div class="centerwrap pt20 mb20">
  <div class="w700" style="margin: auto">
    <!-- BEGIN: PAY -->
    <h1 class="f32 sm-text-center">{PHP.L.unitpay_title}</h1>
    <div class="pt0 p15-20 white-bg-border w700">
      <!-- BEGIN: PINFO -->
      <div class="pt20">
        <h2>Информация о платеже</h2>
        <div>
          <strong>Номер:</strong><br>
          №{PNO}
          <br>
          <strong>Сумма:</strong><br>
          {PSUMM} руб.
          <br>
          <strong>Описание:</strong><br>
          {PDESC}
        </div>
      </div>
      <!-- END: PINFO -->
      <div class="pt10">
        <!-- IF {UPAY_SELECTED_TYPE_FOUND} -->
          <div class="alert alert-info mb0">
            Переадресация на платежную систему..
          </div>
        <!-- ELSE -->
          <!-- BEGIN: TYPES -->
          <br>
          <strong>{UNITPAY_TYPE}</strong><br>
          <br>
          <div>
            <!-- BEGIN: VARIANTS -->
              <div class="floatleft mr10">{UNITPAY_VARIANT}</div>
            <!-- END: VARIANTS -->
            <div class="clear"></div>
          </div>
          <!-- END: TYPES -->
        <!-- ENDIF -->
      </div>
    </div>
    <!-- END: PAY -->
    <!-- BEGIN: ERROR -->
    <h1 class="f32 sm-text-center">{UNITPAY_TITLE}</h1>
    <div class="pt15 p15-20 white-bg-border w700">
      {UNITPAY_ERROR}

      <!-- IF {UNITPAY_REDIRECT} -->
      <br/>
      <p class="small">{UNITPAY_REDIRECT_TEXT}</p>
      <script>
      		$(function(){
      			setTimeout(function () {
      				location.href='{UNITPAY_REDIRECT}';
      			}, 5000);
      		});
      	</script>
      <!-- ENDIF -->
    </div>
    <!-- END: ERROR -->
  </div>
</div>
<!-- END: MAIN -->