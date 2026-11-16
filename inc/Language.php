<?php
if (isset($_GET['lang']) && in_array($_GET['lang'], array('ru', 'en'), true)) {
    $_SESSION['language'] = $_GET['lang'];
}

function current_language() {
    return isset($_SESSION['language']) && in_array($_SESSION['language'], array('ru', 'en'), true)
        ? $_SESSION['language']
        : 'ru';
}

function language_text($text) {
    static $translations = array(
        'Ilusalong — Beauty Salon' => 'Ilusalong — салон красоты',
        'Beauty services and care at Ilusalong' => 'Услуги и уход в салоне красоты Ilusalong',
        'Main navigation' => 'Главная навигация',
        'Services' => 'Услуги',
        'Register' => 'Регистрация',
        'Unable to load content' => 'Не удалось загрузить содержимое',
        '© Ilusalong — Beauty begins with self-care' => '© Ilusalong — красота начинается с заботы о себе',
        'All services' => 'Все услуги',
        'Category services' => 'Услуги категории',
        'Page not found' => 'Страница не найдена',
        'ILUSALONG · BEAUTY &amp; CARE' => 'ILUSALONG · КРАСОТА И ЗАБОТА',
        'Make time for yourself' => 'Время для себя',
        'Professional care, a welcoming atmosphere, and attention to every detail.' => 'Профессиональный уход, уютная атмосфера и внимание к каждой детали.',
        'Explore services' => 'Выбрать услугу',
        'Popular services' => 'Популярные услуги',
        'View details' => 'Подробнее',
        'Your review' => 'Ваш отзыв',
        'Submit review' => 'Отправить отзыв',
        'Reviews' => 'Отзывы',
        'No reviews yet. Be the first!' => 'Пока нет отзывов. Будьте первым!',
        'Create an account' => 'Создать аккаунт',
        'Name' => 'Имя',
        'Email address' => 'Электронная почта',
        'Password' => 'Пароль',
        'Confirm password' => 'Повторите пароль',
        'Account created.' => 'Аккаунт создан.',
        'Home' => 'На главную',
        'Registration failed.' => 'Ошибка регистрации.',
        'Back to registration' => 'Вернуться к регистрации',
        'Invalid email' => 'Некорректный адрес электронной почты',
        'Password must be more than 6 characters' => 'Пароль должен содержать не менее 6 символов',
        'Passwords do not match' => 'Пароли не совпадают',
        'error' => 'Ошибка',
        'Admin sign in' => 'Вход для администратора',
        'Sign in to manage salon services.' => 'Войдите, чтобы управлять услугами салона.',
        'Email' => 'Электронная почта',
        'Sign in' => 'Войти',
        'Invalid email, password, or administrator access.' => 'Неверная почта или пароль либо отсутствуют права администратора.',
        'Please sign in with an administrator account.' => 'Войдите с учётной записью администратора.',
        'Back to website' => 'Вернуться на сайт',
        'Language' => 'Язык',
        'Admin navigation' => 'Навигация администратора',
        'Dashboard' => 'Панель управления',
        'Categories' => 'Категории',
        'Log out' => 'Выйти',
        'Ilusalong — Admin dashboard' => 'Ilusalong — панель администратора',
        '© Ilusalong · Admin dashboard' => '© Ilusalong · Панель администратора',
        'Salon admin dashboard' => 'Панель управления салоном',
        'Manage services and salon categories.' => 'Управление услугами и категориями салона.',
        'Manage services' => 'Управление услугами',
        'View categories' => 'Просмотр категорий',
        'Service categories' => 'Категории услуг',
        'No service categories have been added yet.' => 'Категории услуг пока не добавлены.',
        'Category ID' => 'ID категории',
        'Add service' => 'Добавить услугу',
        'Service' => 'Услуга',
        'ID' => 'ID',
        'Name:' => 'Название:',
        'Category:' => 'Категория:',
        'Price:' => 'Цена:',
        'Edit' => 'Изменить',
        'Delete' => 'Удалить',
        'Service added.' => 'Услуга добавлена.',
        'Back to services' => 'К списку услуг',
        'Unable to add service.' => 'Не удалось добавить услугу.',
        'Service name' => 'Название услуги',
        'Description' => 'Описание',
        'Category' => 'Категория',
        'Price (€)' => 'Цена (€)',
        'Image' => 'Изображение',
        'Optional. JPEG, PNG, GIF, or WebP. Images are optimized for upload (maximum 512 KB after optimization).' => 'Необязательно. JPEG, PNG, GIF или WebP. Изображение будет оптимизировано (до 512 КБ).',
        'Save' => 'Сохранить',
        'Back to list' => 'Назад к списку',
        'Edit service' => 'Изменить услугу',
        'Service updated.' => 'Услуга обновлена.',
        'Unable to update service.' => 'Не удалось обновить услугу.',
        'Current image' => 'Текущее изображение',
        'Save changes' => 'Сохранить изменения',
        'Delete service' => 'Удалить услугу',
        'Service deleted.' => 'Услуга удалена.',
        'Unable to delete service.' => 'Не удалось удалить услугу.',
        'Salon services' => 'Услуги салона',
        'The image upload failed. Please try again with a smaller image.' => 'Не удалось загрузить изображение. Попробуйте выбрать файл меньшего размера.',
        'The optimized image must be 512 KB or smaller. Select another image or reduce its size.' => 'Размер оптимизированного изображения должен быть не более 512 КБ. Выберите другое изображение или уменьшите его размер.',
        'Choose a valid JPEG, PNG, GIF, or WebP image.' => 'Выберите изображение в формате JPEG, PNG, GIF или WebP.',
        'The uploaded image could not be read.' => 'Не удалось прочитать загруженное изображение.',
        'This image is too detailed to compress below 512 KB.' => 'Изображение слишком детализировано, чтобы сжать его до 512 КБ.',
        'Your browser could not prepare this image.' => 'Не удалось подготовить изображение в этом браузере.',
        'Your browser could not compress this image.' => 'Не удалось сжать изображение в этом браузере.',
        'Choose an image file.' => 'Выберите файл изображения.',
        'This image could not be opened.' => 'Не удалось открыть это изображение.',
        'Error 404' => 'Ошибка 404',
        '404 - Page Not Found' => '404 — страница не найдена',
        'The page you are looking for is not found.' => 'Запрашиваемая страница не найдена.'
    );

    return current_language() === 'ru' && isset($translations[$text])
        ? $translations[$text]
        : $text;
}

function language_switcher() {
    $path = isset($_SERVER['REQUEST_URI'])
        ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        : '';
    $params = $_GET;
    unset($params['lang']);
    $html = '<div class="language-switch" aria-label="' .
        htmlspecialchars(language_text('Language'), ENT_QUOTES, 'UTF-8') . '">';
    foreach (array('ru' => 'RU', 'en' => 'EN') as $language => $label) {
        $linkParams = $params;
        $linkParams['lang'] = $language;
        $url = $path . '?' . http_build_query($linkParams);
        $active = current_language() === $language ? ' aria-current="page"' : '';
        $html .= '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"' . $active . '>' . $label . '</a>';
    }
    return $html . '</div>';
}
?>
