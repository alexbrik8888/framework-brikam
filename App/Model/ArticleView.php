<?php

namespace App\Model;

use App\Engine\Model;

/**
 * Модель для учета и обновления количества просмотров статей.
 */
class ArticleView extends Model
{
    /** @var string Название таблицы в БД */
    protected string $table = 'article_views';

    /** @var string Первичный ключ таблицы */
    protected string $primaryKey = 'article_id';

    /** @var array<string> Разрешенные поля для заполнения */
    protected array $fields = [
        'article_id',
        'view'
    ];

    /**
     * Сохраняет или обновляет количество просмотров статьи (UPSERT).
     *
     * @param array $data Данные для сохранения (должны содержать articl_id)
     * @return array|bool Возвращает массив сохраненных данных или false в случае ошибки
     */
    public function save(array $data): array|bool
    {
        // Фильтруем входящие данные, оставляя только разрешенные поля из $this->fields
        $data = array_intersect_key($data, array_flip($this->fields));

        if (empty($data) || !isset($data[$this->primaryKey])) {
            return false;
        }

        $articleId = $data[$this->primaryKey];

        // Пробуем обновить существующую запись
        $updatedRows = $this->query
            ->where($this->primaryKey, $articleId, '=')
            ->update($this->table, $data);

        if ($updatedRows === false) {
            return false;
        }

        // Если ни одна строка не обновилась — запись отсутствует, создаем новую
        if ($updatedRows === 0) {
            $inserted = $this->query->insert($this->table, $data);
            if ($inserted === false) {
                return false;
            }
        }

        return $data;
    }
}