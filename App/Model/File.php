<?php

namespace App\Model;

use App\Engine\Config;
use App\Engine\Model;
use Throwable;

/**
 * Модель для работы с физическими файлами и их реестром в БД (таблица `file`).
 */
class File extends Model
{
    /** Идентификаторы типов файлов */
    public const TYPE_IMAGE    = 1;
    public const TYPE_VIDEO    = 2;
    public const TYPE_AUDIO    = 3;
    public const TYPE_DOCUMENT = 4;
    public const TYPE_OTHER    = 5;

    /**
     * Карта соответствия типов файлов их текстовым директориям
     * @var array<string, int>
     */
    private array $typeMap = [
        'img'      => self::TYPE_IMAGE,
        'video'    => self::TYPE_VIDEO,
        'audio'    => self::TYPE_AUDIO,
        'document' => self::TYPE_DOCUMENT,
        'file'     => self::TYPE_OTHER,
    ];

    /** @var string Название таблицы в БД */
    protected string $table = 'files';

    /** @var string Относительная папка хранилища на диске */
    protected string $dirStorage = '';

    /** @var array<string> Разрешенные поля таблицы */
    protected array $fields = [
        'id',
        'file_name',
        'system_name',
        'extension',
        'file_path',
        'type_id',
        'abstract_id',
        'is_deleted',
        'created_at',
        'updated_at'
    ];



    public function __construct(array|string $select = null)
    {
        $this->dirStorage = Config::getInstance()->getConfig('storage_file');
        $rootDir = Config::getInstance()->getConfig('root_dir');
        $check  =  rtrim($rootDir, '/\\') . DIRECTORY_SEPARATOR . $this->dirStorage . DIRECTORY_SEPARATOR ;
        if (!is_dir( $check) && !mkdir( $check, 0755, true) && !is_dir($check)) {
            return false;
        }
        parent::__construct($select);
    }
    /**
     * Сохраняет загруженный файл на диск и записывает метаданные в БД.
     *
     * @param array $data Массив данных файла, содержащий:
     *                    - 'name' или 'file' (оригинальное имя)
     *                    - 'tmp_name' (временный путь на сервере)
     *                    - 'type_id' (ID типа файла)
     *                    - 'abstract_id' (ID связанной сущности)
     * @return array|bool Возвращает сохраненный массив данных из БД или false в случае ошибки
     */
    public function save(array $data): array|bool
    {
        try {
            // Проверяем наличие всех необходимых данных
            $fileName = $data['name'] ?? $data['file'] ?? null;
            $tmpName  = $data['tmp_name'] ?? null;
            $typeId   = $data['type_id'] ?? null;
            $abstractId = $data['abstract_id'] ?? 0;

            if (!$fileName || !$tmpName || !$typeId) {
                return false;
            }

            // Находим имя директории по ID типа
            $typeFolder = array_search((int)$typeId, $this->typeMap, true);
            if ($typeFolder === false) {
                $typeFolder = 'file'; // Фоллбек-директория
            }

            // Формируем абсолютный путь к папке назначения
            $rootDir = Config::getInstance()->getConfig('root_dir');
            $uploadDir = rtrim($rootDir, '/\\') . DIRECTORY_SEPARATOR .
                $this->dirStorage . DIRECTORY_SEPARATOR .
                $typeFolder . DIRECTORY_SEPARATOR;

            // Вычисляем метаданные файла
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $systemName = sprintf('%d_%d_%s.%s', $typeId, $abstractId, uniqid('', true), $extension);

            // Создаем директорию, если она не существует
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                return false;
            }

            $destination = $uploadDir . $systemName;

            // Перемещаем файл (поддержка HTTP POST загрузок и локальных перемещений)
            $isMoved = is_uploaded_file($tmpName)
                ? move_uploaded_file($tmpName, $destination)
                : rename($tmpName, $destination);

            if ($isMoved) {
                // Подготавливаем массив для сохранения в БД
                $data['file_path']   = $uploadDir;
                $data['file_name']   = $fileName;
                $data['extension']   = $extension;
                $data['system_name'] = $systemName;

                return parent::save($data);
            }
        } catch (Throwable $e) {
            // Логирование ошибки при необходимости
            error_log('File save error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Удаляет файл с диска и запись из БД.
     *
     * @return bool Успешность удаления
     */
    public function delete(): bool
    {
        // Получаем список файлов, попадающих под условия текущего запроса
        $files = $this->getList();

        // Если в БД запись успешно удалилась (или пометилась is_deleted)
        $isDeletedInDb = parent::delete();

        if ($isDeletedInDb) {
            foreach ($files as $file) {
                if (!empty($file['file_path']) && !empty($file['system_name'])) {
                    $fullPath = $file['file_path'] . $file['system_name'];

                    // Удаляем файл с физического диска
                    if (file_exists($fullPath)) {
                        @unlink($fullPath);
                    }
                }
            }
        }

        return $isDeletedInDb;
    }
}