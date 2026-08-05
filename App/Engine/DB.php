<?php

namespace App\Engine;

use PDOStatement;

/**
 * Класс-обертка для передачи сырого SQL-выражения в Query Builder.
 */
class DbExceptionSQL {
    /** @var string SQL-запрос */
    public string $sql = "";

    /**
     * @param string $sql Сырой SQL-текст
     */
    public function __construct(string $sql) {
        $this->sql = $sql;
    }
}

/**
 * Класс Query Builder для работы с базой данных через PDO.
 * Реализует паттерны Singleton и Fluent Interface.
 */
class DB {

    /** @var \PDO|null Экземпляр подключения PDO */
    private static ?\PDO $pdo = null;

    /** @var array|null Конфигурация подключения */
    private $config = null;

    /** @var mixed */
    private $dbObj;

    /** @var array Список полей для SELECT */
    protected array $select = [];

    /** @var array Список таблиц для FROM */
    protected array $from = [];

    /** @var array Условия JOIN */
    protected array $join = [];

    /** @var array Условия WHERE */
    protected array $where = [];

    /** @var array Группировка GROUP BY */
    protected array $group = [];

    /** @var array Условия HAVING */
    protected array $having = [];

    /** @var array Сортировка ORDER BY */
    protected array $order = [];

    /** @var int|string|null Ограничение количества строк (LIMIT) */
    protected int|string|null $limit = null;

    /** @var int|string|null Смещение выборки (OFFSET) */
    protected int|string|null $offset = null;

    /** @var array Привязанные параметры для PDO (Prepared Statements) */
    protected array $bindings = [];

    /** @var string Итоговый сформированный SQL-запрос */
    protected string $strQuery = '';

    /** @var static|null Единственный экземпляр класса (Singleton) */
    protected static $inst = null;

    /**
     * Получить единственный экземпляр класса (Singleton).
     *
     * @return self
     */
    public static function getInstance(): self {
        if (self::$inst == null) {
            self::$inst = new self();
        }
        return self::$inst;
    }

    /**
     * Конструктор класса. Инициализирует соединение с БД через PDO.
     *
     * @param string|null $table Имя таблицы по умолчанию для секции FROM
     */
    public function __construct(string $table = null) {
        $this->config = Config::getInstance()->getConfig('db');
        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=%s",
            $this->config['host'],
            $this->config['dbname'],
            $this->config['charset'] ?? 'utf8mb4'
        );
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        self::$pdo = new \PDO($dsn, $this->config['user'], $this->config['pass'], $options);

