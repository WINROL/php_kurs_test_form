<?php
define('FILE_NAME', 'form.txt');

//приходят данные из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arr = [];

    function validateStr($str, $min = 3)
    {
        $error = false;
        $str = htmlspecialchars(trim($str), ENT_QUOTES, 'utf-8');
        if (mb_strlen($str, 'utf-8') < $min) {
            $error = 'Поле должно быть длиннее ' . $min . '-х символов';
        }

        return [
            'str' => $str,
            'error' => $error
        ];
    }


    $errors = [];

    $nameArr = validateStr($_POST['name']);
    if (false === $nameArr['error']) {
        $arr['name'] = $nameArr['str'];
    } else {
        $errors['name'] = $nameArr['error'];
    }

    $arr['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (false === $arr['email']) {
        unset($arr['email']);
        $errors['email'] = 'Введите корректный e-email адресс';
    }


    $textArr = validateStr($_POST['text']);
    if (false === $textArr['error']) {
        $arr['text'] = nl2br($textArr['str']);
    } else {
        $errors['text'] = $textArr['error'];
    }

    if (empty($errors) && !empty($arr)) {
        //если все хорошо
        file_put_contents(FILE_NAME, serialize($arr) . "END$$$\r\n", FILE_APPEND);

        //то делаем редирект
        header('Location: ' . $_SERVER['PHP_SELF']);
        die;
    }
}
?>

<form id="testForm" name="test" method="POST">

    <label>
        Имя:<br>
        <input type="text"
               value="<?php echo isset($name) ? $name : ''; ?>"
               name="name" placeholder="Пример: Руслан" />
        <?php
        if (isset($errors['name'])) {
            echo '<span style="color: red">'.$errors['name'].'</span>';
        }
        ?>
    </label>
    <label>
        <br><br>
        E-mail:

        <br>
        <input type="text"
               value="<?php echo isset($email) ? $email : ''; ?>"
               name="email">
        <?php
        if (isset($errors['email'])) {
            echo '<span style="color: red">'.$errors['email'].'</span>';
        }
        ?>

    </label>
    <label>
        <br><br>
        Ввведите текст:<br>
        <textarea rows="10" cols="40" name="text"><?php echo isset($text) ? $text : ''; ?></textarea>
        <?php
        if (isset($errors['text'])) {
            echo '<span style="color: red">'.$errors['text'].'</span>';
        }
        ?>
    </label>
    <br/>
    <input type="submit" name="testName" value="Отправить">
</form>

<?php
//выводим все сообщения
if (file_exists(FILE_NAME)) {
    $arr = explode("END$$$\r\n", trim(file_get_contents(FILE_NAME)));

    if (!empty($arr)) {
        foreach ($arr as $k => $v) {
            $v = unserialize($v);

            echo $v['name'] . '<br/>';
            echo $v['email'] . '<br/>';
            echo $v['text'] . '<br/><hr/>';
        }
    }
} else {
    echo 'Сообщение пока нету! Будьте первыми!';
}

?>
