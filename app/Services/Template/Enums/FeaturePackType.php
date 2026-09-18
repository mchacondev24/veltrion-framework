<?php
namespace Veltrion\Services\Template\Enums;

enum FeaturePackType: string
{
    CASE AUTH = 'auth';
    CASE DATABASE = 'database';
    CASE ANALYTICS = 'analytics';
    CASE PAYMENTS = 'payments';
    CASE I18N = 'i18n';
    CASE OFFLINE_SYNC = 'offline-sync';
}
