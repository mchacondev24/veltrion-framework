<?php

namespace Veltrion\Services\UIUX\Enums;

enum ChartType: string
{
    case BAR = 'bar';
    case LINE = 'line';
    case AREA = 'area';
    case PIE = 'pie';
    case DONUT = 'donut';
    case SCATTER = 'scatter';
    case RADAR = 'radar';
    case GAUGE = 'gauge';
    case HEATMAP = 'heatmap';
    case KPI = 'kpi';
}
