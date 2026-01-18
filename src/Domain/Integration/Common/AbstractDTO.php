<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Common;

abstract class AbstractDTO implements DTOInterface
{
    /**
     * @return array
     * Возвращает ассоциативный массив ключ => значение
     * только для тех полей, которые возвращает fields().
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->fields() as $key => $outKey) {
            if (is_int($key)) {
                $property = $outKey;
            } else {
                $property = $key;
            }

            $value = $this->{$property};

            if ($value instanceof DTOInterface) {
                $data[$outKey] = $value->toArray();

            } elseif (is_array($value)) {
                $data[$outKey] = array_map(
                    fn($item) => $item instanceof DTOInterface
                        ? $item->toArray()
                        : $item,
                    $value
                );
            } else {
                $data[$outKey] = $value;
            }
        }

        return $data;
    }

    /**
     * @return string[]
     */
    abstract protected function fields(): array;
}
