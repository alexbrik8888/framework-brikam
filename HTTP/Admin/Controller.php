<?php

namespace HTTP\Admin;

use App\Engine\Auth;
use App\Engine\BaseController;
use App\Engine\Request;
use App\Engine\User\UserAmin;
use App\Engine\View;
use App\Model\Article;
use App\Model\File;
use App\Model\Filter\Category;
use App\Model\Filter\CategoryArticle;

/**
 * Базовый контроллер административной панели.
 * Отвечает за авторизацию, управление категориями и статьями.
 */
class Controller extends BaseController
{
    /**
     * Перехват и проверка прав доступа перед вызовом действия.
     *
     * @param string $action Имя метода/действия.
     * @param array $params Параметры маршрута.
     * @return mixed
     */
    public function callAction($action, $params)
    {
        // Разрешаем доступ к логину без авторизации
        if ($action === 'loginAction') {
            return parent::callAction($action, $params);
        }

        // Проверяем авторизацию пользователя с ролью Admin
        $isAuthorized = Auth::getInstance()
            ->setModel(UserAmin::class)
            ->check();

        if ($isAuthorized) {
            return parent::callAction($action, $params);
        }

        // Если не авторизован — перенаправляем на логин
        return parent::callAction('loginAction', $params);
    }

    /**
     * Главная страница административной панели.
     *
     * @return string Скомпилированный шаблон главной страницы.
     */
    public function indexAction(): string
    {
        return (new View())->render('/Admin/main.tpl');
    }

    /**
     * Управление категориями (Просмотр, Фильтрация, Создание/Редактирование, Удаление).
     *
     * @return string Скомпилированный шаблон управления категориями.
     */
    public function categoryAction(): string
    {
        $category = new Category();
        $category->setLimit(10);

        $request = Request::getInstance();
        $params = $request->getAllParams();
        $method = $request->getMethod();

        // 1. Создание или обновление категории (POST)
        if ($method === 'POST') {
            $category->save($params);
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }

        // 2. Фильтрация и пагинация (GET)
        if ($method === 'GET') {
            if (!empty($params['name'])) {
                $category->where('name', '%' . $params['name'] . '%', 'LIKE');
            }
            if (!empty($params['description'])) {
                $category->where('description', '%' . $params['description'] . '%', 'LIKE');
            }
            if (!empty($params['parent_id'])) {
                $category->where('parent_id', $params['parent_id'], '=');
            }
            if (!empty($params['page'])) {
                $category->setPage($params['page']);
            }
        }

        // 3. Удаление категории (DELETE)
        if ($method === 'DELETE') {
            $categoryId = $request->getParam('id');
            if ($categoryId) {
                $category->where('id', $categoryId)->delete();
            }
        }

        return (new View())->render('/Admin/category.tpl', [
            'category_list' => $category->getList(),
            'pagination'    => $category->getPagination(),
            'parent'        => $category->newQuery()->getList()
        ]);
    }

    /**
     * Список статей (Просмотр, Фильтрация, Удаление).
     *
     * @return string Скомпилированный шаблон списка статей.
     */
    public function articleListAction(): string
    {
        $article = new Article();
        $article->setLimit(10);
        $article->Query()->select('*');

        $request = Request::getInstance();
        $params = $request->getAllParams();
        $method = $request->getMethod();

        // 1. Фильтрация по совпадениям и категориям (GET)
        if ($method === 'GET') {
            if (!empty($params['name'])) {
                $article->where('name', '%' . $params['name'] . '%', 'LIKE', 'OR');
            }
            if (!empty($params['description'])) {
                $article->where('description', '%' . $params['description'] . '%', 'LIKE', 'OR');
            }
            if (!empty($params['category_id'])) {
                $article->Query()->whereIN('category_id', $params['category_id'], 'OR');
            }
            if (!empty($params['page'])) {
                $article->setPage($params['page']);
            }
        }

        // 2. Удаление статьи (DELETE)
        if ($method === 'DELETE') {
            $articleId = $request->getParam('id');
            if ($articleId) {
                $article->where('id', $articleId)->delete();
            }
        }

        $articleData = $article->connectCategory()->getList();

        return (new View())->render('/Admin/list_article.tpl', [
            'article_list' => $articleData,
            'pagination'   => $article->getPagination(),
            'category'     => (new Category())->setLimit('')->getList(),
        ]);
    }

