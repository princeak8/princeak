<?php

namespace App\Enums;

enum Status: string
    {
        case PUBLISHED = 'published';
        case DRAFT = 'draft';
        case ARCHIVED = 'archived';
        case HIDDEN = 'hidden';
    }