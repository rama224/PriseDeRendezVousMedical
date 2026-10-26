<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Levée lorsqu'une réservation enfreint une règle métier.
 * Le message est destiné à être affiché au patient.
 */
class ReservationImpossible extends RuntimeException
{
}
