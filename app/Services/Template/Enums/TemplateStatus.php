<?php
namespace Veltrion\Services\Template\Enums;

enum TemplateStatus: string
{
    CASE STABLE = 'stable';
    CASE BETA = 'beta';
    CASE EXPERIMENTAL = 'experimental';
    CASE DEPRECATED = 'deprecated';
}
