{use class="yii\widgets\ActiveForm" type="block"}
{use class="app\models\Author"}

{title}Crear Libro{/title}

<h1>{$this->title}</h1>

{ActiveForm assign="form" id="new-book"}
    {$form->field($book, 'title')->textInput(['maxlength' => true])}
    {$form->field($book, 'author_id')->dropDownList(Author::getAuthorList())}
    <input type="submit" value="Crear libro" class="btn btn-primary">
{/ActiveForm}