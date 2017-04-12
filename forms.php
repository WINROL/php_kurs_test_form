<?php
//приходят данные из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (false === $email) {
        $errors['email'] = 'Введите корректный e-email адресс';
    }

    if (empty($errors)) {
        //если все хорошо, то делаем редирект
        header('Location: ' . $_SERVER['PHP_SELF']);
        die;
    }
}
?>

<form id="testForm" name="test" method="POST">
    <label>
        Имя:<br>
        <input type="text" value="" name="name" placeholder="Пример: Руслан" />
    </label>
    <label>
        <br>
        E-mail:<br>
        <input type="text" value="" name="email">
    </label>
    <label>
        <br>
        Ввведите текст:<br>
        <textarea rows="10" cols="40" name="text"></textarea>
    </label>
    <br/>
    <input type="submit" name="testName" value="Отправить">
</form>

<?php
//выводим все сообщения
?>