    /**
     * Редактирование и создание статьи с привязкой категорий и загрузкой файлов.
     *
     * @return string Скомпилированный шаблон формы статьи.
     */
    public function articleAction(): string
    {
        $article = new Article('*');
        $error = '';

        $request = Request::getInstance();
        $params = $request->getAllParams();
        $method = $request->getMethod();

        // 1. Сохранение статьи, ее категорий и прикрепленных файлов (POST)
        if ($method === 'POST') {
            try {
                $dataArticle = $article->save($params);

                if ($dataArticle) {
                    $abstractId = $dataArticle['id'];
                    $dataArticle['abstract_id'] = $abstractId;
                    $dataArticle['type_id'] = 1;
                    unset($dataArticle['id']);

                    // Формируем связи категории с текущей статьёй
                    $categoriesToInsert = [];
                    if (!empty($params['category_id']) && is_array($params['category_id'])) {
                        foreach ($params['category_id'] as $cId) {
                            $categoriesToInsert[] = [
                                'category_id' => $cId,
                                'article_id'  => $abstractId
                            ];
                        }
                    }

                    // Перезаписываем связи категорий
                    $categoryModel = new CategoryArticle();
                    $categoryModel->find('article_id', $abstractId)->delete();

                    if (!empty($categoriesToInsert)) {
                        $isCategorySaved = $categoryModel->insertMultiple($categoriesToInsert);
                        if (!$isCategorySaved) {
                            $error = 'Ошибка при сохранении категорий';
                        }
                    }

                    // Обновляем прикрепленные файлы
                    $fileModel = new File();
                    $fileModel->where('abstract_id', $abstractId)->where('type_id', 1)->delete();

                    // Безопасная извлечение данных из массива $_FILES
                    $firstFileKey = array_key_first($_FILES);
                    $fileData = $firstFileKey ? $_FILES[$firstFileKey] : [];

                    $isFileSaved = $fileModel->save(array_merge($dataArticle, $fileData));

                    if ($isFileSaved) {
                        $redirectUrl = isset($params['id'])
                            ? '/admin/article?id=' . $abstractId
                            : '/admin/list/article';

                        header('Location: ' . $redirectUrl);
                        exit;
                    }

                    $error = $error ?: 'Ошибка при сохранении файла';
                } else {
                    $error = 'Ошибка при сохранении данных';
                }
            } catch (\Throwable $e) {
                // В продакшене рекомендуется логировать $e->getMessage() вместо дампа
                d($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
            }
        }

        // 2. Получение данных для редактирования (GET)
        if ($method === 'GET' && $request->getParam('id')) {
            $article->where('id', $request->getParam('id'));
        }

        $articleData = $article->connectImage()->connectCategory()->getFirst();

        // Оптимизированный выбор категорий с помощью array_column
        if (!empty($articleData['category']) && is_array($articleData['category'])) {
            $articleData['category'] = array_column($articleData['category'], 'category_id');
        } else {
            $articleData['category'] = [];
        }

        $article->Query()->select('*');

        return (new View())->render('/Admin/article.tpl', [
            'article'  => $articleData,
            'category' => (new Category())->setLimit('')->getList(),
            'error'    => $error
        ]);
    }

    /**
     * Авторизация администратора.
     *
     * @return string Скомпилированный шаблон входа.
     */
    public function loginAction(): string
    {
        $error = '';
        $request = Request::getInstance();

        if ($request->getMethod() === 'POST') {
            $params = $request->getAllParams();

            if (!empty($params['email']) && !empty($params['password'])) {
                // ВАЖНО: Рекомендуется передавать чистый пароль в метод checkCreadantion
                // и сверять внутри с помощью password_verify(), а не передавать md5()
                $isAuth = Auth::getInstance()
                    ->setModel(UserAmin::class)
                    ->checkCreadantion([
                        'email'    => $params['email'],
                        'password' => md5($params['password']) // Замените на password_hash / password_verify
                    ]);

                if ($isAuth) {
                    header('Location: /admin');
                    exit;
                }

                $error = 'Неверный логин или пароль';
            } else {
                $error = 'Не все данные заполнены';
            }
        }

        return (new View())->render('/Admin/login.tpl', ['error_message' => $error]);
    }

    /**
     * Выход из системы и уничтожение сессии.
     *
     * @return void
     */
    public function logoutAction(): void
    {
        unset($_SESSION['id'], $_SESSION['user_info']);
        header('Location: /admin');
        exit;
    }
}