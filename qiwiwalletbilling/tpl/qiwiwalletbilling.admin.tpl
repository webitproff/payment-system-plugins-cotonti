<!-- BEGIN: MAIN -->
<div class="container">
	<div class="row">
		<div class="span12">
      <h3>Подтверждение платежей Qiwi Wallet Billing</h3>

      {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
    </div>
	</div>

  <div class="block">
    <h4>Не подтвержденные платежи в системе</h4>
  	<table class="cells table table-bordered table-striped">
  	<thead>
  	<tr>
      <th class="coltop">{PHP.L.Date}</th>
  		<th class="coltop">{PHP.L.User}</th>
  		<th class="coltop">{PHP.L.payments_summ}</th>
  		<th class="coltop">{PHP.L.payments_desc}</th>
  		<th class="coltop">{PHP.L.payments_code}</th>
  		<th class="coltop">{PHP.L.Status}</th>
      <th class="coltop">{PHP.L.Action}</th>
  	</tr>
  	</thead>
  	<tbody>
  	<!-- BEGIN: PAYMENTS_ROW -->
  	<tr>
  		<td>{PAY_ROW_CDATE|date('d.m.Y H:i',$this)}</td>
  		<td><a href="{PAY_ROW_USER_ID|cot_url('admin', 'm=payments&id='$this)}">{PAY_ROW_USER_NICKNAME}</a></td>
  		<td style="text-align: right;">{PAY_ROW_SUMM|number_format($this, 2, '.', ' ')}</td>
  		<td>{PAY_ROW_DESC}</td>
  		<td>{PAY_ROW_QIWI_HASH}</td>
  		<td>{PAY_ROW_STATUS}</td>
      <td>
        <!-- IF {QIWIHIST_SELECT} -->
        <form action="{PHP|cot_url('admin', 'm=other&p=qiwiwalletbilling&a=paychecked')}" method="POST">
          <input type="hidden" name="rpayid" value="{PAY_ROW_ID}" />
          {QIWIHIST_SELECT}
          <button type="submit">Привязать</button>
        </form
        <!-- ENDIF -->
      </td>
  	</tr>
  	<!-- END: PAYMENTS_ROW -->
  	</tbody>
  	</table>

  	<div class="pagination"><ul>{PAGENAV_PREV}{PAGENAV_PAGES}{PAGENAV_NEXT}</ul></div>
  </div>

  <div class="block">
    <h4>История пополнений QIWI</h4>
    <!-- IF {QIWIHIST_ERROR} -->
      <div class="alert alert-danger">Произошла ошибка QIWI API: <b>{QIWIHIST_ERROR_TEXT}</b></div>
    <!-- ELSE -->
    	<table class="cells table table-bordered table-striped">
    	<thead>
    	<tr>
        <th class="coltop">#</th>
        <th class="coltop">{PHP.L.Date}</th>
    		<th class="coltop">Платеж</th>
    		<th class="coltop">Сумма</th>
    		<th class="coltop">Комментарий</th>
    		<th class="coltop">Статус в QIWI</th>
        <th class="coltop">Статус</th>
    	</tr>
    	</thead>
    	<tbody>
    	<!-- BEGIN: QIWIHIST_ROW -->
    	<tr>
        <td>{QH_ID}</td>
    		<td>{QH_DATE}</td>
    		<td>{QH_PAYER_TITLE}<!-- IF {QH_PAYER_ACCOUNT} --> от {QH_PAYER_ACCOUNT}<!-- ENDIF --></td>
    		<td style="white-space: nowrap;text-align: right;">{QH_SUMM} {QH_SUMM_VALUTA_CHAR}</td>
    		<td>{QH_COMMENT}</td>
    		<td>{QH_STATUS}<!-- IF {QH_ERROR_CODE} AND {QH_ERROR} -->: {QH_ERROR}<!-- ENDIF --></td>
        <td>
          <!-- IF {QH_HPAYID} -->
            <span class="label label-success">Привязан #{QH_HPAYID}</span>
          <!-- ELSE -->
            <!-- IF {QH_HPAYWAITID} -->
              <span class="label label-info">Возможно #{QH_HPAYWAITID}</span>
            <!-- ELSE -->
              <span class="label label-warning">Не привязан</span>
            <!-- ENDIF -->
          <!-- ENDIF -->
        </td>
    	</tr>
    	<!-- END: QIWIHIST_ROW -->
    	</tbody>
    	</table>
    <!-- ENDIF -->
  </div>

</div>
<!-- END: MAIN -->