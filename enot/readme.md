enot - Биллинг Enot 
Приём платежей и автоматические выплаты через https://enot.io/

проверенный рабочий плагин по индивидуальному запросу и только для покупателей сборки

##### .htaccess
##### enot
<pre>
RewriteRule ^enot/rest/?$ index.php?r=enot [QSA,NC,NE,L] 
RewriteRule ^enot/result/?$ index.php?e=enot&amp;m=result [QSA,NC,NE,L] 
RewriteRule ^enot/result/([0-9]+)/?$ index.php?e=enot&amp;m=result&amp;paymentId=$1 [QSA,NC,NE,L] 
RewriteRule ^enot/cancel/?$ index.php?e=enot&amp;m=cancel [QSA,NC,NE,L] 
RewriteRule ^enot/weebhook/?$ index.php?r=enot&amp;m=weebhook [QSA,NC,NE,L]
</pre>

or

<pre>
RewriteRule ^enot/rest/?$ index.php?r=enot [QSA,NC,NE,L] 
RewriteRule ^enot/result/?$ index.php?e=enot&m=result [QSA,NC,NE,L] 
RewriteRule ^enot/result/([0-9]+)/?$ index.php?e=enot&m=result&paymentId=$1 [QSA,NC,NE,L] 
RewriteRule ^enot/cancel/?$ index.php?e=enot&m=cancel [QSA,NC,NE,L] 
RewriteRule ^enot/weebhook/?$ index.php?r=enot&m=weebhook [QSA,NC,NE,L] 
</pre>
