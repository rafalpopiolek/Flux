<?php

declare(strict_types=1);

namespace App\Trait\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

trait FormResponseStatus
{
    public function setResponseStatus(FormInterface $form): int
    {
        return match ($form->isSubmitted() && !$form->isValid()) {
            true => Response::HTTP_UNPROCESSABLE_ENTITY,
            default => Response::HTTP_OK,
        };
    }
}
