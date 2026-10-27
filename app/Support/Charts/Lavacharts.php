<?php

namespace App\Support\Charts;

/**
 * Sustituye a khill/lavacharts (incompatible con Laravel 13) para el uso
 * de la portada: Lava::DataTable(), Lava::PieChart() y Lava::render().
 * Dibuja los mismos gráficos con Google Charts.
 */
class Lavacharts
{
    /** @var array */
    private $charts = [];

    /** @var bool */
    private $loaderRendered = false;

    /**
     * @return \App\Support\Charts\DataTable
     */
    public function DataTable()
    {
        return new DataTable;
    }

    /**
     * @param  string  $label
     * @param  \App\Support\Charts\DataTable  $data
     * @param  array  $options
     */
    public function PieChart($label, DataTable $data, array $options = [])
    {
        $this->charts['PieChart'][$label] = ['data' => $data, 'options' => $options];
    }

    /**
     * @param  string  $type
     * @param  string  $label
     * @param  string  $elementId
     * @return string
     */
    public function render($type, $label, $elementId)
    {
        $chart = $this->charts[$type][$label] ?? null;
        if ($chart === null) {
            return '';
        }

        $output = '';
        if (! $this->loaderRendered) {
            $output .= '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>'."\n";
            $output .= '<script type="text/javascript">google.charts.load("current", {packages: ["corechart"]});</script>'."\n";
            $this->loaderRendered = true;
        }

        $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        $data = json_encode($chart['data']->toArray(), $flags);
        $options = json_encode($chart['options'], $flags);
        $elementId = json_encode($elementId, $flags);
        $type = json_encode($type, $flags);

        $output .= <<<HTML
<script type="text/javascript">
google.charts.setOnLoadCallback(function () {
    var element = document.getElementById({$elementId});
    var chart = new google.visualization[{$type}](element);
    var data = new google.visualization.DataTable({$data});
    var draw = function () { chart.draw(data, {$options}); };
    draw();
    window.addEventListener('resize', draw);
});
</script>
HTML;

        return $output;
    }
}
