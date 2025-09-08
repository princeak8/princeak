<?php

namespace App;

use App\Enums\Status;
use App\Enums\Role;
use App\Enums\FileType;

class EnumClass
{
    public static function statuses()
    {
        return [
            Status::ARCHIVED->value,
            Status::DRAFT->value,
            Status::HIDDEN->value,
            Status::PUBLISHED->value
        ];
    }

    public static function roles()
    {
        return [
            Role::ADMIN->value,
            Role::USER->value
        ];
    }

    public static function fileTypes()
    {
        return [
            FileType::CSV->value,
            FileType::DOC->value,
            FileType::DOCX->value,
            FileType::IMAGE->value,
            FileType::PDF->value,
            FileType::VIDEO->value,
            FileType::XLS->value
        ];
    }
}