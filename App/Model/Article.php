<?php

namespace App\Model;

use App\Engine\DbExceptionSQL;
use App\Engine\Model;

/**
 * Модель для работы со статьями (таблица `articl`).
 * Содержит связи с изображениями, категориями, просмотрами и логику агрегации SQL.
 */
class Article extends Model
{
    /** @var int Идентификатор типа файла "Изображение" */
    private const FILE_TYPE_IMAGE = 1;

    /** @var string Название таблицы в БД */
    protected string $table = 'articles';

    /** @var array<string> Поля таблицы */
    protected array $fields = [
        'id', 'name', 'text', 'description', 'published_at', 'created_at', 'updated_at'
    ];

    /**
     * Присоединяет статистику просмотров статьи (с подстановкой 0, если просмотров нет).
     *
     * @return $this
     */
    public function connectArticleView(): self
    {
        $this->Query()->joinLeft('article_views', "{$this->table}.id = article_views.article_id");
        $this->Query()->select('IFNULL(article_views.view, 0) as "view"');

        return $this;
    }

    /**
     * Присоединяет к статье список связанных изображений в виде JSON-массива.
     *
     * @return $this
     */
    public function connectImage(): self
    {
        $typeImage = self::FILE_TYPE_IMAGE;

        $joinSql = "(
            SELECT 
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', files.id,
                        'file_name', files.file_name,
                        'system_name', files.system_name,
                        'extension', files.extension,
                        'file_path', files.file_path
                    )
                ) AS image, 
                files.abstract_id, 
                files.type_id   
            FROM files
            GROUP BY files.abstract_id, files.type_id
        ) AS aricle_file ON aricle_file.abstract_id = {$this->table}.id AND aricle_file.type_id = {$typeImage}";

        $this->Query()->joinLeft(new DbExceptionSQL($joinSql));
        $this->Query()->select('aricle_file.image');

        return $this;
    }

    /**
     * Присоединяет к статье список категорий в виде JSON-массива.
     *
     * @return $this
     */
    public function connectCategory(): self
    {
        $joinSql = "(
            SELECT 
                JSON_ARRAYAGG(
                    JSON_OBJECT(
                        'id', category_article.id,
                        'category_id', category_article.category_id, 
                        'name', c.name        
                    )
                ) AS category, 
                category_article.article_id
            FROM category_article  
            LEFT JOIN categories AS c ON category_article.category_id = c.id 
            GROUP BY category_article.article_id
        ) AS aricle_category ON aricle_category.article_id = {$this->table}.id";

        $this->Query()->joinLeft(new DbExceptionSQL($joinSql));
        $this->Query()->select('aricle_category.category');

        return $this;
    }

    /**
     * Простые связи с файлом изображения (без сборки JSON).
     *
     * @return $this
     */
    public function connectImageSimple(): self
    {
        $typeImage = self::FILE_TYPE_IMAGE;
        $this->Query()->joinLeft('files', "{$this->table}.id = files.abstract_id AND files.type_id = {$typeImage}");
        $this->Query()->select('files.id as "file_id"');

        return $this;
    }

    /**
     * Возвращает список статей с автоматически декодированными JSON-полями.
     *
     * @return array<int, array>
     */
    public function getList(): array
    {
        $result = parent::getList();

        foreach ($result as &$article) {
            $article = $this->decodeJsonFields($article);
        }

        return $result;
    }

    /**
     * Возвращает первую статью из выборки с декодированными JSON-полями.
     *
     * @return array
     */
    public function getFirst(): array
    {
        $result = parent::getFirst();

        if (empty($result)) {
            return [];
        }

        return $this->decodeJsonFields($result);
    }

    /**
     * Выбирает до 3 последних статей для каждой категории с помощью CTE и ROW_NUMBER().
     * Возвращает сгруппированный массив статей и список названий категорий.
     *
     * @return array{group: array<int, array>, group_name: array<int, string>}
     */
    public function getLastThreeArticleCategories(): array
    {
        $sql = "
            WITH RankedArticles AS (
                SELECT 
                    c.id AS category_id,
                    c.name AS category_name,
                    a.id AS article_id,
                    a.description AS article_description,
                    a.name AS article_title,
                    f.id AS file_id,
                    a.created_at,
                    -- Нумеруем статьи отдельно для каждой категории по дате от новых к старым
                    ROW_NUMBER() OVER (
                        PARTITION BY c.id 
                        ORDER BY a.created_at DESC, a.id DESC
                    ) AS rn
                FROM categories c
                JOIN category_article  ac ON c.id = ac.category_id
                JOIN articles a ON ac.article_id = a.id
                LEFT JOIN files f ON ac.article_id = f.abstract_id AND f.type_id = " . self::FILE_TYPE_IMAGE . "
            )
            SELECT 
                category_id,
                category_name,
                article_id,
                article_title,
                article_description,
                created_at,
                file_id
            FROM RankedArticles
            WHERE rn <= 3 
            ORDER BY category_id, created_at DESC
        ";

        $result = $this->newQuery()->Query()->statment($sql);
        $group = [];
        $groupName = [];

        foreach ($result as $item) {
            $catId = $item['category_id'];

            // Группируем статьи по ID категории
            $group[$catId][] = $item;

            // Запоминаем название категории
            $groupName[$catId] = $item['category_name'];
        }

        // Сортируем названия категорий по алфавиту
        asort($groupName);

        return [
            'group' => $group,
            'group_name' => $groupName,
        ];
    }

    /**
     * Вспомогательный метод декодирования JSON-строк (image, category) в массив.
     *
     * @param array $article Массив данных статьи
     * @return array
     */
    protected function decodeJsonFields(array $article): array
    {
        if (isset($article['image']) && is_string($article['image'])) {
            $article['image'] = json_decode($article['image'], true) ?? [];
        }

        if (isset($article['category']) && is_string($article['category'])) {
            $article['category'] = json_decode($article['category'], true) ?? [];
        }

        return $article;
    }
}