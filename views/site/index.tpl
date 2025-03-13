{use class="Yii"}
{use class="yii\helpers\Html"}

<h1>Índice de sitio.</h1>

{if Yii::$app->user->isGuest}
hola invitado, {HTML::a('Login', ['site/login'])}
{else}
hola {Yii::$app->user->identity->username} 👋🏻
{/if}