        if (!is_null($table)) {
            $this->from($table);
        }
    }

    /**
     * Деструктор класса.
     */
    public function __destruct() {
        // self::$pdo = null;
    }

    /**
     * Приватный метод для сбора SELECT запроса (альтернативная сборка).
     *
     * @return void
     */
    private function builderSelect(): void {
        $select = (!empty($this->select)) ? $this->select : ['*'];
        $where  = (!empty($this->where)) ? implode(' ', $this->where) : '1=1';
        $group  = (!empty($this->group)) ? (' GROUP BY ' . implode(' , ', $this->group)) : '';
        $having = (!empty($this->having)) ? (' HAVING ' . implode(' ', $this->having)) : '';
        $order  = (!empty($this->order)) ? (' ORDER BY ' . implode(' ', $this->order)) : '';
        $limit  = (!empty($this->limit)) ? (' LIMIT ' . $this->limit) : '';
        $offset = (!empty($this->offset)) ? (' OFFSET ' . $this->offset) : '';

        $this->strQuery = sprintf(
            "SELECT %s FROM %s %s WHERE %s %s %s %s %s %s",
            implode(' , ', $select),
            implode(' , ', $this->from),
            implode(' ', $this->join),
            $where, $group, $having, $order, $limit, $offset
        );
    }

    /**
     * Проверяет существование таблицы в базе данных.
     *
     * @param string $table Имя таблицы
     * @return bool
     */
    public function tableExists(string $table): bool {
        $stmt = self::$pdo->prepare("SELECT COUNT(*) 
                                     FROM information_schema.tables 
                                     WHERE table_schema = DATABASE() 
                                    AND table_name = :table_name");
        $stmt->execute(['table_name' => $table]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Проверяет, есть ли записи, соответствующие текущему запросу.
     *
     * @return bool
     */
    public function Exists(): bool {
        return (bool) $this->count();
    }

    /**
     * Обертка для создания сырых SQL-выражений.
     *
     * @param string $sql Сырой SQL-текст
     * @return DbExceptionSQL
     */
    public static function raw(string $sql): DbExceptionSQL {
        return new DbExceptionSQL($sql);
    }

    /**
     * Указывает поля для выборки (SELECT).
     *
     * @param string|array $select Поле или массив полей
     * @return self
     */
    public function select(string|array $select): self {
        if (is_string($select)) {
            $this->select[] = $select;
        }
        if (is_array($select)) {
            $this->select = array_merge($this->select, $select);
        }
        return $this;
    }

    /**
     * Указывает таблицу(-ы) для выборки (FROM).
     *
     * @param string|array $from Таблица или массив таблиц
     * @return self
     */
    public function from(string|array $from): self {
        if (is_string($from)) {
            $this->from[] = $from;
        }
        if (is_array($from)) {
            $this->from = array_merge($this->from, $from);
        }
        return $this;
    }

    /**
     * Базовый метод присоединения таблиц (JOIN).
     *
     * @param string|DbExceptionSQL $table Таблица или сырое SQL-выражение
     * @param string|array $on Условие связывания ON
     * @param string $type Тип соединения (INNER, LEFT, RIGHT и т.д.)
     * @return self
     * @throws \Exception Если имя таблицы пустое или неверный формат массива условий
     */
    protected function join(string|DbExceptionSQL $table, string|array $on = '', string $type = 'INNER'): self {
        if (empty($table)) {
            throw new \Exception('Table is empty');
        }

        if (!empty($on)) {
            if (is_array($on)) {
                if (array_key_exists($on[0], ['field', 'type', 'operator', 'value'])) {
                    $where = '';
                    for ($i = 0; $i < count($on); $i++) {
                        $where = sprintf(
                            " %s %s %s '%s' ",
                            (count($on) > 1 && $i > 1) ? $on[$i]['type'] : '',
                            $on[$i]['field'],
                            $on[$i]['value'],
                            $on[$i]['operator']
                        );
                    }
                } else {
                    throw new \Exception("Формат массива неверен [['field'=>'','type'=>'','operator'=>'','value'=>''],...]");
                }
            }
            $this->join[] = sprintf('%s JOIN %s ON %s ', $type, $table, $on);
            return $this;
        }

        if (is_string($table)) {
            $this->join[] = sprintf('%s JOIN %s ', $type, $table);
            return $this;
        }

        if ($table instanceof DbExceptionSQL) {
            $this->join[] = $type . " JOIN " . $table->sql;
            return $this;
        }

        return $this;
    }

    /**
     * Добавляет LEFT JOIN.
     * @param mixed $table Таблица
     * @param string|array $where Условия ON
     * @return self
     */
    public function joinLeft($table, $where = ''): self {
        return $this->join($table, $where, 'LEFT');
    }

    /**
     * Добавляет RIGHT JOIN.
     * @param mixed $table Таблица
     * @param string|array $where Условия ON
     * @return self
     */
    public function joinRight($table, $where = ''): self {
        return $this->join($table, $where, 'RIGHT');
    }

    /**
     * Добавляет CROSS JOIN.
     * @param mixed $table Таблица
     * @param string|array $where Условия ON
     * @return self
     */
    public function joinCross($table, $where = ''): self {
        return $this->join($table, $where, 'CROSS');
    }

    /**
     * Добавляет INNER JOIN.
     * @param mixed $table Таблица
     * @param string|array $where Условия ON
     * @return self
     */
    public function joinInner($table, $where = ''): self {
        return $this->join($table, $where, 'INNER');
    }

    /**
     * Вспомогательный метод для формирования условий WHERE с плейсхолдерами PDO.
     *
     * @param string $field Имя поля
     * @param mixed $value Значение для фильтрации
     * @param string $operator Оператор сравнения (=, >, <, LIKE)
     * @param string $type Логический оператор (AND / OR)
     * @return string
     */
    protected function where_(string $field, mixed $value, string $operator = '=', string $type = 'and'): string {
        $type = strtoupper($type);
        $prefix = empty($this->where) ? '' : "{$type} ";
        $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
        $this->bindings[$paramName] = $value;
        return "{$prefix}{$field} {$operator} {$paramName}";
    }

    /**
     * Добавляет базовое условие WHERE.
     *
     * @param string $field Имя поля
     * @param mixed $value Значение
     * @param string $operator Оператор (=, >, <)
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function where(string $field, mixed $value, string $operator = '=', string $type = 'and'): self {
        $this->where[] = $this->where_($field, $value, $operator, $type);
        return $this;
    }

    /**
     * Добавляет массив условий WHERE.
     *
     * @param array $whereArr Массив условий формата [['field' => '...', 'value' => '...', 'operator' => '=', 'type' => 'and']]
     * @return self
     * @throws \Exception
     */
    public function whereMultiple(array $whereArr): self {
        if (is_array($whereArr) && array_diff_key($whereArr[0], ['field' => 1, 'value' => 1, 'operator' => 1, 'type' => 1]) == 0) {
            foreach ($whereArr as $where) {
                $this->where[] = $this->where_($where['field'], $where['value'], $where['operator'], $where['type']);
            }
        } else {
            throw new \Exception("Формат массива неверен [['field'=>'','type'=>'','operator'=>'','value'=>''],...]");
        }
        return $this;
    }

    /**
     * Добавляет условие IS NULL.
     *
     * @param string $field Имя поля
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereISNULL(string $field, string $type = 'and'): self {
        $this->where[] = sprintf(" %s %s IS NULL ", (count($this->where) >= 1) ? $type : '', $field);
        return $this;
    }

    /**
     * Добавляет условие IS NOT NULL.
     *
     * @param string $field Имя поля
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereISNOTNULL(string $field, string $type = 'and'): self {
        $this->where[] = sprintf(" %s %s IS NOT NULL ", (count($this->where) >= 1) ? $type : '', $field);
        return $this;
    }

    /**
     * Добавляет условие WHERE IN (...).
     *
     * @param string $field Имя поля
     * @param array $value Массив значений
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereIN(string $field, array $value, string $type = 'and'): self {
        $inParams = [];
        foreach ($value as $item) {
            $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
            $inParams[] = $paramName;
            $this->bindings[$paramName] = $item;
        }
        $this->where[] = sprintf(" %s %s IN ( %s ) ", (count($this->where) >= 1) ? $type : '', $field, implode(' , ', $inParams));
        return $this;
    }

    /**
     * Добавляет условие WHERE NOT IN (...).
     *
     * @param string $field Имя поля
     * @param array $value Массив значений
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereNOT_IN(string $field, array $value, string $type = 'and'): self {
        $inParams = [];
        foreach ($value as $item) {
            $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
            $inParams[] = $paramName;
            $this->bindings[$paramName] = $item;
        }
        $this->where[] = sprintf(" %s %s NOT IN ( %s ) ", (count($this->where) >= 1) ? $type : '', $field, implode(' , ', $inParams));
        return $this;
    }

    /**
     * Добавляет условие BETWEEN v1 AND v2.
     *
     * @param string $field Имя поля
     * @param mixed $value1 Левая граница
     * @param mixed $value2 Правая граница
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereBetween(string $field, mixed $value1, mixed $value2, string $type = 'and'): self {
        $val1 = ':' . str_replace('.', '_', $field) . '_b1_' . count($this->bindings);
        $val2 = ':' . str_replace('.', '_', $field) . '_b2_' . (count($this->bindings) + 1);
        $this->bindings[$val1] = $value1;
        $this->bindings[$val2] = $value2;
        $this->where[] = sprintf(" %s %s BETWEEN %s AND %s ", (count($this->where) >= 1) ? $type : '', $field, $val1, $val2);
        return $this;
    }

    /**
     * Добавляет условие NOT BETWEEN v1 AND v2.
     *
     * @param string $field Имя поля
     * @param mixed $value1 Левая граница
     * @param mixed $value2 Правая граница
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereNotBetween(string $field, mixed $value1, mixed $value2, string $type = 'and'): self {
        $val1 = ':' . str_replace('.', '_', $field) . '_b1_' . count($this->bindings);
        $val2 = ':' . str_replace('.', '_', $field) . '_b2_' . (count($this->bindings) + 1);
        $this->bindings[$val1] = $value1;
        $this->bindings[$val2] = $value2;
        $this->where[] = sprintf(" %s %s NOT BETWEEN %s AND %s ", (count($this->where) >= 1) ? $type : '', $field, $val1, $val2);
        return $this;
    }

    /**
     * Добавляет сырое условие WHERE из объекта DbExceptionSQL.
     *
     * @param DbExceptionSQL $sql
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function whereRaw(DbExceptionSQL $sql, string $type = 'and'): self {
        if ($sql instanceof DbExceptionSQL) {
            $this->where[] = ((count($this->where) >= 1) ? $type : '') . $sql->sql;
        }
        return $this;
    }

    /**
     * Добавляет группировку GROUP BY.
     *
     * @param string|array $field Поле или массив полей
     * @return self
     */
    public function group(string|array $field): self {
        $this->group = (!is_array($field)) ? [$field] : $field;
        return $this;
    }

    /**
     * Добавляет условие HAVING для сгруппированных данных.
     *
     * @param string $field Имя поля
     * @param mixed $value Значение
     * @param string $operator Оператор
     * @param string $type Логическая связь (AND/OR)
     * @return self
     */
    public function having(string $field, mixed $value, string $operator = '=', string $type = 'and'): self {
        $type = strtoupper($type);
        $prefix = empty($this->having) ? '' : "{$type} ";
        $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
        $this->bindings[$paramName] = $value;
        $this->having[] = "{$prefix}{$field} {$operator} {$paramName}";
        return $this;
    }

    /**
     * Добавляет сырое условие HAVING.
     *
     * @param string $sql
     * @param string $type
     * @return self
     */
    public function havingRaw(string $sql, string $type = 'and'): self {
        if (is_string($sql)) {
            $this->having[] = ((count($this->having) > 1) ? $type : '') . $sql;
        }
        return $this;
    }

    /**
     * Добавляет сортировку ORDER BY.
     *
     * @param string $field Поле сортировки
     * @param string $sort Направление (asc/desc)
     * @return self
     */
    public function order(string $field, string $sort = 'asc'): self {
        $this->order[] = sprintf(" %s %s", $field, $sort);
        return $this;
    }

    /**
     * Устанавливает LIMIT выборки.
     *
     * @param int|string $limit
     * @return self
     */
    public function limit(int|string $limit): self {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Устанавливает OFFSET (смещение) выборки.
     *
     * @param int|string $offset
     * @return self
     */
    public function offset(int|string $offset): self {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Выполняет SELECT-запрос и возвращает массив всех найденных строк.
     *
     * @return array
     */
    public function get(): array {
        $stmt = $this->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt = null;
        return $result;
    }

    /**
     * Возвращает ровно одну строку по запросу.
     *
     * @return array|bool
     */
    public function getOne(): array|bool {
        $this->limit(1);
        $stmt = $this->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt = null;
        return $result;
    }

    /**
     * Возвращает общее количество строк (COUNT(*)), подходящих под условие.
     *
     * @return int
     */
    public function count(): int {
        $tempSelect = $this->select;
        $tempLimit = $this->limit;
        $tempOffset = $this->offset;

        $this->limit = null;
        $this->offset = null;
        $this->select = ['COUNT(*) as \'count\''];

        $stmt = $this->execute();
        $result = $stmt->fetchColumn(0);

        // Восстановление прежнего состояния Builder
        $this->select = $tempSelect;
        $this->limit = $tempLimit;
        $this->offset = $tempOffset;
        $stmt = null;

        return ((int)$result ?? 0);
    }

    /**
     * Выполняет подготовку и исполнение собранного SQL-запроса.
     *
     * @return PDOStatement
     */
    protected function execute(): PDOStatement {
        $this->getQueryString();
        $stmt = self::$pdo->prepare($this->strQuery);
        $stmt->execute($this->bindings);
        return $stmt;
    }

    /**
     * Генерирует строку SQL-запроса из переданных компонентов Query Builder.
     *
     * @return self
     */
    public function getQueryString(): self {
        $selectField = empty($this->select) ? '*' : implode(' , ', $this->select);
        $fromElement = empty($this->from) ? '' : implode(' , ', $this->from);

        $this->strQuery = "SELECT {$selectField} FROM {$fromElement}";

        if (!empty($this->join))
            $this->strQuery .= ' ' . implode(' ', $this->join);
        if (!empty($this->where))
            $this->strQuery .= " WHERE " . implode(' ', $this->where);
        if (!empty($this->group))
            $this->strQuery .= " GROUP BY " . implode(' , ', $this->group);
        if (!empty($this->having))
            $this->strQuery .= " HAVING " . implode(' ', $this->having);
        if (!empty($this->order))
            $this->strQuery .= " ORDER BY " . implode(' , ', $this->order);
        if (!empty($this->limit))
            $this->strQuery .= " LIMIT " . $this->limit;
        if (!empty($this->offset))
            $this->strQuery .= " OFFSET " . $this->offset;

        return $this;
    }

    /**
     * Вставляет одну запись в указанную таблицу.
     *
     * @param string $table Имя таблицы
     * @param array $data Ассоциативный массив данных ['field' => 'value']
     * @return bool
     */
    public function insert(string $table, array $data): bool {
        $field = '`' . implode('` , `', array_keys($data)) . '`';
        $pamBindings = implode(' , ', array_map(fn($k) => ":$k", array_keys($data)));

        $this->strQuery = "INSERT INTO {$table} ({$field}) VALUES ({$pamBindings})";
        $stmt = self::$pdo->prepare($this->strQuery);
        $result = $stmt->execute($data);
        $stmt = null;

        return $result;
    }

    /**
     * Вставляет запись и возвращает её сгенерированный ID (LAST_INSERT_ID).
     *
     * @param string $table Имя таблицы
     * @param array $data Ассоциативный массив данных
     * @return int|bool
     */
    public function insertGetIncriment(string $table, array $data): int|bool {
        if ($this->insert($table, $data)) {
            return (int) self::$pdo->lastInsertId();
        }
        return false;
    }

    /**
     * Заглушка: Вставка с пропуском дубликатов (INSERT IGNORE).
     */
    public function insertIgnore(string $table, array $data) {
        return $this;
    }

    /**
     * Заглушка: Вставка или обновление при совпадении ключа (ON DUPLICATE KEY UPDATE).
     */
    public function insertOrUpdate(string $table, array $data): bool {
        return true;
    }

    /**
     * Множественная вставка нескольких записей за один запрос.
     *
     * @param string $table Имя таблицы
     * @param array $data Список массивов с данными [ ['col' => 'val'], ['col' => 'val'] ]
     * @return bool
     * @throws \Exception Если передан неиндексированный массив
     */
    public function insertMultiple(string $table, array $data): bool {
        if (!array_is_list($data)) {
            throw new \Exception('Не индексированный массив');
        }

        $fields = array_keys($data[0]);
        $fieldStr = '`' . implode('` , `', $fields) . '`';

        $size = count($data);
        $countField = count($fields);
        $values = [];
        $params = [];

        for ($i = 0; $i < $size; $i++) {
            $pamBindings = array_fill(0, $countField, '?');
            $values[] = '(' . implode(' , ', $pamBindings) . ')';
            $params = array_merge($params, array_values($data[$i]));
        }

        $this->strQuery = "INSERT INTO {$table} ({$fieldStr}) VALUES " . implode(',', $values);
        $stmt = self::$pdo->prepare($this->strQuery);
        $result = $stmt->execute($params);
        $stmt = null;

        return $result;
    }

    /**
     * Запускает транзакцию базы данных (если не была запущен ранее).
     *
     * @return bool|void
     */
    public function beginTransaction() {
        if (!self::$pdo->inTransaction()) {
            return self::$pdo->beginTransaction();
        }
    }

    /**
     * Фиксирует (коммитит) текущую транзакцию.
     *
     * @return bool|void
     */
    public function commit() {
        if (self::$pdo->inTransaction()) {
            return self::$pdo->commit();
        }
    }

    /**
     * Отменяет (откатывает) текущую транзакцию.
     *
     * @return bool|void
     */
    public function rollback() {
        if (self::$pdo->inTransaction()) {
            return self::$pdo->rollBack();
        }
    }

    /**
     * Обновляет данные в таблице на основе текущих условий WHERE.
     *
     * @param string $table Имя таблицы
     * @param array $data Данные для обновления
     * @return int|bool Количество измененных строк или false
     */
    public function update(string $table, array $data): int|bool {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = '" . addslashes($value) . "'";
        }

        $setStr = implode(' , ', $set);
        $this->strQuery = "UPDATE {$table} SET {$setStr} ";

        if (!empty($this->where)) {
            $this->strQuery .= "WHERE " . implode(' ', $this->where);
        }

        $stmt = self::$pdo->prepare($this->strQuery);
        $result = $stmt->execute($this->bindings);

        if ($result) {
            $result = $stmt->rowCount();
        }

        $stmt = null;
        return $result;
    }

    /**
     * Удаляет записи из таблицы на основе текущих условий WHERE.
     *
     * @param string $table Имя таблицы
     * @return bool
     */
    public function delete(string $table): bool {
        $this->strQuery = "DELETE FROM {$table} ";

        if (!empty($this->where)) {
            $this->strQuery .= "WHERE " . implode(' ', $this->where);
        }

        $stmt = self::$pdo->prepare($this->strQuery);
        $result = $stmt->execute($this->bindings);
        $stmt = null;

        return $result;
    }

    /**
     * Выполняет произвольный SQL-запрос с параметрами и возвращает результаты.
     *
     * @param string $sql SQL-запрос
     * @param array $params Подготавливаемые параметры
     * @return array
     */
    public function statment(string $sql, array $params = []): array {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Получить текущее значение LIMIT.
     * @return int|string|null
     */
    public function getLimit(): int|string|null {
        return $this->limit;
    }

    /**
     * Получить текущее значение OFFSET.
     * @return int|string|null
     */
    public function getOffset(): int|string|null {
        return $this->offset;
    }

    /**
     * Сбрасывает состояние Query Builder к первоначальному виду.
     *
     * @return self
     */
    public function clear(): self {
        $this->select = [];
        $this->from = [];
        $this->join = [];
        $this->where = [];
        $this->group = [];
        $this->having = [];
        $this->order = [];
        $this->limit = null;
        $this->offset = null;
        $this->bindings = [];
        return $this;
    }
}