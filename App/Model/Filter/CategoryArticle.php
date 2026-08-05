<?php

namespace App\Model\Filter;

use App\Engine\Model;

/**
 * Модель фильтрации связи категорий и статей.
 * Отвечает за построение сложных SQL-запросов (JOIN) и декодирование JSON-полей.
 */
class CategoryArticle extends Model
{
    /** @var int Идентификатор типа файла "Изображение" */
    private const FILE_TYPE_IMAGE = 1;

    /** @var string Имя таблицы в БД */
    protected string $table = 'category_article';

    /** @var array<string> Список доступных полей таблицы */
    protected array $fields = ['id', 'category_id', 'article_id'];

    /**
     * Присоединяет таблицу файлов (изображений) к запросу.
     *
     * @return $this
     */
    public function connectImageArticle(): self
    {
        $this->Query()->joinLeft(
            'files',
            "{$this->table}.article_id = files.abstract_id AND files.type_id = " . self::FILE_TYPE_IMAGE
        );
        $this->Query()->select('files.id as "file_id"');

        return $this;
    }

    /**
     * Присоединяет основную таблицу статей и отсекает записи без статьи.
     *
     * @return $this
     */
    public function connectArticle(): self
    {
        $this->Query()->joinLeft('articles', "{$this->table}.article_id = articles.id");
        $this->Query()->whereISNOTNULL('articles.id');
        $this->Query()->select('articles.*');

        return $this;
    }

    /**
     * Присоединяет статистику просмотров статьи (с подстановкой 0, если просмотров нет).
     *
     * @return $this
     */
    public function connectArticleView(): self
    {
        $this->Query()->joinLeft('article_views', "{$this->table}.article_id = article_views.article_id");
        $this->Query()->select('IFNULL(article_views.view, 0) as "view"');

        return $this;
    }

    /**
     * Возвращает список записей с декодированными JSON-полями.
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
     * Возвращает первую запись выборки с декодированными JSON-полями.
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
     * Вспомогательный метод для декодирования JSON-строк (image, category) в массив.
     *
     * @param array $item Массив данных статьи
     * @return array
     */
    protected function decodeJsonFields(array $item): array
    {
        if (isset($item['image']) && is_string($item['image'])) {
            $item['image'] = json_decode($item['image'], true) ?? [];
        }

        if (isset($item['category']) && is_string($item['category'])) {
            $item['category'] = json_decode($item['category'], true) ?? [];
        }

        return $item;
    }
}