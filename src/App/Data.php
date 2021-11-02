<?php

namespace Template\App;

use Exception;
use JsonException;

class Data
{
    protected $file;

    protected $data = [];

    /**
     * @throws JsonException
     */
    public function __construct(string $file)
    {
        $this->file = $file;
        if (!file_exists($file)) {
            touch($file);
            $this->save();
        }

        $contents = file_get_contents($file);
        $this->data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    }

    public function get(): array
    {
        return $this->data;
    }

    /**
     * @throws JsonException
     */
    public function remove(string $id): void
    {
        unset($this->data[$id]);
        $this->save();
    }

    public function getEntryById(string $id): ?array
    {
        return $this->data[$id] ?? null;
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function update(string $id, array $data): void
    {
        if (!isset($this->data[$id])) {
            throw new Exception('Unable to update data entry, id not found');
        }
        $this->add($data);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function add(array $data): void
    {
        if (!isset($data['id'])) {
            throw new Exception('Id not set in data');
        }
        $this->data[$data['id']] = $data;
        $this->save();
    }

    /**
     * @throws JsonException
     */
    protected function save(): void
    {
        file_put_contents($this->file, json_encode($this->data, JSON_THROW_ON_ERROR));
    }
}
