<?php

namespace Veltrion\Services\UIUX\Enums;

enum UIStateType: string
{
    case INITIAL = 'initial';
    case LOADING = 'loading';
    case LOADED = 'loaded';
    case EMPTY = 'empty';
    case ERROR = 'error';
    case UNAUTHORIZED = 'unauthorized';
    case FORBIDDEN = 'forbidden';
    case OFFLINE = 'offline';
    case SAVING = 'saving';
    case SAVED = 'saved';
    case DELETING = 'deleting';
    case DELETED = 'deleted';
    case VALIDATION_ERROR = 'validation_error';
}
