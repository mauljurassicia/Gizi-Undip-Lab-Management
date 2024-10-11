<?php

namespace App\Enums;

use Symfony\Component\HttpFoundation\Response;

enum ResponseCodeEnum
{
    case STATUS_OK;

    case STATUS_CREATED;

    case STATUS_BAD_REQUEST;

    case STATUS_UNPROCESSABLE_ENTITY;

    case STATUS_NOT_FOUND;

    case STATUS_FORBIDEN;

    case STATUS_UNATENTICATED;

    case STATUS_INTERNAL_SERVER_ERROR;

    public function getCode(): int
    {
        return match ($this) {
            ResponseCodeEnum::STATUS_OK => Response::HTTP_OK,

            ResponseCodeEnum::STATUS_CREATED => Response::HTTP_CREATED,

            ResponseCodeEnum::STATUS_BAD_REQUEST => Response::HTTP_BAD_REQUEST,

            ResponseCodeEnum::STATUS_UNPROCESSABLE_ENTITY => Response::HTTP_UNPROCESSABLE_ENTITY,

            ResponseCodeEnum::STATUS_NOT_FOUND => Response::HTTP_NOT_FOUND,

            ResponseCodeEnum::STATUS_FORBIDEN => Response::HTTP_FORBIDDEN,

            ResponseCodeEnum::STATUS_UNATENTICATED => Response::HTTP_UNAUTHORIZED,

            ResponseCodeEnum::STATUS_INTERNAL_SERVER_ERROR => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }
}
