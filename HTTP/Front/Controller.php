<?php

namespace HTTP\Front;

use App\Engine\BaseController;
use App\Engine\Request;
use App\Engine\View;
use App\Model\Article;
use App\Model\ArticleView;
use App\Model\File;
use App\Model\Filter\Category;
use App\Model\Filter\CategoryArticle;

/**
 * Публичный контроллер клиентской части сайта (Front-end).
 * Обрабатывает вывод главной страницы, каталога, карточки статьи и выдачу файлов.
 */
class Controller extends BaseController
{
    /**
     * @var array Список главных родительских категорий (parent_id = 0).
     */
    private array $mainCategory;

    /**
     * Конструктор контроллера.
     * Загружает список основных категорий для построения навигации/меню.
     */
    public function __construct()
    {
        $category = new Category();
        $category->Query()->where('parent_id', 0);
        $this->mainCategory = $category->getList();
    }

    /**
     * Главная страница сайта.
     * Выводит основные категории и последние статьи по категориям.
     *
     * @return string Скомпилированный HTML-шаблон.
     */
    public function indexAction(): string
    {
        $article = new Article();

        return (new View())->render('/Front/main.tpl', [
            'main_category' => $this->mainCategory,
            'list_category' => $article->getLastThreeArticleCategories(),
        ]);
    }

    /**
     * Страница каталога статей по конкретной категории с сортировкой и пагинацией.
     *
     * @return string Скомпилированный HTML-шаблон.
     */
    public function catalogAction(): string
    {
        $request = Request::getInstance();
        $categoryId = $request->getParam('id');

        // Если ID категории не передан — перенаправляем на главную
        if (!$categoryId) {
            header('Location: /');
            exit;
        }

        $params = $request->getAllParams();
        $categoryArticle = new CategoryArticle();

        // Подготавливаем выборку статей для текущей категории
        $categoryArticle->setLimit(5)
            ->connectArticle()
            ->connectArticleView()
            ->connectImageArticle()
            ->where($categoryArticle->getTable() . '.category_id', $categoryId);

        // Применяем сортировку
        $order = $params['order'] ?? 'date';
        switch ($order) {
            case 'view':
                $categoryArticle->Query()->order('article_views.view', 'DESC');
                break;
            case 'date':
            default:
                $categoryArticle->Query()->order('articles.created_at', 'DESC');
                break;
        }

        // Устанавливаем текущую страницу для пагинации
        if (!empty($params['page'])) {
            $categoryArticle->setPage($params['page']);
        }

        return (new View())->render('/Front/catalog.tpl', [
            'main_category' => $this->mainCategory,
            'category'      => (new Category())->find('id', $categoryId)->getFirst(),
            'list_articl'   => $categoryArticle->getList(),
            'pagination'    => $categoryArticle->getPagination(),
            'query_param'   => $params,
        ]);
    }

    /**
     * Детальная страница статьи с рекомендациями и подсчетом просмотров.
     *
     * @return string Скомпилированный HTML-шаблон.
     */
    public function detailsAction(): string
    {
        $request = Request::getInstance();
        $articleId = $request->getParam('id');

        $articleModel = new Article('*');
        $articleModel->connectImageSimple()
            ->connectCategory()
            ->connectArticleView()
            ->where($articleModel->getTable() . '.id', $articleId);

        $article = $articleModel->getFirst();

        // Если статья не найдена — перенаправляем на главную
        if (!$article) {
            header('Location: /');
            exit;
        }

        // Извлекаем ID категорий статьи для поиска похожих/рекомендуемых статей
        $categoryIds = array_column($article['category'] ?? [], 'id');

        $recommendationModel = new CategoryArticle();
        $recommendationModel->Query()
            ->whereIN($recommendationModel->getTable() . '.category_id', $categoryIds)
            ->order($articleModel->getTable() . '.created_at', 'DESC');

        $recommendation = $recommendationModel->setLimit(3)
            ->connectImageArticle()
            ->connectArticle()
            ->getList();

        // Увеличиваем счетчик просмотров статьи
        $currentViews = (int)($article['view'] ?? 0);
        (new ArticleView())->save([
            'article_id' => $article['id'],
            'view'       => $currentViews + 1,
        ]);

        return (new View())->render('/Front/details.tpl', [
            'main_category'  => $this->mainCategory,
            'query_param'    => $request->getAllParams(),
            'articl'         => $article,
            'recommendation' => $recommendation,
        ]);
    }

    /**
     * Стриминг файла/изображения из файловой системы пользователю.
     *
     * @return void
     */
    public function getImageAction(): void
    {
        $id = Request::getInstance()->getParam('id');
        $file = (new File())->find('id', $id)->getFirst();

        if ($file) {
            $filePath = $file['file_path'] . $file['system_name'];

            if (file_exists($filePath)) {
                // Динамически определяем MIME-тип изображения
                $mimeType = mime_content_type($filePath) ?: 'image/png';
                header('Content-Type: ' . $mimeType);
                header('Content-Length: ' . filesize($filePath));

                // Использование readfile() вместо file_get_contents() для экономии памяти
                readfile($filePath);
                exit;
            }
        }

        // Если файл или запись не найдены
        header('HTTP/1.1 404 Not Found');
        exit;
    }
}