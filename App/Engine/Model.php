<?php

namespace App\Engine;

/**
 * Базовый класс модели (Active Record / Query Builder Wrapper)
 */
abstract class Model
{
    /**
     * Первичный ключ таблицы
     */
    protected string $primaryKey = 'id';

    /**
     * Имя таблицы в БД
     */
    protected string $table;

    /**
     * Разрешенные для заполнения поля таблицы (white-list)
     */
    protected array $fields = [];

    /**
     * Поля, скрываемые из итоговых выборок (например, пароли, токены)
     */
    protected array $hiddenFields = [];

    /**
     * Экземпляр строителя запросов DB
     */
    protected ?DB $query = null;

    /**
     * Конструктор модели
     *
     * @param array|string|null $select Список колонок для выборки
     */
    public function __construct(array|string $select = null)
    {
        $this->query = new DB();
        $this->query->limit(100);

        if (isset($this->table)) {
            $this->query->from($this->table);
        }

        if (!is_null($select)) {
            if (is_string($select)) {
                $this->query->select($this->table . '.' . $select);
            } else {
                $this->query->select($select);
            }
        }
    }

    /**
     * Получение текущего экземпляра строителя запросов DB
     */
    public function query(): DB
    {
        return $this->query;
    }

    /**
     * Сброс и создание нового запроса для текущей таблицы
     */
    public function newQuery(): static
    {
        $this->query->clear();
        $this->query->from($this->table);
        return $this;
    }

    /**
     * Сохранение записи (автоматически определяет INSERT или UPDATE)
     *
     * @param array $data
     * @return array|false Возвращает сохраненный массив данных или false
     */
    public function save(array $data): array|bool
    {
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $data = $this->filterAllowedFields($data);
        if (empty($data)) {
            return false;
        }

        // Если передан первичный ключ — выполняем UPDATE
        if (isset($data[$this->primaryKey])) {
            $updated = $this->query
                ->where($this->primaryKey, $data[$this->primaryKey], '=')
                ->update($this->table, $data);

            return $updated ? $data : false;
        }

        // Иначе выполняем INSERT
        return $this->insert($data);
    }

    /**
     * Вставка одной записи в базу данных
     */
    public function insert(array $data): array|bool
    {
        $data = $this->filterAllowedFields($data);
        if (empty($data)) {
            return false;
        }
        dd(  $this->query );
        $insertId = $this->query->insertGetIncriment($this->table, $data);

        if (!$insertId) {
            return false;
        }

        $data[$this->primaryKey] = $insertId;
        return $data;
    }

    /**
     * Множественная вставка записей
     */
    public function insertMultiple(array $data): bool
    {
        return $this->query->insertMultiple($this->table, $data);
    }

    /**
     * Обновление записей по текущему условию WHERE
     */
    public function update(array $data): array|bool
    {
        $data = $this->filterAllowedFields($data);
        if (empty($data)) {
            return false;
        }

        $updated = $this->query->update($this->table, $data);
        return $updated ? $data : false;
    }

    /**
     * Удаление записей по текущему условию WHERE
     */
    public function delete(): mixed
    {
        return $this->query->delete($this->table);
    }

    /**
     * Получение первой записи из выборки
     */
    public function getFirst(): ?array
    {
        $result = $this->query->getOne();
        if ($result && !empty($this->hiddenFields)) {
            return $this->removeHiddenFields($result);
        }
        return $result ?: null;
    }

    /**
     * Получение списка всех записей
     */
    public function getList(): array
    {
        $result = $this->query->get();
        if (!$result) {
            return [];
        }

        if (!empty($this->hiddenFields)) {
            // Удаляем скрытые поля рекурсивно для массива результатов
            return removeKeysRecursive($result, $this->hiddenFields);
        }

        return $result;
    }

    /**
     * Добавление условия WHERE
     */
    public function where(string $field, mixed $value, string $operation = '=', string $type = 'AND'): static
    {
        $this->query->where($field, $value, $operation, $type);
        return $this;
    }

    /**
     * Поиск записи с проверкой существования поля
     */
    public function find(string $field, mixed $value): static|bool
    {
        if (!in_array($field, $this->fields, true)) {
            return false;
        }

        $this->query->where($field, $value);
        return $this;
    }

    /**
     * Поиск всех записей (псевдоним для find)
     */
    public function findAll(string $field, mixed $value): static|bool
    {
        return $this->find($field, $value);
    }

    /**
     * Установка лимита строк
     */
    public function setLimit(int|string $limit): static
    {
        $this->query->limit($limit);
        return $this;
    }

    /**
     * Установка текущей страницы пагинации
     */
    public function setPage(int $page): static
    {
        $page = max(1, $page);
        $limit = $this->query->getLimit() ?: 100;

        $this->query->offset(($page - 1) * $limit);
        return $this;
    }

    /**
     * Получение информации для пагинации
     */
    public function getPagination(): array
    {
        $total = $this->query->count();
        $limit = $this->query->getLimit() ?: 1; // Защита от деления на 0
        $offset = $this->query->getOffset();

        return [
            'total'     => $total,
            'page'      => (int)($offset / $limit) + 1,
            'pageSize'  => $limit,
            'countPage' => (int)ceil($total / $limit),
        ];
    }

    /**
     * Возвращает список разрешенных полей таблицы
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Возвращает имя таблицы
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Фильтрация входящих данных: оставляем только поля из $this->fields
     */
    protected function filterAllowedFields(array $data): array
    {
        if (empty($this->fields)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fields));
    }

    /**
     * Удаление скрытых полей из одного элемента (строки)
     */
    protected function removeHiddenFields(array $data): array
    {
        return array_diff_key($data, array_flip($this->hiddenFields));
    }
}