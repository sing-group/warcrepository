<?php

namespace App\Support\Charts;

/**
 * Tabla de datos compatible con la de khill/lavacharts para gráficos de Google Charts.
 */
class DataTable
{
    /** @var array */
    private $cols = [];

    /** @var array */
    private $rows = [];

    /**
     * @param  string  $label
     * @return $this
     */
    public function addStringColumn($label)
    {
        $this->cols[] = ['type' => 'string', 'label' => $label];

        return $this;
    }

    /**
     * @param  string  $label
     * @return $this
     */
    public function addNumberColumn($label)
    {
        $this->cols[] = ['type' => 'number', 'label' => $label];

        return $this;
    }

    /**
     * @param  array  $values
     * @return $this
     */
    public function addRow(array $values)
    {
        $this->rows[] = ['c' => array_map(fn ($value) => ['v' => $value], array_values($values))];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['cols' => $this->cols, 'rows' => $this->rows];
    }
}
