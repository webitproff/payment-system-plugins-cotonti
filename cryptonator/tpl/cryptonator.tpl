<!-- BEGIN: MAIN -->


<header id="header" class="second uk-background-fixed uk-position-relative">
    <div class="overlay dotted uk-position-absolute">
        <div class="uk-height-1-1 uk-flex uk-flex-center uk-flex-middle">
            <div class="uk-padding">
                <h1 class="uk-h1 text-white uk-text-center">Пополнение счета</h1>
            </div>
        </div>
    </div>
</header>
<div class="uk-card uk-card-default uk-background-full-width">
    <div class="uk-card-body uk-padding-small uk-grid uk-flex uk-flex-middle">
        <div class="uk-width-expand">
            <ul class="uk-breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i></a></li>
                <li><a href="{PHP|cot_url("dashboard")}">Личный кабинет</a></li>
                <li><a href="{PHP|cot_url("payments", "m=balance")}">Мой счет</a></li>
                <li><a href="{PID|cot_url("payments", "m=billing&pid=$this")}">Способы оплаты</a></li>
                <li class="uk-active"><span class="uk-text-capitalize">{CHECKOUT_CURRENCY}</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- IF {ERROR} -->
<div class="uk-alert uk-alert-danger">
    {ERROR}
</div>
<!-- ENDIF -->
<div class="uk-margin-medium-top uk-margin-medium-bottom  uk-flex uk-flex-center">
    <div class="uk-card uk-card-default uk-width-2-3">
        <div class="uk-card-body uk-flex-middle" uk-grid>
            <div class="uk-width-1-3 uk-margin-bottom">
                <img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl={CHECKOUT_CURRENCY}:{CHECKOUT_ADDRESS}?amount={CHECKOUT_AMOUNT}" alt="">
            </div>
            <div class="uk-width-expand uk-margin-bottom">
                <p>Для оплаты отсканируйте этот QR код через свой мобильный кошелек.</p>
                <p>Или отправьте <b>{CHECKOUT_AMOUNT}</b> {CHECKOUT_SHORT_CURRENCY} на этот адрес <br><b>{CHECKOUT_ADDRESS}</b></p>
                <p>
                    У вас есть 
                    <span uk-countdown="date: {CHECKOUT_DATE}">
                        <span class="uk-countdown-minutes uk-text-bold">00</span>
                        <span>:</span>
                        <span class="uk-countdown-seconds uk-text-bold">00</span>
                    </span>
                    минут для оплаты.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- END: MAIN -->